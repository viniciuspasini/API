<?php

namespace core\templatesEngine;

use core\exceptions\ViewNotFoundException;
use core\interfaces\TemplateEngineInterface;
use League\Plates\Engine;

class Plates implements TemplateEngineInterface
{
    public function render(string $view, array $data, string $viewPath)
    {
        if (!file_exists($viewPath . '/' . $view . '.php')) {
            throw new ViewNotFoundException("View does not exist: " . $view);
        }

        $templates = new Engine($viewPath);
        return $templates->render($view, $data);
    }

}