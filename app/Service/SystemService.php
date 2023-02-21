<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class SystemService
{
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    #[Inject]
    protected EventDispatcherInterface $dispatcher;

    public function flushCache($userId): bool
    {
        $this->dispatcher->dispatch(new DeleteListenerEvent('user-update', ['userId' => $userId]));

        return true;
    }
}
