<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use Ecommerce\Common\Exceptions\ProductInventoryExceededException;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function store(
        StoreOrderRequest $request,
        CreateOrderAction $action
    ) {
        try {
            $order = $action->execute(
                Product::where('uuid', $request->getProductId())->first(),
                $request->getQuantity(),
            );
            return new OrderResource($order);
        } catch (ProductInventoryExceededException $ex) {
            return response([
                'errors' => ['quantity' => $ex->getMessage()]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
