<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\CartStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CartStoreRequest;
use App\Services\Api\V1\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_token' => ['required', 'string', 'exists:user_sessions,token']
        ]);
        $user_token = cleanUp($request->input('user_token'));

        $cart = new CartService($user_token);

        return [
            'items'             => $cart->cartItems,
            'payment'           => [
                'subtotal' => $cart->subTotal(),
            ],
            'promotion'         => $cart->promotion,
            'awaited_promotion' => $cart->awaited_promotion
        ];
    }

    public function store(CartStoreRequest $request, CartStoreAction $action): JsonResponse
    {
        return $action($request);
    }
}