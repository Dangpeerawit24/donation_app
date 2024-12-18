<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoriesdetailsController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignTransactionController;
use App\Http\Controllers\CampaignTransactionComplete;
use App\Http\Controllers\LineLoginController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CampaignstatusController;
use App\Http\Controllers\CampaignstatusImgController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\FormcampaighbirthdayController;
use App\Http\Controllers\FormcampaightextController;
use App\Http\Controllers\FormcampaighallController;
use App\Http\Controllers\FormcampaighgiveController;
use App\Http\Controllers\FormcampaignController;
use App\Http\Controllers\PushevidenceController;
use App\Http\Controllers\Formcampaighall2Controller;
use App\Http\Controllers\Formcampaighall3Controller;
use App\Http\Controllers\LineUsersController;


// Route::get('/login', function () { return view('auth.login');});

// Route::view('/test', 'test');


// Line App
Route::post('/webhook', [WebhookController::class, 'handle']);
Route::get('/line/login', [LineLoginController::class, 'redirectToLine'])->name('line.login');
Route::get('/line/callback', [LineLoginController::class, 'handleLineCallback'])->name('line.callback');
Route::get('/line', [LineLoginController::class, 'showDashboard'])->name('welcome');
Route::get('/campaignstatus', [CampaignstatusController::class, 'campaignstatus'])->name('campaignstatus');
Route::get('/campaignstatusimg', [CampaignstatusImgController::class, 'campaignstatusimg'])->name('campaignstatusimg');
// Line Form
Route::resource('/formcampaigh', FormcampaignController::class);
Route::get('/fetch_formcampaigh_details', [FormcampaignController::class, 'fetchformcampaighDetails'])->name('fetch.formcampaigh.details');
Route::resource('/formcampaighbirthday', FormcampaighbirthdayController::class);
Route::get('/fetch_formcampaighbirthday_details', [FormcampaighbirthdayController::class, 'fetchformcampaighbirthdayDetails'])->name('fetch.formcampaighbirthday.details');
Route::resource('/formcampaightext', FormcampaightextController::class);
Route::get('/fetch_formcampaightext_details', [FormcampaightextController::class, 'fetchformcampaightextdetails'])->name('fetch.formcampaightext.details');
Route::resource('/formcampaighall', FormcampaighallController::class);
Route::resource('/formcampaighall2', Formcampaighall2Controller::class);
Route::resource('/formcampaighall3', Formcampaighall3Controller::class);
Route::get('/fetch_formcampaighall_details', [FormcampaighallController::class, 'fetchformcampaighalldetails'])->name('fetch.formcampaighall.details');
Route::get('/fetch_formcampaighall2_details', [Formcampaighall2Controller::class, 'fetchformcampaighalldetails'])->name('fetch.formcampaighall.details');
Route::resource('/formcampaighgive', FormcampaighgiveController::class);
// Line Push Evidence
Route::get('/pushevidence', [PushevidenceController::class, 'index'])->name('pushevidence.index');
Route::get('/pushevidence2', [PushevidenceController::class, 'index2'])->name('pushevidence2.index');
// Route::get('/pushevidence', [PushevidenceController::class, 'index'])->name('pushevidence.index');
Route::post('/pushevidencetouser', [PushevidenceController::class, 'pushevidencetouser'])->name('pushevidencetouser');
Route::get('/pin', [PinController::class, 'showForm'])->name('pin.form');
Route::post('/pin', [PinController::class, 'verifyPin'])->name('pin.verify');

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

/*------------------------------------------
All Normal Users Routes List
--------------------------------------------*/
Route::middleware(['auth', 'user-access:user'])->group(function () {
  
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');
});
  
/*------------------------------------------
All Admin Routes List
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboardmonth', [DashboardController::class, 'dashboardmonth'])->name('admin.dashboardmonth');
    Route::get('/admin/dashboardyear', [DashboardController::class, 'dashboardyear'])->name('admin.dashboardyear');
    Route::get('/admin/campaignsmonth', [DashboardController::class, 'campaignsmonth'])->name('admin.campaignsmonth');
    Route::get('/admin/campaignsyear', [DashboardController::class, 'campaignsyear'])->name('admin.campaignsyear');
    Route::resource('/admin/users', UsersController::class);
    Route::put('/admin/users/update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/destroy/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/admin/qrcode', [QRCodeController::class, 'index'])->name('qr-code.index');
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/admin/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/admin/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/admin/campaigns', [CampaignController::class, 'index'])->name('campaigns');
    Route::post('/admin/campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::post('/admin/pushmessage', [CampaignController::class, 'pushmessage'])->name('campaigns.pushmessage');
    Route::put('/admin/campaigns/update/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::put('/admin/campaigns/close/{id}', [CampaignController::class, 'Closed'])->name('campaigns.close');
    Route::delete('/admin/campaigns/destroy/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::resource('/admin/categoriesdetails', CategoriesdetailsController::class);
    Route::resource('/admin/campaigns_transaction', CampaignTransactionController::class);
    Route::get('/admin/campaigns_transaction_success', [CampaignTransactionController::class, 'success']);
    Route::resource('/admin/campaign_transaction_complete', CampaignTransactionComplete::class);
    Route::get('/admin/lineusers', [LineUsersController::class, 'index']);
    
});

/*------------------------------------------
All Member Routes List
--------------------------------------------*/
Route::middleware(['auth', 'user-access:member'])->group(function () {
  
    Route::get('/member/home', [HomeController::class, 'memberHome'])->name('member.home');
});

// Fetch API 
Route::get('/api/campaigns', [DashboardController::class, 'getActiveCampaigns']);
Route::get('/api/users', [DashboardController::class, 'getActiveuser']);
Route::get('/api/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
Route::get('/api/lineusers', [LineUsersController::class, 'getLineUsers']);
Route::get('/api/transactions', [CampaignTransactionController::class, 'gettransactions']);

// เส้นทางสำหรับสร้าง QR Code
Route::post('/qr-code/generate', [QRCodeController::class, 'generate'])->name('qr-code.generate');