<?php

namespace core\library;

use core\exceptions\ClassNotFoundException;
use core\exceptions\ViewNotFoundException;
use core\interfaces\TemplateEngineInterface;
use League\Plates\Engine;

class Template
{
    private static TemplateEngineInterface $templateEngine;

    /**
     * @throws \Exception
     */
    public static function render(string $view, array $data = [], $viewPath = VIEW_PATH)
    {
        $templateEngine = resolve('engineTemplate');

        if(!class_exists($templateEngine)){
            throw new ClassNotFoundException('Template engine "'.$templateEngine.'" not found');
        }

        self::$templateEngine = new $templateEngine;

        if (!self::$templateEngine instanceof TemplateEngineInterface) {
            throw new ClassNotFoundException('Template engine "'.$templateEngine.'" must be an instance of TemplateEngineInterface');
        }

        return self::$templateEngine->render($view, $data, $viewPath);
    }
}