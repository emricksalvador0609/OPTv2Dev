<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use App\Models\CrmTasks;
use App\Models\CrmMotherLookup;
use App\Models\CrmMotherAct;
use App\Models\CrmMotherActDept;
use App\Models\CrmCustContacts;
use App\Models\CrmUsers;
use App\Models\CrmDateRanges;
use App\Models\CrmAllocationHeader;
use App\Models\CrmProjection;
use App\Models\OPTv2ProjectionPeriod;
use App\Models\OPTv2Projectionh;
use App\Models\OPTv2Projectiond;
use App\Models\OPTv2Allocated;
use App\Models\OPTv2Files;
use App\Models\OPTv2CustomerLink;
use App\Models\OPTv2Logs;
use App\Models\OPTv2Notif;
use App\Models\OPTv2SOHQtyExc;
use App\Models\OPTv2FinalReq;
use App\Models\OPTv2UpdatePushCUST;
use App\Models\OPTv2UpdatePushISBN;
use App\Models\OPTv2AllocReqh;
use App\Models\OPTv2AllocReqd;
use App\Models\OPTv2ConvertAllocd;
use App\Models\OPTv2User;
use App\Models\OPTv2UserExp;
use App\Models\OPTv2UserAccess;
use App\Models\ZsdOmsh;
use App\Models\ZsdOmsd;
use App\Models\ZmmMatdel;
use App\Models\ZsdBsah;
use App\Models\ZsdBsad;
use App\Models\ZprUsers;
use App\Models\ZmmPrersOpenPO;
use App\Models\ZsdTs;
use App\Models\PushListTagging;
use App\Models\T001L;
use App\Models\Mara;
use App\Models\Mard;
use App\Models\Ekpo;
use App\Models\Ekko;
use App\Models\CustPos;
use App\Models\TblBudget;
use App\Models\NewSales;
use App\Models\OPTv2Justification;
use Auth;
use DB;
use PDF;




class Mkacontrol extends Controller
{

    
    //-------------


    // protected $signature = 'move:files';
    protected $description = 'Move files from subfolders to a destination folder';


    private function applyRankApprovalFilter($query, $rank,$type = 'standard')
    {
        $rank = (string) $rank;

        $pernr = session('pernr');
        $filterPernr = filter_user_list('0','1')->get();
        $arrayFilterPernr = $filterPernr->pluck('PERNR')->toArray();
    
        if($type == 'standard') {

            if (stripos($rank, 'RSM') !== false) {
                $query->whereNull('t1.APPROVED1');
            }
        
            else if (stripos($rank, 'SSM') !== false) {
                $query->whereNotNull('t1.APPROVED1')
                      ->whereNull('t1.APPROVED2');
            }
            //added by emrick 8/25/2026
            else if (stripos($rank, 'AVP') !== false) {

                // AVP must NOT see Allocation Transfer Request - In
                $query->where('t1.PERNR', '00');

            }
            else {
                
            }

        }

        if($type == 'convertalloc') {

            if (strpos($rank, 'RSM') !== false) {
                $query->whereIn('t1.PERNR', $arrayFilterPernr)
                ->whereNull('t1.APPROVED1')
                ->where('t1.TOCONVERTTYPE','nonbsa');
            }

            else if (strpos($rank, 'SSM') !== false) {
                // $query   ->whereIn('t1.PERNR', $arrayFilterPernr)
                 // ->whereNull('t1.APPROVED2');

                        $query   ->where('t1.PERNR', '00');
            }

            else if (strpos($rank, 'AE') !== false) {
                
                $query   ->whereIn('t1.PERNR', $arrayFilterPernr);

            }


            else  if (strpos($rank, 'CRM') !== false) {

                $query ->where('TOCONVERTTYPE','!=', 'nonbsa' )
                // ->whereNotNull('t1.APPROVED1')
                ->whereNull('t1.APPROVED1');
              
            }

            else  if (strpos($rank, 'CC') !== false || strpos($rank, 'AVP') !== false) {

                $query ->where('TOCONVERTTYPE','=', 'nonbsa' )
                ->whereNotNull('t1.APPROVED1')
                ->whereNull('t1.APPROVED2');
              
            }

            else{
                $query   ->where('t1.PERNR', '00');
            }

        }
        if($type == 'allocreqout') {

            if (strpos($rank, 'RSM') !== false) {
                $query ->whereIn('t2.REQTO', $arrayFilterPernr)
                            ->whereNotNull('t1.APPROVED2')
                            ->whereNull('t1.APPROVED3');
            }
            else if (strpos($rank, 'SSM') !== false) {
                    $query->whereIn('t2.REQTO', $arrayFilterPernr)
                    ->whereNotNull('t1.APPROVED2')
                    ->whereNotNull('t1.APPROVED3')
                    ->whereNull('t1.APPROVED4');
            }

            else if (strpos($rank, 'AE') !== false) {

                    $query->where('t2.REQTO', $pernr) 
                    ->whereNotNull('t1.APPROVED4')
                    ->whereNull('t1.APPROVED5');
            }
            else if (strpos($rank, 'AVP') !== false) {
                        $query ->whereNotNull('t1.APPROVED5')
                        ->whereNull('t1.APPROVED6')
                        //added by emrick 8/25/2026
                        ->where('t2.TRANSFERTYPE', 'bsa_to_nonbsa');
            }
            else {
                $query   ->where('t1.PERNR', '00');
            }

        }

  
    
        return $query;
    }


    public function testconnection(Request $request)
    {   

        $s = CrmAllocationHeader::limit(1)
        ->first();

return $s->isbn;
        // $basedocnum = '260';
        // $pernr = '00001219';
        // $rsmm = '00099985';
        // $this->EmailSubmittedForApproval($request,$basedocnum,$pernr,$rsm);

        // foreach ($q as $a) {

        // }

        // if($approvaltype == 'Projection') {

        //     if(strpos($rank,'RSM') !== false) {
            
        //         $linkforapproval= 'approvals/projection?pid='.$projdocnum.'&name='.$username.'&fname='. $fullname . ' ' . $pernr;
        //     }
        //     if(strpos($rank,'SSM') !== false) {
             
        //         $linkforapproval= 'approvals/final/projection?pid='.$projdocnum.'&name='.$username.'&fname='. $fullname . ' ' . $pernr;
        //     }

        // } 
        // if ($approvaltype == 'Alloc. Transfer Request - In') {

        //     $linkforapproval= 'approvals/allocationrequest/in?docnum=' . $docnum;
            
        // }

        // if ($approvaltype == 'Alloc. Transfer Request - Out') {

        //     $linkforapproval= 'approvals/allocationrequest/out?docnum=' . $docnum;

        // }   
    
        // if ($approvaltype == 'Convert Allocation') {

        //     $linkforapproval= 'approvals/convertallocation/request?pid='.$projdocnum.'&name='.$username.'&fname='. $fullname . ' ' . $pernr;
       

        // }

        // $this->EmailForApprovalOVP($request,'Convert Allocation','6');


        // $basedocnum = '257';
        // $isbn = [
        //     "9789719822783",
        //     "9789719824497",
        //     "9789719822745"
        // ];

        // $pernr = [
        //     "00001219"
        // ];
        // $dd = getAllocatedDeductSOH ($basedocnum,$isbn,$pernr);
        // $q = $dd->get();
        // $s = '';
        // foreach ($q as $rr) {
        //     $s .= $rr->EAN11 . ': ' . $rr->TOTALALLOCATED . '</br>' ;
        // }

        // return $s;

        // $l = '';

        // $l = pernr_customer_check_bsa_status('0000900047');
        // return $l;
        
        // $cs = [
        //     '0001804963',
        //     '0001804948',
        //     '0001800144',
          
      
        // ];

        // $k = [
        //     '9789719823209',
        //     '9789719821762',
        //     '9789719822806',
        //     '9789719806615',
        //     '9789719821762',
        //     '9789719823209',
       

        // ];
        // $l = pernrCustomerIsbnPrev3YearSalesHistory('0001804963',$k);
                    
        // $po = '';
        // foreach ($l as $aa){
        //     $po .= $aa->isbn . ' ' . $aa->KUNNR. ': ' . $aa->total_1 . '</br>';
        //     $po .= $aa->isbn . ' ' . $aa->KUNNR. ':: ' . $aa->total_2 . '</br>';
        //     $po .= $aa->isbn . ' ' . $aa->KUNNR. ':: ' . $aa->total_3 . '</br></br>';
        // }
    
        // return $po;

        // $q = ZsdOmsh::limit(500)
        //             ->where('PERNR',$pernr)
        //             ->where('ERDAT','LIKE','%2024%')
        //             ->orderBy('ERDAT','DESC')
        //             ->get();
        // $w = '</br>';

        
        // $e= formatDate('2024-05-06');

        // $ff = getPreviousYear(2);

        // $ff = '';

        // $customerTopOrdersLastYear = customerTopOrdersLastYear('2024','0001804963');

        // foreach ($customerTopOrdersLastYear as $r){
        //         $ff .= $r->EAN11 . '</br>';

        // }

        // $customerTopOrdersLastYear = topCustomerPrevYear('2024',$pernr);

        // foreach ($customerTopOrdersLastYear as $r){
        //         $ff .= $r->KUNNR . '</br>';

        // }
        // $ff= topCustomerPrevYear('2024','00001219')->get();
        
        // $ff = $p->FULLNAME;

        // $f = $this->topCustomerPrevYear('2024','00001219',$pernr = '1')->get();

        // foreach($f as $r){
        //     $w .= $r->ORDNUM . ' - ' . $r->ERDAT.  '</br> ';
        // }
                    
        // return $ff;

        // $l = ';';
     
        // $
        // return $l;
   

    }


    private function periodList_ (){
        $s = [
            "1" => "BED-JUNE",
            "2" => "HED-JUNE",
            "3" => "HED-AUGUST",
            "4" => "HED-NOVEMBER",
            "5" => "HED-JANUARY",
            "6" => "HED-SUMMER",
            
        ];
        return $s;

    }

    public function sessionStoreCheck(Request $request)
    {
        // Check if the 'staff' session exists
        if ($request->session()->has('staff')) {
            // Session is active
            return response()->json(['sessionExpired' => false]);
        }

        // Session has expired
        return response()->json(['sessionExpired' => true]);
    }



    public function update_customer_temp () 
    {

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('update_customer_temp',compact('users','filterdashboardapprover'));


    }
    public function admin_users () 
    {
        

        $date_now = date_now('dateonly');
        
        $ranklist = OPTv2User::groupBy('RANK')
                                ->select('RANK')
                                ->orderBy('RANK','ASC')
                                ->where('RANK','<>',"")
                                ->get();
     
                 
        $rsmlist = OPTv2User::select('FULLNAME','PERNR')
                                ->orderBy('FULLNAME','ASC')
                                ->where('ACTIVE','1')
                                ->where(function($query) {
                                        $query->where('RANK','RSM')
                                            ->orWhere('PERNR','00000000');
                                })
                                ->get();
                                
        $ssmlist = OPTv2User::select('FULLNAME','PERNR')
                                ->orderBy('FULLNAME','ASC')
                                ->where('ACTIVE','1')
                                ->where(function($query) {
                                    $query->where('RANK','SSM')
                                        ->orWhere('PERNR','00000000');
                            })
                                ->get();    
                                
        $division = getUserDivisionList()->get();
        
        
        return view('admin_users',compact('ranklist','rsmlist','ssmlist','division'));
        
      
    }
    public function submit_admin_user_edit(Request $request) {

        $id = $request->input('admin_user_edit_form_id');
        $pernr = $request->input('admin_user_edit_form_pernr');
        $rank = $request->input('admin_user_edit_form_rank');

        $fullname = $request->input('admin_user_edit_form_fullname');
        $username = $request->input('admin_user_edit_form_username');
        $pwd = $request->input('admin_user_edit_form_password');
        $email = $request->input('admin_user_edit_form_email');
        $rsm = $request->input('admin_user_edit_form_rsm');
        $ssm = $request->input('admin_user_edit_form_ssm');

        $status = "404";

        $updateuserinfo = OPTv2User::where('id',$id)
                        ->update([
                                    "FULLNAME"   => $fullname,
                                    "USERNAME" => $username,
                                    "PASSWORD" => $pwd,
                                    "EMAIL" => $email,
                                    "RSM" => $rsm,
                                    "SSM" => $ssm
                            ]);

        if($updateuserinfo) {

              $status = "2";

        }

         $html = "";


        $response[] = array( 
                "status" => $status,
                "html" => $html
        );

        return response()->json($response);
  
    }

    public function datatable_users_list_table(Request $request) {

        $query = OPTv2User::select('*')
                            ->orderBy('FULLNAME','ASC');

        $finalQuery = $query->get();

        $num = 0;
        if ($finalQuery->isEmpty()) {
            $response = [
                'num' => '0'
            ];
        } else {

                foreach($finalQuery as $row){

                    $num++;
                    $id = $row->id; 
                    $fullname = $row->FULLNAME; 
                    $username = $row->USERNAME; 
                    $expgroup = $row->EXPGROUP; 
                    $password = $row->PASSWORD; 
                    $email = $row->EMAIL; 
                    $rank = $row->RANK; 
                    $active = $row->ACTIVE; 
                    $division = $row->DIVISION; 
                    $pernr = $row->PERNR;
                    $rsm = $row->RSM;
                    $ssm = $row->SSM;
                
                    
                    $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm border dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2 p-1" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                        <a href="#"  class="dropdown-item admin_user_edit_btn"  data-bs-target="#adminUserEditModal" data-bs-toggle="modal" data-pernr="'.$pernr.'" data-rank="'.$rank.'" data-id="'.$id.'">
                                                        Edit</a>';
                                // <div class="dropdown-divider"></div>    
                    
                        
                    
                            $action .= "
                              </div>
                            </div>";
                            
                    // $ssmName = pernrDetails($ssm,'FULLNAME');
                    // $rsmName = pernrDetails($rsm,'FULLNAME');

                    $expgroupDisplay = ' <a href="#"  class="admin_user_edit_btn"  data-bs-target="#adminUserEditModal" 
                                        data-bs-toggle="modal" data-pernr="'.$pernr.'" data-rank="'.$rank.'" data-id="'.$id.'">
                                                        View</a>';
                    $rsmDisplay = $rsm;
                    $ssmDisplay = $ssm;
                    $checked = '';

                    if( $active == '1') {
                        $checked = 'checked';
                    }
                    $activeDisplay =  '

                        <div class="mb-0 form-switch h5 d-flex justify-content-center">
                                                    <input class="form-check-input useractive-status-sw" id="flexSwitchCheckChecked" value="'.$active.'" '.$checked.' data-id= "'.$id.'" type="checkbox" >
                                                </div>
                        ';
                      $response[] = array(
                        'num' => $num,
                        'userid' => $id,
                        'fullname' => $fullname,
                        'username' => $username,
                        'expgroup' => $expgroupDisplay,
                        'password' => $password,
                        'email' => $email,
                        'rank' => $rank, 
                        'pernr' => $pernr, 
                        'active' => $activeDisplay, 
                        'division' => $division, 
                        'rsm' => $rsmDisplay, 
                        'ssm' => $ssmDisplay, 
                        'action' => $action
                    );
                    

                }
          }

                return response()->json($response);

    }

    public function submit_update_activestatus_user(Request $request) {

        $v = $request->input('v');
        $id = $request->input('id');
        $html = "";
        $u =  OPTv2User::where('id',$id)
                                ->update([
                                    "ACTIVE" => $v
                                ]);

        if($u) {
            $status = 2;
        }
        else {
            $status = 404;
        }
    


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        


    }

    public function reports_alloctransfer_convert_summary () 
    {
        $date_now = date_now('dateonly');

        $users = filter_user_list_in_projection()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('reports_alloc_transfer_convert_summary',compact('users','filterdashboardapprover'));

    }
    public function reports_allocation_request_progress () 

    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list_in_projection()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('reports_allocation_request_progress',compact('users','filterdashboardapprover'));
        
      
    }

    
    public function notfound () 
    {
        

        return view('403');
        
      
    }

    public function reports_projection_breakdown () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $division = getUserDivisionList()->get();  
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('reports_projection_breakdown',compact('users','filterdashboardapprover','division'));
        
      
    }
    public function reports_projection_progress () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list_in_projection()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('reports_projection_progress',compact('users','filterdashboardapprover'));
        
      
    }
    public function reports_projection_approval_status () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list_in_projection()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('reports_projection_approval_status',compact('users','filterdashboardapprover'));
        
      
    }
    public function reports_stock_allocation_summary () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list_in_projection()->get();
        $division = getUserDivisionList()->get();  
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('reports_stock_allocation_summary',compact('users','filterdashboardapprover','division'));
        
      
    }

    public function get_projperiod_details(Request $request) {
        $basedocnum = $request->query('basedocnum');

        $qdashboardprojectioncount =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
        ->selectRaw("
                                 SUM(CAST(t1.PROJECTION AS INT)) as TOTALPROJTN,
                                SUM(CAST(t1.QTY AS INT)) as TOTALFINALPROJTN,

                                SUM(CASE WHEN t1.STATUS = 'returned_isbn' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_RETURNED,
                                SUM(CASE WHEN t1.STATUS = 'for_rsm_approval' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDINGRSM,
                                SUM(CASE WHEN t1.STATUS = 'for_ssm_approval' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDINGSSM,
                                SUM(CASE WHEN t1.STATUS != 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDING,
                                SUM(CASE WHEN t1.STATUS = 'saved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS SAVED,
                                SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_APPROVED,

                                SUM(CAST(t1.PROJECTION AS INT) * CAST(t1.UNITP AS DECIMAL(18,2))) AS TOTALPROJTNVALUE,
                                SUM(CASE WHEN t1.STATUS = 'returned_isbn' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_RETURNEDVALUE,
                                SUM(CASE WHEN t1.STATUS = 'for_rsm_approval' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_PENDINGRSMVALUE,
                                SUM(CASE WHEN t1.STATUS = 'for_ssm_approval' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_PENDINGSSMVALUE,
                                SUM(CASE WHEN t1.STATUS != 'approved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_PENDINGVALUE,
                                SUM(CASE WHEN t1.STATUS = 'saved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_SAVEDVALUE,
                                SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_APPROVEDVALUE
                ")
        ->where('t1.BASEDOCNUM',$basedocnum)
        ->first();
        ;

         
        $qprojperiod = projection_period_details($basedocnum);

        $projperiodstatus = $qprojperiod->STATUS;
  
        $totalprojtn = $qdashboardprojectioncount->TOTALPROJTN;
        $totalprojtnpending = $qdashboardprojectioncount->TOTAL_PENDING;
        $totalprojtnpendingrsm = $qdashboardprojectioncount->TOTAL_PENDINGRSM;
        $totalprojtnpendingssm = $qdashboardprojectioncount->TOTAL_PENDINGSSM;
        $totalprojtnapproved = $qdashboardprojectioncount->TOTAL_APPROVED;
        $totalprojtnreturned = $qdashboardprojectioncount->TOTAL_RETURNED;
        $totalfinalprojtn = $qdashboardprojectioncount->TOTALFINALPROJTN ?? 0;
        $notyetallocated = $qdashboardprojectioncount->NOTYETALLOCATED;

           // ✅ Compute completed count (lahat ng hindi pending)
           $completed = $totalfinalprojtn - ( $totalprojtnpending);
       

           // ✅ Compute percent completed (avoid divide by zero)
           $percentCompleted = $totalfinalprojtn > 0 ? round(($completed / $totalfinalprojtn) * 100) : 0 ?? 0;

           // $percentCompleted = 71;

           if ($percentCompleted <= 30) {
               $percentCompletedColor = 'bg-warning';
           } 
           elseif ($percentCompleted <= 70) {
               $percentCompletedColor = 'bg-warning'; // usually blue
           } 
           elseif ($percentCompleted <= 99) {
               $percentCompletedColor = 'bg-info'; // usually blue
           } elseif ($percentCompleted >= 100) {
               $percentCompletedColor = 'bg-success';
           } else {
               $percentCompletedColor = 'bg-danger'; // fallback
           }


           // <span class="'.$percentCompletedColor.' fw-bold">' .$percentCompleted . '%' .'</span>
           $percentCompletedDisplay = '
            
                   <div class="progress-bar p-1 fw-bold fs--1 '.$percentCompletedColor.' rounded-3" role="progressbar" style="width: '.$percentCompleted.'%" aria-valuenow="'.$totalprojtnapproved.'" aria-valuemin="0" aria-valuemax="'.$totalprojtn.'"> '.$percentCompleted.'% </div>
             
                   ';


                   $qcustomer = OPTv2Projectionh::from('OPTV2PROJECTIONH as t1')
                   ->selectRaw("
                       MAX(t1.id) as MAXID,
                       t1.CUSTOMERNAME as NAME,
                       MAX(t1.CUSTOMER) as TEMP,
                       'Customer' as TYPE
                   ")
                   ->where('BASEDOCNUM',$basedocnum)
                   ->where('t1.CUSTOMER','LIKE','%TEMP%')
                   ->groupBy('t1.CUSTOMERNAME')
                   ->get();
               
   
                   $qtitle = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                   ->selectRaw("
                       MAX(t1.id) as MAXID,
                       t1.DESCRIPTION as NAME,
                       MAX(t1.EAN11) as TEMP,
                       'Title' as TYPE
                   ")
                   ->where('BASEDOCNUM',$basedocnum)
                   ->where('t1.EAN11','LIKE','%TEMP%')
                   ->groupBy('t1.DESCRIPTION')   
                   ->get();

                   $tempcount = $qcustomer->count() + $qtitle->count();
            
                   
        $response[] = [
            "totalprojtn" => $totalprojtn,
            "percentCompletedDisplay" => $percentCompletedDisplay,
            "percentCompleted" => $percentCompleted,
            "projperiodstatus" => $projperiodstatus,
            "tempcount" => $tempcount,

        ];

        return response()->json($response);
    }
    public function dashboard_graphs_data(Request $request) {

        $basedocnum = $request->query('basedocnum');
        $pernrurl = $request->query('pernr');
        $sessionpernr = trim(session('pernr'));

        $pernr[] = $pernrurl;

        // Guard: dashboard fires this with basedocnum=null when the AE has no
        // submitted projections yet (period dropdown empty). DOCNUM is INT, so
        // the string 'null' made SQL Server throw SQLSTATE[22018] / 500.
        if ($basedocnum === null || trim($basedocnum) === '' || trim($basedocnum) === 'null') {
            return response()->json([
                'maindashboardcount' => [
                    "totalprojection" => 0,
                    "totalfinalprojection" => 0,
                    "totalprojection_amount" => "0.00",
                    "totalreturned" => 0,
                    "totalreturned_amount" => "0.00",
                    "totalprojectionapproved" => 0,
                    "totalprojectionapproved_amount" => "0.00",
                    "totalprojectionpending" => 0,
                    "totalprojectionpending_amount" => "0.00",
                    "totalallocation" => 0,
                    "totalallocation_amount" => 0,
                    "projperiodstatus" => '-',
                    "notyetallocated" => 0,
                    "totalallocated" => 0,
                    "unserved" => 0,
                    "unservedPercent" => 0,
                    "notYetAllocatedPercent" => 0,
                    "allocatedPercent" => 0,
                    "projtnpercentcomplete" => '<span class="fw-bold fs--1 text-700 rounded-3">-</span>',
                ],
                'allocatedchart' => ["bsasummary_count" => 0, "nonbsasummary_count" => 0],
                'typesummarygraph' => ["bsasummary_count" => 0, "nonbsasummary_count" => 0],
                'top10isbn' => [[0, "-"]],
            ]);
        }

        if($pernrurl == '1') {

            $pernr = filter_user_list()->pluck('PERNR')->toArray();;

        }
     
        $date_now = date_now('dateonly');

        $num = 0;     

        

// Main Dashboard Count---------------------

        $qdashboardprojectioncount =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
        ->selectRaw("
                SUM(CAST(t1.PROJECTION AS INT)) as TOTALPROJTN,
                SUM(CAST(t1.QTY AS INT)) as TOTALFINALPROJTN,
                SUM(CAST(t1.PROJECTION AS INT) * CAST(t1.UNITP AS INT)) as TOTALPROJTNAMOUNT,
                SUM(CASE WHEN t1.DATEALLOCATED IS NULL  THEN CAST(t1.QTY AS INT) ELSE 0 END) AS NOTYETALLOCATED,
                SUM(CASE WHEN t1.STATUS = 'returned_isbn' AND t1.STATUS != 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_RETURNED,
                SUM(CASE WHEN t1.STATUS = 'returned_isbn' AND t1.STATUS != 'approved' THEN CAST(t1.PROJECTION AS INT) * CAST(t1.UNITP AS INT) ELSE 0 END) AS TOTAL_RETURNEDAMOUNT,
                SUM(CASE WHEN t1.STATUS != 'approved' AND t1.STATUS != 'returned_isbn' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDING,
                SUM(CASE WHEN t1.STATUS = 'for_rsm_approval' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDINGRSM,
                SUM(CASE WHEN t1.STATUS = 'for_ssm_approval' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDINGSSM,
                SUM(CASE WHEN t1.STATUS != 'approved' AND t1.STATUS != 'returned_isbn'  THEN CAST(t1.PROJECTION AS INT) * CAST(t1.UNITP AS INT) ELSE 0 END) AS TOTAL_PENDINGAMOUNT,
                SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_APPROVED,
                SUM(CASE WHEN t2.BSA = '1' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_BSA,
                SUM(CASE WHEN t2.BSA != '1' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_NONBSA,
                SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS INT) ELSE 0 END) AS TOTAL_APPROVEDAMOUNT
                ")
        ->where('t1.BASEDOCNUM',$basedocnum)
        ->whereIn('t1.PERNR',$pernr)
        ->first();
        ;

            $qdashboardallocated =  OPTv2Allocated::selectRaw("
                                          SUM(CAST(QTY AS INT)) as TOTALALLOCATED
                                      ")
            ->where('BASEDOCNUM',$basedocnum)
            ->whereIn('PERNR',$pernr)
            ->first();
            ;

// ---------------------Main Dashboard Count

// ISBN SUMMARY GRAPH---------------------


            $qdashboardprojectiontopisbn =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
            ->selectRaw("
                    MAX(EAN11) AS EAN11,
                    DESCRIPTION,
                    SUM(CAST(t1.PROJECTION AS INT)) as TOTALPROJTNISBN
                    ")
            ->where('t1.BASEDOCNUM',$basedocnum)
            ->whereIn('t1.PERNR',$pernr)
            ->orderByRaw('TOTALPROJTNISBN DESC')
            ->groupBy('t1.DESCRIPTION')
            ->limit(10)
            ->get();

// ---------------------ISBN SUMMARY GRAPH
        
                $totalprojtn = $qdashboardprojectioncount->TOTALPROJTN;
                $totalprojtnpending = $qdashboardprojectioncount->TOTAL_PENDING;
                $totalprojtnpendingrsm = $qdashboardprojectioncount->TOTAL_PENDINGRSM;
                $totalprojtnpendingssm = $qdashboardprojectioncount->TOTAL_PENDINGSSM;
                $totalprojtnapproved = $qdashboardprojectioncount->TOTAL_APPROVED;
                $totalprojtnreturned = $qdashboardprojectioncount->TOTAL_RETURNED;
                $totalfinalprojtn = $qdashboardprojectioncount->TOTALFINALPROJTN ?? 0;
                $notyetallocated = $qdashboardprojectioncount->NOTYETALLOCATED;
                $totalallocated = $qdashboardallocated->TOTALALLOCATED;
                $unserved = $totalallocated + $notyetallocated;
                $unservedFinal = $totalfinalprojtn - $unserved;
//-----

                // Allocation Percentages
                $allocatedPercent       = $totalfinalprojtn > 0 ? round(($totalallocated / $totalfinalprojtn) * 100, 2) : 0;
                $notYetAllocatedPercent = $totalfinalprojtn > 0 ? round(($notyetallocated / $totalfinalprojtn) * 100, 2) : 0;
                $unservedPercent        = $totalfinalprojtn > 0 ? round(($unservedFinal / $totalfinalprojtn) * 100, 2) : 0;
            
                // ✅ Compute completed count (lahat ng hindi pending)
                $completed = $totalfinalprojtn - ( $totalprojtnpending);
       

                // ✅ Compute percent completed (avoid divide by zero)
                $percentCompleted = $totalfinalprojtn > 0 ? round(($completed / $totalfinalprojtn) * 100) : 0 ?? 0;

                if ($totalprojtnpending > 0 && $percentCompleted >= 100) {
                    $percentCompleted = 99;
                }   

                if ($percentCompleted <= 30) {
                    $percentCompletedColor = 'bg-warning';
                } 
                elseif ($percentCompleted <= 70) {
                    $percentCompletedColor = 'bg-warning'; // usually blue
                } 
                elseif ($percentCompleted <= 99) {
                    $percentCompletedColor = 'bg-info'; // usually blue
                } elseif ($percentCompleted >= 100) {
                    $percentCompletedColor = 'bg-success';
                } else {
                    $percentCompletedColor = 'bg-danger'; // fallback
                }


                // <span class="'.$percentCompletedColor.' fw-bold">' .$percentCompleted . '%' .'</span>
                $percentCompletedDisplay = '
                 
                        <div class="progress-bar fw-bold fs--1 '.$percentCompletedColor.' rounded-3" role="progressbar" style="width: '.$percentCompleted.'%" aria-valuenow="'.$totalprojtnapproved.'" aria-valuemin="0" aria-valuemax="'.$totalprojtn.'"> '.$percentCompleted.'% </div>
                  
                        ';

            $qprojectionperiod = projection_period_details($basedocnum);
            $projperiodstatus = $qprojectionperiod->STATUS ?? '-';


            $response['maindashboardcount'] = [
                "totalprojection" => $qdashboardprojectioncount->TOTALPROJTN ?? 0,
                "totalfinalprojection" => $qdashboardprojectioncount->TOTALFINALPROJTN ?? 0,
                "totalprojection_amount" =>  number_format($qdashboardprojectioncount->TOTALPROJTNAMOUNT,2),
                "totalreturned" => $qdashboardprojectioncount->TOTAL_RETURNED ?? 0,
                "totalreturned_amount" =>  number_format($qdashboardprojectioncount->TOTAL_RETURNEDAMOUNT,2),
                "totalprojectionapproved" => $qdashboardprojectioncount->TOTAL_APPROVED ?? 0,
                "totalprojectionapproved_amount" => number_format($qdashboardprojectioncount->TOTAL_APPROVEDAMOUNT,2),
                "totalprojectionpending" => $qdashboardprojectioncount->TOTAL_PENDING ?? 0,
                "totalprojectionpending_amount" =>  number_format($qdashboardprojectioncount->TOTAL_PENDINGAMOUNT,2),
                "totalallocation" => 0,
                "totalallocation_amount" => 0,
                "projperiodstatus" => $projperiodstatus,
                "notyetallocated" => $notyetallocated,
                "totalallocated" => $totalallocated,
                "unserved" => $unservedFinal,
                "unservedPercent" => $unservedPercent,
                "notYetAllocatedPercent" => $notYetAllocatedPercent,
                "allocatedPercent" => $allocatedPercent,

                "projtnpercentcomplete" => $percentCompletedDisplay,
            ];


            $response['allocatedchart'] = [

                "bsasummary_count" => $qdashboardprojectioncount->TOTAL_BSA  ?? 0,
                "nonbsasummary_count" => $qdashboardprojectioncount->TOTAL_NONBSA  ?? 0,
             
            ];

            $response['typesummarygraph'] = [

                "bsasummary_count" => $qdashboardprojectioncount->TOTAL_BSA  ?? 0,
                "nonbsasummary_count" => $qdashboardprojectioncount->TOTAL_NONBSA  ?? 0,
             
            ];

      
            if(!$qdashboardprojectiontopisbn->isEmpty()) {
                foreach($qdashboardprojectiontopisbn as $disbn){

                    $isbn = $disbn->EAN11;
                    $description = $disbn->DESCRIPTION;
                    $totalprojtnisbn = $disbn->TOTALPROJTNISBN;
    
               
                    $response['top10isbn'][] = [
    
                        $totalprojtnisbn  ?? 0,
                        truncatelimitWords($description,35)  . ' -- ' . $isbn   
                     
                    ]; 
                }

            }else {

                      
                $response['top10isbn'][] = [
    
                    0,
                    "-"  
                 
                ]; 

            }
      


        return response()->json($response);
        
    }

    public function dashboard_admin () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list_in_projection()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('dashboard_admin',compact('users','filterdashboardapprover'));
        
      
    }

    public function stock_allocation () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('stock_allocation',compact('users','filterdashboardapprover'));
        
      
    }

    public function finalize_req_adjustment () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('finalize_req_adjustment',compact('users','filterdashboardapprover'));
        
      
    }

    public function allocation_request () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('allocation_request',compact('users','filterdashboardapprover'));
        
      
    }

    public function convert_allocation () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('convert_allocation',compact('users','filterdashboardapprover'));
        
      
    }

    
    public function create_allocation_request () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('create_allocation_request',compact('users','filterdashboardapprover'));
        
      
    }


    public function approvals () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('approvals',compact('users','filterdashboardapprover'));
        
      
    }

    
    public function approvals_projection () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('approvals_projection',compact('users','filterdashboardapprover'));
        
      
    }

    public function approvals_allocationrequest_in () 
    {
        $date_now = date_now('dateonly');

        $docnum = request('docnum');
        $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                    ->first();
    
        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('approvals_allocationrequest_in',compact('users','filterdashboardapprover','qallocreqh'));
        
    }

    
    public function approvals_convertallocation () 
    {
        $date_now = date_now('dateonly');

        $docnum = request('docnum');
        $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                    ->first();
    
        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('approvals_convertallocation',compact('users','filterdashboardapprover','qallocreqh'));
        
    }

    public function approvals_allocationrequest_out () 
    {
        $date_now = date_now('dateonly');

        $docnum = request('docnum');
        $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                    ->first();
    
        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('approvals_allocationrequest_out',compact('users','filterdashboardapprover','qallocreqh'));
        
    }

    public function approvals_final_projection () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('approvals_final_projection',compact('users','filterdashboardapprover'));
        
      
    }

    public function customer_link_accounts () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('customer_link_accounts',compact('users','filterdashboardapprover'));
        
      
    }

    public function sessionList () {
        $sessionData = session()->all();
        dd($sessionData);

    }

    public function open_projection () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('open_projection',compact('users','filterdashboardapprover'));
        
      
    }

    
    public function create_projection () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('create_projection',compact('users'));
        
      
    }


    public function update_push_list () 
    {
        

        $date_now = date_now('dateonly');

        $users = filter_user_list()->get();
        $filterdashboardapprover = [
            'RSM',
            'SSM',
            'CCM'
        ];

        return view('update_push_list',compact('users'));
        
      
    }

    public function logoff_admin (Request $request) {
        
    

        Session::forget('staff');        
        Session::forget('user_staff');
        Session::forget('pernr');
        Session::forget('rsm');
        Session::forget('ssm');
        Session::forget('rank');
        Session::forget('aplevel');
        
        return  $this->login_page(); 
    
    }

    public function login_page () 
    {

       
        $userstaff = session('user_staff');

        if(isset($userstaff)) {
            return redirect()->route('dashboard_admin');
        }
        else {
            return view('login');
        } 

 
   
    }
    

    public function login_admin (Request $request) {
        
        $username = $request->input("username");    
        $pass = $request->input("password");
        
        $user = OPTv2User::where([
            "USERNAME" => $username,
            ])
            ->first();
            
    
            if($user && $pass == $user->PASSWORD) {

                $active = $user->ACTIVE;
    
                if($active == '1') {
                    $id = $user->id;
                    $pernr = $user->PERNR;
                    $rsm = $user->RSM;
                    $ssm = $user->SSM;
                    $rank = $user->RANK;
                    $aplevel = $user->APLEVEL;
                    $division = $user->DIVISION;
        
                    $request->session()->put('staff', $id);
                    $request->session()->put('user_staff', $username);
                    $request->session()->put('pernr', $pernr);
                    $request->session()->put('rsm', $rsm);
                    $request->session()->put('ssm', $ssm);
                    $request->session()->put('rank', $rank);
                    $request->session()->put('aplevel', $aplevel);
                    $request->session()->put('division', $division);
                    return redirect()->route('dashboard_admin');
        
    
                } else {
    
                    $request->session()->flash('errormessage', "Account is disabled.");
                    $request->session()->flash('classDiv', "is-invalid");
                    return back();
    
                }
            
            } else {
    
                $request->session()->flash('errormessage', "Wrong username or password.");
                $request->session()->flash('classDiv', "is-invalid");
                return back();
            }
    
    }

    
    public function get_mainprojection_list(Request $request) {

        $query = mainprojectionlist();

        foreach ($query as $r){
                $projectionid = $r->PROJECTIONID;
                $docnum = $r->DOCNUM;
                $period = $r->PERIOD;

                $projectionidDisplay =  'ID: ' . $projectionid . ' | Period: ' . $period;
                $response[] = array(
                    "projectionid" => $projectionid,
                    "docnum" => $docnum,
                    "projectioniddisplay" => $projectionidDisplay,
                );
        }
        
        return response()->json($response);

    }

    public function datatable_update_customer_temp_list(Request $request) {

        $num = 0;
        $qcustomer = OPTv2Projectionh::from('OPTV2PROJECTIONH as t1')
        ->where('t1.CUSTOMER','LIKE','%TEMP%')
        ->selectRaw('
            CUSTOMERNAME,
            MAX(TEMPCUSTOMER) as TEMPCUSTOMER,
            MAX(id) as id
        ')
        ->groupBy('CUSTOMERNAME')
        ->get();

        if($qcustomer->isEmpty()) {

            $response = [
                "num" => 0
            ];

        } else {

            foreach ($qcustomer as $r){
                $num++;
                $id = $r->id;
                $tempcode = $r->TEMPCUSTOMER;
                $customertemp = $r->CUSTOMERNAME;

                $customertempList[] = $customertemp;
                $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                                <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                    <div class="dropdown-menu dropdown-menu-end py-2">';
                                    // <div class="dropdown-divider"></div>    
                       
                $action .= '
                            <a href="#" class="dropdown-item text-primary update-customertemp-btn" data-customertemp="'.$customertemp.'" >
                                            Update</a>
                                    ';

                $action .= "
                                    </div>
                            </div>";
            
                $customertempDisplay = '<span class="line-clamp-1" title="'.$customertemp.'"> '.$customertemp.' </span>';
                $response[] = array(
                    "num" => $num,
                    "tempcode" => $tempcode,
                    "customertemp" => $customertempDisplay,
                    "customertemptext" => $customertemp,
                    "addedby" => '',
                    "action"  => $action,
                );
            }

            $qaddedby = OPTv2Projectionh::from('OPTV2PROJECTIONH as t1')
            ->whereIn('t1.CUSTOMERNAME',$customertempList)
            ->selectRaw('
                t1.PERNRNAME,
                t1.CUSTOMERNAME
            ')
            ->where('t1.CUSTOMER','LIKE','%TEMP%')
            ->groupBy('t1.PERNRNAME','t1.CUSTOMERNAME')
            ->get();

            $resultaddedby = [];
            foreach ($qaddedby as $s) {
                $addedby_customername = $s->CUSTOMERNAME;
                $addedby_pernrname = $s->PERNRNAME;

                $resultaddedby[$addedby_customername][] = $addedby_pernrname;
          
            }

            foreach($response as &$res) {

                $customernametemp = $res['customertemptext'];

                $addedby = implode(' | ', $resultaddedby[$customernametemp]);
                $addedbyDisplay = '<span class="line-clamp-1" title="'.$addedby.'"> '.$addedby.' </span>';
                $res['addedby'] = $addedbyDisplay;
            }

        }

        
        return response()->json($response);

    }
    public function datatable_generate_allocation_isbn(Request $request) {

            $projdocnum = $request->query('projdocnum');
            $isbn = $request->query('isbn');

            $expISBN = explode(",",$isbn);

            // STEP 1️⃣: Get projection data per ISBN + PERNR
            $projectionResults = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                ->leftJoin('OPTV2PROJECTIONH as t3', 't1.DOCNUM', '=', 't3.DOCNUM')
                ->where('t1.BASEDOCNUM', $projdocnum)
                ->whereIn('t1.EAN11',$expISBN)
                ->whereNull('t1.ALLOCATED')
                ->whereNotNull('t1.APPROVED')
                ->selectRaw("
                    MAX(t1.MATNR) as MATNR,
                    t1.EAN11,
                    MAX(t3.PERNRNAME) AS PERNRNAME,
                    MAX(t1.PERNR) AS PERNR,
                    MAX(t1.DESCRIPTION) AS DESCRIPTION,
                    MAX(t1.USERNAME) AS USERNAME,
                    SUM(CAST(t1.QTY AS INT)) AS TOTALPROJTN,
                    SUM(CASE WHEN t3.BSA = 1 THEN CAST(t1.QTY AS INT) ELSE 0 END) AS BSA_QTY,
                    SUM(CASE WHEN t3.BSA = 0 THEN CAST(t1.QTY AS INT) ELSE 0 END) AS NONBSA_QTY,
                     (
                        SELECT ISNULL(CAST(t2.PROPOSEREQQTY AS INT),0) + ISNULL(CAST(t2.ADJSTOCKQTY AS INT),0)
                        FROM OPTV2FINALREQ AS t2
                        WHERE t2.EAN11 = t1.EAN11
                        AND t2.BASEDOCNUM = '{$projdocnum}' AND t2.APPROVED = '1'
                    ) AS PROPOSEREQQTY
                ")
                ->orderBy('DESCRIPTION', 'ASC')
                ->groupBy('t1.EAN11', 't1.USERNAME')
                ->get();
            
            //     SELECT TOP 1  CASE 
            //     WHEN t2.ADJSTOCKQTY > SUM(CAST(t1.QTY AS INT)) 
            //         THEN t2.ADJSTOCKQTY 
            //     ELSE CAST(t2.PROPOSEREQQTY AS INT) + CAST(t2.ADJSTOCKQTY AS INT)
            // END
            // STEP 2️⃣: Collect PERNR & ISBN lists for the sales query
            $pernrList = [];
            $isbnList  = [];
            
            $currentYear = getPreviousYear(0);
            $years = [
                $currentYear - 1,
                $currentYear - 2,
                $currentYear - 3,
            ];
            
            foreach ($projectionResults as $projRow) {
                $pernrList[] = $projRow->PERNR;
                $isbnList[]  = $projRow->EAN11;
            }
            
    
            
    // Get 3-year sales history per AE_CODE + ISBN
            $salesQuery = NewSales::from('new_sales as t1')
                ->whereIn('t1.ae_code', $pernrList)
                ->whereIn('t1.isbn', $isbnList)
                ->whereIn('t1.doc_year', $years)
                ->groupBy('t1.ae_code', 't1.isbn');
            
            $salesSelects = [
                DB::raw('MAX(t1.ae_code) AS ae_code'),
                DB::raw('MAX(t1.cust_code) AS cust_code'),
                DB::raw('MAX(t1.isbn) AS isbn'),
            ];
            
            $yearCounter = 0;
            foreach ($years as $year) {
                $yearCounter++;
                $salesSelects[] = DB::raw("
                    SUM(CASE WHEN t1.doc_year = {$year} THEN t1.qty ELSE 0 END) AS total_{$yearCounter}
                ");
            }
            
            $salesResults = $salesQuery->select($salesSelects)->get();
            
            
            // STEP 4️⃣: Build associative map for faster sales lookup
            $salesMap = [];
            foreach ($salesResults as $salesRow) {
                $mapKeySales = $salesRow->isbn . '_' . $salesRow->ae_code;
                $salesMap[$mapKeySales] = [
                    'sales_year_1' => $salesRow->total_1 ?? 0,
                    'sales_year_2' => $salesRow->total_2 ?? 0,
                    'sales_year_3' => $salesRow->total_3 ?? 0,
                ];
            }
    //-----------------------       
            
            // STEP 5️⃣: Merge projection + sales data
            $response = [];
            
            foreach ($projectionResults as $projRow) {
                $matnr  = $projRow->MATNR;
                $isbnCode  = $projRow->EAN11;
                $pernrCode = $projRow->PERNR;
                $description = $projRow->DESCRIPTION;
                $pernrName = $projRow->PERNRNAME;
                $proposedreqqty = $projRow->PROPOSEREQQTY;
                $mapKeyProj    = $isbnCode . '_' . $pernrCode;
            
                $salesYear1 = $salesMap[$mapKeyProj]['sales_year_1'] ?? 0;
                $salesYear2 = $salesMap[$mapKeyProj]['sales_year_2'] ?? 0;
                $salesYear3 = $salesMap[$mapKeyProj]['sales_year_3'] ?? 0;
            
                $bsaQty     = (int) $projRow->BSA_QTY;
                $nonBsaQty  = (int) $projRow->NONBSA_QTY;

                $bsaallocateqty = '
                        <input class="form-control mx-0 p-1 border text-center border-primary-200 allocate_qty_bsa allocate_qty_input" data-isbn="'.$isbnCode.'" data-matnr="'.$matnr.'" data-alloctype="bsa" data-basedocnum="'.$projdocnum.'" data-pernr="'.$pernrCode.'" data-projqty="'.$bsaQty.'" max="'.$bsaQty.'" type="number" value="0" min="0">
                               
                ';
                $nonbsaallocateqty = '
                        <input class="form-control mx-1 p-1 border text-center border-primary-200 allocate_qty_nonbsa allocate_qty_input" data-isbn="'.$isbnCode.'" data-matnr="'.$matnr.'" data-alloctype="nonbsa" data-basedocnum="'.$projdocnum.'" data-pernr="'.$pernrCode.'" data-projqty="'.$nonBsaQty.'" max="'.$nonBsaQty.'" type="number" value="0" min="0">
                ';
            
                
                // if($proposedreqqty > 0 ) {
             
                    
                // }
             
                $response[] = array (
                    "isbn" => $isbnCode,
                    "description" => $description,
                    "pernrname" => $pernrName,
                    "pernr" => $pernrCode,
                    "bsaqty" => $projRow->BSA_QTY,
                    "nonbsaqty" => $projRow->NONBSA_QTY,
                    "bsaallocateqty" => $bsaallocateqty,
                    "nonbsaallocateqty" => $nonbsaallocateqty,
                    "proposereq" => $proposedreqqty ?? 0,
                    "proposereq" => $proposedreqqty ?? 0,
                    "total_1" => $salesYear1,
                    "total_2" => $salesYear2,
                    "total_3" => $salesYear3,
                );
           
            }
            
            
            // STEP 6️⃣: Optional — clean up unmatched sales entries
            foreach ($projectionResults as $projRow) {
                $removeKey = $projRow->EAN11 . '_' . $projRow->PERNR;
                unset($salesMap[$removeKey]);
            }
            
//group muna per isbn bago final json
            $grouped = collect($response)->groupBy('isbn')->map(function ($rows) {
                return [
                    'isbn' => $rows->first()['isbn'],
                    'description' => $rows->first()['description'],
                    'proposereq' => $rows->first()['proposereq'],
                    'userlist' => $rows->values(),
                ];
            });
//-------------------       
      
            return response()->json($grouped->values());

    }
    public function datatable_for_approval_convertalloc_list(Request $request) {

        $basedocnum = $request->query('basedocnum');
        $username = $request->query('username');
        $rank = trim(session('rank'));

        $arrayFilterPernr = arrayFilterPernr();
        
        $_qconvertallocd = OPTv2ConvertAllocd::from('OPTV2CONVERTALLOCD as t1')
                                ->where('BASEDOCNUM',$basedocnum)
                                ->where('USERNAME',$username)
                                ->whereNull('t1.CANCEL')
                                ->whereNull('t1.APPROVED')
                                ->orderBy('id','DESC')
                               ;

        $this->applyRankApprovalFilter($_qconvertallocd,$rank,'convertalloc');
        
        $num = 0;

        $qconvertallocd = $_qconvertallocd->get();
        
        if($qconvertallocd->isEmpty()) {

            $response = [
                "num" => 0
            ];

        } else {

            foreach ($qconvertallocd as $r){
                $num++;
                $id = $r->id;
                $docnum = $r->docnum;
                $isbn = $r->EAN11;
                $tempisbn = $r->TEMPEAN11;
                $description = $r->DESCRIPTION;
                $status = $r->STATUS;
                $qty = $r->QTY;
                $approved = $r->APPROVED;
                $converttype = $r->FROMCONVERTTYPE;
                $toconverttype = $r->TOCONVERTTYPE;
                $created_at = $r->created_at;
                $updated_at = $r->updated_at;
                $checkbox = '
                        <div class="d-flex justify-content-center">
                                <input class="form-check-input for_approval_convertalloc_checkisbn" data-basedocnum="'.$basedocnum.'" data-id="'.$id.'"  type="checkbox" value="">
                        </div>
                    ';
                            
    
                $descriptionDisplay = '<span class="line-clamp-1" data-status="'.$status.'" title="'.$description.'"> '.$description.' </span>';
                $converttypeDisplay = acronymFullWord($converttype) . ' to ' . acronymFullWord($toconverttype); 
                $datecreateDisplay =  formatDate($created_at,'mdy');
                $statusDisplay = status_display($status);
                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "description"  =>  $descriptionDisplay, 
                    "qty"  =>  $qty, 
                    "datecreate"  =>  $datecreateDisplay, 
                    "converttypedisplay"  =>  $converttypeDisplay, 
                    "status"  => $statusDisplay,
                    "checkbox"  => $checkbox,
                );
            }

        }

        
        return response()->json($response);


    }
    
    public function datatable_for_approval_allocreq_out_details(Request $request) {

        $pernr = trim(session('pernr'));
        $docnum = $request->query('docnum');
        $rank = trim(session('rank'));
        
        $arrayFilterPernr = arrayFilterPernr();

        $qallochdetails = allocReqHDetails($docnum);
        $reqtopernr = $qallochdetails->REQTO;
        $basedocnum = $qallochdetails->BASEDOCNUM;

        $qallocreqd = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
                            ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
                            ->select('t1.*','t2.REQTO')
                            ->where('t1.DOCNUM',$docnum)
                            ->whereNull('t1.CANCEL')
                            ->whereNull('t1.APPROVED')
                            ;

            $this->applyRankApprovalFilter($qallocreqd,$rank,'allocreqout');
       

        $num = 0;
        $tl = 0;

        $finalquery = $qallocreqd->get();
        
        if($finalquery->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($finalquery as $c) {
                    $num++;
                
                   
                    $id = $c->id;
                    $isbn = $c->EAN11;
                    $matnr = $c->MATNR;
                    $linenum = $c->LINENUM;
                    $description = $c->DESCRIPTION;
                  
                    $docnum = $c->DOCNUM;
                    $qty = $c->QTY;
                    $alloctype = $c->ALLOCTYPE;
                    $reqfrom = $c->PERNR;
                    $reqto = $c->REQTO;
                    $approved1 = $c->APPROVED1;
                    $approved = $c->APPROVED;
                    $status = $c->STATUS;
                    $submit = $c->SUBMIT;

                    $balance = getAllocatedQty ($isbn,$reqtopernr,$alloctype,$basedocnum);
                    
                    $classDisplay = 'un-cl-nbg border-0';

                    if( $pernr == $reqto ) {
                        $classDisplay = '';
                    }

                    if($qty > $balance ) {
                        $qty = $balance;

                    }
        
                    $qtyDisplay = ' <input type="number" class="form-control p-0 '.$classDisplay.' text-center apprvallocreqqty" data-requested="'.$qty.'" data-id="'.$id.'" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" data-reqfrom="'.$reqfrom.'" data-reqto="'.$reqto.'" data-alloctype="'.$alloctype.'" value="'.$qty.'" data-balance="'.$balance.'">';
                  

                    $tl += $qty;

                   
                    $statusDisplay = status_display($status);

                    $checkbox = '
                        <div class="d-flex justify-content-center">
                                <input class="form-check-input for_approval_allocreqout_checkisbn" data-docnum="'.$docnum.'" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" data-id="'.$id.'" type="checkbox" value="">
                        </div>
                    ';
    
                    $response[] = array(
                        "num" => $num,
                        "id" => $id,
                        "isbn" => $isbn,
                        "linenum" => $linenum,
                        "matnr" => $matnr,
                        "description" => $description,
                        "requested" => $qty,
                        "approve" => $qtyDisplay,
                        "balance" => $balance,
                        "alloctype" => $alloctype,
                        "checkbox" => $checkbox,
        
                    );

            }
        }

    
        return response()->json($response);

    }
    public function datatable_for_approval_allocreq_details(Request $request) {

        $pernr = trim(session('pernr'));
        $docnum = $request->query('docnum');
        $rank = trim(session('rank'));
        
        $arrayFilterPernr = arrayFilterPernr();

        $qallochdetails = allocReqHDetails($docnum);
        $reqtopernr = $qallochdetails->REQTO;
        $basedocnum = $qallochdetails->BASEDOCNUM;
        $allocInQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
                                ->where('DOCNUM',$docnum)
                                ->whereNull('t1.CANCEL')
                                ->whereIn('PERNR', $arrayFilterPernr)
                                    ;


        $this->applyRankApprovalFilter($allocInQ,$rank);
       

        $num = 0;
        $tl = 0;

        $finalquery = $allocInQ->get();
        
        if($finalquery->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($finalquery as $c) {
                    $num++;
                
                   
                    $id = $c->id;
                    $isbn = $c->EAN11;
                    $matnr = $c->MATNR;
                    $linenum = $c->LINENUM;
                    $description = $c->DESCRIPTION;
                  
                    $qty = $c->QTY;
                    $alloctype = $c->ALLOCTYPE;
                    $approved1 = $c->APPROVED1;
                    $approved = $c->APPROVED;
                    $status = $c->STATUS;
                    $submit = $c->SUBMIT;

                    $balance = getAllocatedQty ($isbn,$reqtopernr,$alloctype,$basedocnum);
                    
                    $qtyDisplay = number_format($qty);

                    $tl += $qty;

                        
                    $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                                <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                    <div class="dropdown-menu dropdown-menu-end py-2">';
                                    // <div class="dropdown-divider"></div>    
                            
                    if(is_null($submit)) {
                            $action .= '
                                            <a href="#" class="dropdown-item text-danger remove-btn-allocreqd-isbn" data-docnum="'.$docnum.'" data-id="'.$id.'">
                                                            Remove</a>
                                                    ';
                    }
                    else if(is_null($approved)) {
                          $action .= '
                                <a href="#" class="dropdown-item text-danger cancel-btn-allocreqd-isbn" data-docnum="'.$docnum.'" data-id="'.$id.'">
                                                Cancel</a>
                                        ';

                    } 
                    $action .= "
                                        </div>
                                </div>";
                    $statusDisplay = status_display($status);

                    $checkbox = '
                        <div class="d-flex justify-content-center">
                                <input class="form-check-input for_approval_allocreqin_checkisbn" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" data-id="'.$id.'" type="checkbox" value="">
                        </div>
                    ';
    
                    $response[] = array(
                        "num" => $num,
                        "id" => $id,
                        "isbn" => $isbn,
                        "linenum" => $linenum,
                        "matnr" => $matnr,
                        "description" => $description,
                        "qty" => $qtyDisplay,
                        "balance" => $balance,
                        "alloctype" => $alloctype,
                        "status" => $statusDisplay,
                        "checkbox" => $checkbox,
                        "action" => $action,
        
                    );

            }
        }

    
        return response()->json($response);

    }

    public function datatable_allocreq_details(Request $request) {

        $pernr = session('pernr');
        $docnum = $request->query('docnum');
      
        $qallochdetails = allocReqHDetails($docnum);
        $reqtopernr = $qallochdetails->REQTO;
        $basedocnum = $qallochdetails->BASEDOCNUM;
        $finalquery = OPTv2AllocReqd::where('DOCNUM',$docnum)
                                    ->get();
    
        $num = 0;
        $tl = 0;

        
        if($finalquery->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($finalquery as $c) {
                    $num++;
                
                   
                    $id = $c->id;
                    $isbn = $c->EAN11;
                    $matnr = $c->MATNR;
                    $linenum = $c->LINENUM;
                    $description = $c->DESCRIPTION;
                  
                    $reqqty = $c->REQQTY;
                    $qty = $c->QTY;
                    $alloctype = $c->ALLOCTYPE;
                    $approved1 = $c->APPROVED1;
                    $approved = $c->APPROVED;
                    $cancel = $c->CANCEL;
                    $datedisapproved = $c->DATEDISAPPROVED;
                    $disapporvedby = $c->DISAPPROVEDBY;
                    $remarksdisapproved = $c->REMARKSDISAPPROVED;
                    $tobranchwhouse = $c->BRANCHWHOUSE;
                    $status = $c->STATUS;
                    $submit = $c->SUBMIT;

                    $balance = getAllocatedQty ($isbn,$reqtopernr,$alloctype,$basedocnum);
                    
                    $qtyDisplay = number_format($reqqty);

                    if( $submit !== '1') {
                        $qtyDisplay = ' <input type="number" class="form-control p-1 text-center update_allocreq_qty" data-reqqty="'.$qty.'" data-id="'.$id.'" value="'.$qty.'" data-balance="'.$balance.'">';
                    }
        
                    $tl += $qty;

                        
                    $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                                <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                    <div class="dropdown-menu dropdown-menu-end py-2">';
                                    // <div class="dropdown-divider"></div>    
                            
                    if(is_null($submit)) {
                            $action .= '
                                            <a href="#" class="dropdown-item text-danger remove-btn-allocreqd-isbn" data-docnum="'.$docnum.'" data-id="'.$id.'">
                                                            Remove</a>
                                                    ';
                    }
                    else if(is_null($approved) && is_null($cancel)) {
                          $action .= '
                                <a href="#" class="dropdown-item text-danger cancel-btn-allocreqd-isbn" data-docnum="'.$docnum.'" data-id="'.$id.'">
                                                Cancel</a>
                                        ';

                    } 
                    $action .= "
                                        </div>
                                </div>";

                    $addtltext = '';
                    $addtitle = '';
                    if($approved == '1') {
                        $addtltext =  ': ' . $qty;
                    }

        
                    if(!is_null($remarksdisapproved)) {

                      $addtitle = formatDate($datedisapproved,'mdy') . ' &nbsp ' . $disapporvedby . ': ' . $remarksdisapproved   ;

                    }
                 
                    $statusDisplay = status_display($status,'badge','0.7', $addtltext,$addtitle);
    
    
                        //select type only
                    $activeWarehouses = activeWarehouses();
                    $activeBranches = activeBranches();
                    
                    $ab = '';
                    $aw = '';
                
                    // dd($branchwhouseFinal);

                    
                    foreach($activeBranches as $a => $b){

                        $selected1 = $a === $tobranchwhouse ? 'selected' : '';

                        $ab.= '
                                <option value="'.$a.'" '.$selected1.'>'.$b.'</option>
                        ';
                    }
                
                                        
                    foreach($activeWarehouses as $c => $d){
                        
                        $selected2 = $b === $tobranchwhouse ? 'selected' : '';
                        $aw.= '
                                <option value="'.$c.'" '.$selected1.'>'.$d.'</option>
                        ';
                    }

                    $selectbranchwhouse = '
                            <select data-id="'.$id.'" class="form-control p-1 update_allocreq_branchwhouse  form-control-sm">
                                <option value=" " selected>Choose in the list </option>

                                <optgroup label="Branches" class="branchesopt">
                                    '.$ab.'
                                </optgroup>


                                <optgroup label="Warehouses" class="whouseopt">
                                    '.$aw.'
                                </optgroup>
                            </select>
                        ';
                        
            //------
            
            $tobranchwhouseDisplay = $selectbranchwhouse;   
            $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
            
                    $response[] = array(
                        "num" => $num,
                        "id" => $id,
                        "isbn" => $isbn,
                        "linenum" => $linenum,
                        "matnr" => $matnr,
                        "description" => $descriptionDisplay,
                        "qty" => $qtyDisplay,
                        "balance" => $balance,
                        "alloctype" => $alloctype,
                        "status" => $statusDisplay,
                        "action" => $action,
                        "tobranchwhouse" => $tobranchwhouseDisplay,
        
                    );

            }
        }

    
        return response()->json($response);

    }

    public function submit_allocreq_isbn_remove(Request $request) {

        $id = $request->input('id');
        $date_now = date_now('dateonly');
        
        $html = "";
        $qallocreqd = OPTv2AllocReqd::where('id',$id);
                                        ;
        $status = 404;                  

        if(!$qallocreqd->exists()) {
            $status = 403;
        }
        else {
            
            $qallocreqd->delete();

            $status = 2;

        }

    


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);

    }

   public function submit_remove_zero_projtn(Request $request)
    {
        $pernr = session('pernr');
        $customercode = $request->input('customercode');
        $isbn = $request->input('isbn');
        $projdocnum = $request->input('projdocnum');
        $date_now = date_now('dateonly');

        $html = "";
        $status = 404;

        $baseQuery = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftJoin('OPTV2PROJECTIONH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
            ->where('t2.CUSTOMER', $customercode)
            ->where('t1.EAN11', $isbn)
            ->where('t1.BASEDOCNUM', $projdocnum)
            ->where('t1.PERNR', $pernr);

        $qcheck = (clone $baseQuery)
            ->select('t1.*','t1.SUBMIT')
            ->first();

        if (!$qcheck) {

            $status = 403;

        } else {

            // if ($qcheck->SUBMIT !== '1') {

                $rows = (clone $baseQuery)
                    ->select('t1.id','t1.PERNR','t1.EAN11','t1.QTY')
                    ->get();

                foreach ($rows as $row) {

                    OPTv2Logs::create([
                        "REFERENCE" => $customercode,
                        "REMARKS" => "id:".$row->id.", ". $row->EAN11 .", ". $row->QTY ,
                        "USERID" => $row->PERNR,
                        "DOCDATE" => $date_now,
                        "LOGTYPE" => 'deleteprojection',
                    ]);

                }

                     // (clone $baseQuery)->update([
                //          "QTY" => '0',
                //         "PROJECTION" => '0',
                //         "LINETOTAL" => '0', 
                // ]);
                (clone $baseQuery)->delete();

                $status = 2;

            // } else {

            //     $status = 403;

            // }

        }

        return response()->json([
            'status' => $status,
            'html' => $html,
        ]);
    }


    public function datatable_soh_isbn_per_location_list(Request $request) {

        $pernr = session('pernr');
        $isbn = $request->query('isbn');
      
        $finalquery =  get_soh_isbn_per_location($isbn);
    
        $num = 0;
        $tl = 0;

        
        if($finalquery->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($finalquery as $c) {
                    $num++;
                
                   
                    $isbn = $c->EAN11;
                    $location = $c->LGOBE;
                    $qty = $c->LABST;
                    $storage = $c->LGORT;
                    $plant = $c->WERKS;
                    
                    $qtyDisplay = number_format($qty);
        
                    $tl += $qty;

                    $response[] = array(
                        "num" => $num,
                        "isbn" => $isbn,
                        "location" => $location,
                        "qty" => $qtyDisplay,
                        "storage" => $storage,
                        "storage" => $plant,
                        "total" => $tl,
        
                    );

            }
        }

    
        return response()->json($response);

    }

    public function datatable_approved_finalreq_list(Request $request) {

        $pernr = session('pernr');
        $basedocnum = $request->query('basedocnum');
      
        $approvedfinalreqisbn =  OPTv2FinalReq::where('BASEDOCNUM',$basedocnum)
                                            ->where('APPROVED','=','1')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ->get();
    
        $num = 0;
        

        
        if($approvedfinalreqisbn->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($approvedfinalreqisbn as $c) {
                $num++;
            
                $isbn = $c->EAN11;
                $description = $c->DESCRIPTION;
        
                $descriptiontruncate = truncatelimitWords($description,27) ;

                
                $totalproj = $c->TOTALPROJQTY;
                $soh = $c->SOHQTY ?? 0;
                $pullouttransit = $c->PULLOUTQTY ?? 0;
                $onorderoms =  $c->OMSQTY ?? 0;
                $onpo = $c->ONPOQTY ?? 0;
                $bufferstock = round(($totalproj * 0.05));
                $adjstock = round($soh + $pullouttransit - $onorderoms + $onpo - $bufferstock);
                $requireqty = $adjstock - $totalproj;
                $roundreqqty =  $requireqty < 0 ? 0 :round($requireqty, -1) ?? 0; // -1 is nearest 20 
                $propreqval = $c->PROPOSEREQQTY ?? $roundreqqty;
                $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
                $proprequireqty = $propreqval; 

                $checkisbn = '
                    <div class="d-flex justify-content-center">
                            <input class="form-check-input for_approval_finalreq_isbn_approve_check" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" type="checkbox" value="">
                    </div>';
                $totalprojDisplay = '<a href="javascript:void(0)" class="titleprojectionaelist" data-totalprojtn="'.$totalproj.'" data-title="'.$description.'" data-basedocnum="'.$basedocnum.'" data-isbn="'.$isbn.'">'.$totalproj.'</a>';

      

                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "description" => $descriptionDisplay,
                    "totalproj" => $totalprojDisplay,
                    "soh" => $soh,
                    "pullouttransit" => $pullouttransit,
                    "onorderoms" => $onorderoms,
                    "onpo" => $onpo,
                    "bufferstock" => $bufferstock,
                    "adjstock" => $adjstock,
                    "requireqty" => $requireqty,
                    "proprequireqty" => $proprequireqty,
                    "propreqval" => $propreqval,
                    "checkisbn" => $checkisbn,
                    "allocated" => 0,
                    "action" => '',
                );

        }
        $qallocated =  OPTv2Allocated::selectRaw("
                MAX(EAN11) as EAN11,
                SUM(CAST(ALLOCATED AS INT)) as TOTALALLOCATED
            ")
            ->where('BASEDOCNUM',$basedocnum)
            ->groupBy('EAN11')
            ->get();

        if(!$qallocated->isEmpty()) {

            $allocatedISBNLogs = [];

            foreach($qallocated as $al) {

            $allocatedISBNLogs[$al->EAN11]['TOTALALLOCATED'] = $al->TOTALALLOCATED ?? 0; 

            }

        }

        
        foreach($response as &$res) {

            $isbn = $res['isbn'];
            $allocated = isset($allocatedISBNLogs[$isbn]['TOTALALLOCATED']) ? $allocatedISBNLogs[$isbn]['TOTALALLOCATED'] : 0; 
        

                    
            $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
            <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                <div class="dropdown-menu dropdown-menu-end py-2">';

                    $action .= '
                    <a href="javascript:void(0)" class="dropdown-item text-primary refloat_approved_isbn" data-basedocnum="'.$basedocnum.'" data-allocated="'.$allocated.'" data-isbn="'.$isbn.'" >
                                    Return to Projection Summary</a>
                            ';
            $action .= "
                                </div>
                        </div>";

            $res['action'] = $action;
            $res['allocated'] = $allocated;

        }

        unset($res);

    }

    
        return response()->json($response);

    }

    public function datatable_changeisbn_projection_list(Request $request) {

        $pernr = session('pernr');
        $basedocnum = $request->query('basedocnum');
        $date_now_full = date_now();
        $num = 0;
        $qchangeisbnprojectionlist = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->select(
                                                "TEMPEAN11",
                                                DB::raw("MAX(DESCRIPTION) as DESCRIPTION"),
                                                DB::raw("MAX(EAN11) as EAN11"),
                                                DB::raw("SUM(CAST(QTY AS INT)) as TOTALPROJTN")
                                                 )
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->whereNotNull('t1.APPROVED')
                                            ->groupBy('TEMPEAN11')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ->get();

            if($qchangeisbnprojectionlist->isEmpty()){
                $response = [
                    "num" => "0",
                ];
            }
            else {
    
                foreach($qchangeisbnprojectionlist as $c) {
                    
                    $num++;
                
                    $isbn = $c->EAN11;
                    $tempisbn = $c->TEMPEAN11;
    
                    $isbnlist[] = $isbn;
                    
                    $description = $c->DESCRIPTION;
            
                    $totalproj = $c->TOTALPROJTN;
                 
                    $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
                
                    $totalprojDisplay = '<a href="javascript:void(0)" class="titleprojectionaelist" data-totalprojtn="'.$totalproj.'" data-title="'.$description.'" data-basedocnum="'.$basedocnum.'" data-isbn="'.$tempisbn.'">'.$totalproj.'</a>';

                    $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                    <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                        <div class="dropdown-menu dropdown-menu-end py-2">';

                            $action .= '
                            <a href="javascript:void(0)" class="dropdown-item text-primary changeisbn_btn" data-existing="'.$tempisbn.'" >
                                            Change</a>
                                    ';
                    $action .= "
                                        </div>
                                </div>";
                    $response[] = array(
                        "num" => $num,
                        "isbn" => '',
                        "tempisbn" => $tempisbn,
                        "totalprojtn" => $totalprojDisplay,
                        "description" => $descriptionDisplay,
                        "descriptionval" => $description,
                        "totalproj" => $totalprojDisplay,
                        "action" => $action,
                        "changeto" => '',
                        "allocated" => 0,
                        "dateupdate" => '',
                    );
    
                }
                
                $qupdatelogs = OPTv2Logs::from('OPTV2LOGS as l')
                ->join(
                    DB::raw('(
                        SELECT REFERENCE, MAX(id) as max_id
                        FROM OPTV2LOGS
                        WHERE BASEDOCNUM = '.$basedocnum.'
                        GROUP BY REFERENCE
                    ) as x'),
                    'l.id',
                    '=',
                    'x.max_id'
                )
                ->where('LOGTYPE','changeisbn')
                ->select([
                    'l.REFERENCE',
                    'l.REMARKS as NEWISBN',
                    'l.updated_at'
                ])
                ->get();

                if(!$qupdatelogs->isEmpty()) {

                    //get updated-----
                    
                    $changeISBNLogs = [];
                    foreach($qupdatelogs as $u) {

                        $changeISBNLogs[$u->REFERENCE]['newisbn'] = $u->NEWISBN; //remarks is new isbn
                        $changeISBNLogs[$u->REFERENCE]['updated_at'] = $u->updated_at; //remarks is new isbn


                    }
                    foreach($response as &$res) {
                            $changeto = $changeISBNLogs[$res['tempisbn']]['newisbn'] ?? ''; 
                            $updated_at= $changeISBNLogs[$res['tempisbn']]['updated_at'] ?? '';
            
                            $dateupdate = !empty($updated_at) ? formatDate($updated_at,'mdyts') : '';
            
                            $dateupdateDisplay ="<span class='fs--2'>".$dateupdate."</span>";

                            $res['changeto'] = $changeto;
                            $res['dateupdate'] = $dateupdateDisplay;
            
                    }
                    
                    unset($res);

                }

                $qallocated =  OPTv2Allocated::selectRaw("
                                    MAX(EAN11) as EAN11,
                                    SUM(CAST(ALLOCATED AS INT)) as TOTALALLOCATED
                                ")
                                ->where('BASEDOCNUM',$basedocnum)
                                ->groupBy('EAN11')
                                ->get();

                if(!$qallocated->isEmpty()) {

                    $allocatedISBNLogs = [];

                    foreach($qallocated as $al) {

                        $allocatedISBNLogs[$al->EAN11]['TOTALALLOCATED'] = $al->TOTALALLOCATED ?? 0; 

                    }

                    foreach($response as &$res) {

                        $isbn = $res['tempisbn'];
                            $allocated = isset($allocatedISBNLogs[$isbn]['TOTALALLOCATED']) ? $allocatedISBNLogs[$isbn]['TOTALALLOCATED'] : 0; 
                            $totalallocatedDisplay = '<a ref="" title="Show Summary" class="showbsoallocationbtn" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'">' . ($allocated)  . '</a> ';
           
                            if($allocated == '0') {
                                $totalallocatedDisplay = 0;
                            }

                            $res['allocated'] = $totalallocatedDisplay;
            
                    }
                    
                    unset($res);

                }
            

            }

        
          

            
        return response()->json($response);

    }

    public function datatable_projsummary_finalreq_list(Request $request) {

        $pernr = session('pernr');
        $staff = session('user_staff');
        $basedocnum = $request->query('basedocnum');
        $date_now_full = date_now();
        $qprojectionPeriodDetails = projection_period_details($basedocnum);
        $projectionid = $qprojectionPeriodDetails->PROJECTIONID;
        $insertedisbnfinalreq =  OPTv2FinalReq::where('BASEDOCNUM',$basedocnum)
                                            ->groupBy('EAN11')
                                            ->pluck('EAN11')
                                            ->toArray();
    
        $num = 0;
        $isbnlist = [];
        $insertApproveFinalReqISBNList = [];

        $qisbnprojsummaryfinalreq = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->select(
                                                "EAN11",
                                                DB::raw("MAX(DESCRIPTION) as DESCRIPTION"),
                                                DB::raw("SUM(CAST(QTY AS INT)) as TOTALPROJTN")
                                                 )
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->whereNotIn('EAN11',$insertedisbnfinalreq)
                                            ->whereNotNull('t1.APPROVED')
                                            ->groupBy('EAN11')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ->get();
    
        if($qisbnprojsummaryfinalreq->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($qisbnprojsummaryfinalreq as $c) {
                
                $num++;
            
                $isbn = $c->EAN11;

                $isbnlist[] = $isbn;
                
                $description = $c->DESCRIPTION;
        
                $descriptiontruncate = truncatelimitWords($description,22) ;

                $totalproj = $c->TOTALPROJTN;
                $soh =  0;
                $pullouttransit =  0;
                $onorderoms =   0;
                $onpo = 0;
                $bufferstock = round(($totalproj * 0.05));
                $adjstock = round($soh + $pullouttransit - $onorderoms + $onpo - $bufferstock);
                $requireqty = $adjstock - $totalproj;
                $roundreqqty =  $requireqty < 0 ? 0 :round($requireqty, -1) ?? 0; // -1 is nearest 20 
                $propreqval = $c->PROPOSEREQQTY ?? 0;
                $descriptionDisplay = '<span class="" title="'.$description.'"> '.$descriptiontruncate.' </span>';
            
                $checkisbn = '
                    <div class="d-flex justify-content-center">
                            <input class="form-check-input projsummary_finalreq_isbn_check" checked data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" type="checkbox" value="">
                    </div>';
                $totalprojDisplay = '<a href="#" data-isbn="'.$isbn.'">'.$totalproj.'</a>';

                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "description" => $descriptionDisplay,
                    "descriptionval" => $description,
                    "totalproj" => $totalprojDisplay,
                    "soh" => '-',
                    "pullouttransit" => '-',
                    "onorderoms" => '-',
                    "onpo" => '-',
                    "bufferstock" => '-',
                    "adjstock" => '-',
                    "requireqty" => '-',
                    "proprequireqty" => '-',
                    "total_1" => '-',
                    "total_2" => '-',
                    "total_3" => '-',
                    "propreqval" => $propreqval,
                    "checkisbn" => $checkisbn,
                );

            }

//better performance to get data per isbn??
            $omsData = get_omstransact_isbn($isbnlist,$basedocnum);
            $pulloutData = get_pullout_isbn($isbnlist);
            $sohData = get_soh_isbn($isbnlist);
            $onpoData = get_onpo_isbn($isbnlist);
            $allocatedData = getAllocatedMainProjectionDeductSOH ($basedocnum,$isbnlist);

            $allocatedMap = [];
            foreach ($allocatedData as $d5) {
                $allocatedMap[$d5->EAN11] = round($d5->TOTALALLOCATED) ?? 0;
            }

            $omsMap = [];
            foreach ($omsData as $d1) {
                $omsMap[$d1->EAN11] = round($d1->OMSQTY) ?? 0;
            }

            $pulloutMap = [];
            foreach ($pulloutData as $d2) {
                $pulloutMap[$d2->EAN11] = round($d2->PULLOUTQTY) ?? 0;
            }

            $sohMap = [];
            foreach ($sohData as $d3) {
                $sohMap[$d3->EAN11] = round($d3->SOHQTY) ?? 0;
            }
            $onpoMap = [];
            foreach ($onpoData as $d4) {
                $onpoMap[$d4->EAN11] = round($d4->ONPOQTY) ?? 0;
            }
//------------------------

//ISBN sales per 3 years 
$isbnPrev3YearSalesHistory = isbnPrev3YearSalesHistory ($isbnlist);
$salesMap = [];
foreach ($isbnPrev3YearSalesHistory as $salesData) {
    $salesMap[$salesData->isbn] = [
        "total_1" => $salesData->total_1,
        "total_2" => $salesData->total_2,
        "total_3" => $salesData->total_3
    ];

}
//------------------------

//final response
            foreach ($response as &$res) {
                $isbn = $res['isbn'];
          
                $description = $res['descriptionval'];

                // set values from maps (0 default kung wala)
                $allocated = $allocatedMap[$isbn] ?? 0;
                $soh = $sohMap[$isbn] ?? 0;
                $pullouttransit = $pulloutMap[$isbn] ?? 0;
                $onorderoms = $omsMap[$isbn] ?? 0;
                $onpo = $onpoMap[$isbn] ?? 0; 
                $saleshistorytotal_1 = $salesMap[$isbn]['total_1'] ?? 0;
                $saleshistorytotal_2 = $salesMap[$isbn]['total_2'] ?? 0;
                $saleshistorytotal_3 = $salesMap[$isbn]['total_3'] ?? 0;

                $sohFinal = $soh - $allocated;
                // recompute formula
                $totalproj = strip_tags($res['totalproj']); 
                $bufferstock = round(($totalproj * 0.05));
                $adjstock = round($sohFinal + $pullouttransit - $onorderoms + $onpo - $bufferstock);
                $requireqty = $adjstock - $totalproj;
                // $roundreqqty = $requireqty < 0 ? 0 :round($requireqty, -1) ?? 0;
                $roundreqqty = round($requireqty, -1) ;

                $propreqval =    round(ceil(abs($requireqty) / 100) * 100) ;

                if($sohFinal >= $totalproj) {
                    $propreqval =  0;

                }

                $proprequireqty = '<input class="form-control text-center p-1 proposedreq_qty " 
                type="number" data-customercode="" data-isbn="'.$isbn.'" 
                data-description="'.$description.'" data-basedocnum="'.$basedocnum.'" 
                data-totalproj="'.$totalproj.'" data-soh="'.$sohFinal.'" 
                data-pullouttransit="'.$pullouttransit.'" data-onorderoms="'.$onorderoms.'" 
                data-onpo="'.$onpo.'" data-bufferstock="'.$bufferstock.'" 
                data-adjstock="'.$adjstock.'" data-requireqty="'.$requireqty.'" 
                value="'.$propreqval.'" min="1">'; 

                $adjstockDisplay  = '<span class="adstockinsertedisbntext">' . $adjstock . '</span>';
                $requireqtyDisplay = '<span class="requireqtyinsertedisbntext">' . $requireqty . '</span>';

                $isbnFinal = $isbn;
                $proposedreqqty = $propreqval;

                OPTV2FinalReq::firstOrCreate(
                    [
                        'EAN11' => $isbnFinal,
                        'BASEDOCNUM' => $basedocnum,
                    ],
                    [
                        "TEMPEAN11"              => $isbnFinal,
                        "DESCRIPTION"            => $description,
                        "PROPOSEREQQTY"          => $proposedreqqty,
                        "REQUIREQTY"             => $requireqty,
                        "TOTALPROJQTY"           => $totalproj,
                        "SOHQTY"                 => $soh,
                        "APPROVED"               => '0',
                        "DATEAPPROVED"           => null,
                        "APPROVEDBYUSERNAME"     => null,
                        "USERCREATE"             => $staff,
                        "STATUS"                 => 'for_approval',
                        "created_at"             => $date_now_full,
                        "updated_at"             => $date_now_full,
                        "SAVED"                  => '1',
                        "PROJECTIONID"           => $projectionid,
                        "PULLOUTQTY"             => $pullouttransit,
                        "OMSQTY"                 => $onorderoms,
                        "ONPOQTY"                => $onpo,
                        "BUFFSTOCKQTY"           => $bufferstock,
                        "ADJSTOCKQTY"            => $adjstock,
                    ]
                );


                // update response
                
                // $res['soh'] = $sohFinal;
                // $res['pullouttransit'] = $pullouttransit;
                // $res['onpo'] = $onpo;
                // $res['onorderoms'] = $onorderoms;
                // $res['bufferstock'] = $bufferstock;
                // $res['adjstock'] = $adjstockDisplay;
                // $res['requireqty'] = $requireqtyDisplay;
                // $res['propreqval'] = $propreqval;
                // $res['proprequireqty'] = $proprequireqty;
                // $res['total_1'] = $saleshistorytotal_1;
                // $res['total_2'] = $saleshistorytotal_2;
                // $res['total_3'] = $saleshistorytotal_3;


            }
            unset($res);
//-------------------------------

            // $responseZero = [];
            // $responseNonZero = [];

            // foreach ($response as $res) {
            //     if (($res['propreqval'] ?? 0) == 0) {
            //         $responseZero[] = $res;
            //     } else {
            //         $responseNonZero[] = $res;
            //     }
            // }

                // $insertApproveFinalReq =
                //             OPTV2FinalReq::upsert(
                //                 $insertApproveFinalReqISBNList,
                //                 ['EAN11', 'BASEDOCNUM'],
                //                 [
                //                     'TEMPEAN11','DESCRIPTION', 'PROPOSEREQQTY', 'REQUIREQTY', 'TOTALPROJQTY', 'SOHQTY',
                //                     'APPROVED', 'DATEAPPROVED', 'APPROVEDBYUSERNAME', 'USERCREATE',
                //                     'STATUS', 'created_at', 'updated_at', 'SAVED', 'PROJECTIONID',
                //                     'PULLOUTQTY', 'OMSQTY', 'ONPOQTY', 'BUFFSTOCKQTY', 'ADJSTOCKQTY'
                //                 ]
                //             );

        }

    
        // return response()->json([

        //     "zero" => $responseZero, 
        //     "non_zero" => $responseNonZero
        // ]);

        return response()->json($response);

    }

    public function datatable_tempcustomertitle_list(Request $request) {

        $basedocnum = $request->query('basedocnum');
      
        $num = 0;
                $qcustomer = OPTv2Projectionh::from('OPTV2PROJECTIONH as t1')
                ->selectRaw("
                    MAX(t1.id) as MAXID,
                    MAX(t1.CUSTOMER) as TEMP,
                    t1.CUSTOMERNAME as NAME,
                    'Customer' as TYPE
                ")
                ->where('BASEDOCNUM',$basedocnum)
                ->where('t1.CUSTOMER','LIKE','%TEMP%')
                ->groupBy('t1.CUSTOMERNAME');
            

                $qtitle = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                ->selectRaw("
                    MAX(t1.id) as MAXID,
                    MAX(t1.EAN11) as TEMP,
                    t1.DESCRIPTION as NAME,
                    'Title' as TYPE
                ")
                ->where('BASEDOCNUM',$basedocnum)
                ->where('t1.EAN11','LIKE','%TEMP%')
                ->groupBy('t1.DESCRIPTION')   ;
            
            
            // union all (Laravel handles it fine)
            $finalQuery = $qcustomer->unionAll($qtitle);
            $queryFinal = $finalQuery->orderBy('NAME','DESC')->get();

            if($queryFinal->isEmpty()){

                $response = [
                    'num' => 0
                ];
                
            } else {

                
                foreach ($queryFinal as $r) {
                    $num++;
                    $temp = $r->TEMP;
                    $name = $r->NAME;
                    $type = $r->TYPE;

                    $nameDisplay = '<span class="line-clamp-1" title="'.$name.'"> '.$name.' </span>';

                    $response[] = array (
                        "num" => $num,
                        "temp" => $temp,
                        "name" => $nameDisplay,
                        "type" => $type,

                    );
                   
                      
                    
                }
               
                
            }

            return response()->json($response);


    }
    public function datatable_insertedisbn_finalreq_list(Request $request) {

        $pernr = session('pernr');
        $basedocnum = $request->query('basedocnum');
        $filter = $request->query('filter');
      
        // $notapprovedfinalreqisbn =  OPTv2FinalReq::where('BASEDOCNUM',$basedocnum)
        //                                     ->where('APPROVED','!=','1')
        //                                     ->orderBy('id','DESC')
        //                                     // ->get();
        //                                     ->pluck('EAN11')
        //                                     ->toArray();
    
        $num = 0;
        
        $_qisbnforapprovalfinalreq = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->select(
                                                "EAN11",
                                                DB::raw("MAX(DESCRIPTION) as DESCRIPTION"),
                                                DB::raw("SUM(CAST(QTY AS INT)) as TOTALPROJTN"),
                                                DB::raw("(SELECT TOP 1 PROPOSEREQQTY FROM OPTV2FINALREQ t2 WHERE t2.EAN11 = t1.EAN11 AND t2.BASEDOCNUM = '".$basedocnum."' ) as PROPOSEREQQTY"),
                                                DB::raw("(SELECT TOP 1 BUFFSTOCKQTY FROM OPTV2FINALREQ t2 WHERE t2.EAN11 = t1.EAN11 AND t2.BASEDOCNUM = '".$basedocnum."' ) as BUFFSTOCKQTY")
                                                )
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->whereExists(function ($query) use ($basedocnum) {
                                                $query->select(DB::raw(1))
                                                    ->from('OPTV2FINALREQ as f')
                                                    ->whereColumn('f.EAN11', 't1.EAN11')
                                                    ->where('f.BASEDOCNUM', $basedocnum)
                                                    ->where('f.APPROVED', '!=', '1');
                                            })
                                            // ->whereIn('EAN11',$notapprovedfinalreqisbn)
                                            // ->whereNotNull('t1.APPROVED')
                                            ->groupBy('EAN11')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ;
    
        if($filter == '0'){
            $_qisbnforapprovalfinalreq->whereRaw("(SELECT PROPOSEREQQTY FROM OPTV2FINALREQ t2 WHERE t2.EAN11 = t1.EAN11 AND t2.BASEDOCNUM = '".$basedocnum."' )  < 1");

        }

        if($filter == '2'){

            $_qisbnforapprovalfinalreq->whereRaw("(SELECT PROPOSEREQQTY FROM OPTV2FINALREQ t2 WHERE t2.EAN11 = t1.EAN11 AND t2.BASEDOCNUM = '".$basedocnum."' )  > 0");

        }

        $qisbnforapprovalfinalreq = $_qisbnforapprovalfinalreq->get();
        if($qisbnforapprovalfinalreq->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            $isbnlist = [];

            foreach($qisbnforapprovalfinalreq as $c) {
                $num++;
            
                $isbn = $c->EAN11;
                $isbnlist[] = $isbn;
                $description = $c->DESCRIPTION;
        
                $descriptiontruncate = truncatelimitWords($description,22) ;

                $totalproj = $c->TOTALPROJTN;
                $soh =  0;
                $pullouttransit =  0;
                $onorderoms =   0;
                $onpo = 0;
                $bufferstock = $c->BUFFSTOCKQTY;
                $adjstock = round($soh + $pullouttransit - $onorderoms + $onpo - $bufferstock);
                $requireqty = $adjstock - $totalproj;
                $roundreqqty =  $requireqty < 0 ? 0 :round($requireqty, -1) ?? 0; // -1 is nearest 20 
                $propreqvaldb = $c->PROPOSEREQQTY;
                $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
         
                $checkisbn = '
                    <div class="d-flex justify-content-center">
                            <input class="form-check-input for_approval_finalreq_isbn_approve_check" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" type="checkbox" value="">
                    </div>';
                $totalprojDisplay = '<a href="javascript:void(0)" class="titleprojectionaelist" data-totalprojtn="'.$totalproj.'" data-title="'.$description.'" data-basedocnum="'.$basedocnum.'" data-isbn="'.$isbn.'">'.$totalproj.'</a>';

                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "description" => $descriptionDisplay,
                    "totalproj" => $totalprojDisplay,
                    "descriptionval" => $description,
                    "soh" => '-',
                    "pullouttransit" => '-',
                    "onorderoms" => '-',
                    "onpo" => $onpo,
                    "bufferstock" => $bufferstock,
                    "adjstock" => '-',
                    "requireqty" => '-',
                    "proprequireqty" => '-',
                    "propreqval" => '-',
                    "propreqvaldb" => $propreqvaldb,
                    "total_1" => '-',
                    "total_2" => '-',
                    "total_3" => '-',
                    "checkisbn" => $checkisbn,
                );

                

            }

            //better performance to get data per isbn??
            $omsData = get_omstransact_isbn($isbnlist,$basedocnum);
            $pulloutData = get_pullout_isbn($isbnlist);
            $sohData = get_soh_isbn($isbnlist);
            $onpoData = get_onpo_isbn($isbnlist);
            // get_misopenpo() was never defined — commit ef67415 shipped the call, production
            // fatals with "Call to undefined function". Reverted to get_onpo_isbn() (pre-ef67415
            // behavior). Intended source: MIS DB (172.16.0.199), tables empty + MATNR-keyed —
            // blocked until team defines which table refreshes + MATNR->EAN11 mapping.
            // $misopenpoData =  get_misopenpo();
            $allocatedData = getAllocatedMainProjectionDeductSOH ($basedocnum,$isbnlist);

            $allocatedMap = [];
            foreach ($allocatedData as $d5) {
                $allocatedMap[$d5->EAN11] = round($d5->TOTALALLOCATED);
            }
         

            $omsMap = [];
            foreach ($omsData as $row) {
                $omsMap[$row->EAN11] = round($row->OMSQTY) ?? 0;
            }

            $pulloutMap = [];
            foreach ($pulloutData as $row) {
                $pulloutMap[$row->EAN11] = round($row->PULLOUTQTY) ?? 0;
            }

            $sohMap = [];
            foreach ($sohData as $row) {
                $sohMap[$row->EAN11] = round($row->SOHQTY) ?? 0;
            }
            $onpoMap = [];
            foreach ($onpoData as $d4) {
                $onpoMap[$d4->EAN11] = round($d4->ONPOQTY) ?? 0;
            }
            // foreach ($misopenpoData as $isbn => $qty) {
            //     $onpoMap[$isbn] = round($qty) ?? 0;
            // }
//------------------------

//ISBN sales per 3 years 
    $isbnPrev3YearSalesHistory = isbnPrev3YearSalesHistory ($isbnlist);
    $salesMap = [];
    foreach ($isbnPrev3YearSalesHistory as $salesData) {
        $salesMap[$salesData->isbn] = [
            "total_1" => $salesData->total_1,
            "total_2" => $salesData->total_2,
            "total_3" => $salesData->total_3
        ];

    }
//------------------------

// dd($sohMap);

//final response
            foreach ($response as &$res) {
                $isbn = $res['isbn'];
                $propreqvalset = $res['propreqval'];
                $description = $res['descriptionval'];

                // set values from maps (0 default kung wala)
                $allocated = $allocatedMap[$isbn] ?? 0;
                $soh = $sohMap[$isbn] ?? 0;
                $pullouttransit = $pulloutMap[$isbn] ?? 0;
                $onorderoms = $omsMap[$isbn] ?? 0;
                $onpo = $onpoMap[$isbn] ?? 0; 
                $sohFinal = $soh - $allocated;
                // $sohFinal = $soh;

                $saleshistorytotal_1 = $salesMap[$isbn]['total_1'] ?? 0;
                $saleshistorytotal_2 = $salesMap[$isbn]['total_2'] ?? 0;
                $saleshistorytotal_3 = $salesMap[$isbn]['total_3'] ?? 0;
               // if mayroon kang actual value

                // recompute formula
                $totalproj = strip_tags($res['totalproj']); // remove html <a> para makuha numeric
                $bufferstock = $res['bufferstock'];
                $adjstock = round($sohFinal + $pullouttransit - $onorderoms + $onpo - $bufferstock);
                $requireqty = $adjstock - $totalproj;
                $roundreqqty =  $requireqty < 0 ? 0 :round($requireqty, -1) ?? 0;
                
                $propreqval =  $res["propreqvaldb"] ?? $roundreqqty;

                $proprequireqty = '<input class="form-control text-center p-1 proposedreq_qty  " 
                type="number" data-customercode="" data-isbn="'.$isbn.'" 
                data-description="'.$description.'" data-basedocnum="'.$basedocnum.'" 
                data-totalproj="'.$totalproj.'" data-soh="'.$sohFinal.'" 
                data-pullouttransit="'.$pullouttransit.'" data-onorderoms="'.$onorderoms.'" 
                data-onpo="'.$onpo.'" data-bufferstock="'.$bufferstock.'" 
                data-adjstock="'.$adjstock.'" data-requireqty="'.$requireqty.'" 
                value="'.$propreqval.'" min="1">'; 

                $bufferstock = '<input class="form-control text-center p-1 bufferstock_qty  " 
                type="number" value="'.$bufferstock.'" min="1">'; 


                $adjstockDisplay  = '<span class="adstockinsertedisbntext">' . $adjstock . '</span>';
                $requireqtyDisplay = '<span class="requireqtyinsertedisbntext">' . $requireqty . '</span>';
      
                // update response
                $res['soh'] = $sohFinal;
                $res['onpo'] = $onpo;
                $res['pullouttransit'] = $pullouttransit;
                $res['onorderoms'] = $onorderoms;
                $res['bufferstock'] = $bufferstock;
                $res['adjstock'] = $adjstockDisplay;
                $res['requireqty'] = $requireqtyDisplay;
                $res['propreqval'] = $propreqval;
                $res['proprequireqty'] = $proprequireqty;
                $res['total_1'] = $saleshistorytotal_1;
                $res['total_2'] = $saleshistorytotal_2;
                $res['total_3'] = $saleshistorytotal_3;
            }
            unset($res);
//-------------------------------
        }
        
   
        
    
        return response()->json($response);

    }

    public function datatable_stockallocate_isbn_user_list(Request $request) {

        $pernr = session('pernr');
        $basedocnum = $request->query('basedocnum');
        $isbn = $request->query('isbn');
      
        $num = 0;
        $isbnlist = [];
        
        $qisbnstockallocation = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->select(
                                                "EAN11",
                                                DB::raw("MAX(PERNR) as PERNR"),
                                                DB::raw("MAX(USERNAME) as USERNAME"),
                                                DB::raw("MAX(DESCRIPTION) as DESCRIPTION"),
                                                DB::raw("MAX(AUTHOR) as AUTHOR"),
                                                DB::raw("SUM(CAST(QTY AS INT)) as TOTALPROJTN"),
                                                DB::raw("(SELECT PROPOSEREQQTY FROM OPTV2FINALREQ t2 WHERE t2.EAN11 = t1.EAN11 AND t2.BASEDOCNUM = '".$basedocnum."' ) as PROPOSEREQQTY")
                                                )
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->where('EAN11',$isbn)
                                            ->whereNotNull('t1.APPROVED')
                                            ->whereNull('t1.ALLOCATED')
                                            ->groupBy('EAN11','USERNAME')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ->get();
    
        if($qisbnstockallocation->isEmpty()){
            $response[] = array(
                "num" => "0",
            );
        }
        else {


            $qisbnstockallocationbranchwhouseqty = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->select(
                                                "BRANCHWHOUSE",
                                                DB::raw("SUM(CAST(QTY AS INT)) as TOTALPROJTN") 
                                            )
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->where('EAN11',$isbn)
                                            ->whereNull('t1.ALLOCATED')
                                            ->groupBy('BRANCHWHOUSE')
                                            ->orderByRaw('TOTALPROJTN DESC')
                                            ->get();
                                            
            foreach($qisbnstockallocation as $c) {
                $num++;
            
                $isbn = $c->EAN11;

                $isbnlist[] = $isbn;
                $description = $c->DESCRIPTION;
                $author = $c->AUTHOR;
                $username = $c->USERNAME;
                $qusernameDetails = userNameDetails($username);
        
                $descriptiontruncate = truncatelimitWords($description,40) ;
                $authortruncate = truncatelimitWords($author,35) ;

             

                $totalproj = $c->TOTALPROJTN;
                $qpernrqty = pernrqty_stockallocate_approved_isbn($basedocnum,$isbn,$username)[0] ;
                $bsa = $qpernrqty->bsa_1;
                // $bsa = 15;
                $nonbsa = $qpernrqty->bsa_0;
                $bsaallocateqty = 0;
                $nonbsaallocateqty = 0;
                $total1 = 0;
                $total2 = 0;
                $total3 = 0;
                $propreqval = $c->PROPOSEREQQTY ?? 0;
                $usernamefname = $qusernameDetails->FULLNAME;
                $aepernr = trim($c->PERNR);

           

  
//customer isbn sales history           
             $pernrCustomerIsbnPrev3YearSalesHistory = pernrCustomerIsbnPrev3YearSalesHistory('',$isbnlist,$aepernr);
             $salesisbnMap = [];    

             $totalPrev1Display = 0;
             $totalPrev2Display = 0;
             $totalPrev3Display = 0;
 
             if(!$pernrCustomerIsbnPrev3YearSalesHistory->isEmpty()){
                
                foreach ($pernrCustomerIsbnPrev3YearSalesHistory as $aaa){
 
                    $isbnhistory = $aaa->isbn;
                    $totalPrev1 = $aaa->total_1 ?? 0;
                    $totalPrev2 = $aaa->total_2 ?? 0;
                    $totalPrev3 = $aaa->total_3 ?? 0;

                    $totalPrev1Display = number_format($totalPrev1);
                    $totalPrev2Display = number_format($totalPrev2);
                    $totalPrev3Display = number_format($totalPrev3);

                
                }
          
             }
        
 //---------------

                $bsaallocateqty = '
                      <input class="form-control  p-1 border text-center border-primary-300 allocate_qty_bsa allocate_qty_input" data-isbn="'.$isbn.'" data-alloctype="bsa" data-basedocnum="'.$basedocnum.'" data-pernr="'.$aepernr.'" data-projqty="'.$bsa.'" max="'.$bsa.'" type="number" value="0" min="0">
                                     
                ';
                $nonbsaallocateqty = '
                      <input class="form-control  p-1 border text-center border-primary-300 allocate_qty_nonbsa allocate_qty_input" data-isbn="'.$isbn.'" data-alloctype="nonbsa" data-basedocnum="'.$basedocnum.'" data-pernr="'.$aepernr.'" data-projqty="'.$nonbsa.'" max="'.$nonbsa.'" type="number" value="0" min="0">
                ';

                $usernameDisplay = '
                        <span title="'.$aepernr.'">'.$usernamefname.'</span>
                            ';
                $response['stockallocateuserlist'][] = array(
                    "num" => $num,
                    "username" => $usernameDisplay,
                    "bsa" => $bsa,
                    "nonbsa" => $nonbsa,
                    "aepernr" => $aepernr,
                    "bsaallocateqty" => $bsaallocateqty,
                    "nonbsaallocateqty" => $nonbsaallocateqty,
                    "total1" => $totalPrev1Display,
                    "total2" => $totalPrev2Display,
                    "total3" => $totalPrev3Display,
                );

            }


        }

    
        foreach ($qisbnstockallocationbranchwhouseqty as $bwqty) {

            $branchwhouse = $bwqty->BRANCHWHOUSE;
            $projtnbwqty = $bwqty->TOTALPROJTN;

            $branchwhouseDisplay = getActiveLocationName($branchwhouse);

            $response['branchwhouseqty'][] = array (
                "branchwhouse" => $branchwhouseDisplay ,
                "qtybwqty" => $projtnbwqty,

            );
            
        }
        return response()->json($response);

    }

    public function datatable_stock_allocation_list(Request $request) {

        $pernr = session('pernr');
        $basedocnum = $request->query('basedocnum');
      
        $num = 0;
        
        $alreadyallocatedisbn =  OPTv2Allocated::where('BASEDOCNUM',$basedocnum)
                                ->pluck('EAN11')
                                ->toArray()
                                ;
                                
        $notallocatedapprovedprojectiond = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->select(
                                                "EAN11",
                                                DB::raw("MAX(DESCRIPTION) as DESCRIPTION"),
                                                DB::raw("MAX(AUTHOR) as AUTHOR"),
                                                DB::raw("COUNT(DISTINCT PERNR) as COUNTAE"),
                                                DB::raw("SUM(CAST(QTY AS INT)) as TOTALPROJTN"),
                                                DB::raw("(SELECT PROPOSEREQQTY FROM OPTV2FINALREQ t2 WHERE t2.EAN11 = t1.EAN11 AND t2.BASEDOCNUM = '".$basedocnum."' AND t2.APPROVED = '1' ) as PROPOSEREQQTY")
                                                )
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->whereNotNull('t1.APPROVED')
                                            ->whereNull('t1.DATEALLOCATED')
                                            ->groupBy('EAN11')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ->pluck('EAN11')
                                            ->toArray();

        // $qisbnstockallocation =   OPTv2FinalReq::where('BASEDOCNUM',$basedocnum)
        //                                     ->where('APPROVED','1')
        //                                     ->whereIn('EAN11',$notallocatedapprovedprojectiond)
        //                                     ->whereNotIn('EAN11',$alreadyallocatedisbn)
        //                                     ->orderBy('id','DESC')
        //                                     ->get();

        $qisbnstockallocation = OPTv2FinalReq::from('OPTV2FINALREQ as fr')
                                    ->where('fr.BASEDOCNUM', $basedocnum)
                                    ->where('fr.APPROVED', '1')

                                    // dapat may projectiond record na approved at hindi pa dateallocated
                                    ->whereExists(function ($query) use ($basedocnum) {
                                        $query->select(DB::raw(1))
                                            ->from('OPTV2PROJECTIOND as pd')
                                            ->whereColumn('pd.EAN11', 'fr.EAN11')
                                            ->where('pd.BASEDOCNUM', $basedocnum)
                                            ->whereNotNull('pd.APPROVED')
                                            ->whereNull('pd.DATEALLOCATED');
                                    })

                                    // at wala pang allocated record
                                    ->whereNotExists(function ($query) use ($basedocnum) {
                                        $query->select(DB::raw(1))
                                            ->from('OPTV2ALLOCATED as al')
                                            ->whereColumn('al.EAN11', 'fr.EAN11')
                                            ->where('al.BASEDOCNUM', $basedocnum);
                                    })

                                    ->orderBy('fr.id', 'DESC')
                                    ->get();
                                    
        if($qisbnstockallocation->isEmpty()){
            $response = [
                "num" => "0",
            ];
        }
        else {

            foreach($qisbnstockallocation as $c) {
                $num++;
            
                $isbn = $c->EAN11;
                $description = $c->DESCRIPTION;
                $author = $c->AUTHOR;
                $countae = $c->COUNTAE;
        
                $descriptiontruncate = truncatelimitWords($description,37) ;
                $authortruncate = truncatelimitWords($author,30) ;

                $totalproj = $c->TOTALPROJQTY;
                $soh = '0';
                $pullouttransit = '0';
                $onorderoms = '0';
                $onpo = '0';
                $bufferstock = '0';
                $adjstock = '0';
                $requireqty = '0';
                $propreqval = $c->PROPOSEREQQTY ?? 0;

                // $propreqval = 10;

                $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
                $authorDisplay = '<span class="" title="'.$author.'"> '.$authortruncate.' </span>';
                $proprequireqty = '<input class="form-control text-center p-1  " 
                                        type="number" data-customercode="" data-isbn="'.$isbn.'" 
                                        data-description="'.$description.'" data-basedocnum="'.$basedocnum.'" 
                                        data-totalproj="'.$totalproj.'" data-soh="'.$soh.'" 
                                        data-pullouttransit="'.$pullouttransit.'" data-onorderoms="'.$onorderoms.'" 
                                        data-onpo="'.$onpo.'" data-bufferstock="'.$bufferstock.'" 
                                        data-adjstock="'.$adjstock.'" data-requireqty="'.$requireqty.'" 
                                        value="'.$propreqval.'" min="1">'; 
        

                $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                        <a href="#" data-bs-target="#UserStockAllocateQtyModal" data-bs-toggle="modal" class="dropdown-item text-primary btn_stockallocatedetails" data-title="'.$description.'" data-doctotalproj="'.$totalproj.'" data-approvereq="'.$propreqval.'" data-isbn="'.$isbn.'">
                                                        View</a>';
                                // <div class="dropdown-divider"></div>    
                        
                    
                $action .= "
                                    </div>
                            </div>";

                $checkbox = '
                    <div class="d-flex justify-content-center">
                            <input class="form-check-input stockallocateisbn" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'" type="checkbox" value="">
                    </div>
                ';

                $countaeDisplay = '<a href="javascript:void(0)" class="titleprojectionaelist" data-totalprojtn="'.$totalproj.'" data-title="'.$description.'" data-basedocnum="'.$basedocnum.'" data-isbn="'.$isbn.'">'.$countae.'</a>';
                $totalprojDisplay = '<a href="javascript:void(0)" class="titleprojectionaelist" data-totalprojtn="'.$totalproj.'" data-title="'.$description.'" data-basedocnum="'.$basedocnum.'" data-isbn="'.$isbn.'">'.$totalproj.'</a>';


                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "description" => $descriptionDisplay,
                    "author" => $authorDisplay,
                    "countae" => $countaeDisplay,
                    "totalproj" => $totalprojDisplay,
                    "proprequireqty" => $proprequireqty,
                    "propreqval" => $propreqval,
                    "action" => $action,
                    "checkbox" => $checkbox,
                );

            }
        }

    
        return response()->json($response);

    }



public function datatable_customer_saleshistory(Request $request) {

    $kunnr = $request->query('customercode');
    $year = $request->query('year');
      

      $q = NewSales::from('new_sales as t1')
                  ->select(
                            DB::raw('SUM(t1.qty) as TOTAL'),
                            DB::raw('MAX(t1.doc_month) as MONTH'),
                            
                            )
                  ->where('t1.doc_year',$year)
                  ->where('t1.cust_code',$kunnr)
                  ->orderBy('t1.doc_month','ASC')
                  ->groupBy('t1.doc_month');


      $qresults = $q->get();

    if($qresults->isEmpty()) {
        $response[] = array(
            "customercode" => $kunnr,
            "total" => '-',
            "monthnumber" => '-',
            "monthname" => '-',
        );
    }else {
        foreach($qresults as $custr) {

        
            $customercode = $custr->cust_code;
            $total = $custr->TOTAL;
            $month = $custr->MONTH;
        
            $totalDisplay = number_format($total);
            $monthDisplay = convertMonthNumberToName($month);

            if($totalDisplay !== '0'){

                $response[] = array(
                    "customercode" => $kunnr,
                    "total" => $totalDisplay,
                    "monthnumber" => $month,
                    "monthname" => $monthDisplay,
                );

            }
         

    }

    }




    return response()->json($response);


}

public function datatable_customer_link_accounts(Request $request) {

    $pernr = session('pernr');
      
    // $qcustomerlink =  OPTv2CustomerLink::where('USERCREATE',$pernr)
    //                                     ->orderBy('id','DESC')
    //                                     ->get();

    $qcustomerlink = CrmMotherLookup::whereIn('AE',userteam())
                ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
                ->orderByRaw('CUSTNAME','ASC')
                ->selectRaw('
                                MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, 
                                MAX(RTRIM(CUSTNO)) as CUSTNO, 
                                MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, 
                                MAX(AE) as AE, 
                                MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
                            
                                '
                                )
                ->groupBy('t2.CUSTNO')
                ->get();
    $num = 0;
    
    if($qcustomerlink->isEmpty()) {
        $response = [
            "num" => 0
        ];

    } else {
        foreach($qcustomerlink as $c) {
                $num++;
            
                $customercode = $c->CUSTNO;
           
                $department = $c->DEPARTMENT;
                // $docdate = $c->DOCDATE;
                $frompernr = $c->AE;
                $topernr = $c->TOPERNR;

                $customerlist[] = $customercode;
                $fromaelist[] = $frompernr;
                
                $frompernrDisplay =  $frompernr;
                // $topernrDisplay = userDetails($topernr)->FULLNAME . " &nbsp" . $topernr;

                $dept = '';

                if (!is_null($department) && $department !== '') {
                    $dept = ' - ' . $department;
                }

                $customername = $c->CUSTNAME . $dept;

                $customernameDisplay = '<span class="text-start line-clamp-1" title="'.$customername.'">'.$customercode.'&nbsp ' .$customername . '</span>' ;

                $response[] = array(
                    "num" => $num,
                    "customercode" => $customercode,
                    "customername" => $customernameDisplay,
                    "customernametext" => $customername,
                    "frompernr" => $frompernr,
                    "frompernrdisplay" => '-',
                    "topernr" => '-',
                    "topernrdisplay" => '-',
                    "docdate" => '-',
                    "action" => '-',
                );

        }

        

// kunin mo lng from pernr name

        $qfromae = OPTv2User::whereIn('PERNR',array_unique($fromaelist))
                                ->get();

        $qfromaeName = [];
        foreach($qfromae as $qfae) {

            $qfromaeName[$qfae->PERNR]['FULLNAME'] = $qfae->FULLNAME;

        }

        foreach ($response as &$res) {

            $frompernr = isset($res['frompernr']) ? trim($res['frompernr']) : '-';
        
            $fullname = $qfromaeName[$frompernr]['FULLNAME'] ?? '-';
        
            $res['frompernrdisplay'] = $frompernr . ' &nbsp;' . $fullname;
        }

        unset($res);
//-----------------

// kunin mo yung mga customercodes na may naka link

        // $customerCodes = array_values(array_unique(array_map(
        //     fn($r) => trim($r['customercode'] ?? ''),
        //     $response
        // )));
        
      

        // foreach (array_chunk($customerCodes, 2000) as $chunk) { // 2000 safe
        //     OPTv2CustomerLink::whereIn('CUSTOMER', $chunk)
        //         ->where('STATUS', '1')  // only what you need
        //         ->get()
        //         ->each(function ($row) use (&$customerTopMap) {
        //             $customerTopMap[trim($row->CUSTOMER)]['TOPERNR'] = trim($row->TOPERNR ?? '');
        //             $customerTopMap[trim($row->CUSTOMER)]['TOPERNRNAME'] = trim($row->TOPERNRNAME ?? '');
        //         });
        // }

        $customerTopMap = []; 

        $qcustomerlink = OPTv2CustomerLink::whereIn('FROMPERNR', array_unique($fromaelist))
                ->where('STATUS', '1')  
                ->get();

        foreach ($qcustomerlink as $qcl) {

            $customerTopMap[trim($qcl->CUSTOMER)]['TOPERNR'] = trim($qcl->TOPERNR ?? '');
            $customerTopMap[trim($qcl->CUSTOMER)]['TOPERNRNAME'] = trim($qcl->TOPERNRNAME ?? '');
        }
        
        $filterPernr = filter_user_list('0')->get()
                                    ->map(fn($u) => [
                                        'PERNR' => $u->PERNR,
                                        'FULLNAME' => $u->FULLNAME,
                                    ])
                                    ->values()
                                    ->toArray();
        $pernrOptions = '';

        foreach ($filterPernr as $u) {
            $pernr = trim($u['PERNR']);
            $name  = htmlspecialchars($u['FULLNAME'], ENT_QUOTES, 'UTF-8');
        
            // pwede mo i-format kung ano gusto mo ipakita
            $pernrOptions .= ' <a href="#" class="dropdown-item p-1 text-primary btn-linkto" data-linktopernr="'.$pernr.'" data-linktopernrname="'.$name.'"> '.$pernr.' - '.$name.' </a>';
        }

        foreach ($response as &$res) {
            $code = trim($res['customercode'] ?? '');
            $topernrname = $customerTopMap[$code]['TOPERNRNAME'] ?? '-';
            $topernr = $customerTopMap[$code]['TOPERNR'] ?? '';
            $customercode = $res['customercode'];
            $customername = $res['customernametext'];
            $frompernr =  trim($res['frompernr']);
            $res['topernr'] = $topernr ;
            $res['topernrdisplay'] = $topernr . ' &nbsp' . $topernrname;

            $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
            <button class="btn btn-sm border p-1 dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2"
                type="button"
                data-bs-toggle="dropdown"
                data-bs-container="body"
                data-bs-boundary="viewport"
                aria-expanded="false">
                <span class="fas fa-ellipsis-h fs--2"></span>
        </button>
          <div class="dropdown-menu dropdown-menu-end py-2" style="height:35vh; overflow-y:auto;overflow-x:hidden;">
                        <a href="#" class="dropdown-item text-danger p-1 btn-unlink" data-customer="'.$customercode.'" data-customername="'.$customername.'">
                                        Unlink
                        </a>
                        <div class="dropdown-divider"></div>    
                        <span class="fw-bold px-1"> Link To</span>
                            <input class="linkfrompernr un-cl d-none"  readonly="readonly" value="'.$frompernr.'" > 
                            <input class="linktoexistpernr un-cl d-none"  readonly="readonly" value="'.$topernr.'" > 
                            <input class="linktocustomercode un-cl d-none"  readonly="readonly" value="'.$customercode.'" > 
                            <input class="linktocustomername un-cl d-none"  readonly="readonly" value="'.$customername.'" > 

                            '.$pernrOptions.'
                        ';
                                        
            
        
    
            $action .= "
                                </div>
                        </div>";
                        $res['action'] = $action;
            
        }
        unset($res);
//-------------------


    }




    return response()->json($response);


}

public function datatable_dashboard_allocationsummary_list (Request $request) {

    $basedocnum = $request->query('basedocnum');
    $pernrurl = $request->query('pernr');

    $thisyear = getPreviousYear(0);

    if($pernrurl == '1') {

         $qpernr = filter_user_list()->get()
           // ->pluck('PERNR')->toArray();
                    ;

        foreach($qpernr as $q) {
                $pernr[] = trim($q->PERNR);
        }

    } else {
        $pernr[] = $pernrurl;
    }

    $qdashboardallocated =  OPTv2Allocated::selectRaw("
                MAX(EAN11) as EAN11,
                SUM(CAST(QTY AS INT)) as TOTALABALANCE,
                SUM(CASE WHEN ALLOCTYPE = 'nonbsa' THEN CAST(QTY AS INT) ELSE 0 END) as TOTALABALANCENONBSA,
                SUM(CASE WHEN ALLOCTYPE = 'bsa' THEN CAST(QTY AS INT) ELSE 0 END) as TOTALABALANCEBSA,
                SUM(CAST(ALLOCATED AS INT)) as TOTALALLOCATED,
                SUM(CAST(PROJECTION AS INT)) as TOTALPROJTN
            ")
            ->where('BASEDOCNUM',$basedocnum)
            ->whereIn('PERNR',$pernr)
            ->groupBy('EAN11')
            ->get();

    $allocatedisbn = [];

    if($qdashboardallocated->isEmpty()) {

        $response = [ 
            "num" => 0
        ];

    

    } else {

        $num = 0;
        foreach ($qdashboardallocated as $r) {
         
            $isbn = $r->EAN11;
            $totalprojtn = $r->TOTALPROJTN;
            $totalallocated = $r->TOTALALLOCATED;
            $totalbalancedb = $r->TOTALABALANCE;
            $totalbalancedbnonbsa = $r->TOTALABALANCENONBSA;
            $totalbalancedbbsa = $r->TOTALABALANCEBSA;
            
            $allocatedisbn[] = $isbn;
         
            $totalallocatedDisplay = '<a ref="" title="Show Summary" class="dashboardbsoallocationbtn" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'">' . $totalallocated  . '</a> ';
           

            $response [] = [
                "num" => $num,
                "isbn" => $isbn,
                "description" => '',
                "descriptiontext" => '',
                "allocrate" => '',
                "alloctransferin" => '',
                "alloctransferout" => '',
                "ordered" => '',
                "allocbal" => '',
                "totalbalancedbnonbsa" => $totalbalancedbnonbsa,
                "totalbalancedbbsa" => $totalbalancedbbsa,
                "allocbaldb" => $totalbalancedb,
                "totalprojtndat" => $totalprojtn,
                "totalallocateddat" => $totalallocated,
                "totalprojtn" => number_format($totalprojtn),
                "totalallocated" => $totalallocatedDisplay,

            ];

            

        }
//DESCRIPTION-------------------

        $qmatdel = ZmmMatdel::select('*')
                ->whereIn('EAN11',$allocatedisbn)
                ->get();

        $descMap = [];
        foreach($qmatdel as $qmat) {

            $maktx = $qmat->MAKTX;
            $descMap['descdisplay'][$qmat->EAN11] = '<span class="line-clamp-1" title="'.$maktx.'">' . strtoupper($maktx) . '</span>';
            $descMap['desctext'][$qmat->EAN11] = $maktx;
        }
         
 
 //-------------------DESCRIPTION  
 
 //alloc req in
        $allocInQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
        ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
        ->selectRaw("
            EAN11,
            SUM(CAST(QTY AS INT))  AS TOTALQTYREQIN
        ")
        ->whereIn('t1.PERNR', $pernr)
        ->where('t2.BASEDOCNUM', $basedocnum)
        ->where('t1.APPROVED','1')
        ->groupBy('t1.EAN11')
        ->get();

        
        $dballocInMap = [];
        foreach ($allocInQ as $d1) {
            $dballocInMap[$d1->EAN11] = $d1->TOTALQTYREQIN;
        }
 //---------alloc req in


//alloc req out
    $allocOutQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
    ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
    ->selectRaw("
        EAN11,
        SUM(CAST(QTY AS INT)) AS TOTALQTYREQOUT
    ")
    ->whereIn('t2.REQTO', $pernr)
    ->where('t2.BASEDOCNUM', $basedocnum)
    ->where('t1.APPROVED','1')
    ->groupBy('t1.EAN11')
    ->get();
    
    $dballocOutMap = [];
    foreach ($allocOutQ as $d2) {
        $dballocOutMap[$d2->EAN11] = $d2->TOTALQTYREQOUT;
    }
//---------alloc req out

//ordered
$qOrdered = get_year_omstransact_isbn($pernr,$basedocnum);

$dorderedMap = [];
foreach ($qOrdered as $d3) {
    $dorderedMap[$d3->EAN11] = $d3->OMSQTY;
}
//---------ordered

// dd($dballocInMap);

    foreach($response as &$res){
        $num++;
        $isbn = $res['isbn'];
        $allocated = $res['totalallocateddat'];
        $projtn = $res['totalprojtndat'];
        $title = isset($descMap['desctext'][$res['isbn']]) 
            ? $descMap['desctext'][$res['isbn']] 
            : '';
        $allocrate = 0;
        $allocin = $dballocInMap[$isbn] ?? 0;
        $allocout =  $dballocOutMap[$isbn] ?? 0;
        // $ordered =   0;
        $ordered =   $dorderedMap[$isbn] ?? 0;
        $orderedDisplay = number_format($ordered);
        $allocrate = ($projtn > 0)
                ? round(($allocated / $projtn) * 100)
                : 0;
        $allocbal = $allocated + $allocin - $allocout - $ordered;

        if ($allocrate <= 30) {
            $allocRateColor = 'text-warning-600';
        } 
        elseif ($allocrate <= 70) {
            $allocRateColor = 'text-warning-600'; // usually blue
        } 
        elseif ($allocrate <= 99) {
            $allocRateColor = 'text-info-600'; // usually blue
        } elseif ($allocrate >= 100) {
            $allocRateColor = 'text-success-600';
        } else {
            $allocRateColor = 'text-danger-600'; // fallback
        }

       $totalbalancedbnonbsa = $res['totalbalancedbnonbsa'];
       $totalbalancedbbsa = $res['totalbalancedbbsa'];
       $desctext = isset($descMap['desctext'][ $res['isbn']]) ? $descMap['desctext'][ $res['isbn']] : '';
       $descdisplay = isset($descMap['descdisplay'][ $res['isbn']]) ?$descMap['descdisplay'][ $res['isbn']] : '';
       $alloctypeBalance = '
       <b>'.$isbn.'
       </b>
        </br>
           Non-BSA: &nbsp&nbsp <b> '.$totalbalancedbnonbsa.'</b> </br>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspBSA: &nbsp&nbsp  <b> '.$totalbalancedbbsa.'</b>
        ';
         
        $allocbalDisplay = '<a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true"  title="'.$alloctypeBalance.'" data-totalbalance="'.$res['allocbaldb'].'">'.$allocbal.'</a>';
        
        $totalprojtnDisplay = '<a href="#" class="dashboardisbnprojtncustomerlist" title="Customer List"  data-totalprojtn="'.$projtn.'" data-isbn="'.$isbn.'" data-title="'.$desctext.'" data-basedocnum="'.$basedocnum.'" >'.$projtn.'</a>';
         
        $allocrateDisplay = '<span class="'.$allocRateColor.'">'.$allocrate.'%</span>';
        $res['num'] = $num;
        $res['totalprojtn'] = $totalprojtnDisplay;
        $res['allocrate'] = $allocrateDisplay;
        $res['alloctransferin'] =  $allocin;
        $res['alloctransferout'] = $allocout;
        $res['ordered'] = $orderedDisplay;
        $res['descriptiontext'] = $desctext;
        $res['description'] = $descdisplay;
        
        $res['allocbal'] = $allocbalDisplay;

        
    } 
    unset($res);


      usort($response, function ($a, $b) {
            // Kapag int ordering:  $b['totalprojtn'] <=> $a['totalprojtn'];
            return strcmp($a['descriptiontext'] ?? '', $b['descriptiontext'] ?? '');

        });

    }

   

    return response()->json($response);



}

public function approvals_count(Request $request)
{
    $pernr = trim(session('pernr'));
    $rank = trim(session('rank'));

    $filterPernr = filter_user_list('0', '1')->get();
    $arrayFilterPernr = $filterPernr->pluck('PERNR')->toArray();


    /** -----------------------
     *  Query 1: PROJECTION
     * ---------------------- */
    $projectionQ = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        ->selectRaw("
            t1.USERNAME,
            t1.BASEDOCNUM
        ")
        ->where('t1.SUBMIT', '1')
        ->whereNull('t1.APPROVED')
        ->whereIn('t1.PERNR', $arrayFilterPernr)
        ->groupBy(
            't1.USERNAME',
            't1.BASEDOCNUM'
        );

    $this->applyRankApprovalFilter(
        $projectionQ,
        $rank
    );

    $cntProjection = (int) $projectionQ->get()->count();


    /** -----------------------
     *  Query 2: ALLOCATION IN
     * ---------------------- */
    $allocInQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
        ->leftJoin(
            'OPTV2ALLOCREQH as t2',
            't1.DOCNUM',
            '=',
            't2.DOCNUM'
        )
        ->selectRaw("
            t1.REFERENCE,
            t1.BASEDOCNUM
        ")
        ->whereIn(
            't1.PERNR',
            $arrayFilterPernr
        )
        ->whereNull('t1.APPROVED')
        ->whereNull('t1.CANCEL')
        ->whereNotNull('t1.SUBMIT')
        ->groupBy(
            't1.REFERENCE',
            't1.BASEDOCNUM'
        );

    $this->applyRankApprovalFilter(
        $allocInQ,
        $rank
    );

    $cntAllocIn = (int) $allocInQ->get()->count();


    /** -----------------------
     *  Query 3: ALLOCATION OUT
     * ---------------------- */
    $allocOutQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
        ->leftJoin(
            'OPTV2ALLOCREQH as t2',
            't1.DOCNUM',
            '=',
            't2.DOCNUM'
        )
        ->selectRaw("
            t1.REFERENCE,
            t1.BASEDOCNUM
        ")
        ->whereNull('t1.CANCEL')
        ->whereNull('t1.APPROVED')
        ->whereNotNull('t1.SUBMIT')
        ->groupBy(
            't1.REFERENCE',
            't1.BASEDOCNUM'
        );

    $this->applyRankApprovalFilter(
        $allocOutQ,
        $rank,
        'allocreqout'
    );

    $cntAllocOut = (int) $allocOutQ->get()->count();


    /** -----------------------
     *  Query 4: CONVERT ALLOCATION
     * ---------------------- */
    $convertAllocdQ = OPTv2ConvertAllocd::from(
        'OPTV2CONVERTALLOCD as t1'
    )
        ->selectRaw("
            t1.USERNAME,
            t1.BASEDOCNUM
        ")
        ->whereNull('t1.APPROVED')
        ->whereNull('t1.CANCEL')
        ->groupBy(
            't1.USERNAME',
            't1.BASEDOCNUM'
        );

    $this->applyRankApprovalFilter(
        $convertAllocdQ,
        $rank,
        'convertalloc'
    );

    $cntConvertAllocd =
        (int) $convertAllocdQ->get()->count();


    /** -----------------------
     *  TOTAL
     * ---------------------- */
    $finalCnt =
        $cntProjection +
        $cntAllocIn +
        $cntAllocOut +
        $cntConvertAllocd;


    $qcustomertemplist = OPTv2Projectionh::where(
        'CUSTOMER',
        'LIKE',
        '%TEMP%'
    )
        ->distinct('CUSTOMERNAME')
        ->count();


    return response()->json([
        'approvalscount' => $finalCnt,
        'customertempcount' => $qcustomertemplist
    ]);
}

public function datatable_dashboard_titlecustomerprojection_list(Request $request) {

    $isbn = $request->query('isbn');
    $basedocnum = $request->query('basedocnum');
    $pernrurl = $request->query('pernr');

    if($pernrurl == '1') {

        $pernr = filter_user_list()->pluck('PERNR')->toArray();;

    } else {
        $pernr[] = $pernrurl;
    }

    $_qstockallocated = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
                        ->where('t1.BASEDOCNUM',$basedocnum)
                        ->whereIn('t1.PERNR',$pernr)
                        ->where('t1.EAN11',$isbn)
                        ->whereNotNull('t1.APPROVED')
                        ->selectRaw("
                                 t1.*,
                                 CUSTOMERNAME,
                                 CUSTOMER
                                 
                        ")
                        ->orderByRaw('CUSTOMERNAME ASC');



    $num = 0;

    $qstockallocated = $_qstockallocated->get();

    if($qstockallocated->isEmpty()) {

        $response = [
            "num" => 0
        ];

    }
    else {

        foreach ($qstockallocated as $r){
            $num++;
            $isbn = $r->EAN11;
            $matnr = $r->MATNR;
            $customername = $r->CUSTOMERNAME;
            $customercode = $r->CUSTOMER;
            $pernr = $r->PERNR;
            $projtn = $r->QTY;

            $customer = $customercode . ' ' . $customername;
            $customerDisplay = '<span class="line-clamp-1" title="'.$customer.'">'.$customer.'</span>';

            $response[] = array (
                "num" => $num,
                "isbn" => $isbn,
                "pernr" => $pernr,
                "projection" => $projtn,
                "customer" => $customerDisplay,
        
            );


        }

    }
  
    
    
    return response()->json($response);
}
public function datatable_dashboard_bsoallocation_list(Request $request) {

    $isbn = $request->query('isbn');
    $basedocnum = $request->query('basedocnum');
    $pernrurl = $request->query('pernr');

    if($pernrurl == '1') {

        $pernr = filter_user_list()->pluck('PERNR')->toArray();;

    } else {
        $pernr[] = $pernrurl;
    }

    $_qstockallocated = OPTv2Allocated::from('OPTV2ALLOCATED as t1')
                        ->where('BASEDOCNUM',$basedocnum)
                        ->whereIn('PERNR',$pernr)
                        ->where('t1.EAN11',$isbn)
                        ->where('t1.PROJECTION','<>','0')
                        ->selectRaw("
                                 PERNR,
                                 MAX(t1.MATNR) as MATNR,
                                 MAX(t1.EAN11) as EAN11,
                                SUM(CAST(ALLOCATED AS INT)) as TOTALALLOCATED,
                                SUM(CAST(PROJECTION AS INT)) as TOTALPROJTN,
                                 (SELECT TOP 1 PERNRNAME FROM OPTV2PROJECTIONH t3 WHERE t3.PERNR = t1.PERNR ORDER BY id DESC ) as PERNRNAME
                                 
                        ")
                        ->groupBy('t1.PERNR')
                        ->orderByRaw('PERNRNAME ASC');



    $num = 0;

    $qstockallocated = $_qstockallocated->get();

    if($qstockallocated->isEmpty()) {

        $response = [
            "num" => 0
        ];

    }
    else {

        foreach ($qstockallocated as $r){
            $num++;
            $isbn = $r->EAN11;
            $matnr = $r->MATNR;
            $pernr = $r->PERNR;
            $pernrname = $r->PERNRNAME;
            $allocated = $r->TOTALALLOCATED;
            $projtn = $r->TOTALPROJTN;

            $allocrate = $allocrate = ($projtn > 0) 
                    ? round(($allocated / $projtn) * 100) 
                    : 0;;
           

            if ($allocrate <= 30) {
                $allocRateColor = 'text-warning-600';
            } 
            elseif ($allocrate <= 70) {
                $allocRateColor = 'text-warning-600'; // usually blue
            } 
            elseif ($allocrate <= 99) {
                $allocRateColor = 'text-info-600'; // usually blue
            } elseif ($allocrate >= 100) {
                $allocRateColor = 'text-success-600';
            } else {
                $allocRateColor = 'text-danger-600'; // fallback
            }

            $allocrateDisplay = '<span class="'.$allocRateColor.'">'.$allocrate.'%</span>';

            $pernrnameDisplay = '<span title="'.$pernrname.'" class="line-clamp-1">'. $pernr . ' ' . $pernrname.'</span>' ;
            $response[] = array (
                "num" => $num,
                "isbn" => $isbn,
                "allocation" => $allocated,
                "pernr" => $pernr,
                "pernrname" => $pernrnameDisplay,
                "projection" => $projtn,
                "allocrate" => $allocrateDisplay,
        
            );


        }

    }
  
    
    
    return response()->json($response);

}

public function datatable_reports_alloctransferconvertsummary (Request $request) {

    $basedocnum = $request->query('basedocnum');
    $pernr = $request->query('pernr');
      
   

    $num = 0;

        $qalloctransfer = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
        ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
        ->selectRaw("
            t1.id as MAXID,
            t1.DOCNUM as DOCNUM,
            t1.EAN11  as EAN11,
            t1.DESCRIPTION as DESCRIPTION,
            t1.QTY as QTY,
            t1.ALLOCTYPE as ALLOCTYPE,
            t1.PERNR as TOPERNR,
            t2.REQTONAME as FROMPERNRNAME,
            t2.REQTO as FROMPERNR,
            t1.TOALLOCTYPE as TOALLOCTYPE,
            t1.EAN11 as TEMP,
            t1.STATUS as STATUS,
            t1.BRANCHWHOUSE as BRANCHWHOUSE,
            t1.DATEAPPROVED as DATEAPPROVED,
            'Alloc. Req.' as TYPE
        ")
        ->where('t1.BASEDOCNUM',$basedocnum)
        // ->whereNotNull('t1.APPROVED')
        ;

        if($pernr !== '1') {
            $qalloctransfer->where('t1.PERNR',$pernr);
    
        } 
    

        $qconvert = OPTv2ConvertAllocd::from('OPTV2CONVERTALLOCD as t1')
        ->selectRaw("
            t1.id as MAXID,
            '-' as DOCNUM,
            t1.EAN11  as EAN11,
            t1.DESCRIPTION  as DESCRIPTION,
            t1.QTY  as QTY,
            CASE WHEN t1.TOCONVERTTYPE = 'nonbsa' THEN 'bsa' ELSE 'nonbsa' END as ALLOCTYPE,
            t1.PERNR as FROMPERNR,
            t1.PERNR as TOPERNR,
            '' as FROMTOPERNRNAME,
            t1.TOCONVERTTYPE as TOALLOCTYPE,
            t1.EAN11 as TEMP,
            t1.STATUS as STATUS,
            t1.BRANCHWHOUSE as BRANCHWHOUSE,
            t1.DATEAPPROVED as DATEAPPROVED,
            'Convert' as TYPE
        ")
        ->where('BASEDOCNUM',$basedocnum)
        // ->whereNotNull('APPROVED')
       
        ;

        if($pernr !== '1') {
            $qconvert->where('t1.PERNR',$pernr);
    
        } 
    
        
        // union all (Laravel handles it fine)
        $finalQuery = $qalloctransfer->unionAll($qconvert);
        $queryFinal = $finalQuery->orderBy('MAXID','DESC')->get();

        if($queryFinal->isEmpty()){

            $response = [
                'num' => 0
            ];
            
        } else {


            
            foreach ($queryFinal as $r) {
                $num++;
                $docnum = $r->DOCNUM;
                $isbn = $r->EAN11;
                $description = $r->DESCRIPTION;
                $type = $r->TYPE;
                $frompernr = $r->FROMPERNR;
                $topernr = $r->TOPERNR;
                $alloctype = $r->ALLOCTYPE;
                $toalloctype = $r->TOALLOCTYPE;
                $frompernrname = $r->FROMPERNRNAME;
                $branchwhouse = $r->BRANCHWHOUSE;
                $dateapproved = $r->DATEAPPROVED;
                $qty = $r->QTY;
                $status = $r->STATUS;
                $approved = $r->APPROVED;
                $datedisapproved = $r->DATEDISAPPROVED;
                $disapporvedby = $r->DISAPPROVEDBY;
                $remarksdisapproved = $r->REMARKSDISAPPROVED;
                $topernrlist[] = $topernr;
                $topernrlist[] = $frompernr;

         

                $alloctypeDisplay = '<span class="fs--2">' . acronymFullWord($alloctype) . ' <span class="fw-bold text-primary"> to </span> ' . acronymFullWord($toalloctype) . '</span>'; 
                $pernrDisplay = ''; 
                $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
                $typeDisplay = '<span title="'.$docnum.'"> '.$type.' </span>';
                $dateapprovedDisplay = '<span class="fs--2">' . formatDate($dateapproved,'y-m-d') .'</span>';

           
                $addtltext = '';
                $addtitle = '';

                if($approved == '1') {
                    $addtltext =  ': ' . $qty;
                }

    
                if(!is_null($remarksdisapproved)) {

                  $addtitle = formatDate($datedisapproved,'mdy') . ' &nbsp ' . $disapporvedby . ': ' . $remarksdisapproved   ;

                }
             
                $statusDisplay = status_display($status,'badge','0.64', $addtltext,$addtitle);

                $response[] = array (
                    "num" => $num,
                    "isbn" => $isbn,
                    "description" => $descriptionDisplay,
                    "frompernr" => $frompernr,
                    "topernr" => $topernr,
                    "frompernrname" => $frompernrname,
                    "alloctype" => $alloctypeDisplay,
                    "toalloctype" => $toalloctype,
                    "pernrDisplay" => $pernrDisplay,
                    "branchwhouse" => $branchwhouse,
                    "dateapproved" => $dateapprovedDisplay,
                    "qty" => $qty,
                    "status" => $statusDisplay,
                    "type" => $typeDisplay,

                );
                
            }

            $topernrdetails = OPTv2User::where('ACTIVE','1')->get();

            $toperd = [];
            foreach ($topernrdetails as $f){

                $toperd['NAME'][$f->PERNR] = $f->FULLNAME;

            }

            foreach ($response as &$res){

               
             
                $frompernr = $res['frompernr'] ;
                $topernr = $res['topernr'] ;

                $frompernrname =  $toperd['NAME'][$frompernr] ?? null ;
                $topernrname = $toperd['NAME'][$topernr] ?? null;

                $frompernrDisplay = $frompernr . ' ' . $frompernrname;
                $topernrDisplay = $topernr . ' ' . $topernrname;
    
                $pernrDisplay = $topernrDisplay;
                if(!empty($frompernrname)) {
                    $pernrDisplay = '<span class="fs--2">' . $frompernrDisplay . ' <span class="fw-bold text-primary"> to </span> ' . $topernrDisplay . '</span>';
              
                }
              

                $res['pernrDisplay'] = $pernrDisplay;
            }
           
            
        }

        return response()->json($response);


}
public function datatable_dashboard_projectionsummary_list (Request $request) {

    $basedocnum = $request->query('basedocnum');
    $pernrurl = $request->query('pernr');

    if($pernrurl == '1') {

        $pernr = filter_user_list()->pluck('PERNR')->toArray();;

    } else {
        $pernr[] = $pernrurl;
    }

    $qdashboardprojectionsummary =  OPTv2Allocated::selectRaw("
                MAX(EAN11) as EAN11,
                SUM(CAST(ALLOCATED AS INT)) as TOTALALLOCATED,
                SUM(CAST(PROJECTION AS INT)) as TOTALPROJTN
            ")
            ->where('BASEDOCNUM',$basedocnum)
            ->whereIn('PERNR',$pernr)
            ->groupBy('EAN11')
            ->get();

    $allocatedisbn = [];

    if($qdashboardprojectionsummary->isEmpty()) {

        $response = [ 
            "num" => 0
        ];

    

    } else {

        $num = 0;
        foreach ($qdashboardprojectionsummary as $r) {
         
            $isbn = $r->EAN11;
            $totalprojtn = $r->TOTALPROJTN;
            $totalallocated = $r->TOTALALLOCATED;
            
            $allocatedisbn[] = $isbn;

            // <i class="fs--2 far fa-eye text-primary"></i> 
            $totalallocatedDisplay = '<a ref="" title="Show Summary" class="dashboardbsoallocationbtn" data-isbn="'.$isbn.'" data-basedocnum="'.$basedocnum.'">' . number_format($totalallocated)  . '</a> ';
            $response [] = [
                "num" => $num,
                "isbn" => $isbn,
                "description" => '',
                "edition" => '',
                "descriptiontext" => '',
                "allocrate" => '',
                "alloctransferin" => '',
                "alloctransferout" => '',
                "ordered" => '',
                "allocbal" => '',
                "totalprojtndat" => $totalprojtn,
                "totalallocateddat" => $totalallocated,
                "totalprojtn" => number_format($totalprojtn),
                "totalallocated" => $totalallocatedDisplay,

            ];

            

        }
//DESCRIPTION-------------------

        $qmatdel = ZmmMatdel::select('*')
                ->whereIn('EAN11',$allocatedisbn)
                ->get();

        $descMap = [];
        foreach($qmatdel as $qmat) {

            $maktx = $qmat->MAKTX;
            $edition = $qmat->ZEIVR;
            $descMap['descdisplay'][$qmat->EAN11] = '<span class="line-clamp-1" title="'.$maktx.'">' . strtoupper($maktx) . '</span>';
            $descMap['desctext'][$qmat->EAN11] = $maktx;
            $descMap['edition'][$qmat->EAN11] = $edition ?? '-';
        }
         
 
 //-------------------DESCRIPTION  
 
 //alloc req in
        $allocInQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
        ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
        ->selectRaw("
            EAN11,
            SUM(CAST(QTY) AS INT) AS TOTALQTYREQIN
        ")
        ->whereIn('t1.PERNR', $pernr)
        ->where('t2.BASEDOCNUM', $basedocnum)
        ->where('t1.APPROVED','1')
        ->groupBy('t1.EAN11');

        
        $dballocInMap = [];
        foreach ($allocInQ as $d1) {
            $dballocInMap[$d1->EAN11] = $d1->TOTALQTYREQIN;
        }
 //---------alloc req in

//alloc req out
    $allocOutQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
    ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
    ->selectRaw("
        EAN11,
        SUM(CAST(QTY) AS INT) AS TOTALQTYREQOUT
    ")
    ->whereIn('t2.REQTO', $pernr)
    ->where('t2.BASEDOCNUM', $basedocnum)
    ->where('t1.APPROVED','1')
    ->groupBy('t1.EAN11');
    
    $dballocOutMap = [];
    foreach ($allocOutQ as $d2) {
        $dballocOutMap[$d1->EAN11] = $d2->TOTALQTYREQOUT;
    }
//---------alloc req out
 

    foreach($response as &$res){
        $num++;
        $isbn = $res['isbn'];
        $allocated = $res['totalallocateddat'] ;
        $projtn = $res['totalprojtndat'];

        $allocrate = 0;
        $allocin = $dballocInMap[$isbn] ?? 0;
        $allocout =  $dballocOutMap[$isbn] ?? 0;
        $ordered =   0;
        $allocrate = ($projtn > 0) 
                ? round(($allocated / $projtn) * 100) 
                : 0;
        $allocbal = $allocated + $allocin - $allocout - $ordered;

        if ($allocrate <= 30) {
            $allocRateColor = 'text-warning-600';
        } 
        elseif ($allocrate <= 70) {
            $allocRateColor = 'text-warning-600'; // usually blue
        } 
        elseif ($allocrate <= 99) {
            $allocRateColor = 'text-info-600'; // usually blue
        } elseif ($allocrate >= 100) {
            $allocRateColor = 'text-success-600';
        } else {
            $allocRateColor = 'text-danger-600'; // fallback
        }

        $desctext = isset($descMap['desctext'][ $res['isbn']]) ? $descMap['desctext'][ $res['isbn']] : '';
        $descdisplay = isset($descMap['descdisplay'][ $res['isbn']]) ?$descMap['descdisplay'][ $res['isbn']] : '';
        
        $allocrateDisplay = '<span class="'.$allocRateColor.'">'.$allocrate.'%</span>';
        $res['num'] = $num;
        $res['allocrate'] = $allocrateDisplay;
        $res['alloctransferin'] =  $allocin;
        $res['alloctransferout'] = $allocout;
        $res['ordered'] = $ordered;
        $res['descriptiontext'] = $desctext;
        $res['description'] = $descdisplay;
        $res['edition'] = $descMap['edition'][ $res['isbn']] ?? '-';

        $res['allocbal'] = $allocbal;
    } 
    unset($res);


      usort($response, function ($a, $b) {
            // Kapag int ordering:  $b['totalprojtn'] <=> $a['totalprojtn'];
            return strcmp($a['descriptiontext'] ?? '', $b['descriptiontext'] ?? '');

        });

    }

   

    return response()->json($response);



}


public function datatable_titleprojectionae_list (Request $request) {

    $basedocnum = $request->query('basedocnum');
    $isbn = $request->query('isbn');

    $qprojection = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
    ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
    ->selectRaw("
        t1.PERNR,
        MAX(t2.PERNRNAME) as PERNRNAME,
        SUM(CAST(t1.QTY AS INT)) as TOTALPROJTN
    ")
    ->where('t1.BASEDOCNUM', $basedocnum)
    ->where('t1.EAN11', $isbn)
    ->groupBy('t1.PERNR')
    ->orderByRaw('PERNRNAME ASC')
    ->get();

    if(!$qprojection->isEmpty()) {

        foreach ($qprojection as $risbnprojd) {

            $pernrname = $risbnprojd->PERNRNAME;
            $totalprojtn = $risbnprojd->TOTALPROJTN;


            $response [] = [
                "pernrname" => $pernrname,
                "totalproj" => $totalprojtn,

            ];

        }

    } else {

        $response = [ 
                "num" => 0
            ];

           
    }

   

    return response()->json($response);



}
public function datatable_reports_booktitleprojtnbreakdown(Request $request) {
    
        
    $basedocnum = $request->query('basedocnum');
    $pernr = $request->query('pernr');
    $division = $request->query('division');

    $num = 0;

    $_qprojectiond = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
    ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
    ->selectRaw("
        t1.EAN11,
        t2.BSA,
        MAX(t1.DESCRIPTION) as DESCRIPTION,
        SUM(CAST(t1.QTY AS INT)) as TOTALPROJTN,
        MAX(t2.PERNRNAME) as PERNRNAME
    ")
    ->where('t1.BASEDOCNUM', $basedocnum)
    ->orderByRaw('DESCRIPTION ASC')
    ->groupBy('t1.EAN11','t2.BSA')
    ;

    $_qprojectiondbreakdown = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
        ->selectRaw("
            t1.EAN11,
            t2.BSA,
            t1.PERNR,
            MAX(t2.PERNRNAME) as PERNRNAME,
            SUM(CAST(t1.QTY AS INT)) as TOTALPROJTN
        ")
        ->where('t1.BASEDOCNUM', $basedocnum)
        ->groupBy('t1.EAN11','t2.BSA','t1.PERNR')
        ;

    if($division !== '1') {
        
        $pernrList = OPTv2User::where(
                            DB::raw('UPPER(LTRIM(RTRIM(DIVISION)))'),
                        'LIKE',
                        '%'.strtoupper($division).'%')
                ->pluck('PERNR')
                ->toArray();
                ;
        $_qprojectiond->whereIn('t1.PERNR',$pernrList);
        $_qprojectiondbreakdown->whereIn('t1.PERNR',$pernrList);

    }

    $qprojectiond = $_qprojectiond->get();
    $qprojectiondbreakdown = $_qprojectiondbreakdown->get();
// group breakdown by EAN11 + BSA
$breakdownGrouped = $qprojectiondbreakdown->groupBy(function ($item) {
    return $item->EAN11 . '-' . $item->BSA;
});

$response = [];

if ($qprojectiond->isEmpty()) {
    $response = ["num" => 0];
} else {
    $num = 0;
    foreach ($qprojectiond as $risbnprojd) {
        $num++;
        $isbn = $risbnprojd->EAN11;
        $bsa = $risbnprojd->BSA;
        $description = $risbnprojd->DESCRIPTION;
        $totalprojtn = $risbnprojd->TOTALPROJTN;
        
        $descriptiontruncate = truncatelimitWords($description, 37);
        $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';

        $bsaDisplay = $bsa == '1' ? 'BSA' : 'Non-BSA';

        // get breakdown for this item
        $key = $isbn . '-' . $bsa;
        $breakdown = $breakdownGrouped->has($key) ? $breakdownGrouped[$key] : collect();

        $breakdownList = $breakdown->map(function ($b) {
            return $b['PERNRNAME'] . '<span class="text-warning fw-bold"> (' . $b['TOTALPROJTN']. ')</span>';
        })->implode(' | ');

        $response[] = [
            "num" => $num,
            "isbn" => $isbn,
            "totalprojtn" => $totalprojtn,
            "description" => $descriptionDisplay,
            "descriptiondisplay" => $descriptionDisplay,
            "bsadisplay" => $bsaDisplay,
            "breakdown" => $breakdownList,
        ];
    }
}




    return response()->json($response);

}


public function datatable_reports_projapprovalstatus(Request $request) {
    
        
    $basedocnum = $request->query('basedocnum');
    $pernr = $request->query('pernr');

    $pernrfilter = [];

    if($pernr !== '1') {
        $pernrfilter[] = $pernr;

    } else {
        $pernrfilter = filter_user_list()->pluck('PERNR')->toArray();
    }

    $num = 0;

    $qprojectiond = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                            ->leftJoin('OPTV2PROJECTIONH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
                            ->selectRaw("
                                t1.PERNR,

                                SUM(CAST(t1.PROJECTION AS INT)) as TOTALPROJTN,
                                SUM(CAST(t1.QTY AS INT)) as TOTALFINALPROJTN,

                                SUM(CASE WHEN t1.STATUS = 'returned_isbn' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_RETURNED,
                                SUM(CASE WHEN t1.STATUS = 'for_rsm_approval' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDINGRSM,
                                SUM(CASE WHEN t1.STATUS = 'for_ssm_approval' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDINGSSM,
                                SUM(CASE WHEN t1.STATUS != 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_PENDING,
                                SUM(CASE WHEN t1.STATUS = 'saved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS SAVED,
                                SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTAL_APPROVED,

                                SUM(CAST(t1.PROJECTION AS INT) * CAST(t1.UNITP AS DECIMAL(18,2))) AS TOTALPROJTNVALUE,
                                SUM(CASE WHEN t1.STATUS = 'returned_isbn' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_RETURNEDVALUE,
                                SUM(CASE WHEN t1.STATUS = 'for_rsm_approval' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_PENDINGRSMVALUE,
                                SUM(CASE WHEN t1.STATUS = 'for_ssm_approval' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_PENDINGSSMVALUE,
                                SUM(CASE WHEN t1.STATUS != 'approved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_PENDINGVALUE,
                                SUM(CASE WHEN t1.STATUS = 'saved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_SAVEDVALUE,
                                SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) * CAST(t1.UNITP AS DECIMAL(18,2)) ELSE 0 END) AS TOTAL_APPROVEDVALUE,

                                MAX(t2.PERNRNAME) as PERNRNAME
                            ")
                            // ->whereNotNull('t1.SUBMIT')
                            ->where('t1.BASEDOCNUM', $basedocnum)
                            ->whereIn('t1.PERNR', $pernrfilter)
                            ->groupBy('t1.PERNR');



        $queryprojectiond = $qprojectiond->get();


        if($queryprojectiond->isEmpty()) {
            $response = [
                "num" => 0
            ];
        }

        else {


//get RSM ----------------------------
            $pernrlist = [];
            foreach($queryprojectiond as $rgetpernr){

                $pernrlist[] = $rgetpernr->PERNR;

            }

            $qUserList = OPTv2User::whereIn('PERNR', $pernrlist)
                                    ->get(['PERNR', 'RSM']);

            $pernrToRSM = [];
            foreach ($qUserList as $u) {
                
                $pernrToRSM[trim($u->PERNR)] = trim($u->RSM);

            }
            
            $qUserRSMName = OPTv2User::whereIn('PERNR', $pernrToRSM)
                        ->where('ACTIVE','1')
                        ->get(['PERNR', 'FULLNAME']);
            $RSMtoName = [];
            foreach ($qUserRSMName as $r) {
                    $RSMtoName[trim($r->PERNR)] = $r->FULLNAME;

            }
// ---------------------------- get RSM

//per territory total (rsm)
$rsmTotals = [];

foreach ($queryprojectiond as $row) {
    $employeePernr = trim($row->PERNR);
    $rsmPernr = $pernrToRSM[$employeePernr] ?? null;

    if (!$rsmPernr) {
        continue;
    }

    if (!isset($rsmTotals[$rsmPernr])) {
        $rsmTotals[$rsmPernr] = [
            'totalprojtn' => 0,
            'totalprojtnvalue' => 0,
            'totalprojtnreturned' => 0,
            'totalprojtnpendingrsm' => 0,
            'totalprojtnpendingssm' => 0,
            'totalprojtnapproved' => 0,
        ];
    }

    $rsmTotals[$rsmPernr]['totalprojtn'] += (int) $row->TOTALPROJTN;
    $rsmTotals[$rsmPernr]['totalprojtnvalue'] += (float) $row->TOTALPROJTNVALUE;
    $rsmTotals[$rsmPernr]['totalprojtnreturned'] += (int) $row->TOTAL_RETURNED;
    $rsmTotals[$rsmPernr]['totalprojtnpendingrsm'] += (int) $row->TOTAL_PENDINGRSM;
    $rsmTotals[$rsmPernr]['totalprojtnpendingssm'] += (int) $row->TOTAL_PENDINGSSM;
    $rsmTotals[$rsmPernr]['totalprojtnapproved'] += (int) $row->TOTAL_APPROVED;
}
//---------------
            foreach($queryprojectiond as $risbnprojd){

                $num++;
                $pernr = trim($risbnprojd->PERNR);
                $pernrname = $risbnprojd->PERNRNAME;
                $totalprojtn = $risbnprojd->TOTALPROJTN;
                $totalfinalprojtn = $risbnprojd->TOTALFINALPROJTN;
                $totalprojtnpending = $risbnprojd->TOTAL_PENDING;
                $totalprojtnreturned = $risbnprojd->TOTAL_RETURNED;
                $totalprojtnpendingrsm = $risbnprojd->TOTAL_PENDINGRSM;
                $totalprojtnpendingssm = $risbnprojd->TOTAL_PENDINGSSM;
                $totalprojtnapproved = $risbnprojd->TOTAL_APPROVED;

                $totalprojtnvalue = $risbnprojd->TOTALPROJTNVALUE;
                $totalreturnedvalue = $risbnprojd->TOTAL_RETURNEDVALUE;
                $totalpendingrsmvalue = $risbnprojd->TOTAL_PENDINGRSMVALUE;
                $totalpendingssmvalue = $risbnprojd->TOTAL_PENDINGSSMVALUE;
                $totalpendingvalue = $risbnprojd->TOTAL_PENDINGVALUE;
                $totalapprovedvalue = $risbnprojd->TOTAL_APPROVEDVALUE;
                $totalsavedvalue = $risbnprojd->TOTAL_SAVEDVALUE;

                $totalprojtnvalueDisplay = number_format($totalprojtnvalue);
                $totalreturnedvalueDisplay = number_format($totalreturnedvalue);
                $totalpendingrsmvalueDisplay = number_format($totalpendingrsmvalue);
                $totalpendingssmvalueDisplay = number_format($totalpendingssmvalue);
                $totalpendingvalueDisplay = number_format($totalpendingvalue);
                $totalapprovedvalueDisplay = number_format($totalapprovedvalue);
                $totalsavedvalueDisplay = number_format($totalsavedvalue);
             
                $pernrnameDisplay = '<span class="text-start px-2 line-clamp-1" title="'.$pernrname.'"> '.$pernrname.' </span>';
                    // 🔹 hanapin PERNR ng RSM gamit employee PERNR
                $rsmPernr = $pernrToRSM[$pernr] ?? null;

                // 🔹 hanapin FULLNAME ng RSM gamit RSM PERNR
                $pernrrsmname = $rsmPernr && isset($RSMtoName[$rsmPernr])
                    ? $RSMtoName[$rsmPernr]
                    : '';
                    
                $pernrrsmamount = $rsmPernr ? ($rsmTotals[$rsmPernr]['totalprojtn'] ?? 0) : 0;
                $pernrrsmamountvalue = $rsmPernr ? ($rsmTotals[$rsmPernr]['totalprojtnvalue'] ?? 0) : 0;
                $pernrrsmamountvalueDisplay = number_format($pernrrsmamountvalue);
        
                
                // ✅ Compute completed count (lahat ng hindi pending)
                // $completed = $totalprojtn - ($totalprojtnreturned  + $totalprojtnpendingrsm + $totalprojtnpendingssm);
                $completed = $totalfinalprojtn - ($totalprojtnpending);
                // ✅ Compute percent completed (avoid divide by zero)
                // $percentCompleted = $totalprojtn > 0 ? round(($completed / $totalprojtn) * 100) : 0;

                $percentCompleted = $totalfinalprojtn > 0 ? round(($completed / $totalfinalprojtn) * 100) : 0 ?? 0;

                //Kung talagang may konti pa na pending qty dapat d yan mag 100%
                if ($totalprojtnpending > 0 && $percentCompleted >= 100) {
                    $percentCompleted = 99;
                }

                $statusbadgeDisplay =    $statusDisplay = status_display('pending','badge','0.7');
                if ($percentCompleted <= 30) {
                    $percentCompletedColor = 'bg-warning';
                } 
                elseif ($percentCompleted <= 70) {
                    $percentCompletedColor = 'bg-warning'; // usually blue
                } 
                elseif ($percentCompleted <= 99) {
                    $percentCompletedColor = 'bg-info'; // usually blue
                } elseif ($percentCompleted >= 100) {
                    $percentCompletedColor = 'bg-success';
                    $statusbadgeDisplay =    $statusDisplay = status_display('approved','badge','0.7');
                } else {
                    $percentCompletedColor = 'bg-danger'; // fallback
                }


                // <span class="'.$percentCompletedColor.' fw-bold">' .$percentCompleted . '%' .'</span>
                $percentCompletedDisplay = '
             
                    <span class="" hidden>'.$percentCompleted.'% </span>
                     <div class="progress" style="height:15px">
                        <div class="progress-bar fw-bold fs--2 '.$percentCompletedColor.' rounded-3" role="progressbar" style="width: '.$percentCompleted.'%" aria-valuenow="'.$totalprojtnapproved.'" aria-valuemin="0" aria-valuemax="'.$totalprojtn.'">'.$percentCompleted.'%</div>
                      </div>
                ';
                
             
                // $percentCompletedDisplay = $percentCompleted;

                // $totalreturnedvalueDisplay = 
                // $totalpendingrsmvalueDisplay 
                // $totalpendingssmvalueDisplay 
                // $totalpendingvalueDisplay = n
                // $totalapprovedvalueDisplay = 

                
                $pernrrsmnameDisplay = ' <div class="d-flex justify-content-between "> • ' . $pernrrsmname . '  <span class="text-primary w-75 px-7 fw-bold"> ' .$pernrrsmamountvalueDisplay . '</span></div>';
                  
                    $response[] = array(
                        "num" => $num,
                        "pernr" => $pernr,
                        "pernrnamedata" => $pernrname,
                        "pernrname" => $pernrnameDisplay,
                        "pernrrsmname" => $pernrrsmnameDisplay,

                        "pernrrsmamount" => $pernrrsmamount,
                        "pernrrsmamountvalue" => $pernrrsmamountvalueDisplay,

                        "totalprojtn" => $totalprojtn,
                         "totalprojtnreturned" => $totalprojtnreturned,
                         "totalprojtnpendingrsm" => $totalprojtnpendingrsm,
                         "totalprojtnpendingssm" => $totalprojtnpendingssm,
                         "totalprojtnapproved" => $totalprojtnapproved,
                         
                         "totalprojtnvalue" => $totalprojtnvalueDisplay,
                         "totalreturnedvalue" => $totalreturnedvalueDisplay,
                         "totalpendingrsmvalue" => $totalpendingrsmvalueDisplay,
                         "totalpendingssmvalue" => $totalpendingssmvalueDisplay,
                         "totalpendingvalue" => $totalpendingvalueDisplay,
                         "totalapprovedvalue" => $totalapprovedvalueDisplay,
                         "totalsavedvalue" => $totalsavedvalueDisplay,
                         "statusbadge" => $statusbadgeDisplay,

                         "completed" => $completed,
                         "percent_completed" => $percentCompletedDisplay
                    
                       
                        
                    );
            
            }

            // SORT Array by RSM Name (ascending)
            usort($response, function ($a, $b) {
                // Kapag int ordering:  $b['totalprojtn'] <=> $a['totalprojtn'];
                return strcmp($b['pernrrsmname'] , $a['pernrrsmname']);
            });

        }




    return response()->json($response);

}

public function datatable_reports_projprogress(Request $request) {
    
        
    $basedocnum = $request->query('basedocnum');
    $_pernr = $request->query('pernr');

    if($_pernr == '1') {   

        $pernr = filter_user_list()->pluck('PERNR')->toArray();
    } else {
     
        $pernr[] = trim($_pernr);
    }

    $num = 0;

    $readonly = '';
    $hidden  = '';
    $uncl =  '';

    $customercode = '';
    $qprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
                ->select('t1.*','CUSTOMER','CUSTOMERNAME','BSA')
                ->where('t1.BASEDOCNUM',$basedocnum)
                ->whereIn('t1.PERNR',$pernr)
                ->orderBy('CUSTOMERNAME','ASC')
                ;

     

        $queryprojectiond = $qprojectiond->get();


        if($queryprojectiond->isEmpty()) {
            $response = [
                "num" => 0
            ];
        }

        else {
            foreach($queryprojectiond as $risbnprojd){

                $num++;
                $id = $risbnprojd->id;
                $isbn = $risbnprojd->EAN11;
    
                $title = $risbnprojd->DESCRIPTION;
                $customername = $risbnprojd->CUSTOMERNAME;
                $qty = $risbnprojd->QTY;
                $disc = $risbnprojd->DISC;
                $isbnunitprice = $risbnprojd->UNITP;
                $population = $risbnprojd->POPULATION;
                $projection = $risbnprojd->PROJECTION;
                $linetotal = $risbnprojd->LINETOTAL;
                $customercode = $risbnprojd->CUSTOMER;
                $status = $risbnprojd->STATUS;
                $approved = $risbnprojd->APPROVED;
    
                $rsmqty = $risbnprojd->QTYAPPROVED1;
                $rsmdateapproved = $risbnprojd->DATEAPPROVED1;
                $ssmqty = $risbnprojd->QTYAPPROVED2;
                $ssmdateapproved = $risbnprojd->DATEAPPROVED2;
                $_datesubmit = $risbnprojd->DATESUBMIT;
                
                $datesubmit = formatDate($_datesubmit,'mdy');

                $isbnlist[] = $isbn;
                $isbnunitpriceclean = str_replace(',','',$isbnunitprice);
    
                $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                                <span class="visually-hidden">Loading...</span>
    
                            </div>';
                            
                $linetotaleDisplay = number_format($linetotal);

                // $titletruncate = truncatelimitWords($title,27);
                $titletruncate = $title;
                $titleDisplay = '<span class="line-clamp-1" title="'.$title.'"> '.$titletruncate.' </span>';
          
                // $customernametruncate = truncatelimitWords($customername,27);
                $customernametruncate = $customername;
                $customernameDisplay = '<span class="line-clamp-1 " title="'.$customername.'"> • '.$customercode.' &nbsp '.$customernametruncate.' </span>';

                $qtyFinal = number_format($qty);
                $isbnunitpriceDisplay = number_format($isbnunitprice);
                $bsastatus = $risbnprojd->BSA;
                $addtltext = '';

                if($approved == '1') {
                    $addtltext =  ': ' . $qty;
                }
                $statusDisplay = status_display($status,'badge','0.7', $addtltext);
    
                    $response[] = array(
                        "num" => $num,
                        "customername" => $customernameDisplay,
                        "qty" => $qty,
                        "projection" => $projection,
                        "population" => $population,
                        "rsmqty" => $rsmqty,
                        "ssmqty" => $ssmqty,
                        "rsmdateapproved" => $rsmdateapproved,
                        "ssmdateapproved" => $ssmdateapproved,
                        "datesubmit" => $datesubmit,
                        "isbn" => $isbn,
                        "description" => $titleDisplay,
                        "status" => $statusDisplay,
                       
                        
                    );
            
        }
         
       


        }




    return response()->json($response);

}

public function datatable_for_approval_projection_final_customer_isbn_list(Request $request) {
    
        
    $user_staff = session('user_staff');
    $pernr = session('pernr');


    $prevyear = getPreviousYear(1);
    $prevyear2 = getPreviousYear(2);
    $prevyear3 = getPreviousYear(3);

    $docnum = $request->query('docnum');
    $basedocnum = $request->query('basedocnum');
    $username = $request->query('name');

    $qusernameDetails = userNameDetails($username);
    $usernamePERNR = $qusernameDetails->PERNR;

    $querycheck = OPTv2Projectionh::where('USERNAME',  $user_staff)
                            ->where('BASEDOCNUM',$basedocnum)
                            ->limit(1)
                            ->get();

    $num = 0;

    $readonly = '';
    $hidden  = '';
    $uncl =  '';

    $isbnlist = [];
    $qprojectiondetails = projectionDetails($basedocnum,$username);
    $customercode = '';
    $qprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
                ->select('t1.*','CUSTOMER','BSA')
                ->whereNotNull('t1.SUBMIT')
                ->whereNotNull('t1.APPROVED1')
                ->whereNotNull('t1.QTY')
                ->whereNull('t1.APPROVED2')
                ->where('t1.DOCNUM',$docnum);



        $queryprojectiond = $qprojectiond->get();


         
        foreach($queryprojectiond as $risbnprojd){

            $id = $risbnprojd->id;
            $isbn = $risbnprojd->EAN11;

            $title = $risbnprojd->DESCRIPTION;
            $qty = $risbnprojd->QTY;
            $disc = $risbnprojd->DISC;
            $isbnunitprice = $risbnprojd->UNITP;
            $population = $risbnprojd->POPULATION;
            $projection = $risbnprojd->PROJECTION;
            $linetotal = $risbnprojd->LINETOTAL;
            $customercode = $risbnprojd->CUSTOMER;
            $remarks = $risbnprojd->REMARKS;
            $aepernr = $risbnprojd->PERNR;
                
            $remarksDisplay = '';
            $borderDisplay = '';
            if(!is_null($remarks)) {

                $remarksDisplay = 'title="Exceeded: '.$remarks.'"';
                $borderDisplay = 'border border-warning';
            }
            
            $isbnlist[] = $isbn;
            $isbnunitpriceclean = str_replace(',','',$isbnunitprice);

            $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                            <span class="visually-hidden">Loading...</span>

                        </div>';
                        
            $linetotaleDisplay = number_format($linetotal);
            $titleDisplay = truncatelimitWords($title,27);
            $qtyFinal = number_format($qty);
            $isbnunitpriceDisplay = number_format($isbnunitprice);
            $bsastatus = $risbnprojd->BSA;

          
                
                $tr ='
             
                <tr class="for_approval_projection_final_edit_isbn_row '.$docnum. $isbn . 'finaleditisbnrow">
                    <td class="d-none"> 
                          <div class="form-check d-flex justify-content-center">
                                <input class="form-check-input for_approval_projection_final_edit_isbn_approve_check '.$docnum. $isbn . 'checkfinal" data-docnum="'.$docnum.'" data-customercode="'.$customercode.'" data-isbn="'.$isbn.'"  id="flexCheckChecked" type="checkbox" value="">
                            </div>
                    <a class="disaprv-projtn d-none" data-customercode="'.$customercode.'" '.$hidden.' data-isbn="'.$isbn.'">
                        <span class="text-danger fa-2x fas fa-times-circle"></span>
                        <input class="form-control text-center d-none un-cl form-control-sm for_approval_projection_final_edit_isbn_unitp" type="number" value="'.$isbnunitpriceclean.'" min="1">
                        <input class="form-control un-cl d-none text-center form-control-sm for_approval_projection_final_edit_isbn_prev1_sales '.$docnum.''.$isbn.'totalprev1value"" type="number" value="" min="1">
                        <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_final_edit_isbn_linetotal '.$customercode.'linetotal '.$docnum.''.$isbn.'linetotal" type="number" value="" min="1">
                        <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_final_edit_isbn_customercode" type="text" value="'.$customercode.'">
                    </a></td>
                    <td class="text-center">'.$isbn.'</td>
                    <td class="text-center" title="'.$title.'">'.$titleDisplay.'</td>
                    <td>₱<span class="for_approval_projection_final_edit_linetotal_amount_display '.$docnum.''.$isbn.'linetotaltext">0</span></td>
                    <td>₱'.$isbnunitpriceDisplay.'</td>
                    <td><input class="form-control text-center p-1 for_approval_projection_final_edit_population_qty un-cl" type="number" value="'.$population.'" min="1"></td>
            
                    <td>
                        <div class="rounded '.$borderDisplay.'" '.$remarksDisplay.'>
                            <input class="form-control text-center p-1 for_approval_projection_final_edit_projtn_qty '.$customercode.'projtn un-cl" type="number" data-aepernr="'.$aepernr.'" data-customercode="'.$customercode.'" data-isbn="'.$isbn.'" value="'.$qty.'" min="1">
                        </div>
                    </td>
                    <td><input class="form-control text-center p-1  for_approval_projection_final_edit_approve_qty '.$uncl.' '.$docnum.''.$isbn.'editapproveqty" '.$readonly.' type="number" data-aepernr="'.$aepernr.'" data-docnum="'.$docnum.'"  data-customercode="'.$customercode.'" data-isbn="'.$isbn.'" data-aeprojtn="'.$projection.'" min="1"></td>
                   
                  <td><span class="totalbudget_display '.$customercode.''.$isbn.'totalbudget">0</span></td>
                    <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev1">0</span></td>
                    <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev2">0</span></td>
                    <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev3">0</span></td>
                    <td>
                        <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm border dropdown-toggle dropdown-caret-none p-1 mx-2 transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                        <a href="#"  class="dropdown-item text-success forfinalapproval_approve_isbn" data-id="'.$id.'" data-docnum="'.$docnum.'" data-docnum="'.$isbn.'" data-docnum="'.$customercode.'" data-title="'.$title.'" href="#!">Approve</a>
                                        <a href="#"  class="dropdown-item text-danger forfinalapproval_return_isbn" data-id="'.$id.'" data-docnum="'.$docnum.'" data-docnum="'.$isbn.'" data-docnum="'.$customercode.'" data-title="'.$title.'" href="#!">Return</a>
                                        <!-- <div class="dropdown-divider"></div> -->
                    
                                    </div>
                        </div>
                    </td>
                    <td class="d-none">    
                        <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_final_edit_isbn" type="text" value="'.$isbn.'">
                        <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_final_edit_isbn_title" type="text" value="'.$title.'">
                        <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_final_edit_isbn_disc" type="text" value="'.$disc.'">
                    </td>
                    
                </tr>';

                $response['customerisbnlist'][] = array(
                    "num" => $num,
                    "tr" => $tr,
                    "bsastatus" => $bsastatus,
                    "isbn" => $isbn
                    
                );


        }

//isbn sales history
                $pernrCustomerIsbnPrev3YearSalesHistory = pernrCustomerIsbnPrev3YearSalesHistory($customercode,$isbnlist,$usernamePERNR);
                
                if($pernrCustomerIsbnPrev3YearSalesHistory->isEmpty()){
                        $response['customerisbnsaleshistorylist'][] = array(
                            "num" => $isbnlist
                            
                        );
                }
                else {
                    foreach ($pernrCustomerIsbnPrev3YearSalesHistory as $aaa){

                        $isbnhistory = $aaa->isbn;
                        $totalPrev1 = $aaa->total_1 ?? 0;
                        $totalPrev2 = $aaa->total_2 ?? 0;
                        $totalPrev3 = $aaa->total_3 ?? 0;

                        $totalPrev1Display = number_format($totalPrev1);
                        $totalPrev2Display = number_format($totalPrev2);
                        $totalPrev3Display = number_format($totalPrev3);

                        $response['customerisbnsaleshistorylist'][] = array(
                            "customercode" => $customercode,
                            "isbn" => $isbnhistory,
                            "total_1" => $totalPrev1Display,
                            "total_1Value" => $totalPrev1,
                            "total_2" => $totalPrev2Display,
                            "total_3" => $totalPrev3Display
                            
                        );

                    }

                }
//------



//get items in budgeting system                                         
    $customerBudgetTitlesThisYear = customerTitleBudgetThisYear($customercode,$usernamePERNR);
                
                
    if($customerBudgetTitlesThisYear->isEmpty()){


        $response['customerisbnbudgetqty'][] = array(
            "customercode" => '',
            "isbn" => '',
            "qty" => '',
            
        );
    } else {

        foreach($customerBudgetTitlesThisYear as $risbn){

            $isbn = $risbn->isbn;
            $qty = $risbn->qty;
            $qtyFinal = number_format($qty);

                
            $response['customerisbnbudgetqty'][] = array(
                "customercode" => $customercode,
                "isbn" => $isbn,
                "qty" => $qtyFinal,
                
            );
        } 


    }
//------------



    return response()->json($response);

}

public function datatable_for_approval_projection_customer_list(Request $request) {
    
    $basedocnum = $request->query('basedocnum');
    $username = $request->query('username');

    $pernr = session('pernr');
    $user_staff = session('user_staff');

    $thisyear = getPreviousYear(0);
    $prevyear = getPreviousYear(1);
    $prevyear2 = getPreviousYear(2);
    $prevyear3 = getPreviousYear(3);
    $date_now = date_now('dateonly');
    $qusernameDetails = userNameDetails($username);
    $usernamePERNR = $qusernameDetails->PERNR;

    $docnumISBSubmittedForApproval = [];
    
    
//get all customer's submitted isbn 
                                    
    $qprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
    ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
    ->select('t1.*','CUSTOMER')
    ->whereNotNull('t1.SUBMIT')
    ->whereNull('t1.APPROVED1')
    ->where('t1.USERNAME',  $username)
    ->where('t1.BASEDOCNUM',$basedocnum);



        $queryprojectiond = $qprojectiond->get();

            if(!$queryprojectiond->isEmpty()) {

                foreach ($queryprojectiond as $rpd) {
                        $docnum =  $rpd->DOCNUM;
                        $customercode =  $rpd->CUSTOMER;
                        $branchwhouse = $rpd->BRANCHWHOUSE;
                        $isbn = $rpd->EAN11;
                        $isbn_title = $rpd->DESCRIPTION;
                        $isbn_unitp = $rpd->UNITP;
                        $disc = $rpd->DISC;
                        $population = $rpd->POPULATION;
                        $qty = $rpd->QTY;
                        $linetotal = $rpd->LINETOTAL;
                        $docnumISBSubmittedForApproval[] = $docnum;
                        
                        $response['projectiond'][] = array(
                            "customercode" => $customercode,
                            "docnum" => $docnum,
                            "branchwhouse" => $branchwhouse,
                            "isbn" => $isbn,
                            "isbn_title" => $isbn_title,
                            "isbn_unitp" => $isbn_unitp,
                            "disc" => $disc,
                            "population" => $population,
                            "qty" => $qty,
                            "linetotal" => $linetotal,
                    );

                }
            

        }

        //--------
      
        $querycheck = OPTv2Projectionh::selectRaw("
                                    MAX(DOCNUM) as DOCNUM,
                                    MAX(CUSTOMERNAME) as CUSTOMERNAME,
                                    MAX(CUSTOMER) as CUSTOMER,
                                    MAX(MOTHERACT) as MOTHERACT,
                                    MAX(DEPARTMENT) as DEPARTMENT
                                ")
                                ->whereIn('DOCNUM',$docnumISBSubmittedForApproval)
                                ->groupBy('CUSTOMER')
                                ->orderBy('CUSTOMERNAME','ASC')
                                ;


    $qprojectiondetails = projectionDetails($basedocnum,$username);

    $qprojection = $querycheck->get();

    $qomsh =  
    $numtitles = [];
    $kunnr = [];
    $qtyordered = [];
    $sbrancwhouse = [];


//get customers from projectionh if already saved

    if($qprojection->isEmpty() ) {
        $response['customerlist'][] = array(
            "systemstatus" => '2-2',
        );
    }else {

        $num = 0;
        
        foreach ($qprojection as $r){
            
            $num++;
            $docnum = $r->DOCNUM;
            $customername = $r->CUSTOMERNAME;
            $customercode = $r->CUSTOMER;
            $motheracct = $r->MOTHERACT;
            $department = $r->DEPARTMENT;
            $numtitles = 0;
            
            $kunnr[] = $customercode;

            $qtyorderedFinal =  0;
            $branchwhouseFinal = 0;
            $qtyorderedDisplay = number_format($qtyorderedFinal);

  
            $projtn = 0;
            
            $departmentDisplay = trim($department) !== '' ? '- ' . $department : '';

        
            $doctotalDisplay = '₱<span class="'.$customercode.'doctotal">0</span>     <input type="number" class="d-none form-control un-cl form-control-sm customersdoctotalvalue '.$customercode.'doctotalvalue" readonly="readonly">';
            $projtnDisplay = '<span class="'.$customercode.'projtntotal">0</span>  <input type="number" class="d-none form-control un-cl form-control-sm projtntotalvalue '.$customercode.'projtntotalvalue" readonly="readonly">';
          $numtitlesDisplay = '';

            $customernamedepartment = $customername . $departmentDisplay;
            $customernametruncate = truncatelimitWords($customernamedepartment,36);
       
            $customernameDisplay = '
            
            <a class="for_approval_projection_isbn_list_display" data-docnum="'.$docnum.'" data-customername="'.$customernamedepartment.'" data-bs-toggle="collapse" href="#collapseExample'.$customercode.'" data-customercode="'.$customercode.'" role="button" title="'.$customernamedepartment.' '.$customercode.'" aria-expanded="true" aria-controls="collapseExample1">
                            <span class="line-clamp-1"> '.$customercode.' '.$customernamedepartment.'</span> 
                            

                        </a>
            
            ';


            //select type only
                    $activeWarehouses = activeWarehouses();
                    $activeBranches = activeBranches();
                    
                    $ab = '';
                    $aw = '';
                
                    // dd($branchwhouseFinal);

                    foreach($activeBranches as $a => $b){

                        $selected1 = $a === $branchwhouseFinal ? 'selected' : '';
                        $ab.= '
                                <option value="'.$a.'" '.$selected1.'>'.$b.'</option>
                        ';
                    }
                
                                        
                    foreach($activeWarehouses as $c => $d){
                        
                
                        $selected2 = $c === $branchwhouseFinal ? 'selected' : '';
                        $aw.= '
                                <option value="'.$c.' '.$selected2.'">'.$d.'</option>
                        ';
                    }
            //------

            
            // <div class="" style="height:60vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">

            $isbnTableList = '

            
                <tr class="collapse fade" id="collapseExample'.$customercode.'" style="">
                    
                            
                            <td class="pt-0 pb-2 px-1" colspan="11">    
                                    
                                
                                    <table class="fs--1 table border  mt-2 border-400 table-responsive table-striped title-table table-responsive text-center '.$customercode.'isbntable">
                                        <thead class="sticky-top">
                                         
                                            <tr>
                                                <th scope="col" class="text-center" width="5%">Remove </th>
                                                <th scope="col" class="text-center" width="12%">ISBN </th>
                                                <th scope="col" class="text-center" width="28%">Title Name </th>
                                                <th scope="col" class="text-center" width="8%">Amount </th>
                                                <th scope="col" class="text-center" width="8%">Unit </br> Price </th>
                                                <th scope="col" class="text-center" width="8%" title="Population">Popup.</th>
                                                <th scope="col" class="text-center" width="8%">Projtn</th>
                                                <th scope="col" class="text-center" width="8%">Approve</th>
                                                <th scope="col" class="text-center" width="8%">Budget</th>
                                        
                                                <th scope="col" class="text-center" width="7%">2024 </th>
                                                <th scope="col" class="text-center" width="7%">2023 </th>
                                                <th scope="col" class="text-center" width="7%">2022 </th>
                                            </tr>
                                        </thead>
                                            

                                        <tbody>';                     

                $isbnTableList .='     </tbody>
                                </table>
                            </td>
                        </tr>
        
            ';
            

            $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                    <span class="visually-hidden">Loading...</span>

                </div>';
          
            $totalPrev1Display = '<a class="saleshistorycanvas" data-year="'.$prevyear.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev1">0</span></a>';
            $totalPrev2Display = '<a class="saleshistorycanvas" data-year="'.$prevyear2.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev2">0</span></a>';
            $totalPrev3Display = '<a class="saleshistorycanvas" data-year="'.$prevyear3.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev3">0</span></a>';
            $budgetqtyDisplay = '<span class="totalprev_display '.$customercode.'budgetqty">0</span>';

            $checkcustomerisbn = '
                        <div class="d-flex justify-content-center">
                                <input class="form-check-input for_approval_projection_edit_customerisbn_approve_check" data-docnum="'.$docnum.'" data-customercode="'.$customercode.'" type="checkbox" value="">
                        </div>
            ';
            $response['customerlist'][] = array(
                "num" => $num,
                "numtitles" => $numtitlesDisplay,
                "customername" => $customernameDisplay,
                "customercode" => $customercode,
                "projection" => $projtnDisplay,
                "motheracct" => $motheracct,
                "department" => $department,
                "amount" => $doctotalDisplay,
                "thisyearprojtn" => 0,
                "budget" => $budgetqtyDisplay,
                "isbnTableList" => $isbnTableList,
                "saleshistoryprev1" => $totalPrev1Display,
                "saleshistoryprev2" => $totalPrev2Display,
                "saleshistoryprev3" => $totalPrev3Display,
                "systemstatus" => '2-1',
                "checkcustomerisbn" => $checkcustomerisbn,
                
            );
        }

                $customersaleshistory = customerPrev3YearSalesHistory($kunnr);
        
                foreach($customersaleshistory as $custr) {
        
                
                    $customercode = $custr->cust_code;
                    $total_1 = $custr->total_1;
                    $total_2 = $custr->total_2;
                    $total_3 = $custr->total_3;
                
                    $total_1Display = number_format($total_1);
                    $total_2Display = number_format($total_2);
                    $total_3Display = number_format($total_3);
        
                    $response['cshlist'][] = array(
                        "customercode" => $customercode,
                        "total_1" => $total_1Display,
                        "total_2" => $total_2Display,
                        "total_3" => $total_3Display,
                    );
        
            }

//get budget per customer

            $qbudget = TblBudget::from('tbl_budget as t1')
            ->where('t1.ae_code',$usernamePERNR)
            ->where('t1.period','LIKE','%'.$thisyear.'%')
            ->whereIN('t1.cust_code',$kunnr)
            ->where('t1.isbn','!=','')
            ->groupBy('t1.cust_code')
            ->select('t1.cust_code',
                            DB::raw("
                                COUNT(DISTINCT t1.isbn) as CNTISBN,
                                MAX(t1.price) as UNITPRICE,
                                MAX(t1.disc) as DISC,
                                SUM(t1.qty) as QTYORDERED,
                                SUM(CAST(REPLACE(t1.value, ',', '') AS DECIMAL(15,2))) AS DOCTOTAL
                                
                                ")
                    )
            ->get()
            // ->limit(10)
        
        ;

        if($qbudget->isEmpty()){
            $response['budgetlist'][] = array(
                    "customercode" => 0,
                    "budgetqty" => 0,
            );

        } else{

            foreach ($qbudget as $rbudget){
                $rbudgetkunnr = $rbudget->cust_code;

                $response['budgetlist'][] = array(
                        "customercode" => $rbudgetkunnr,
                        "budgetqty" => $rbudget->QTYORDERED,
                );

            }

        }     

        $qprojectiondapprove =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
        ->whereNotNull('t1.APPROVED')
        ->whereIn('t2.CUSTOMER',$kunnr)
        ->where('t1.USERNAME',  $username)
        ->where('t2.DOCDATE','LIKE','%'.$thisyear.'%'  )
        ->groupBy('t2.CUSTOMER')
        ->selectRaw("
                t2.CUSTOMER,
                SUM(CAST(t1.QTY AS INT)) as THISYEARPROJTN
        ")
        ->get();

        if(!$qprojectiondapprove->isEmpty()){

            $thisyearprojMap = [];

            foreach($qprojectiondapprove as $prapr){
                $thisyearprojMap[$prapr->CUSTOMER] = $prapr->THISYEARPROJTN;
            }

            foreach($response['customerlist'] as &$custrow) {
                $custcode = $custrow['customercode'];
                if(!empty($custcode)) {

                    $custrow['thisyearprojtn'] = $thisyearprojMap[$custcode] ?? 0;

                }
            }

      
        }
        
//-----------



        }

        return response()->json($response);

}
    
    public function datatable_for_approval_projection_final_customer_list(Request $request) {
    
        $basedocnum = $request->query('basedocnum');
        $username = $request->query('username');

        $pernr = session('pernr');
        $user_staff = session('user_staff');

        $thisyear = getPreviousYear(0);
        $prevyear = getPreviousYear(1);
        $prevyear2 = getPreviousYear(2);
        $prevyear3 = getPreviousYear(3);
        $date_now = date_now('dateonly');
        $qusernameDetails = userNameDetails($username);
        $usernamePERNR = $qusernameDetails->PERNR;

        $docnumISBNForApprovalFinal = [];

                      
 //get all customer's submitted isbn 
                                                    
            $qprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
            ->select('t1.*','CUSTOMER')
            ->whereNotNull('t1.APPROVED1')
            ->whereNotNull('t1.QTY')
            ->whereNull('t1.APPROVED2')
            ->whereNull('t1.APPROVED')

            ->where('t1.USERNAME',  $username)
            ->where('t1.BASEDOCNUM',$basedocnum);



                $queryprojectiond = $qprojectiond->get();

                    if(!$queryprojectiond->isEmpty()) {

                        foreach ($queryprojectiond as $rpd) {

                         
                                $docnum =  $rpd->DOCNUM;

                                $docnumISBNForApprovalFinal[] = $docnum;
                                $customercode =  $rpd->CUSTOMER;
                                $branchwhouse = $rpd->BRANCHWHOUSE;
                                $isbn = $rpd->EAN11;
                                $isbn_title = $rpd->DESCRIPTION;
                                $isbn_unitp = $rpd->UNITP;
                                $disc = $rpd->DISC;
                                $population = $rpd->POPULATION;
                                $qty = $rpd->QTY;
                                $linetotal = $rpd->LINETOTAL;

                                $linetotal = $qty * $isbn_unitp;
                                $docnumISBNNotForApprovalFinal[] = $docnum;
                                
                                $response['projectiond'][] = array(
                                    "customercode" => $customercode,
                                    "docnum" => $docnum,
                                    "branchwhouse" => $branchwhouse,
                                    "isbn" => $isbn,
                                    "isbn_title" => $isbn_title,
                                    "isbn_unitp" => $isbn_unitp,
                                    "disc" => $disc,
                                    "population" => $population,
                                    "qty" => $qty,
                                    "linetotal" => $linetotal,
                            );

                        }
                    

                }

                //--------

            $qomsh =  
            $numtitles = [];
            $kunnr = [];
            $qtyordered = [];
            $sbrancwhouse = [];
        

//get customers from projectionh if already saved

        $querycheck = OPTv2Projectionh::selectRaw("
                MAX(DOCNUM) as DOCNUM,
                MAX(CUSTOMERNAME) as CUSTOMERNAME,
                MAX(CUSTOMER) as CUSTOMER,
                MAX(MOTHERACT) as MOTHERACT,
                MAX(DEPARTMENT) as DEPARTMENT
                ")
                ->whereIn('DOCNUM',$docnumISBNForApprovalFinal)
                ->groupBy('CUSTOMER')
                ->orderBy('CUSTOMERNAME','ASC')
                ;


        $qprojectiondetails = projectionDetails($basedocnum,$username);
        $cntapprover = $qprojectiondetails ? $qprojectiondetails->CNTAPPROVER2 : '';


        $qprojection = $querycheck->get();

        if($qprojection->isEmpty() ) {
            $response['customerlist'][] = array(
            "systemstatus" => '2-2',
        );
        }else {
            
  

            $num = 0;
            
            foreach ($qprojection as $r){
                
                $num++;
                $docnum = $r->DOCNUM;
                $customername = $r->CUSTOMERNAME;
                $customercode = $r->CUSTOMER;
                $motheracct = $r->MOTHERACT;
                $department = $r->DEPARTMENT;
                $numtitles = 0;
                
                $kunnr[] = $customercode;

                $qtyorderedFinal =  0;
                $branchwhouseFinal = 0;
                $qtyorderedDisplay = number_format($qtyorderedFinal);

      
                $projtn = 0;
                
                $departmentDisplay = trim($department) !== '' ? '- ' . $department : '';

            
                $doctotalDisplay = '₱<span class="'.$customercode.'doctotal">0</span>     <input type="number" class="d-none form-control un-cl form-control-sm customersdoctotalvalue '.$customercode.'doctotalvalue" readonly="readonly">';
                $projtnDisplay = '<span class="'.$customercode.'projtntotal">'. $projtn . '</span>  <input type="number" class="d-none form-control un-cl form-control-sm projtntotalvalue '.$customercode.'projtntotalvalue" readonly="readonly">';
              $numtitlesDisplay = '';

                $customernamedepartment = $customername . $departmentDisplay;
                $customernametruncate = truncatelimitWords($customernamedepartment,36);
           
                $customernameDisplay = '
                
                <a class="for_approval_projection_final_isbn_list_display" data-docnum="'.$docnum.'" data-bs-toggle="collapse" href="#collapseExample'.$customercode.'" data-customercode="'.$customercode.'" role="button" data-customername="'.$customernamedepartment.'" title="'.$customernamedepartment.' '.$customercode.'" aria-expanded="true" aria-controls="collapseExample1">
                                 <span class="line-clamp-1"> '.$customercode.' '.$customernamedepartment.'</span> 
                                

                            </a>
                
                ';

    
                //select type only
                        $activeWarehouses = activeWarehouses();
                        $activeBranches = activeBranches();
                        
                        $ab = '';
                        $aw = '';
                    
                        // dd($branchwhouseFinal);

                        foreach($activeBranches as $a => $b){

                            $selected1 = $a === $branchwhouseFinal ? 'selected' : '';
                            $ab.= '
                                    <option value="'.$a.'" '.$selected1.'>'.$b.'</option>
                            ';
                        }
                    
                                            
                        foreach($activeWarehouses as $c => $d){
                            
                    
                            $selected2 = $c === $branchwhouseFinal ? 'selected' : '';
                            $aw.= '
                                    <option value="'.$c.' '.$selected2.'">'.$d.'</option>
                            ';
                        }
                //------

           
                

                $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>

                    </div>';
                $totalPrev1Display = '<a class="saleshistorycanvas" data-year="'.$prevyear.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev1">0</span></a>';
                $totalPrev2Display = '<a class="saleshistorycanvas" data-year="'.$prevyear2.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev2">0</span></a>';
                $totalPrev3Display = '<a class="saleshistorycanvas" data-year="'.$prevyear3.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev3">0</span></a>';
                $budgetqtyDisplay = '<span class="totalprev_display '.$customercode.'budgetqty">0</span>';

                $checkfinalcustomerisbn = '
                <div class="d-flex justify-content-center">
                        <input class="form-check-input for_approval_projection_final_edit_customerisbn_approve_check" data-docnum="'.$docnum.'" data-customercode="'.$customercode.'" type="checkbox" value="">
                </div>
    ';

                $response['customerlist'][] = array(
                    "num" => $num,
                    "numtitles" => $numtitlesDisplay,
                    "customername" => $customernameDisplay,
                    "customercode" => $customercode,
                    "projection" => $projtnDisplay,
                    "motheracct" => $motheracct,
                    "department" => $department,
                    "thisyearprojtn" => 0,
                    "amount" => $doctotalDisplay,
                    "budget" => $budgetqtyDisplay,
                    "saleshistoryprev1" => $totalPrev1Display,
                    "saleshistoryprev2" => $totalPrev2Display,
                    "saleshistoryprev3" => $totalPrev3Display,
                    "systemstatus" => '2-1',
                    "checkfinalcustomerisbn" => $checkfinalcustomerisbn,
                    
                    
                );
            }

                    $customersaleshistory = customerPrev3YearSalesHistory($kunnr);
            
                    foreach($customersaleshistory as $custr) {
            
                    
                        $customercode = $custr->cust_code;
                        $total_1 = $custr->total_1;
                        $total_2 = $custr->total_2;
                        $total_3 = $custr->total_3;
                    
                        $total_1Display = number_format($total_1);
                        $total_2Display = number_format($total_2);
                        $total_3Display = number_format($total_3);
            
                        $response['cshlist'][] = array(
                            "customercode" => $customercode,
                            "total_1" => $total_1Display,
                            "total_2" => $total_2Display,
                            "total_3" => $total_3Display,
                        );
            
                }

//get budget per customer

                $qbudget = TblBudget::from('tbl_budget as t1')
                ->where('t1.ae_code',$usernamePERNR)
                ->where('t1.period','LIKE','%'.$thisyear.'%')
                ->whereIN('t1.cust_code',$kunnr)
                ->where('t1.isbn','!=','')
                ->groupBy('t1.cust_code')
                ->select('t1.cust_code',
                                DB::raw("
                                    COUNT(DISTINCT t1.isbn) as CNTISBN,
                                    MAX(t1.price) as UNITPRICE,
                                    MAX(t1.disc) as DISC,
                                    SUM(t1.qty) as QTYORDERED,
                                    SUM(CAST(REPLACE(t1.value, ',', '') AS DECIMAL(15,2))) AS DOCTOTAL
                                    
                                    ")
                        )
                ->get()
                // ->limit(10)
            
            ;

            if($qbudget->isEmpty()){
                $response['budgetlist'][] = array(
                        "customercode" => 0,
                        "budgetqty" => 0,
                );

            } else{

                foreach ($qbudget as $rbudget){
                    $rbudgetkunnr = $rbudget->cust_code;
    
                    $response['budgetlist'][] = array(
                            "customercode" => $rbudgetkunnr,
                            "budgetqty" => $rbudget->QTYORDERED,
                    );
    
                }

            }     

            $qprojectiondapprove =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
            ->whereNotNull('t1.APPROVED')
            ->whereIn('t2.CUSTOMER',$kunnr)
            ->where('t1.USERNAME',  $username)
            ->where('t2.DOCDATE','LIKE','%'.$thisyear.'%'  )
            ->groupBy('t2.CUSTOMER')
            ->selectRaw("
                    t2.CUSTOMER,
                    SUM(CAST(t1.QTY AS INT)) as THISYEARPROJTN
            ")
            ->get();

            if(!$qprojectiondapprove->isEmpty()){

                $thisyearprojMap = [];

                foreach($qprojectiondapprove as $prapr){
                    $thisyearprojMap[$prapr->CUSTOMER] = $prapr->THISYEARPROJTN;
                }

                foreach($response['customerlist'] as &$custrow) {
                    $custcode = $custrow['customercode'];
                    if(!empty($custcode)) {

                        $custrow['thisyearprojtn'] = $thisyearprojMap[$custcode] ?? 0;

                    }
                }

          
            }

//-----------

        }

            return response()->json($response);
    
}

public function datatable_for_approval_projection_customer_isbn_list(Request $request) {
    
        
        $user_staff = session('user_staff');
        $pernr = session('pernr');

  
        $prevyear = getPreviousYear(1);
        $prevyear2 = getPreviousYear(2);
        $prevyear3 = getPreviousYear(3);

        $basedocnum = $request->query('basedocnum');
        $username = $request->query('name');
        $docnum = $request->query('docnum');
        $customercode = '';
        $qusernameDetails = userNameDetails($username);
        $usernamePERNR = $qusernameDetails->PERNR;


        $num = 0;

        $qprojectiondetails = projectionDetails($basedocnum,$username);
        $cntapprover = $qprojectiondetails ? $qprojectiondetails->CNTAPPROVER1 : '';

        
        $readonly = '';
        $hidden  = '';
         $uncl =  '';

         $isbnlist = [];

           $qprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
                ->select('t1.*','CUSTOMER','BSA')
                ->whereNotNull('t1.SUBMIT')
                ->whereNull('t1.APPROVED1')
                ->where('t1.DOCNUM',$docnum);


            $queryprojectiond = $qprojectiond->get();


            if($queryprojectiond->isEmpty()) {

                $response = [
                    "num" => 0,

                ];

            }

            else {
                            foreach($queryprojectiond as $risbnprojd){

                                $id = $risbnprojd->id;
                                $docnum = $risbnprojd->DOCNUM;
                                $isbn = $risbnprojd->EAN11;
                
                                $title = $risbnprojd->DESCRIPTION;
                                $qty = $risbnprojd->QTYAPPROVED1;
                                $disc = $risbnprojd->DISC;
                                $isbnunitprice = $risbnprojd->UNITP;
                                $population = $risbnprojd->POPULATION;
                                $projection = $risbnprojd->PROJECTION;
                                $linetotal = $risbnprojd->LINETOTAL;
                                $customercode = $risbnprojd->CUSTOMER;
                                $status = $risbnprojd->STATUS;
                                $bsastatus = $risbnprojd->BSA;
                                $remarks = $risbnprojd->REMARKS;
                                $aepernr = $risbnprojd->PERNR;

                                $remarksDisplay = '';
                                $borderDisplay = '';
                                if(!is_null($remarks)) {

                                    $remarksDisplay = 'title="Exceeded: '.$remarks.'"';
                                    $borderDisplay = 'border border-warning';
                                }
                                $isbnlist[] = $isbn;
                                $isbnunitpriceclean = str_replace(',','',$isbnunitprice);
                
                                $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                                                <span class="visually-hidden">Loading...</span>
                
                                            </div>';
                                            
                                $linetotaleDisplay = number_format($linetotal);
                                $titleDisplay = '<span class="line-clamp-1" title="'.$title.'"> '.$title.'</span> ';
                                $qtyFinal = number_format($qty);
                                $isbnunitpriceDisplay = number_format($isbnunitprice);
                
                            
                                    
                                    $tr ='
                                    <tr class="for_approval_projection_edit_isbn_row '.$docnum. $isbn . 'editisbnrow">
                                        <td class="d-none"> 
                
                                            <div class="form-check d-none un-cl d-flex justify-content-center">
                                                <input class="form-check-input for_approval_projection_edit_isbn_approve_check '.$docnum. $isbn . 'check" data-docnum="'.$docnum.'" data-customercode="'.$customercode.'" data-isbn="'.$isbn.'"  id="flexCheckChecked" type="checkbox" value="">
                                            </div>
                                            <a class="remove-projtn-approval" data-customercode="'.$docnum.'" '.$hidden.' data-docnum="'.$docnum.'" data-isbn="'.$isbn.'">
                                                <span class="text-danger d-none fa-2x fas fa-times-circle"></span>
                                            
                                                <input class="form-control text-center d-none un-cl form-control-sm for_approval_projection_edit_isbn_unitp" type="number" value="'.$isbnunitpriceclean.'" min="1">
                                                <input class="form-control un-cl d-none text-center form-control-sm for_approval_projection_edit_isbn_prev1_sales '.$docnum.''.$isbn.'totalprev1value"" type="number" value="" min="1">
                                                <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_edit_isbn_linetotal '.$docnum.'linetotal '.$docnum.''.$isbn.'linetotal" type="number" value="" min="1">
                                                <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_edit_isbn_customercode" type="text" value="'.$docnum.'">
                                                <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_edit_isbn_docnum" type="text" value="'.$docnum.'">
                                            </a>
                                        </td>
                                        <td class="text-center">'.$isbn.'</td>
                                        <td class="text-center" title="'.$title.'">'.$titleDisplay.'</td>
                                        <td>₱<span class="for_approval_projection_edit_linetotal_amount_display '.$docnum.''.$isbn.'linetotaltext">0</span></td>
                                        <td>₱'.$isbnunitpriceDisplay.'</td>
                                        <td><input class="form-control text-center p-1 for_approval_projection_edit_population_qty un-cl" type="number" value="'.$population.'" min="1"></td>
                                
                                        <td>
                                            <div class="rounded '.$borderDisplay.'" '.$remarksDisplay.'>
                                             <input class="form-control text-center p-1 for_approval_projection_edit_projtn_qty '.$docnum.'projtn un-cl" title="hey" type="number" data-customercode="'.$docnum.'" data-isbn="'.$isbn.'" value="'.$projection.'" min="1">
                                            </div>
                                        </td>
                                        <td><input class="form-control text-center p-1 for_approval_projection_edit_projtn_rsm_qty '.$uncl.' '.$docnum. $isbn . 'editrsmqty '.$status.'"  '.$readonly.'  type="number" data-aepernr="'.$aepernr.'" data-docnum="'.$docnum.'" data-customercode="'.$customercode.'" data-isbn="'.$isbn.'" value="0" data-aeprojtn="'.$projection.'" min="1"></td>
                                    
                                    <td><span class="totalbudget_display '.$customercode.''.$isbn.'totalbudget">0</span></td>
                                        <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev1">0</span></td>
                                        <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev2">0</span></td>
                                        <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev3">0</span></td>
                                        <td>
                                            <div class="font-sans-serif btn-reveal-trigger position-static">
                                                <button class="btn btn-sm border dropdown-toggle dropdown-caret-none p-1 mx-2 transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2">
                                                            <a href="#"  class="dropdown-item text-success forapproval_approve_isbn" data-id="'.$id.'" data-docnum="'.$docnum.'" data-docnum="'.$isbn.'" data-docnum="'.$customercode.'" data-title="'.$title.'" href="#!">Approve</a>
                                                            <a href="#"  class="dropdown-item text-danger forapproval_return_isbn" data-id="'.$id.'" data-docnum="'.$docnum.'" data-docnum="'.$isbn.'" data-docnum="'.$customercode.'" data-title="'.$title.'" href="#!">Return</a>
                                                            <!-- <div class="dropdown-divider"></div> -->
                                        
                                                        </div>
                                            </div>
                                        </td>
                                        <td class="d-none">    
                                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_edit_isbn" type="text" value="'.$isbn.'">
                                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_edit_isbn_title" type="text" value="'.$title.'">
                                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_edit_isbn_disc" type="text" value="'.$disc.'">
                                        </td>
                                        
                                    </tr>';
                
                                    $response['customerisbnlist'][] = array(
                                        "num" => $num,
                                        "tr" => $tr,
                                        "bsastatus" => $bsastatus,
                                        "isbn" => $isbn
                                        
                                    );
                
                
                            }
                
                //isbn sales history
                                    $pernrCustomerIsbnPrev3YearSalesHistory = pernrCustomerIsbnPrev3YearSalesHistory($customercode,$isbnlist,$usernamePERNR);
                                    
                                    if($pernrCustomerIsbnPrev3YearSalesHistory->isEmpty()){
                                            $response['customerisbnsaleshistorylist'][] = array(
                                                "num" => $isbnlist
                                                
                                            );
                                    }
                                    else {
                                        foreach ($pernrCustomerIsbnPrev3YearSalesHistory as $aaa){
                
                                            $isbnhistory = $aaa->isbn;
                                            $totalPrev1 = $aaa->total_1 ?? 0;
                                            $totalPrev2 = $aaa->total_2 ?? 0;
                                            $totalPrev3 = $aaa->total_3 ?? 0;
                
                                            $totalPrev1Display = number_format($totalPrev1);
                                            $totalPrev2Display = number_format($totalPrev2);
                                            $totalPrev3Display = number_format($totalPrev3);
                
                                            $response['customerisbnsaleshistorylist'][] = array(
                                                "customercode" => $customercode,
                                                "isbn" => $isbnhistory,
                                                "total_1" => $totalPrev1Display,
                                                "total_1Value" => $totalPrev1,
                                                "total_2" => $totalPrev2Display,
                                                "total_3" => $totalPrev3Display
                                                
                                            );
                    
                                        }
                
                                    }
                //------
                
                
                
                //get items in budgeting system                                         
                        $customerBudgetTitlesThisYear = customerTitleBudgetThisYear($customercode,$usernamePERNR);
                                    
                                    
                        if($customerBudgetTitlesThisYear->isEmpty()){
                
                
                            $response['customerisbnbudgetqty'][] = array(
                                "customercode" => '',
                                "isbn" => '',
                                "qty" => '',
                                
                            );
                        } else {
                
                            foreach($customerBudgetTitlesThisYear as $risbn){
                
                                $isbn = $risbn->isbn;
                                $qty = $risbn->qty;
                                $qtyFinal = number_format($qty);
                
                                    
                                $response['customerisbnbudgetqty'][] = array(
                                    "customercode" => $customercode,
                                    "isbn" => $isbn,
                                    "qty" => $qtyFinal,
                                    
                                );
                            } 
                
                
                        }
                //------------


            }
             

   
        return response()->json($response);

    }


    public function datatable_create_projection_customer_isbn_list(Request $request) {
        
        $basedocnum = $request->query('basedocnum');
        $user_staff = session('user_staff');
        $pernr = session('pernr');
        $prevyear = getPreviousYear(1);
        $customercode = $request->query('customercode');

        $qprojectionperiod = projection_period_details($basedocnum);
        $projectionperiodStatus = $qprojectionperiod->STATUS ?? '';
        $num = 0;

        $isbnlist = [];


//get items in projectiond if already saved
            $queryprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->where('USERNAME',  $user_staff)
                                            ->whereRaw("(SELECT CUSTOMER FROM OPTV2PROJECTIONH t2 WHERE t2.DOCNUM = t1.DOCNUM) = ?", [$customercode])
                                            ->where('BASEDOCNUM',$basedocnum)
                                            // ->whereNull('SUBMIT')
                                            // ->whereNull('APPROVED1')
                                            // ->whereNull('APPROVED')
                                            ->orderBy('DESCRIPTION','ASC')
                                            ->get();
             
            $bsastatus = pernr_customer_check_bsa_status ($customercode);
                                            
            foreach($queryprojectiond as $risbnprojd){

                $isbn = $risbnprojd->EAN11;

                $title = $risbnprojd->DESCRIPTION;
                $qty = $risbnprojd->PROJECTION;
                $disc = $risbnprojd->DISC;
                $isbnunitprice = $risbnprojd->UNITP ?: 0;
                $population = $risbnprojd->POPULATION;
                $projection = $risbnprojd->PROJECTION;
                $linetotal = $risbnprojd->LINETOTAL;
                $branchwhouse = $risbnprojd->BRANCHWHOUSE;

                $isbnlist[] = $isbn;
                $isbnunitpriceclean = str_replace(',','',$isbnunitprice);

                $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                                <span class="visually-hidden">Loading...</span>

                            </div>';
                            
                $linetotaleDisplay = number_format($linetotal);
                $titleDisplay = '<span class="line-clamp-1" title="'.$title.'"> '.$title.'</span> ' ;
                $qtyFinal = number_format($qty);
                $isbnunitpriceDisplay = number_format($isbnunitprice);
              
                    
                $response['customerisbnlist'][] = [
                    "num"                  => '1',
                    "customercode"         => $customercode,
                    "isbn"                 => $isbn,
                    "title"                => $title,
                    "titleDisplay"         => $titleDisplay,
                    "isbnunitpriceclean"   => $isbnunitpriceclean,
                    "isbnunitpriceDisplay" => $isbnunitpriceDisplay,
                    "population"           => $population,
                    "disc"                 => $disc,
                    "isbnbudget"                 => '',
                    "isbnprevtotal1"                 => '',
                    "isbnprevtotal2"                 => '',
                    "isbnprevtotal3"                 => '',
                    "isbnremarks"          => '', 
                ];


            }
//----------------

            
 //get items in budgeting system                                         
 $customerBudgetTitlesThisYear = customerTitleBudgetThisYear($customercode);
              
            


     foreach($customerBudgetTitlesThisYear as $risbn){

            $num++;

             $isbn = $risbn->isbn;
             
             $title = $risbn->titlename;
             $qty = $risbn->qty;
             $disc = $risbn->disc;
             $qgetISBNDetails = getISBNDetails($isbn);
             $isbnunitprice = $qgetISBNDetails->KBETRCE ?? 0;
            //  $isbnunitprice = $risbn->price ?: 0 ;
             $population = $risbn->population;
             $projection = $risbn->qty;
             $linetotal = $risbn->net_price;

             $isbnunitpriceclean = str_replace(',','',$isbnunitprice);

             $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                             <span class="visually-hidden">Loading...</span>

                         </div>';
                         
            $titleDisplay = '<span class="line-clamp-1" title="'.$title.'"> '.$title.'</span> ' ;
             $qtyFinal = number_format($qty);
             $isbnunitpriceDisplay = $isbnunitprice;
             // if($isbn !== ''){
                 
             $response['customerisbnbudgetqty'][] = array(
                "customercode" => $customercode,
                "isbn" => $isbn,
                "qty" => $qtyFinal,
                
            );

       

//if existing in projectiond then dont populate isbn           
             if (in_array($isbn, $isbnlist)) {
                continue; // Skip this iteration
            }

            if($projectionperiodStatus === '1') {

                $isbnlist[] = $isbn;


                $response['customerisbnlist'][] = [
                     "num"                  => '1',
                     "customercode"         => $customercode,
                     "isbn"                 => $isbn,
                     "title"                => $title,
                     "titleDisplay"         => $titleDisplay,
                     "isbnunitpriceclean"   => $isbnunitpriceclean,
                     "isbnunitpriceDisplay" => $isbnunitpriceDisplay,
                     "population"           => $population,
                     "disc"                 => $disc,
                     "isbnbudget"           => '',
                     "isbnprevtotal1"       => '',
                     "isbnprevtotal2"       => '',
                     "isbnprevtotal3"       => '',
                     "isbnremarks"          => '', 
                 ];
    
            }

 

             // }

         } 
//-----------    

            
//isbn sales history

                    $pernrCustomerIsbnPrev3YearSalesHistory = pernrCustomerIsbnPrev3YearSalesHistory($customercode,$isbnlist);
                    
                    
                    if($pernrCustomerIsbnPrev3YearSalesHistory->isEmpty()){
                            $response['customerisbnsaleshistorylist'][] = array(
                                "num" => $isbnlist,
                                "bsastatus" => $bsastatus,
                                
                                
                            );
                    }
                    else {
                        foreach ($pernrCustomerIsbnPrev3YearSalesHistory as $aaa){

                            $isbnhistory = $aaa->isbn;
                            $totalPrev1 = $aaa->total_1 ?? 0;
                            $totalPrev2 = $aaa->total_2 ?? 0;
                            $totalPrev3 = $aaa->total_3 ?? 0;

                            $totalPrev1Display = number_format($totalPrev1);
                            $totalPrev2Display = number_format($totalPrev2);
                            $totalPrev3Display = number_format($totalPrev3);

                            $response['customerisbnsaleshistorylist'][] = array(
                                "customercode" => $customercode,
                                "isbn" => $isbnhistory,
                                "total_1" => $totalPrev1Display,
                                "total_1Value" => $totalPrev1,
                                "total_2" => $totalPrev2Display,
                                "bsastatus" => $bsastatus,
                                "total_3" => $totalPrev3Display
                                
                            );
    
                        }

                    }
//------

  

   
        return response()->json($response);


    }
    
    public function submit_create_projection_add_new_customer(Request $request) {

        $kunnr = $request->input('customercode');

        $prevyear = getPreviousYear(1);
        $prevyear2 = getPreviousYear(2);
        $prevyear3 = getPreviousYear(3);
        
        $r = CrmMotherLookup::where('CUSTNO',$kunnr)
        ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
        ->orderByRaw('CUSTNAME','ASC')
        ->selectRaw('MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, MAX(RTRIM(CUSTNO)) as CUSTNO, MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, MAX(AE) as AE, MAX(RTRIM(DEPARTMENT)) as DEPARTMENT')
        ->groupBy('t2.CUSTNO')
        // ->limit(1)
        ->first();

        $num = '-';

                $customername = $r->CUSTNAME;
                $customernameraw = $r->CUSTNAME;
                $customercode = $r->CUSTNO;
                $motheracct = $r->MOTHERACCT;
                $department = $r->DEPARTMENT;
                $numtitles = 0;

                $qtyorderedFinal =  0;
                $branchwhouseFinal =  '';
                $qtyorderedDisplay = 0;

                $projtn = 0;

                $departmentDisplay = trim($department) !== '' ? '- ' . $department : '';
                ;

                $doctotalDisplay = '₱<span class="'.$customercode.'doctotal">0</span>     <input type="number" class="d-none form-control un-cl form-control-sm customersdoctotalvalue '.$customercode.'doctotalvalue" readonly="readonly">';
                $projtnDisplay = '<span class="'.$customercode.'projtntotal">'. $projtn . '</span>  <input type="number" class="d-none form-control un-cl form-control-sm projtntotalvalue '.$customercode.'projtntotalvalue" readonly="readonly">';

                $numtitlesDisplay = '';
                $customernamedepartment = $customername . $departmentDisplay;
                $customernametruncate = truncatelimitWords($customernamedepartment,36);
      

                $customernameDisplay = '

                <a class="create_projection_isbn_list_display" data-customercode="'.$customercode.'" data-customername="'.$customernamedepartment.'" role="button" title="'.$customernamedepartment.' '.$customercode.'" aria-expanded="true" aria-controls="collapseExample1">
                                '.$customernametruncate.' &nbsp&nbsp '.$customercode.' 
                                

                            </a>

                ';


                //select type only
                        $activeWarehouses = activeWarehouses();
                        $activeBranches = activeBranches();
                        
                        $ab = '';
                        $aw = '';
                    
                        // dd($branchwhouseFinal);

                        foreach($activeBranches as $a => $b){

                            $selected1 = $a === $branchwhouseFinal ? 'selected' : '';
                            $ab.= '
                                    <option value="'.$a.'" '.$selected1.'>'.$b.'</option>
                            ';
                        }
                    
                                            
                        foreach($activeWarehouses as $c => $d){
                            
                    
                            $selected2 = $c === $branchwhouseFinal ? 'selected' : '';
                            $aw.= '
                                    <option value="'.$c.' '.$selected2.'">'.$d.'</option>
                            ';
                        }
                //------


                // <div class="" style="height:60vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">

                $isbnTableList = '


                    <tr class="collapse fade" id="collapseExample'.$customercode.'" style="">
                        
                                
                                <td class="pt-0 pb-2 px-1" colspan="11">    
                                        
                                    
                                        <table class="fs--1 table  mt-2 border-400 table-responsive table-striped title-table table-responsive text-center '.$customercode.'isbntable">
                                            <thead class="sticky-top">
                                                <tr role="row">
                                                    <th colspan="3" class="bg-white text-start" rowspan="1"> 
                                                             <div class="input-group border border-300">
                                                                <span class="input-group-text" id="basic-addon1">
                                                                    + New Title
                                                                </span>
                                                                 <input class="form-control text-center form-control-sm w-50 create_projection_search_title" data-customercode="'.$customercode.'" name="" type="text" placeholder="Search...">

                                                            </div>

                                                            

                                                </th>
                                                    <th colspan="2" class="bg-white  text-center" rowspan="1"> 
                                                                &nbsp
                                                    </th>

                                                    <th colspan="7" class="bg-white  text-center" rowspan="1"> 
                                                      
                                                            <div class="input-group border border-300">
                                                                <span class="input-group-text" id="basic-addon1">
                                                                    Branch/Whouse
                                                                </span>
                                                                <select name="create_projection_branchwhouse_id[]" id="" class="form-control create_projection_branchwhouse_id '.$customercode.'branchwhouse form-control-sm">
                                                                    <option value="" selected>Choose for: '.$customername.'</option>

                                                                    <optgroup label="Branches" class="branchesopt">
                                                                        '.$ab.'
                                                                    </optgroup>


                                                                    <optgroup label="Warehouses" class="whouseopt">
                                                                        '.$aw.'
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                
                                                    </th>
                                                
                                                </tr>
                                                <tr>
                                                    <th scope="col" class="text-center" width="2%">Remove</th>
                                                    <th scope="col" class="text-center" width="12%">ISBN </th>
                                                    <th scope="col" class="text-center" width="28%">Title Name </th>
                                                    <th scope="col" class="text-center" width="8%">Amount </th>
                                                    <th scope="col" class="text-center" width="8%">Unit </br> Price </th>
                                                    <th scope="col" class="text-center" width="8%" title="Population">Popup.</th>
                                                    <th scope="col" class="text-center" width="8%">Projtn</th>
                                                    <th scope="col" class="text-center" width="8%">Budget</th>
                                            
                                                    <th scope="col" class="text-center" width="7%">2024 </th>
                                                    <th scope="col" class="text-center" width="7%">2023 </th>
                                                    <th scope="col" class="text-center" width="7%">2022 </th>
                                                </tr>
                                            </thead>
                                                

                                            <tbody>';                     

                    $isbnTableList .='     </tbody>
                                    </table>
                                </td>
                            </tr>

                ';


                $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>

                    </div>';
         
                $response['customerlist'][] = array(
                    "num" => $num,
                    "numtitles" => $numtitlesDisplay,
                    "customernameraw" => $customernameraw,
                    "customername" => $customernameDisplay,
                    "customercode" => $customercode,
                    "projection" => $projtnDisplay,
                    "motheracct" => $motheracct,
                    "department" => $department,
                    "amount" => $doctotalDisplay,
                    "budget" => $qtyorderedDisplay,
                    "isbnTableList" => $isbnTableList,
                    "prevyear" => $prevyear,
                    "prevyear2" => $prevyear2,
                    "prevyear3" => $prevyear3,
                    "systemstatus" => '2-1',
                    
                );

                return response()->json($response);
 

    }


            
    
    public function datatable_view_projection_progress_customer_isbn_list(Request $request) {
        
        $user_staff = session('user_staff');
        $pernr = session('pernr');

  
        $prevyear = getPreviousYear(1);

        $basedocnum = $request->query('basedocnum');
        $username = $request->query('name');
        $customercode = $request->query('customercode');

        $qusernameDetails = userNameDetails($username);
        $usernamePERNR = $qusernameDetails->PERNR;

        $querycheck = OPTv2Projectionh::where('USERNAME',  $user_staff)
                                ->where('BASEDOCNUM',$basedocnum)
                                ->limit(1)
                                ->get();

        $num = 0;

        $qprojectiondetails = projectionDetails($basedocnum,$username);
        $cntapprover = $qprojectiondetails ? $qprojectiondetails->CNTAPPROVER1 : '';


           $isbnlist = [];

            $qprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->where('USERNAME',  $username)
                                            ->whereRaw("(SELECT CUSTOMER FROM OPTV2PROJECTIONH t2 WHERE t2.DOCNUM = t1.DOCNUM) = ?", [$customercode])
                                            ->where('BASEDOCNUM',$basedocnum)
                                            ->orderBy('DESCRIPTION','ASC')
                                            ;


            $queryprojectiond = $qprojectiond->get();


             
            foreach($queryprojectiond as $risbnprojd){

                $isbn = $risbnprojd->EAN11;

                $title = $risbnprojd->DESCRIPTION;
                $qty = $risbnprojd->QTY;
                $disc = $risbnprojd->DISC;
                $isbnunitprice = $risbnprojd->UNITP;
                $population = $risbnprojd->POPULATION;
                $projection = $risbnprojd->PROJECTION;
                $rsmqty = $risbnprojd->QTYAPPROVED1 ?? 0;
                $ssmqty = $risbnprojd->QTYAPPROVED2 ?? 0; 
                $linetotal = $risbnprojd->LINETOTAL;

                $isbnlist[] = $isbn;
                $isbnunitpriceclean = str_replace(',','',$isbnunitprice);

                $spinsm = '<div class="spinner-border spinner-border-sm text-dark" role="status">
                                <span class="visually-hidden">Loading...</span>

                            </div>';
                            
                $linetotaleDisplay = number_format($linetotal);
                $titleDisplay = truncatelimitWords($title,25);
                $qtyFinal = number_format($qty);
                $projectionFinal = number_format($projection);
                $rsmqtyFinal = number_format($rsmqty);
                $ssmqtyFinal = number_format($ssmqty);
                $isbnunitpriceDisplay = number_format($isbnunitprice);

              
                    
                    $tr ='
                    <tr>
                        <td class="d-none"> <a class="disaprv-projn" data-customercode="'.$customercode.'" hidden data-isbn="'.$isbn.'">
                            <span class="text-danger fa-2x fas fa-times-circle"></span>
                            <input class="form-control text-center d-none un-cl form-control-sm for_approval_projection_isbn_unitp" type="number" value="'.$isbnunitpriceclean.'" min="1">
                            <input class="form-control un-cl d-none text-center form-control-sm for_approval_projection_isbn_prev1_sales '.$customercode.''.$isbn.'totalprev1value"" type="number" value="" min="1">
                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_isbn_linetotal '.$customercode.'linetotal '.$customercode.''.$isbn.'linetotal" type="number" value="" min="1">
                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_isbn_customercode" type="text" value="'.$customercode.'">
                        </a></td>
                        <td class="text-center">'.$isbn.'</td>
                        <td class="text-center" title="'.$title.'">'.$titleDisplay.'</td>
                        <td>₱<span class="for_approval_projection_linetotal_amount_display '.$customercode.''.$isbn.'linetotaltext">0</span></td>
                        <td>₱'.$isbnunitpriceDisplay.'</td>
                        <td>'.$population.'</td
                        <td>'.$projectionFinal.'</td>
                        <td>'.$rsmqtyFinal.'</td>
                        <td>'.$ssmqtyFinal.'</td>
                        <td>0</td>
                        <td>0</td>
                   
                         <td><span class="totalbudget_display '.$customercode.''.$isbn.'totalbudget">0</span></td>
                        <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev1">0</span></td>
                        <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev2">0</span></td>
                        <td><span class="totalprev_display '.$customercode.''.$isbn.'totalprev3">0</span></td>
                        <td class="d-none">    
                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_isbn" type="text" value="'.$isbn.'">
                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_isbn_title" type="text" value="'.$title.'">
                            <input class="form-control d-none text-center form-control-sm un-cl for_approval_projection_isbn_disc" type="text" value="'.$disc.'">
                        </td>
                        
                    </tr>';

                    $response['customerisbnlist'][] = array(
                        "num" => $num,
                        "tr" => $tr,
                        "isbn" => $isbn
                        
                    );


            }
  
//isbn sales history
                    $pernrCustomerIsbnPrev3YearSalesHistory = pernrCustomerIsbnPrev3YearSalesHistory($customercode,$isbnlist,$usernamePERNR);
                    
                    if($pernrCustomerIsbnPrev3YearSalesHistory->isEmpty()){
                            $response['customerisbnsaleshistorylist'][] = array(
                                "num" => $isbnlist
                                
                            );
                    }
                    else {
                        foreach ($pernrCustomerIsbnPrev3YearSalesHistory as $aaa){

                            $isbnhistory = $aaa->isbn;
                            $totalPrev1 = $aaa->total_1 ?? 0;
                            $totalPrev2 = $aaa->total_2 ?? 0;
                            $totalPrev3 = $aaa->total_3 ?? 0;

                            $totalPrev1Display = number_format($totalPrev1);
                            $totalPrev2Display = number_format($totalPrev2);
                            $totalPrev3Display = number_format($totalPrev3);

                            $response['customerisbnsaleshistorylist'][] = array(
                                "customercode" => $customercode,
                                "isbn" => $isbnhistory,
                                "total_1" => $totalPrev1Display,
                                "total_1Value" => $totalPrev1,
                                "total_2" => $totalPrev2Display,
                                "total_3" => $totalPrev3Display
                                
                            );
    
                        }

                    }
//------



 //get items in budgeting system                                         
        $customerBudgetTitlesThisYear = customerTitleBudgetThisYear($customercode,$usernamePERNR);
                    
                    
        if($customerBudgetTitlesThisYear->isEmpty()){


            $response['customerisbnbudgetqty'][] = array(
                "customercode" => '',
                "isbn" => '',
                "qty" => '',
                
            );
        } else {

            foreach($customerBudgetTitlesThisYear as $risbn){

                $isbn = $risbn->isbn;
                $qty = $risbn->qty;
                $qtyFinal = number_format($qty);

                    
                $response['customerisbnbudgetqty'][] = array(
                    "customercode" => $customercode,
                    "isbn" => $isbn,
                    "qty" => $qtyFinal,
                    
                );
            } 


        }
 //------------

   
   
        return response()->json($response);


    }

        
    public function datatable_create_projection_customer_list(Request $request) {

    

        $basedocnum = $request->query('basedocnum');

        $pernr = session('pernr');
        $user_staff = session('user_staff');

        $thisyear = getPreviousYear(0);
        $prevyear = getPreviousYear(1);
        $prevyear2 = getPreviousYear(2);
        $prevyear3 = getPreviousYear(3);
        $date_now = date_now('dateonly');

        $qprojectionperiod = projection_period_details($basedocnum);
        $projectionperiodStatus = $qprojectionperiod->STATUS ?? '';

        $response['projectionperiod'][] = array(

                "projperiodstatus" => $projectionperiodStatus,
        );


        $queryhasreturn =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
        ->selectRaw('
                  t2.DOCNUM,
                  CUSTOMER,
                  COUNT(DISTINCT EAN11) as cnt,
                  MAX(t2.CUSTOMERNAME) as CUSTOMERNAME,
                  MAX(t1.updated_at) as updated_at
                  ')
        ->where('t1.STATUS','returned_isbn')
        ->where('t1.USERNAME',  $user_staff)
        ->where('t1.BASEDOCNUM',$basedocnum)
        ->groupBy('t2.DOCNUM','t2.CUSTOMER')
        ->orderByRaw('CUSTOMERNAME ASC')
        ->get();

        $response['customerhasreturncount'][] = array(

            'cnt' => $queryhasreturn->count(),

        );

        if(!$queryhasreturn->isEmpty()) {

            foreach ($queryhasreturn as $rt) {

                $custcode = $rt->CUSTOMER;
                $custname = $rt->CUSTOMERNAME;
                $updated_at = $rt->updated_at;
                $cnt = $rt->cnt;

                $customerhasreturnDisplay = '
                
                           <a class="border-300 create_projection_isbn_list_display" data-customercode="'.$custcode.'" data-customername="'.$custname.'" >
                                    <div class="px-1 px-sm-2 py-2  border-300 notification-card position-relative unread border-bottom">
                                    <div class="d-flex align-items-center justify-content-between position-relative">
                                        <div class="d-flex">
                                            <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../../assets/img/team/40x40/avatar.webp" alt="" />
                                            </div>
                                            <div class="flex-1 me-sm-3">
                                                <h4 class="fs--1 text-black">'.$custname.'</h4>
                                                <p class="fs--1 text-1000 mb-0  fw-normal"><span class="me-0 fs--2"></span>
                                                '.$custcode.'
                                                <span class="ms-2 text-400 fw-bold fs--2"></span></p>
                                                <p class="text-800 fs--1 mb-0"><span class="me-0 fas fa-book-open"></span><span class="fw-bold"> You have <b> '.$cnt.' </b> return title(s). </span></p>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </a>
                ';

                $response['customerhasreturn'][] = array(

                    'customercode' => $custcode,
                    'customername' => $custname,
                    'customerhasreturn' => $customerhasreturnDisplay,

                );

            }

        } else {
            
            $response['customerhasreturn'][] = array(

                'customercode' => '',
                'customername' => '',
                'customerhasreturn' => '
                              <a class="border-300">
                                    <div class="px-1 px-sm-2 py-2  border-300 notification-card position-relative unread border-bottom">
                                    <div class="d-flex align-items-center text-center justify-content-center position-relative">
                                        <div class="d-flex text-center">
                                            
                                            <div class="flex-1 me-sm-3">
                                                <h4 class="fs--1 text-black">You have no return projections</h4>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </a>
                ',

            );
             
        }

        $customerInprojectiond = [];
        
//get all customer's isbn if already saved period

      $queryprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
      ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
      ->select(
                't1.*',
                DB::raw("CASE WHEN t1.STATUS = 'returned_isbn' THEN '1' ELSE '0' END  as HASRETURN"),
                'CUSTOMER',
                'CUSTOMERNAME')
      ->whereNull('t1.SUBMIT')
      ->where('t1.USERNAME',  $user_staff)
      ->where('t1.BASEDOCNUM',$basedocnum)
      ->get();

        if(!$queryprojectiond->isEmpty()) {

            $customersavedDisplay = '        
                            <a class="border-300">
                                    <div class="px-1 px-sm-2 py-2  border-300 notification-card position-relative unread border-bottom">
                                    <div class="d-flex align-items-center text-center justify-content-center position-relative">
                                        <div class="d-flex text-center">
                                            
                                            <div class="flex-1 me-sm-3">
                                                <h4 class="fs--1 text-black">You have no saved.</h4>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </a>';
                                $displayedCustomers = array();
                                $customerSavedCounts = array();
                                
                                // First loop: bilangin muna ilang titles per customer
                                foreach ($queryprojectiond as $row) {
                                    $code = $row->CUSTOMER;
                                
                                    if (!isset($customerSavedCounts[$code])) {
                                        $customerSavedCounts[$code] = 0;
                                    }
                                
                                    $customerSavedCounts[$code]++;
                                }
                                
                                // Second loop: actual display and response
                                foreach ($queryprojectiond as $rpd) {
                                    $customercode = $rpd->CUSTOMER;
                                    $customername = $rpd->CUSTOMERNAME;
                                
                                    $customerInprojectiond[] = $customercode;
                                
                                    $branchwhouse = $rpd->BRANCHWHOUSE;
                                    $isbn = $rpd->EAN11;
                                    $isbn_title = $rpd->DESCRIPTION;
                                    $isbn_unitp = $rpd->UNITP;
                                    $disc = $rpd->DISC;
                                    $population = $rpd->POPULATION;
                                    $qty = $rpd->QTY;
                                    $linetotal = $rpd->LINETOTAL;
                                    $isbnremarks = $rpd->REMARKS;
                                    $hasreturn = $rpd->HASRETURN;
                                    $isbnstatus = $rpd->STATUS;
                                
                                    $customersavedDisplay = '';
                                    $countTitles = isset($customerSavedCounts[$customercode]) ? $customerSavedCounts[$customercode] : 0;
                                
                                    if ($isbnstatus == 'saved' && !isset($displayedCustomers[$customercode])) {
                                        $customersavedDisplay = '
                                            <a class="border-300 create_projection_isbn_list_display" 
                                                data-customercode="'.$customercode.'" 
                                                data-customername="'.$customername.'">
                                                <div class="px-1 px-sm-2 py-2 border-300 notification-card position-relative unread border-bottom">
                                                    <div class="d-flex align-items-center justify-content-between position-relative">
                                                        <div class="d-flex">
                                                            <div class="avatar avatar-m status-online me-3">
                                                                <img class="rounded-circle" src="../../assets/img/team/40x40/avatar.webp" alt="" />
                                                            </div>
                                                            <div class="flex-1 me-sm-3">
                                                                <h4 class="fs--1 text-black">'.$customername.'</h4>
                                                                <p class="fs--1 text-1000 mb-0 fw-normal">
                                                                    <span class="me-0 fs--2"></span>
                                                                    '.$customercode.'
                                                                    <span class="ms-2 text-400 fw-bold fs--2"></span></p>
                                                                    <p class="text-800 fs--1 mb-0"><span class="me-0 fas fa-book-open"></span><span class="fw-bold"> You have <b> '.$countTitles.' </b> saved title(s). </span></p>
                                                    
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        ';
                                
                                        $displayedCustomers[$customercode] = true;
                                    }
                                
                                    $response['projectiond'][] = array(
                                        "customercode" => $customercode,
                                        "branchwhouse" => $branchwhouse,
                                        "isbn" => $isbn,
                                        "isbn_title" => $isbn_title,
                                        "isbn_unitp" => $isbn_unitp,
                                        "disc" => $disc,
                                        "population" => $population,
                                        "qty" => $qty,
                                        "linetotal" => $linetotal,
                                        "isbnremarks" => $isbnremarks,
                                        "hasreturn" => $hasreturn,
                                        "projtnisbnstatus" => $isbnstatus,
                                        "customersaved" => $customersavedDisplay,
                                    );
                                }
        

      }

//-------


//get all customer's isbn that are submitted regardless of approved

$queryprojectiond =  OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
->select('t1.*','CUSTOMER')
->whereNotNull('t1.SUBMIT')
->where('t1.USERNAME',  $user_staff)
->where('t1.BASEDOCNUM',$basedocnum)
->get();

  if(!$queryprojectiond->isEmpty()) {

      foreach ($queryprojectiond as $rpd) {
              $customercode =  $rpd->CUSTOMER;

              $customerInprojectiond[] = $customercode;

              $branchwhouse = $rpd->BRANCHWHOUSE;
              $isbn = $rpd->EAN11;
              $isbn_title = $rpd->DESCRIPTION;
              $isbn_unitp = $rpd->UNITP;
              $disc = $rpd->DISC;
              $population = $rpd->POPULATION;
              $qty = $rpd->QTY;
              $linetotal = $rpd->LINETOTAL;
              $isbnremarks = $rpd->REMARKS;
              $isbnstatus = $rpd->STATUS;
  
              $response['projectiondsubmitted'][] = array(
                  "customercode" => $customercode,
                  "branchwhouse" => $branchwhouse,
                  "isbn" => $isbn,
                  "isbn_title" => $isbn_title,
                  "isbn_unitp" => $isbn_unitp,
                  "disc" => $disc,
                  "population" => $population,
                  "qty" => $qty,
                  "linetotal" => $linetotal,
                  "isbnremarks" => $isbnremarks,
                  "isbnstatus" => $isbnstatus,
                  "hasreturn" => '0',
                  "projtnisbnstatus" => $isbnstatus,
          );

      }
  

}

//-------

        $qbudget = TblBudget::from('tbl_budget as t1')
                                    ->where('t1.ae_code',$pernr)
                                    ->where('t1.period','LIKE','%'.$thisyear.'%')
                                    ->where('t1.cust_code','!=','')
                                    ->where('t1.isbn','!=','')
                                    ->groupBy('t1.cust_code')
                                    ->select('t1.cust_code',
                                                    DB::raw("
                                                        COUNT(DISTINCT t1.isbn) as CNTISBN,
                                                        MAX(t1.price) as UNITPRICE,
                                                        MAX(t1.disc) as DISC,
                                                        SUM(t1.qty) as QTYORDERED,
                                                        SUM(CAST(REPLACE(t1.value, ',', '') AS DECIMAL(15,2))) AS DOCTOTAL
                                                        
                                                        ")
                                            )
                                    ->get()
                              
                                 
                                ;

        $qomsh =  
        $numtitles = [];
        $kunnr = [];
        $qtyordered = [];
        $budgetqty = [];
        $sbrancwhouse = [];
    
 

//get customer from budgeting system if exist
    if($qbudget->isEmpty()) {
        $response['budgetlist'][] = array(
            "systemstatus" => '2-2',
        );
    }else {


            foreach ($qbudget as $rbudget){
                    $rbudgetkunnr = $rbudget->cust_code;

                    $response['budgetlist'][] = array(
                            "customercode" => $rbudgetkunnr,
                            "budgetqty" => $rbudget->QTYORDERED,
                    );

                }

        }
 //get customer from budgeting system if exist ----------
          
            $includecustomerlinkto = includeCustomerLinkTo($pernr);
            $excludecustomerlinkfrom = excludeCustomerLinkFrom($pernr);



            $_qlinkedaccounts = CrmMotherLookup::where('AE',$pernr)
              
                    ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
                    ->orderByRaw('CUSTNAME','ASC')
                    ->selectRaw('
                                    MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, 
                                    MAX(RTRIM(CUSTNO)) as CUSTNO, 
                                    MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, 
                                    MAX(AE) as AE, 
                                    MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
                                
                                    '
                                    )
                    ->groupBy('t2.CUSTNO')
                    // ->limit(10)
                    ;

            if($projectionperiodStatus === '0') {
                
                $_qlinkedaccounts->whereIn('CUSTNO',$customerInprojectiond);

            } else {
      
                $_qlinkedaccounts->whereNotIn('CUSTNO',$excludecustomerlinkfrom)
                ->orWhereIn('CUSTNO',$includecustomerlinkto);

            }

            $qlinkedaccounts = $_qlinkedaccounts->get();

            $num = 0;
            
            foreach ($qlinkedaccounts as $r){
                
                $num++;
                $customername = $r->CUSTNAME;
                $customercode = $r->CUSTNO;
                $motheracct = $r->MOTHERACCT;
                $department = $r->DEPARTMENT;
                $numtitles = $numtitles_map[$customercode] ?? 0;

                $kunnr[] = $customercode;
                
                $branchwhouseFinal =  $sbrancwhouse_map[$customercode] ?? '';

                $departmentDisplay = trim($department) !== '-' ? ' - ' . $department : '';

            
                $doctotalDisplay = '₱<span class="'.$customercode.'doctotal">0</span>     <input type="number" class="d-none form-control un-cl form-control-sm customersdoctotalvalue '.$customercode.'doctotalvalue" readonly="readonly">';
                $projtnDisplay = '<span class="'.$customercode.'projtntotal">0</span>  <input type="number" class="d-none form-control un-cl form-control-sm projtntotalvalue '.$customercode.'projtntotalvalue" readonly="readonly">';

                $numtitlesDisplay = '';

                $customernamedepartment = $customername . $departmentDisplay;
                $customernametruncate = truncatelimitWords($customernamedepartment,36);


                   $customernameDisplay = '
                
                   <a class="create_projection_isbn_list_display" data-customercode="'.$customercode.'" data-customername="'.$customernamedepartment.'" role="button" title="'.$customernamedepartment.'" aria-expanded="true" aria-controls="collapseExample1">
                                  <span class="line-clamp-1"><span class="create_projection_customer_hasreturn "></span>  '.$customercode.' &nbsp '.$customernamedepartment.'</span> 
                                  

                              </a>
                
                ';


                //select type only
                        $activeWarehouses = activeWarehouses();
                        $activeBranches = activeBranches();
                        
                        $ab = '';
                        $aw = '';
                    
                        // dd($branchwhouseFinal);

                        foreach($activeBranches as $a => $b){

                            $selected1 = $a === $branchwhouseFinal ? 'selected' : '';
                            $ab.= '
                                    <option value="'.$a.'" '.$selected1.'>'.$b.'</option>
                            ';
                        }
                    
                                            
                        foreach($activeWarehouses as $c => $d){
                            
                    
                            $selected2 = $c === $branchwhouseFinal ? 'selected' : '';
                            $aw.= '
                                    <option value="'.$c.' '.$selected2.'">'.$d.'</option>
                            ';
                        }
                //------

             

                $totalPrev1Display = '<a class="saleshistorycanvas" data-year="'.$prevyear.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev1">0</span></a>';
                $totalPrev2Display = '<a class="saleshistorycanvas" data-year="'.$prevyear2.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev2">0</span></a>';
                $totalPrev3Display = '<a class="saleshistorycanvas" data-year="'.$prevyear3.'" data-customercode="'.$customercode.'" data-customername="'.$customername.'"><span class="totalprev_display '.$customercode.'totalprev3">0</span></a>';
                $budgetqtyDisplay = '<span class="budgetqty '.$customercode.'budgetqty">0</span>';
                $curprojtnDisplay = '<span title="Approved this year" class="curprojtnqty_display '.$customercode.'curprojtnqty">0</span>';

           
           
                    $response['customerlist'][] = array(
                        "num" => $num,
                        "numtitles" => $numtitlesDisplay,
                        "customername" => $customernameDisplay,
                        "customercode" => $customercode,
                        "projection" => $projtnDisplay,
                        "motheracct" => $motheracct,
                        "department" => $department,
                        "amount" => $doctotalDisplay,
                        "curprojtn" => $curprojtnDisplay,
                        "budgetqtydisplay" => $budgetqtyDisplay,
                        "saleshistoryprev1" => $totalPrev1Display,
                        "saleshistoryprev2" => $totalPrev2Display,
                        "saleshistoryprev3" => $totalPrev3Display,
                        "systemstatus" => '2-1',
                        
                    );

            }


  //----------  

//temp customers ----
        $_qprojectionh = OPTv2Projectionh::where('USERNAME',$user_staff)
        ->whereNotIn('CUSTOMER',$kunnr)
        ->orderBy('CUSTOMERNAME','ASC')
 
        ;

        if($projectionperiodStatus === '0') {
                
            $_qprojectionh->whereIn('CUSTOMER',$customerInprojectiond);

        } else {
  
            $_qprojectionh->whereNotIn('CUSTOMER',$excludecustomerlinkfrom)
            ->orWhereIn('CUSTOMER',$includecustomerlinkto);

        }


        $qprojectionh = $_qprojectionh->get();
        if(!$qprojectionh->isEmpty()) {
            foreach ($qprojectionh as $ph) {

                $num++;
            
                $customername = $ph->CUSTOMERNAME;
                $customercode = $ph->CUSTOMER;
                $motheracct = $ph->MOTHERACCT;
                $department = $ph->DEPARTMENT;
            
                
                $customernamedepartment = $customername;
                $customernametruncate = truncatelimitWords($customernamedepartment,36);
                
                $customernameDisplay = '
                            
                    <a class="create_projection_isbn_list_display" data-customercode="'.$customercode.'" data-customername="'.$customernamedepartment.'" role="button" title="'.$customernamedepartment.'" aria-expanded="true" aria-controls="collapseExample1">
                                <span class="line-clamp-1"><span class="create_projection_customer_hasreturn "></span>  '.$customercode.' &nbsp '.$customernamedepartment.'</span> 
                                
            
                            </a>
                
                ';
            
                $projtnDisplay = '<span class="'.$customercode.'projtntotal">0</span>  <input type="number" class="d-none form-control un-cl form-control-sm projtntotalvalue '.$customercode.'projtntotalvalue" readonly="readonly">';
                $doctotalDisplay = '₱<span class="'.$customercode.'doctotal">0</span>     <input type="number" class="d-none form-control un-cl form-control-sm customersdoctotalvalue '.$customercode.'doctotalvalue" readonly="readonly">';
                    
                $curprojtnDisplay = '<span title="Approved this year" class="curprojtnqty_display '.$customercode.'curprojtnqty">0</span>';
            
                
                $response['customerlist'][] = array(
                    "num" => $num,
                    "numtitles" => '',
                    "customername" => $customernameDisplay,
                    "customercode" => $customercode,
                    "projection" => $projtnDisplay,
                    "motheracct" => $motheracct,
                    "department" => $department,
                    "amount" => $doctotalDisplay,
                    "curprojtn" => $curprojtnDisplay,
                    "budgetqtydisplay" => 0,
                    "saleshistoryprev1" => 0,
                    "saleshistoryprev2" => 0,
                    "saleshistoryprev3" => 0,
                    "systemstatus" => '2-1',
                    
                );
            
            }


        }

  //------

        $qcurprojtn = curprojtn_customer_pernr ($kunnr,$pernr);

        if($qcurprojtn->isEmpty()) {
            $response['crproj'][] = array(
                "systemstatus" => '2-2',
            );
        }else {
    
    
            foreach ($qcurprojtn as $cr) {

                $crproj_customercode = $cr->CUSTOMER;
                $crproj_projtn = $cr->TOTALPROJTN;
                $response['crproj'][] = array(
                        "customercode" => $crproj_customercode,
                        "curprojtn" => $crproj_projtn
                );
    
            }
    
        }

     

        $customersaleshistory = customerPrev3YearSalesHistory($kunnr);

        if($customersaleshistory->isEmpty()) {
            $response['cshlist'][] = array(
                "customercode" => '-',
                "total_1" => '-',
                "total_2" => '-',
                "total_3" => '-',
            );
        } else {
            
            foreach($customersaleshistory as $custr) {

          
                $customercode = $custr->cust_code;
                $total_1 = $custr->total_1;
                $total_2 = $custr->total_2;
                $total_3 = $custr->total_3;
            
                $total_1Display = number_format($total_1);
                $total_2Display = number_format($total_2);
                $total_3Display = number_format($total_3);
    
                $response['cshlist'][] = array(
                      "customercode" => $customercode,
                      "total_1" => $total_1Display,
                      "total_2" => $total_2Display,
                      "total_3" => $total_3Display,
                );
    
          }
    
        }




     
        
        return response()->json($response);

}

    public function datatable_approvals_list_table(Request $request) {
        $pernr = session('pernr');
        $rank = trim(session('rank'));
        $user_staff = session('user_staff');

         $filterPernr = filter_user_list('0','1')->get();
       

        $arrayFilterPernr = $filterPernr->pluck('PERNR')->toArray();

        $projectionQ = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                    ->selectRaw("
                                MAX(t1.id) as MAXID,
                                MAX(t1.BASEDOCNUM) as BASEDOCNUM,
                                'PID: ' + MAX(t1.PROJECTIONID) as REFERENCE,
                                MAX(t1.DOCNUM) as DOCNUM,
                                MAX(t1.USERNAME) as USERNAME,
                                MAX(t1.PERNR) as PERNR,
                                MAX(t1.DATESUBMIT) as DATESUBMIT,
                                MAX(t1.SUBMIT) as SUBMIT,
                                '0' as SAVED,
                                MAX(t1.SUPPLEMENTAL) as SUPPLEMENTAL,
                                '' as REQTO,
                                'Projection' as APPROVALTYPE
                    ")
                    ->where('t1.SUBMIT', '1')
                    ->whereNull('t1.APPROVED')
                    ->whereIn('t1.PERNR', $arrayFilterPernr)
                    // ->where('t3.STATUS','=','1')
                    ->groupBy('t1.USERNAME', 't1.BASEDOCNUM');
                    
        ;

        $this->applyRankApprovalFilter($projectionQ,$rank);

            
            // second query
            $allocInQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
                ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
                ->selectRaw("
                    MAX(t1.id) as MAXID,
                    t1.BASEDOCNUM,
                    t1.REFERENCE,
                    MAX(t1.DOCNUM) as DOCNUM,
                    MAX(t1.USERNAME) as USERNAME,
                    MAX(t1.PERNR) as PERNR,
                    MAX(t1.DATESUBMIT) as DATESUBMIT,
                    MAX(t1.SUBMIT) as SUBMIT,
                    '0' as SAVED,
                    MAX(t1.SUPPLEMENTAL) as SUPPLEMENTAL,
                    MAX(t2.REQTONAME) as REQTO,
                    'Alloc. Transfer Request - In' as APPROVALTYPE
                ")
                ->whereIn('t1.PERNR', $arrayFilterPernr)
                ->whereNull('t1.APPROVED')
                ->whereNull('t1.CANCEL')
                ->whereNotNull('t1.SUBMIT')
                ->groupBy('t1.REFERENCE', 't1.BASEDOCNUM');
            
                $this->applyRankApprovalFilter($allocInQ,$rank);
  
            

             // second query
             $allocOutQ = OPTv2AllocReqd::from('OPTV2ALLOCREQD as t1')
                ->leftJoin('OPTV2ALLOCREQH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
                ->selectRaw("
                    MAX(t1.id) as MAXID,
                    t1.BASEDOCNUM,
                    t1.REFERENCE,
                    MAX(t1.DOCNUM) as DOCNUM,
                    MAX(t1.USERNAME) as USERNAME,
                    MAX(t1.PERNR) as PERNR,
                    MAX(t1.DATESUBMIT) as DATESUBMIT,
                    MAX(t1.SUBMIT) as SUBMIT,
                    '0' as SAVED,
                    MAX(t1.SUPPLEMENTAL) as SUPPLEMENTAL,
                    MAX(t2.REQTONAME) as REQTO,
                    'Alloc. Transfer Request - Out' as APPROVALTYPE
                ")
                ->whereNull('t1.APPROVED')
                ->whereNull('t1.CANCEL')
                ->whereNotNull('t1.SUBMIT')
                ->groupBy('t1.REFERENCE', 't1.BASEDOCNUM');
            
                $this->applyRankApprovalFilter($allocOutQ,$rank,'allocreqout');

              // convert allocation query
                $convertAllocdQ = OPTv2ConvertAllocd::from('OPTV2CONVERTALLOCD as t1')
                ->selectRaw("
                    MAX(t1.id) as MAXID,
                    t1.BASEDOCNUM,
                    'PID: ' + MAX(t1.PROJECTIONID) as REFERENCE,
                    MAX(t1.DOCNUM) as DOCNUM,
                    t1.USERNAME,
                    MAX(t1.PERNR) as PERNR,
                    MAX(t1.DATESUBMIT) as DATESUBMIT,
                    '1' as SUBMIT,
                    '0' as SAVED,
                    MAX(t1.SUPPLEMENTAL) as SUPPLEMENTAL,
                    '' as REQTO,
                    'Convert Allocation' as APPROVALTYPE
                ")
                ->whereNull('t1.APPROVED')
                ->whereNull('t1.CANCEL')
                ->groupBy('t1.USERNAME', 't1.BASEDOCNUM')   ;
            
                $this->applyRankApprovalFilter($convertAllocdQ,$rank,'convertalloc');
            
            // union all (Laravel handles it fine)
            $finalQuery = $projectionQ->unionAll($allocInQ)->unionAll($allocOutQ)->unionAll($convertAllocdQ);
            
            // order + get
            $queryFinal = $finalQuery->orderBy('MAXID', 'DESC')->get();
      

        if($queryFinal->isEmpty()) {

            $response = [
                'num' => '0'
            ];

        } else {

            $num = 0;

            foreach ($queryFinal as $r){
                $num++;
                $projdocnum = $r->BASEDOCNUM;
                $projectionid = $r->REFERENCE;
                $docnum = $r->DOCNUM;
                $approvaltype = $r->APPROVALTYPE;
                $username = $r->USERNAME;
                $pernr = $r->PERNR;
                $datesubmit = $r->DATESUBMIT;
                $supplemental = $r->SUPPLEMENTAL;
                $status = $r->STATUS;
                
          

                $qdetailsprojperiod = projection_period_details($projdocnum);
                $qdetailsusername = userNameDetails($username);
                $fullname = $qdetailsusername->FULLNAME ?? '';
                $period = $qdetailsprojperiod->PERIOD;
                $schoollevel = $qdetailsprojperiod->LEVEL;
                $startdate = $qdetailsprojperiod->STARTDATE;
                $enddate = $qdetailsprojperiod->ENDDATE;
                $schoolyear = $qdetailsprojperiod->YEAR;
                $periodstatus = $qdetailsprojperiod->STATUS;

                $linkforapproval= '';

// For Approval Links-------------
                if($approvaltype == 'Projection') {

                    if(strpos($rank,'RSM') !== false) {
                    
                        $linkforapproval= 'approvals/projection?pid='.$projdocnum.'&name='.$username.'&fname='. $fullname . ' ' . $pernr;
                    }
                    if(strpos($rank,'SSM') !== false) {
                     
                        $linkforapproval= 'approvals/final/projection?pid='.$projdocnum.'&name='.$username.'&fname='. $fullname . ' ' . $pernr;
                    }

                } 
                if ($approvaltype == 'Alloc. Transfer Request - In') {

                    $linkforapproval= 'approvals/allocationrequest/in?docnum=' . $docnum;
                    
                }

                if ($approvaltype == 'Alloc. Transfer Request - Out') {

                    $linkforapproval= 'approvals/allocationrequest/out?docnum=' . $docnum;
    
                }   

                if ($approvaltype == 'Convert Allocation') {

                    $linkforapproval= 'approvals/convertallocation/request?pid='.$projdocnum.'&name='.$username.'&fname='. $fullname . ' ' . $pernr;
               
    
                }   

//    -------------For Approval Links                     

                $projectionidDisplay1 =  ' ' . $projectionid . ' | Level: ' . $schoollevel . ' | Year: ' . $schoolyear . ' | ' . periodList()[$period];
                $projectionidDisplay2 =  '<a href="'.$linkforapproval.'" target="_blank" title="'.$projectionidDisplay1.'">'.$projectionid.'</a>';
                $supplementalDisplay = strpad($supplemental,3);
                $approvaltypeDisplay = $approvaltype;
                $tat = time_elapsed_string($datesubmit);
                $requestedto = $r->REQTO;

                $datesubmitDisplay =  formatDate($datesubmit,'mdy');
                // <!-- <a class="dropdown-item" href="#!">View</a>
                // <a class="dropdown-item" href="#!">Export</a> -->
                // <!-- <div class="dropdown-divider"></div> -->

                $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                                <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                      <div class="dropdown-menu dropdown-menu-end py-2">
                                             <a class="dropdown-item  text-primary" href="'.$linkforapproval.'" target="_blank" role="button" title="ANGELICUM LEARNING CENTRE, INC. - BASIC EDUCATION   0001802148" aria-expanded="true" aria-controls="collapseExample1">
                                                    View
                                                </a>';
                    // <div class="dropdown-divider"></div>    
            
                $action .= "
                                     </div>
                            </div>";

               
                            
                $statusDisplay = '
    
                <div class="mb-0 form-switch h5 d-flex justify-content-center">
                                              <input class="form-check-input projectionid-status-sw" id="flexSwitchCheckChecked" value="'.$status.'" type="checkbox" >
                                           </div>
                ';
             
                // if($periodstatus == '1') {

                    $response[] = array(
                        "num" => $num,
                        "projectionid" => $projectionid,
                        "reference" => $projectionidDisplay2,
                        "supplemental"  =>  $supplementalDisplay, 
                        "startdate" =>  $startdate,
                        "enddate"  => $enddate,
                        "year" =>  $schoolyear,
                        "level" =>  $schoollevel,
                        "approvaltype"  => $approvaltypeDisplay,
                        "datesubmit"  => $datesubmitDisplay,
                        "requestedto"  => $requestedto,
                        "name"  => $fullname,
                        "status"  => $statusDisplay,
                        "tat"  => $tat,
                        "action"  => $action,
                    );

                // } else {
                    
                //     $response = [
                //         'num' => '0'
                //     ];
                // }
               
        
            }

        }
        
        return response()->json($response);

    }

    public function submit_create_projection_forapproval(Request $request) {

        $staff = session('user_staff');
        $pernr = session('pernr');
        $rsm = session('rsm');
        $date_now_full = date_now();
        $date_now = date_now('dateonly');
    
        $projdocnum = $request->query('projdocnum');
    
        $qusernameDetails = userNameDetails($staff);

        $date_now = date_now();

        $status = 404;
        $html = "";

        $query = OPTv2Projectiond::where('BASEDOCNUM',$projdocnum)
                                ->where('USERNAME',$staff)
                                ->where('QTY', '!=', '0')
                                ->whereNull('SUBMIT');

     
        if(!$query->exists()){
            $status = 410;

        }
        else {

            $update = $query->update([
                    "STATUS" => 'for_rsm_approval',
                    "SUBMIT" => '1',
                    "DATESUBMIT" => $date_now
            ]);

            $this->EmailSubmittedForApproval($request,$projdocnum,$pernr,$rsm);

            // $queryh = OPTv2Projectionh::where('BASEDOCNUM',$projdocnum)
            // ->where('USERNAME',$staff)
            // ->where('HASRETURN','1')
            // ->update([
            //     "HASRETURN" => null
            // ]);;

            $status = 2;

        }
      

        $response[] = array( 
            "status" => $status,
            "html" => $html
        );

        return response()->json($response);
    }

    public function submit_return_projection(Request $request) {

        $projdocnum = $request->query('projdocnum');
        $docnumlist = $request->query('docnumlist');
        $remarks = $request->input('return_projection_reason');
        $pernr = session('pernr');
        $qpernrDetails = userDetails($pernr);
        $pernrfullname = $qpernrDetails->FULLNAME;
        $date_now = date_now();

        $expDocnumlist = explode(',',$docnumlist);
        $query = OPTv2Projectiond::whereIn('DOCNUM',$expDocnumlist)
                                ->whereNull('APPROVED');


        $update = $query->update([
                "STATUS" => 'returned_isbn',
                "RETURNREMARKS" => $remarks,
                "RETURNBY" => $pernrfullname,
                "SUBMIT" => null,
                "APPROVED1" => null,
                "APPROVEDBY1" => null,
                "QTYAPPROVED1" => null,
                "DATEAPPROVED1" => null,
        ]);

        $html = "";
        $status = "404";
        
        if($update) {
           
            $row = $query->first();
            $projtnpernr = $row->PERNR;
            $basedocnum = $row->BASEDOCNUM;

            foreach($expDocnumlist as $exd) {

                $createLogs = OPTv2Logs::create([
                        "REFERENCE" => $exd,
                        "REMARKS" => $remarks,
                        "USERID" => $pernr,
                        "DOCDATE" => $date_now,
                        "LOGTYPE" => 'returncustomerisbn',
                ]);

            }
    
            $query = OPTv2Projectionh::whereIn('DOCNUM',$expDocnumlist)
                                    ->update([
                                        "HASRETURN" => '1'
                                    ]);

            $status = "2";

            $this->EmailReturnProjection($request,$basedocnum,$projtnpernr,$remarks);

       

        }

        $response[] = array( 
            "status" => $status,
            "html" => $html
        );

        return response()->json($response);
    }
    
    public function datatable_allocreq_pernr_new_title(Request $request) {

        $pernr = session('pernr');
        $reqtopernr = $request->query('reqtopernr');
        $projdocnum = $request->query('projdocnum');
        $docnum = $request->query('docnum');
        $transfertype = $request->query('transfertype');

         if($transfertype === 'nonbsa_to_nonbsa' || $transfertype === 'nonbsa_to_bsa' )
        {
            $transfertypeFinal = 'nonbsa';
        }
        if($transfertype === 'bsa_to_nonbsa' || $transfertype === 'bsa_to_bsa' )
        {
             $transfertypeFinal = 'bsa';
        }

        $qallocreqdexisting = OPTv2AllocReqd::where('DOCNUM',$docnum)
                                    ->pluck('EAN11')
                                    ->toArray()
                                    ;

        $qinventory = OPTv2Allocated::where('BASEDOCNUM',$projdocnum)
                            ->where('PERNR',$reqtopernr)
                            ->where('ALLOCTYPE',$transfertypeFinal)
                            ->whereNotIn('EAN11',$qallocreqdexisting)
                            ->orderByRaw('CAST(QTY AS INT) DESC')
                            ->get();

        $num = 0;

        if($qinventory->isEmpty()) {

            $response = [
                "num" => 0
            ];

        }
        else {

            foreach ($qinventory as $r){
                $num++;
                $isbn = $r->EAN11;
                $matnr = $r->MATNR;
                $alloctype = $r->ALLOCTYPE;
                $reqtopernr = $r->PERNR;

                $qisbndetails = getISBNDetails($isbn);
                $quserdetails = userDetails($reqtopernr);

                $reqtopernrName = $quserdetails->FULLNAME;
                $description = htmlspecialchars($qisbndetails->MAKTX, ENT_QUOTES, 'UTF-8');
                $balanceqty = $r->QTY ?: 0 ;

                
                if($alloctype == 'nonbsa'){
                    
                    $orderoms = get_year_omstransact_isbn_pernr_pertitle($isbn,$reqtopernr,$projdocnum) ;
                    $balanceqty = $balanceqty - $orderoms ;
                }

                $balanceqtyDisplay = number_format($balanceqty);

                $descriptiontruncate = truncatelimitWords($description,27) ;
                $descriptionDisplay = '<span class="" title="'.$description.'"> '.$descriptiontruncate.' </span>';

                $reqqty = ' <div class="d-flex justify-content-center">
                <input class="form-control text-center p-1 w-75 allocreq_new_title_qty" value="0" max="'.$balanceqty.'" 
                data-type="'.$alloctype.'" data-balance="'.$balanceqty.'" data-reqtopernr="'.$reqtopernr.'" data-reqtopernrname="'.$reqtopernrName.'"
                                type="number" value="" data-isbn="'.$isbn.'" data-title="'.$description.'">
                        </div>
                ';
               
                $addbtn = ' <a class="fw-bold btn btn-sm p-1 btn-primary btn-allocreq-new-title" ">
                                     Add
                                </a>';

                $response[] = array (
                    "num" => $num,
                    "isbn" => $isbn,
                    "titlename" => $descriptionDisplay,
                    "reqtopernrname" => $reqtopernrName,
                    "reqqty" => $reqqty,
                    "balance" => $balanceqtyDisplay,
                    "alloctype" => $alloctype,
                    "addbtn" => $addbtn,
                );


            }

        }
      
        
        
        return response()->json($response);

    }

    public function datatable_reports_stockallocationsummary(Request $request) {

        $isbn = $request->query('isbn');
        $pernr = $request->query('pernr');
        $division = $request->query('division');
        $basedocnum = $request->query('basedocnum');

        $_qstockallocated = OPTv2Allocated::from('OPTV2ALLOCATED as t1')
                            ->where('BASEDOCNUM',$basedocnum)
                            ->where('t1.PROJECTION','<>','0')
                            ->selectRaw("
                                     *,
                                     (SELECT TOP 1 DESCRIPTION FROM OPTV2PROJECTIOND t2 WHERE t2.EAN11 = t1.EAN11 ) as DESCRIPTION,
                                     (SELECT TOP 1 PERNRNAME FROM OPTV2PROJECTIONH t3 WHERE t3.PERNR = t1.PERNR ORDER BY id DESC ) as PERNRNAME
                                     
                            ")
                            ->orderBy('EAN11','DESC');

              
        $_qstockallocationbreakdown = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                        ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
                        ->selectRaw("
                            t1.EAN11,
                            t2.BSA,
                            t1.PERNR,
                            t1.BRANCHWHOUSE,
                            MAX(t2.PERNRNAME) as PERNRNAME,
                            SUM(CAST(t1.QTY AS INT)) as TOTALPROJTN
                        ")
                        ->where('t1.BASEDOCNUM', $basedocnum)
                        ->whereNotNull('t1.APPROVED')
                        ->groupBy('t1.EAN11','t2.BSA','t1.PERNR','t1.BRANCHWHOUSE')
                        ;
        if($division !== '1') {
            
            $pernrList = OPTv2User::where(
                                DB::raw('UPPER(LTRIM(RTRIM(DIVISION)))'),
                            'LIKE',
                            '%'.strtoupper($division).'%')
                    ->pluck('PERNR')
                    ->toArray();
                    ;
            $_qstockallocated->whereIn('PERNR',$pernrList);
            $_qstockallocationbreakdown->whereIn('t1.PERNR',$pernrList);

        }

        if($pernr !== '1') {

            $_qstockallocated->where('PERNR',$pernr);
            $_qstockallocationbreakdown->where('t1.PERNR',$pernr);
        }

        $num = 0;

        $qstockallocated = $_qstockallocated->get();
// group breakdown by EAN11 + BSA

        $qstockallocationbreakdown = $_qstockallocationbreakdown->get();
        $breakdownGrouped = $qstockallocationbreakdown->groupBy(function ($item) {
            return $item->EAN11 . '-' . $item->BSA . '-' . trim($item->PERNR);
        });


        if($qstockallocated->isEmpty()) {

            $response = [
                "num" => 0
            ];

        }
        else {

            //ordered
                $pernr = filter_user_list_in_projection()->pluck('PERNR')->toArray();;
                $qOrdered = get_year_omstransact_isbn_pernr($pernr,$basedocnum);

                $dorderedMap = [];
                foreach ($qOrdered as $d3) {
                    $dorderedMap[$d3->EAN11][trim($d3->PERNR)] = $d3->OMSQTY;
                }
            //---------ordered
            
            // dd($dorderedMap);
            foreach ($qstockallocated as $r){
                $num++;
                $isbn = $r->EAN11;
                $matnr = $r->MATNR;
                $pernr = $r->PERNR;
                $pernrname = $r->PERNRNAME;
                $alloctype = $r->ALLOCTYPE;
                $description = $r->DESCRIPTION;
                $allocation = $r->QTY;
                $projection = $r->PROJECTION;
                $ordered =   $dorderedMap[$isbn][trim($pernr)] ?? 0;
                $orderedDisplay = number_format($ordered);
                $titlename = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
                $descriptionDisplay = '<span title="'.$description.'" class="line-clamp-1">'.$description.'</span>';
         
                // $nonbsaqtyDisplay = number_format($nonbsaqty);

                $bsa = $alloctype == 'nonbsa' ? '0' : '1';

            // get breakdown for this item
                $key = $isbn . '-' . $bsa . '-' . trim($pernr);
                $breakdown = $breakdownGrouped->has($key) ? $breakdownGrouped[$key] : collect();

                $breakdownList = $breakdown->map(function ($b) {
                    return $b['BRANCHWHOUSE'] . '<span class="text-warning fw-bold">(' . $b['TOTALPROJTN']. ')</span>';
                })->implode(', ');
                
                $pernrnameDisplay = '<span title="'.$pernrname.'" class="line-clamp-1">'. $pernr . ' ' . $pernrname.'</span>' ;

                $isbnDisplay = ' <div class="d-flex justify-content-between "> • ' . $isbn . '  <span class="text-primary w-75 fw-bold"> ' .$descriptionDisplay . '</span></div>';
               
                $isbnDisplay = $isbn;
                $response[] = array (
                    "num" => $num,
                    "isbn" => $isbnDisplay,
                    "description" => $descriptionDisplay,
                    "type" => $alloctype,
                    "allocation" => $allocation,
                    "pernr" => $pernr,
                    "ordered" => $orderedDisplay,
                    "pernrname" => $pernrnameDisplay,
                    "projection" => $projection,
                    "breakdown" => '*' . $breakdownList,
            
                );


            }

        }
      

        // $breakdownGrouped = $qprojectiondbreakdown->groupBy(function ($item) {
        //     return $item->EAN11 . '-' . $item->BSA;
        // });
        
        // $response = [];
        
        // if ($qprojectiond->isEmpty()) {
        //     $response = ["num" => 0];
        // } else {
        //     $num = 0;
        //     foreach ($qprojectiond as $risbnprojd) {
        //         $num++;
        //         $isbn = $risbnprojd->EAN11;
        //         $bsa = $risbnprojd->BSA;
        //         $description = $risbnprojd->DESCRIPTION;
        //         $totalprojtn = $risbnprojd->TOTALPROJTN;
                
        //         $descriptiontruncate = truncatelimitWords($description, 37);
        //         $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
        
        //         $bsaDisplay = $bsa == '1' ? 'BSA' : 'Non-BSA';
        
        //         // get breakdown for this item
        //         $key = $isbn . '-' . $bsa;
        //         $breakdown = $breakdownGrouped->has($key) ? $breakdownGrouped[$key] : collect();
        
        
        return response()->json($response);

    }

    public function datatable_convertalloc_balance_table(Request $request) {

        $isbn = $request->query('isbn');
        $basedocnum = $request->query('basedocnum');
        $converttype = $request->query('converttype');
        $pernr = session('pernr');

        $toconverttype = 'nonbsa';
        if($converttype == 'nonbsa'){

            $toconverttype = 'bsa';

        }

  
    //select type only
           $activeWarehouses = activeWarehouses();
           $activeBranches = activeBranches();
           
           $ab = '';
           $aw = '';
       
           // dd($branchwhouseFinal);

           foreach($activeBranches as $a => $b){

               $ab.= '
                       <option value="'.$a.'">'.$b.'</option>
               ';
           }
       
                               
           foreach($activeWarehouses as $c => $d){
               
       
               $aw.= '
                       <option value="'.$c.'">'.$d.'</option>
               ';
           }

           $selectbranchwhouse = '
                <select name="convertalloc_new_branchwhouse[]" id="" class="form-control convertalloc_new_branchwhouse  form-control-sm">
                    <option value="" disabled selected>Choose in the list </option>

                    <optgroup label="Branches" class="branchesopt">
                        '.$ab.'
                    </optgroup>


                    <optgroup label="Warehouses" class="whouseopt">
                        '.$aw.'
                    </optgroup>
                </select>
            ';
            
   //------


        $qinventory = OPTv2Allocated::where('BASEDOCNUM',$basedocnum)
                            ->where('ALLOCTYPE',$converttype)
                            ->where('QTY','<>','0')
                            ->where('PERNR',$pernr)
                            ->orderBy('PERNR','DESC')
                            ->get();

        $num = 0;

        if($qinventory->isEmpty()) {

            $response = [
                "num" => 0
            ];

        }
        else {

            $isbnlist = [];
            foreach ($qinventory as $r){
                $isbn = $r->EAN11;
                $matnr = $r->MATNR;
                $type = $r->TYPE;
                $qty = $r->QTY;

                $isbnlist[] = $isbn;

                $balance = $qty;


                $response[] = array (
                    "isbn" => $isbn,
                    "titlename" => '',
                    "balance" => $balance,
                    "toconverttype" => $toconverttype,
                    "selectbranchwhouse" => $selectbranchwhouse,
                    "qtyinput" => '',
                    "type" => $type,
                );


            }

            $qitemdetails = OPTv2Projectiond::groupBy('EAN11')
                            ->selectRaw('
                                EAN11,
                                MAX(DESCRIPTION) as MAKTX,
                                MAX(MATNR) as MATNR
                            ')
                            ->where('BASEDOCNUM',$basedocnum)
                            ->get()
                            ;

            $qtitlename = [];
            foreach($qitemdetails as $qitem) {
                $qtitlename[$qitem->EAN11]['titlenamedisplay'] = '<span class="line-clamp-1" title="'.$qitem->MAKTX.'"> '.$qitem->MAKTX.' </span>';
                $qtitlename[$qitem->EAN11]['titlenametext'] = $qitem->MAKTX;
                $qtitlename[$qitem->EAN11]['matnr'] = $qitem->MATNR;

            }

            foreach($response as &$res) {

                $titlename = $qtitlename[$res['isbn']]['titlenamedisplay'] ?? '-';
                $res['titlename'] = $titlename;
                $res['qtyinput'] =   '<div class="d-flex justify-content-center">
                                            <input name="convertalloc_new_qty[]" class="form-control text-center p-1 w-75 convertalloc_new_qty" value="0"  max="'.$res['balance'].'" 
                                            data-converttype="'.$converttype.'" data-toconverttype="'.$toconverttype .'" data-basedocnum="'.$basedocnum.'"  data-balance="'.$res['balance'].'" 
                                                            type="number" value="" data-isbn="'.$res['isbn'].'">
                                                    </div>
                                                    <div class="d-flex d-none justify-content-center">
                                                        <input class="form-control text-center un-cl p-1 w-75 d-none" name="convertalloc_new_converttype[]" value="'.$converttype.'" hidden readonly>
                                                        <input class="form-control text-center un-cl p-1 w-75 d-none" name="convertalloc_new_toconverttype[]" value="'.$toconverttype.'" hidden readonly>
                                                        <input class="form-control text-center un-cl p-1 w-75 d-none" name="convertalloc_new_titlename[]" value="'.$qtitlename[$res['isbn']]['titlenametext'].'" hidden readonly>
                                                        <input class="form-control text-center un-cl p-1 w-75 d-none" name="convertalloc_new_matnr[]" value="'.$qtitlename[$res['isbn']]['matnr'].'" hidden readonly>
                                                        <input class="form-control text-center un-cl p-1 w-75 d-none" name="convertalloc_new_isbn[]" value="'.$res['isbn'].'" hidden readonly>
                                                        <input class="form-control text-center un-cl p-1 w-75 d-none" name="convertalloc_new_balance[]" value="'.$res['balance'].'" hidden readonly>
                                                    </div>
                                        ';

            }

            // unset($res);

        }
      
        
        
        return response()->json($response);

    }
    public function datatable_create_allocation_request_balance_table(Request $request) {

        $isbn = $request->query('isbn');
        $curpernr = session('pernr');
        $projdocnum = $request->query('projdocnum');
        $transfertype = $request->query('transfertype');
        $transfertypeFinal = '';

        if($transfertype === 'nonbsa_to_nonbsa' || $transfertype === 'nonbsa_to_bsa' )
        {
            $transfertypeFinal = 'nonbsa';
        }
        if($transfertype === 'bsa_to_nonbsa' || $transfertype === 'bsa_to_bsa' )
        {
             $transfertypeFinal = 'bsa';
        }

        // dd($transfertypeFinal);

        if($transfertype === 'nonbsa_to_bsa' || $transfertype === 'bsa_to_bsa' ) {
            $hidebranch = '';
            $hidewhouse = 'd-none';

        } else {

            $hidebranch = 'd-none';
            $hidewhouse = '';

        }
        $qinventory = OPTv2Allocated::where('BASEDOCNUM',$projdocnum)
                            ->where('EAN11',$isbn)
                            ->where('ALLOCTYPE',$transfertypeFinal)
                            ->where("PERNR",'!=',$curpernr)
                            ->selectRaw("PERNR,
                                MAX(EAN11) as EAN11,
                                MAX(MATNR) as MATNR,
                                SUM(CASE WHEN ALLOCTYPE = 'bsa' THEN CAST(QTY AS INT) ELSE 0 END)  as BSAQTY,
                                SUM(CASE WHEN ALLOCTYPE = 'nonbsa' THEN CAST(QTY AS INT) ELSE 0 END) as NONBSAQTY
                            ")
                            ->orderBy('PERNR','DESC')
                            ->groupBy('PERNR')
                            ->get();

        $num = 0;

        if($qinventory->isEmpty()) {

            $response = [
                "num" => 0
            ];

        }
        else {

            foreach ($qinventory as $r){
                $isbn = $r->EAN11;
                $matnr = $r->MATNR;
                $type = $r->TYPE;
                $reqtopernr = $r->PERNR;

                $qisbndetails = getISBNDetails($isbn);
                $quserdetails = userDetails($reqtopernr);

                $reqtopernrName = $quserdetails->FULLNAME;
                $titlename = htmlspecialchars($qisbndetails->MAKTX, ENT_QUOTES, 'UTF-8');
                $bsaqty = $r->BSAQTY ?: 0 ;
                $nonbsaqty = $r->NONBSAQTY ?: 0;

                $bsaqtyDisplay = number_format($bsaqty);
                $nonbsaqtyDisplay = number_format($nonbsaqty);

                if($transfertypeFinal == 'nonbsa'){
                    
                    $orderoms = get_year_omstransact_isbn_pernr_pertitle($isbn,$reqtopernr,$projdocnum) ;
                    $nonbsaqty = $nonbsaqty - $orderoms ;
                }
                
                $bsainput = ' <div class="d-flex justify-content-center">
                <input class="form-control text-center p-1 w-75 allocationrequestaddnewbooktitlerequestqty bsainputqty" value="0" max="'.$bsaqty.'" 
                data-type="bsa" data-balance="'.$bsaqty.'" data-reqtopernr="'.$reqtopernr.'" data-reqtopernrname="'.$reqtopernrName.'"
                                type="number" value="" data-isbn="'.$isbn.'" data-title="'.$titlename.'">
                        </div>
                ';

                $nonbsainput = '<div class="d-flex justify-content-center">
                <input class="form-control text-center p-1 w-75 allocationrequestaddnewbooktitlerequestqty nonbsainputqty" value="0"  max="'.$nonbsaqty.'" 
                data-type="nonbsa" data-balance="'.$nonbsaqty.'" data-reqtopernr="'.$reqtopernr.'" data-reqtopernrname="'.$reqtopernrName.'"
                                type="number" value="" data-isbn="'.$isbn.'" data-title="'.$titlename.'">
                        </div>
                ';

                $balance = $bsaqty;
                $qtyinput = $bsainput;
                if($transfertypeFinal == 'nonbsa'){
                    
                    $balance = $nonbsaqty;
                    $qtyinput =  $nonbsainput;

                }
                  //select type only
                  $activeWarehouses = activeWarehouses();
                  $activeBranches = activeBranches();
                  
                  $ab = '';
                  $aw = '';
              
                  // dd($branchwhouseFinal);

                        foreach($activeBranches as $a => $b){

                            $ab.= '
                                    <option value="'.$a.'">'.$b.'</option>
                            ';
                        }
                    
                                            
                        foreach($activeWarehouses as $c => $d){
                            
                    
                            $aw.= '
                                    <option value="'.$c.'">'.$d.'</option>
                            ';
                        }
                //------
        
                $branchwhouseDisplay = '
                                              <select name="branchwhouse[]" id="" class="form-control allocationrequestaddnewbooktitilebranchwhouse p-1">
                                                    <option value="" selected disabled> Select in the list </option>
                                                    <optgroup label="Branches" class="branchesopt '.$hidebranch.'">
                                                        '.$ab.'
                                                    </optgroup>


                                                    <optgroup label="Warehouses" class="whouseopt '.$hidewhouse.'">
                                                        '.$aw.'
                                                    </optgroup>
                                                </select>
                ';

                $reqtopernrfull = $reqtopernr . ' &nbsp' . $reqtopernrName;
                $reqtopernrnameDisplay = '<span class="line-clamp-1" title="'.$reqtopernrfull.'"> '.$reqtopernrfull.' </span>';

                if($balance > 0) {

                    $response[] = array (
                        "isbn" => $isbn,
                        "titlename" => $titlename,
                        "reqtopernrname" => $reqtopernrnameDisplay,
                        "balance" => $balance,
                        "qtyinput" => $qtyinput,
                        "nonbsaqty" => $nonbsaqty,
                        "bsaqty" => $bsaqty,
                        "nonbsaqty" => $nonbsaqty,
                        "bsaqtyDisplay" => $bsaqtyDisplay,
                        "nonbsaqtyDisplay" => $nonbsaqtyDisplay,
                        "bsainput" => $bsainput,
                        "nonbsainput" => $nonbsainput,
                        "branchwhouse" => $branchwhouseDisplay,
                        "type" => $type,
                    );
                    
                } else {

                }
            


            }

        }
      
        if(empty($response)) {

            $response = [
                "num" => 0
            ];

        }
        
        
        return response()->json($response);

    }
   
    public function datatable_allocation_request_list_table(Request $request) {

        $pernr = session('pernr');
        $projdocnum = $request->query('basedocnum');
        $query = OPTv2AllocReqh::where('PERNR',$pernr)
                                ->where('BASEDOCNUM',$projdocnum)
                                ->whereNull('CANCEL')
                                ->orderBy('id','DESC')
                                ->get();

        $num = 0;

        if($query->isEmpty()) {

            $response = [
                "num" => 0
            ];

        } else {

            foreach ($query as $r){
                $num++;
                $projectionid = $r->PROJECTIONID;
                $reqto = $r->REQTO;
                $reqtoname = $r->REQTONAME;
                $docnum = $r->DOCNUM;
                $reference = $r->REFERENCE;
                $transfertype = $r->TRANSFERTYPE;
                $status = $r->STATUS;
                $docdate = $r->DOCDATE;
                $cancelled = $r->CANCEL;
                $datesubmit = $r->DATESUBMIT ;
                $submit = $r->SUBMIT;
                
                
                    
                $transfertypeDisplay = $transfertype;
                $checked = $status == 1 ? 'checked' : '';
                $statusDisplay = status_display($status, $type = 'badge') ;
                $typeDisplay = type_display($transfertype);

                $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2">
                            <a href="#"  class="dropdown-item text-primary btn_reftrdetails" data-bs-toggle="modal" data-bs-target="#TRDetailsModal" 
                            data-cancelled="'.$cancelled.'" data-datesubmit="'.$datesubmit.'"  data-transfertype="'.$transfertype.'" data-transfertypetext="'.$typeDisplay.'" data-datereq="'.$docdate.'" data-reqtopernr="'.$reqto.'" 
                            data-submitted="'.$submit.'" data-reqto="'.$reqtoname.'" data-status="'.$status.'" data-reftr="'.$reference.'" data-docnum="'.$docnum.'">
                                            View</a>';
                    // <div class="dropdown-divider"></div>    
        
            
        
                $action .= "
                  </div>
                </div>";
           
    
                    $response[] = array(
                        "num" => $num,
                        "reqto" => $reqto,
                        "reqtoname" => $reqtoname,
                        "docnum" => $docnum,
                        "reference" => $reference,
                        "status" => $statusDisplay,
                        "docdate" => $docdate,
                        "datesubmit" => $datesubmit,
                        "transfertype" => $typeDisplay,
                        "action" => $action,
                    );
            }
        }
   
        
        return response()->json($response);

    }

    public function datatable_open_projection_list_table(Request $request) {

        $query = OPTv2ProjectionPeriod::orderBy('id','DESC')
                                ->get();

        $num = 0;
        foreach ($query as $r){
            $num++;
            $projectionid = $r->PROJECTIONID;
            $docnum = $r->DOCNUM;
            $period = $r->PERIOD;
            $supplemental = $r->SUPPLEMENTAL;
            $startdate = $r->STARTDATE;
            $enddate = $r->ENDDATE;
            $schoolyear = $r->YEAR;
            $schoollevel = $r->LEVEL;
            $staff = $r->USERCREATE;
            $status = $r->STATUS;
            $linenum = $r->LINENUM;
            $remarks = $r->REMARKS;
            $projectiontype = $r->PROJECTIONTYPE;
            
            $projectionidDisplay =  'ID: ' . $projectionid . ' | Period: ' . $period;
            $supplementalDisplay = strpad($supplemental,3);
                
            $periodDisplay = '';
            $checked = $status == 1 ? 'checked' : '';
            $statusDisplay = '

            <div class="mb-0 form-switch h5 d-flex justify-content-center">
                                          <input class="form-check-input projectionid-status-sw" id="flexSwitchCheckChecked" value="'.$status.'" '.$checked.' data-docnum= "'.$docnum.'" type="checkbox" >
                                       </div>
            ';
            // $periodList = periodList();
            // $periodDisplay = $periodList[$period];

           $pl = periodList();

           $enddateDisplay = '<input type="date" class="form-control p-1 border border-primary openprojectionupdateenddate input-sm text-center" title="Change end date." data-docnum="'.$docnum.'" data-curenddate="'.$enddate.'" value="'.$enddate.'">';
           $remarksDisplay = '<span class="line-clamp-1" title="'.$remarks.'" > '.$remarks.'</span>';
           $startdateDisplay = formatDate($startdate,'mdy');
           $periodDisplay = $pl[$period];

                $response[] = array(
                    "num" => $num,
                    "projectionid" => $projectionid,
                    "docnum" => $docnum,
                    "projectioniddisplay" => $projectionidDisplay,
                    "period"  =>  $periodDisplay, 
                    "supplemental"  =>  $supplementalDisplay, 
                    "startdate" =>  $startdateDisplay,
                    "enddate"  => $enddateDisplay,
                    "year" =>  $schoolyear,
                    "level" =>  $schoollevel,
                    "staff" =>  $staff,
                    "linenum"  => $linenum,
                    "remarks"  => $remarksDisplay,
                    "projectiontype"  => $projectiontype,
                    "status"  => $statusDisplay,
                );
        }
        
        return response()->json($response);

    }

    public function get_customer_projectiond(Request $request) {

        $customercode = $request->query('customercode');
        $basedocnum = $request->query('basedocnum');
        $qprojectionperiod = projection_period_details($basedocnum);
        $supplemental = $qprojectionperiod->SUPPLEMENTAL;

        $qprojectiond = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
            ->where('t1.SUPPLEMENTAL', $supplemental)
            ->where('t2.CUSTOMER', $customercode)
            ->get();

        if($qprojectiond->isEmpty()) {
            $response = [
                "num" => 0
            ];
        } else {


            $num = 0;
            foreach ($qprojectiond as $r) {

                $num++;
                $isbn = $r->EAN11;
                $title = $r->DESCRIPTION;
                $unitp = $r->UNITP;
                $disc = $r->DISC;
                $population = $r->POPULATION;
                $isbnunitpriceDisplay = number_format($unitp);

                $response[] = array(
                    "num" => $num,
                    "customercode" => $customercode,
                    "isbn" => $isbn,
                    "title" => $title,
                    "unitp" => $unitp,
                    "population" => $population,
                    "isbnunitpriceDisplay" => $isbnunitpriceDisplay,
                    "disc" => $disc,

        
                );
            }

        }


        
        return response()->json($response);

    }
    public function get_projection_minidashboard(Request $request) {

        $pernr = $request->query('pernr');
        $username = $request->query('username');
        $projdocnum = $request->query('projdocnum');

        $thisyear = getPreviousYear(0);
        $lastyear = getPreviousYear(1);
        $confirmedordersimdlevel = '9';

        $qytdsales = NewSales::from('new_sales as t1')
        ->select(DB::raw("SUM(CAST(REPLACE(t1.netsales, ',', '') AS DECIMAL(18,2))) as TOTAL"))
        ->where('t1.ae_code','LIKE','%'.$pernr.'%')
        ->where('doc_year',$thisyear)
        ->take(1)
        ->first();

        $qlysales = NewSales::from('new_sales as t1')
        ->select(DB::raw("SUM(CAST(REPLACE(t1.netsales, ',', '') AS DECIMAL(18,2))) as TOTAL"))
        ->where('t1.ae_code','LIKE','%'.$pernr.'%')
        ->where('doc_year',$lastyear)
        ->take(1)
        ->first();

        $qtybudget = TblBudget::from('tbl_budget as t1')
        ->select(DB::raw("SUM(CAST(REPLACE(t1.value, ',', '') AS DECIMAL(18,2))) as TOTAL"))
        ->where('t1.ae_code','LIKE','%'.$pernr.'%')
        ->where('PERIOD',$thisyear)
        ->take(1)
        ->first();

        
        $qthisprojtnperiod = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                            ->selectRaw("
                                                SUM(CAST(LINETOTAL AS DECIMAL(18,2)))  as TOTALTHISPROJTNPERIOD
                                            ")
                                            // ->where('APPROVED','1')
                                            ->where('BASEDOCNUM',$projdocnum)
                                            ->where('USERNAME',$username)
                                            ->first();
        $qytdprojtn = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                        ->selectRaw("
                                            SUM(CAST(LINETOTAL AS DECIMAL(18,2)))  as TOTALTHISYEARPROJTNPERIOD
                                        ")
                                        ->leftJoin('OPTV2PROJECTIONPERIOD as t2','t1.BASEDOCNUM','=','t2.DOCNUM')
                                        ->where('APPROVED','1')
                                        ->where('USERNAME',$username)
                                        ->where('YEAR','LIKE','%'.$thisyear.'-%')
                                        ->first();


        $projectionh = projectionDetails($projdocnum,$username);

        $qprojectionperiod = projection_period_details($projdocnum);
        $projperiodstatus = $qprojectionperiod->STATUS ?? '';

        $saved = $projectionh ? $projectionh->SAVED  : null ; 
        $statusdb = $projectionh ? $projectionh->STATUS  : null ; 
        $submitforapproval = $projectionh ? $projectionh->SUBMITFORAPPROVAL : null ; 
        $cntapprover1 = $projectionh ? $projectionh->CNTAPPROVER1 : null ; 
        $cntapprover2 = $projectionh ? $projectionh->CNTAPPROVER2 : null ; 
        $projectionstatusDisplay = $cntapprover2 > 0 ? status_display('approved', 'text') : ($cntapprover1 > 0 ? status_display('for_ssm_approval', 'text') : status_display($statusdb, 'text'));
        $thisprojtnperiod = $qthisprojtnperiod->TOTALTHISPROJTNPERIOD ?? 0;
        $ytdprojtn = $qytdprojtn->TOTALTHISYEARPROJTNPERIOD ?? 0;
        // if($saved == 1) {
        //     $projectionstatusDisplay = '<span class="text-primary">'. $statusdb . '</span>' ;
        // }
        // if($submitforapproval == 1) {
        //     $projectionstatusDisplay = '<span class="text-info">'. $statusdb . '</span>' ;
        // }

        $qytdsalesi = $qytdsales->TOTAL;
        $qytdsalesiDisplay = number_format($qytdsalesi);
        
        $qtybudgeti = $qtybudget->TOTAL;
        $qtybudgetiDisplay = number_format($qtybudgeti);

        $qlysalesi = $qlysales->TOTAL;
        $qlysalesiDisplay = number_format($qlysalesi);
      
        $projtnoverbudget = $ytdprojtn - $qtybudgeti;
        $projtnoverbudgetDisplay = number_format($projtnoverbudget);

        $thisprojtnperiodDisplay = number_format($thisprojtnperiod);
        $ytdprojtnDisplay = number_format($ytdprojtn);


        $response[] = array(

            "ytdsalesval" => $qytdsalesi,
            "lastyearval" => $qlysalesi,
            "tybudgetval" => $qtybudgeti,
            "ytdsales" => $qytdsalesiDisplay,
            "lastyear" => $qlysalesiDisplay,
            "tybudget" => $qtybudgetiDisplay,
            "projectionidstatus" => $projectionstatusDisplay,
            "thisprojtnperiod" => $thisprojtnperiodDisplay,
            "ytdprojtn" => $ytdprojtnDisplay,
            "projperiodstatus" => $projperiodstatus,
            "projtnoverbudget" => $projtnoverbudgetDisplay,

        );

        return response()->json($response);

        
    }

    public function get_projection_period_status(Request $request) {

        $pernr = $request->query('pernr');
        $username = $request->query('username');
        $projdocnum = $request->query('projdocnum');

        $qprp = OPTv2ProjectionPeriod::where('DOCNUM',$projdocnum)
        ->first();



        if(!empty($qprp)){

        $qcountapprovedfinalreq =  OPTv2FinalReq::where('BASEDOCNUM',$projdocnum)
                                            ->where('APPROVED','=','1')
                                            ->orderBy('id','DESC')
                                            ->pluck('EAN11')
                                            ->toArray();
    
        $num = 0;
        
        // $qcountforapprovalfinalreq = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
        //                                     ->select(
        //                                         DB::raw("COUNT(DISTINCT EAN11) as cntisbn")
        //                                         )
        //                                     ->where('BASEDOCNUM',$projdocnum)
        //                                     ->whereNotIn('EAN11',$qcountapprovedfinalreq)
        //                                     ->whereNotNull('t1.APPROVED')
        //                                     ->first();
            
        $qcountforapprovalfinalreq =  OPTv2FinalReq::where('BASEDOCNUM',$projdocnum)
                                                ->where('APPROVED','!=','1')
                                                   ->select(
                                                        DB::raw("COUNT(DISTINCT EAN11) as cntisbn")
                                                    )
                                                ->first();

        $qcountapprovedfinalreqDisplay = count($qcountapprovedfinalreq);


            $docnum         = $qprp->DOCNUM;
            $supplemental   = $qprp->SUPPLEMENTAL;
            $startdate      = $qprp->STARTDATE;
            $enddate        = $qprp->ENDDATE;
            $year           = $qprp->YEAR;
            $level          = $qprp->LEVEL;
            $period         = $qprp->PERIOD;
            $usercreate     = $qprp->USERCREATE;
            $remarks        = $qprp->REMARKS;
            $status         = $qprp->STATUS;
            $created_at     = $qprp->created_at;
            $updated_at     = $qprp->updated_at;
            $projectiontype = $qprp->PROJECTIONTYPE;
            $linenum        = $qprp->LINENUM;
            $projectionid   = $qprp->PROJECTIONID;


            $response[] = array(

                    "status"          => '1',
                    "projperiodstatus"=> $status,
                    "docnum"          => $docnum,
                    "supplemental"    => $supplemental,
                    "startdate"       => $startdate,
                    "enddate"         => $enddate,
                    "year"            => $year,
                    "level"           => $level,
                    "period"          => $period,
                    "usercreate"      => $usercreate,
                    "remarks"         => $remarks,
                    "created_at"      => $created_at,
                    "updated_at"      => $updated_at,
                    "projectiontype"  => $projectiontype,
                    "linenum"         => $linenum,
                    "projectionid"    => $projectionid,
                    "countforapprovalfinalreq"    => $qcountforapprovalfinalreq->cntisbn,
                    "countapprovedfinalreq"    => $qcountapprovedfinalreqDisplay,
    
            );

        } else {
            $response = array(

                "status" => '0',
    
            );
        }
     

        return response()->json($response);

        
    }

    public function submit_find_customer(Request $request) {

        // $_customer = $request->query("customer");
        // $customer = makeContainsPhrase($request->query("customer"));

        $customer = $request->query("customer");

        $pernr = $request->query('pernr');
        $withsapcode = $request->query('withsapcode');

        $num = 0;
        $date_only = date_now('dateonly');
        // 
        $querya = CrmMotherLookup::leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
                                ->orderByRaw('CUSTNAME','ASC')
                                ->take(50)
                                ->selectRaw('MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, MAX(AE) as AE, MAX(RTRIM(DEPARTMENT)) as DEPARTMENT, MAX(RTRIM(CUSTNO)) as CUSTNO')
                                // ->selectRaw('prd.CRMMOTHERLOOKUP.NAME as CUSTNAME, MAX(RTRIM(CUSTNO)) as CUSTNO, MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, MAX(AE) as AE')
                                // ->groupBy('prd.CRMMOTHERLOOKUP.NAME')
                                ->where(function( $querya) use ($customer) {
                                    $querya->where('CUSTNO','LIKE','%'.$customer.'%')
                                            ->orWhere('TAGS','LIKE','%'.$customer.'%')
                                            ;
                                            // ->orWhere('NAME', 'LIKE', '%"'.$customer.'"%');
                                         })
                             
                                ->groupBy('t2.CUSTNO')
                                ;
                                
        if($pernr == '1') {

        }else {
            $querya->where(function ($querya) use ($pernr) {
                        $querya->where('AE',$pernr)
                            ->orWhereIn('AE',userteam());
                 });
            
        }

        $query = $querya->get();

                if ($query->isEmpty()) {
                    
                    $response[] = array(
                        "num" =>  '0',
                        "description" =>  'No records found.'
                    );
               

        } else {
            foreach ($query as $row) {

                $num++;
                $name = $row->CUSTNAME ?: '-';
                $ae = $row->AE ?: '-';
                $code = $row->CUSTNO ?: '-';
                $motheract = $row->MOTHERACCT;
                $department = $row->DEPARTMENT ;
                $dept = '';

                if(!is_null($department) || $department !== '') {
                    $dept = ' - ' . $department;
                }
                // $code = $row->KUNNR;
                $qaename = userDetails($ae);
                $aename = $qaename ? $qaename->FULLNAME : '';

                $response[] = array(
                    "num" => $num,
                    "label" =>   $name . $dept . '   ' . $code ,
                    "customername" =>   $name . $dept ,
                    "dept" =>   $department ,
                    "ae" =>  $ae,
                    "aename" =>  $aename,
                    "kunnr" =>  $code,
                    "motheract" =>  $motheract
                );
            }
          }
          
                return response()->json($response);

    }

    public function submit_find_publisher(Request $request) {

        $search = $request->query('search');

        $num = 0;
        $qmatdel = ZmmMatdel::selectRaw("
                        LTRIM(RTRIM(
                            CASE 
                                WHEN CHARINDEX(';', ZZPUBLISHER1) > 0
                                    THEN LEFT(ZZPUBLISHER1, CHARINDEX(';', ZZPUBLISHER1) - 1)
                                ELSE ZZPUBLISHER1
                            END
                        )) AS publishername
                    ")
                    ->where('ZZLONGTEXT', 'NOT LIKE', '%Deactivate%')
                    ->whereRaw("LOWER(ZZPUBLISHER1) LIKE LOWER(?)", ['%' . $search . '%'])
                    ->groupByRaw("
                        LTRIM(RTRIM(
                            CASE 
                                WHEN CHARINDEX(';', ZZPUBLISHER1) > 0
                                    THEN LEFT(ZZPUBLISHER1, CHARINDEX(';', ZZPUBLISHER1) - 1)
                                ELSE ZZPUBLISHER1
                            END
                        ))
                    ")
                    ->orderBy('publishername', 'ASC')
                    ->limit(20)
                    ->get();


        if(!$qmatdel->isEmpty() ){


            foreach ($qmatdel as $mat) {
            
                $num++; 

                $publishername = $mat->publishername;
               
                $response[] = array(
                    "publishername" => $publishername,
                    "label" =>  $publishername,
                );
            }



        }
        
        else {
            $response[] = array(
                "num" =>  '0',
                "label" => '',
                "publishername" =>  'No records found.'
            );

        }
        

            
        
        return response()->json($response);

    }

    public function submit_find_item_inallocated(Request $request) {

 
        $basedocnum = $request->input('basedocnum');
        $alloctype = $request->input('alloctype');

        $html = '';
        $num = 0;
        $qprojectiond = OPTv2Allocated::where('BASEDOCNUM', $basedocnum)
                            ->where('ALLOCTYPE', $alloctype)
                            ->select('EAN11')
                            ->distinct()
                            ->pluck('EAN11')
                            ->toArray();
                                //  ->get();


        // if($qprojectiond->isEmpty()) {
        if(empty($qprojectiond)) {

            $response[] = array(
                "num" =>  '0',
                "description" =>  'No records found.'
            );
        } else {

            $qmatdel = ZmmMatdel::select('*')
                        ->whereIn('EAN11',$qprojectiond)
                        ->get();

            foreach($qmatdel as $projd) {
                $num++;

                $isbn = $projd->EAN11;
                $description = strtoupper($projd->MAKTX);


                $isbnlist[] = $isbn;

                $nan = "Not Found";
                $author = $projd->ZZAUTHOR1;
                $copyright = $projd->ZZCOPYRIGHT;

                $unitprice = $projd->UNITP;
                $isbnunitpriceclean = str_replace(',', '', $unitprice);
                $unitpriceDisplay = number_format($unitprice);

                $discount = 0;
                $finalunitprice = $unitprice;

                $titleDisplay = truncatelimitWords($description, 27);
                $copyrightDisplay = empty($copyright) || $copyright == ' ' ? '-' : $copyright;

                $descriptionDisplay = '<i>' . $isbn .  '</i>&nbsp&nbsp ' .  $description ;
                $response[] = [
                    "num" => $num,
                    "description" => $descriptionDisplay,
                    "descriptionDisplay" => $titleDisplay,
                    "isbn" => $isbn,
                    "copyright" => $copyrightDisplay,
                    "author" => $author,
                    "label" => '000TEMP',
                    "discount" => $discount,
                    "unitprice" => $unitprice,
                    "isbnunitpriceclean" => $isbnunitpriceclean,
                    "unitpriceDisplay" => $unitpriceDisplay,
                    "finalunitprice" => $finalunitprice,
                ];

            }
        

        }
   

        
        return response()->json($response);


    }
    public function submit_find_item_inprojectiond(Request $request) {

 
        $basedocnum = $request->input('basedocnum');

        $html = '';
        $num = 0;
        $qprojectiond = OPTv2Projectiond::where('BASEDOCNUM',$basedocnum)
                                ->selectRaw('
                                    EAN11,
                                    DESCRIPTION,
                                    MAX(AUTHOR) as AUTHOR,
                                    MAX(UNITP) as UNITP,
                                    MAX(COPYRIGHT) as COPYRIGHT


                                ')
                                ->orderBy('DESCRIPTION','ASC')
                                ->groupBy('EAN11','DESCRIPTION')
                                 ->get();


        if($qprojectiond->isEmpty()) {

            $response[] = array(
                "num" =>  '0',
                "description" =>  'No records found.'
            );
        } else {

            foreach($qprojectiond as $projd) {
                $num++;

                $isbn = $projd->EAN11;
                $description = strtoupper($projd->DESCRIPTION);


                $isbnlist[] = $isbn;

                $nan = "Not Found";
                $author = $projd->AUTHOR;
                $copyright = $projd->COPYRIGHT;

                $unitprice = $projd->UNITP;
                $isbnunitpriceclean = str_replace(',', '', $unitprice);
                $unitpriceDisplay = number_format($unitprice);

                $discount = 0;
                $finalunitprice = $unitprice;

                $titleDisplay = truncatelimitWords($description, 27);
                $copyrightDisplay = empty($copyright) || $copyright == ' ' ? '-' : $copyright;

                $descriptionDisplay = '<i>' . $isbn .  '</i>&nbsp&nbsp ' .  $description ;
                $response[] = [
                    "num" => $num,
                    "description" => $descriptionDisplay,
                    "descriptionDisplay" => $titleDisplay,
                    "isbn" => $isbn,
                    "copyright" => $copyrightDisplay,
                    "author" => $author,
                    "label" => '000TEMP',
                    "discount" => $discount,
                    "unitprice" => $unitprice,
                    "isbnunitpriceclean" => $isbnunitpriceclean,
                    "unitpriceDisplay" => $unitpriceDisplay,
                    "finalunitprice" => $finalunitprice,
                ];

            }
        

        }
   

        
        return response()->json($response);


    }

    public function submit_find_item(Request $request) {

        $search = trim($request->query('search'));
        $_customercode = $request->query('customercode') ;
        $tempstatus = $request->query('tempstatus') ;
        $showall = $request->query('showall') ;
        $customercode = isset($_customercode) ? $_customercode : '';
        $pernr = session('pernr');
             
// QUERY ITEM---

     
 

        $existingsapcodeactiveitems = PushListTagging::where('status','1')
                                    ->pluck('isbn')
                                    ->toArray()
        
        ;
        $qmatdel = ZmmMatdel::select('*')
                            ->where('ZZLONGTEXT','NOT LIKE', '%Deactivate%')
                            ->where(function ($query) use ($search) {
                                
                                $query->where('EAN11', 'LIKE', '%'. $search .'%')
                                ->orWhereRaw("LOWER(ZZLONGTEXT) LIKE LOWER(?)", ['%'. $search .'%'])
                                ->orWhereRaw("LOWER(MAKTX) LIKE LOWER(?)", ['%'. $search .'%'])
                                ->orWhereRaw("LOWER(ZZAUTHOR1) LIKE LOWER(?)", ['%'. $search .'%']);

                                    // ->orWhere('ZZLONGTEXT', 'LIKE', '%' . $search . '%')
                                    // ->orWhere('ZZAUTHOR1', 'LIKE', '%' . $search . '%');
                            })
                            ->orderByRaw("
                            CASE
                                WHEN REPLACE(MAKTX, CHAR(160), ' ') LIKE ? THEN 0
                                WHEN REPLACE(MAKTX, CHAR(160), ' ') LIKE ? THEN 1
                                WHEN ZZLONGTEXT LIKE ? THEN 2
                                WHEN ZZAUTHOR1 LIKE ? THEN 3
                                ELSE 4
                            END
                        ", [$search, "%{$search}%", "%{$search}%", "%{$search}%"])
                            // ->whereIn('EAN11',$existingsapcodeactiveitems)
                            ->limit(20)
                            ->get()
        ;

        // dd($existingsapcodeactiveitems);
        
        $qrupdatepushlistisbn = OPTv2UpdatePushISBN::select('*')
                            ->whereNull('SAPEAN11')
                            ->where('STATUS','1')
                         
                            ->where(function ($qupdatepushlist) use ($search) {
                                
                                $qupdatepushlist->where('EAN11', 'LIKE', '%' . $search . '%')
                                ->orWhereRaw("LOWER(DESCRIPTION) LIKE LOWER(?)", ['%' . $search . '%']);

                            })
                            ->limit(20)
                           
                    ;

        if($tempstatus == '0'){
         
        }
        else {

            $qrupdatepushlistisbn->where('STATUS','1');

        }

        $qupdatepushlistisbn = $qrupdatepushlistisbn->get();
//---QUERY ITEM   
            
        $num = 0;
        $num1 = 0;
        $isbnlist = [];

//create projection on click list top projection
        if($search == 'projectiontop10') {

            $qtopprojectionitems = OPTv2Projectiond::where('PERNR',$pernr)
                        ->limit(20)
                        ->selectRaw("
                            EAN11,
                            MAX(id) as id,
                            MAX(DESCRIPTION) as DESCRIPTION,
                            MAX(AUTHOR) as AUTHOR,
                            MAX(COPYRIGHT) as COPYRIGHT,
                            MAX(UNITP) as UNITP
                        ")
                        ->orderByRaw('id DESC')
                        ->groupBy('EAN11')
                        ->get();
            
            if($qtopprojectionitems->isEmpty()) {

                $response[] = array(
                    "num" =>  '0',
                    "description" =>  'No records found.'
                );

            } else {

                $numm = 0;
                foreach ($qtopprojectionitems as $projd) {

                    $numm++;
                    $isbn = $projd->EAN11;
                    $description = strtoupper($projd->DESCRIPTION);

                    // Filter only when NOT showall
                    if (empty($showall) && !in_array($isbn, $existingsapcodeactiveitems)) {
                        continue;
                    }

                    $isbnlist[] = $isbn;

                    $nan = "Not Found";
                    $author = $projd->AUTHOR;
                    $copyright = $projd->COPYRIGHT;

                    $unitprice = $projd->UNITP;
                    $isbnunitpriceclean = str_replace(',', '', $unitprice);
                    $unitpriceDisplay = number_format($unitprice);

                    $discount = 0;
                    $finalunitprice = $unitprice;

                    $titleDisplay = truncatelimitWords($description, 27);
                    $copyrightDisplay = empty($copyright) || $copyright == ' ' ? '-' : $copyright;
                    
                    $response[] = [
                        "num" => $numm,
                        "description" => $description,
                        "descriptionDisplay" => $titleDisplay,
                        "isbn" => $isbn,
                        "copyright" => $copyrightDisplay,
                        "author" => $author,
                        "label" => '000TEMP',
                        "discount" => $discount,
                        "unitprice" => $unitprice,
                        "isbnunitpriceclean" => $isbnunitpriceclean,
                        "unitpriceDisplay" => $unitpriceDisplay,
                        "customercode" => $customercode,
                        "finalunitprice" => $finalunitprice,
                        "total1" => 0,
                        "total2" => 0,
                        "total3" => 0,
                    ];

                }
          

            }

        }
//------------------------------


        if(!$qmatdel->isEmpty() || !$qupdatepushlistisbn->isEmpty()){


                        
                foreach ($qmatdel as $mat) {

                    $num++;
                    $isbn = $mat->EAN11;
                    $description = strtoupper($mat->MAKTX);

                    // Filter only when NOT showall
                    if (empty($showall) && !in_array($isbn, $existingsapcodeactiveitems)) {
                        continue;
                    }

                    $isbnlist[] = $isbn;

                    $nan = "Not Found";
                    $author = trim($mat->ZZAUTHOR1 . " " . $mat->ZZAUTHOR2 . " " . $mat->ZZAUTHOR2);
                    $copyright = $mat->ZZCOPYRIGHT;

                    $unitprice = $mat->KBETRCE;
                    $isbnunitpriceclean = str_replace(',', '', $unitprice);
                    $unitpriceDisplay = number_format($unitprice);

                    $discount = 0;
                    $finalunitprice = $unitprice;

                    $titleDisplay = truncatelimitWords($description, 27);
                    $copyrightDisplay = empty($copyright) || $copyright == ' ' ? '-' : $copyright;

                    if ($mat->MAKTX) {
                        $response[] = [
                            "num" => $num,
                            "description" => $description,
                            "descriptionDisplay" => $titleDisplay,
                            "isbn" => $isbn,
                            "copyright" => $copyrightDisplay,
                            "author" => $author,
                            "label" => $mat->MATNR,
                            "discount" => $discount,
                            "unitprice" => $unitprice,
                            "isbnunitpriceclean" => $isbnunitpriceclean,
                            "unitpriceDisplay" => $unitpriceDisplay,
                            "customercode" => $customercode,
                            "finalunitprice" => $finalunitprice,
                            "total1" => 0,
                            "total2" => 0,
                            "total3" => 0,
                        ];
                    }
                }

                foreach ($qupdatepushlistisbn as $rpushisbn) {

                    $num1++;
                    $isbn = $rpushisbn->EAN11;

                    // Filter only when NOT showall
                        // if (empty($showall) && !in_array($isbn, $existingsapcodeactiveitems)) {
                        //     continue;
                        // }

                    $description = strtoupper($rpushisbn->DESCRIPTION);

                    $isbnlist[] = $isbn;

                    $nan = "Not Found";
                    $author = 'TEMP';
                    $copyright = 'TEMP';

                    $unitprice = 0;
                    $isbnunitpriceclean = str_replace(',', '', $unitprice);
                    $unitpriceDisplay = number_format($unitprice);

                    $discount = 0;
                    $finalunitprice = 0;

                    $titleDisplay = truncatelimitWords($description, 27);
                    $copyrightDisplay = '';

                    $response[] = [
                        "num" => $num1,
                        "description" => $description,
                        "descriptionDisplay" => $titleDisplay,
                        "isbn" => $isbn,
                        "copyright" => $copyrightDisplay,
                        "author" => $author,
                        "label" => '000TEMP',
                        "discount" => $discount,
                        "unitprice" => $unitprice,
                        "isbnunitpriceclean" => $isbnunitpriceclean,
                        "unitpriceDisplay" => $unitpriceDisplay,
                        "customercode" => $customercode,
                        "finalunitprice" => $finalunitprice,
                        "total1" => 0,
                        "total2" => 0,
                        "total3" => 0,
                    ];
                }

            if(empty($response)){
                                    
                    $response[] = array(
                        "num" =>  '0',
                        "description" =>  'No records found.'
                    );

            }

 //customer isbn sales history           
            $pernrCustomerIsbnPrev3YearSalesHistory = pernrCustomerIsbnPrev3YearSalesHistory($customercode,$isbnlist);
            $salesisbnMap = [];    

            if($pernrCustomerIsbnPrev3YearSalesHistory->isEmpty()){
         
            }
            else {

                foreach ($pernrCustomerIsbnPrev3YearSalesHistory as $aaa){

                    $isbnhistory = $aaa->isbn;
                    $totalPrev1 = $aaa->total_1 ?? 0;
                    $totalPrev2 = $aaa->total_2 ?? 0;
                    $totalPrev3 = $aaa->total_3 ?? 0;

                    $totalPrev1Display = number_format($totalPrev1);
                    $totalPrev2Display = number_format($totalPrev2);
                    $totalPrev3Display = number_format($totalPrev3);

                    $salesisbnMap[$isbnhistory] = [
                        "total1" =>  $totalPrev1Display,
                        "total2" =>  $totalPrev2Display,
                        "total3" =>  $totalPrev3Display,
                    ];
                }

        
                
                foreach($response as &$res) {

                    $total1 = 0;
                    $total2 = 0;
                    $total3 = 0;
                    if(isset($res["isbn"]) && isset($salesisbnMap[$res['isbn']])) {

                        $total1 = $salesisbnMap[$res["isbn"]]["total1"];
                        $total2 = $salesisbnMap[$res["isbn"]]["total2"];
                        $total3 = $salesisbnMap[$res["isbn"]]["total3"];

                    }

                    $res['total1'] = $total1;
                    $res['total2'] = $total2;
                    $res['total3'] = $total3;
                }
                unset($res);

            }
//---------------

        

        }
        
     
      
        
        if(empty($response)){
                                    
                $response[] = array(
                    "num" =>  '0',
                    "description" =>  'No records found.'
                );

        }
        

        return response()->json($response);

    }

    public function datatable_update_push_list_existing_isbn_list(Request $request) {
        
        $publisher = trim((string) $request->query('publisher', ''));
        $title     = trim((string) $request->query('title', ''));
    
        
        $_qmatdel = ZmmMatdel::select('*')
                            ->where('ZZLONGTEXT','NOT LIKE', '%Deactivate%')
                            ->limit(250)           
                    ;
 
        if($publisher !== '') {
            $_qmatdel->whereRaw("LOWER(ZZPUBLISHER1) LIKE LOWER(?)", ['%' . $publisher . '%']);

        }

        if($title !== '') {

            $_qmatdel->where(function ($query) use ($title) {
                                
                $query->where('EAN11', 'LIKE', '%' . $title . '%')
                ->orWhereRaw("LOWER(ZZLONGTEXT) LIKE LOWER(?)", ['%' . $title . '%'])
                ->orWhereRaw("LOWER(ZZAUTHOR1) LIKE LOWER(?)", ['%' . $title . '%'])
                ;

                    // ->orWhere('ZZLONGTEXT', 'LIKE', '%' . $search . '%')
                    // ->orWhere('ZZAUTHOR1', 'LIKE', '%' . $search . '%');
            });

        }

        $num = 0;
        $isbnlist = [];

        $qmatdel = $_qmatdel->get();

        if(!$qmatdel->isEmpty()){

            foreach ($qmatdel as $mat) {
            
                $num++; 
                $isbn = $mat->EAN11;

                $description = strtoupper($mat->MAKTX);

                    $isbnlist[] = $isbn;
                    $nan = "Not Found";
                    $author = $mat->ZZAUTHOR1  . " " . $mat->ZZAUTHOR2 . " " . $mat->ZZAUTHOR2;
                    $copyright = $mat->ZZCOPYRIGHT;
                    
                    $matnr = $mat->MATNR;
                    $unitprice = $mat->KBETRCE;
                    $isbnunitpriceclean = str_replace(',','',$unitprice);
                    $unitpriceDisplay = number_format($unitprice);
                    // $discount = item_discount($customercode,$isbn);
                    $discount = 0;
                    // $finalunitprice = discounted_unit_price($customercode,$isbn,$unitprice);
                    $finalunitprice = $unitprice;
                    $titleDisplay = truncatelimitWords($description,27);
                    $copyrightDisplay = empty($copyright) || $copyright == ' ' ? '-' : $copyright;
                    if($mat->MAKTX) {
        
                            $response[] = array(
                                "num" => $num,
                                "description" =>  $description,
                                "descriptionDisplay" =>  $titleDisplay,
                                "matnr" =>  $matnr,
                                "isbn" =>  $isbn,
                                "copyright" =>  $copyrightDisplay,
                                "author" =>  $author,
                                "label" =>  $mat->MATNR,
                                "discount" =>  $discount,
                                "unitprice" =>  $unitprice,
                                "isbnunitpriceclean" =>  $isbnunitpriceclean,
                                "unitpriceDisplay" =>  $unitpriceDisplay,
                                "finalunitprice" =>  $finalunitprice,
                                "status" => '',
                                "dateupdate" => '',
                            );
                    }


            }

             $activeisbn = PushListTagging::whereIn('isbn',$isbnlist)
                                        ->get();
              

              $statusMap = [];
              foreach($activeisbn as $r){
                    $isbn_active = trim($r->isbn);
                    $status_active = $r->status;
                    $datetagged_active = $r->DateTagged;
                    
                    $statusMap['status'][$isbn_active] = $status_active;
                    $statusMap['datetag'][$isbn_active] = $datetagged_active;

              }

              foreach($response as &$res) {
                    $responseisbn = trim($res['isbn']);
                    $responsematnr = trim($res['matnr']);
                    $status = $statusMap['status'][$responseisbn] ?? 0;
                    $checked = $status == 1 ? 'checked' : '';
                    $statusDisplay = '

                                <div class="mb-0 form-switch h5 d-flex justify-content-center">
                                    <input class="form-check-input update_status_existing_pushlistisbn" id="flexSwitchCheckChecked" data-isbn="'.$responseisbn.'" data-matnr="'.$responsematnr.'" value="'.$status.'" '.$checked.' type="checkbox" >
                                </div>

                    ';

                    $datetag = $statusMap['datetag'][$responseisbn] ?? '';
                    $dateupdate = $datetag !== '' ? formatDate($datetag,'mdyts') : '-';
                    $dateupdateDisplay ="<span class='fs--2'>".$dateupdate."</span>";

                    $res['status'] = $statusDisplay;
                    $res['dateupdate'] = $dateupdateDisplay;

              }
              unset($res);


        }
        
     
        if(empty($response)){
                                    
            $response = [
                      "num" =>  '0',
                      "description" =>  'No records found.'

                    ]          
            ;

        }
        

            

        return response()->json($response);

    }

    public function get_projectionid_details(Request $request) {

        $pid = $request->query('id');
        $r =  OPTv2ProjectionPeriod::where('DOCNUM',$pid)
                                    ->first();


        $projectionid = $r->PROJECTIONID;
        $docnum = $r->DOCNUM;
        $period = $r->PERIOD;
        $supplemental = $r->SUPPLEMENTAL;
        $startdate = $r->STARTDATE;
        $enddate = $r->ENDDATE;
        $schoolyear = $r->YEAR;
        $schoollevel = $r->LEVEL;
        $staff = $r->USERCREATE;
        $linenum = $r->LINENUM;
        $remarks = $r->REMARKS;
        $projectiontype = $r->PROJECTIONTYPE;

        $projectionidDisplay =  'ID: ' . $projectionid . ' | Period: ' . $period;

            $response[] = array(
                "projectionid" => $projectionid,
                "docnum" => $docnum,
                "projectioniddisplay" => $projectionidDisplay,
                "period"  =>  $period, 
                "supplemental"  =>  $supplemental, 
                "startdate" =>  $startdate,
                "enddate"  => $enddate,
                "year" =>  $schoolyear,
                "level" =>  $schoollevel,
                "staff" =>  $staff,
                "linenum"  => $linenum,
                "remarks"  => $remarks,
                "projectiontype"  => $projectiontype,

            );
            
        
        return response()->json($response);

    }

    public function get_pernr_customer (Request $request) {

        $pernr = $request->query('pernr');
        $username = session('user_staff');
        $includecustomerlinkto = includeCustomerLinkTo($pernr);
        $excludecustomerlinkfrom = excludeCustomerLinkFrom($pernr);

        $ff = CrmMotherLookup::where('AE',$pernr)
        ->whereNotIn('CUSTNO',$excludecustomerlinkfrom)
        ->orWhereIn('CUSTNO',$includecustomerlinkto)
        ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
        ->orderByRaw('CUSTNAME','ASC')
        ->selectRaw('
                          MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, 
                          MAX(RTRIM(CUSTNO)) as CUSTNO, 
                          MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, 
                          MAX(AE) as AE, 
                          MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
                       
                          '
                          )
        ->groupBy('t2.CUSTNO')
        ->get();

     


        if($ff->isEmpty()) {
            
            $response = [
                'num' => '0'
            ];

        } else {
        
            $kunnr = [];
            $num = 0;
                foreach ($ff as $c ) {
                    $num++;
                    $customercode = $c->CUSTNO;
                    $customername = $c->CUSTNAME;
                    $dept = $c->DEPARTMENT;
                    $motheracct = $c->MOTHERACCT;
                    $ae = $c->AE;

                    $kunnr[] = $customercode;
                    
                    $deptDisplay = $dept !== '-' ? ' - ' . $dept : '';
                    $customernameDisplay = '<span class="line-clamp-1" title="'.$customername . $deptDisplay.'"> '.$customername . $deptDisplay.' </span>';  

                    $action = '';
                    $check = '';

                    $response[] = array(
                        "num" => $num,
                        "customercode" => $customercode,
                        "customername" => $customernameDisplay ,
                        "dept" => $dept ,
                        "motheracct" => $motheracct ,
                        "ae" => $ae,
                        "check" => $check,
                        "action" => '',
                        "actionlink" => '',
                        "total1" => '',
                        "total2" => '',
                        "total3" => '',
                    );

                }

                        
                 // Fetch sales history and build a map based on customer code
                    $customersaleshistory = customerPrev3YearSalesHistory($kunnr);
                    $salesMap = [];

//map first we are doing this for faster load this is alternate for every item row query get sales history
                    foreach ($customersaleshistory as $custr) {

                        $total1Display = number_format($custr->total_1);
                        $total2Display = number_format($custr->total_2);
                        $total3Display = number_format($custr->total_3);

                        $salesMap[$custr->cust_code] = [
                            
                            'total1' => $total1Display,
                            'total2' => $total2Display,
                            'total3' => $total3Display,
                        ];
                    }
//-------

// Inject the totals into the response. get everything in json by using $res because it is joined
                    foreach ($response as &$res) {

                        $total1 = '0';
                        $total2 = '0';
                        $total3 = '0';
                    
                        if (isset($res['customercode']) && isset($salesMap[$res['customercode']])) {
                            $total1 = $salesMap[$res['customercode']]['total1'];
                            $total2 = $salesMap[$res['customercode']]['total2'];
                            $total3 = $salesMap[$res['customercode']]['total3'];
                        }
                    
        // Populate into response
                        $res['total1'] = $total1;
                        $res['total2'] = $total2;
                        $res['total3'] = $total3;

                        $escapedCustomerName = htmlspecialchars($res['customername'], ENT_QUOTES, 'UTF-8');
                    
    // Rebuild action with totals, always
                        $res['action'] = ' <a class="fw-bold btn btn-sm p-1 btn-primary create_projection_add_new_customer" 
                                            data-total1="'.$total1.'" 
                                            data-total2="'.$total2.'" 
                                            data-total3="'.$total3.'" 
                                            data-customercode="'.$res['customercode'].'"
                                            data-customername="' . $escapedCustomerName . '
                                            
                                            
                                            ">
                                     Add
                                </a>';

                        $res['actionlink'] = ' <a class="fw-bold btn btn-sm p-1 btn-primary add_new_link_customer" 
                                                data-total1="'.$total1.'" 
                                                data-total2="'.$total2.'" 
                                                data-total3="'.$total3.'" 
                                                data-customercode="'.$res['customercode'].'"
                                                data-customername="' . $escapedCustomerName . '
                                                
                                                
                                                ">
                                            Link
                                    </a>';
                    }
                    unset($res);
//------
            
        }


        return response()->json($response);

  }

  
  public function return_projection_isbn(Request $request) {

    $id = $request->input('id');
    $projdocnum = $request->query('projdocnum');
    $remarks = $request->input('return_projection_reason') ?? '';
    $pernr = session('pernr');
    $qpernrDetails = userDetails($pernr);
    $pernrfullname = $qpernrDetails->FULLNAME;
    $date_now = date_now();
    $query = OPTv2Projectiond::where('id',$id);
    
    $update = $query->update([
            "STATUS" => 'returned_isbn',
            "RETURNREMARKS" => $remarks,
            "RETURNBY" => $pernrfullname,
            "SUBMIT" => null,
            "APPROVED1" => null,
            "APPROVEDBY1" => null,
            "QTYAPPROVED1" => null,
            "DATEAPPROVED1" => null,
    ]);

    $html = "";
    $status = "404";
    
    if($update) {
       
        $row = $query->first();
        $projtnpernr = $row->USERID;
        $projtndocnum = $row->DOCNUM;

        
       $createLogs = OPTv2Logs::create([
                "REFERENCE" => $projtndocnum,
                "REMARKS" => $remarks,
                "USERID" => $pernr,
                "DOCDATE" => $date_now,
                "LOGTYPE" => 'returnisbn'
        ]);

        $query = OPTv2Projectionh::where('DOCNUM',$projtndocnum)
                    ->update([
                        "HASRETURN" => '1'
                    ]);

        if($createLogs) {
            $status = "2";

        

        } 
        else {
            $status = "404";
        }

    }

    $response[] = array( 
        "status" => $status,
        "html" => $html
    );

    return response()->json($response);
}



public function approve_projection_isbn(Request $request) {

    $staff = session('user_staff');
    $approverPernr = session('pernr');
    $id = $request->input('id');
    $approveQty = $request->input('approveQty');
    $unitp = $request->input('unitp');
    $username = $request->query('username');
    $projdocnum = $request->query('projdocnum');
    $date_now_full = date_now();
    $date_now = date_now('dateonly');


    $rsm_qty = $approveQty;
    $linetotal = $rsm_qty * $unitp;

    $status = 404;
    $html = '';
        
    // dd($id);
    $updateprojectiond = OPTv2Projectiond::where('id',$id)
                ->update([
                    // "STATUS" => 'for_ssm_approval',
                    "STATUS" => 'for_ssm_approval',
                    "APPROVED1" => '1',
                    "APPROVEDBY1" => $approverPernr,
                    "QTYAPPROVED1" => $rsm_qty,
                    "DATEAPPROVED1" => $date_now,
                    "LINETOTAL" => $linetotal,
                    "QTY" => $rsm_qty,
                ]);
                
        if($updateprojectiond) {
            $status = 2;

            $div = session('division');

            if (strpos(trim($div), 'BED') !== false) {

                $projectionRows = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                ->where('t1.id',$id)
                ->selectRaw('   
                        t1.*,
                        (SELECT TOP 1 CUSTOMER FROM OPTV2PROJECTIONH t2 WHERE t1.DOCNUM = t2.DOCNUM ) as CUSTOMER'
                )
                ->first();

                if (empty($projectionRows)) {
                    $status = 403;
                } else {
                    try {

                        DB::transaction(function () use ($projectionRows, $approverPernr, $date_now) {

                            $row = $projectionRows;

                                OPTv2Projectiond::where('id', $row->id)
                                        ->update([
                                            "STATUS" => 'approved',
                                            "APPROVED" => '1',
                                            "DATEAPPROVED" => $date_now,
                                            "APPROVED2" => '1',
                                            "APPROVEDBY2" => $approverPernr,
                                            "QTYAPPROVED2" => $row->QTY,
                                            "DATEAPPROVED2" => $date_now,
                                        ]);

                                $pernr = trim($row->PERNR);
                                $basedocnum = $row->BASEDOCNUM;

                                $quserdetails = userDetails($pernr);
                                $qprojectiondetails = projection_period_details($basedocnum);

                                $school_year = $qprojectiondetails->YEAR ?? null;

                                $isbn = $row->EAN11;
                                $matnr = $row->MATNR;
                                $customercode = $row->CUSTOMER;
                                $bsa = $row->BSA;
                                $population = $row->POPULATION;
                                $qty = $row->QTY;

                                $rsm = $quserdetails->RSM ?? null;
                                $ssm = $quserdetails->SSM ?? null;

                                $pernrleadzeros = ltrim(trim($pernr), '0');
                                $rsmleadzeros = ltrim(trim((string)$rsm), '0');
                                $ssmleadzeros = ltrim(trim((string)$ssm), '0');

                                $qtybsa = $bsa == '1' ? $qty : 0;
                                $qtynbsa = $bsa != '1' ? $qty : 0;

                                CrmProjection::create([
                                    "idno" => $pernrleadzeros,
                                    "school_id" => $customercode,
                                    "isbn" => $isbn,
                                    "matnr" => $matnr,
                                    "school_year" => $school_year,
                                    "population" => $population,
                                    "projection_bsa" => $qtybsa,
                                    "projection_con" => $qtynbsa,
                                    "status" => '3',
                                    "remarks" => 'Projection Approved',
                                    "rsm" => $rsmleadzeros,
                                    "ssm" => $ssmleadzeros,
                                    "batchid" => $basedocnum,
                                ]);
                            
                        });

                        $status = 2;

                    } catch (\Exception $e) {
                        $status = 500;
                        $html = $e->getMessage();
                    }
                }
            }

  

        }
        
        else {
            $status = 404;

        }



    $response = array(
        'status' => $status,
        'html' => $html
    );
                            
    return response()->json($response);


}


  public function submit_allocate_qty(Request $request) {

    $staff = session('user_staff');
    $pernr = session('pernr');
    $date_now_full = date_now();
    $date_now = date_now('dateonly');

    $stockallocate_matnr_input = $request->input('matnr');
    $stockallocate_isbn_input = $request->input('isbn');
    $stockallocate_stock_allocate_qty_input = $request->input('stock_allocate_qty');
    $stockallocate_alloctype_input = $request->input('alloctype');
    $stockallocate_basedocnum_input = $request->input('basedocnum');
    $stockallocate_pernr_input = $request->input('pernr');
    $stockallocate_projqty_input = $request->input('projqty');

    $qprojperiod = projection_period_details($stockallocate_basedocnum_input);

    $projectionid = $qprojperiod->PROJECTIONID;
    $basedocnum = $stockallocate_basedocnum_input;

    $noUpdate = true;
    $html = '';
    $status = 404;

    $updateprojd = [];
    $insertAllocationHeader = [];
    $insertAllocated = [];
    $isbnlist = [];

    // $max = max(
    //     count($stockallocate_matnr_input),
    //     count($stockallocate_isbn_input),
    //     count($stockallocate_stock_allocate_qty_input),
    //     count($stockallocate_alloctype_input),
    //     count($stockallocate_basedocnum_input),
    //     count($stockallocate_pernr_input),
    //     count($stockallocate_projqty_input)
    // );
    
    // $rows = [];
    
    // for ($x = 0; $x < $max; $x++) {
    //     $rows[] = [
    //         'pos' => $x,
    //         'matnr' => $stockallocate_matnr_input[$x] ?? 'WALA',
    //         'isbn' => $stockallocate_isbn_input[$x] ?? 'WALA',
    //         'stock_allocate_qty' => $stockallocate_stock_allocate_qty_input[$x] ?? 'WALA',
    //         'alloctype' => $stockallocate_alloctype_input[$x] ?? 'WALA',
    //         'basedocnum' => $stockallocate_basedocnum_input[$x] ?? 'WALA',
    //         'pernr' => $stockallocate_pernr_input[$x] ?? 'WALA',
    //         'projqty' => $stockallocate_projqty_input[$x] ?? 'WALA',
    //     ];
    // }
    
    // dd($rows);
    
    // dd($basedocnum);

    if(!empty($stockallocate_isbn_input) && is_array($stockallocate_isbn_input)) {

   

        foreach($stockallocate_isbn_input as $a => $i) {
        

            $matnr = $stockallocate_matnr_input[$a];
            $isbn = $stockallocate_isbn_input[$a];
            $stock_allocate_qty = $stockallocate_stock_allocate_qty_input[$a];
            $alloctype = $stockallocate_alloctype_input[$a];
      
            $pernr = $stockallocate_pernr_input[$a];
            $projqty = $stockallocate_projqty_input[$a];
         
            $pernrleadzeros = ltrim(trim($pernr), '0');

            if($stock_allocate_qty > 0) {

                // $qModify = modifyAllocated($isbn,$pernr, $basedocnum,$alloctype,$stock_allocate_qty,'add',$projqty);

                // if($qModify == 2){
    
                    $noUpdate = false;

               
                    $isbnlist[] = $isbn;

                    // $updateprojd[] = [

                  
                    //     "EAN11" => $isbn,
                    //     "BASEDOCNUM" => $basedocnum,
                    //     "MATNR" => $matnr,
                    //     "DATEALLOCATED" => $date_now,
                    // ];


                    $alloctypeFinal = $alloctype;
                    if($alloctype == 'nonbsa') {
                        $alloctypeFinal = 'nbsa';

                    }
                         // check muna kung existing sa OPTv2Allocated
                         $existsAllocated = OPTv2Allocated::where('EAN11', $isbn)
                         ->where('ALLOCTYPE', $alloctype)
                         ->where('PERNR', $pernr)
                         ->where('BASEDOCNUM', $basedocnum)
                         ->exists();
     
                     if (!$existsAllocated) {
                         $insertAllocated[] = [
                             "MATNR" => $matnr,
                             "TEMPEAN11" => $isbn,
                             "EAN11" => $isbn,
                             "BASEDOCNUM" => $basedocnum,
                             "PROJECTIONID" => $projectionid,
                             "ALLOCTYPE" => $alloctype,
                             "ALLOCATED" => $stock_allocate_qty,
                             "QTY" => $stock_allocate_qty,
                             "PERNR" => $pernr,
                             "PROJECTION" => $projqty,
                             "created_at" => $date_now_full,
                             "updated_at" => $date_now_full
                         ];
                     }
     
     
                        // check muna kung existing sa CrmAllocationHeader
                     $existsHeader = CrmAllocationHeader::where('isbn', $isbn)
                     ->where('aType', $alloctypeFinal)
                     ->where('aAE', $pernrleadzeros)
                     ->where('aBatch', $basedocnum)
                     ->exists();
     
                     if (!$existsHeader) {
                         $insertAllocationHeader[] = [
                             "isbn" => $isbn,
                             "aActual" => 0,
                             "aType" => $alloctypeFinal,
                             "aAE" => $pernrleadzeros,
                             "aBatch" => $basedocnum,
                             "matnr" => $matnr,
                             "aQty" => $stock_allocate_qty,
                             "DateAdded" => $date_now_full,
                             "DateUpdated" => $date_now_full,
                             "prsCode" => '-',
                             "prCode" => '-',
                             "poCode" => '-',
                         ];
                     }
    
    
                // }
                
            }
  
            
        }

       
        
    } 

    if(!$noUpdate) {
        
        $status = 2;



        // $updateprojd = collect($updateprojd)
        //                     ->unique(fn($item) => $item['EAN11'].'-'.$item['PERNR'].'-'.$item['BASEDOCNUM'])
        //                     ->values()
        //                     ->toArray();

        if(!empty($insertAllocated)) {


            OPTv2Projectiond::whereIn('EAN11',$isbnlist)
                            ->where('BASEDOCNUM',$basedocnum)
                            ->update([
                                    "DATEALLOCATED" => $date_now_full
                                ])
                            ;

            // OPTv2Projectiond::upsert($updateprojd,
            //                             [
            //                                  "EAN11",
            //                                  "BASEDOCNUM",
            //                             ],
            //                                 [
                                              
            //                                 ]
            //                             );

            OPTv2Allocated::insert($insertAllocated);
            CrmAllocationHeader::insert($insertAllocationHeader);

        }
     

  
    }

    $response = array(
        "status" => $status,
        'html' => $html
    );

    return response()->json($response);
  }
  
//   public function submit_approve_projection(Request $request) {

//     $staff = session('user_staff');
//     $pernr = session('pernr');
//     $username = $request->query('username');
//     $projdocnum = $request->query('projdocnum');
//     $date_now_full = date_now();
//     $date_now = date_now('dateonly');


//     $isbn_approve_input = $request->input('forapproval_projection_isbn_approve');
//     $docnum_input = $request->input('forapproval_projection_docnum');
//     $branchwhouse_input = $request->input('forapproval_projection_branchwhouse');
//     $customercode_input = $request->input('forapproval_projection_customercode');
//     $isbn_input = $request->input('forapproval_projection_isbn');
//     $rsm_qty_input = $request->input('forapproval_projection_isbn_rsm_qty');
//     $linetotal_input = $request->input('forapproval_projection_isbn_linetotal');

//     $status = 404;
//     $html = '';
    
//     // $isbnapproves = [];
//     $docnumapproves = [];

//     // dd($docnum_input);
//     $updatesuccess = false;
//     $notemptyapprove = false;
    
//     if(!empty($docnum_input) && is_array($docnum_input)) {

//         foreach ($docnum_input as $i => $cc){

//             $docnumapproves[] = $docnum_input[$i] ?? null;
//             // $isbnapproves[] = $isbn_input[$i] ?? null;

//             $docnum = $docnum_input[$i] ?? null;
//             $customercode = $customercode_input[$i] ?? null;
//             $linetotal = $linetotal_input[$i] ?? null;
//             $rsm_qty = $rsm_qty_input[$i] ?? null;
//             $isbn = $isbn_input[$i] ?? null;
//             $isbn_approve = $isbn_approve_input[$i] ?? null;

//             if($isbn_approve == '1') {

//                 $notemptyapprove = true;

//                 $updateprojectiond = OPTv2Projectiond::where('DOCNUM',$docnum)
//                                 ->where('EAN11',$isbn)
//                                 ->update([
//                                     "STATUS" => 'for_ssm_approval',
                                    
//                                     "APPROVED1" => '1',
//                                     "APPROVEDBY1" => $pernr,
//                                     "QTYAPPROVED1" => $rsm_qty,
//                                     "DATEAPPROVED1" => $date_now,
//                                     "LINETOTAL" => $linetotal,
//                                     "QTY" => $rsm_qty,
//                                 ]);

//                 // $updateprojectionh = OPTv2Projectionh::where('DOCNUM',$docnum)
//                 //                 ->update([
//                 //                     "STATUS" => 'for_ssm_approval',
//                 //                     "DONEAPPROVER1" => '1',
//                 //                     "APPROVER1" => $pernr,
//                 //                     "DATEAPPROVER1" => $date_now,
//                 //                 ]);

//                 if($updateprojectiond) {
//                 $updatesuccess = true;

//                 }

//             }

       
 
//         }


//         if(!$notemptyapprove) {
//             $status = 403;

//         }
//         else if ($updatesuccess) {
//             $status = 2;

//             // $updatedisapproveprojectiond = OPTv2Projectiond::where('BASEDOCNUM',$projdocnum)
//             //                                                 ->where('USERNAME',$username)
//             //                                                 ->whereNull('APPROVED1')
//             //                                                 ->update([
//             //                                                     "QTY" => '0',
//             //                                                     "LINETOTAL" => '0',
//             //                                                     
//             //                                                     "DISAPPROVED" => '1',
//             //                                                     "DATEDISAPPROVED" => $date_now,
//             //                                                 ]);

//             // $updatedisapproveprojectionh = OPTv2Projectionh::where('BASEDOCNUM',$projdocnum)
//             //                                                 ->where('USERNAME',$username)
//             //                                                 ->whereNull('APPROVER1')
//             //                                                     ->update([
//             //                                                         "DISAPPROVED" => '1',
//             //                                                         "DONEAPPROVER1" => '1',
//             //                                                     ]);

            
//         } else {
//             $status = 404;

//         }

//     }

//     else {
//         $status = 410;
//     }


//     $response = array(
//         'status' => $status,
//         'html' => $html
//     );
                            
//     return response()->json($response);


    
// }

public function approve_projection_final_isbn(Request $request) {

    $staff = session('user_staff');
    $pernr = session('pernr');
    $id = $request->input('id');
    $approveQty = $request->input('approveQty');
    $unitp = $request->input('unitp');
    $username = $request->query('username');
    $projdocnum = $request->query('projdocnum');
    $date_now_full = date_now();
    $date_now = date_now('dateonly');


    $approve_qty = $approveQty;
    $linetotal = $approve_qty * $unitp;




    $status = 404;
    $html = '';
        
    // dd($id);

    $updateprojectiond = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1') 
                                ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM') 
                                ->select('t1.*','BSA','CUSTOMER')
                                ->where('t1.id',$id)
                                ->first();


    $qupdateprojectiond = OPTv2Projectiond::where('id',$id)
                                    ->update([
                                "STATUS" => 'approved',
                                "APPROVED" => '1',
                                "DATEAPPROVED" => $date_now,
                                "APPROVED2" => '1',
                                "APPROVEDBY2" => $pernr,
                                "QTYAPPROVED2" => $approve_qty,
                                "DATEAPPROVED2" => $date_now,
                                "LINETOTAL" => $linetotal,
                                "QTY" => $approve_qty,
                            ]);

                
        if($qupdateprojectiond) {
            $status = 2;


         
            
            $pernr = trim($updateprojectiond->PERNR);

            $quserdetails = userDetails($pernr);

            $basedocnum = $updateprojectiond->BASEDOCNUM;


            $qprojectiondetails = projection_period_details($basedocnum);
            $school_year = $qprojectiondetails->YEAR;

            $pernrrsm = $updateprojectiond->PERNR;
            $pernrssmsm = $updateprojectiond->PERNR;
            $isbn = $updateprojectiond->EAN11;
            $matnr = $updateprojectiond->MATNR;
            $customercode = $updateprojectiond->CUSTOMER;
            $bsa = $updateprojectiond->BSA;
            $population = $updateprojectiond->POPULATION;
            $qty = $updateprojectiond->QTY;
            $rsm = $quserdetails->RSM;
            $ssm = $quserdetails->SSM;
         
  
            $pernrleadzeros = ltrim(trim($pernr), '0');
            $rsmleadzeros = ltrim(trim($rsm), '0');
            $ssmleadzeros = ltrim(trim($ssm), '0');

            $qtybsa = $bsa == '1' ? $qty : 0;
            $qtynbsa = $bsa !== '1' ? $qty : 0;

                $qcreatecrmprojection = CrmProjection::create([
                        "idno" => $pernrleadzeros, 
                        "school_id" => $customercode, 
                        "isbn" => $isbn, 
                        "matnr" => $matnr, 
                        "school_year" => $school_year, 
                        "population" => $population, 
                        "projection_bsa" => $qtybsa, 
                        "projection_con" => $qtynbsa, 
                        "status" => '3', 
                        "remarks" => 'Projection Approved', 
                        "rsm" => $rsmleadzeros, 
                        "ssm" => $ssmleadzeros, 
                        "batchid" => $basedocnum, 
                ]);

        }
        
        else {
            $status = 404;

        }



    $response = array(
        'status' => $status,
        'html' => $html
    );
                            
    return response()->json($response);


}


//   public function submit_approve_projection_final(Request $request) {

//     $staff = session('user_staff');
//     $pernr = session('pernr');
//     $username = $request->query('username');
//     $basedocnum = $request->query('projdocnum');
//     $date_now_full = date_now();
//     $date_now = date_now('dateonly');


//     $docnum_input = $request->input('forapproval_projection_final_docnum');
//     $branchwhouse_input = $request->input('forapproval_projection_final_branchwhouse');
//     $isbn_final_approve_input = $request->input('forapproval_projection_final_isbn_approve');
//     $customercode_input = $request->input('forapproval_projection_final_customercode');
//     $isbn_input = $request->input('forapproval_projection_final_isbn');
//     $approve_qty_input = $request->input('forapproval_projection_final_isbn_ssm_qty');
//     $linetotal_input = $request->input('forapproval_projection_final_isbn_linetotal');

//     $status = 404;
//     $html = '';
    
    
//     $qprojectiondetails = projection_period_details($basedocnum);
//     $school_year = $qprojectiondetails->YEAR;

//     $isbnapproves = [];
//     $docnumapproves = [];
//     $notemptyapprove = false;

//     // dd($docnum_input);
//     $updatesuccess = false;

    
//     if(!empty($docnum_input) && is_array($docnum_input)) {

//         foreach ($docnum_input as $i => $cc){

//             $docnumapproves[] = $docnum_input[$i] ?? null;
//             $isbnapproves[] = $isbn_input[$i] ?? null;

//             $isbn_final_approve = $isbn_final_approve_input[$i];

//             if($isbn_final_approve == '1') {
                
//                     $docnum = $docnum_input[$i] ?? null;
//                     $customercode = $customercode_input[$i] ?? null;
//                     $linetotal = $linetotal_input[$i] ?? null;
//                     $approve_qty = $approve_qty_input[$i] ?? null;
//                     $isbn = $isbn_input[$i] ?? null;
        
        
//                     $notemptyapprove = true;

//                     $updateprojectiond = OPTv2Projectiond::where('DOCNUM',$docnum)
//                                                         ->where('EAN11',$isbn)
//                                                         ->update([
//                                                             "STATUS" => 'approved',
//                                                             "APPROVED" => '1',
//                                                             "DATEAPPROVED" => $date_now,
//                                                             "APPROVED2" => '1',
//                                                             "APPROVEDBY2" => $pernr,
//                                                             "QTYAPPROVED2" => $approve_qty,
//                                                             "DATEAPPROVED2" => $date_now,


//                                                             "LINETOTAL" => $linetotal,
//                                                             "QTY" => $approve_qty,
//                                                         ]);
        
        
//                     if($updateprojectiond) {
//                             $updatesuccess = true;

                                   
                                             
//                             $qprojectiond = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1') 
//                                                         ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM') 
//                                                         ->select('t1.*','BSA','CUSTOMER')
//                                                         ->where('t1.DOCNUM',$docnum)
//                                                         ->where('EAN11',$isbn)
//                                                         ->first();
                        
//                             $pernr = trim($qprojectiond->PERNR);
//                             $bsa = $qprojectiond->BSA;
//                             $matnr = $qprojectiond->MATNR;
//                             $population = $qprojectiond->POPULATION;

//                             $quserdetails = userDetails($pernr);
                        
//                             $rsm = $quserdetails->RSM;
//                             $ssm = $quserdetails->SSM;
   
//                             $pernrleadzeros = ltrim(trim($pernr), '0');
//                             $rsmleadzeros = ltrim(trim($rsm), '0');
//                             $ssmleadzeros = ltrim(trim($ssm), '0');
                
//                             $qtybsa = $bsa == '1' ? $approve_qty : 0;
//                             $qtynbsa = $bsa !== '1' ? $approve_qty : 0;
                
//                                 $qcreatecrmprojection = CrmProjection::create([
//                                         "idno" => $pernrleadzeros, 
//                                         "school_id" => $customercode, 
//                                         "isbn" => $isbn, 
//                                         "matnr" => $matnr, 
//                                         "school_year" => $school_year, 
//                                         "population" => $population, 
//                                         "projection_bsa" => $qtybsa, 
//                                         "projection_con" => $qtynbsa, 
//                                         "status" => '3', 
//                                         "remarks" => 'Projection Approved', 
//                                         "rsm" => $rsmleadzeros, 
//                                         "ssm" => $ssmleadzeros, 
//                                         "batchid" => $basedocnum, 
//                                 ]);
        
//                     }

//             }

   
 
//         }


//         if ($notemptyapprove) {
//             $status = 2;

//             // $updatedisapproveprojectiond = OPTv2Projectiond::where('BASEDOCNUM',$projdocnum)
//             //                                                 ->where('USERNAME',$username)
//             //                                                 ->whereNull('APPROVED2')
//             //                                                 ->update([
//             //                                                     "QTY" => '0',
//             //                                                     "LINETOTAL" => '0',
//             //                                                     "DISAPPROVED" => '1',
//             //                                                     "DATEDISAPPROVED" => $date_now,
//             //                                                 ]);

//             // $updatedisapproveprojectionh = OPTv2Projectionh::where('BASEDOCNUM',$projdocnum)
//             //                                                 ->where('USERNAME',$username)
//             //                                                 ->whereNull('APPROVER2')
//             //                                                     ->update([
//             //                                                         "DISAPPROVED" => '1',
//             //                                                         "DONEAPPROVER2" => '1',
//             //                                                     ]);

            
//         } else {
//             $status = 404;

//         }

//     }

//     else {
//         $status = 410;
//     }


//     $response = array(
//         'status' => $status,
//         'html' => $html
//     );
                            
//     return response()->json($response);


// }

public function submit_approve_projection_final(Request $request)
{
    $staff = session('user_staff');
    $approverPernr = session('pernr');
    $username = $request->query('username');
    $date_now = date_now('dateonly');

    $docnum_input = $request->input('forapproval_projection_final_docnum');

    $status = 404;
    $html = '';

    if (!empty($docnum_input)) {

        $projectionRows = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->whereIn('DOCNUM', (array)$docnum_input)
            ->where('USERNAME', $username)
            ->selectRaw('   
                    t1.*,
                    (SELECT TOP 1 CUSTOMER FROM OPTV2PROJECTIONH t2 WHERE t1.DOCNUM = t2.DOCNUM ) as CUSTOMER'
            )
            ->whereNotNull('APPROVED1')
            ->whereNull('APPROVED2')
            ->get();

        if ($projectionRows->isEmpty()) {
            $status = 403;
        }

        try {

            DB::transaction(function () use ($projectionRows, $approverPernr, $date_now) {

                foreach ($projectionRows as $row) {

                    $row->update([
                        "STATUS" => 'approved',
                        "APPROVED" => '1',
                        "DATEAPPROVED" => $date_now,
                        "APPROVED2" => '1',
                        "APPROVEDBY2" => $approverPernr,
                        "QTYAPPROVED2" => $row->QTY,
                        "DATEAPPROVED2" => $date_now,
                    ]);

                    $pernr = trim($row->PERNR);
                    $basedocnum = $row->BASEDOCNUM;

                    $quserdetails = userDetails($pernr);
                    $qprojectiondetails = projection_period_details($basedocnum);

                    $school_year = $qprojectiondetails->YEAR ?? null;

                    $isbn = $row->EAN11;
                    $matnr = $row->MATNR;
                    $customercode = $row->CUSTOMER;
                    $bsa = $row->BSA;
                    $population = $row->POPULATION;
                    $qty = $row->QTY;

                    $rsm = $quserdetails->RSM ?? null;
                    $ssm = $quserdetails->SSM ?? null;

                    $pernrleadzeros = ltrim(trim($pernr), '0');
                    $rsmleadzeros = ltrim(trim($rsm), '0');
                    $ssmleadzeros = ltrim(trim($ssm), '0');

                    $qtybsa = $bsa == '1' ? $qty : 0;
                    $qtynbsa = $bsa != '1' ? $qty : 0;

                    CrmProjection::create([
                        "idno" => $pernrleadzeros,
                        "school_id" => $customercode,
                        "isbn" => $isbn,
                        "matnr" => $matnr,
                        "school_year" => $school_year,
                        "population" => $population,
                        "projection_bsa" => $qtybsa,
                        "projection_con" => $qtynbsa,
                        "status" => '3',
                        "remarks" => 'Projection Approved',
                        "rsm" => $rsmleadzeros,
                        "ssm" => $ssmleadzeros,
                        "batchid" => $basedocnum,
                    ]);
                }

            });

            $status = 2;

        } catch (\Exception $e) {

            $status = 500;
            $html = $e->getMessage();

        }

    } else {

        $status = 410;

    }

    return response()->json([
        'status' => $status,
        'html' => $html
    ]);
}

public function submit_approve_projection(Request $request)
{
    $staff = session('user_staff');
    $approverPernr = session('pernr');
    $username = $request->query('username');
    $projdocnum = $request->query('projdocnum');
    $date_now_full = date_now();
    $date_now = date_now('dateonly');

    $docnum_input = $request->input('forapproval_projection_docnum');

    $status = 404;
    $html = '';

    if (!empty($docnum_input)) {

        $updateprojectiond = OPTv2Projectiond::whereIn('DOCNUM', (array)$docnum_input)
            ->where('USERNAME', $username)
            ->whereNotNull('SUBMIT')
            ->whereNull('APPROVED1')
            ->update([
                "STATUS" => 'for_ssm_approval',
                "APPROVED1" => '1',
                "APPROVEDBY1" => $approverPernr,
                "QTYAPPROVED1" => DB::raw('QTY'),
                "DATEAPPROVED1" => $date_now,
            ]);

        if ($updateprojectiond) {
            $status = 2;

            $div = session('division');

            if (strpos(trim($div), 'BED') !== false) {

                $projectionRows = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                    ->whereIn('t1.DOCNUM', (array)$docnum_input)
                    ->where('t1.USERNAME', $username)
                    ->whereNotNull('t1.APPROVED1')
                    ->whereNull('t1.APPROVED2')
                    ->selectRaw('
                        t1.*,
                        (SELECT TOP 1 CUSTOMER 
                         FROM OPTV2PROJECTIONH t2 
                         WHERE t1.DOCNUM = t2.DOCNUM) as CUSTOMER
                    ')
                    ->get();

                if ($projectionRows->isEmpty()) {
                    $status = 403;
                } else {
                    try {

                        DB::transaction(function () use ($projectionRows, $approverPernr, $date_now) {

                            foreach ($projectionRows as $row) {

                                $row->update([
                                    "STATUS" => 'approved',
                                    "APPROVED" => '1',
                                    "DATEAPPROVED" => $date_now,
                                    "APPROVED2" => '1',
                                    "APPROVEDBY2" => $approverPernr,
                                    "QTYAPPROVED2" => $row->QTY,
                                    "DATEAPPROVED2" => $date_now,
                                ]);

                                $pernr = trim($row->PERNR);
                                $basedocnum = $row->BASEDOCNUM;

                                $quserdetails = userDetails($pernr);
                                $qprojectiondetails = projection_period_details($basedocnum);

                                $school_year = $qprojectiondetails->YEAR ?? null;

                                $isbn = $row->EAN11;
                                $matnr = $row->MATNR;
                                $customercode = $row->CUSTOMER;
                                $bsa = $row->BSA;
                                $population = $row->POPULATION;
                                $qty = $row->QTY;

                                $rsm = $quserdetails->RSM ?? null;
                                $ssm = $quserdetails->SSM ?? null;

                                $pernrleadzeros = ltrim(trim($pernr), '0');
                                $rsmleadzeros = ltrim(trim((string)$rsm), '0');
                                $ssmleadzeros = ltrim(trim((string)$ssm), '0');

                                $qtybsa = $bsa == '1' ? $qty : 0;
                                $qtynbsa = $bsa != '1' ? $qty : 0;

                                CrmProjection::create([
                                    "idno" => $pernrleadzeros,
                                    "school_id" => $customercode,
                                    "isbn" => $isbn,
                                    "matnr" => $matnr,
                                    "school_year" => $school_year,
                                    "population" => $population,
                                    "projection_bsa" => $qtybsa,
                                    "projection_con" => $qtynbsa,
                                    "status" => '3',
                                    "remarks" => 'Projection Approved',
                                    "rsm" => $rsmleadzeros,
                                    "ssm" => $ssmleadzeros,
                                    "batchid" => $basedocnum,
                                ]);
                            }
                        });

                        $status = 2;

                    } catch (\Exception $e) {
                        $status = 500;
                        $html = $e->getMessage();
                    }
                }
            }

        } else {
            $status = 404;
        }

    } else {
        $status = 410;
    }

    $response = array(
        'status' => $status,
        'html' => $html
    );

    return response()->json($response);
}

// public function submit_approve_projection(Request $request) {

//     $staff = session('user_staff');
//     $pernr = session('pernr');
//     $username = $request->query('username');
//     $projdocnum = $request->query('projdocnum');
//     $date_now_full = date_now();
//     $date_now = date_now('dateonly');


//     $docnum_input = $request->input('forapproval_projection_docnum');

//     $status = 404;
//     $html = '';
    
    
//     if(!empty($docnum_input)) {

//         $updateprojectiond = OPTv2Projectiond::whereIn('DOCNUM',$docnum_input)
//                         ->where('USERNAME',$username)
//                         ->whereNotNull('SUBMIT')
//                         ->whereNull('APPROVED1')
//                         ->update([
//                             "STATUS" => 'for_ssm_approval',
                            
//                             "APPROVED1" => '1',
//                             "APPROVEDBY1" => $pernr,
//                             "QTYAPPROVED1" => DB::raw('QTY'),
//                             "DATEAPPROVED1" => $date_now,
//                         ]);


//         if ($updateprojectiond) {
//             $status = 2;

            
//         } else {
//             $status = 404;

//         }

//     }

//     else {
//         $status = 410;
//     }


//     $response = array(
//         'status' => $status,
//         'html' => $html
//     );
                            
//     return response()->json($response);


    
// }

public function submit_changeprojection_final_approve_qty(Request $request) {


    $docnum = $request->input('docnum');
    $aepernr = $request->input('aepernr');
    $isbn = $request->input('isbn');
    $approve_qty = $request->input('qty');
    $linetotal = $request->input('linetotal');

    $html = '';
    $status = 404;
    
    // dd($aepernr);
    $updateapproveqty = OPTv2Projectiond::where('DOCNUM',$docnum)
                        ->where('EAN11',$isbn)
                        ->where('PERNR',$aepernr)
                        ->update([
                            "QTY" => $approve_qty,
                            "LINETOTAL" => $linetotal,
                        ]);

    if($updateapproveqty) {
        $status = 2;
    }
    $response = array(
        'status' => $status,
        'html' => $html
    );
                            
    return response()->json($response);

  }
  
  public function submit_changeprojection_approve_qty(Request $request) {


    $docnum = $request->input('docnum');
    $aepernr = $request->input('aepernr');
    $isbn = $request->input('isbn');
    $approve_qty = $request->input('qty');
    $linetotal = $request->input('linetotal');

    $html = '';
    $status = 404;
    
    // dd($aepernr);
    $updateapproveqty = OPTv2Projectiond::where('DOCNUM',$docnum)
                        ->where('EAN11',$isbn)
                        ->where('PERNR',$aepernr)
                        ->update([
                            "QTY" => $approve_qty,
                            "LINETOTAL" => $linetotal,
                        ]);

    if($updateapproveqty) {
        $status = 2;
    }
    $response = array(
        'status' => $status,
        'html' => $html
    );
                            
    return response()->json($response);

  }

public function isbn_create_projection(Request $request) {

    $staff = session('user_staff');
    $pernr = session('pernr');
    $date_now_full = date_now();
    $date_now = date_now('dateonly');

    $projdocnum = $request->query('projdocnum');

    $qproj = OPTv2ProjectionPeriod::select('PROJECTIONID','SUPPLEMENTAL')
        ->where('DOCNUM', $projdocnum)
        ->first();

    $projid = $qproj->PROJECTIONID ?? null;
    $projsupplemental = $qproj->SUPPLEMENTAL ?? null;

    $statusdb = 'saved';

    $customercode = $request->input('newisbnprojection_customercode');
    $customername = $request->input('newisbnprojection_customername');
    $branchwhouse = $request->input('newisbnprojection_branchwhouse') ?? null;

    $isbn = $request->input('newisbnprojection_isbn');
    $isbn_title = $request->input('newisbnprojection_isbn_title');
    $isbn_unitp = $request->input('newisbnprojection_isbn_unitp');
    $isbn_disc = $request->input('newisbnprojection_isbn_disc');
    $isbn_population = $request->input('newisbnprojection_isbn_population');
    $isbn_qty = $request->input('newisbnprojection_isbn_qty');
    $isbn_linetotal = $request->input('newisbnprojection_isbn_linetotal');
    $isbn_remarks = $request->input('newisbnprojection_isbn_remarks');

    if (empty($customercode)) {
        return response()->json(['status' => 410, 'html' => '']);
    }

    $status = 404;
    $html = '';

    DB::transaction(function () use (
        &$status, $staff, $pernr, $date_now, $date_now_full,
        $projdocnum, $projid, $projsupplemental, $statusdb,
        $customercode,$customername, $branchwhouse,
        $isbn, $isbn_title, $isbn_unitp, $isbn_disc, $isbn_population, $isbn_qty, $isbn_linetotal, $isbn_remarks
    ) {
        // 1) Customer details (1 query)
        $customerdetails = CrmMotherLookup::where('CUSTNO', $customercode)
            ->leftJoin('prd.CRMMOTHERACT as t2', 'prd.CRMMOTHERLOOKUP.ACCTNO', '=', 't2.MOTHERACCT')
            ->selectRaw('
                MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME,
                MAX(RTRIM(CUSTNO)) as CUSTNO,
                MAX(RTRIM(MOTHERACCT)) as MOTHERACCT,
                MAX(AE) as AE,
                MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
            ')
            ->groupBy('t2.CUSTNO')
            ->first();

        $motheract = $customerdetails->MOTHERACCT ?? '';
        $dept = $customerdetails->DEPARTMENT ?? '';
        $includeDept = ($dept !== '-' && $dept !== '') ? ' - ' . $dept : '';
        // $customername = ($customerdetails->CUSTNAME ?? '') . $includeDept;

        $bsa = pernr_customer_check_bsa_status($customercode, $pernr);

        // 2) Reuse existing header FIRST (1 query, locked)
        $existingH = OPTv2Projectionh::where('CUSTOMERNAME', $customername)
            ->where('BASEDOCNUM', $projdocnum)
            ->where('USERNAME', $staff)
            ->lockForUpdate()
            ->first();

        if ($existingH) {
            $docnum = $existingH->DOCNUM;
        } else {
            // 3) Compute next DOCNUM (1 query) – replaces orderByRaw + check
            $docnum = (string) OPTv2Projectionh::selectRaw('ISNULL(MAX(CAST(DOCNUM AS INT)), 0) + 1 as next_docnum')
                ->lockForUpdate()
                ->value('next_docnum');

            $usernamefname = optional(userNameDetails($staff))->FULLNAME;

            OPTv2Projectionh::insert([
                "DOCNUM" => $docnum,
                "CUSTOMER" => $customercode,
                "TEMPCUSTOMER" => $customercode,
                "TEMPCUSTOMERNAME" => $customername,
                "CUSTOMERNAME" => $customername,
                "MOTHERACT" => $motheract,
                "DEPARTMENT" => $dept,
                "DOCDATE" => $date_now,
                "BSA" => $bsa,
                "PROJECTIONID" => $projid,
                "BASEDOCNUM" => $projdocnum,
                "PERNR" => $pernr,
                "PERNRNAME" => $usernamefname,
                "SUPPLEMENTAL" => $projsupplemental,
                "USERNAME" => $staff,
                "created_at" => $date_now_full,
                "updated_at" => $date_now_full,
                "BRANCHWHOUSE" => $branchwhouse,
            ]);
        }

        // 4) Next LINENUM via MAX (1 query) – replaces orderByRaw first()
        $linenum = (int) OPTv2Projectiond::where('DOCNUM', $docnum)
            ->selectRaw('ISNULL(MAX(CAST(LINENUM AS INT)), 0) + 1 as next_line')
            ->lockForUpdate()
            ->value('next_line');

        // ISBN details (keep as-is)
        $isbn_details = getISBNDetails($isbn);
        $matnr = $isbn_details->MATNR ?? null;
        $copyright = $isbn_details->COPYRIGHT ?? null;
        $author = trim(($isbn_details->ZZAUTHOR1 ?? '') . ' ' . ($isbn_details->ZZAUTHOR2 ?? ''));

        // 5) Upsert detail (same as yours)
        $ok = OPTv2Projectiond::upsert([[
            "DOCNUM" => $docnum,
            "EAN11" => $isbn,
            "BASEDOCNUM" => $projdocnum,
            "USERNAME" => $staff,
            "LINENUM" => $linenum,
            "DESCRIPTION" => $isbn_title,
            "MATNR" => $matnr,
            "ORIGINALUNITP" => null,
            "UNITP" => $isbn_unitp,
            "DISC" => $isbn_disc,
            "POPULATION" => $isbn_population,
            "QTY" => $isbn_qty,
            "PROJECTION" => $isbn_qty,
            "LINETOTAL" => $isbn_linetotal,
            "AUTHOR" => $author,
            "COPYRIGHT" => $copyright,
            "PROJECTIONID" => $projid,
            "PERNR" => $pernr,
            "STATUS" => $statusdb,
            "SUPPLEMENTAL" => $projsupplemental,
            "created_at" => $date_now_full,
            "updated_at" => $date_now_full,
            "TEMPEAN11" => $isbn,
            "REMARKS" => $isbn_remarks,
            "SAVED" => '1',
            "BRANCHWHOUSE" => $branchwhouse,
        ]],
        ['DOCNUM', 'EAN11','BASEDOCNUM','USERNAME'],
        [
            'LINENUM', 'DESCRIPTION', 'MATNR', 'ORIGINALUNITP', 'UNITP',
            'DISC', 'POPULATION', 'QTY', 'PROJECTION', 'LINETOTAL',
            'AUTHOR', 'COPYRIGHT', 'PROJECTIONID', 'BASEDOCNUM', 'PERNR',
            'USERNAME', 'STATUS', 'SUPPLEMENTAL', 'updated_at', 'TEMPEAN11',
            'REMARKS', 'SAVED', 'BRANCHWHOUSE'
        ]);

        if ($ok) {
            $status = 2;
            // NOTE: remove this unless you really need resequence every time:
            // updateLinenumprojectiond($docnum);
        }
    });


    $response = array(
        'status' => $status,
        'html' => $html
    );
                            
    return response()->json($response);

}

// public function submit_new_projection(Request $request) {

//     $staff = session('user_staff');
//     $pernr = session('pernr');
//     $date_now_full = date_now();
//     $date_now = date_now('dateonly');

//     $projdocnum = $request->query('projdocnum');

//     $qproj = OPTv2ProjectionPeriod::where('DOCNUM',$projdocnum)
//                                 ->first();
//     $projid = $qproj->PROJECTIONID;
//     $projsupplemental = $qproj->SUPPLEMENTAL;

//     $savedraft = $request->query('savedraft');
//     $submitforapproval = $request->query('forapproval');
//     $datesubmit = null;
//     $emptyprojection = true;

//     $statusdb = 'saved';
//     if($submitforapproval == 1){
//         $datesubmit = $date_now;
//         $statusdb = 'for_rsm_approval';
//     }
    


//     $customercode_input = $request->input('new_projection_customercode');
//     $branchwhouse_input = $request->input('new_projection_branchwhouse');
//     $isbn_input = $request->input('new_projection_isbn');
//     $isbn_title_input = $request->input('new_projection_isbn_title');
//     $isbn_unitp_input = $request->input('new_projection_isbn_unitp');
//     $isbn_disc_input = $request->input('new_projection_isbn_disc');
//     $isbn_qty_population = $request->input('new_projection_isbn_population');
//     $isbn_qty_input = $request->input('new_projection_isbn_qty');
//     $isbn_linetotal_input = $request->input('new_projection_isbn_linetotal');
//     $isbn_remarks_input = $request->input('new_projection_isbn_remarks');

//     $status = 404;
//     $html = '';
    
//     $insertprojh = [];
//     $insertprojd = [];

//     if(!empty($customercode_input) && is_array($customercode_input)) {

//     // group customers first for header
//         $group_customer = [];

//         foreach ($customercode_input as $i => $cc){

//             $customercode = $customercode_input[$i] ?? null;
//             $branchwhouse = $branchwhouse_input[$i] ?? null;
//             $isbnqty = $isbn_qty_input[$i] ?? 0;


//             if(!isset($group_customer[$cc])) {

//                 $group_customer[$cc]['branchwhouse'] = $branchwhouse;
//                 $group_customer[$cc]['items'] = [];
                
//             }

//             if($isbnqty > 0) {
                
//                 $group_customer[$cc]['items'][] = $i;
//             }
            
//         }
//     //--------

//     //start inserting

//     $qdocnum = OPTv2Projectionh::orderByRaw('DOCNUM + 0 DESC')
//                              ->first();

//     $dbdocnum = $qdocnum ?  $qdocnum->DOCNUM + 1 : 1;

//         foreach($group_customer as $cust => $aa){

//             $emptyprojection = false;

//             $idocnum = $dbdocnum++;
            
//             $qdocnumcheck = OPTv2Projectionh::orderByRaw('DOCNUM + 0 DESC')
//                                 ->where('DOCNUM',$idocnum)
//                                 ->first();
                 

//             $branchwhouse = $aa['branchwhouse'] ?? null;
//             $customercode = $cust;
            
//             $customerdetails = CrmMotherLookup::where('CUSTNO',$customercode)
//                                     ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
//                                     ->orderByRaw('CUSTNAME','ASC')
//                                     ->take(1)
//                                     ->selectRaw('
//                                                     MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, 
//                                                     MAX(RTRIM(CUSTNO)) as CUSTNO, 
//                                                     MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, 
//                                                     MAX(AE) as AE, 
//                                                     MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
                                                
//                                                     '
//                                                     )
//                                     ->groupBy('t2.CUSTNO')
//                                     ->first();

//             $motheract = $customerdetails ? $customerdetails->MOTHERACCT : '';
//             $dept = $customerdetails->DEPARTMENT ?? '';
//             $includeDept = $dept !== '-' ? ' - ' . $dept : '';
//             $customername = $customerdetails ? $customerdetails->CUSTNAME . $includeDept : '';
//             $linenum = 0;
//             $bsa = pernr_customer_check_bsa_status($customercode,$pernr);


//             $qcustomercheck = OPTv2Projectionh::orderByRaw('DOCNUM + 0 DESC')
//                                 // ->where('DOCNUM',$idocnum)
//                                 ->where('CUSTOMERNAME',$customername)
//                                 ->where('BASEDOCNUM',$projdocnum)
//                                 ->where('USERNAME',$staff)
//                                 ->first();

//             if(!empty($qdocnumcheck)) {
//                 $idocnum = $idocnum + 1;

//             }  

//             $docnum = $idocnum;

//             if(!empty($qcustomercheck)) {
//                 $docnum = $qcustomercheck->DOCNUM;

//             }

//             if(empty($qcustomercheck)) {
                
//                 $qusernameDetails = userNameDetails($staff);
//                 $usernamefname = $qusernameDetails->FULLNAME;


//                 $insertprojh[]  = [
//                     "DOCNUM" => $docnum,
//                     "CUSTOMER" => $customercode,
//                     "TEMPCUSTOMER" => $customercode,
//                     "CUSTOMERNAME" => $customername,
//                     "MOTHERACT" => $motheract,
//                     "DEPARTMENT" => $dept,
//                     "DOCDATE" => $date_now,
//                     "BSA" => $bsa,
//                     "PROJECTIONID" => $projid,
//                     "BASEDOCNUM" => $projdocnum,
//                     "PERNR" => $pernr,
//                     "PERNRNAME" => $usernamefname,
//                     "SUPPLEMENTAL" => $projsupplemental,
//                     "USERNAME" => $staff,
//                     "created_at" => $date_now_full,
//                     "updated_at" => $date_now_full,
//                     "BRANCHWHOUSE" => $branchwhouse, 
//                     ];

//             }



//             foreach ($aa['items'] as $ritem) {
                
//                 $linenum++;
                
//                 $isbn = $isbn_input[$ritem] ?? null ; 
//                 $isbn_title = $isbn_title_input[$ritem] ?? null ;
//                 $isbn_unitp = $isbn_unitp_input[$ritem] ?? null ;
//                 $isbn_disc = $isbn_disc_input[$ritem] ?? null ;
//                 $isbn_qty = $isbn_qty_input[$ritem] ?? null ;
//                 $isbn_population = $isbn_qty_population[$ritem] ?? null ;
//                 $isbn_linetotal = $isbn_linetotal_input[$ritem] ?? null ;
//                 $isbn_remarks = $isbn_remarks_input[$ritem] ?? null ;

//                 $isbn_details =  getISBNDetails($isbn);
//                 $matnr = $isbn_details->MATNR;
//                 $copyright = $isbn_details->COPYRIGHT;
//                 $author = $isbn_details->ZZAUTHOR1 . ' '. $isbn_details->ZZAUTHOR2; 


//                 $insertprojd[] = 
//                 [
//                     "DOCNUM" => $docnum,
//                     "EAN11" => $isbn, 
//                     "LINENUM" => $linenum,
//                     "DESCRIPTION" => $isbn_title, 
//                     "MATNR" => $matnr, 
//                     "ORIGINALUNITP" => null, 
//                     "UNITP" => $isbn_unitp, 
//                     "DISC" => $isbn_disc, 
//                     "POPULATION" => $isbn_population, 
//                     "QTY" => $isbn_qty, 
//                     "PROJECTION" => $isbn_qty, 
//                     "LINETOTAL" => $isbn_linetotal, 
//                     "AUTHOR" => $author, 
//                     "COPYRIGHT" => $copyright, 
//                     "PROJECTIONID" => $projid, 
//                     "BASEDOCNUM" => $projdocnum, 
//                     "PERNR" => $pernr, 
//                     "USERNAME" => $staff, 
//                     "STATUS" => $statusdb, 
//                     "SUPPLEMENTAL" => $projsupplemental, 
//                     "created_at" => $date_now_full, 
//                     "updated_at" => $date_now_full, 
//                     "TEMPEAN11" => $isbn, 
//                     "REMARKS" => $isbn_remarks, 
//                     "SAVED" => $savedraft,
//                     "SUBMIT" => $submitforapproval,
//                     "DATESUBMIT" => $datesubmit,
//                     "BRANCHWHOUSE" => $branchwhouse, 

//                 ];

//             }


//         }

//         if($emptyprojection) {
//             $status = 409;
            
//         }
//         if (empty($insertprojh) && empty($insertprojd)) {
//             $status = 410;
//         } else {


//             if(!empty($insertprojh)) {

//                 $insertProjectionH = OPTV2Projectionh::insert($insertprojh);

//             }
           
//             $insertProjectionD = OPTV2Projectiond::upsert($insertprojd,
//                                     ['DOCNUM', 'EAN11'],
//                                     [
//                                         'LINENUM', 'DESCRIPTION', 'MATNR', 'ORIGINALUNITP', 'UNITP',
//                                         'DISC', 'POPULATION', 'QTY', 'PROJECTION', 'LINETOTAL',
//                                         'AUTHOR', 'COPYRIGHT', 'PROJECTIONID', 'BASEDOCNUM', 'PERNR',
//                                         'USERNAME', 'STATUS', 'SUPPLEMENTAL', 'updated_at', 'TEMPEAN11',
//                                         'REMARKS', 'SAVED', 'SUBMIT', 'DATESUBMIT', 'BRANCHWHOUSE'
//                                     ]
        
//                             );

//             if ($insertProjectionD) {
//                 $status = 2;
//             }
 
        
//         }
//     //--------

//     }

//     else {
//         $status = 410;
//     }


//     $response = array(
//         'status' => $status,
//         'html' => $html
//     );
                            
//     return response()->json($response);


// }

public function submit_convertalloc_new(Request $request) {
        
     
    $staff = session('user_staff');
    $pernr = session('pernr');
    $date_now_full = date_now();
    $date_now = date_now('dateonly');

    $isbn_input = $request->input('convertalloc_new_isbn'); 
    $matnr_input = $request->input('convertalloc_new_matnr'); 
    $titlename_input = $request->input('convertalloc_new_titlename'); 
    $qty_input = $request->input('convertalloc_new_qty'); 
    $branchwhouse_input = $request->input('convertalloc_new_branchwhouse'); 
    $balance_input = $request->input('convertalloc_new_balance'); 
    $converttype_input = $request->input('convertalloc_new_converttype'); 
    $toconverttype_input = $request->input('convertalloc_new_toconverttype'); 
    $basedocnum = $request->query('basedocnum'); 
    $justification = trim(
        (string) $request->input(
            'convertalloc_new_justification',
            ''
        )
    );
    $convertTypeSelected = trim(
        (string) $request->input(
            'convertallocation_type',
            ''
        )
    );

    if (
        $convertTypeSelected === 'bsa' &&
        $justification === ''
    ) {

        return response()->json([
            'status' => 411,
            'html' =>
                'Justification is required for BSA to Non-BSA conversion.'
        ]);
    }

     /*
    |--------------------------------------------------------------------------
    | Shared Convert Allocation reference
    |--------------------------------------------------------------------------
    */
    $convertReference =
        'CA-'
        . $basedocnum
        . '-'
        . trim($pernr);


    $status = 404;
    $html = '';
    $insertApproveFinalReqISBNList = [];
    $insertConvertAlloc = [];
    $qprojectionPeriodDetails = projection_period_details($basedocnum);
    $projectionid = $qprojectionPeriodDetails->PROJECTIONID;
    $supplemental = $qprojectionPeriodDetails->SUPPLEMENTAL;




    if(!empty($isbn_input) && is_array($isbn_input)) {
        
        $_docnum = OPTv2ConvertAllocd::orderBy('id', 'DESC')
                            ->value('DOCNUM');

        $docnum = $_docnum ? $_docnum + 1 : 0;

        
        foreach ($isbn_input as $i => $p) {

        
            $qty = $qty_input[$i];
            $branchwhouse = $branchwhouse_input[$i] ?? null;
            $balance = $balance_input[$i] ?? null;
            $isbn = $isbn_input[$i] ?? null; 
            $matnr = $matnr_input[$i] ?? null; 
            $converttype = $converttype_input[$i] ?? null; 
            $toconverttype = $toconverttype_input[$i] ?? null; 
            $titlename = $titlename_input[$i] ?? null; 

            if($qty > 0 ) {

                $docnum++;
              
            

                if($toconverttype !== 'nonbsa'){ 

                    $statusdb = 'for_imd_approval';

                } else {

                    $statusdb = 'for_rsm_approval';

                }

                $insertConvertAlloc[] = [
                    "DOCNUM"  => $docnum,
                    "MATNR"  => $matnr,
                    "EAN11"  => $isbn,
                    "TEMPEAN11"  => $isbn,
                    "DESCRIPTION"  => $titlename,
                    "QTY"  => $qty,
                    "BRANCHWHOUSE"  => $branchwhouse,
                    "FROMCONVERTTYPE"  => $converttype,
                    "TOCONVERTTYPE"  => $toconverttype,
                    "STATUS"  => $statusdb,
                    "BALANCE"  => $balance,
                    "PROJECTIONID"  => $projectionid,
                    "BASEDOCNUM"  => $basedocnum,
                    "SUPPLEMENTAL"  => $supplemental,
                    "PERNR"  => $pernr,
                    "USERNAME"  => $staff,
                    "DATESUBMIT"         => $date_now,
                    "created_at"         => $date_now_full,
                    "updated_at"         => $date_now_full,
                ];
      

            }
            else {

            }

        }

    } 


    if (!empty($insertConvertAlloc)) {

        $insertConvert = OPTv2ConvertAllocd::insert(
            $insertConvertAlloc
        );

        if ($insertConvert) {

            /*
            |--------------------------------------------------------------------------
            | Only BSA -> Non-BSA inserts initial justification
            |--------------------------------------------------------------------------
            */
            if ($convertTypeSelected === 'bsa') {

                $insertJustification = OPTv2Justification::create([
                    'REFERENCE'     => $convertReference,
                    'REFTYPE'       => 'CA',
                    'JUSTIFICATION' => $justification,
                    'USERNAME'      => $staff,
                    'created_at'    => $date_now_full,
                ]);

                if (!$insertJustification) {

                    $status = 405;

                } else {

                    $status = 2;
                }

            } else {

                $status = 2;
            }

        } else {

            $status = 405;
        }

    } else {

        $status = 403;
    }


    $response = array(
        'status' => $status,
        'html' => $html
    );
                            
    return response()->json($response);


}

    public function submit_finalreq(Request $request) {
        
     
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now_full = date_now();
        $date_now = date_now('dateonly');

        $isbn_input = $request->input('isbn'); 
        $approve = $request->query('approve'); 
        $projdocnum = $request->query('projdocnum'); 

        $status = 404;
        $html = '';
        $insertApproveFinalReqISBNList = [];
        $qprojectionPeriodDetails = projection_period_details($projdocnum);
        $projectionid = $qprojectionPeriodDetails->PROJECTIONID;

        $status = 'for_approval';
        // dd($isbn_input);

        if(!empty($isbn_input) && is_array($isbn_input)) {
            
            $dateapproved = null;

            if($approve == '1') {
                $dateapproved = $date_now;
                $status = 'approved';
            }
            
            foreach ($isbn_input as $is => $pp) {
        
                $isbnFinal               = $isbn_input[$is] ?? null;
                $description         = $request->input('description')[$is] ?? null;
                $proposedreqqty      = $request->input('proposedreqqty')[$is] ?? null;
                $requireqty          = $request->input('requireqty')[$is] ?? null;
                $totalproj           = $request->input('totalproj')[$is] ?? null;
                $soh                 = $request->input('soh')[$is] ?? null;
                $approved            = $request->input('approved')[$is] ?? null;
                // $dateapproved        = $request->input('dateapproved')[$is] ?? null;
                $approvedbyusername  = $request->input('approvedbyusername')[$is] ?? null;
                $usercreate          = $request->input('usercreate')[$is] ?? null;
                $createdby           = $request->input('createdby')[$is] ?? null;
                $statusVal           = $request->input('status')[$is] ?? null;
                $saved               = $request->input('saved')[$is] ?? null;
                $projectionid        = $request->input('projectionid')[$is] ?? null;
                $pulloutqty          = $request->input('pullouttransit')[$is] ?? null;
                $omsqty              = $request->input('onorderoms')[$is] ?? null;
                $onpoqty             = $request->input('onpo')[$is] ?? null;
                $buffstockqty        = $request->input('bufferstock')[$is] ?? null;
                $adjstockqty         = $request->input('adjstock')[$is] ?? null;
        
                $insertApproveFinalReqISBNList[] = [
                    "EAN11"              => $isbnFinal,
                    "BASEDOCNUM"         => $projdocnum,
                    "TEMPEAN11"              => $isbnFinal,
                    "DESCRIPTION"        => $description,
                    "PROPOSEREQQTY"      => $proposedreqqty,
                    "REQUIREQTY"         => $requireqty,
                    "TOTALPROJQTY"       => $totalproj,
                    "SOHQTY"             => $soh,
                    "APPROVED"           => $approve,
                    "DATEAPPROVED"       => $dateapproved,
                    "APPROVEDBYUSERNAME" => $staff,
                    "USERCREATE"         => $staff,
                    "STATUS"             => $status,
                    "created_at"         => $date_now_full,
                    "updated_at"         => $date_now_full,
                    "SAVED"              => '1',
                    "PROJECTIONID"       => $projectionid,
                    "PULLOUTQTY"         => $pulloutqty,
                    "OMSQTY"             => $omsqty,
                    "ONPOQTY"            => $onpoqty,
                    "BUFFSTOCKQTY"       => $buffstockqty,
                    "ADJSTOCKQTY"        => $adjstockqty,
                ];

                // dd($isbnFinal);
            }
        }
        
        
     
        $insertApproveFinalReq =
            OPTV2FinalReq::upsert(
                $insertApproveFinalReqISBNList,
                ['EAN11', 'BASEDOCNUM'],
                [
                    'TEMPEAN11','DESCRIPTION', 'PROPOSEREQQTY', 'REQUIREQTY', 'TOTALPROJQTY', 'SOHQTY',
                    'APPROVED', 'DATEAPPROVED', 'APPROVEDBYUSERNAME', 'USERCREATE',
                    'STATUS', 'created_at', 'updated_at', 'SAVED', 'PROJECTIONID',
                    'PULLOUTQTY', 'OMSQTY', 'ONPOQTY', 'BUFFSTOCKQTY', 'ADJSTOCKQTY'
                ]
            );

        if($insertApproveFinalReq) {

            $status = 2;
        }
        else {

            $status = 403;
            
        }

        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);


    }

    public function submit_allocreq_forapproval(Request $request) {

        $docnum = $request->input('docnum');
        $date_now = date_now('dateonly');
        
        $html = "";
        $qallocreqd =  OPTv2AllocReqd::where('DOCNUM',$docnum);
        $status = 404;                  

        if(!$qallocreqd->exists()) {
            $status = 403;
        }
        else {
            
            $qallocreqd  ->update([
                "SUBMIT" => '1',
                "DATESUBMIT" => $date_now,
                "STATUS" => 'for_rsm_approval',
            ]);

            $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                    ->update([
                                        "SUBMIT" => '1',
                                        "DATESUBMIT" => $date_now,
                                    ]);

            $status = 2;

        }

    


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);

    }

    public function submit_allocreq_cancel(Request $request) {

        $docnum = $request->input('docnum');
        $date_now = date_now('dateonly');
        
        $html = "";
        $qallocreqd =  OPTv2AllocReqd::where('DOCNUM',$docnum);
        $status = 404;                  

        if(!$qallocreqd->exists()) {
            $status = 403;
        }
        else {
            
            $qallocreqd  ->update([
                "CANCEL" => '1',
                "DATECANCEL" => $date_now,
                "STATUS" => 'cancelled',
            ]);

            $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                ->update([
                                    "CANCEL" => '1',
                                    "DATECANCEL" => $date_now,
                                    "STATUS" => 'cancelled',
                                ]);

            $status = 2;

        }

    


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);

    }

    // public function submit_modify_allocreq(Request $request) {

    //     $staff = session('user_staff');
    //     $pernr = session('pernr');
    //     $date_now_full = date_now();
    //     $date_now = date_now('dateonly');

    //     $docnum = $request->input('docnum');

    //     $qallocreqh = OPTV2AllocReqh::where('DOCNUM',$docnum)
    //                                 ->select('*',
    //                                     DB::raw("(SELECT TOP 1 LINENUM FROM OPTV2ALLOCREQD t2 WHERE OPTV2ALLOCREQH.DOCNUM = t2.DOCNUM ORDER BY CAST(LINENUM AS INT) DESC ) as LINENUM")
    //                                 )
    //                                 ->first();
                                    
    //     $reference = $qallocreqh->REFENCE;
    //     $linenum = $qallocreqh->LINENUM ?? 0;
    //     $projdocnum = $qallocreqh->BASEDOCNUM;

    //     $qproj = OPTv2ProjectionPeriod::where('DOCNUM',$projdocnum)
    //                                 ->first();

    //     $projid = $qproj->PROJECTIONID;
    //     $projsupplemental = $qproj->SUPPLEMENTAL;

    //     $submitforapproval = $request->query('forapproval');
    //     $datesubmit = null;
    //     $emptyreqto = true;
    //     $reqtoreferences = [];
        
    //     $statusdb = 'saved';
    //     if($submitforapproval == 1){
    //         $datesubmit = $date_now;
    //         $statusdb = 'for_rsm_approval';
    //     } else{
    //         $submitforapproval = null;

    //     }

    //     // $reqtoname_input = $request->input('mdfallocation_request_reqtoname_input');
    //     $type_input = $request->input('mdfallocation_request_type_input');
    //     $isbn_input = $request->input('mdfallocation_request_isbn_input');
    //     $isbn_title_input = $request->input('mdfallocation_request_title_input');
    //     $isbn_qty_input = $request->input('mdfallocation_request_qty_input');
    //     $balance_input = $request->input('mdfallocation_request_balance_input');

    //     $status = 404;
    //     $html = '';
        
    //     $insertallocd = [];

    //     if(!empty($isbn_input) && is_array($isbn_input)) {

    //         foreach( $isbn_input as $ritem => $p) {

    //             $linenum++;
    //             $isbn = $isbn_input[$ritem] ?? null ; 
    //             $isbn_title = $isbn_title_input[$ritem] ?? null ;
    //             $isbn_qty = $isbn_qty_input[$ritem] ?? null ;
    //             $balance = $balance_input[$ritem] ?? null ;
    //             $type = $type_input[$ritem] ?? null ;
    
    //             $isbn_details =  getISBNDetails($isbn);
    //             $matnr = $isbn_details->MATNR;
    //             $copyright = $isbn_details->COPYRIGHT;
    //             $author = $isbn_details->ZZAUTHOR1 . ' '. $isbn_details->ZZAUTHOR2; 
    
    
    //             $insertallocd[] = [
    //                 "DOCNUM" => $docnum,
    //                 "EAN11" => $isbn, 
    //                 "REFERENCE" => $reference,
    //                 "LINENUM" => $linenum,
    //                 "DESCRIPTION" => $isbn_title, 
    //                 "MATNR" => $matnr, 
    //                 "QTY" => $isbn_qty, 
    //                 "AUTHOR" => $author, 
    //                 "COPYRIGHT" => $copyright, 
    //                 "PROJECTIONID" => $projid, 
    //                 "BASEDOCNUM" => $projdocnum, 
    //                 "PERNR" => $pernr, 
    //                 "USERNAME" => $staff, 
    //                 "STATUS" => $statusdb, 
    //                 "SUPPLEMENTAL" => $projsupplemental, 
    //                 "created_at" => $date_now_full, 
    //                 "updated_at" => $date_now_full, 
    //                 "TEMPEAN11" => $isbn, 
    //                 "ALLOCTYPE" => $type, 
    //                 "BALANCE" => $balance, 
    
    //             ];
    //         }
        


    //         if($emptyreqto) {
    //             $status = 409;
                
    //         }
    //         if (empty($insertalloch) && empty($insertallocd)) {
    //             $status = 410;
    //         } else {

               
    //             $insertAllocReqD = OPTV2AllocReqd::upsert($insertallocd,
    //                                                 ["DOCNUM","EAN11"],
                                                    
    //                                                 [   "REFERENCE"
    //                                                     ,"LINENUM"
    //                                                     ,"DESCRIPTION"
    //                                                     ,"MATNR"
    //                                                     ,"QTY"
    //                                                     ,"AUTHOR"
    //                                                     ,"COPYRIGHT"
    //                                                     ,"PROJECTIONID"
    //                                                     ,"BASEDOCNUM"
    //                                                     ,"PERNR"
    //                                                     ,"USERNAME"
    //                                                     ,"STATUS"
    //                                                     ,"SUPPLEMENTAL"
    //                                                     ,"created_at"
    //                                                     ,"updated_at"
    //                                                     ,"TEMPEAN11"
    //                                                     ,"ALLOCTYPE"
    //                                                     ,"BALANCE"
    //                                                 ]
    //                                   );

    //             if ($insertAllocReqD) {

    //                 $status = 2;
    //             }
     
            
    //         }
    //     //--------

    //     }

    //     else {
    //         $status = 410;
    //     }


    //     $response = array(
    //         'status' => $status,
    //         'html' => $html
    //     );
                                
    //     return response()->json($response);


    // }

    public function submit_allocreq_new_title(Request $request) {

        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now_full = date_now();
        $date_now = date_now('dateonly');

        $docnum = $request->input('allocreq_newtitle_docnum');

        $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                    ->select('*',
                                        DB::raw("(SELECT TOP 1 LINENUM FROM OPTV2ALLOCREQD t2 WHERE OPTV2ALLOCREQH.DOCNUM = t2.DOCNUM ORDER BY CAST(LINENUM AS INT) DESC ) as LINENUM")
                                    )
                                    ->first();

        $projdocnum = $qallocreqh->BASEDOCNUM;
        $reference = $qallocreqh->REFERENCE;
        $projid = $qallocreqh->PROJECTIONID;
        $projsupplemental = $qallocreqh->SUPPLEMENTAL;

        $statusdb = 'saved';
    
        $alloctype = $request->input('allocreq_newtitle_alloctype');
        $isbn = $request->input('allocreq_newtitle_isbn');
        $isbn_qty = $request->input('allocreq_newtitle_qty');
        $balance = $request->input('allocreq_newtitle_balance');

        $status = 404;
        $html = '';


        if(!empty($qallocreqh)) {

            $qallocreqd = OPTv2AllocReqd::where('DOCNUM',$docnum)
                                    ->where('EAN11',$isbn)
                                    ->first();
        
            if(empty($qallocreqd)) {

                $isbn_details =  getISBNDetails($isbn);
                $matnr = $isbn_details->MATNR;
                $copyright = $isbn_details->COPYRIGHT;
                $author = $isbn_details->ZZAUTHOR1 . ' '. $isbn_details->ZZAUTHOR2; 
                $isbn_title = htmlspecialchars($isbn_details->MAKTX, ENT_QUOTES, 'UTF-8');
                $linenum = $qallocreqh->LINENUM + 1 ?? 1;

                $qcreateallocreqd = OPTv2AllocReqd::create([
                    "DOCNUM" => $docnum,
                    "REFERENCE" => $reference,
                    "LINENUM" => $linenum,
                    "EAN11" => $isbn, 
                    "DESCRIPTION" => $isbn_title, 
                    "MATNR" => $matnr, 
                    "REQQTY" => $isbn_qty, 
                    "QTY" => $isbn_qty, 
                    "AUTHOR" => $author, 
                    "COPYRIGHT" => $copyright, 
                    "PROJECTIONID" => $projid, 
                    "BASEDOCNUM" => $projdocnum, 
                    "PERNR" => $pernr, 
                    "USERNAME" => $staff, 
                    "STATUS" => $statusdb, 
                    "SUPPLEMENTAL" => $projsupplemental, 
                    "created_at" => $date_now_full, 
                    "updated_at" => $date_now_full, 
                    "TEMPEAN11" => $isbn, 
                    "ALLOCTYPE" => $alloctype, 
                    "BALANCE" => $balance, 

                ]);

                $status = 2;

            } else {

                $status = 405;

            }
            
              
        }

        else {
            $status = 403;
        }


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);

    }
    
    public function submit_create_allocation_request(Request $request) {

        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now_full = date_now();
        $date_now = date_now('dateonly');

        $projdocnum = $request->input('create_allocation_request_projectionperiod');
        $transfertype = $request->input('create_allocation_request_transfertype');
        $reason = $request->input('create_allocation_request_reason');

        if (
            $transfertype == 'bsa_to_nonbsa' &&
            empty(trim($reason ?? ''))
        ) {
            return response()->json([
                'status' => 411,
                'html' => 'Justification is required for BSA to Non-BSA transfer.'
            ]);
        }

        $qproj = OPTv2ProjectionPeriod::where('DOCNUM',$projdocnum)
                                    ->first();
        $projid = $qproj->PROJECTIONID;
        $projsupplemental = $qproj->SUPPLEMENTAL;

        $savedraft = $request->query('savedraft');
        $submitforapproval = $request->query('forapproval');
        $datesubmit = null;
        $emptyreqto = true;
        $reqtoreferences = [];
        
        $statusdb = 'saved';
        if($submitforapproval == '1'){
            $datesubmit = $date_now;
            $statusdb = 'for_rsm_approval';
        } else{
            $submitforapproval = null;

        }

        $reqto_input = $request->input('create_allocation_request_reqto_input');
        $reqtoname_input = $request->input('create_allocation_request_reqtoname_input');
        $type_input = $request->input('create_allocation_request_type_input');
        $isbn_input = $request->input('create_allocation_request_isbn_input');
        $isbn_title_input = $request->input('create_allocation_request_title_input');
        $isbn_qty_input = $request->input('create_allocation_request_qty_input');
        $branchwhouse_input = $request->input('create_allocation_request_branchwhouse_input');
        $balance_input = $request->input('create_allocation_request_balance_input');

        $status = 404;
        $html = '';

        $partstransfertype = explode('_to_', $transfertype);

        $fromalloctype = $partstransfertype[0];
        $toalloctype = $partstransfertype[1];

        $insertalloch = [];
        $insertallocd = [];
        $insertjustification = [];

        if(!empty($reqto_input) && is_array($reqto_input)) {

        // group reqto first for header
            $group_reqto = [];

            foreach ($reqto_input as $i => $cc){

                $reqto = $reqto_input[$i] ?? null;
                $isbnqty = $isbn_qty_input[$i] ?? 0;
    

                if(!isset($group_reqto[$cc])) {

                    $group_reqto[$cc]['items'] = [];
                    
                }

                if($isbnqty > 0) {
                    
                    $group_reqto[$cc]['items'][] = $i;
                }
                
            }
        //--------
    
        //start inserting

        $qdocnumalloc = OPTv2AllocReqh::orderByRaw('DOCNUM + 0 DESC')
                                 ->first();

        $dbdocnum = $qdocnumalloc ?  $qdocnumalloc->DOCNUM + 1 : 1;

            foreach($group_reqto as $cust => $aa){

                if (empty($aa['items'])) {
                    continue;
                }

                $emptyreqto = false;
                $idocnum = $dbdocnum++;
                $firstIndex = $aa['items'][0];
                
                $reqto = $reqto_input[$firstIndex];
                $reqtoname = $reqtoname_input[$firstIndex];
                
                // $qdocnumalloccheck = OPTv2AllocReqh::orderByRaw('DOCNUM + 0 DESC')
                //                     ->where('DOCNUM',$idocnum)
                //                     ->first();

                $docnum = $idocnum;
                if(!empty($qdocnumcheck)) {

                    $docnum = $idocnum + 1;

                }

    
                $linenum = 0;

                $reference = 'AR-' . strpad($docnum,4);
                
                $reqtoreferences[] = '</br>' . $reqtoname . ': <b>' . $reference . '</b>';

                $insertalloch[]  = [
                    "DOCNUM" => $docnum,
                    "REFERENCE" => $reference,
                    "PERNR" => $pernr,
                    "USERNAME" => $staff,
                    "REQTO" => $reqto,
                    "REQTONAME" => $reqtoname,
                    "DOCDATE" => $date_now,
                    "SAVED" => '1',
                    "SUBMIT" => $submitforapproval,
                    "DATESUBMIT" => $datesubmit,
                    "PROJECTIONID" => $projid,
                    "BASEDOCNUM" => $projdocnum,
                    "SUPPLEMENTAL" => $projsupplemental,
                    "TRANSFERTYPE" => $transfertype,
                    "STATUS" => $statusdb,
                    "created_at" => $date_now_full,
                    "updated_at" => $date_now_full,
                    "REMARKS" => $reason,
                ];

                foreach ($aa['items'] as $ritem) {
                    
                    $linenum++;
                    
                    $isbn = $isbn_input[$ritem] ?? null ; 
                    $isbn_title = $isbn_title_input[$ritem] ?? null ;
                    $isbn_qty = $isbn_qty_input[$ritem] ?? null ;
                    $branchwhouse = $branchwhouse_input[$ritem] ?? null ;
                    $balance = $balance_input[$ritem] ?? null ;
                    $type = $type_input[$ritem] ?? null ;

                    $isbn_details =  getISBNDetails($isbn);
                    $matnr = $isbn_details->MATNR;
                    $copyright = $isbn_details->COPYRIGHT;
                    $author = $isbn_details->ZZAUTHOR1 . ' '. $isbn_details->ZZAUTHOR2; 


                    $insertallocd[] = [
                        "DOCNUM" => $docnum,
                        "REFERENCE" => $reference,
                        "LINENUM" => $linenum,
                        "EAN11" => $isbn, 
                        "DESCRIPTION" => $isbn_title, 
                        "MATNR" => $matnr, 
                        "QTY" => $isbn_qty, 
                        "REQQTY" => $isbn_qty, 
                        "AUTHOR" => $author, 
                        "COPYRIGHT" => $copyright, 
                        "PROJECTIONID" => $projid, 
                        "BASEDOCNUM" => $projdocnum, 
                        "SUBMIT" => $submitforapproval,
                        "DATESUBMIT" => $datesubmit,
                        "PERNR" => $pernr, 
                        "USERNAME" => $staff, 
                        "STATUS" => $statusdb, 
                        "SUPPLEMENTAL" => $projsupplemental, 
                        "created_at" => $date_now_full, 
                        "updated_at" => $date_now_full, 
                        "TEMPEAN11" => $isbn, 
                        "ALLOCTYPE" => $type, 
                        "TOALLOCTYPE" => $toalloctype, 
                        "BALANCE" => $balance, 
                        "BRANCHWHOUSE" => $branchwhouse, 

                    ];

                }


            }

            if($emptyreqto) {
                $status = 409;
                
            }
            if (empty($insertalloch) && empty($insertallocd)) {
                $status = 410;
            } else {


                $insertAllocReqH = true;
                $insertJustification = true;

                /*
                |--------------------------------------------------------------------------
                | Insert headers individually
                |--------------------------------------------------------------------------
                | We need the generated OPTV2ALLOCREQH.id so it can be saved as
                | OPTV2JUSTIFICATION.JUSTIFICATIONID.
                */
                foreach ($insertalloch as $headerData) {

                    $createdHeader = OPTv2AllocReqh::create($headerData);

                    if (!$createdHeader) {
                        $insertAllocReqH = false;
                        break;
                    }

                     /*
                    |--------------------------------------------------------------------------
                    | Only BSA -> Non-BSA needs justification
                    |--------------------------------------------------------------------------
                    */
                    if ($transfertype == 'bsa_to_nonbsa') {

                        $createdJustification = OPTv2Justification::create([
                            'REFERENCE'     => $createdHeader->REFERENCE,
                            'REFTYPE'       => 'AR',
                            'JUSTIFICATION' => $reason,
                            'USERNAME'      => $staff,
                            'created_at'    => $date_now_full,
                        ]);

                        if (!$createdJustification) {
                            $insertJustification = false;
                            break;
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Details can remain bulk insert
                |--------------------------------------------------------------------------
                */
                $insertAllocReqD = OPTv2AllocReqd::insert($insertallocd);

                if (
                    $insertAllocReqH &&
                    $insertAllocReqD &&
                    $insertJustification
                ) {

                    $reqtoreferencesImplode = implode(' ', $reqtoreferences);

                    $status = 2;
                    $html = $reqtoreferencesImplode;
                }
     
            
            }
        //--------

        }

        else {
            $status = 410;
        }


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);


    }
    
    public function update_status_pushlistisbn(Request $request) {
        $v = $request->input('v');
        $id = $request->input('id');
        
        $html = "";
        $u =  OPTv2UpdatePushISBN::where('id',$id)
                                ->update([
                                    "STATUS" => $v
                                ]);

        if($u) {
            $status = 2;
        }
        else {
            $status = 404;
        }
    


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);


    }

    public function submit_disapprove_allocreqout(Request $request) {

        $idallocreq_input = $request->input('id');
        $allocreqtype_input = $request->input('alloctype');
        $docnum_input = $request->input('docnum');
        $remarks = $request->input('remarks');
        $pernr = trim(session('pernr'));
        $rank = trim(session('rank'));
        $user_staff = session('user_staff');
        $date_now = date_now();
        $html = "";
        $status = 404;

        if(!empty($idallocreq_input) && is_array($idallocreq_input)){

            foreach($idallocreq_input as $i => $p) {

                $id = $idallocreq_input[$i];
                $docnum = $docnum_input[$i]; 
                
                $qallocreqddetails = OPTv2AllocReqd::where('id',$id)
                                            ->first();
                $fromalloctype = $qallocreqddetails->ALLOCTYPE; 
                $toalloctype = $qallocreqddetails->TOALLOCTYPE; 

                $rankTrim = trim(strtolower($rank));
                $statusdb = $rankTrim . '_disapproved';

                if($rankTrim == 'rsm' || $rankTrim == 'ssm') {

                    $statusdb = 'ae_' . $rankTrim . '_disapproved';
                }

                $qallocreqddisapprove = OPTv2AllocReqd::where('id',$id)
                                        ->update([
                                            "STATUS" =>  $statusdb,
                                            "CANCEL" => '1',
                                            "DISAPPROVED" => '1',
                                            "DISAPPROVEDBY" => $user_staff,
                                            "REMARKSDISAPPROVED" => $remarks,
                                            "DATEDISAPPROVED" => $date_now,
                                        ]);     

            }

        }
        else {
            $status = 403;
        }

       


            if($qallocreqddisapprove) {

                $status = 2;

            } else{
                $status = 405;
            }

        
 

        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);



    }

    public function submit_approve_allocreqout(Request $request) {

        $idallocreq_input = $request->input('id');
        $allocreqtype_input = $request->input('alloctype');
        $qty_input = $request->input('qty');
        
        $docnum_input = $request->input('docnum');
        $isbn_input = $request->input('isbn');
        $basedocnum_input = $request->input('basedocnum');
        $reqfrom_input = $request->input('reqfrom');
        $reqto_input = $request->input('reqto');
        $emailAVP = false;
        $pernr = trim(session('pernr'));
        $rank = trim(session('rank'));
        $user_staff = session('user_staff');
        $date_now = date_now();
        $html = "";

        if(!empty($idallocreq_input) && is_array($idallocreq_input)){

            foreach($idallocreq_input as $i => $p) {

                $id = $idallocreq_input[$i];
                $docnum = $docnum_input[$i];
                $allocreqtype = $allocreqtype_input[$i];
                $qty = $qty_input[$i];

                $isbn = $isbn_input[$i];
                $basedocnum = $basedocnum_input[$i];
                $reqfrom = $reqfrom_input[$i];
                $reqto = $reqto_input[$i];
                
                $qallocreqddetails = OPTv2AllocReqd::where('id',$id)
                                            ->first();
                $fromalloctype = $qallocreqddetails->ALLOCTYPE; 
                $toalloctype = $qallocreqddetails->TOALLOCTYPE; 

                $qallocreqdapprove = OPTv2AllocReqd::where('id',$id);

                // dd($toalloctype . ' from : ' . $fromalloctype);
                
                if (strpos($rank, 'RSM') !== false) {
                    $qallocreqdapprove =  $qallocreqdapprove->update([
                        "STATUS" => 'for_ae_approval',
                        "APPROVED3" => '1',
                        "APPROVEDBY3" => $pernr,
                        "DATEAPPROVED3" => $date_now,
                        "APPROVED4" => '1',
                        "APPROVEDBY4" => $pernr,
                        "DATEAPPROVED4" => $date_now,
                    ]);
                }

                // if (strpos($rank, 'SSM') !== false) {

                //         $qallocreqdapprove =  $qallocreqdapprove->update([
                //             "STATUS" => 'for_ae_approval',
                //             "APPROVED4" => '1',
                //             "APPROVEDBY4" => $pernr,
                //             "DATEAPPROVED4" => $date_now,
                //         ]);
                // }

           
      

                if (strpos($rank, 'AE') !== false) {

                    

                        if($fromalloctype == 'bsa' && $toalloctype == 'nonbsa'  ){

                            $qallocreqdapprove =  $qallocreqdapprove->update([
                                "STATUS" => 'for_avp_approval',
                                "APPROVED5" => '1',
                                "APPROVEDBY5" => $pernr,
                                "DATEAPPROVED5" => $date_now,
                                "QTY" => $qty,
                            ]);

                            $emailAVP = true;
                           
                         
                            
                        } else{
                            
                            $qallocreqdapprove =  $qallocreqdapprove->update([
                                "STATUS" => 'approved',
                                "APPROVED" => '1',
                                "APPROVEDBY" => $pernr,
                                "DATEAPPROVED" => $date_now,
                                "APPROVED5" => '1',
                                "APPROVEDBY5" => $pernr,
                                "DATEAPPROVED5" => $date_now,
                                "QTY" => $qty,
                            ]);
                            modifyAllocated($isbn,$reqfrom, $basedocnum, $toalloctype,$qty,$modtype = 'add');
                            modifyAllocated($isbn,$reqto, $basedocnum, $fromalloctype,$qty,$modtype = 'deduct');

                     
                         
                        }
                       
                     
                }

                if (strpos($rank, 'AVP') !== false) {

                        $qallocreqdapprove =  $qallocreqdapprove->update([

                                            "STATUS" => 'approved',
                                            "APPROVED" => '1',
                                            "APPROVEDBY" => $pernr,
                                            "DATEAPPROVED" => $date_now,
                                            "APPROVED6" => '1',
                                            "APPROVEDBY6" => $pernr,
                                            "DATEAPPROVED6" => $date_now,
                                            "QTY" => $qty,
                                        ]);

                        modifyAllocated($isbn,$reqfrom, $basedocnum, $toalloctype,$qty,$modtype = 'add');
                        modifyAllocated($isbn,$reqto, $basedocnum, $fromalloctype,$qty,$modtype = 'deduct');
                                
            }
                

            }

        }
        else {
            $status = 403;
        }

       


            if($qallocreqdapprove) {
                $status = 2;

                if($emailAVP){
                    
                    $this->EmailForApprovalOVP($request,'Alloc. Transfer Request - Out',$docnum_input);

                }
            } else{
                $status = 405;
            }

        
 

        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);



    }

    
    public function submit_disapproved_convertalloc(Request $request) {

        $ids = $request->input('id');
        $remarks = $request->input('disapproved_convertalloc_reason');
        $pernr = trim(session('pernr'));
        $rank = trim(session('rank'));
        $user_staff = session('user_staff');
        $date_now = date_now();
        $html = "";

        
        // dd($remarks);

        $convertallocd =  OPTv2ConvertAllocd::whereIn('id',$ids);

        if(!$convertallocd->exists()) {
            $status = 403;
        }
        else {
            
      
            $rankTrim = trim(strtolower($rank));
            $statusdb = $rankTrim . '_disapproved';

          $qconvertallocddisapprove = $convertallocd->update([
               "STATUS" => $statusdb,
               "CANCEL" => '1',
               "DISAPPROVED" => '1',
               "DATEDISAPPROVED" => $date_now,
               "REMARKSDISAPPROVED" => $remarks,
               "DISAPPROVEDBY" => $user_staff,
           ]);
          
            


            if($qconvertallocddisapprove) {
                $status = 2;
            } else{
                $status = 405;
            }

        }
 
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);


    }
    public function submit_approve_convertalloc(Request $request) {

        $ids = $request->input('id');
        $pernr = trim(session('pernr'));
        $rank = trim(session('rank'));
        $user_staff = session('user_staff');
        $date_now = date_now();
        $html = "";
        $rankstrlower = strtolower($rank);
        $emailAVP = false;

        $convertallocd =  OPTv2ConvertAllocd::whereIn('id',$ids);

        if(!$convertallocd->exists()) {
            $status = 403;
        }
        else {

            $qconvertallocd = $convertallocd->get();

            Foreach ($qconvertallocd as $r) {
                
                $_id = $r->id;

                $qconvertallocdetails = $r;

                $qty = $qconvertallocdetails->QTY; 
                $docnum = $qconvertallocdetails->DOCNUM; 
                $basedocnum = $qconvertallocdetails->BASEDOCNUM; 
                $isbn = $qconvertallocdetails->EAN11; 
                $pernrconvallocd = $qconvertallocdetails->PERNR; 
                $fromconverttype = $qconvertallocdetails->FROMCONVERTTYPE; 
                $toconverttype = $qconvertallocdetails->TOCONVERTTYPE; 
                
                if($toconverttype !== 'nonbsa'){ 

                 

                    $qconvertallocdapprove =  $qconvertallocdetails->update([
                                            
                                        "STATUS" => $rankstrlower . '_approved',
                                        "APPROVED" => '1',
                                        "APPROVEDBY" => $pernr,
                                        "DATEAPPROVED" => $date_now,
                                        "APPROVED1" => '1',
                                        "APPROVEDBY1" => $pernr,
                                        "DATEAPPROVED1" => $date_now,
                                    ]);

                    modifyAllocated($isbn,$pernrconvallocd, $basedocnum, $toconverttype,$qty,$modtype = 'add');
                    modifyAllocated($isbn,$pernrconvallocd, $basedocnum, $fromconverttype,$qty,$modtype = 'deduct');
                    

                } else {

                    if (strpos($rank, 'RSM') !== false) {

                            $qconvertallocdapprove =  $qconvertallocdetails->update([
                                "STATUS" => 'for_avp_approval',
                                "APPROVED1" => '1',
                                "APPROVEDBY1" => $pernr,
                                "DATEAPPROVED1" => $date_now,
                            ]);

                            $emailAVP = true;

                    }

                    if (strpos($rank, 'AVP') !== false || strpos($rank, 'CC') !== false) {

                                $qconvertallocdapprove =  $qconvertallocdetails->update([
                                            
                                                    "STATUS" => $rankstrlower . '_approved',
                                                    "APPROVED" => '1',
                                                    "APPROVEDBY" => $pernr,
                                                    "DATEAPPROVED" => $date_now,
                                                    "APPROVED2" => '1',
                                                    "APPROVEDBY2" => $pernr,
                                                    "DATEAPPROVED2" => $date_now,
                                                ]);

                                modifyAllocated($isbn,$pernrconvallocd, $basedocnum, $toconverttype,$qty,$modtype = 'add');
                                modifyAllocated($isbn,$pernrconvallocd, $basedocnum, $fromconverttype,$qty,$modtype = 'deduct');
                    }
                    
                }

            }
          
            


            if($qconvertallocdapprove) {
                $status = 2;

                if($emailAVP){
                    
                    $this->EmailForApprovalOVP($request,'Convert Allocation',$docnum);

                }

            } else{
                $status = 405;
            }

        }
 
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);


    }

    public function submit_approve_allocreqin(Request $request) {

        $ids = $request->input('ids');
        $docnum = $request->query('docnum');
        $pernr = trim(session('pernr'));
        $rank = trim(session('rank'));
        $user_staff = session('user_staff');
        $date_now = date_now();
        $html = "";

        $qallocreqd =  OPTv2AllocReqd::whereIn('id',$ids);

        if(!$qallocreqd->exists()) {
            $status = 403;
        }
        else {

            $qallocreqh = OPTv2AllocReqh::where('DOCNUM',$docnum)
                                    ->first();

            $fromrequestpernr = $qallocreqh->PERNR;
            $torequestpernr = $qallocreqh->REQTO;

            $fromqpernrDetails = userDetails($fromrequestpernr);
            $toqpernrDetails = userDetails($torequestpernr);

            $rsmfrompernr = $fromqpernrDetails->RSM;
            $rsmtopernr = $toqpernrDetails->RSM;
            $ssmfrompernr = $fromqpernrDetails->SSM;
            $ssmtopernr = $toqpernrDetails->SSM;
          
            if (strpos($rank, 'RSM') !== false) {

                    $qallocreqdapprove =  $qallocreqd->update([
                        // "STATUS" => 'for_ssm_approval',
                        "STATUS" => 'for_ae_rsm_approval',
                        "APPROVED1" => '1',
                        "APPROVEDBY1" => $pernr,
                        "DATEAPPROVED1" => $date_now,
                        "APPROVED2" => '1',
                        "APPROVEDBY2" => $pernr,
                        "DATEAPPROVED2" => $date_now,
                    ]);

                    if($rsmfrompernr == $rsmtopernr) {

                            $qallocreqdapprove3 =  $qallocreqd->update([
                                // "STATUS" => 'for_ssm_approval',
                                "STATUS" => 'for_ae_approval',
                                "APPROVED3" => '1',
                                "APPROVEDBY3" => $pernr,
                                "DATEAPPROVED3" => $date_now,
                                "APPROVED4" => '1',
                                "APPROVEDBY4" => $pernr,
                                "DATEAPPROVED4" => $date_now,
                            ]);

                    }
            }

            if (strpos($rank, 'SSM') !== false) {

                        $qallocreqdapprove =  $qallocreqd->update([
                            "STATUS" => 'for_ae_rsm_approval',
                            "APPROVED2" => '1',
                            "APPROVEDBY2" => $pernr,
                            "DATEAPPROVED2" => $date_now,
                        ]);

                        if($ssmfrompernr == $ssmtopernr) {

                            $qallocreqdapprove4 =  $qallocreqd->update([
                                "STATUS" => 'for_ae_approval',
                                "APPROVED4" => '1',
                                "APPROVEDBY4" => $pernr,
                                "DATEAPPROVED4" => $date_now,
                            ]);

                        }
                  
                    
            }


            if($qallocreqdapprove) {
                $status = 2;
            } else{
                $status = 405;
            }

        }
 
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);



    }
    
    public function submit_refloat_approved_finalreq(Request $request)
    {
        $basedocnum = $request->input('basedocnum');
        $isbn = $request->input('isbn');
        $allocated = $request->input('allocated');
        $pernr = session('pernr');
        $date_now = date_now();

        $qfinalreq = OPTv2FinalReq::where('BASEDOCNUM', $basedocnum)
                        ->where('EAN11', $isbn);
    
        $status = 404;
        $html = '';
    
        if (!$qfinalreq->exists()) {
            $status = 403;
        } else {
            try {
                DB::transaction(function () use ($qfinalreq, $basedocnum, $isbn, &$status, &$html,$pernr,$date_now,$allocated) {
    
                    if($allocated > 0){

                        $deletedCrm = CrmAllocationHeader::where('isbn', $isbn)
                        ->where('aBatch', $basedocnum)
                        ->delete();
    
                        if ($deletedCrm <= 0) {
                            throw new \Exception('Failed to delete CrmAllocationHeader.');
                        }
        
                        $deletedAllocated = OPTv2Allocated::where('EAN11', $isbn)
                            ->where('BASEDOCNUM', $basedocnum)
                            ->delete();
        
                        if ($deletedAllocated <= 0) {
                            throw new \Exception('Failed to delete OPTv2Allocated.');
                        }

                    }
    
                    $updatedProjection = OPTv2Projectiond::where('EAN11', $isbn)
                        ->where('BASEDOCNUM', $basedocnum)
                        ->update([
                            'DATEALLOCATED' => null
                        ]);
    
                    if ($updatedProjection <= 0) {
                        throw new \Exception('Failed to update OPTv2Projectiond.');
                    }
    
                    $qdeletefinalreq = $qfinalreq->delete();
    
                    if (!$qdeletefinalreq) {
                        throw new \Exception('Failed to delete final req.');
                    }

                    OPTv2Logs::create([
                        "REFERENCE" => $isbn,
                        "REMARKS" => 'Refloated ISBN - Deleted Qty: ' . $allocated ,
                        "USERID" => $pernr,
                        "DOCDATE" => $date_now,
                        "LOGTYPE" => 'refloatapprovefinalreq',
                        "BASEDOCNUM" => $basedocnum,
                    ]);
    
                    $status = 2;
                });
            } catch (\Throwable $e) {
                $status = 500;
                $html = $e->getMessage() . ' - ' . $e->getLine();
            }
        }
    
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
    }
        
    public function submit_update_finalreq_buffstock(Request $request) {

        $basedocnum = $request->input('basedocnum');
        $isbn = $request->input('isbn');

        $soh = $request->input('soh');
        $pullouttransit = $request->input('pullouttransit');
        $onorderoms = $request->input('onorderoms');
        $onpo = $request->input('onpo');
        $bufferstock = $request->input('bufferstock');
        $totalproj = $request->input('totalproj'); 
   
        $html = '';
     
        $adjstock = round($soh + $pullouttransit - $onorderoms + $onpo - $bufferstock);
        $requireqty = $adjstock - $totalproj;
    
        $propreqval =    round(ceil(abs($requireqty) / 100) * 100) ;

        if($soh >= $totalproj) {
            $propreqval =  0;

        }

        $qfinalreqbuffstock =  OPTv2FinalReq::where('BASEDOCNUM',$basedocnum)
                        ->where('EAN11',$isbn)
                        ->orderBy('id','DESC')
                        ->update([
                        "PROPOSEREQQTY"         => $propreqval,
                            "REQUIREQTY"         => $requireqty,
                            "TOTALPROJQTY"       => $totalproj,
                            "SOHQTY"             => $soh,
                            "PULLOUTQTY"         => $pullouttransit,
                            "OMSQTY"             => $onorderoms,
                            "ONPOQTY"            => $onpo,
                            "BUFFSTOCKQTY"       => $bufferstock,
                            "ADJSTOCKQTY"        => $adjstock,
                        ]);

        if($qfinalreqbuffstock) {
            $status = 2;
        }
        else {
            $status = 404;
        }
 
        $response = array(
            'status' => $status,
            'propreq' => $propreqval,
            'adjstock' => $adjstock,
            'req' => $requireqty,
            'html' => $html
        );
                                
        return response()->json($response);


    }

    
    public function submit_update_customer_temp(Request $request) {

        $customercode = $request->input('customercode');
        $customername = $request->input('customername');
        $date_now = date_now();
        $status = 403;
        $pernr = session('pernr');

        $html = '';

        $customerdetails = CrmMotherLookup::where('CUSTNO', $customercode)
        ->leftJoin('prd.CRMMOTHERACT as t2', 'prd.CRMMOTHERLOOKUP.ACCTNO', '=', 't2.MOTHERACCT')
        ->selectRaw('
            MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME,
            MAX(RTRIM(CUSTNO)) as CUSTNO,
            MAX(RTRIM(MOTHERACCT)) as MOTHERACCT,
            MAX(AE) as AE,
            MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
        ')
        ->groupBy('t2.CUSTNO')
        ->first();

        if(empty($customerdetails)) {

            $status = 403;

        } else {


            $motheract = $customerdetails->MOTHERACCT ?? '';
            $dept = $customerdetails->DEPARTMENT ?? '';
            $includeDept = ($dept !== '-' && $dept !== '') ? ' - ' . $dept : '';
            $nameincludeDept = $customerdetails->CUSTNAME . $includeDept;
            $bsa = pernr_customer_check_bsa_status ($customercode);
            
            $updatecustomercode = OPTv2Projectionh::where('CUSTOMERNAME',$customername)
                                    ->where('CUSTOMER','LIKE','%TEMP%')
                                    ->update([
                                        "CUSTOMERNAME" => $nameincludeDept,
                                        "CUSTOMER" => $customercode,
                                        "MOTHERACT" => $motheract,
                                        "DEPARTMENT" => $dept,
                                        "BSA" => $bsa,

                                    ]);
            if($updatecustomercode){
                $status = 2;

                $createLogs = OPTv2Logs::create([
                        "REFERENCE" => $customername ,
                        "REMARKS" => 'to ' . $nameincludeDept,
                        "USERID" => $pernr,
                        "DOCDATE" => $date_now,
                        "LOGTYPE" => 'updatedcustomertemp',
                ]);
                
            }
            
        }
     

 
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);


    }

    public function datatable_create_projection_saved_customer(Request $request) {

        $basedocnum = $request->query('basedocnum');
        $customercode = $request->query('customercode');
        $pernr = session('pernr');
        $qprojectionperiod = projection_period_details($basedocnum);
        $supplemental = $qprojectionperiod->SUPPLEMENTAL;

        $projectionResults = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftJoin('OPTV2PROJECTIONH as t3', 't1.DOCNUM', '=', 't3.DOCNUM')
            ->selectRaw('
                CUSTOMER,
                CUSTOMERNAME,
                COUNT(EAN11) as cnt
            ')
            ->where('t1.SUPPLEMENTAL',$supplemental)
            ->where('t1.PERNR',$pernr)
            ->where('CUSTOMER','!=',$customercode)
            ->groupBy('CUSTOMER','CUSTOMERNAME')
            ->orderBy('CUSTOMERNAME','ASC')
            ->get()
            ;

            $num = 0;
        if($projectionResults->isEmpty()) {

            $response = [
                "num" => 0
            ];

        } else {
            
            // var ap = createProjectionEditingIsbnRow(
            //     randomid, customercode, isbn, title, titleDisplay,
            //     isbnunitpriceclean, isbnunitpriceDisplay,
            //     population, projection, totalprev1, totalprev2, totalprev3, disc,0,0,'no_projection'
            // ) 



            foreach ($projectionResults as $r){
                
                $num++;
                $customercode = $r->CUSTOMER;
                $customername = $r->CUSTOMERNAME;
                $cnt = $r->cnt;
              
                $customernameDisplay = '<span class="line-clamp-1 d-inline" title="'.$customername.'"> '.$customername.' </span> <span class="text-info d-inline">('.$cnt.')</span>';

                $copy = '
                <span class="text-center">
                     <a class="fs--1 copy-isbn-btn text-primary text-primary" href="javascript:void(0)" data-customercode="'.$customercode.'" data-cnt="'.$cnt.'" title="Copy Titles" role="button" aria-expanded="true" aria-controls="collapseExample1">
                                                    Copy
                                                </a>
                    </span>
             
                ';


                $response[] = array(
                    "num" => $num,
                    "customercode" => $customercode,
                    "customername" => $customernameDisplay,
                    "copy" => $copy,
                );
            }

        }
     

        return response()->json($response);
    }
    public function submit_update_convertallocd_branchwhouse(Request $request) {

        $v = $request->input('v');
        $id = $request->input('id');
        $html = "";
        $u =  OPTv2ConvertAllocd::where('id',$id)
                                ->update([
                                    "BRANCHWHOUSE" => $v
                                ]);

        if($u) {
            $status = 2;
        }
        else {
            $status = 404;
        }
 
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        

    }
    public function submit_update_allocreq_qty(Request $request) {

        $v = $request->input('v');
        $id = $request->input('id');
        $html = "";
        $u =  OPTv2AllocReqd::where('id',$id)
                                ->update([
                                    "QTY" => $v,
                                    "REQQTY" => $v
                                ]);

        if($u) {
            $status = 2;
        }
        else {
            $status = 404;
        }
 
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        

    }


    public function submit_update_enddate_projectionid(Request $request) {

        $v = $request->input('v');
        $docnum = $request->input('docnum');
        $date_now = date_now('dateonly');
        $html = "";
        $status = 404;
        $u =  OPTv2ProjectionPeriod::where('DOCNUM',$docnum);

        $updateenddate = $u->update([
                            "ENDDATE" => $v
                            ]);

        if($updateenddate) {
            $status = 2;
        }
    
        if($date_now <= $v) {

            $u->update([
                "STATUS" => '1'
            ]);

            $status = '2-1';

        } else {

            $u->update([
                "STATUS" => '0' // or expired status
            ]);
        }

      


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        

    }
    public function submit_update_status_projectionid(Request $request) {

        $v = $request->input('v');
        $docnum = $request->input('docnum');
        $html = "";
        $u =  OPTv2ProjectionPeriod::where('DOCNUM',$docnum)
                                ->update([
                                    "STATUS" => $v
                                ]);

        if($u) {
            $status = 2;
        }
        else {
            $status = 404;
        }
    


        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        


    }

    public function submit_unlink_customer(Request $request) {

        $customercode = $request->input('customercode');
        $customername = $request->input('customername');
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now('dateonly');

        $status = 404;
        $html = "";
        $qunlink =  OPTv2CustomerLink::where('CUSTOMER',$customercode)
                                        ->update([
                                            'STATUS' => '0'
                                        ]);

        if($qunlink) {

            $status = 2;

        }

        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
    }

    
    public function submit_link_customer(Request $request) {
        $customercode = $request->input('customercode');
        $customername = $request->input('customername');
        $frompernr = $request->input('linkfrompernr');
        $topernr = $request->input('linktopernr');
        $topernrname = $request->input('linktopernrname');
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now('dateonly');

        $html = "";

        $qdocnum =  OPTv2CustomerLink::orderByRaw('DOCNUM + 0 DESC')
                                        ->first();
        $docnum = $qdocnum ? $qdocnum->DOCNUM + 1 : 1;


        $qunlink =  OPTv2CustomerLink::where('CUSTOMER',$customercode)
                                ->update([
                                    'STATUS' => '0'
                                ]);

        $qlinknew = OPTv2CustomerLink::create([
                "DOCNUM" => $docnum,
                "CUSTOMER" => $customercode,
                "CUSTOMERNAME" => $customername,
                "FROMPERNR" => $frompernr,
                "TOPERNR" => $topernr,
                "TOPERNRNAME" => $topernrname,
                "DOCDATE" => $date_now,
                "USERCREATE" => $pernr,
                "STATUS" => "1",
        ]);
        

        if($qlinknew) {
            $status = 2;
        }
        else {
            $status = 404;
        }


     
     
     
    
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        


    }

    public function submit_add_new_link_customer(Request $request) {
        $customercode = $request->input('customercode');
        $customername = $request->input('customername');
        $frompernr = $request->input('frompernr');
        $topernr = $request->input('topernr');
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now('dateonly');

        $html = "";
        $qdocnum =  OPTv2CustomerLink::orderByRaw('DOCNUM + 0 DESC')
                                        ->first();

        $docnum = $qdocnum ? $qdocnum->DOCNUM + 1 : 1;

        $qcheck = OPTv2CustomerLink::where('CUSTOMER',$customercode)
                                    ->where('FROMPERNR',$frompernr)
                                    ->where('TOPERNR',$topernr)
                                    ->where('STATUS','1')
                                    ->first();

        if(empty($qcheck)) {

            $querystatusdisable = OPTv2CustomerLink::where('CUSTOMER',$customercode)
                                        ->where('TOPERNR',$frompernr)
                                        ->update([

                                            "STATUS" => '0'
                                        ]);

            $qlinknew = OPTv2CustomerLink::create([
                    "DOCNUM" => $docnum,
                    "CUSTOMER" => $customercode,
                    "CUSTOMERNAME" => $customername,
                    "FROMPERNR" => $frompernr,
                    "TOPERNR" => $topernr,
                    "DOCDATE" => $date_now,
                    "USERCREATE" => $pernr,
                    "STATUS" => "1",
            ]);
            

            if($qlinknew) {
                $status = 2;
            }
            else {
                $status = 404;
            }


        } else {

            $status = 401;

          
            
        }
     
     
    
        $response = array(
            'status' => $status,
            'html' => $html
        );
                                
        return response()->json($response);
        


    }

    public function datatable_convert_alloc_list_table(Request $request) {

        $basedocnum = $request->query('basedocnum');
        $pernr = session('pernr');

        $qconvertallocd = OPTv2ConvertAllocd::where('BASEDOCNUM',$basedocnum)
                                ->where('PERNR',$pernr)
                                ->orderBy('id','DESC')
                                ->get();

        $num = 0;

        if($qconvertallocd->isEmpty()) {

            $response = [
                "num" => 0
            ];

        } else {

            foreach ($qconvertallocd as $r){
                $num++;
                $id = $r->id;
                $docnum = $r->docnum;
                $isbn = $r->EAN11;
                $tempisbn = $r->TEMPEAN11;
                $description = $r->DESCRIPTION;
                $status = $r->STATUS;
                $qty = $r->QTY;
                $cancel = $r->CANCEL;
                $approved = $r->APPROVED;
                $branchwhouse = $r->BRANCHWHOUSE;
                $remarksdisapproved = $r->REMARKSDISAPPROVED;
                $converttype = $r->FROMCONVERTTYPE;
                $toconverttype = $r->TOCONVERTTYPE;
                $created_at = $r->created_at;
                $updated_at = $r->updated_at;
                $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm border p-1  dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                <div class="dropdown-menu dropdown-menu-end py-2">';
                                // <div class="dropdown-divider"></div>    
                        
                if($approved !== '1' && $cancel !== '1' ) {

                        $action .= '
                                        <a href="#" class="dropdown-item text-danger cancel-btn-convertallocd-isbn" data-docnum="'.$docnum.'" data-id="'.$id.'">
                                                        Cancel</a>
                                                ';
                }

                $action .= "
                                    </div>
                            </div>";
                            
             //select type only
             $activeWarehouses = activeWarehouses();
             $activeBranches = activeBranches();
             
             $ab = '';
             $aw = '';
         
             // dd($branchwhouseFinal);

             
             foreach($activeBranches as $a => $b){

                 $selected1 = $a === $branchwhouse ? 'selected' : '';

                 $ab.= '
                         <option value="'.$a.'" '.$selected1.'>'.$b.'</option>
                 ';
             }
         
                                 
             foreach($activeWarehouses as $c => $d){
                 
                 $selected2 = $b === $branchwhouse ? 'selected' : '';
                 $aw.= '
                         <option value="'.$c.'" '.$selected1.'>'.$d.'</option>
                 ';
             }

             $selectbranchwhouse = '
                     <select data-id="'.$id.'" class="form-control p-1 update_convertallocd_branchwhouse  form-control-sm">
                         <option value=" " selected>Choose in the list </option>

                         <optgroup label="Branches" class="branchesopt">
                             '.$ab.'
                         </optgroup>


                         <optgroup label="Warehouses" class="whouseopt">
                             '.$aw.'
                         </optgroup>
                     </select>
                 ';
                $descriptionDisplay = '<span class="line-clamp-1" title="'.$description.'"> '.$description.' </span>';
                $converttypeDisplay = acronymFullWord($converttype) . ' to ' . acronymFullWord($toconverttype); 
                $datecreateDisplay =  formatDate($created_at,'mdy');
                $statusDisplay =    $statusDisplay = status_display($status,'badge','0.7', '',$remarksdisapproved); 
                
                $history = '';

                if (
                    $converttype === 'bsa' &&
                    $toconverttype === 'nonbsa'
                ) {

                    $convertReference =
                        'CA-'
                        . $r->BASEDOCNUM
                        . '-'
                        . trim($r->PERNR);

                    $messageCount = OPTv2Justification::where(
                            'REFERENCE',
                            $convertReference
                        )
                        ->where('REFTYPE', 'CA')
                        ->count();

                    $history = '
                        <button
                            type="button"
                            class="btn btn-sm btn-link text-primary p-0 btn_convert_view_messages"
                            data-reference="' . $convertReference . '"
                        >
                            View Messages
                            <span class="badge bg-primary ms-1">'
                                . $messageCount .
                            '</span>
                        </button>
                    ';
                }
                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "description"  =>  $descriptionDisplay, 
                    "qty"  =>  $qty, 
                    "datecreate"  =>  $datecreateDisplay, 
                    "converttypedisplay"  =>  $converttypeDisplay, 
                    "branchwhouse"  =>  $selectbranchwhouse, 
                    "status"  => $statusDisplay,
                    "history" => $history,
                    "action"  => $action,
                );
            }

        }

        
        return response()->json($response);

    }
    public function datatable_update_push_list_isbn_table(Request $request) {

        $query = OPTv2UpdatePushISBN::orderBy('id','DESC')
                                ->get();

        $num = 0;

        if($query->isEmpty()) {

            $response = [
                "num" => 0
            ];

        } else {
            foreach ($query as $r){
                $num++;
                $id = $r->id;
                $isbn = $r->EAN11;
                $tempisbn = $r->TEMPEAN11;
                $sapisbn = $r->SAPEAN11;
                $description = $r->DESCRIPTION;
                $status = $r->STATUS;
                $usercreate = $r->USERCREATE;
                $created_at = $r->created_at;
                $updated_at = $r->updated_at;
    
                $quserDetails = userDetails($usercreate);
                $usercreateDisplay = $quserDetails->FULLNAME;
                $datecreate = formatDate($created_at,'mdy');
                $dateupdate = formatDate($updated_at,'mdyts');

            
                $checked = $status == 1 ? 'checked' : '';

                $statusDisplay = '
    
                <div class="mb-0 form-switch h5 d-flex justify-content-center">
                                              <input class="form-check-input update_status_pushlistisbn" id="flexSwitchCheckChecked" data-id="'.$id.'" value="'.$status.'" '.$checked.' type="checkbox" >
                                           </div>
                ';

                $sapisbnDisplay = $sapisbn;

                if($sapisbn == '') {
                    $sapisbnDisplay = '<a class="fw-bold updateisbn_pushlist_btn text-primary " data-id="'.$id.'" data-tempisbn="'.$tempisbn.'" >+ Update</a>';
                }

                $dateupdateDisplay ="<span class='fs--2'>".$dateupdate."</span>";
                $response[] = array(
                    "num" => $num,
                    "isbn" => $isbn,
                    "tempisbn" => $tempisbn,
                    "sapisbn" => $sapisbnDisplay,
                    "description"  =>  $description, 
                    "usercreate"  =>  $usercreate, 
                    "datecreate"  =>  $datecreate, 
                    "dateupdate"  =>  $dateupdateDisplay, 
                    "status"  => $statusDisplay,
                );
            }

        }

        
        return response()->json($response);

    }

    public function submit_update_proposedreq_qty(Request $request) {
        $num = 0;
        $date_now = date_now('dateonly');
        $html = '';
        $proposedreqqty = $request->input('proposedreqqty');  
        $projdocnum = $request->input('projdocnum');  
        $isbn = $request->input('isbn');  

        $pullouttransit = $request->input('pullouttransit');  
        $totalproj = $request->input('totalproj');  
        $soh = $request->input('soh');  
        $onorderoms = $request->input('onorderoms');  
        $onpo = $request->input('onpo');  
        $bufferstock = $request->input('bufferstock');  
        $adjstock = $request->input('adjstock');  
        $requireqty = $request->input('requireqty');  


        $status = 403;

        $qfinalreq =  OPTv2FinalReq::where('BASEDOCNUM',$projdocnum)
                        ->where('EAN11',$isbn)
                        ->orderBy('id','DESC')
                        ->update([
                            "PROPOSEREQQTY" => $proposedreqqty,
                            "REQUIREQTY"         => $requireqty,
                            "TOTALPROJQTY"       => $totalproj,
                            "SOHQTY"             => $soh,
                            "PULLOUTQTY"         => $pullouttransit,
                            "OMSQTY"             => $onorderoms,
                            "ONPOQTY"            => $onpo,
                            "BUFFSTOCKQTY"       => $bufferstock,
                            "ADJSTOCKQTY"        => $adjstock,
                        ])
                        ;
                    
        if($qfinalreq) {
            $status = 2;

        } else{
            $status = 404;

        }
   

    
        $response = array(
            'status' => $status,
            'html' => $html
        );

        return response()->json($response);

    }

    public function update_projection_branchwhouse_customer(Request $request) {

        $branchwhouse = $request->input('branchwhouse');
        $customercode = $request->input('customercode');
        $customername = $request->input('customername');
        $basedocnum = $request->input('basedocnum');
        $user_staff = session('user_staff');
        $pernr = session('pernr');

        $querycheck = OPTv2Projectionh::where('USERNAME',  $user_staff)
                            ->where('BASEDOCNUM',$basedocnum)
                            ->where('CUSTOMER',$customercode)
                            ->first();
        $docnum = '';
        if(!empty($querycheck)){

            $docnum = $querycheck->DOCNUM;

            $updateprojectionh = OPTv2Projectionh::where('DOCNUM',$docnum)
                                    ->update([
                                        "BRANCHWHOUSE" => $branchwhouse
                                    ]);
            $updateprojectiond = OPTv2Projectiond::where('DOCNUM',$docnum)
                                    ->update([
                                        "BRANCHWHOUSE" => $branchwhouse
                                    ]);
            $status = 2;

        }else {
            
            $status = 403;

        }

        $html = '';
        
        $response = array(
            'status' => $status,
            'docnum' => $docnum,
            'html' => $html
        );

        return response()->json($response);

    }

    public function update_status_existing_pushlistisbn(Request $request) {
        $num = 0;
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now();
        $html = '';
        $status = $request->input('v');  
        $isbn = $request->input('isbn');  
        $matnr = $request->input('matnr');  


        $updateexistingpushlist[] = [
                "isbn" => $isbn,
                "matnr" => $matnr, 
                "status" => $status,
                "tag" => 1,
                "DateTagged" => $date_now,
                "TaggedBy" => '',
                "LevelType" => null,

        ];

        $status = 404;

        if(empty($updateexistingpushlist)) {
            $status = 403;

        } else {

            $qexistingpushlist = PushListTagging::upsert($updateexistingpushlist,
            [
                "isbn",
                "matnr" 
            ],
            [
                "status" ,
                "tag" ,
                "DateTagged" ,
                "TaggedBy" ,
                 "LevelType" ,

            ]
            );
            
            $status = 2;


        }
      
    
        $response = array(
            'status' => $status,
            'html' => $html
        );

        return response()->json($response);

    }

    public function submit_change_isbn_projection(Request $request) {
        $num = 0;
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now('dateonly');
        $date_full = date_now();
        $html = '';
        $basedocnum = $request->input('basedocnum');  
        $newisbn = $request->input('change_isbn_newisbn');  
        $existing = $request->input('change_isbn_existing');  

        $querycheck = getISBNDetails($newisbn);

        $status = 404;

        if(empty($querycheck)) {
            $status = 403;

        } else {

            $newmatnr = $querycheck->MATNR;
            $newdescription = strtoupper($querycheck->MAKTX);
            $unitprice = round($querycheck->KBETRCE);
            $author = trim($querycheck->ZZAUTHOR1 . " " . $querycheck->ZZAUTHOR2);
            $copyright = $querycheck->ZZCOPYRIGHT;

            try {
                DB::transaction(function () use (
                    $date_now,
                    $pernr,
                    $basedocnum,
                    $newdescription,
                    $newmatnr,
                    $newisbn,
                    $existing,
                    $unitprice,
                    $author
                ) {
              
    
                    
                    $updated = OPTv2Projectiond::where('TEMPEAN11', $existing)
                    ->where('BASEDOCNUM',$basedocnum)
                    ->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                        "UNITP"   => $unitprice,
                        "AUTHOR"      => $author,
    
                        // LINETOTAL = QTY * UNITPRICE (QTY is varchar)
                        // If QTY should be whole numbers only:
                        // "LINETOTAL" => DB::raw("CAST(QTY as INT) * {$unitprice}"),
    
                        // If may chance na decimals ang QTY, ito mas safe:
                        "LINETOTAL"   => DB::raw("CAST(QTY as DECIMAL(10,2)) * {$unitprice}"),
                    ]);

                    // if gusto mo strict: pag di nag-update, stop
                    if (!$updated) {
                        throw new \Exception('Failed to update OPTv2Projectiond');
                    }
    
                    // 2) update other tables
                    OPTv2AllocReqd::where('TEMPEAN11', $existing)
                    ->where('BASEDOCNUM',$basedocnum)
                    ->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                    ]);
    
                    OPTv2ConvertAllocd::where('TEMPEAN11', $existing)
                    ->where('BASEDOCNUM',$basedocnum)
                    ->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                    ]);
    
                    // OPTv2FinalReq::where('TEMPEAN11', $existing)->update([
                    //     "EAN11"       => $newisbn,
                    //     "DESCRIPTION" => $newdescription,
                    // ]);
    
                    OPTv2Allocated::where('TEMPEAN11', $existing)
                    ->where('BASEDOCNUM',$basedocnum)
                    ->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                    ]);
    

                    OPTv2Logs::create([
                        "REFERENCE" => $existing,
                        "REMARKS" => $newisbn ,
                        "USERID" => $pernr,
                        "DOCDATE" => $date_now,
                        "LOGTYPE" => 'changeisbn',
                        "BASEDOCNUM" => $basedocnum,
                    ]);
                });
    
                $status = 2; // success
            } catch (\Throwable $e) {
                // optional: log error
                // \Log::error($e);
    
                $status = 500; // or other code you prefer
                $html = $e->getMessage() . ' - ' . $e->getLine();
        
            }
        }
      
    
        $response = array(
            'status' => $status,
            'html' => $html
        );

        return response()->json($response);

    }

    public function submit_update_pushlist_sap_isbn(Request $request) {
        $num = 0;
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now('dateonly');
        $html = '';
        $id = $request->input('update_pushlist_isbn_id');  
        $newisbn = $request->input('update_pushlist_isbn_newisbn');  
        $tempisbn = $request->input('update_pushlist_isbn_tempisbn');  

        $querycheck = getISBNDetails($newisbn);

        $status = 404;

        if(empty($querycheck)) {
            $status = 403;

        } else {

            $newmatnr = $querycheck->MATNR;
            $newdescription = strtoupper($querycheck->MAKTX);
            $unitprice = round($querycheck->KBETRCE);
            $author = trim($querycheck->ZZAUTHOR1 . " " . $querycheck->ZZAUTHOR2);
            $copyright = $querycheck->ZZCOPYRIGHT;

            $qpushlistisbn = OPTv2UpdatePushISBN::where('id', $id)
                        ->orderBy('id', 'DESC')
                        ->first();

            try {
                DB::transaction(function () use (
                    $qpushlistisbn,
                    $newisbn,
                    $newdescription,
                    $newmatnr,
                    $tempisbn,
                    $unitprice,
                    $author
                ) {
                    // 1) update push list
                    $updated = $qpushlistisbn->update([
                        'SAPEAN11'     => $newisbn,
                        'DESCRIPTION'  => $newdescription,
                    ]);
    
                    // if gusto mo strict: pag di nag-update, stop
                    if (!$updated) {
                        throw new \Exception('Failed to update OPTv2UpdatePushISBN');
                    }
    
                    // 2) update other tables
                    OPTv2AllocReqd::where('TEMPEAN11', $tempisbn)->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                    ]);
    
                    OPTv2ConvertAllocd::where('TEMPEAN11', $tempisbn)->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                    ]);
    
                    // OPTv2FinalReq::where('TEMPEAN11', $tempisbn)->update([
                    //     "EAN11"       => $newisbn,
                    //     "DESCRIPTION" => $newdescription,
                    // ]);
    
                    OPTv2Allocated::where('TEMPEAN11', $tempisbn)->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                    ]);
    
                    OPTv2Projectiond::where('TEMPEAN11', $tempisbn)->update([
                        "MATNR"       => $newmatnr,
                        "EAN11"       => $newisbn,
                        "DESCRIPTION" => $newdescription,
                        "UNITP"   => $unitprice,
                        "AUTHOR"      => $author,
    
                        // LINETOTAL = QTY * UNITPRICE (QTY is varchar)
                        // If QTY should be whole numbers only:
                        // "LINETOTAL" => DB::raw("CAST(QTY as INT) * {$unitprice}"),
    
                        // If may chance na decimals ang QTY, ito mas safe:
                        "LINETOTAL"   => DB::raw("CAST(QTY as DECIMAL(10,2)) * {$unitprice}"),
                    ]);
                });
    
                $status = 2; // success
            } catch (\Throwable $e) {
                // optional: log error
                // \Log::error($e);
    
                $status = 500; // or other code you prefer
                $html = $e->getMessage() . ' - ' . $e->getLine();
        
            }
        }
      
    
        $response = array(
            'status' => $status,
            'html' => $html
        );

        return response()->json($response);

    }

    public function submit_new_pushlist_isbn(Request $request) {

        $num = 0;
        $staff = session('user_staff');
        $pernr = session('pernr');
        $date_now = date_now('dateonly');
        $html = '';
        $title = $request->input('new_pushlist_isbn_titlename');  
        $tempisbn = '9700TEMP_' . generateRandomString(5) ;

        $querycheck = OPTv2UpdatePushISBN::where('DESCRIPTION',$title)
                                    ->orderBy('id','DESC')
                                    ->first();

        $status = 404;
        if(!empty($querycheck)) {
            $status = 403;

        } else {


            $insertPushListISBN = OPTv2UpdatePushISBN::create([
                            'EAN11'        => $tempisbn,
                            'TEMPEAN11'   => $tempisbn,
                            'DESCRIPTION'     => $title,
                            'STATUS'     => '1',
                            'USERCREATE'     => $pernr,
                ]);
            
            $status = 2;


        }
      
    
        $response = array(
            'status' => $status,
            'html' => $html
        );

        return response()->json($response);

    }

    public function submit_add_new_projectionperiod(Request $request) {

        $num = 0;
        $staff = session('user_staff');
        $pernr = trim(session('pernr'));
        $date_now = date_now('dateonly');
        $fulldate = date_now();
        $html = '';
        $projectiontype = $request->input('add_new_projection_type');  
        $supplemental = $request->input('add_new_projection_supplemental');  
        $startdate = $request->input('add_new_projection_startdate');  
        $enddate = $request->input('add_new_projection_enddate');
        $schoolyear =  $request->input('add_new_projection_school_year');
        $schoollevel = $request->input('add_new_projection_level');
        $schoolperiod =  $request->input('add_new_projection_school_period');
        $remarks =  $request->input('add_new_projection_remarks');

        $pernrleadzeros = $pernr = ltrim(trim(session('pernr')), '0');

        if($projectiontype == 'mainprojection') {

            $remarks = 'Main Projection';
        
        }

        $newcrmperiod = CrmDateRanges::create([

                            'dSuppStatus'   => $supplemental ?? 0,
                            'dFrom'     => $startdate ?? null,
                            'dStatus'     => 0,
                            'dTo'       => $enddate . ' 23:59:59.000' ?? null,
                            'dSy'         => $schoolyear ?? null,
                            'dLevel'         => $schoollevel ?? null,
                            'dPeriod'        => $schoolperiod ?? null,
                            'dIDno'      => $pernrleadzeros ?? null,
                            'dRemarks'       => $remarks ?? null,
                            'dDate'=> $fulldate ?? null

                    ]);

        $crmdaterangeid = $newcrmperiod->id ?? null;
        if($crmdaterangeid === null){
            $status = 403;

        }
        else {
         

            $docnum = $crmdaterangeid;

            if($projectiontype == 'mainprojection') {

            $linenum = null;


            $projectionid = strpad($docnum,3);
            $supplemental = null;

            }
            else {

            $querySupplemental = OPTv2ProjectionPeriod::where('SUPPLEMENTAL',$supplemental)
                        ->where('PROJECTIONTYPE','supplemental')
                        ->orderByRaw('CAST(LINENUM AS INT) DESC')
                        ->first();

            $linenum = isset($querySupplemental->LINENUM) ? $querySupplemental->LINENUM + 1 : 1;

            $projectionid =  strpad($supplemental,3) . '-' . $linenum;

            }



            // $linetotal = $qty * $amount;

            $insertProjectionPeriod = OPTv2ProjectionPeriod::create([
                    'DOCNUM'        => $docnum ?? null,
                    'SUPPLEMENTAL'   => $supplemental ?? $docnum,
                    'STARTDATE'     => $startdate ?? null,
                    'ENDDATE'       => $enddate ?? null,
                    'YEAR'         => $schoolyear ?? null,
                    'LEVEL'         => $schoollevel ?? null,
                    'PERIOD'        => $schoolperiod ?? null,
                    'USERCREATE'      => $staff ?? null,
                    'LINENUM'      => $linenum ?? null,
                    'REMARKS'       => $remarks ?? null,
                    'PROJECTIONTYPE'=> $projectiontype ?? null,
                    'PROJECTIONID'=> $projectionid ?? null,
            ]);


            if($insertProjectionPeriod) {
            $status = 2;
            }
            else {
            $status = 404;
            }


            // }


        }
     
        

        $response = array(
            'status' => $status,
            'html' => $html
        );

        return response()->json($response);

    }

    public function PrintForApprovalFinalReq(Request $request)
    {
        $projdocnum = $request->query('projdocnum');
        $pernr = session('pernr');
        $user_staff = session('user_staff');
        $date_now = date_now();

        $notapprovedfinalreqisbn =  OPTv2FinalReq::where('BASEDOCNUM',$projdocnum)
                        ->where('APPROVED','!=','1')
                        ->orderBy('id','DESC')
                        ->get();

        $data = [
            'title' => 'Welcome to Laravel PDF Generation',
            'pernr' => $pernr,
            'username' => $user_staff,
            'date_now' => $date_now,
            'projdocnum' => $projdocnum,
            'qfinalreqd' => $notapprovedfinalreqisbn,
        ];

        $pdf = PDF::loadView('pdf/PrintForApprovalFinalReq', $data);
        

        return $pdf->stream('document.pdf');

    }

    public function PrintWithoutProposalFinalReq(Request $request)
    {
        $projdocnum = $request->query('projdocnum');
        $pernr = session('pernr');
        $user_staff = session('user_staff');
        $date_now = date_now();

        $notapprovedfinalreqisbn =  OPTv2FinalReq::where('BASEDOCNUM',$projdocnum)
                        ->where('APPROVED','!=','1')
                        ->whereRaw('CAST(PROPOSEREQQTY AS INT) < 1 ')
                        ->orderBy('DESCRIPTION','ASC')
                        ->get();

        $data = [
            'title' => 'Welcome to Laravel PDF Generation',
            'pernr' => $pernr,
            'username' => $user_staff,
            'date_now' => $date_now,
            'projdocnum' => $projdocnum,
            'qfinalreqd' => $notapprovedfinalreqisbn,
        ];

        $pdf = PDF::loadView('pdf/PrintWithoutProposalFinalReq', $data);
        

        return $pdf->stream('document.pdf');

    }

    public function PrintWithProposalFinalReq(Request $request)
    {
        $projdocnum = $request->query('projdocnum');
        $pernr = session('pernr');
        $user_staff = session('user_staff');
        $date_now = date_now();

        $notapprovedfinalreqisbn =  OPTv2FinalReq::where('BASEDOCNUM',$projdocnum)
                        ->where('APPROVED','!=','1')
                        ->whereRaw('CAST(PROPOSEREQQTY AS INT) > 0 ')
                        ->orderBy('DESCRIPTION','ASC')
                        ->get();

        $data = [
            'title' => 'Welcome to Laravel PDF Generation',
            'pernr' => $pernr,
            'username' => $user_staff,
            'date_now' => $date_now,
            'projdocnum' => $projdocnum,
            'qfinalreqd' => $notapprovedfinalreqisbn,
        ];

        $pdf = PDF::loadView('pdf/PrintWithProposalFinalReq', $data);
        

        return $pdf->stream('document.pdf');


        
    }

    public function submit_admin_users_retrieve_user_details(Request $request) {

        $id = $request->input('id');
        $pernr = $request->input('pernr');
        
        $query = OPTv2User::select('*')
                            ->where('id',$id);

        $row = $query->first();

        $num = 0;
        if (empty($row)) {
            $response = [
                'num' => '0'
            ];
        } else {

              

                    $num++;
                    $id = $row->id; 
                    $pernr = $row->PERNR; 
                    $name = $row->FULLNAME; 
                    $username = $row->USERNAME; 
                    $password = $row->PASSWORD; 
                    $rsm = $row->RSM; 
                    $ssm = $row->SSM; 
                    $rank = $row->RANK; 
                    $email = $row->EMAIL; 
                    
                    $action = '<div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm border dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                        <a href="#"  class="dropdown-item admin_user_edit_btn"   data-id="'.$id.'">
                                                        Edit</a>';
                                // <div class="dropdown-divider"></div>    
                    
                        
                    
                            $action .= "
                              </div>
                            </div>";


                      $response[] = array(
                        'num' => $num,
                        'id' => $id,
                        'pernr' => $pernr,
                        'name' => $name,
                        'username' => $username,
                        'password' => $password,
                        'rsm' =>$rsm,
                        'ssm' =>$ssm,
                        'rank' =>$rank,
                        'email' =>$email,
                    );
                    

                
          }

                return response()->json($response);



    }

    public function cronInsertNonSalesTeam()
    {   



        $nonsalesteam = [
            'CRM',
            'CC',
            'PEAM'
        ];

        $e = OPTv2User::select('USERNAME')
                ->whereIn('RANK',$nonsalesteam)
                ->pluck('USERNAME')
                ->toArray(); 

        $ww = CrmUsers::whereNotIn('USERID',$e)
        ->whereIn('RANK',$nonsalesteam)
        // ->toArray();
        ->get();

        foreach($ww as $w){
            $recno = $w->RECNO;
            $persnr = $w->PERSNR;
            $idno = $w->IDNO;
            $ceid = $w->CEID;
            $aecode = $w->AECODE;
            $rank = $w->RANK;
            $aplevel = $w->APLEVEL;
            $userid = $w->USERID;
            $fname = $w->FNAME;
            $lname = $w->LNAME;
            $mi = $w->MI;
            $rsm = $w->RSM;
            $ssm = $w->SSM;
            $territory = $w->TERRITORY;
            $division = $w->DIVISION;
            $type = $w->TYPE;
            $lastmsgid = $w->LASTMSGID;
            $hide = $w->HIDE;
            $active = $w->ACTIVE;
            $primaryacct = $w->PRIMARYACCT;
            $avp = $w->AVP;
            $ccdtl = $w->CCDTL;
            $email = $w->EMAIL;

            $fullname = trim($lname) . ', ' . trim($fname);

            $getpwd = ZprUsers::where('USERID',$userid)
                            ->limit(1)
                            ->first();

            $pwd = $getpwd ? $getpwd->PWD : '';   

            OPTv2User::create([
                    "FULLNAME"   => $fullname,
                    "USERNAME" => $userid,
                    "PASSWORD" => $pwd,
                    "USERGROUP" => "",
                    "ADDRESS" => "",
                    "CONTACT" => "",
                    "EMAIL" => $email,
                    "RSM" => $rsm,
                    "SSM" => $ssm,
                    "STATUS" => "1",
                    "PERNR" => $persnr,
                    "IDNO" => $idno,
                    "RANK" => $rank,
                    "ACTIVE" => $active,
                    "DIVISION" => $division,
                    "TYPE" => $type,
                    "APLEVEL" => $aplevel
                ]);


        }

        
    //     $query = OPTv2User::groupBy('PERNR')
    //                     // ->where('RANK','LIKE','%AE%')
    //                     // ->where('RANK','LIKE','%RSM%')
    //                     // ->where('RANK','LIKE','%SSM%')
    //                     ->select('PERNR',
    //                             DB::raw('MAX(RTRIM(RANK)) as RANK')
    //                             )
    //                     ->get();
        
    //     foreach ($query as $r){
    //         $pernr = $r->PERNR;
    //         $rank = $r->RANK;

    //   }

//iNSERT nonSALESTEAM

    }

    public function cronInsertSalesTeam()
    {   


//iNSERT SALESTEAM

        $salesteam = [
            'AE',
            'SSM',
            'RSM'
        ];

        $e = OPTv2User::select('USERNAME')
                ->whereIn('RANK',$salesteam)
                ->pluck('USERNAME')
                ->toArray(); 

        $ww = CrmUsers::whereNotIn('USERID',$e)
        ->whereIn('RANK',$salesteam)
        // ->toArray();
        ->get();

        foreach($ww as $w){
            $recno = $w->RECNO;
            $persnr = trim($w->PERSNR);
            $idno = $w->IDNO;
            $ceid = $w->CEID;
            $aecode = $w->AECODE;
            $rank = $w->RANK;
            $aplevel = $w->APLEVEL;
            $userid = $w->USERID;
            $fname = $w->FNAME;
            $lname = $w->LNAME;
            $mi = $w->MI;
            $rsm = trim($w->RSM);
            $ssm = trim($w->SSM);
            $territory = $w->TERRITORY;
            $division = $w->DIVISION;
            $type = $w->TYPE;
            $lastmsgid = $w->LASTMSGID;
            $hide = $w->HIDE;
            $active = $w->ACTIVE;
            $primaryacct = $w->PRIMARYACCT;
            $avp = $w->AVP;
            $ccdtl = $w->CCDTL;
            $email = $w->EMAIL;

            $fullname = trim($lname) . ', ' . trim($fname);

            $getpwd = ZprUsers::where('USERID',$userid)
                            ->limit(1)
                            ->first();

            $pwd = $getpwd ? $getpwd->PWD : '';   

            OPTv2User::create([
                    "FULLNAME"   => $fullname,
                    "USERNAME" => $userid,
                    "PASSWORD" => $pwd,
                    "USERGROUP" => "",
                    "ADDRESS" => "",
                    "CONTACT" => "",
                    "EMAIL" => $email,
                    "RSM" => $rsm,
                    "SSM" => $ssm,
                    "STATUS" => "1",
                    "PERNR" => $persnr,
                    "IDNO" => $idno,
                    "RANK" => $rank,
                    "ACTIVE" => $active,
                    "DIVISION" => $division,
                    "TYPE" => $type,
                    "APLEVEL" => $aplevel
                ]);


        }

        
        $query = OPTv2User::groupBy('PERNR')
                        // ->where('RANK','LIKE','%AE%')
                        // ->where('RANK','LIKE','%RSM%')
                        // ->where('RANK','LIKE','%SSM%')
                        ->select('PERNR',
                                DB::raw('MAX(RTRIM(RANK)) as RANK')
                                )
                        ->get();
        
        foreach ($query as $r){
            $pernr = $r->PERNR;
            $rank = $r->RANK;

      
   
    }

//iNSERT SALESTEAM

    }

   public function EmailSubmittedForApproval(Request $request,$basedocnum = null,$submittedpernr = null,$approvalrsmpernr = null) {

        $quser = userDetails($approvalrsmpernr);
        $iduser = $quser->id;
        $emailuser = $quser->EMAIL;

        $qusersubmitted = userDetails($submittedpernr);
        $aefullname = $qusersubmitted->FULLNAME;
        $usernamesubmmited = $qusersubmitted->USERNAME;

        //$email = 'nico.padilla@cebookshop.com';
         $email = $quser->EMAIL ?: 'nico.padilla@cebookshop.com';

        $qprojectionPeriodDetails = projection_period_details($basedocnum);
        $projectionid = $qprojectionPeriodDetails->PROJECTIONID;
        $username = $qprojectionPeriodDetails->USERNAME;

        $token = secureTokenWithId($iduser,30);
      
        $baseUrl = url('/');

        $ae = '';
     
        $linkforapproval= 'approvals/projection?pid='.$basedocnum.'&tk='.$token .'&name='.$usernamesubmmited.'&fname='. $aefullname . ' ' . $submittedpernr;

        Mail::send('emails.EmailSubmittedForApproval',[
            'linkforapproval' => $baseUrl . '/modules/' . $linkforapproval,
            'aefullname' => $aefullname,
            'projectionid' => $projectionid,
        ], function($message) use($projectionid,$aefullname,$email)  {
            $message->to($email)
                    ->subject($aefullname . ' has submitted their projection for approval. PID: ' . $projectionid)
                    ->cc('cesalesitinerary@cebookshop.com');;

                    

        });
    }

    public function EmailReturnProjection(Request $request,$basedocnum = null,$pernr = null,$remarks = null) {

        $quser = userDetails($pernr);
        $iduser = $quser->id;
        $emailuser = $quser->EMAIL;

        //$email = 'nico.padilla@cebookshop.com';
         $email = $quser->EMAIL ?: 'nico.padilla@cebookshop.com';

        $qprojectionPeriodDetails = projection_period_details($basedocnum);
        $projectionid = $qprojectionPeriodDetails->PROJECTIONID;
        $username = $qprojectionPeriodDetails->USERNAME;

        $token = secureTokenWithId($iduser,30);
      
        $baseUrl = url('/');

        $ae = '';
     
        $linkforapproval= 'createprojection?pid='.$basedocnum.'&tk='.$token.'&name='.$username;
   
      

        Mail::send('emails.EmailReturnProjection',[
            'linkforapproval' => $baseUrl . '/modules/' . $linkforapproval,
            'remarks' => $remarks,
            'projectionid' => $projectionid,
        ], function($message) use($projectionid,$email)  {
            $message->to($email)
                    ->subject('Projection Returned in PID: ' . $projectionid)
                    ->cc('cesalesitinerary@cebookshop.com');;

                    

        });
    }
    
    public function EmailForApprovalOVP(Request $request,$approvaltype = null,$docnum = null) {

        $token = secureTokenWithId('1343',30);
        $baseUrl = url('/');

        $ae = '';
     
        if ($approvaltype == 'Alloc. Transfer Request - In') {

            $linkforapproval= 'approvals/allocationrequest/in?docnum=' . $docnum;
            
        }

        if ($approvaltype == 'Alloc. Transfer Request - Out') {


            $linkforapproval= 'approvals/allocationrequest/out?docnum=' . $docnum .'&tk='.$token;

        }   
    
        if ($approvaltype == 'Convert Allocation') {

            $qconvertallocd = OPTv2ConvertAllocd::from('OPTV2CONVERTALLOCD as t1')
                                        ->where('DOCNUM',$docnum)
                                        ->first();

            $projdocnum = $qconvertallocd->BASEDOCNUM;
            $username = $qconvertallocd->USERNAME;
            $fullname = $username;
            $pernr = $qconvertallocd->PERNR;
            $quserDetails = userDetails($pernr);
            $ae = $quserDetails->PERNR . ' ' . $quserDetails->FULLNAME;

            $linkforapproval= 'approvals/convertallocation/request?pid='.$projdocnum.'&tk='.$token.'&name='.$username.'&fname='. $ae;
       

        }



        $ccEmails = [
            'samuel.deluna@metrostarrealty.com.ph',
            'samuel.deluna@cebookshop.com',
            'maricon.tuazon@cebookshop.com',
            'emrick.salvador@cebookshop.com'
        ];        
      

        Mail::send('emails.EmailForApprovalOVP',[
            'linkforapproval' => $baseUrl . '/modules/' . $linkforapproval,
            'ae' => $ae,
        ], function($message) use($approvaltype,$ccEmails)  {
            $message->to('nico.padilla@cebookshop.com')
                    ->subject('OPTv2: '.$approvaltype.' Pending Approval.');

                    foreach ($ccEmails as $cc) {
                        $message->cc($cc);
                    }

        });
    }

    //added by emrick august 18, 2026
    public function testEmailOVP(Request $request)
    {
        try {

            $baseUrl = url('/');

            Mail::send('emails.EmailForApprovalOVP', [
                'linkforapproval' => $baseUrl . '/modules/approvals',
                'ae' => 'TEST EMAIL - EMRICK',
            ], function ($message) {

                $message->to('emrick.salvador@cebookshop.com')
                    ->subject('OPTv2: TEST Email Notification');

            });

            return response()->json([
                'status' => 200,
                'message' => 'Test email sent successfully.',
                'recipient' => 'emrick.salvador@cebookshop.com'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 500,
                'message' => 'Email sending failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get_allocreq_justification(Request $request)
    {
        $reference = trim((string) $request->input('reference'));
        $reftype = strtoupper(
            trim((string) $request->input('reftype', 'AR'))
        );

        if (empty($reference)) {
            return response()->json([
                'status' => 400,
                'data' => []
            ]);
        }

        if (!in_array($reftype, ['AR', 'CA'])) {
            return response()->json([
                'status' => 400,
                'data' => []
            ]);
        }

        $justifications = OPTv2Justification::where(
                'REFERENCE',
                $reference
            )
            ->where('REFTYPE', $reftype)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get([
                'id',
                'REFERENCE',
                'REFTYPE',
                'JUSTIFICATION',
                'USERNAME',
                'created_at'
            ]);

        return response()->json([
            'status' => 2,
            'data' => $justifications
        ]);
    }

    public function submit_allocreq_justification_message(Request $request)
    {
        $reference = trim(
            (string) $request->input('reference')
        );

        $reftype = strtoupper(
            trim((string) $request->input('reftype', 'AR'))
        );

        $message = trim(
            (string) $request->input('message')
        );

        $staff = trim(
            (string) session('user_staff')
        );

        $date_now_full = date_now();


        if (empty($reference)) {
            return response()->json([
                'status' => 400,
                'message' => 'Reference is required.'
            ]);
        }


        if (!in_array($reftype, ['AR', 'CA'])) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid reference type.'
            ]);
        }


        if (empty($message)) {
            return response()->json([
                'status' => 411,
                'message' => 'Message is required.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate reference
        |--------------------------------------------------------------------------
        */
        if ($reftype === 'AR') {

            $recordExists = OPTv2AllocReqh::where(
                'REFERENCE',
                $reference
            )->exists();

        } else {

            /*
            |--------------------------------------------------------------------------
            | CA reference:
            | CA-{BASEDOCNUM}-{PERNR}
            |--------------------------------------------------------------------------
            */
            $parts = explode('-', $reference);

            $basedocnum = $parts[1] ?? null;
            $pernr = $parts[2] ?? null;

            $recordExists = OPTv2ConvertAllocd::where(
                    'BASEDOCNUM',
                    $basedocnum
                )
                ->where('PERNR', $pernr)
                ->exists();
        }


        if (!$recordExists) {
            return response()->json([
                'status' => 404,
                'message' => 'Reference not found.'
            ]);
        }


        $insert = OPTv2Justification::create([
            'REFERENCE' => $reference,
            'REFTYPE' => $reftype,
            'JUSTIFICATION' => $message,
            'USERNAME' => $staff,
            'created_at' => $date_now_full,
        ]);


        if (!$insert) {
            return response()->json([
                'status' => 500,
                'message' => 'Unable to save message.'
            ]);
        }


        return response()->json([
            'status' => 2,
            'message' => 'Message posted successfully.'
        ]);
    }


    
 
}
