<?php

define('ROOTDIR', realpath(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('APPNAME', 'My Phonebook');
define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once ROOTDIR.'vendor/autoload.php';
require_once ROOTDIR.'db.php';

session_start();

use \App\Router;

// Error handlers
register_shutdown_function([new \App\Controllers\Controller(), 'internalServerError']);
Router::error('\App\Controllers\Controller@notFound');

// Auth routes
Router::post('/logout', '\App\Controllers\Auth\LoginController@logout');
Router::get('/register', '\App\Controllers\Auth\RegisterController@showRegisterForm');
Router::post('/register', '\\App\Controllers\Auth\RegisterController@register');
Router::get('/login', '\App\Controllers\Auth\LoginController@showLoginForm');
Router::post('/login', '\App\Controllers\Auth\LoginController@login');

// Contact routes
Router::get('/', '\App\Controllers\ContactsController@index');
Router::get('/home', '\App\Controllers\ContactsController@index');
Router::get('/contacts', '\App\Controllers\ContactsController@index');
Router::get('/contacts/add', '\App\Controllers\ContactsController@add');
Router::post('/contacts', '\App\Controllers\ContactsController@create');
Router::get('/contacts/edit/(:num)', '\App\Controllers\ContactsController@edit');
Router::post('/contacts/(:num)', '\App\Controllers\ContactsController@update');
Router::post('/contacts/delete/(:num)', '\App\Controllers\ContactsController@delete');

// Contact APIs
Router::get(
    '/api/v1/contacts/(:num)',
    '\App\Controllers\ContactsApiController@getContactById'
);

Router::post('/api/v1/contacts', '\App\Controllers\ContactsApiController@create');
Router::post('/api/v1/contacts/edit/(:num)', '\App\Controllers\ContactsApiController@edit');
Router::post('/api/v1/contacts/delete/(:num)', '\App\Controllers\ContactsApiController@delete');

Router::get('/api/v1/contacts/', '\App\Controllers\ContactsApiController@getListContacts');
Router::get('/api/v1/contacts', '\App\Controllers\ContactsApiController@findContact');


Router::dispatch();
