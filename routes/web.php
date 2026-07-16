<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SalesHeadController;
use App\Http\Controllers\DeliveryBoyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () { return redirect()->route('login'); })->name('home');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'ur'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return back();
})->name('lang.switch');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/members', [AdminController::class, 'members'])->name('members');
        Route::get('/members/create', [AdminController::class, 'createMember'])->name('members.create');
        Route::post('/members', [AdminController::class, 'storeMember'])->name('members.store');
        Route::get('/members/{member}/edit', [AdminController::class, 'editMember'])->name('members.edit');
        Route::put('/members/{member}', [AdminController::class, 'updateMember'])->name('members.update');
        Route::delete('/members/{member}', [AdminController::class, 'destroyMember'])->name('members.destroy');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('reports.export');
        Route::get('/blocked', [AdminController::class, 'blockedDeliveries'])->name('blocked');
        Route::post('/blocked/{user}/{route}/unblock', [AdminController::class, 'unblockDelivery'])->name('blocked.unblock');
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/routes/{route}', [AdminController::class, 'viewRoute'])->name('routes.view');
    });

    Route::middleware('role:saleshead')->prefix('saleshead')->name('saleshead.')->group(function () {
        Route::get('/dashboard', [SalesHeadController::class, 'dashboard'])->name('dashboard');
        Route::get('/members', [SalesHeadController::class, 'members'])->name('members');
        Route::get('/reports', [SalesHeadController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [SalesHeadController::class, 'exportReport'])->name('reports.export');
        Route::get('/routes/{route}', [SalesHeadController::class, 'viewRoute'])->name('routes.view');
    });

    Route::middleware('role:deliveryboy')->prefix('deliveryboy')->name('deliveryboy.')->group(function () {
        Route::get('/dashboard', [DeliveryBoyController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-routes', [DeliveryBoyController::class, 'myRoutes'])->name('my-routes');
        Route::get('/routes/create', [DeliveryBoyController::class, 'createRoute'])->name('routes.create');
        Route::post('/routes', [DeliveryBoyController::class, 'storeRoute'])->name('routes.store');
        Route::post('/routes/{route}/status/{status}', [DeliveryBoyController::class, 'updateRouteStatus'])->name('routes.status');
        Route::post('/routes/{route}/complete', [DeliveryBoyController::class, 'completeRoute'])->name('routes.complete');
        Route::post('/routes/{route}/pit-stop', [DeliveryBoyController::class, 'addPitStop'])->name('routes.pitstop');
        Route::get('/routes/{route}/pit-stops', [DeliveryBoyController::class, 'getPitStops'])->name('routes.pitstops');
        Route::get('/routes/{route}/edit', [DeliveryBoyController::class, 'editRoute'])->name('routes.edit');
        Route::put('/routes/{route}', [DeliveryBoyController::class, 'updateRoute'])->name('routes.update');
        Route::put('/routes/{route}/pit-stop/{pitStop}', [DeliveryBoyController::class, 'updatePitStop'])->name('routes.pitstop.update');
        Route::delete('/routes/{route}/pit-stop/{pitStop}', [DeliveryBoyController::class, 'deletePitStop'])->name('routes.pitstop.delete');
    });

    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/broadcast-recording', [ChatController::class, 'broadcastRecording'])->name('chat.broadcast-recording');
    Route::get('/chat/recording-status', [ChatController::class, 'recordingStatus'])->name('chat.recording-status');

    // Notifications
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
