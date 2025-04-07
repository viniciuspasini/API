<?php

namespace core\library;

class Request
{
    public function __construct(
        public readonly array $server,
        public readonly array $get,
        public readonly array $post,
        public readonly array $session,
        public readonly array $cookies,
        public readonly array $headers,
        public readonly array $body,
        public readonly false|string $authorizationToken,
    )
    {
    }

    public static function create(): static
    {
        return new static(
            $_SERVER,
            $_GET,
            $_POST,
            $_SESSION,
            $_COOKIE,
            getallheaders(),
            json_decode(file_get_contents('php://input') , true) ?? [],
            self::getAuthorizationToken()
        );
    }

    public static function getAuthorizationToken(): array|string
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) return false;

        $authorizationParts = explode(' ', $headers['Authorization']);

        if (count($authorizationParts) != 2) return false;

        return $authorizationParts[1] ?? '';
    }
}