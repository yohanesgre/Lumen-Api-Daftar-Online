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

$router->group(['prefix'=>'api/'], function($router){
   $router->post('login', 'LoginController@login'); 
   $router->post('register', 'RegisterController@Register'); 

   // ---------------------------------- PROFILE -----------------------------

   $router->get('profile', 'ProfileController@UserGetProfile'); 
   $router->post('profile/store', 'ProfileController@Store'); 
   
   // ---------------------------------- BEROBAT -----------------------------

   $router->get('berobat', 'BerobatController@UserGetAllBerobat'); 
   //$router->get('berobat/{id}', 'BerobatController@getUserBerobatByID'); 
   $router->post('berobat/reservasi', 'BerobatController@UserGetBerobatByReservasi'); 
   $router->post('berobat/store', 'BerobatController@Store');
   $router->post('berobat/riwayat', 'RiwayatController@UserGetRiwayatByNomerIdBerobat');
   //Admin Only
   $router->post('berobat/riwayat/store/{user_id}', 'RiwayatController@Store');
   
   // ---------------------------------- ADMIN -----------------------------

   $router->get('admin/get_all_user', 'ProfileController@AdminGetAllUser');
   $router->post('admin/edit_profile/{id}', 'ProfileController@Store');

   $router->post('admin/get_user_by_norm', 'ProfileController@AdminSearchByNoRM');
   $router->post('admin/get_user_by_nik', 'ProfileController@AdminSearchByNIK');
   $router->post('admin/get_user_by_nama', 'ProfileController@AdminSearchByNama');
   $router->post('admin/get_user_by_email', 'ProfileController@AdminSearchByEmail');

   $router->get('admin/get_all_berobat', 'BerobatController@AdminGetAllBerobat');
   $router->post('admin/get_all_berobat_tgl', 'BerobatController@AdminGetAllBerobatByTgl');
   $router->get('admin/berobat/{id}', 'BerobatController@AdminGetUserBerobatById');
   $router->post('admin/berobat/get_berobat_by_reserasi', 'BerobatController@AdminGetBerobatByReservasi');
   $router->post('admin/berobat/delete', 'BerobatController@AdminDeleteBerobatByReservasi');

   $router->delete('berobat/riwayat/delete/{berobat_id}', 'RiwayatController@AdminDeleteRiwayatByNomerIdBerobat');
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});
