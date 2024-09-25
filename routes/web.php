<?php

use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\VendorController;
use App\Mail\InventoryNotification;
use App\Models\dispose;
use App\Models\inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

Route::get('/send-email', function () {
    $details = [
        'title' => 'Inventory ATK Notification',
        'user' => 'Test',
        'body' => 'https://inventoryatk.mlpmining.com/public/sign-in'
    ];

    // dd($details);

    Mail::to('naufal.mtsyurja91@gmail.com')->send(new InventoryNotification($details));

    return 'Email has been sent!';
});

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/home', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
// Route::get('sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('register');
// Route::post('sign-up', [RegisterController::class, 'store'])->middleware('guest');
Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest');
Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {
	return view('sessions.password.verify');
})->middleware('guest')->name('verify');
Route::get('/reset-password/{token}', function ($token) {
	return view('sessions.password.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('profile', [ProfileController::class, 'create'])->middleware('auth')->name('profile');
Route::post('user-profile', [ProfileController::class, 'update'])->middleware('auth');

Route::get('/user-management', [AccessController::class, 'index'])->name('user-management')->middleware('auth');
Route::get('/add_user', [AccessController::class, 'adduser'])->name('add_user')->middleware('auth');
Route::post('/store_user', [AccessController::class, 'create'])->name('store_user')->middleware('auth');
Route::delete('/destroy_user/{id}', [AccessController::class, 'destroy'])->name('destroy_user')->middleware('auth');
Route::get('/users/{id}/edit', [AccessController::class, 'edit'])->name('edit_user')->middleware('auth');
Route::put('/users/{id}', [AccessController::class, 'update'])->name('update_user')->middleware('auth');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory')->middleware('auth');
Route::get('/add_inventory', [InventoryController::class, 'addinventory'])->name('add_inventory')->middleware('auth');
Route::post('/store_inventory', [InventoryController::class, 'store'])->name('store_inventory')->middleware('auth');
Route::get('/data_in', [InventoryController::class, 'repair'])->name('data_in')->middleware('auth');
Route::get('/inventory/{id}/in', [InventoryController::class, 'in'])->name('in_inventory')->middleware('auth');
Route::put('/inventory/{inventory}/storein', [InventoryController::class, 'storein'])->name('store_in')->middleware('auth');
Route::get('/inventory/destroy_in/{id}', [InventoryController::class, 'destroy_in'])->name('destroy_in')->middleware('auth');

Route::get('/data_out', [InventoryController::class, 'dataout'])->name('data_out')->middleware('auth');
Route::get('/inventory/{id}/out', [InventoryController::class, 'out'])->name('out_inventory')->middleware('auth');
Route::put('/inventory/{inventory}/storeout', [InventoryController::class, 'storeout'])->name('store_out')->middleware('auth');
Route::get('/inventory/destroy_out/{id}', [InventoryController::class, 'destroy_out'])->name('destroy_out')->middleware('auth');
Route::get('/add_dataout', [InventoryController::class, 'adddataout'])->name('add_dataout')->middleware('auth');
Route::post('/dataout/store', [InventoryController::class, 'storedatot'])->name('dataout.store');

Route::get('/vendor', [VendorController::class, 'index'])->name('vendor');
Route::get('/vendor/destroy/{id}', [VendorController::class, 'destroy'])->name('destroy_vendor')->middleware('auth');
Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor_create')->middleware('auth');
Route::post('/vendor/store', [VendorController::class, 'store'])->name('store_vendor')->middleware('auth');
Route::get('/vendor/edit/{id}', [VendorController::class, 'edit'])->name('edit_vendor')->middleware('auth');
Route::put('/vendor/update/{id}', [VendorController::class, 'update'])->name('update_vendor')->middleware('auth');

Route::get('/employee', [EmployeeController::class, 'index'])->name('employee')->middleware('auth');
Route::get('/add_employee', [EmployeeController::class, 'addemployee'])->name('add_employee')->middleware('auth');
Route::post('/store_employee', [EmployeeController::class, 'store'])->name('store_employee')->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
});

Route::get('/inputexcel', [InventoryController::class, 'inputexcel'])->name('inputexcel')->middleware('auth');
Route::post('/store_excel', [InventoryController::class, 'storeexcel'])->name('store_excel')->middleware('auth');
Route::post('/store_excel_data_out', [InventoryController::class, 'storeexceldataout'])->name('store_excel_data_out')->middleware('auth');

// Route::get('/report', [InventoryController::class, 'report'])->name('report')->middleware('auth');