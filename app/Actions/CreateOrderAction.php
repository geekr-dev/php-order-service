<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use App\Services\RedisService;
use App\Services\WarehouseService;
use Ecommerce\Common\Exceptions\ProductInventoryExceededException;

class CreateOrderAction
{
    public function __construct(
        private readonly WarehouseService $warehouseService,
        private readonly RedisService $redis,
    ) {
    }

    public function execute(
        Product $product,
        int $quantity
    ): Order {
        $inventory = $this->warehouseService->getTotalInventory($product);
        if ($quantity > $inventory) {
            throw new ProductInventoryExceededException(
                "There is not enough $product->name in inventory"
            );
        }

        $order = Order::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $product->price * $quantity,
        ]);
        $this->redis->publishOrderCreated($order->toData());
        return $order;
    }
}
