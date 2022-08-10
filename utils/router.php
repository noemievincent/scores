<?php
$routes = require('./configs/routes.php');

$method = $_SERVER['REQUEST_METHOD'];//GET POST
$methodName = '_'.$method;//_GET _POST
$action = $$methodName['action'] ?? '';
$resource = $$methodName['resource'] ?? '';

$route = array_filter($routes, static function ($r) use ($method, $action, $resource) {
    return $r['method'] === $method
        && $r['action'] === $action
        && $r['resource'] === $resource;
});

if (!$route) {
    header('Location: index.php');
    exit();
}

return reset($route);