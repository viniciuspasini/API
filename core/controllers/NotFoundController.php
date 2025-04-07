<?php

namespace core\controllers;

use core\library\Response;

class NotFoundController
{
    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        return response(statusCode: 404, headers: [
            'Content-Type' => 'application/json',
        ])->json([
            'error' => true,
            'success' => false,
            'message' => '404 Not Found',
        ]);
    }
}