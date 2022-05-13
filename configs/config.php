<?php

use Carbon\Carbon;

define('DB_PATH', $_SERVER['DOCUMENT_ROOT'].'/data/scores.sqlite');
define('TODAY',
    Carbon::now('Europe/Brussels')
        ->locale('fr_BE')
        ->isoFormat('dddd DD MMMM YYYY'));
const THUMBS = './assets/images/thumbs/';
const LOGO = './assets/images/full/';

$data = [];
$view = 'vue.php';
