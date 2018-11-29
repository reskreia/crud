<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth', 'middleware' => ['activity']], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');

    });

    $api->group(['middleware' => ['jwt.auth', 'activity'], 'namespace' => 'App\Api\V1\Controllers'], function(Router $api) {

        // check user
        $api->get('check', 'UserController@getAuthUser');

        // pelayan atau kasir melihat order active
        $api->get('item/list/active', 'ManageItem@listItemActive');
        
        // list order finish
        $api->get('item/list/finish', 'ManageItem@listItemFinish');
        
        // all item
        $api->get('item/all', 'ManageItem@allItem');

        // pelayan menambah orderan
        $api->post('order/add', 'Pelayan\ManageOrder@newOrder');
        
        // pelayan / kasir edit orderan
        $api->post('order/edit', 'Pelayan\ManageOrder@editOrder');

        // delete order item
        $api->post('order/item/delete', 'Pelayan\ManageOrder@deleteOrderItem');
        
        // delete order
        $api->post('order/delete', 'Pelayan\ManageOrder@deleteOrder');

        // kasir tutup order
        $api->post('order/pay', 'Kasir\ManageOrder@upOrder');
    });

    $api->group(['namespace' => 'App\Api\V1\Controllers'], function(Router $api) {

        // tambah menu / item
        $api->post('add/item', 'ManageItem@add');
    });

});
