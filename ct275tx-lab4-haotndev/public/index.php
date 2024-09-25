<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../bootstrap.php';

define('APPNAME', 'My Phonebook');

session_start();
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$router = new \Bramus\Router\Router();

// Auth routes
$router->post('/logout', '\App\Controllers\Auth\LoginController@destroy');
$router->get('/register', '\App\Controllers\Auth\RegisterController@create');
$router->post('/register', '\\App\Controllers\Auth\RegisterController@store');
$router->get('/login', '\App\Controllers\Auth\LoginController@create');
$router->post('/login', '\App\Controllers\Auth\LoginController@store');

// Contact routes
$router->get('/', '\App\Controllers\ContactsController@index');
$router->get('/home', '\App\Controllers\ContactsController@index');

// Create contact route
$router->get(
  '/contacts/add',
  '\App\Controllers\ContactsController@create'
);
$router->post(
  '/contacts',
  '\App\Controllers\ContactsController@store'
);

// Edit contact route
$router->get(
  '/contacts/edit/(\d+)',
  '\App\Controllers\ContactsController@edit'
);
$router->post(
  '/contacts/(\d+)',
  '\App\Controllers\ContactsController@update'
);

// Delete contact route
$router->post(
  '/contacts/delete/(\d+)',
  '\App\Controllers\ContactsController@destroy'
);

$router->set404('\App\Controllers\Controller@sendNotFound');
$router->run();
