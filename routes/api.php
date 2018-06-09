<?php

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
$router->post('/recover', 'AuthController@recover');
$router->post('/new-password', 'AuthController@newPassword');
$router->get('/activate-account/{id}/{token}','AuthController@active');

$router->get('/user/{id}', 'UserController@show');

$router->post('/like/{id}', ['middleware' => 'auth', 'uses' => 'UserController@like']);
$router->post('/unlike/{id}', ['middleware' => 'auth', 'uses' => 'UserController@unlike']);

$router->post('/report/{id}', ['middleware' => 'auth', 'uses' => 'UserController@report']);

$router->post('/block/{id}', ['middleware' => 'auth', 'uses' => 'UserController@block']);
$router->post('/unblock/{id}', ['middleware' => 'auth', 'uses' => 'UserController@unblock']);

$router->get('/user/{id}/edit', 'UserController@edit');
$router->put('/user', ['middleware' => 'auth', 'uses' => 'UserController@update']);
$router->put('/user/geo', ['middleware' => 'auth', 'uses' => 'UserController@updateGeo']);

$router->get('/suggestions/{id}', ['middleware' => 'auth', 'uses' => 'UserController@suggestions']);

$router->get('/tags/{query}', 'TagController@search');
$router->post('/tag', ['middleware' => 'auth', 'uses' =>'TagController@add']);
$router->delete('/tag/{id}', ['middleware' => 'auth', 'uses' => 'TagController@remove']);

$router->post('/image', ['middleware' => 'auth', 'uses' =>'ImageController@add']);
$router->delete('/image/{id}', ['middleware' => 'auth', 'uses' => 'ImageController@remove']);
$router->put('/image/{id}', ['middleware' => 'auth', 'uses' => 'ImageController@update']);

$router->post('/search', ['middleware' => 'auth', 'uses' => 'SearchController@search']);

$router->get('/notifications', ['middleware' => 'auth', 'uses' => 'NotificationController@get']);
$router->post('/notifications/read', ['middleware' => 'auth', 'uses' => 'NotificationController@read']);

$router->get('/matches', ['middleware' => 'auth', 'uses' => 'ChatController@get']);

$router->get('/messages/{id}', ['middleware' => 'auth', 'uses' => 'ChatController@getMessages']);
$router->post('/message', ['middleware' => 'auth', 'uses' => 'ChatController@storeMessage']);

