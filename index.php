<?php
session_start();

require('./vendor/autoload.php');

require('./configs/config.php');

$route = require('./utils/router.php');

$controllerName = 'Controllers\\' . $route['controller'];

$controller = new $controllerName();

$data = call_user_func([$controller, $route['callback']]);

extract($data, EXTR_OVERWRITE);

require($view);

$_SESSION['errors'] = [];
$_SESSION['old'] = [];

/*
 * Chaque requête est caractérisée par :
 * — une méthode [GET - POST]
 * — une action [lister/créer/éditer/sauvegarder/supprimer]
 * — ressource [team/match]
 * */
