<?php

namespace core\auth;

class JWT
{
    private static string $secret = 'secret-key';

    public static function generate(array $data = []): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($data);

        $base64UrlHeader = self::base64Url_encode($header);
        $base64UrlPayload = self::base64Url_encode($payload);
        $signature = self::signature($base64UrlHeader, $base64UrlPayload);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $signature;

    }

    public static function verify(String $jwt)
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) !== 3) return false;

        [$header, $payload, $signature] = $tokenParts;

        if($signature !== self::signature($header, $payload)) return false;

        return self::base64Url_decode($payload);
    }

    public static function signature(string $header, string $payload): string
    {
        $signature = hash_hmac('sha256', $header . "." .$payload, self::$secret, true);

        return self::base64Url_encode($signature);
    }

    public static function base64Url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64Url_decode(string $data)
    {

        $padding = strlen($data) % 4;

        $padding !== 0 && $data .= str_repeat('=', 4 - $padding);

        $data = strtr($data, '-_', '+/');

        return json_decode(base64_decode($data), true);
    }
}