<?php

use core\library\Container;
use core\library\Response;
use core\library\Template;

/**
 * @throws Exception
 */
function view($view, $data = [], $viewPath = VIEW_PATH)
{
    return Template::render($view, $data, $viewPath);
}

function bind(string $key, mixed $value): void
{
    Container::bind($key, $value);
}

function resolve(string $key): ?string
{
    return Container::resolve($key);
}

function response(string $content = '', int $statusCode = 200, array $headers = []): Response
{
    return new Response($content, $statusCode, $headers);
}


