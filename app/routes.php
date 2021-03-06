<?php

    /**/

    Route::model('media_id', 'MediaItem');

    Route::get(
        '/',
        [
            'before' => ['consumer_signup'],
            'as'     => 'home',
            'uses'   => 'PagesController@index'
        ]
    );

    Route::get(
        '/media/{slug}',
        [
            'before' => ['consumer_signup'],
            'as'     => 'media-item',
            'uses'   => 'MediaPageController@getIndex'
        ]
    );

    Route::get(
        'logout',
        [
            'before' => 'auth',
            'as'     => 'users.logout',
            'uses'   => 'UsersController@logout'
        ]
    );

    /**
     * Consumer Account Routes
     */
    Route::group(
        [
            'before' => ['consumer_signup', 'guest']
        ],
        function () {
            Route::get('users/create',
                [
                    'as'   => 'consumer.create',
                    'uses' => 'UsersController@create'
                ]
            );
            Route::post('users',
                [
                    'as' => 'consumer.create',
                    'uses' => 'UsersController@store'
                ]
            );
            Route::get('users/login',
                [
                    'as' => 'consumer.login',
                    'uses' => 'UsersController@login'
                ]
            );
            Route::post('users/login',
                [
                    'as' => 'consumer.login',
                    'uses' => 'UsersController@doLogin'
                ]
            );
            Route::get('users/confirm/{code}',
                [
                    'as' => 'consumer.confirm',
                    'uses' => 'UsersController@confirm'
                ]
            );
            Route::get('users/forgot_password',
                [
                    'as' => 'consumer.forgot_password',
                    'uses' => 'UsersController@forgotPassword'
                ]
            );
            Route::post('users/forgot_password',
                [
                    'as' => 'consumer.forgot_password',
                    'uses' => 'UsersController@doForgotPassword'
                ]
            );
            Route::get('users/reset_password/{token}',
                [
                    'as' => 'consumer.reset_password',
                    'uses' => 'UsersController@resetPassword'
                ]
            );
            Route::post('users/reset_password',
                [
                    'as' => 'consumer.reset_password',
                    'uses' => 'UsersController@doResetPassword'
                ]
            );
        }
    );


    /**
     * Media Partners Account Route
     */
    Route::group(
        [
            'before' => ['partner_signup', 'guest']
        ],
        function () {
            Route::get(
                'tenant/create',
                [
                    'as'   => 'partner.create',
                    'uses' => 'UsersController@create'
                ]
            );
            Route::post(
                'tenant',
                [
                    'as'   => 'partner.create',
                    'uses' => 'UsersController@store'
                ]
            );
            Route::get(
                'tenant/login',
                [
                    'as'   => 'partner.login',
                    'uses' => 'UsersController@login'
                ]
            );
            Route::post('tenant/login',
                [
                    'as' => 'partner.login',
                    'uses' => 'UsersController@doLogin'
                ]
            );
            Route::get('tenant/confirm/{code}',
                [
                    'as' => 'partner.confirm',
                    'uses' => 'UsersController@confirm'
                ]
            );
            Route::get('tenant/forgot_password',
                [
                    'as' => 'partner.forgot_password',
                    'uses' => 'UsersController@forgotPassword'
                ]
            );
            Route::post('tenant/forgot_password',
                [
                    'as' => 'partner.forgot_password',
                    'uses' => 'UsersController@doForgotPassword'
                ]
            );
            Route::get('tenant/reset_password/{token}',
                [
                    'as' => 'partner.reset_password',
                    'uses' => 'UsersController@resetPassword'
                ]
            );
            Route::post('tenant/reset_password',
                [
                    'as' => 'partner.reset_password',
                    'uses' => 'UsersController@doResetPassword'
                ]
            );
            Route::get('tenant',[
               'uses' => function(){
                   return Redirect::route('media-items.create');
               }
            ]);
        }
    );


    /**
     * Media Partner Dashboard Routes
     */
    Route::group(
        [
            'prefix' => 'media-partner',
            'before' => 'mediapartner_role'
        ], function () {
            Route::get(
                '/',
                [
                    'as'   => 'mediapartner-dashboard',
                    'uses' => 'MediapartnerController@dashboard'
                ]
            );
        });

    /**
     * Admin Dashboard Routes
     */
    Route::group(
        [
            'prefix' => 'gatekeeper',
            'before' => 'admin_role'
        ], function () {
            Route::get(
                '/',
                [
                    'as' => 'admin-dashboard',
                    function () {
                        echo 'Admin Dashboard';
                    }
                ]
            );
        });

    /**
     * Owner Dashboard Routes
     */
    Route::group(
        [
            'prefix' => 'manager',
            'before' => 'owner_role'
        ],
        function () {
            Route::get(
                '/',
                [
                    'as' => 'owner-dashboard',
                    function () {
                        echo 'Owner Dashboard';
                    }
                ]
            );
        }
    );

    /**
     * Media Item Resource
     */

    Route::resource('media-items', 'MediaItemsController');
    Route::resource('media-groups', 'MediaGroupController');
    Route::resource('media-partner/settings', 'MediapartnerSettingsController');

    Route::when('admin*', 'admin_role');
    Route::when('media-partner*', 'mediapartner_role');
    Route::get('php_info', function () {
        echo phpinfo();
    });

    /**
     * Buy Media Item
     */
    Route::group(['prefix' => 'buy'], function () {
        Route::post('item/{media_id}',
            ['as'   => 'charge_url',
             'uses' => 'MediaPurchaseController@postBuy'
            ]
        );
    });

    Route::get('/units/messages/new', function () {
        $token = Request::header("Authorization");

        return Response::json([[
            "messageType" => 0,
            "message"     => "Checking In"
        ], [
            "messageType" => 1,
            "message"     => "Testing Out"
        ]]);
    });

    Route::post('/units/messages/new', function () {
        return Response::json([
            "messageType" => 0,
            "message"     => "Checking In"
        ]);
    });

    Route::any('/units/login', function () {

        $token = Str::quickRandom();
        Session::put("token", $token);

        return $token;
    });