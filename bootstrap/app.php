<?php

use core\library\App;

$app = App::create()
    ->withEnv()
    ->withTemplateEngine(\core\templatesEngine\Plates::class)
    ->withErrorPage()
    ->withContainer();

