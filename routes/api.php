<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route
::controller(AuthController::class)
->prefix('auth')
->group(function ($unauthorized) {
    $unauthorized->post('login' , 'login' );
    $unauthorized->post('signup', 'signup');
    $unauthorized->middleware('token.auth')->group(function ($authorized) {
        $authorized->get('logout', 'logout');
    });
});

Route
::controller(UserController::class)
->prefix('users')
->group(function ($users) {
    $users->middleware('token.auth:user' )->patch('', 'editSelf');
    $users->middleware('token.auth:admin')->group(function ($usersManage) {
        $usersManage->post('', 'create' );
        $usersManage->get ('', 'showAll');
        $usersManage->prefix('{id}')->group(function ($userManage) {
            $userManage->get   ('', 'show'  )->where('id', '[0-9]+');
            $userManage->patch ('', 'edit'  )->where('id', '[0-9]+');
            $userManage->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(RoomController::class)
->prefix('rooms')
->group(function ($rooms) {
    $rooms->get('', 'showAll');
    $rooms->get('types', 'showTypes');
    $rooms->middleware('token.auth:admin')->post('', 'create');
    $rooms->prefix('{id}')->group(function ($room) {
        $room->get('', 'show')->where('id', '[0-9]+');
        $room->middleware('token.auth:admin')->group(function ($roomManage) {
            $roomManage->post  ('edit', 'edit'  )->where('id', '[0-9]+');
            $roomManage->delete(''    , 'delete')->where('id', '[0-9]+');
        });
        $room
        ->controller(ReservationController::class)
        ->prefix('reserve')
        ->middleware('token.auth')
        ->group(function ($reserve) {
            $reserve->post  ('', 'createSelf')->where('id', '[0-9]+');
            $reserve->delete('', 'deleteSelf')->where('id', '[0-9]+');
        });
    });
    $rooms
    ->controller(RoomTypesController::class)
    ->prefix('types')
    ->group(function ($roomTypes) {
        $roomTypes->get('', 'showAll');
        $roomTypes->middleware('token.auth:admin')->post('', 'create');
        $roomTypes
        ->prefix('{id}')
        ->middleware('token.auth:admin')
        ->group(function ($type) {
            $type->get   ('', 'show'  )->where('id', '[0-9]+');
            $type->patch ('', 'edit'  )->where('id', '[0-9]+');
            $type->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(ReservationController::class)
->prefix('reservations')
->middleware('token.auth:admin')
->group(function ($reservations) {
    $reservations->get ('', 'showAll');
    $reservations->post('', 'create' );
    $reservations->prefix('{id}')->group(function ($reservation) {
        $reservation->get   ('', 'show'  )->where('id', '[0-9]+');
        $reservation->patch ('', 'edit'  )->where('id', '[0-9]+');
        $reservation->delete('', 'delete')->where('id', '[0-9]+');
    });
});

