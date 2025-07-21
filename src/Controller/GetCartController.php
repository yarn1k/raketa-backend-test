<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\View\CartView;

readonly class GetCartController
{
    public function __construct(
        public CartView $cartView,
        public CartManager $cartManager
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartManager->getCart();

        if ($cart === null) {
            return $this->json([
                'status' => 'fail',
                'message' => 'Cart not found',
            ], 404);
        }

        return $this->json([
            'status' => 'success',
            'cart' => $this->cartView->toArray($cart),
        ]);
    }

    private function json(array $data, int $status = 200): ResponseInterface
    {
        $response = new JsonResponse();
        $response->getBody()->write(json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        ));

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status);
    }
}
