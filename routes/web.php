<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

 // Clear Chache route
 Route::get('/cache_clear', function() {
    $exitCode    = Artisan::call('cache:clear');
    $config      = Artisan::call('config:cache');
    $view        = Artisan::call('view:clear');
    $optimize    = Artisan::call('optimize:clear');
    $route       = Artisan::call('route:clear');
    return "Cache, View, Config, Optimize & Route All is cleared";
});

Route::get('/run-migration', function () {
    Artisan::call('migrate', [
        '--force' => true,
    ]);
    return nl2br(Artisan::output());
});

// Route::get('/view_clear', function() { Artisan::call('view:clear'); });
// Route::get('/config_clear', function() {Artisan::call('config:cache');});
// Route::get('/route_clear', function() {Artisan::call('route:clear');});
// Route::get('/cache_clear', function() {Artisan::call('cache:clear');});
Auth::routes();


Route::get('admin/login', [App\Http\Controllers\Auth\LoginController::class, 'vip_login']);
Route::get('admin/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role']], function()
{
    Route::get('/', [App\Http\Controllers\admin\HomeController::class, 'index']);
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
    Route::get('/managers', [App\Http\Controllers\UserController::class, 'accountant']);
    Route::get('/employees', [App\Http\Controllers\UserController::class, 'employees']);
    Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create']);
    Route::get('/user/edit/{user}', [App\Http\Controllers\UserController::class, 'edit']);
    Route::delete('/user/destroy/{user}', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'adminprofile']);

    // Clients
    Route::resource('/clients', App\Http\Controllers\admin\ClientsController::class);
    Route::get('/client/create', [App\Http\Controllers\admin\ClientsController::class, 'create']);
    Route::post('client/store', [App\Http\Controllers\admin\ClientsController::class, 'store']);
    Route::get('/client/edit/{client}', [App\Http\Controllers\admin\ClientsController::class, 'edit']);
    Route::put('/client/update/{client}', [App\Http\Controllers\admin\ClientsController::class, 'update']);
    Route::delete('/client/destroy/{client}', [App\Http\Controllers\admin\ClientsController::class, 'destroy']);
    Route::get('clients/search', [App\Http\Controllers\admin\ClientsController::class, 'index']);
    Route::post('clients/search', [App\Http\Controllers\admin\ClientsController::class, 'search']);

    // Leads
    Route::resource('/leads', App\Http\Controllers\admin\LeadsController::class);
    Route::get('/lead/create', [App\Http\Controllers\admin\LeadsController::class, 'create']);
    Route::post('/lead/store', [App\Http\Controllers\admin\LeadsController::class, 'store']);
    Route::get('/lead/edit/{lead}', [App\Http\Controllers\admin\LeadsController::class, 'edit']);
    Route::put('/lead/update/{lead}', [App\Http\Controllers\admin\LeadsController::class, 'update']);
    Route::delete('/lead/destroy/{lead}', [App\Http\Controllers\admin\LeadsController::class, 'destroy']);
    Route::post('leads/search', [App\Http\Controllers\admin\LeadsController::class, 'search']);
    Route::get('leads/search', [App\Http\Controllers\admin\LeadsController::class, 'index']);

    // Projects
    Route::resource('/projects', App\Http\Controllers\admin\ProjectController::class);
    Route::get('/project/create', [App\Http\Controllers\admin\ProjectController::class, 'create']);
    Route::post('project/store', [App\Http\Controllers\admin\ProjectController::class, 'store']);
    Route::get('/project/edit/{project}', [App\Http\Controllers\admin\ProjectController::class, 'edit']);
    Route::put('/project/update/{project}', [App\Http\Controllers\admin\ProjectController::class, 'update']);
    Route::delete('/project/destroy/{project}', [App\Http\Controllers\admin\ProjectController::class, 'destroy']);

    // Tasks
    Route::post('gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
    Route::post('gettasksubtype', [App\Http\Controllers\TaskController::class, 'gettasksubtype']);
    Route::post('getunits', [App\Http\Controllers\TaskController::class, 'getunits']);
    Route::post('getfloor', [App\Http\Controllers\TaskController::class, 'getfloor']);
    Route::post('getsubtypeoption', [App\Http\Controllers\TaskController::class, 'getsubtypeoption']);
    Route::resource('/tasks', App\Http\Controllers\admin\TaskController::class);
    Route::get('/task/create', [App\Http\Controllers\TaskController::class, 'create']);
    Route::post('task/store', [App\Http\Controllers\TaskController::class, 'store']);
    Route::get('/task/edit/{lead}', [App\Http\Controllers\TaskController::class, 'edit']);
    Route::put('/task/update/{lead}', [App\Http\Controllers\TaskController::class, 'update']);
    Route::delete('/task/destroy/{lead}', [App\Http\Controllers\TaskController::class, 'destroy']);

    Route::get('/todolist/', [App\Http\Controllers\admin\TaskController::class, 'todolist']);
    Route::get('/todos/{todo}', [App\Http\Controllers\admin\TaskController::class, 'todos']);

    // Category
    Route::resource('category', App\Http\Controllers\admin\CategoryController::class);
    Route::post('/category/store', [App\Http\Controllers\admin\CategoryController::class, 'store']);
    Route::get('/category/edit/{category}', [App\Http\Controllers\admin\CategoryController::class, 'edit']);
    Route::put('/category/update/{category}', [App\Http\Controllers\admin\CategoryController::class, 'update']);
    Route::delete('/category/destroy/{category}', [App\Http\Controllers\admin\CategoryController::class, 'destroy']);

    // Attributes
    Route::resource('attributes', App\Http\Controllers\admin\AttributesController::class);
    Route::post('/attributes/store', [App\Http\Controllers\admin\AttributesController::class, 'store']);
    Route::get('/attributes/edit/{attributes}', [App\Http\Controllers\admin\AttributesController::class, 'edit']);
    Route::put('/attributes/update/{attributes}', [App\Http\Controllers\admin\AttributesController::class, 'update']);
    Route::delete('/attributes/destroy/{attributes}', [App\Http\Controllers\admin\AttributesController::class, 'destroy']);

    // Variations
    Route::get('options/{attrid}', [App\Http\Controllers\admin\OptionsController::class, 'index']);
    Route::get('options/create/{attrid}', [App\Http\Controllers\admin\OptionsController::class, 'create']);
    Route::post('options/store/', [App\Http\Controllers\admin\OptionsController::class, 'store']);
    Route::get('options/edit/{options}', [App\Http\Controllers\admin\OptionsController::class, 'edit']);
    Route::put('options/update/{options}', [App\Http\Controllers\admin\OptionsController::class, 'update']);
    Route::delete('options/destroy/{options}', [App\Http\Controllers\admin\OptionsController::class, 'destroy']);

    // Products
    Route::resource('inventory', App\Http\Controllers\admin\ProductController::class);
    Route::post('inventory/store', [App\Http\Controllers\admin\ProductController::class, 'store']);
    Route::get('inventory/edit/{product}', [App\Http\Controllers\admin\ProductController::class, 'edit']);
    Route::put('inventory/update/{product}', [App\Http\Controllers\admin\ProductController::class, 'update']);
    Route::delete('inventory/destroy/{product}', [App\Http\Controllers\admin\ProductController::class, 'destroy']);
    Route::get('inventory-search', [App\Http\Controllers\admin\ProductController::class, 'search']);
    Route::get('inventory/status', [App\Http\Controllers\admin\ProductController::class, 'checkstatus'])->name('change-status');
    Route::get('inventory/import', [App\Http\Controllers\admin\ProductController::class, 'import'])->name('file-import');

    // Affiliators
    Route::resource('affiliators', App\Http\Controllers\admin\AffiliatorsController::class)->name('resource', 'adminaffiliators');

    Route::get('/affiliators/create', [App\Http\Controllers\admin\AffiliatorsController::class, 'create']);
    Route::post('/affiliators/store', [App\Http\Controllers\admin\AffiliatorsController::class, 'store']);

    Route::get('dealors', [App\Http\Controllers\admin\AffiliatorsController::class, 'dealors']);
    Route::get('freeliencer', [App\Http\Controllers\admin\AffiliatorsController::class, 'freeliencer']);
    Route::get('affiliator/leads/{affiliator}', [App\Http\Controllers\admin\AffiliatorsController::class, 'affilitor_leads']);
    Route::post('affiliators/search', [App\Http\Controllers\admin\AffiliatorsController::class, 'search']);
    Route::get('/affiliator/lead/{affiliator}', [App\Http\Controllers\admin\LeadsController::class, 'affiliatorLead']);
    //Route::post('/affiliator/lead', [App\Http\Controllers\LeadsController::class, 'leadstore']);
    // Settings
    //  Route::resource('/settings', App\Http\Controllers\admin\SettingController::class);
    Route::get('/settings', [App\Http\Controllers\admin\SettingController::class, 'index']);

    //  Lead Status
    Route::get('/status', [App\Http\Controllers\admin\LeadStatusController::class, 'index']);
    Route::get('/status/create', [App\Http\Controllers\admin\LeadStatusController::class, 'create']);
    Route::post('/status/store', [App\Http\Controllers\admin\LeadStatusController::class, 'store']);
    Route::get('/status/edit/{status}', [App\Http\Controllers\admin\LeadStatusController::class, 'edit']);
    Route::put('/status/update/{status}', [App\Http\Controllers\admin\LeadStatusController::class, 'update']);
    Route::delete('/status/destroy/{status}', [App\Http\Controllers\admin\LeadStatusController::class, 'destroy']);

    //  Lead Priority
    Route::get('/priority', [App\Http\Controllers\admin\LeadPriorityController::class, 'index']);
    Route::get('/priority/create', [App\Http\Controllers\admin\LeadPriorityController::class, 'create']);
    Route::post('/priority/store', [App\Http\Controllers\admin\LeadPriorityController::class, 'store']);
    Route::get('/priority/edit/{priority}', [App\Http\Controllers\admin\LeadPriorityController::class, 'edit']);
    Route::put('/priority/update/{priority}', [App\Http\Controllers\admin\LeadPriorityController::class, 'update']);
    Route::delete('/priority/destroy/{priority}', [App\Http\Controllers\admin\LeadPriorityController::class, 'destroy']);

    //  Lead sources
    Route::get('/sources', [App\Http\Controllers\admin\LeadSourcesController::class, 'index']);
    Route::get('/sources/create', [App\Http\Controllers\admin\LeadSourcesController::class, 'create']);
    Route::post('/sources/store', [App\Http\Controllers\admin\LeadSourcesController::class, 'store']);
    Route::get('/sources/edit/{sources}', [App\Http\Controllers\admin\LeadSourcesController::class, 'edit']);
    Route::put('/sources/update/{sources}', [App\Http\Controllers\admin\LeadSourcesController::class, 'update']);
    Route::delete('/sources/destroy/{sources}', [App\Http\Controllers\admin\LeadSourcesController::class, 'destroy']);

    //  Lead Department
    Route::get('/departments', [App\Http\Controllers\admin\DepartmentController::class, 'index']);
    Route::get('/departments/create', [App\Http\Controllers\admin\DepartmentController::class, 'create']);
    Route::post('/departments/store', [App\Http\Controllers\admin\DepartmentController::class, 'store']);
    Route::get('/departments/edit/{departments}', [App\Http\Controllers\admin\DepartmentController::class, 'edit']);
    Route::put('/departments/update/{departments}', [App\Http\Controllers\admin\DepartmentController::class, 'update']);
    Route::delete('/departments/destroy/{departments}', [App\Http\Controllers\admin\DepartmentController::class, 'destroy']);

    // Designations
    Route::get('/designations', [App\Http\Controllers\admin\DesignationController::class, 'index']);
    Route::get('/designations/create', [App\Http\Controllers\admin\DesignationController::class, 'create']);
    Route::post('/designations/store', [App\Http\Controllers\admin\DesignationController::class, 'store']);
    Route::get('/designations/edit/{designations}', [App\Http\Controllers\admin\DesignationController::class, 'edit']);
    Route::put('/designations/update/{designations}', [App\Http\Controllers\admin\DesignationController::class, 'update']);
    Route::delete('/designations/destroy/{designations}', [App\Http\Controllers\admin\DesignationController::class, 'destroy']);

     // offices
     Route::get('/offices', [App\Http\Controllers\admin\OfficeController::class, 'index']);
     Route::get('/offices/create', [App\Http\Controllers\admin\OfficeController::class, 'create']);
     Route::post('/offices/store', [App\Http\Controllers\admin\OfficeController::class, 'store']);
     Route::get('/offices/edit/{offices}', [App\Http\Controllers\admin\OfficeController::class, 'edit']);
     Route::put('/offices/update/{offices}', [App\Http\Controllers\admin\OfficeController::class, 'update']);
     Route::delete('/offices/destroy/{offices}', [App\Http\Controllers\admin\OfficeController::class, 'destroy']);

    // Campaigns
    Route::resource('/compain', App\Http\Controllers\admin\CompainController::class);
    Route::get('/compain/inactive/{id}', [App\Http\Controllers\admin\CompainController::class, 'compainInActive']);
    Route::get('/compain/active/{id}', [App\Http\Controllers\admin\CompainController::class, 'compainActive']);

    // Company
    Route::resource('/company', App\Http\Controllers\admin\CompanyController::class);


    // //Facebook leads Api
    // Route::get('facebook_access_token', [App\Http\Controllers\FacebookApiController::class, 'access_token']);


});

Route::group(['middleware' => ['auth', 'checkStatus']], function() {
    //Dashboard
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit']);
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update']);
    Route::post('/user/status/{user}', [App\Http\Controllers\UserController::class, 'status']);
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'password']);
    Route::put('/user/password/{$user}', [App\Http\Controllers\outer\UserController::class, 'userpassword']);

    Route::group(['middleware' => ['not_role_10']], function()  // Role 10 Only Compain User
    {

        // Inventory
        Route::resource('inventory', App\Http\Controllers\ProductController::class);
        Route::get('inventory-search', [App\Http\Controllers\ProductController::class, 'search']);
        Route::get('get-unit-ids/{unitids}', [App\Http\Controllers\ProductController::class, 'getUnitIds']);
        Route::get('priceupdate', [App\Http\Controllers\ProductController::class, 'show']);
        Route::get('inventory-update', [App\Http\Controllers\ProductController::class, 'updateprice']);
        Route::post('/existence/store', [App\Http\Controllers\ProductController::class, 'existence']);
        Route::post('inventory/approve/store', [App\Http\Controllers\ProductController::class, 'approval_store']);
        Route::get('inventory/edit/{product}', [App\Http\Controllers\ProductController::class, 'edit']);
        Route::put('inventory/update/{product}', [App\Http\Controllers\ProductController::class, 'update']);

        Route::post('load/types' , [App\Http\Controllers\ProductController::class, 'loadTypes']); // Load Subcategories with Ajax
        Route::post('load/subtypes' , [App\Http\Controllers\ProductController::class, 'loadSubTypes']); // Load Subcategories with Ajax

        Route::get('discountprice', [App\Http\Controllers\ProductController::class, 'showdiscount']);
        Route::get('discount/edit/{id}', [App\Http\Controllers\ProductController::class, 'discountEdit']);
        Route::put('/discount/update/{id}', [App\Http\Controllers\ProductController::class, 'discountUpdate']);
        Route::post('get-inventory-orders', [App\Http\Controllers\ProductController::class, 'get_orders']);


        Route::group(['middleware' => ['not_role_11']], function() // Role 11 Dealer User
        {
            // Clients
            Route::resource('/clients', App\Http\Controllers\ClientsController::class);
            Route::get('/client/create', [App\Http\Controllers\ClientsController::class, 'create']);
            Route::post('client/store', [App\Http\Controllers\ClientsController::class, 'store']);
            Route::get('/client/edit/{client}', [App\Http\Controllers\ClientsController::class, 'edit']);
            Route::put('/client/update/{client}', [App\Http\Controllers\ClientsController::class, 'update']);
            Route::get('clients/search', [App\Http\Controllers\ClientsController::class, 'index']);
            Route::get('clients-search', [App\Http\Controllers\ClientsController::class, 'search']);
            Route::post('client/edit/gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
            Route::post('client/edit/gettasksubtype', [App\Http\Controllers\TaskController::class, 'gettasksubtype']);
            Route::post('client/edit/getunits', [App\Http\Controllers\TaskController::class, 'getunits']);
            Route::post('client/edit/getsubtypeoption', [App\Http\Controllers\TaskController::class, 'getsubtypeoption']);
            Route::get('all/clients/{id}', [App\Http\Controllers\ClientsController::class, 'All_Clients']);
            Route::delete('/client/destroy/{client}', [App\Http\Controllers\ClientsController::class, 'destroy']);

            Route::get('/trash/clients', [App\Http\Controllers\ClientsController::class, 'trash_Clients']);
            Route::post('/client/restore/{client}', [App\Http\Controllers\ClientsController::class, 'resotre_Clients']);
            Route::delete('/client/delete/permanently/{client}', [App\Http\Controllers\ClientsController::class, 'client_delete_permanently']);
            Route::get('trash/clients-search', [App\Http\Controllers\ClientsController::class, 'trash_search']);

            // Leads
            Route::resource('/leads', App\Http\Controllers\LeadsController::class);
            Route::get('/lead/create', [App\Http\Controllers\LeadsController::class, 'create']);
            Route::get('newleads', [App\Http\Controllers\LeadsController::class, 'facebookleads']);
            Route::post('leads/assigned', [App\Http\Controllers\LeadsController::class, 'assigned']);
            Route::post('lead/store', [App\Http\Controllers\LeadsController::class, 'store']);
            Route::get('/lead/edit/{lead}', [App\Http\Controllers\LeadsController::class, 'edit']);
            Route::put('/lead/update/{lead}', [App\Http\Controllers\LeadsController::class, 'update']);
            Route::get('/client/lead/{client}', [App\Http\Controllers\LeadsController::class, 'clientLead']);
            Route::get('share/leads', [App\Http\Controllers\LeadsController::class, 'shareleads']);
            Route::post('/client/lead', [App\Http\Controllers\LeadsController::class, 'leadstore']);
            Route::get('leads-search', [App\Http\Controllers\LeadsController::class, 'search']);
            Route::get('leads/search', [App\Http\Controllers\LeadsController::class, 'index']);
            Route::get('/lead/{day}', [App\Http\Controllers\LeadsController::class, 'leads_sorting']);
            Route::post('lead/sorting', [App\Http\Controllers\LeadsController::class, 'sorting']);
            Route::delete('lead/delete/{id}', [App\Http\Controllers\LeadsController::class, 'leadDelete']);

            Route::get('/tarsh/leads', [App\Http\Controllers\LeadsController::class, 'trash_leads']);
            Route::post('/lead/restore/{leads}', [App\Http\Controllers\LeadsController::class, 'resotre_leads']);
            Route::delete('/lead/delete/permanently/{leads}', [App\Http\Controllers\LeadsController::class, 'lead_delete_permanently']);
            Route::get('tarsh/leads-search', [App\Http\Controllers\LeadsController::class, 'trash_search']);

            Route::post('lead/feedback/store',[App\Http\Controllers\LeadsController::class,'storeFeedback'])->name('lead.feedback.store');

            Route::get('markasread/{id}', [App\Http\Controllers\LeadsController::class, 'markAsRead']);
            Route::get('markas/all_as/read', [App\Http\Controllers\LeadsController::class, 'markAllAsRead']);
            Route::get('getallnotification', [App\Http\Controllers\LeadsController::class, 'getAllNotification']);
            Route::get('/all/notifications', [App\Http\Controllers\LeadsController::class, 'viewAllNotification']);


            // Todos
            Route::post('todos/gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
            Route::post('todos/gettasksubtype', [App\Http\Controllers\TaskController::class, 'gettasksubtype']);
            Route::post('todos/getunits', [App\Http\Controllers\TaskController::class, 'getunits']);
            Route::post('todos/getsubtypeoption', [App\Http\Controllers\TaskController::class, 'getsubtypeoption']);
            Route::post('todos/search', [App\Http\Controllers\TaskController::class, 'search']);

            // Tasks
            Route::post('getunitdetail', [App\Http\Controllers\TaskController::class, 'getunitdetail']);
            Route::post('gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
            Route::post('account_log', [App\Http\Controllers\TaskController::class, 'account_log']);
            Route::post('gettasksubtype', [App\Http\Controllers\TaskController::class, 'gettasksubtype']);
            Route::post('getunits', [App\Http\Controllers\TaskController::class, 'getunits']);
            Route::post('changetype', [App\Http\Controllers\TaskController::class, 'changetype']);
            Route::post('changefloor', [App\Http\Controllers\TaskController::class, 'changefloor']);

            Route::post('getfloor', [App\Http\Controllers\TaskController::class, 'getfloor']);
            Route::post('getsubtypeoption', [App\Http\Controllers\TaskController::class, 'getsubtypeoption']);
            Route::resource('/tasks', App\Http\Controllers\TaskController::class);
            Route::get('/task/create', [App\Http\Controllers\TaskController::class, 'create']);
            Route::post('task/store', [App\Http\Controllers\TaskController::class, 'store']);
            Route::get('/task/edit/{lead}', [App\Http\Controllers\TaskController::class, 'edit']);
            Route::put('/task/update/{lead}', [App\Http\Controllers\TaskController::class, 'update']);
            Route::delete('/task/destroy/{lead}', [App\Http\Controllers\TaskController::class, 'destroy']);
            Route::get('/todolist/', [App\Http\Controllers\TaskController::class, 'todolist']);
            Route::get('/todos/{todo}', [App\Http\Controllers\TaskController::class, 'todos']);

            // Reports
            Route::get('all-leads/', [App\Http\Controllers\ReportController::class, 'index']);
            Route::get('assignedleads/', [App\Http\Controllers\ReportController::class, 'assign']);
            Route::get('unassignedleads/', [App\Http\Controllers\ReportController::class, 'noassign']);
            Route::get('inactive-affiliators/', [App\Http\Controllers\ReportController::class, 'noassignaffiliator']);
            Route::get('overdue-affiliator-task/', [App\Http\Controllers\ReportController::class, 'overdueafftask']);
            Route::get('overduetask', [App\Http\Controllers\ReportController::class, 'overduetask']);
            Route::get('noassign/search', [App\Http\Controllers\ReportController::class, 'notassigntask']);
            Route::get('user/task', [App\Http\Controllers\ReportController::class, 'usertask']);
            Route::post('user/gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
            Route::post('dashboard/search', [App\Http\Controllers\HomeController::class, 'search']);
            //Route::post('dashboard/gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
            Route::post('lead/report', [App\Http\Controllers\ReportController::class, 'search']);
            Route::get('facebook-leads-import', [App\Http\Controllers\FacebookController::class, 'facebookImport']);
            Route::post('facebook-leads-store', [App\Http\Controllers\FacebookController::class, 'facebookfileImportInstantForm']);

            //Facebook leads Api
            Route::get('facebook_access_token', [App\Http\Controllers\FacebookApiController::class, 'access_token']);


            Route::get('file-import-inventory', [App\Http\Controllers\admin\FileController::class, 'fileImportExport']);
            Route::post('inventory-sizes-update', [App\Http\Controllers\admin\FileController::class, 'sizesFileImport']);


            Route::get('export-inventory-file', [App\Http\Controllers\admin\FileController::class, 'inventoryExportSearch']);
            Route::get('inventory-exporting', [App\Http\Controllers\admin\FileController::class, 'exportInventoryFile']);

            Route::post('file-import', [App\Http\Controllers\admin\FileController::class, 'fileImport'])->name('file-import');
            Route::get('file-export', [App\Http\Controllers\admin\FileController::class, 'fileExport'])->name('file-export');
            Route::get('check-status', [App\Http\Controllers\ProductController::class, 'checkstatus']);
             Route::get('office-users-get/{office_id}', [App\Http\Controllers\LeadsController::class, 'office_users_get']);

            Route::get('client-leads-import', [App\Http\Controllers\FacebookController::class, 'clientsImport']);
            Route::post('client-leads-store', [App\Http\Controllers\FacebookController::class, 'clientfileImportInstantForm']);


        });


        Route::group(['middleware' => ['not_role_11' , 'not_role_12']], function() // Role 12 Freelancer User && Role 11 Dealer User
        {
            Route::get('facebookleads', [App\Http\Controllers\FacebookApiController::class, 'facebookleads']);
            Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create']);
            Route::put('/user/update/{user}', [App\Http\Controllers\UserController::class, 'update']);
            Route::put('/user/password/{user}', [App\Http\Controllers\UserController::class, 'password']);
            Route::get('/user/edit/{user}', [App\Http\Controllers\UserController::class, 'edit']);
            Route::post('/user/store', [App\Http\Controllers\UserController::class, 'store']);

            //i think so Affiliators Not Clients
            Route::resource('affiliators', App\Http\Controllers\AffiliatorsController::class);
            Route::get('dealors', [App\Http\Controllers\AffiliatorsController::class, 'dealors']);
            Route::get('freeliencer', [App\Http\Controllers\AffiliatorsController::class, 'freeliencer']);
            Route::get('affiliator/leads/{affiliator}', [App\Http\Controllers\AffiliatorsController::class, 'affilitor_leads']);
            Route::get('affiliator-search', [App\Http\Controllers\AffiliatorsController::class, 'search']);
            Route::get('affliator-todos/search', [App\Http\Controllers\AffiliatorsController::class, 'todossearch']);
            Route::get('/affiliator/lead/{affiliator}', [App\Http\Controllers\LeadsController::class, 'affiliatorLead']);
            Route::post('getafitasksubtype', [App\Http\Controllers\AffiliatorsController::class, 'getafitasksubtype']);
            Route::post('affiliator/todos/getafitasksubtype', [App\Http\Controllers\AffiliatorsController::class, 'getafitasksubtype']);
            Route::post('get_affiliator_task_history', [App\Http\Controllers\AffiliatorsController::class, 'get_affiliator_task_history']);
            Route::post('affiliator/todos/get_affiliator_task_history', [App\Http\Controllers\AffiliatorsController::class, 'get_affiliator_task_history']);
            Route::post('affiliator/task', [App\Http\Controllers\AffiliatorsController::class, 'taskstore']);
            Route::get('affiliator/todos', [App\Http\Controllers\AffiliatorsController::class, 'todolist']);
            Route::get('affiliator/todos/{todo}', [App\Http\Controllers\AffiliatorsController::class, 'todos']);
            //Route::post('/affiliator/lead', [App\Http\Controllers\LeadsController::class, 'leadstore']);
            Route::get('all/affiliator/{id}', [App\Http\Controllers\AffiliatorsController::class, 'All_Affiliator']);
            Route::post('all/affiliator/get_affiliator_task_history', [App\Http\Controllers\AffiliatorsController::class, 'get_affiliator_task_history']);
            Route::post('all/affiliator/getafitasksubtype', [App\Http\Controllers\AffiliatorsController::class, 'getafitasksubtype']);


            // Affiliator Trash Routes
            Route::get('/trash/affiliators', [App\Http\Controllers\AffiliatorsController::class, 'trash_affiliators']);
            Route::post('/affiliator/restore/{affiliator}', [App\Http\Controllers\AffiliatorsController::class, 'resotre_affiliator']);
            Route::delete('/affiliator/delete/permanently/{affiliator}', [App\Http\Controllers\AffiliatorsController::class, 'affiliator_delete_permanently']);
            Route::get('trash-affiliator-search', [App\Http\Controllers\AffiliatorsController::class, 'trash_search']);


            // Route::get('view/leads', [App\Http\Controllers\LeadsController::class, 'view_lead']);

            Route::post('getaffiliators', [App\Http\Controllers\LeadsController::class, 'getaffiliators']);
            //    Route::post('getaffiliators', [App\Http\Controllers\LeadsController::class, 'getaffiliators']);
            Route::post('getclients', [App\Http\Controllers\LeadsController::class, 'getclients']);
            Route::post('/affiliator/lead/getclients', [App\Http\Controllers\LeadsController::class, 'getclients']);
            Route::post('lead/status', [App\Http\Controllers\LeadsController::class, 'leadstatus']);
            Route::get('dateformat', [App\Http\Controllers\TaskController::class, 'dateformat']);
            Route::get('lead/performance/graph', [App\Http\Controllers\UserController::class, 'PerformanceGraph']);
            Route::post('check/user/performance', [App\Http\Controllers\UserController::class, 'UserSortingGraph']);
            Route::post('users/sorting', [App\Http\Controllers\HomeController::class, 'UserSortingGraph']);
            Route::get('all/leads/{id}', [App\Http\Controllers\LeadsController::class, 'All_Leads']);
            Route::post('all/leads/gettask', [App\Http\Controllers\TaskController::class, 'get_task']);
            Route::post('all/leads/gettasksubtype', [App\Http\Controllers\TaskController::class, 'gettasksubtype']);
            Route::post('all/leads/getunits', [App\Http\Controllers\TaskController::class, 'getunits']);
            Route::post('all/leads/getsubtypeoption', [App\Http\Controllers\TaskController::class, 'getsubtypeoption']);
            Route::post('lead/share/store', [App\Http\Controllers\LeadsController::class, 'sharedleadstore']);
            Route::post('lead/transfer/store', [App\Http\Controllers\LeadsController::class, 'transferleadstore']);
            Route::get('clients/leads/{id}', [App\Http\Controllers\LeadsController::class, 'clientLeads']);
            Route::post('multi/lead/share/store', [App\Http\Controllers\LeadsController::class, 'multisharedleadstore']);
            Route::post('multi/lead/transfer/store', [App\Http\Controllers\LeadsController::class, 'multitransferleadstore']);
            Route::post('multi/leads/delete/permanently', [App\Http\Controllers\LeadsController::class, 'multiLeadsDeletePermanently']);
            Route::post('multi/clients/delete/permanently', [App\Http\Controllers\ClientsController::class, 'multiClientsDeletePermanently']);
            Route::post('multi/affiliators/delete/permanently', [App\Http\Controllers\AffiliatorsController::class, 'multiAffiliatorsDeletePermanently']);
            Route::post('/leads/multi-trash', [App\Http\Controllers\LeadsController::class, 'multiTrashLeads'])->name('leads.multi-trash');

	        // reports
            Route::get('reports/', [App\Http\Controllers\ReportController::class, 'reports']);
            Route::get('user/reports', [App\Http\Controllers\ReportController::class, 'userreports']);
            Route::get('report_info/{id}/{task}/{subtype}', [App\Http\Controllers\ReportController::class, 'report_info']);
            Route::get('report_info/{report_of}/{id}/{task}/{subtype}', [App\Http\Controllers\ReportController::class, 'affiliators_report_info']);

            // Staff
            Route::get('/staff', [App\Http\Controllers\UserController::class, 'staff']);
            Route::get('/inactive-staff', [App\Http\Controllers\UserController::class, 'inactiveStaff']);
            Route::post('/user/search', [App\Http\Controllers\UserController::class, 'search']);
            Route::get('/contacts', [App\Http\Controllers\UserController::class, 'teams']);

            // staff Tree
            Route::get('view-tree', [App\Http\Controllers\UserController::class, 'viewTree']);
            Route::post('search/user/tree', [App\Http\Controllers\UserController::class, 'searchTree']);
            Route::get('get-office-managers/{office_id}', [App\Http\Controllers\UserController::class, 'getOfficeMagers']);
            Route::get('get-office-managers_with_all/{office_id}', [App\Http\Controllers\UserController::class, 'getAllOfficeMagers']);


            // Trash Staff
            Route::get('trash/staff/user/{id}', [App\Http\Controllers\UserController::class, 'trashStaff']);
            Route::get('trash-staff', [App\Http\Controllers\UserController::class, 'trashedStaff']);
            Route::get('staff/active/again/{id}', [App\Http\Controllers\UserController::class, 'unTrashStaff']);
            Route::post('/user/search-in-trash', [App\Http\Controllers\UserController::class, 'trashsearch']);
            Route::get('staff/delete/permanently/{id}', [App\Http\Controllers\UserController::class, 'permanentlyDeleteStaff']);

            // Permissions
            Route::get('permissions', [App\Http\Controllers\PermissionController::class, 'index']);
            Route::get('permission/create', [App\Http\Controllers\PermissionController::class, 'create']);
            Route::post('permission/store', [App\Http\Controllers\PermissionController::class, 'store']);
            Route::get('permission/edit/{id}', [App\Http\Controllers\PermissionController::class, 'edit']);
            Route::post('permission/update/{id}', [App\Http\Controllers\PermissionController::class, 'update']);
            Route::get('permission/delete/{id}', [App\Http\Controllers\PermissionController::class, 'delete']);


            // Roles
            Route::get('roles', [App\Http\Controllers\RoleController::class, 'index']);
            Route::get('role/create', [App\Http\Controllers\RoleController::class, 'create']);
            Route::post('role/store', [App\Http\Controllers\RoleController::class, 'store']);
            Route::get('role/edit/{id}', [App\Http\Controllers\RoleController::class, 'edit']);
            Route::post('role/update/{id}', [App\Http\Controllers\RoleController::class, 'update']);
            Route::get('role/delete/{id}', [App\Http\Controllers\RoleController::class, 'delete']);

            // role-in-permissions
            Route::get('role-in-permissions', [App\Http\Controllers\RoleInPermissionController::class, 'index']);
            Route::get('role-in-permission/create', [App\Http\Controllers\RoleInPermissionController::class, 'create']);
            Route::post('role-in-permission/store', [App\Http\Controllers\RoleInPermissionController::class, 'store']);
            Route::get('role-in-permission/edit/{id}', [App\Http\Controllers\RoleInPermissionController::class, 'edit']);
            Route::post('role-in-permission/update/{id}', [App\Http\Controllers\RoleInPermissionController::class, 'update']);
            Route::get('role-in-permission/delete/{id}', [App\Http\Controllers\RoleInPermissionController::class, 'delete']);

            // exist user or not on lead create page
            Route::post('/get/user', [App\Http\Controllers\LeadsController::class, 'confirmUserAjax'])->name('get.user');
            Route::post('check-edit-user', [App\Http\Controllers\LeadsController::class, 'confirmEditUserAjax'])->name('check-edit-user');
            Route::post('check/multi-phone', [App\Http\Controllers\LeadsController::class, 'confirmMultiPhoneAjax'])->name('check.multi-phone');
            Route::post('check/multi-phone/edit', [App\Http\Controllers\LeadsController::class, 'confirmEditMultiPhoneAjax']);
            Route::post('delete/multi-phone/number', [App\Http\Controllers\LeadsController::class, 'deleteMultiPhoneAjax']);


            // Targets
            Route::get('/targets', [App\Http\Controllers\UserController::class, 'targets']);
            Route::get('set-targets', [App\Http\Controllers\UserController::class, 'setTargets']);
            Route::get('targets/repeat', [App\Http\Controllers\UserController::class, 'repeat_targets']);

            Route::get('/all-targets', [App\Http\Controllers\UserController::class, 'all_targets']);
            Route::delete('target/destroy/{target}', [App\Http\Controllers\UserController::class, 'destory_target']);
            Route::get('edit/target/{target}', [App\Http\Controllers\UserController::class, 'edit_target']);
            Route::post('update-target/{target}', [App\Http\Controllers\UserController::class, 'update_target']);
            Route::get('target-search', [App\Http\Controllers\UserController::class, 'target_search']);

        });


    });

    Route::group([ 'middleware' => ['role10']], function()
    {
        // Show Only Campains
        Route::resource('/compain', App\Http\Controllers\CompainController::class);
        Route::get('/compain/inactive/{id}', [App\Http\Controllers\CompainController::class, 'compainInActive']);
        Route::get('/compain/active/{id}', [App\Http\Controllers\CompainController::class, 'compainActive']);
    });

});


// Testing Routes
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::get('sendemail', [App\Http\Controllers\MailController::class, 'basic_email']);
Route::get('move_numbers', [App\Http\Controllers\LeadsController::class, 'move_numbers']);
Route::get('move_notifications', [App\Http\Controllers\LeadsController::class, 'move_notifications']);



Route::get('update_status', [App\Http\Controllers\Controller::class, 'bookStatus']);
Route::post('change/inventory/status', [App\Http\Controllers\Controller::class, 'readFile']);

Route::get('price/update/file', [App\Http\Controllers\Controller::class, 'chooseFile']);
Route::get('update/product/prices', [App\Http\Controllers\Controller::class, 'updateprice']);

Route::get('corner/update/file', [App\Http\Controllers\Controller::class, 'chooseCornerFile']);
Route::get('corner/product/prices', [App\Http\Controllers\Controller::class, 'updateCorner']);



// Below Routes Are Not Used
// Below Routes Are Not Used
// Below Routes Are Not Used




// Outsiders
// Route::group(['prefix' => 'outer', 'middleware' => ['auth', 'CheckOutSiderStatus']], function() {
//     // Inventory
//     Route::resource('inventory', App\Http\Controllers\outer\ProductController::class);
//     Route::get('inventory-search', [App\Http\Controllers\outer\ProductController::class, 'search']);
//     Route::get('/profile/edit', [App\Http\Controllers\outer\ProfileController::class, 'edit']);
//     Route::get('bookleads', [App\Http\Controllers\outer\ProductController::class, 'show']);
//     Route::put('/profile/update/{id}', [App\Http\Controllers\outer\ProfileController::class, 'update']);
//     Route::put('/profile/password', [App\Http\Controllers\outer\ProfileController::class, 'password']);
//     Route::post('/existence/store', [App\Http\Controllers\outer\ProductController::class, 'existence']);
// });



// ===========================These Routes Are Not In Use==================================
// ===========================These Routes Are Not In Use==================================
// ===========================These Routes Are Not In Use==================================

// Route::group(['middleware' => 'guest:affiliator'], function ()
// {
//     Route::get('affiliator/login', [App\Http\Controllers\Affiliator\LoginController::class, 'showLoginForm'])->name('affiliator.login');
//     Route::post('affiliator/login', [App\Http\Controllers\Affiliator\LoginController::class, 'login'])->name('affiliator.login');
// });
// Route::group(['prefix' => 'affiliator', 'middleware' => ['affiliator']], function()
// {
//     //index
//     Route::get('/', [App\Http\Controllers\Affiliator\HomeController::class, 'index']);
//     Route::get('/dashboard', [App\Http\Controllers\Affiliator\HomeController::class, 'index'])->name('affiliator.dashboard');
//     Route::get('/logout', [App\Http\Controllers\Affiliator\LoginController::class, 'logout'])->name('affiliator.logout');

//     //Profile
//     Route::get('/profile/edit', [App\Http\Controllers\Affiliator\ProfileController::class, 'edit']);
//     Route::put('/profile/update', [App\Http\Controllers\Affiliator\ProfileController::class, 'update']);
//     Route::put('/profile/password', [App\Http\Controllers\Affiliator\ProfileController::class, 'password']);

//     // Inventory
//     Route::resource('inventory', App\Http\Controllers\Affiliator\ProductController::class);
//     Route::get('inventory-search', [App\Http\Controllers\Affiliator\ProductController::class, 'search']);

//     Route::group(['middleware' => 'freelancer'], function ()
//     {
//         // Leads
//         Route::resource('leads', App\Http\Controllers\Affiliator\LeadsController::class);
//         Route::get('leads-search', [App\Http\Controllers\Affiliator\LeadsController::class, 'search']);
//         Route::post('lead/sorting', [App\Http\Controllers\Affiliator\LeadsController::class, 'sorting']);
//         Route::get('{day}/lead', [App\Http\Controllers\Affiliator\LeadsController::class, 'leads_sorting']);
//         Route::get('leads-import', [App\Http\Controllers\Affiliator\LeadsController::class, 'leadsImport']);
//         Route::post('leads-store', [App\Http\Controllers\Affiliator\LeadsController::class, 'leadsfileImport']);
//         Route::get('todolist', [App\Http\Controllers\Affiliator\TaskController::class, 'todolist']);
//         Route::get('/todos/{todo}', [App\Http\Controllers\Affiliator\TaskController::class, 'todos']);
//         Route::get('user/task', [App\Http\Controllers\Affiliator\ReportController::class, 'usertask']);

//         // Clients
//         Route::resource('clients', App\Http\Controllers\Affiliator\ClientsController::class);
//         Route::get('clients/search', [App\Http\Controllers\Affiliator\ClientsController::class, 'index']);
//         Route::get('clients-search', [App\Http\Controllers\Affiliator\ClientsController::class, 'search']);
//     	Route::get('client/edit/{client}', [App\Http\Controllers\Affiliator\ClientsController::class, 'edit']);
//     	Route::put('client/update/{client}', [App\Http\Controllers\ClientsController::class, 'update']);
//     	Route::get('client/create', [App\Http\Controllers\Affiliator\ClientsController::class, 'create']);
//         Route::post('client/store', [App\Http\Controllers\Affiliator\ClientsController::class, 'store']);

//     });

// });

// ===========================These Routes Are Not In Use==================================
// ===========================These Routes Are Not In Use==================================
// ===========================These Routes Are Not In Use==================================



// ^(\s)*$\n


// //Facebook leads Api
// Route::get('facebook_access_token', [App\Http\Controllers\FacebookApiController::class, 'access_token']);
