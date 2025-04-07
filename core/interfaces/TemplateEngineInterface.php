<?php

namespace core\interfaces;

interface TemplateEngineInterface
{
    public function render(string $view, array $data, string $viewPath);

}