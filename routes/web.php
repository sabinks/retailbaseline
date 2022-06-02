<?php

    // Route::get('/', 'HomeController@index')->middleware('auth');
    Route::view('/', 'components.dashboard.index')->middleware('auth');
    // Route::get('/home', 'HomeController@index')->middleware('auth');
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth', 'role:Admin|Regional Admin|Supervisor']], function () {
        Route::get('/mystaffs','StaffController@mystaffIndex');
    });

    Route::group(['middleware' => ['auth','role:Admin']], function () {
        Route::resource('/regionalAdmins','RegionalAdminController'); 
    });
    Route::group(['middleware' => ['auth','role:Super Admin|Admin']], function () {
        Route::get('/themeSetting', function() { return view('theme');  });
        Route::post('/theme','ThemeController@store');
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::get('forms/{form}/get-staffs','StaffController@getFormStaffs');
        //staff section
        Route::resource('/staffs','StaffController');
        Route::get('/mystaffs','StaffController@index');
        Route::post('/field-staff-enable-disable/{id}','StaffController@enableDisableStaff');
        Route::post('/supervisor-enable-disable/{id}','SupervisorController@enableDisableSupervisor');

        //assign supervisor to regional Admin
        Route::get('/assignRegioalAdmin/{id}','RegionalAdminController@viewAssignForm');
        //Fetch all the supervisors created by yours regional admins
        Route::get('/regionalAdminSupervisors','SupervisorController@regionalAdminSupervisors');
        Route::get('/allRegionalSupervisors','SupervisorController@allRegionalSupervisors');
        Route::resource('/supervisors','SupervisorController');
        Route::resource('/profile','ProfileController');
        Route::get('/editPassword','ProfileController@editPassword');
        Route::put('/editPassword','ProfileController@updatePassword');
       
        Route::resource('/regions','RegionController');
        Route::get('/createMyRegion','RegionController@createMyRegion');
        Route::post('/createMyRegion','RegionController@storeMyRegion');
        Route::get('/edit-group/{name}','RegionController@editGroupRegion');
        Route::post('/edit-group/{name}','RegionController@updateGroupRegion');

        Route::get('/otherStaffs','StaffController@otherStaff');
        Route::get('/staffByRegionalAdmin','StaffController@staffByRegionalAdmin');
        //assign admin to staff 
        Route::get('/admin_staff_assign/{user}','StaffHireController@viewassign');

        Route::post('/admin_staff','StaffHireController@storeassign');

        //Region assign and select
        Route::get('/selectRegion/{region}','RegionController@selectRegion');
        Route::get('/removeRegion/{region}','RegionController@removeRegion');
        // Route::get('/assignRegion/{region}','RegionController@showAssign');
        // Route::post('/assignRegion/{region}','RegionController@storeAssign');
        Route::get('/myRegion','RegionController@myRegion');
        Route::get('/regions-list','RegionController@regionslist');
        Route::get('/remove/{group}','RegionController@removeGroupAndRegion');
        Route::get('/listEntities', function() {    return view('components/entities/listEntities');    });
        //setting (update) users profile and company by user
        Route::get('/setting',function(){ return view('profile.setting');});
        //view company data and update
        Route::get('/company','CompanyController@myCompany');
        Route::get('/updatecompany','CompanyController@editCompany');
        Route::put('/updatecompany/{company}','CompanyController@updateCompany');

        // entity tracking form routes
        Route::get('entities-forms/{entities_form}/submit-super-admin/{submit}', 'EntityFormController@submitSuperAdmin');
        Route::get('entities-forms/{entities_form}/assign','EntityFormController@getEntitiesFormStaffs');
        Route::post('entities-forms/{entities_form}/assign', 'EntityFormController@assignForm');
        Route::resource('entities-forms', 'EntityFormController');

        Route::get('entities-forms/{entities_form}/entities-form-data/{entities_form_data}/approve', 'EntityFormDataController@approve');
        Route::get('entities-forms/{entities_form}/entities-form-data/{entities_form_data}/reject', 'EntityFormDataController@reject');
        Route::post('entities-forms/{entities_form}/entities-form-data/{entities_form_data}/assign-type-client', 'EntityFormDataController@assignTypeAndClient');
        Route::resource('entities-forms/{entities_form}/entities-form-data', 'EntityFormDataController');
        
        Route::delete('entity-data/{entity_id}', 'EntityFormDataController@deleteEntityData');
        Route::view('entities', 'entities_form');
        Route::view('entities-form/{path?}', 'entities_form');
        Route::view('entities-form-view/{id}', 'entities_form');
        Route::view('entities-form/{entities_form}/entities-form-data/{path?}', 'entities_form_data');
        
        Route::get('entity-info-view/{id}', 'EntityFormDataController@getEntityData');
        Route::view('entity-data-view/{id}', 'entities_form_data');
        Route::view ('/staff-attendance', 'components.attendance.staff');
        Route::get('/api/staff-attendance', 'StaffAttendanceController@index');
        Route::get('/api/staff-attendance-report', 'StaffAttendanceController@generateStaffAttendanceReport');
    });


    //Accept or reject pointer 
    Route::prefix('supervisor')->middleware(['auth'])->group( function () {
        Route::get('/entities-form','RejectDynamicFormDataController@index');
        Route::get('/entities-form/{entities_form}/entities-form-data','RejectDynamicFormDataController@getEntitiesFormDataForForm');
        Route::get('entities-form-datum/accept/{id}','RejectDynamicFormDataController@accept');
        Route::get('entities-form-datum/reject/{id}','RejectDynamicFormDataController@reject');
        Route::get('entities-form-data/acceptedList','RejectDynamicFormDataController@acceptedEntityList');
        Route::get('entities-form-data/rejectedList','RejectDynamicFormDataController@rejectedEntityList');
        Route::get('entities-form-datum/removeEntity/{id}','RejectDynamicFormDataController@removeEntityList');
    });

    //map location
    Route::view('/map-location', 'components.map.index');
    Route::view('/map-location/{id}', 'components.map.index');
    Route::get('/entities-location/{id}', 'EntityFormDataController@getEntityLocation');
    Route::get('staff-current-location/{staff_id}', 'StaffLocationController@getLocation');

    //report form part
    Route::resource('report', 'ReportController');

    //regular report data
    Route::resource('report-data', 'ReportDataController');
    Route::get('all-report-list', 'ReportDataController@allReportsList');
    Route::post('generate-report/{id}', 'ReportDataController@reportGenerate');
    Route::get('report-detail/{id}', 'ReportController@reportDetail');
    Route::get('report-list/{status}/{id}', 'ReportDataController@reportList');
    Route::get('/report-data-bulk-approve/{id}', 'ReportDataController@reportBulkApprove');
    Route::get('/report-data-detail/{id}', 'ReportDataController@getReportDetail');
    Route::get('/report-detail/{reportdata_id}/{entitygroup_id}', 'ReportDataController@reportDetail');
    Route::get('get-report-images/{entity_id}', 'ReportDataController@getReportImages');
    Route::get('get-entity-list/{id}', 'ReportDataController@getEntityList');
    Route::post('all-staff-list', 'ReportDataController@allStaffList');
    Route::post('assign-report-staff', 'ReportDataController@assignStaff');

    //blade view page for SPA
    Route::view('/report-form', 'components.reports.report_form.index');
    Route::view('report-form/{path?}', 'components.reports.report_form.index');
    Route::view('/report-assign', 'components.reports.report_form.index');//assign regular report form
    Route::view('/report-form-assigned', 'components.reports.report_form.index');//regular report form assign to login user 
    Route::view('/report-form-assign-by-you', 'components.reports.report_form.index');//regular report form assign by you

    Route::view('/report-info', 'components.reports.report_data.index');
    Route::view('/report-info/listing', 'components.reports.report_data.index');
    Route::view('/report-info/listing/{id}', 'components.reports.report_data.index');

    Route::view('/report-info/detail/{id}', 'components.reports.report_data.index');
    Route::view('/report-info/view/{id}', 'components.reports.report_data.index');
    Route::view('/report-generate', 'components.reports.report_data.index');

    //Assign staff/remove to/from supervisor
    Route::group(['middleware' => ['auth','role:Regional Admin']], function () {
        Route::get('assign/staff/to/supervisor/{supervisor}','SupervisorController@assignStaff');//view form
        Route::post('assign/staff/to/supervisor/{supervisor}','SupervisorController@storeStaff');//store data
        Route::get('remove/staff/from/supervisor/{supervisor}','SupervisorController@removeStaff');//view form
        Route::post('remove/staff/from/supervisor/{supervisor}','SupervisorController@updateStorage');//view form
        Route::get('supervisors/delete/{supervisor}','SupervisorController@destroy');
    });

    //report assign
    Route::get('/my-report-list','AssignedReportFormController@index');
    Route::get('/report-form-assign-list','AssignedReportFormController@listAssignedReportForm');//assign to user
    Route::get('/report-form-assign-by-user','AssignedReportFormController@AssignedReportForm');//assign by user
    Route::get('/my-report-list/{role_id}','AssignedReportFormController@assignToNames');
    Route::post('/my-report-list','AssignedReportFormController@assignForm');
    Route::get('/report-assign/{assigned_report_form}','AssignedReportFormController@assignForm');
    Route::delete('/remove-regular-report-form/{report_id}/{assigned_id}','AssignedReportFormController@RemoveRegularReportForm');

    //pictorial history
    Route::group(['middleware' => ['auth','role:Super Admin|Admin|Regional Admin|Supervisor']], function () {
        Route::view('/entities-history', 'components.pictorial.index');
        Route::view('/report-info/view/{id}', 'components.pictorial.index');
        Route::view('/entities-history/{id}', 'components.pictorial.index');

        Route::get('/all-entities/{status}','PictorialHistoryController@index')->where('status', '[a-z]+');;
        Route::get('/all-entities/{id}','PictorialHistoryController@show')->where('id', '[0-9]+');
        Route::post('entity-data-approve-reject/{status}/{id}','RejectDynamicFormDataController@changeEntityStatus');
    });

    Route::group(['middleware' => ['auth','role:Super Admin|Admin|Regional Admin']], function () {
        Route::resource('group-entites','EntityGroupController');

        Route::view('/assign-entities-form', 'components.entities.assign');
        Route::view('/assign-entities-form/remove','components.entities.assign');
    
        Route::get('/list-entity-tracking-form','EntityTrackingFormAssignController@index');
        Route::get('/entity-track-form-assign-to/{region_id}','EntityTrackingFormAssignController@filedStaffList');
        Route::post('/assign-entity-track-form','EntityTrackingFormAssignController@assign');
        Route::get('/assigned-entity-tracking-form','EntityTrackingFormAssignController@assignedList');
        Route::delete('/remove-entity-tracking-form/{form_id}/{assigned_id}','EntityTrackingFormAssignController@removeEntityForm');
    });

    Route::group(['middleware' => ['auth','role:Admin|Regional Admin|Supervisor']], function () {
        Route::view('/client/report-form/assigned-list', 'components.reports.report_form.index');
        Route::view('/client/report-form/{id}', 'components.reports.report_form.index');
        Route::view('/client/entity-form/assigned-list', 'components.entity.index');
        Route::view('/client/entity-form/{id}', 'components.entity.index');

        Route::get('/clients/report-form/assigned-list', 'ReportController@getReportFormAssignedForms');
        Route::get('/clients/report-form/{id}', 'ReportController@getReportFormAssignedList');
        Route::get('/clients/entity-form/assigned-list', 'EntityFormController@getEntityFormAssignedForms');
        Route::get('/clients/entity-form/{id}', 'EntityFormController@getEntityFormAssignedList');

        Route::post('/generate-entity-form-report', 'GenerateReportController@entityReportGenerate');
        Route::post('/generate-report-form-report', 'GenerateReportController@reportReportGenerate');
    });

    Route::group(['middleware' => ['auth','role:Super Admin']], function () {
        //reset user password by the super admin only
        Route::get('/resetPassword','ProfileController@viewReset');
        Route::put('/resetPassword','ProfileController@storeResetPassword');
        //assign staff to admin
        Route::get('/assign_staff/{user}','AdminController@viewassign');
        Route::post('/assign_staff','AdminController@storeassign');
        Route::get('/remove_staff/{user}','AdminController@viewrAssociatedStaffs');
        Route::post('/remove_staff/{user}','AdminController@removeStaffs');
    
        Route::resource('/admins','AdminController')->middleware('auth');
        Route::get('/super/entities-location-by-form/{id}', 'SuperAdmin\EntityDataController@getEntityByForm');

        //super admin report part view
        Route::view('/super/report-form/create', 'components.super-admin.report');
        Route::view('/super/report-form/update/{id}', 'components.super-admin.report');
        Route::view('/super/report-form/list', 'components.super-admin.report');
        Route::view('/super/report-form/assign', 'components.super-admin.report');
        Route::view('/super/report-form/client-list', 'components.super-admin.report');
        Route::view('/super/report-data/list', 'components.super-admin.report');
        Route::view('/super/report-data/list/{id}', 'components.super-admin.report');
    
        //Super admin entity part view
        Route::view('/super/entity-form/create', 'components.super-admin.entity');
        Route::view('/super/entity-form/update/{id}', 'components.super-admin.entity');
        Route::view('/super/entity-form/list', 'components.super-admin.entity');
        Route::view('/super/entity-form/assign', 'components.super-admin.entity');
        Route::view('/super/entity-form/client-list', 'components.super-admin.entity');
        Route::view('/super/entity-data/list', 'components.super-admin.entity');
        Route::view('/super/entity-data/list/{id}', 'components.super-admin.entity');
    
        //api for report
        Route::resource('/superadmin/report-form','SuperAdmin\ReportController');
        
        Route::resource('/superadmin/report-data','SuperAdmin\ReportDataController');
        Route::post('/superadmin/report', 'SuperAdmin\ReportController@store');
        Route::get('/superadmin/report-data-bulk-approve/{id}', 'ReportDataController@reportBulkApprove');
        Route::get('/superadmin/get-entity-list/{id}', 'SuperAdmin\ReportDataController@getEntityList');
        Route::post('/superadmin/all-staff-list', 'SuperAdmin\ReportDataController@allStaffList');
        Route::post('superadmin/assign-report-staff', 'SuperAdmin\ReportDataController@assignStaff');
        Route::get('/superadmin/report-detail/{reportdata_id}/{entitygroup_id}', 'ReportDataController@reportDetail');
        Route::get('/superadmin/client-report-view', 'SuperAdmin\ReportController@clientReportViewAccess');
        Route::post('/superadmin/assign-report-client/{client_id}', 'SuperAdmin\ReportController@clientReportAssign');
        
        //api for entity
        Route::resource('/superadmin/entity-form', 'SuperAdmin\EntityController');
        Route::resource('/superadmin/entity-data', 'SuperAdmin\EntityDataController');
        Route::get('/superadmin/entity-count/{form_id}', 'SuperAdmin\EntityDataController@entityDataCount');
        Route::get('/superadmin/assigned-form-staff', 'SuperAdmin\EntityController@getFormAssignedStaff');
        Route::post('/superadmin/entity-assign-staff/{entity_form}', 'SuperAdmin\EntityController@entityAssignStaff');
        Route::get('/superadmin/entity-list/{status}/{id}', 'SuperAdmin\EntityDataController@entityList');
        Route::get('/superadmin/entity-data-bulk-approve/{id}', 'SuperAdmin\EntityDataController@entityBulkApprove');
        Route::get('/superadmin/client-entity-view', 'SuperAdmin\EntityController@clientEntityViewAccess');
        Route::post('/superadmin/assign-entity-client/{client_id}', 'SuperAdmin\EntityController@clientEntityAssign');
        //report generated by admin
        Route::get('/list-company','GenerateReportController@listCompany');
        Route::get('/form-list/{company_id}/{form_type}','GenerateReportController@FormList');
        Route::post('/download-report/{id}','SuperAdmin\GenerateReportController@reportGenerate');
        Route::post('/superadmin/generate-report-form-report', 'SuperAdmin\GenerateReportController@reportReportGenerate');
        Route::post('/superadmin/generate-entity-form-report', 'SuperAdmin\GenerateReportController@entityReportGenerate');
        Route::view('/generate-report', 'components.generate.index');

    });
//Stock Register
Route::group(['middleware' => ['auth','role:Admin']], function () {
    Route::view('/stock/category-list', 'components.stock.category.index');
    Route::view('/stock/category-create', 'components.stock.category.index');

    Route::view('/stock/item-list', 'components.stock.item.index');
    Route::view('/stock/item-create', 'components.stock.item.index');

    Route::resource('stock/item', 'Stock\ItemController');
    Route::resource('stock/category', 'Stock\CategoryController');
    Route::view('/stock/upload-stock', 'components.stock.upload_stock');
    Route::post('/stock/upload-stock-item', 'Stock\InwardStockController@storeStock');
    Route::resource('/stock/inward-stock', 'Stock\InwardStockController')->only(['store', 'update', 'destroy']);
});
Route::group(['middleware' => ['auth','role:Admin|Regional Admin']], function () {
    Route::resource('stock/item', 'Stock\ItemController')->only(['index']);
    Route::resource('stock/category', 'Stock\CategoryController')->only(['index']);
    // Route::view('/stock/opening', 'components.stock.open');
    // Route::view('/stock/opening-list', 'components.stock.open');
    // Route::view('/stock/inward', 'components.stock.inward');
    Route::view('/stock/inward-list', 'components.stock.inward');
    Route::view('/stock/outward-list', 'components.stock.inward');

    Route::get('/stock/item/category/{categoryId}', 'Stock\ItemController@getItem');
    // Route::get('/stock/open-stock/{categoryId}', 'Stock\OpenStockController@index');
    // Route::resource('/stock/open-stock', 'Stock\OpenStockController')->only(['store', 'update', 'destroy']);
    Route::get('/stock/inward-stock/{categoryId}', 'Stock\InwardStockController@index');    
    Route::get('/stock/outward-stock/{categoryId}', 'Stock\OutwardStockController@index');    
    Route::resource('/stock/outward-stock', 'Stock\OutwardStockController')->only(['update', 'destroy']); 
});
Route::group(['middleware' => ['auth','role:Admin|Regional Admin|Supervisor']], function () {
    Route::view('/stock-register', 'components.stock.register');
    Route::view('/stock/outward-item-detail/{id}', 'components.stock.outward_item_detail');
    // Route::view('/stock/balance-sheet/{itemId}', 'components.stock.balance_sheet');
    Route::get('/stock/stock-register/{categoryId}', 'Stock\StockRegisterController@stock');
    // Route::get('/stock/item/balance-sheet/{itemId}', 'Stock\StockRegisterController@stockItemBalanceSheet');
    Route::get('/stock/item/outward-item-detail/{id}', 'Stock\OutwardStockController@outwardItemDetail');
    // Route::post('/stock/generate-item-report/{itemId}', 'Stock\StockRegisterController@generateItemReport');
    Route::get('/stock/generate-item-stock-category-report/{categoryId}', 'Stock\StockRegisterController@generateItemCategoryReport');
    Route::get('/stock/generate-inward-stock-report/{categoryId}', 'Stock\InwardStockController@generateInwardStockReport');
    Route::get('/stock/generate-outward-stock-report', 'Stock\OutwardStockController@generateOutwardStockReport');
    Route::get('stock/download-stock-template-file', 'Stock\InwardStockController@downloadTemplateFile');
});
Route::get('apk-app-download', function(){
    // $headers = array(
    //     'Content-Type: application/vnd.android.package-archive',
    // );
    // return response()->download(storage_path('app/retailbaseline.apk'), 'retailbaseline.apk', $headers);

    return response()->file(storage_path('app/retailbaseline.apk') ,[
		'Content-Type'=>'application/vnd.android.package-archive',
	    'Content-Disposition'=> 'attachment; filename="retailbaseline.apk"',
    ]) ;
});
Route::get('/entity-date-change', function(){
    $entities = App\EntitiesFormData::get();
    foreach($entities as $entity){
        $entity->update(['filled_date' => Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $entity->created_at, 'UTC')->timezone('Asia/Kathmandu')->format('Y-m-d')]);
    }
});
Route::get('remove-entity/{remove_id}', function($remove_id){
    $entities_group = App\Entitygroup::get();
    foreach($entities_group as $group){
        $ids = json_decode($group->entity_ids, true);
        if(in_array( $remove_id, $ids)){
            $filter_ids = array_filter($ids, function($id) use ($remove_id){
                if($id != $remove_id) return true; return false;
            });
            $group->entity_ids = json_encode($filter_ids);
            $group->save();
        }
    }
});

//reset password of user by creator 
Route::group(['middleware' => ['auth', 'role:Admin|Regional Admin']], function () {
    Route::get('/subordinate/{id}/reset/password','ProfileController@resetUserPassword')->name('subordinate.resetPassword');
    Route::put('/subordinate/reset/password','ProfileController@storeResetUserPassword')->name('subordinate.storeResetPassword');
});
//Content for Dashboard
Route::group(['middleware' => ['auth', 'role:Super Admin|Admin|Regional Admin|Supervisor']], function () {
    Route::get('/user-details','UserController@userDetail');
    Route::get('pie-chart-staff-attendance', 'StaffAttendanceController@pieChartAttendance');
    Route::get('/stock/stock-register/{categoryId}', 'Stock\StockRegisterController@stock');
    Route::resource('stock/category', 'Stock\CategoryController')->only('index');
    Route::get('report-forms', 'ReportController@getReportForm');
    Route::get('daily-report-count/{form_id}', 'ReportDataController@getDailyReportCount');
});

