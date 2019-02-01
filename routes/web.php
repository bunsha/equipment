<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return [
        "name" => "equipment",
        "version" => "0.1"
    ];
});
$router->post('/setup', 'EquipmentController@setupMutations');


$router->group(['prefix' => 'equipment/statuses'], function ($route) {
    $route->get('/', 'EquipmentStatusesController@index');
    $route->post('/', 'EquipmentStatusesController@store');
    $route->get('/{id}', 'EquipmentStatusesController@get');
    $route->put('/{id}', 'EquipmentStatusesController@update');
    $route->delete('/{id}', 'EquipmentStatusesController@delete');
    $route->post('/{id}/restore', 'EquipmentStatusesController@restore');
    $route->delete('/{id}/purge', 'EquipmentStatusesController@destroy');
});

$router->group(['prefix' => 'equipment/mutations'], function ($route) {
    $route->get('/', 'EquipmentMutationsController@index');
    $route->post('/', 'EquipmentMutationsController@store');
    $route->get('/{id}', 'EquipmentMutationsController@get');
    $route->put('/{id}', 'EquipmentMutationsController@update');
    $route->delete('/{id}', 'EquipmentMutationsController@delete');
    $route->post('/{id}/restore', 'EquipmentMutationsController@restore');
    $route->delete('/{id}/purge', 'EquipmentMutationsController@destroy');
});

$router->group(['prefix' => 'equipment'], function ($route) {
    $route->get('/', 'EquipmentController@index');
    $route->post('/', 'EquipmentController@store');
    $route->get('/{id}', 'EquipmentController@get');
    $route->put('/{id}', 'EquipmentController@update');
    $route->delete('/{id}', 'EquipmentController@delete');
    $route->post('/{id}/restore', 'EquipmentController@restore');
    $route->delete('/{id}/purge', 'EquipmentController@destroy');
    $route->get('/{id}/history', 'EquipmentController@getHistory');
    $route->get('/{id}/history/attached', 'EquipmentController@getAttachedHistory');
    $route->get('/{id}/history/detached', 'EquipmentController@getDetachedHistory');
    $route->post('/{id}/attach', 'EquipmentController@attach');
    $route->post('/{id}/detach', 'EquipmentController@detach');
});