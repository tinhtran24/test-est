<?php
use core\Router;

spl_autoload_register(function ($class) {
    $class = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($class)) require $class;
});

$controllerRunConfig = (new Router)->findControllerRunConfig();
if (!$controllerRunConfig) echo 'Error 404';
else {
    list($controllerClass, $actionMethod, $runArgs) = $controllerRunConfig;
    (new $controllerClass)->$actionMethod(...$runArgs);
}