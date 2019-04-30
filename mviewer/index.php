<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require __DIR__.'/../lib/array_DB.php';
require 'vendor/autoload.php';
require 'controller.php';



$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);
$app->get('/hello/{name}[/]', 'C_hello');
$app->get('/', 'C_index');
$app->get('/mail/{email_slug}[/]', 'C_email');
$app->get('/private-page/{secret_hash}[/]', 'C_private');

$app->run();