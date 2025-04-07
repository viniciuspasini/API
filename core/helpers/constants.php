<?php

define("BASE_PATH", dirname(__DIR__, 2));
const VIEW_PATH = BASE_PATH . '/resources/views';
const VIEW_PATH_CORE = BASE_PATH . '/core/resources/views/errors';
define("REQUEST_URI", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
