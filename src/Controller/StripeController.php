<?php

namespace App\Controller;

use Exception;
use Stripe\Webhook;
use App\Entity\User;
use App\Entity\Offer;
use Stripe\StripeClient;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/stripe')]
class StripeController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        SecurityAuth $securityAuth,
        LoggerInterface $logger
    )
    {
        $this->em = $em;
        $this->security = $securityAuth;
        $this->logger = $logger;
        $this->front_url = $_ENV['FRONT_URL'];
        $this->stripe_public_key = $_ENV['STRIPE_PUBLIC_KEY'];
        $this->stripe_private_key = $_ENV['STRIPE_PRIVATE_KEY'];
        $this->stripe_hook_key = $_ENV['STRIPE_HOOK_KEY'];
        $this->stripe = new StripeClient($this->stripe_private_key);
    }

    #[Route('/payment-intent', name: 'create_payment_intent', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function createPaymentIntent(Request $request)
    {
        return false; // not used we use checkouts sessions now

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }

        $data = json_decode($request->getContent(), false);
        $offer = $this->em->getRepository(Offer::class)->find($data->offerId);

        if (!$offer) {
            throw new Exception('Cette offre n\'existe pas.', 404);
        }

        $stripe = new StripeClient($this->stripe_private_key);
        try {
            $result = $stripe->paymentIntents->create([
                'amount' => $offer->getPrice() * 100,
                'currency' => 'eur',
                'description' => $offer->getName(),
                'payment_method_types' => ['card'],
            ]);

            return $this->json([
                'client_secret' => $result->client_secret
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }

        throw new Exception('Une erreur est survenue lors de la création du paiement.', 500);
    }

    #[Route('/checkout-session', name: 'create_checkout_session', methods:['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function createCheckoutSession(Request $request)
    {
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }

        $data = json_decode($request->getContent(), false);

        // dump($data);
        // return $this->json($data);
        $offer = $this->em->getRepository(Offer::class)->find($data->offerId);

        if (!$offer) {
            throw new Exception('Cette offre n\'existe pas.', 404);
        }

        try {
            $checkout_session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $offer->getName() . ' - ' . $offer->getCoins() . ' coins',
                        ],
                        'unit_amount' => $offer->getPrice() * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->front_url + '/shop/success',
                'cancel_url' => $this->front_url + '/shop/cancel',
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }

        if ($checkout_session && $checkout_session->id) {
            $transaction = new Transaction();
            $user = $this->em->getRepository(User::class)->find($user->getId());

            $transaction->setCoins($offer->getCoins());
            $transaction->setTotal($offer->getPrice()); 
            $transaction->setUser($user);
            $transaction->setDateTransaction(new \DateTime());
            $transaction->setOffer($offer);
            $transaction->setPaymentId($checkout_session->id);
            $transaction->setStatus('pending');

            $this->em->persist($transaction);
            $this->em->flush();
            
            // $this->em->persist($user);
            // $user->setCoins($user->getCoins() + $offer->getCoins());
            // $this->em->flush();
            
            return $this->json(["id" => $checkout_session->id]);
        }

        throw new Exception('Une erreur est survenue lors de la création du paiement.', 500);
    }

    #[Route('/checkout-completed-webhook', name: 'stripe_checkout_webhook')]
    public function checkoutCompletedWebhook(Request $request)
    {
        //  checkout.session.completed

        // Utiliser ça pour debugger 
        // if (file_exists('stripe_error.log')) {
        //     file_put_contents('stripe_error.log', '');
        // }
        // file_put_contents('stripe_error.log', $e->getMessage() . PHP_EOL, FILE_APPEND);

        $payload = $request->getContent();
        $sig_header = $request->headers->get('stripe-signature');
        $endpoint_secret = $this->stripe_hook_key;
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            $this->log('KO Invalid payload, ERROR:'. $e->getMessage());
            return new Response('Invalid payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            $this->log('KO Invalid signature, ERROR:'. $e->getMessage());
            return new Response('Invalid signature', 400);
        } catch (\Exception $e) {
            $this->log('KO General Error At Event Construct, ERROR:'. $e->getMessage());
            return new Response('Unknown Error', 500);
        }

        if ($event) {
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;
                try {
                    $transaction = $this->em->getRepository(Transaction::class)->findOneBy(['payment_id' => $session->id]);
                    if ($transaction) {
                        $transaction->setStatus('completed');
                        $transaction->setPaymentIntent($session->payment_intent);
                        
                        $user = $transaction->getUser();
                        $user->setCoins($user->getCoins() + $transaction->getCoins());
    
                        $this->em->persist($transaction);
                        $this->em->persist($user);
                        $this->em->flush();
                    }
                } catch (\Exception $e) {
                    $this->log('KO General Error At Database Update, ERROR:'. $e->getMessage());
                    return new Response('Unknown Error', 500);
                }
            }
            return new Response('Webhook Received', 200);
        } else {
            $this->log('KO General Error No Event');
            return new Response('Unknown Error', 500);
        }
    }


    private function log($message)
    {
        $this->logger->log('error', $message, ['channel' => 'stripe']); // logs in var\log\stripe.log
    }
}
