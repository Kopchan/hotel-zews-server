<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\StatsController;
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
    $users->middleware('token.auth')->group(function ($currentUser) {
        $currentUser->get ('self', 'showSelf');
        $currentUser->post('self', 'editSelf');
    });
    $users->middleware('token.auth:admin')->group(function ($usersManage) {
        $usersManage->post('', 'create' );
        $usersManage->get ('', 'showAll');
        $usersManage->prefix('{id}')->group(function ($userManage) {
            $userManage->get   ('', 'show'  )->where('id', '[0-9]+');
            $userManage->post  ('', 'edit'  )->where('id', '[0-9]+');
            $userManage->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(RoomController::class)
->prefix('rooms')
->group(function ($rooms) {
    $rooms->get('', 'showAll');
    $rooms->middleware('token.auth:admin')->post('', 'create');
    $rooms->prefix('{id}')->group(function ($room) {
        $room->get('', 'show')->where('id', '[0-9]+');
        $room->middleware('token.auth:admin')->group(function ($roomManage) {
            $roomManage->post  ('', 'edit'  )->where('id', '[0-9]+');
            $roomManage->delete('', 'delete')->where('id', '[0-9]+');
        });
        $room
        ->controller(ReservationController::class)
        ->prefix('reserve')
        ->middleware('token.auth')
        ->group(function ($reserve) {
            $reserve->post  ('', 'createSelf')->where('id', '[0-9]+');
            $reserve->delete('', 'deleteSelf')->where('id', '[0-9]+');
        });
        $room
        ->controller(ReviewController::class)
        ->prefix('review')
        ->middleware('token.auth')
        ->group(function ($review) {
            $review->post  ('', 'createSelf')->where('id', '[0-9]+');
            $review->delete('', 'deleteSelf')->where('id', '[0-9]+');
        });
    });
    $rooms
    ->controller(RoomTypeController::class)
    ->prefix('types')
    ->group(function ($roomTypes) {
        $roomTypes->get('', 'showAll');
        $roomTypes->get('{id}', 'show')->where('id', '[0-9]+');
        $roomTypes->middleware('token.auth:admin')->post('', 'create');
        $roomTypes
        ->prefix('{id}')
        ->middleware('token.auth:admin')
        ->group(function ($type) {
            $type->post  ('', 'edit'  )->where('id', '[0-9]+');
            $type->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(ReservationController::class)
->prefix('reservations')
->middleware('token.auth')
->group(function ($reserves) {
    $reserves->get('my', 'showAllSelf');
    $reserves->middleware('token.auth:admin')->group(function ($reservesManage) {
        $reservesManage->get ('', 'showAll');
        $reservesManage->post('', 'create' );
        $reservesManage->prefix('{id}')->group(function ($reserve) {
            $reserve->get   ('', 'show'  )->where('id', '[0-9]+');
            $reserve->post  ('', 'edit'  )->where('id', '[0-9]+');
            $reserve->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(NewsController::class)
->prefix('news')
->group(function ($newsList) {
    $newsList->get('', 'showAll');
    $newsList->middleware('token.auth:manager')->post('', 'create');
    $newsList->prefix('{id}')->group(function ($news) {
        $news->get('', 'show')->where('id', '[0-9]+');
        $news->middleware('token.auth:manager')->group(function ($newsManage) {
            $newsManage->post  ('', 'edit'  )->where('id', '[0-9]+');
            $newsManage->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(ReviewController::class)
->prefix('reviews')
->middleware('token.auth:admin')
->group(function ($reviews) {
    $reviews->get('', 'showAll');
    $reviews->post('', 'create');
    $reviews->prefix('{id}')->group(function ($review) {
        $review->get('', 'show')->where('id', '[0-9]+');
        $review->middleware('token.auth:manager')->group(function ($reviewManage) {
            $reviewManage->post  ('', 'edit'  )->where('id', '[0-9]+');
            $reviewManage->delete('', 'delete')->where('id', '[0-9]+');
        });
    });
});

Route
::controller(ServiceController::class)
->prefix('services')
->group(function ($services) {
    $services->get('', 'showAll');
    $services->middleware('token.auth:admin')->post('', 'create');
    $services->prefix('{id}')->group(function ($service) {
        $service->get('', 'show')->where('id', '[0-9]+');
        $service->middleware('token.auth:admin')->group(function ($servicesManage) {
            $servicesManage->post  ('', 'edit'  )->where('id', '[0-9]+');
            $servicesManage->delete('', 'delete')->where('id', '[0-9]+');
        });
        $service
        ->controller(ServiceItemController::class)
        ->prefix('items')
        ->middleware('token.auth:manager')
        ->group(function ($servicesItems) {
            $servicesItems->post('', 'create');
            $servicesItems->prefix('{itemId}')->group(function ($items) {
                $items->post  ('', 'edit'  )->where('id', '[0-9]+');
                $items->delete('', 'delete')->where('id', '[0-9]+');
            });
        });
    });
});

Route
::controller(StatsController::class)
->prefix('stats')
->middleware('token.auth:admin')
->group(function ($services) {
    $services->get('', 'show');
});
