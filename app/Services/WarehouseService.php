<?php

namespace App\Services;

use App\Models\Product;
use GuzzleHttp\Client;

class WarehouseService
{
    public function __construct(
        private Client $httpClient,
        private string $apiUrl,
    ) {
    }

    public function getTotalInventory(Product $product): int
    {
        $content = $this->httpClient
            ->get($this->apiUrl . "inventory/products/$product->id")
            ->getBody()
            ->getContents();

        $data = json_decode($content);

        return (int)$data['data']['totalInventory'];
    }
}
