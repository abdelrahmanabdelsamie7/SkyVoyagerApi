<?php
use App\Http\Controllers\{AuthUserController,AuthAdminController};
use App\Http\Controllers\API\{HotelController,OfferController,FlightScheduleController,PopularPlaceController,OfferImageController,BookingHotelController,BookingOfferController};

Route::apiResources([
    'hotels' => HotelController::class,
    'offers' => OfferController::class,
    'offer-images' => OfferImageController::class,
    'flight-schedules' => FlightScheduleController::class,
    'popular-places' => PopularPlaceController::class,
    'booking-hotel' => BookingHotelController::class,
    'booking-offer' => BookingOfferController::class,
]);

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthUserController::class, 'register']);
    Route::post('/login', [AuthUserController::class, 'login']);
    Route::post('/logout', [AuthUserController::class, 'logout']);
    Route::get('/getaccount', [AuthUserController::class, 'getaccount']);
    Route::post('/password/forgot', [AuthUserController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthUserController::class, 'resetPassword']);
    Route::delete('/account', [AuthUserController::class, 'deleteAccount']);
    Route::get('/verify-email/{token}', [AuthUserController::class, 'verifyEmail']);
    Route::post('/resend-verification', [AuthUserController::class, 'resendVerification']);
});
Route::prefix( 'admin')->group(function () {
    Route::post('/register', [AuthAdminController::class, 'register']);
    Route::post('/login', [AuthAdminController::class, 'login']);
    Route::post('/logout', [AuthAdminController::class, 'logout']);
    Route::get('/getaccount', [AuthAdminController::class, 'getaccount']);
});

Route::match(['post', 'put', 'patch'], 'hotels/{id}', [HotelController::class, 'update']);
Route::match(['post', 'put', 'patch'], 'offers/{id}', [OfferController::class, 'update']);
Route::match(['post', 'put', 'patch'], 'popular-places/{id}', [PopularPlaceController::class, 'update']);
