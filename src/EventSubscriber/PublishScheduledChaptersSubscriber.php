<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

// Chapters are only ever read through the API, so publishing due ones at the start of each request
// behaves exactly like a real scheduler without needing a cron job.
class PublishScheduledChaptersSubscriber implements EventSubscriberInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->connection->executeStatement(
            "UPDATE chapter SET status = 'published', date_creation = publish_at, publish_at = NULL
             WHERE status = 'scheduled' AND publish_at <= :now",
            ['now' => (new \DateTime())->format('Y-m-d H:i:s')]
        );
    }
}
