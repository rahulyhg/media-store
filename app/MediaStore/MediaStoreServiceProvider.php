<?php namespace MediaStore;
use Illuminate\Support\ServiceProvider;
use User;

/**
 * Created by PhpStorm.
 * User: kaso
 * Date: 9/26/2014
 * Time: 9:36 PM
 */

class MediaStoreServiceProvider extends ServiceProvider{


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mediaRepoBind();
        $this->bootstrapStripe();
    }

    private function mediaRepoBind(){
        $this->app->bind('MediaStore\Repositories\Media\MediaRepository',function($app){
            return $app->make('MediaStore\Repositories\Media\EloquentMediaRepository');
        });
    }

    private function bootstrapStripe(){
        if(\Config::getEnvironment() == 'production'){
            User::setStripeKey('sk_live_OsGUW43gxYVn4IyxUjmDnaX7');
        }else{
            User::setStripeKey('sk_test_ywP4xbbvVzAE6vP1fABrbGdf');
        }
    }
}