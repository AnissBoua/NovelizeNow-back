# NovelizeNow

This template should help get you started developing with symfony 6.

## Recommended Setup

PHP 8

## Project Setup
Avant tout, installez les dépendances : 
```sh
composer install
```

Ensuite, il faut modifier le fichier .env avec les bonnes informations pour la connexion à la base de données, CORS et Stripe :
``` sh
FRONT_URL='http://localhost:5173'
DATABASE_URL=
CORS_ALLOW_ORIGIN=

STRIPE_PUBLIC_KEY='pk_test_from_stripe'
STRIPE_PRIVATE_KEY='sk_test_from_stripe'
STRIPE_HOOK_KEY='whsec_... From Sripe CLI (see below)'
```

Ensuite, créez la base de données et lancez les migrations :
``` sh
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```


Enfin, créez les clés JWT pour l’authentification :
```sh
php bin/console lexik:jwt:generate-keypair
```


### Stripe 
Pour tester les webhooks de Stripe en local, il faut utiliser le [Stripe CLI](https://stripe.com/docs/stripe-cli).
Après authentification dans le CLI, exécutez la commande suivante pour écouter les webhooks :
```sh
stripe listen --forward-to http://127.0.0.1:8000/api/stripe/checkout-completed-webhook
```

Les carte de test sont ici : 
| Result | Carte |
| ------ | ----- |
| Accepted | 4242424242424242 |
| Auth required | 4000002500003155 |
| Refused | 4000000000009995 |


Enfin mettre le Stripe Webhook Key dans le .env