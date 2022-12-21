<?php

namespace App\Services;

use Ecommerce\Common\DTOs\Order\OrderData;
use Ecommerce\Common\Events\Order\OrderCreatedEvent;
use Ecommerce\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function publishOrderCreated(OrderData $data)
    {
        $this->publish(new OrderCreatedEvent($data));
    }

    public function getServiceName(): string
    {
        return 'orders';
    }
}
