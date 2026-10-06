<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mkacontrol;
use App\Http\Controllers\PasswordController;

Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.edit');
Route::post('/change-password', [PasswordController::class, 'update'])->middleware('throttle:5,1')->name('password.update');
Route::post('/change-password/exit', [PasswordController::class, 'exit'])->name('password.exit');
use App\Http\Controllers\PrintController;

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

Route::middleware(['opttoken.login'])->group(function () {

   
    Route::get('/', [Mkacontrol::class,'dashboard_admin'])->name('dashboard_admin');

Route::get('/sessionList', [Mkacontrol::class,'sessionList'])->name('sessionList');


Route::get('/admin/users', [Mkacontrol::class,'admin_users'])->name('admin_users');

Route::get('/testconnection', [Mkacontrol::class,'testconnection'])->name('testconnection');

Route::get('/login', [Mkacontrol::class,'login_page'])->name('login_page');

Route::get('/session-store-check', [Mkacontrol::class,'sessionStoreCheck']);


Route::get('/logout', [Mkacontrol::class,'logoff_admin'])->name('logoff_admin');

Route::get('/403', [Mkacontrol::class,'notfound'])->name('notfound');

Route::post('/authenticate/login', [Mkacontrol::class,'login_admin'])->name('login_admin');

//MAIN MENU------

Route::get('/dashboard', [Mkacontrol::class,'dashboard_admin'])->name('dashboard_admin');

Route::get('/imd/openprojection', [Mkacontrol::class,'open_projection'])->name('open_projection');

Route::get('/modules/approvals', [Mkacontrol::class,'approvals'])->name('approvals');

Route::get('/modules/approvals/projection', [Mkacontrol::class,'approvals_projection'])->name('approvals_projection');

Route::get('/modules/approvals/final/projection', [Mkacontrol::class,'approvals_final_projection'])->name('approvals_final_projection');

Route::get('/modules/approvals/allocationrequest/in', [Mkacontrol::class,'approvals_allocationrequest_in'])->name('approvals_allocationrequest_in');

Route::get('/modules/approvals/allocationrequest/out', [Mkacontrol::class,'approvals_allocationrequest_out'])->name('approvals_allocationrequest_out');

Route::get('/modules/approvals/convertallocation/request', [Mkacontrol::class,'approvals_convertallocation'])->name('approvals_convertallocation');

Route::get('/modules/customerlinkaccounts', [Mkacontrol::class,'customer_link_accounts'])->name('customer_link_accounts');

Route::get('/modules/createprojection', [Mkacontrol::class,'create_projection'])->name('create_projection');

Route::get('/modules/convertallocation', [Mkacontrol::class,'convert_allocation'])->name('convert_allocation');

Route::get('/modules/viewprojectionprogress', [Mkacontrol::class,'view_projection_progress'])->name('view_projection_progress');

Route::get('/modules/allocationrequest', [Mkacontrol::class,'allocation_request'])->name('allocation_request');
Route::get('/notfound', [Mkacontrol::class,'notfound'])->name('notfound');

Route::get('/modules/allocationrequest/new', [Mkacontrol::class,'create_allocation_request'])->name('create_allocation_request');

Route::get('/imd/update_push_list/', [Mkacontrol::class,'update_push_list'])->name('update_push_list');

Route::get('/imd/finalizereqadjustment/', [Mkacontrol::class,'finalize_req_adjustment'])->name('finalize_req_adjustment');

Route::get('/imd/stockallocation/', [Mkacontrol::class,'stock_allocation'])->name('stock_allocation');

Route::get('/ccd/updatecustomertemp/', [Mkacontrol::class,'update_customer_temp'])->name('update_customer_temp');

Route::get('/reports/allocationrequestprogress/', [Mkacontrol::class,'reports_allocation_request_progress'])->name('reports_allocation_request_progress');
Route::get('/reports/projectionbreakdown/', [Mkacontrol::class,'reports_projection_breakdown'])->name('reports_projection_breakdown');
Route::get('/reports/projectionprogress/', [Mkacontrol::class,'reports_projection_progress'])->name('reports_projection_progress');
Route::get('/reports/projectionapprovalstatus/', [Mkacontrol::class,'reports_projection_approval_status'])->name('reports_projection_approval_status');
Route::get('/reports/stockallocationsummary/', [Mkacontrol::class,'reports_stock_allocation_summary'])->name('reports_stock_allocation_summary');
Route::get('/reports/alloctransferconvertsummary/', [Mkacontrol::class,'reports_alloctransfer_convert_summary'])->name('reports_alloctransfer_convert_summary');


});




//---

//--- crud get data

Route::get('/get_projection_minidashboard', [Mkacontrol::class,'get_projection_minidashboard'])->name('get_projection_minidashboard');

Route::get('/get_projection_period_status', [Mkacontrol::class,'get_projection_period_status'])->name('get_projection_period_status');

Route::get('/get_mainprojection_list', [Mkacontrol::class,'get_mainprojection_list'])->name('get_mainprojection_list');

Route::get('/get_projectionid_details', [Mkacontrol::class,'get_projectionid_details'])->name('get_projectionid_details');

Route::get('/get_pernr_customer', [Mkacontrol::class,'get_pernr_customer'])->name('get_pernr_customer');

Route::get('/approvals_count', [Mkacontrol::class,'approvals_count'])->name('approvals_count');

Route::get('/submit_find_item', [Mkacontrol::class,'submit_find_item'])->name('submit_find_item');

Route::get('/submit_find_item_inprojectiond', [Mkacontrol::class,'submit_find_item_inprojectiond'])->name('submit_find_item_inprojectiond');

Route::get('/submit_find_item_inallocated', [Mkacontrol::class,'submit_find_item_inallocated'])->name('submit_find_item_inallocated');

Route::get('/submit_find_publisher', [Mkacontrol::class,'submit_find_publisher'])->name('submit_find_publisher');

Route::get('/get_projperiod_details', [Mkacontrol::class,'get_projperiod_details'])->name('get_projperiod_details');


Route::get('/get_customer_projectiond', [Mkacontrol::class,'get_customer_projectiond'])->name('get_customer_projectiond');


Route::get('/dashboard_graphs_data', [Mkacontrol::class,'dashboard_graphs_data'])->name('dashboard_graphs_data');

Route::get('/submit_find_customer', [Mkacontrol::class,'submit_find_customer'])->name('submit_find_customer');



//----

//DATATABLES----


Route::get('/datatable_open_projection_list_table', [Mkacontrol::class,'datatable_open_projection_list_table']);

Route::get('/datatable_users_list_table', [Mkacontrol::class,'datatable_users_list_table']);

Route::get('/datatable_create_projection_customer_list', [Mkacontrol::class,'datatable_create_projection_customer_list']);

Route::get('/datatable_create_projection_customer_isbn_list', [Mkacontrol::class,'datatable_create_projection_customer_isbn_list']);

Route::get('/datatable_for_approval_projection_customer_list', [Mkacontrol::class,'datatable_for_approval_projection_customer_list']);

Route::get('/datatable_for_approval_projection_customer_isbn_list', [Mkacontrol::class,'datatable_for_approval_projection_customer_isbn_list']);

Route::get('/datatable_for_approval_projection_final_customer_list', [Mkacontrol::class,'datatable_for_approval_projection_final_customer_list']);

Route::get('/datatable_for_approval_projection_final_customer_isbn_list', [Mkacontrol::class,'datatable_for_approval_projection_final_customer_isbn_list']);

Route::get('/datatable_approvals_list_table', [Mkacontrol::class,'datatable_approvals_list_table']);

Route::get('/datatable_customer_saleshistory', [Mkacontrol::class,'datatable_customer_saleshistory']);

Route::get('/datatable_allocation_request_list_table', [Mkacontrol::class,'datatable_allocation_request_list_table']);


Route::get('/datatable_view_projection_progress_customer_isbn_list', [Mkacontrol::class,'datatable_view_projection_progress_customer_isbn_list']);

Route::get('/datatable_customer_link_accounts', [Mkacontrol::class,'datatable_customer_link_accounts']);

Route::get('/datatable_create_allocation_request_balance_table', [Mkacontrol::class,'datatable_create_allocation_request_balance_table']);

Route::get('/datatable_update_push_list_isbn_table', [Mkacontrol::class,'datatable_update_push_list_isbn_table']);

Route::get('/datatable_insertedisbn_finalreq_list', [Mkacontrol::class,'datatable_insertedisbn_finalreq_list']);

Route::get('/datatable_approved_finalreq_list', [Mkacontrol::class,'datatable_approved_finalreq_list']);

Route::get('/datatable_disapproved_finalreq_list', [Mkacontrol::class,'datatable_disapproved_finalreq_list']);

Route::get('/datatable_stock_allocation_list', [Mkacontrol::class,'datatable_stock_allocation_list']);

Route::get('/datatable_stockallocate_isbn_user_list', [Mkacontrol::class,'datatable_stockallocate_isbn_user_list']);

Route::get('/datatable_soh_isbn_per_location_list', [Mkacontrol::class,'datatable_soh_isbn_per_location_list']);

Route::get('/datatable_allocreq_details', [Mkacontrol::class,'datatable_allocreq_details']);

Route::get('/datatable_allocreq_pernr_new_title', [Mkacontrol::class,'datatable_allocreq_pernr_new_title']);

Route::get('/datatable_for_approval_allocreq_details', [Mkacontrol::class,'datatable_for_approval_allocreq_details']);

Route::get('/datatable_tempcustomertitle_list', [Mkacontrol::class,'datatable_tempcustomertitle_list']);

Route::get('/datatable_for_approval_allocreq_out_details', [Mkacontrol::class,'datatable_for_approval_allocreq_out_details']);

Route::get('/datatable_reports_projprogress', [Mkacontrol::class,'datatable_reports_projprogress']);

Route::get('/datatable_titleprojectionae_list', [Mkacontrol::class,'datatable_titleprojectionae_list']);

Route::get('/datatable_projsummary_finalreq_list', [Mkacontrol::class,'datatable_projsummary_finalreq_list']);

Route::get('/datatable_generate_allocation_isbn', [Mkacontrol::class,'datatable_generate_allocation_isbn']);

Route::get('/datatable_reports_projapprovalstatus', [Mkacontrol::class,'datatable_reports_projapprovalstatus']);

Route::get('/datatable_reports_booktitleprojtnbreakdown', [Mkacontrol::class,'datatable_reports_booktitleprojtnbreakdown']);

Route::get('/datatable_reports_stockallocationsummary', [Mkacontrol::class,'datatable_reports_stockallocationsummary']);

Route::get('/datatable_dashboard_allocationsummary_list', [Mkacontrol::class,'datatable_dashboard_allocationsummary_list']);

Route::get('/datatable_dashboard_projectionsummary_list', [Mkacontrol::class,'datatable_dashboard_projectionsummary_list']);

Route::get('/datatable_dashboard_bsoallocation_list', [Mkacontrol::class,'datatable_dashboard_bsoallocation_list']);

Route::get('/datatable_update_push_list_existing_isbn_list', [Mkacontrol::class,'datatable_update_push_list_existing_isbn_list']);

Route::get('/datatable_convertalloc_balance_table', [Mkacontrol::class,'datatable_convertalloc_balance_table']);

Route::get('/datatable_convert_alloc_list_table', [Mkacontrol::class,'datatable_convert_alloc_list_table']);

Route::get('/datatable_for_approval_convertalloc_list', [Mkacontrol::class,'datatable_for_approval_convertalloc_list']);

Route::get('/datatable_dashboard_titlecustomerprojection_list', [Mkacontrol::class,'datatable_dashboard_titlecustomerprojection_list']);

Route::get('/datatable_reports_alloctransferconvertsummary', [Mkacontrol::class,'datatable_reports_alloctransferconvertsummary']);

Route::get('/datatable_update_customer_temp_list', [Mkacontrol::class,'datatable_update_customer_temp_list']);

Route::get('/datatable_changeisbn_projection_list', [Mkacontrol::class,'datatable_changeisbn_projection_list']);

Route::get('/datatable_create_projection_saved_customer', [Mkacontrol::class,'datatable_create_projection_saved_customer']);
//---


//CRUD-----


Route::post('/submit_change_isbn_projection', [Mkacontrol::class,'submit_change_isbn_projection'])->name('submit_change_isbn_projection');

Route::post('/submit_add_new_projectionperiod', [Mkacontrol::class,'submit_add_new_projectionperiod'])->name('submit_add_new_projectionperiod');

Route::post('/submit_update_status_projectionid', [Mkacontrol::class,'submit_update_status_projectionid'])->name('submit_update_status_projectionid');

Route::post('/submit_approve_projection', [Mkacontrol::class,'submit_approve_projection'])->name('submit_approve_projection');

Route::post('/submit_approve_projection_final', [Mkacontrol::class,'submit_approve_projection_final'])->name('submit_approve_projection_final');

Route::post('/submit_create_projection_add_new_customer', [Mkacontrol::class,'submit_create_projection_add_new_customer'])->name('submit_create_projection_add_new_customer');

Route::post('/submit_new_projection', [Mkacontrol::class,'submit_new_projection'])->name('submit_new_projection');

Route::post('/submit_add_new_link_customer', [Mkacontrol::class,'submit_add_new_link_customer'])->name('submit_add_new_link_customer');

Route::post('/submit_return_projection', [Mkacontrol::class,'submit_return_projection'])->name('submit_return_projection');

Route::post('/submit_create_allocation_request', [Mkacontrol::class,'submit_create_allocation_request'])->name('submit_create_allocation_request');

Route::post('/submit_new_pushlist_isbn', [Mkacontrol::class,'submit_new_pushlist_isbn'])->name('submit_new_pushlist_isbn');

Route::post('/submit_update_pushlist_sap_isbn', [Mkacontrol::class,'submit_update_pushlist_sap_isbn'])->name('submit_update_pushlist_sap_isbn');

Route::post('/update_status_pushlistisbn', [Mkacontrol::class,'update_status_pushlistisbn'])->name('update_status_pushlistisbn');

Route::post('/approve_projection_isbn', [Mkacontrol::class,'approve_projection_isbn'])->name('approve_projection_isbn');

Route::post('/approve_projection_final_isbn', [Mkacontrol::class,'approve_projection_final_isbn'])->name('approve_projection_final_isbn');

Route::post('/return_projection_isbn', [Mkacontrol::class,'return_projection_isbn'])->name('return_projection_isbn');

Route::post('/submit_finalreq', [Mkacontrol::class,'submit_finalreq'])->name('submit_finalreq');

Route::post('/submit_allocate_qty', [Mkacontrol::class,'submit_allocate_qty'])->name('submit_allocate_qty');

Route::post('/submit_update_allocreq_qty', [Mkacontrol::class,'submit_update_allocreq_qty'])->name('submit_update_allocreqd_qty');

Route::post('/submit_allocreq_forapproval', [Mkacontrol::class,'submit_allocreq_forapproval'])->name('submit_allocreq_forapproval');

Route::post('/submit_allocreq_cancel', [Mkacontrol::class,'submit_allocreq_cancel'])->name('submit_allocreq_cancel');

Route::post('/submit_remove_zero_projtn', [Mkacontrol::class,'submit_remove_zero_projtn'])->name('submit_remove_zero_projtn');

Route::post('/submit_allocreq_isbn_remove', [Mkacontrol::class,'submit_allocreq_isbn_remove'])->name('submit_allocreq_isbn_remove');

Route::post('/submit_allocreq_isbn_cancel', [Mkacontrol::class,'submit_allocreq_isbn_cancel'])->name('submit_allocreq_isbn_cancel');

Route::post('/submit_allocreq_new_title', [Mkacontrol::class,'submit_allocreq_new_title'])->name('submit_allocreq_new_title');

Route::post('/submit_approve_allocreqin', [Mkacontrol::class,'submit_approve_allocreqin'])->name('submit_approve_allocreqin');

Route::post('/submit_return_allocreqin', [Mkacontrol::class,'submit_return_allocreqin'])->name('submit_return_allocreqin');

Route::post('/submit_convertalloc_new', [Mkacontrol::class,'submit_convertalloc_new'])->name('submit_convertalloc_new');

Route::post('/submit_approve_allocreqout', [Mkacontrol::class,'submit_approve_allocreqout'])->name('submit_approve_allocreqout');

Route::post('/submit_return_allocreqout', [Mkacontrol::class,'submit_return_allocreqout'])->name('submit_return_allocreqout');

Route::post('/submit_update_proposedreq_qty', [Mkacontrol::class,'submit_update_proposedreq_qty'])->name('submit_update_proposedreq_qty');

Route::post('/submit_update_finalreq_buffstock', [Mkacontrol::class,'submit_update_finalreq_buffstock'])->name('submit_update_finalreq_buffstock');

Route::post('/submit_create_projection_forapproval', [Mkacontrol::class,'submit_create_projection_forapproval'])->name('submit_create_projection_forapproval');

Route::post('/update_status_existing_pushlistisbn', [Mkacontrol::class,'update_status_existing_pushlistisbn'])->name('update_status_existing_pushlistisbn');

Route::post('/update_projection_branchwhouse_customer', [Mkacontrol::class,'update_projection_branchwhouse_customer'])->name('update_projection_branchwhouse_customer');

Route::post('/isbn_create_projection', [Mkacontrol::class,'isbn_create_projection'])->name('isbn_create_projection');

Route::post('/submit_approve_convertalloc', [Mkacontrol::class,'submit_approve_convertalloc'])->name('submit_approve_convertalloc');

Route::post('/submit_disapproved_convertalloc', [Mkacontrol::class,'submit_disapproved_convertalloc'])->name('submit_disapproved_convertalloc');

Route::post('/submit_update_customer_temp', [Mkacontrol::class,'submit_update_customer_temp'])->name('submit_update_customer_temp');

Route::post('/submit_update_enddate_projectionid', [Mkacontrol::class,'submit_update_enddate_projectionid'])->name('submit_update_enddate_projectionid');

Route::get('/submit_admin_users_retrieve_user_details', [Mkacontrol::class,'submit_admin_users_retrieve_user_details'])->name('submit_admin_users_retrieve_user_details');

Route::post('/submit_unlink_customer', [Mkacontrol::class,'submit_unlink_customer'])->name('submit_unlink_customer');

Route::post('/submit_link_customer', [Mkacontrol::class,'submit_link_customer'])->name('submit_link_customer');

Route::post('/submit_update_activestatus_user', [Mkacontrol::class,'submit_update_activestatus_user'])->name('submit_update_activestatus_user');

Route::post('/submit_admin_user_edit', [Mkacontrol::class,'submit_admin_user_edit'])->name('submit_admin_user_edit');

Route::post('/submit_changeprojection_approve_qty', [Mkacontrol::class,'submit_changeprojection_approve_qty'])->name('submit_changeprojection_approve_qty');

Route::post('/submit_changeprojection_final_approve_qty', [Mkacontrol::class,'submit_changeprojection_final_approve_qty'])->name('submit_changeprojection_final_approve_qty');

Route::post('/submit_refloat_approved_finalreq', [Mkacontrol::class,'submit_refloat_approved_finalreq'])->name('submit_refloat_approved_finalreq');

Route::post('/submit_disapprove_allocreqout', [Mkacontrol::class,'submit_disapprove_allocreqout'])->name('submit_disapprove_allocreqout');

Route::post('/submit_update_convertallocd_branchwhouse', [Mkacontrol::class,'submit_update_convertallocd_branchwhouse'])->name('submit_update_convertallocd_branchwhouse');

//---


//Print PDF-----


Route::get('/PrintForApprovalFinalReq', [Mkacontrol::class,'PrintForApprovalFinalReq'])->name('PrintForApprovalFinalReq');

Route::get('/PrintWithoutProposalFinalReq', [Mkacontrol::class,'PrintWithoutProposalFinalReq'])->name('PrintWithoutProposalFinalReq');

Route::get('/PrintWithProposalFinalReq', [Mkacontrol::class,'PrintWithProposalFinalReq'])->name('PrintWithProposalFinalReq');

//Cron Job----


Route::get('/cronInsertNonSalesTeam', [Mkacontrol::class,'cronInsertNonSalesTeam'])->name('cronInsertNonSalesTeam');

Route::get('/cronInsertSalesTeam', [Mkacontrol::class,'cronInsertSalesTeam'])->name('cronInsertSalesTeam');


//added by emrick 08-18-2026
Route::get('/test-email-ovp', [
    Mkacontrol::class,
    'testEmailOVP'
]);

Route::get(
    '/get_allocreq_justification',
    [Mkacontrol::class, 'get_allocreq_justification']
);

Route::post(
    '/submit_allocreq_justification_message',
    [
        Mkacontrol::class,
        'submit_allocreq_justification_message'
    ]
);
