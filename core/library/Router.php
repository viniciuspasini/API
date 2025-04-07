<?php

namespace core\library;

use core\controllers\NotFoundController;
use core\exceptions\ControllerNotFoundException;
use DI\Container;

class Router
{
    protected array $routes = [];
    protected ?string $controller = null;
    protected string $action;
    protected array $params = [];
    private Response $response;

    public function __construct(private Container $container, private Request $request)
    {

    }

    public function add(string $method, string $uri, array $route): void
    {
        $this->routes[$method][$uri] = $route;
    }

    public function execute(): void
    {
        foreach ($this->routes as $method => $routes) {
            if($method === $this->request->server['REQUEST_METHOD']) {
                $this->handleUri($routes);
            }
        }
    }

    private function handleUri(array $routes)
    {
        foreach ($routes as $uri => $route) {

            if ($uri === $this->request->server['REQUEST_URI']) {
                [$this->controller, $this->action] = $route;
                break;
            }

            $pattern = str_replace('/', '\/', trim($uri, '/'));
            if ($uri !== '/' && preg_match("/^$pattern$/", trim($this->request->server['REQUEST_URI'], '/'), $this->params)) {
                [$this->controller, $this->action] = $route;
                unset($this->params[0]);
                break;
            }
        }
        if ($this->controller){
            return $this->handleController();
        }

        return $this->handleNotFound();
        
    }

    /**
     * @throws ControllerNotFoundException
     */
    private function handleController(): void
    {
        if(!class_exists($this->controller) || !method_exists($this->controller, $this->action)) {
            throw new ControllerNotFoundException("[$this->controller::$this->action] not found");
        }
        $controller = $this->container->get($this->controller);
        $this->response = $this->handleResponse($this->container->call([$controller, $this->action], [...$this->params]));
        $this->response->send();
    }

    /**
     * @throws \Exception
     */
    private function handleNotFound()
    {
        $this->container->get(NotFoundController::class)->index()->send();
    }

    private function handleResponse(Response|array|string $response): Response
    {

        if(is_array($response)) {
            $response = response(
                headers: ['Content-Type' => 'application/json'],
            )->json($response);
        }

        if (is_string($response)) {
            $response = response($response);
        }

        return $response;
    }
}