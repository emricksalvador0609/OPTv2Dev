<?php
date_default_timezone_set('Asia/Manila');

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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
use App\Models\OPTv2Projectionda;
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
// use Auth;
// use DB;


// use RecursiveIteratorIterator;
// use RecursiveDirectoryIterator;

if (!function_exists('yourFunction')) {
      
    function yourFunction()
    {
         return 'Hello C&E';
    }
    function number_format_2_decimals($number)
    {
         return number_format($number,2, '.' , ',');
    }


    function truncatelimitWords($words,$cnt = 30) {
      
      $t = Str::limit($words,$cnt);

      return $t;

    }

    function filterUsersSalesTeam() {
      $r = [
            'AE',
            'SSM',
            'RSM',
      ];

      return $r;

    }

 

    function selectTypeBranchWhouse (){
      $activeWarehouses = activeWarehouses();
      $activeBranches = activeBranches();

      $r = '
                 <select name="create_projection_branchwhouse_id[]" id="" class="form-control create_projection_branchwhouse_id form-control-sm">
                        <optgroup label="Branches">
                        ';

      foreach($activeBranches as $a => $b){
            $r.= '
                  <option value="'.$a.'">'.$b.'</option>
            ';
      }
      
      

              $r .='</optgroup>

                        <optgroup label="Warehouses">';
                            
      foreach($activeWarehouses as $c => $d){
            $r.= '
                  <option value="'.$c.'">'.$d.'</option>
            ';
      }


     $r .= '            </optgroup>       
                  </select>
      ';

      return $r;

    }

    function getActiveLocationName($code)
{
    $warehouses = activeWarehouses();
    $branches = activeBranches();

    // 1️⃣ Check kung warehouse
    if (array_key_exists($code, $warehouses)) {
        return $warehouses[$code];
    }

    // 2️⃣ Check kung branch
    if (array_key_exists($code, $branches)) {
        return $branches[$code];
    }

    // 3️⃣ Check kung baka pareho? (optional logic)
    if (array_key_exists($code, $warehouses) && array_key_exists($code, $branches)) {
        return $warehouses[$code] . ' / ' . $branches[$code];
    }

    // ❌ else return empty
    return '';
}

    function activeWarehouses() {

      $r = [

            
            '2101' => 'RBC Cebu',   
            '3613' => 'SanBar Davao', 
            '3601' => 'Sanbar Regular', 
      ];

      return $r;

    }

    function activeBranches() {

      $r = [
            'MULTP' => 'Multiple Branches',
            'B029' => 'BACOLOD Selling',
            'B064' => 'BINAN Selling',
            'B049' => 'CDO Selling',
            'B025' => 'CEBU Selling',
            'B001' => 'CEIRC Selling',
            'B009' => 'DAPITAN Selling',
            'B037' => 'DAGUPAN Selling',
            'B057' => 'DAVAO Selling',
            'B021' => 'ILOILO Selling',
            'B033' => 'NAGA Selling',
            'B061' => 'TACLOBAN Selling',
            'B041' => 'TUGUEGARAO Selln',
            'B005' => 'UE RECTO Selling',
            'B053' => 'ZAMBOANGA Selln',
        ];

      return $r;

    }

    function customerTopOrdersLastYear($year,$kunnr = 1) {

            // $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
            //       ->leftJoin('prd.ZSD_OMSD as t2', 't1.ORDNUM', '=', 't2.ORDNUM')
                  
            //       ->select('t2.EAN11',DB::raw('
            //                         SUM(QTYORDERED) as QTYORDERED,
            //                         MAX(KBETR) as UNITPRICE
            //                         '))
            //       ->where('t1.ERDAT','LIKE','%'.$year.'%')
            
            //      ->where('t1.KUNNR',$kunnr)
            //       ->whereNotNull('t2.EAN11')
            //       ->groupBy('t2.EAN11')
            //       ->orderBy("t2.EAN11",'DESC')
            //       ->get();

            $qytdsales = NewSales::from('new_sales as t1')
                              ->select(DB::raw("SUM(CAST(REPLACE(t1.netsales, ',', '') AS DECIMAL(18,2))) as TOTAL"))
                              ->where('t1.ae_code','LIKE','%'.$pernr.'%')
                              ->where('doc_year',$thisyear)
                              ->first();


            return $q;

    }
    
    function customerTitleBudgetThisYear($kunnr,$_pernr = 1) {

            $cur = getPreviousYear(0);
            $pernr = $_pernr;
            
            if($_pernr == 1) {
                  $pernr = session('pernr');
            }
            
            $qbudget = TblBudget::from('tbl_budget as t1')
            ->where('t1.ae_code',$pernr)
            ->where('t1.period','LIKE','%'.$cur.'%')
            ->where('t1.cust_code',$kunnr)
            ->where('t1.isbn','!=','')
            
            ->orderBy('titlename','ASC')
            ->groupBy('t1.isbn')
            ->select('t1.isbn',
                        DB::raw("
                              COUNT(DISTINCT t1.isbn) as CNTISBN,
                              MAX(t1.price) as price,
                              MAX(t1.disc) as disc,
                              MAX(t1.title) as titlename,
                              SUM(t1.qty) as qty,
                              SUM(t1.population) as population,
                              SUM(CAST(REPLACE(t1.value, ',', '') AS DECIMAL(15,2))) AS DOCTOTAL
                              
                              ")
                  )
            ->get();

            return $qbudget;

    }

    function item_search($search) {

            $matdels =  ZmmMatdel::select('*')
                              ->where('ZZLONGTEXT','NOT LIKE', '%Deactivate%')
                              ->where(function ($query) use ($search) {
                              $query->where('EAN11', 'LIKE', '%' . $search . '%')
                                    ->orWhere('ZZLONGTEXT', 'LIKE', '%' . $search . '%');
                              })
                              ->take(20)
                              ->orderBy('ZZLONGTEXT', 'ASC')
                              ->get();

            return $matdels;

    }

    function getISBNDetails($isbn) {


      $q = ZmmMatdel::where('EAN11',$isbn)
                  ->limit(1)
                  ->first();

      return $q;

}
    
//     function topCustomerPrevYear ($year,$pernr = '1') {
//     }
    function topCustomerPrevYear ($year,$pernr = '1') {
        
      
            $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
                        // ->leftJoin('prd.ZSD_OMSD as t2', 't1.ORDNUM', '=', 't2.ORDNUM')
                        ->where('t1.PERNR',$pernr)
                        ->where('t1.ERDAT','LIKE','%'.$year.'%')
                        ->groupBy('t1.KUNNR');

            $kunnr = $q->pluck('t1.KUNNR')
                        ->toArray();

            $ff = CrmMotherLookup::whereIn('CUSTNO',$kunnr)
                        ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
                        ->orderByRaw('CUSTNAME','ASC')
                        ->take(50)
                        ->selectRaw('MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, MAX(RTRIM(CUSTNO)) as CUSTNO, MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, MAX(AE) as AE, MAX(RTRIM(DEPARTMENT)) as DEPARTMENT')
                        ->groupBy('t2.CUSTNO');


            return $ff;

      }
      
            
      function makeContainsPhrase($input) {
            // una, escape double quotes para safe sa CONTAINS
            $escaped = str_replace('"', '""', $input);
            // pangalawa, escape single quote (para di sumabog ang SQL string)
            $escaped = str_replace("'", "''", $escaped);
            // wrap sa double quotes (phrase search)
            return '"' . $escaped . '"';
        }

      function userDetails($pernr) {
            $users =  OPTv2User::where('PERNR', ''.$pernr.'')
                        ->where('ACTIVE','1')
                        ->take(1)
                        ->orderBy('FULLNAME','ASC')
                        ->first();
      
            return $users;

      }

      function discounted_unit_price ($customercode,$isbn,$unitprice) {

            $disc = item_discount ($customercode,$isbn);

            $discValue = $disc / 100;
            $finalDiscount = number_format($discValue, 4, '.', ''); 
            $_unit_price = $unitprice;

            if (is_numeric($_unit_price)) {
                  // Perform multiplication with $unit_price
                  $unit_price = $_unit_price;
              } else {
                  // Handle the case where $unit_price is not numeric
                  $unit_price = 0;
              }

              
            $finalPrice = $unit_price - $unit_price * $finalDiscount;

            return $finalPrice;

      }

      function item_discount ($customercode,$isbn,$vl = 'discount') {

                  $custpos = CustPos::where('KUNNR', '=', $customercode ) 
                  ->take(1)
                  ->first();

                  $matdel = ZmmMatdel::where('EAN11','=',$isbn)
                        ->take(1)
                        ->first();

                  if (!$custpos || !$matdel) {

                        return 0;

                  }

                  $kdgrp  = $custpos->KDGRP;
                  $mvgr1 = $matdel->MVGR1;
                  $kdgrpFinal = '33';

                  if($kdgrp !== '19') {
                        $kdgrpFinal = $kdgrp;
                  } 
            //GET DISCOUNT---

                  $query = ZsdBsah::select('prd.ZSD_BSAH.ORDNUM', 'BOOKINGAGENT', 'MATNR', 'EAN11', 'ITEMDISCOUNT','ZZMAXDISC')
                        ->leftJoin('prd.ZSD_BSAD', 'prd.ZSD_BSAH.ORDNUM', '=', 'prd.ZSD_BSAD.ORDNUM')
                        ->where('EAN11','=',$isbn)
                        ->where('KUNNR','=',$customercode)
                        ->orderBy('TRANSDATE','DESC')
                        ->first();

                  $query_2  = ZsdTs::select('DISCOUNT')
                                    ->where("MVGR1", '=',$mvgr1)
                                    ->where("KDGRP", '=',$kdgrpFinal)
                                    ->first();

                  //---GET DISCOUNT
                  if(!empty($query)) {
                        $disc = $query->ITEMDISCOUNT;
                        $maxdisc = $query->ZZMAXDISC;
                  }
                  else if (!empty($query_2)) {
                        $disc = $query_2->DISCOUNT;
                        $maxdisc = $query_2->MAXDISC;
                  }
                  else {
                        $disc = 0;
                        $maxdisc = 0;
                  }


                  if($vl == 'discount') {
                        $discFinal = $disc;
                  }
                  elseif($vl = 'maxdiscount') {
                        $discFinal = $maxdisc;
                  }
                  else {
                        $discFinal = $vl;
                  }
                  
                  return $discFinal;

            }
            
      function userAccess($id, $col, $setup = false, $parent = false) {
            // $isAdmin = userDetails(session('staff'), 'USERGROUP') === 'admin';
            $isAdmin = userDetails(session('staff'), 'USERGROUP') !== 'admin';
      
            if ($parent) {
                  $count = OPTv2UserAccess::where('USERID', $id)
                        ->where('PARENT', $col)
                        ->count();
            
                  $column = ($isAdmin && !$setup) || $count > 0   ? 1 : 0;
            } else {
                  $userAccess = OPTv2UserAccess::where('USERID', $id)
                        ->where('MENU', $col)
                        ->first();
            
                  $column = ($isAdmin && !$setup) || (!empty($userAccess) && $userAccess->ACCESS == 1)      ? 1 : 0;
            }
      
            return $column;
      }
         
  

      function isbnPrev3YearSalesHistory ($isbn) {


            $cur = getPreviousYear(0);

            $years = [
            $cur - 1,
            $cur - 2,
            $cur - 3,

            ];
      
                  
            $q = NewSales::from('new_sales as t1')
            ->whereIn('t1.isbn',$isbn)
            ->whereIn('t1.doc_year',$years)
            ->groupBy('t1.isbn');
            

            $s = [];
      
            $ll = 0;
            $customerhistory = [];
      
            foreach ($years as $y) {
                  $ll++;
                  
                             
                  $s[] = DB::raw("
                                    MAX(t1.cust_code) as cust_code,      
                                    MAX(t1.isbn) as isbn,
                              SUM(CASE WHEN t1.doc_year = $y 
                              THEN (t1.qty) 
                              ELSE 0 END)    as total_$ll
                        ");
            
            }
      
            $qresults = $q->select($s)->get();
      
      
            return $qresults;
            
      
      }

      function pernrCustomerIsbnPrev3YearSalesHistory ($kunnr,$isbn,$p = 1) {

            $pernr = $p;

            if($p == 1) {
                  $pernr = session('pernr');

            }
            $cur = getPreviousYear(0);
    
            $years = [
                $cur - 1,
                $cur - 2,
                $cur - 3,
    
            ];
          
      //     $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
      //                 ->leftJoin('prd.ZSD_OMSD as t2', 't1.ORDNUM', '=', 't2.ORDNUM')
      //                 ->select(DB::raw('SUM(t2.QTYORDERED) as TOTAL'))
      //                 ->where('t1.PERNR',$pernr)
      //                 ->whereIn(DB::raw('YEAR(t1.ERDAT)'),$years)
      //                 ->where('t1.KUNNR',$kunnr)
      //                 ->whereIn('t2.EAN11',$isbn)
      //                 ->groupBy('t1.KUNNR','t2.EAN11');
                      
            $q = NewSales::from('new_sales as t1')
            ->where('t1.ae_code',$pernr)
            ->whereIn('t1.isbn',$isbn)
            ->whereIn('t1.doc_year',$years);
        

            if($kunnr !== '') {

                  $q->where('t1.cust_code',$kunnr)
                  ->groupBy('t1.isbn','t1.cust_code');

            }
            else {
                 
            }

      //     $s = ['MAX(t1.cust_code)'];
            
          $s = [];
    
          $ll = 0;
          $customerhistory = [];
    
          foreach ($years as $y) {
                $ll++;
                
            //     $s[] = DB::raw("
                                          
            //             MAX(t2.EAN11) as isbn,
            //             SUM(CASE WHEN YEAR(t1.ERDAT) = $y 
            //             THEN (t2.QTYORDERED) 
            //             ELSE 0 END)    as total_$ll
            //       ");
                  
                $s[] = DB::raw("
                              MAX(t1.cust_code) as isbn,      
                              MAX(t1.isbn) as isbn,
                            SUM(CASE WHEN t1.doc_year = $y 
                            THEN (t1.qty) 
                            ELSE 0 END)    as total_$ll
                      ");
           
          }
    
          $qresults = $q->select($s)->get();
    
    
          return $qresults;
          
    
    }

    function convertMonthNumberToName($month) {
      $months = [
          "01" => "January",
          "02" => "February",
          "03" => "March",
          "04" => "April",
          "05" => "May",
          "06" => "June",
          "07" => "July",
          "08" => "August",
          "09" => "September",
          "10" => "October",
          "11" => "November",
          "12" => "December"
      ];
  
      // Pad with zero in case it's not already two digits
      $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
      return isset($months[$month]) ? $months[$month] : 'Invalid';
  }


    function customerPrev3YearSalesHistory ($kunnr = '1') {

        $cur = getPreviousYear(0);

        $years = [
            $cur - 1,
            $cur - 2,
            $cur - 3,

        ];
      
      // $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
      //             ->leftJoin('prd.ZSD_OMSD as t2', 't1.ORDNUM', '=', 't2.ORDNUM')
      //             ->select(DB::raw('SUM(t2.QTYORDERED) as TOTAL'))
      //             ->whereIn(DB::raw('YEAR(t1.ERDAT)'),$years)
      //             ->groupBy('t1.KUNNR');
        
      //             if($kunnr !== '1') {
      //                   $q->whereIn('t1.KUNNR',$kunnr);
      
      //             }
                 
      $q = NewSales::from('new_sales as t1')
                  ->select(DB::raw('SUM(t1.qty) as TOTAL'))
                  ->whereIn('t1.doc_year',$years)
                  ->whereIN('t1.cust_code',$kunnr)
                  ->groupBy('t1.cust_code');

      // $s = ['t1.KUNNR'];
      $s = ['t1.cust_code'];

      $ll = 0;
      $customerhistory = [];

      foreach ($years as $y) {
            $ll++;
            
            // $s[] = DB::raw("
            //             SUM(CASE WHEN YEAR(t1.ERDAT) = $y 
            //             THEN (t2.QTYORDERED) 
            //             ELSE 0 END) as total_$ll
            //       ");

            
            $s[] = DB::raw("
                        '".$y."' as year,
                        SUM(CASE WHEN t1.doc_year = $y 
                        THEN (t1.qty) 
                        ELSE 0 END) as total_$ll
                  ");
       
      }

      $qresults = $q->select($s)->get();


      return $qresults;
      

}

      function getAllocatedBalance ($basedocnum,$pernr,$isbn) {

      }

      function getAllocatedMainProjectionDeductSOH ($basedocnum,$isbn,$pernr = '1') {

        
            
            $_qisbnforapprovalfinalreq = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
                                                ->select(
                                                "PERNR"
                                                )
                                                ->where('BASEDOCNUM',$basedocnum)
                                                ->whereIn('EAN11',$isbn)
                                                // ->whereNotNull('t1.APPROVED')
                                                ->groupBy('PERNR')
                               ;
            if($pernr !== '1') {
                  $_qisbnforapprovalfinalreq->whereIn("PERNR",$pernr);
            }                                       

            $qpernrlistfinalreq = $_qisbnforapprovalfinalreq->pluck('PERNR')
                                                            ->toArray();
              
            $qprojperioddetails = projection_period_details($basedocnum);
            $mainprojection = $qprojperioddetails->SUPPLEMENTAL;

            $qgetallundermainprojection = OPTv2ProjectionPeriod::where('SUPPLEMENTAL',$mainprojection)
                                                      ->pluck('DOCNUM')
                                                      ->toArray();

            $qallocated = OPTv2Allocated::whereIn('EAN11', $isbn)
                              ->whereIn('BASEDOCNUM',$qgetallundermainprojection)
                              ->groupBy('EAN11')
                              ->where('ALLOCATED','<>','0')
                              ->selectRaw("
                                    EAN11,
                                    SUM(CAST(ALLOCATED AS INT)) AS TOTALALLOCATED
                              ");

            if($pernr !== '1') {
                  $qallocated->whereIn("PERNR",$pernr);
            }  
             
            $qq = $qallocated->get();
            return $qq;
            

      }
      function getAllocatedQty ($isbn,$pernr,$alloctype,$basedocnum) {

            $query = OPTv2Allocated::where('EAN11', $isbn)
            ->where('PERNR',$pernr)
            ->where('ALLOCTYPE',$alloctype)
            ->where('BASEDOCNUM',$basedocnum)
            ->get();

            $p = 0;

            foreach($query as $s) {
                  $p = $s->QTY; 

            }

            return $p;


      }

      function curprojtn_customer_pernr ($customercode,$pernr) {

            $curyear = getPreviousYear(0);
            $qcurprojtn = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1') 
                                          ->leftjoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM') 
                                          ->selectRaw("
                                                      CUSTOMER,
                                                       SUM(CASE WHEN t1.STATUS = 'approved' THEN CAST(t1.QTY AS INT) ELSE 0 END) AS TOTALPROJTN
                                          ")
                                          ->whereIn('CUSTOMER',$customercode)
                                          ->where('t1.PERNR',$pernr)
                                          ->where('t2.DOCDATE','LIKE','%'.$curyear.'%')
                                          ->groupBy('CUSTOMER')
                                          ->get();

            return $qcurprojtn;

      }
      function pernrqty_stockallocate_approved_isbn ($basedocnum,$isbn,$username) {

            $type = [
                  '0',
                  '1'
            ];

      
      
            $e = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftJoin('OPTV2PROJECTIONH as t2','t1.DOCNUM','=','t2.DOCNUM')
            ->select(
                "EAN11",
                DB::raw("MAX(t2.PERNR) as PERNR"),
                DB::raw("MAX(t2.USERNAME) as USERNAME"),
                DB::raw("MAX(DESCRIPTION) as DESCRIPTION"),
                DB::raw("MAX(AUTHOR) as AUTHOR"),
                DB::raw("(SELECT PROPOSEREQQTY FROM OPTV2FINALREQ t3 WHERE t3.EAN11 = t1.EAN11 AND t3.BASEDOCNUM = '".$basedocnum."' ) as PROPOSEREQQTY")
                )
            ->where('t1.USERNAME',$username)
            ->where('t1.BASEDOCNUM',$basedocnum)
            ->where('t1.EAN11',$isbn)
            ->whereNotNull('t1.APPROVED')
            ->groupBy('t1.EAN11','t1.USERNAME')
            // ->where('BSA','0')
            // ->get()
            ;

            foreach($type as $tp ) {

                  $s[] = DB::raw("
                       
                        SUM(CASE WHEN BSA = $tp
                        THEN (CAST(QTY AS INT)) 
                        ELSE 0 END)    as bsa_$tp
                  ");

            }
            
            $q = $e->select($s)
                  ->get();

            return $q;

      }

      function pernr_customer_check_bsa_status ($kunnr,$pernr = 1) {

            $pr = $pernr;
            if($pernr == 1) {
                  $pr = session('pernr');
            }

            $ff = CrmMotherAct::where('t2.CUSTNO',$kunnr)
            ->where('AE','LIKE','%'.$pr.'%')
            ->where('t2.KTOKD','BRSA')
            ->leftjoin('prd.CRMMOTHERACTDEPT as t2', function ($join) {
                        $join->on('prd.CRMMOTHERACT.CUSTNO','=','t2.CUSTNO')
                              ->on('prd.CRMMOTHERACT.MOTHERACCT','=','t2.MOTHERACCT');

            })
            // ->leftjoin('prd.CRMMOTHERACTDEPT as t2','prd.CRMMOTHERACT.CUSTNO','=','t2.CUSTNO')
            ->limit(1)
            ->first();

            $bsa = 0;
            if(!empty($ff)) {
                  $bsa = 1;
            }

            return $bsa;

      }

    function find_customer ($kunnr) {
            // $querya = CrmMotherLookup::where(function( $querya) use ($customer) {
            //       $querya->where('CUSTNO',$customer)
            //               ->orwhereRaw("CONTAINS(TAGS, '\"".$customer."\"')");
            //            })
            $ff = CrmMotherLookup::where('CUSTNO',$kunnr)
            ->leftjoin('prd.CRMMOTHERACT as t2','prd.CRMMOTHERLOOKUP.ACCTNO','=','t2.MOTHERACCT')
            ->orderByRaw('CUSTNAME','ASC')
            ->take(50)
            ->selectRaw('
                              MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME, 
                              MAX(RTRIM(CUSTNO)) as CUSTNO, 
                              MAX(RTRIM(MOTHERACCT)) as MOTHERACCT, 
                              MAX(AE) as AE, 
                              MAX(RTRIM(DEPARTMENT)) as DEPARTMENT
                           
                              '
                              )
            ->groupBy('t2.CUSTNO');

            return $ff;

      }

      function includeCustomerLinkTo ($pernr) {
 
            $ff = OPTv2CustomerLink::where('TOPERNR',$pernr)
                              ->where('STATUS','1')
                              ->pluck('CUSTOMER')
                              ->toArray();

            return $ff;

      }

      function excludeCustomerLinkFrom ($pernr) {
 
            $ff = OPTv2CustomerLink::where('FROMPERNR',$pernr)
                              ->where('STATUS','1')
                              ->pluck('CUSTOMER')
                              ->toArray();

            return $ff;

      }


    function tt ($username,$col){

      $users =  OPTv2User::where('USERNAME', ''.$username.'')
                        ->take(1)
                        ->first();

            if(!empty($users)) {
                  $column = $users->$col;
            }
            else {
                  $column = "";
            }

                  return $users;
}

    function createLogs($reference, $remarks,$logtype) {

      $date_now = date_now();
      $pernr = session('pernr');

            $createLogs = OPTv2Logs::create([
                        "REFERENCE" => $reference,
                        "REMARKS" => $remarks,
                        "USERID" => $pernr,
                        "DOCDATE" => $date_now,
                        "LOGTYPE" => $logtype,
            ]);

    }
    function time_elapsed_string($datetime, $textAdd = '',$_now = '1',$full = false) {
         
            if($_now == "1") {
                  $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
            }
            else {
                  $now = new DateTime($_now, new DateTimeZone('Asia/Manila'));
            }

            $ago = new DateTime($datetime, new DateTimeZone('Asia/Manila'));
            $diff = $now->diff($ago);
      
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
      
            $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hr',
            'i' => 'min',
            's' => 'second',
            );
            foreach ($string as $k => &$v) {
            if ($diff->$k) {
                  $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                  unset($string[$k]);
            }
            }
      
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . $textAdd : 'just now';
      }

      function newNotification ($desc,$to,$docdate,$refnum ="",$url = null,$status='0') {     

            $pernr = session('pernr');
            $insertNotificationIT =    OPTv2Notif::create([
                  "DESCRIPTION"=> $desc,	
                  "FRUSERID"	=> $pernr,	
                  "REFNUM"	=> $refnum,	
                  "TOUSERID"	=> $to,	
                  "DOCDATE"	=> $docdate,		
                  "STATUS"	=> $status,		
                  "URL"		=> $url
               
              ]);

      }


      function strpad ($id,$int=4) {
            
      
            $strpad = str_pad($id, $int, '0', STR_PAD_LEFT);

	      return $strpad;

       }

      function mainprojectionlist() {

       

            $query = OPTv2ProjectionPeriod::where('PROJECTIONTYPE','mainprojection')
                                    ->orderBy('id','DESC')
                                    ->get();
            return $query;


      }

      function get_soh_isbn_per_location($isbn) {

            $query = Mard::from('prd.MARD as t1')
                  ->join('prd.MARA as t2',function($q) {
                        $q->on('t1.MANDT','=','t2.MANDT')
                        ->on('t1.MATNR','=','t2.MATNR');

                        
                  })
                  ->join('prd.T001L as t3',function($q) {
                        $q->on('t1.MANDT','=','t3.MANDT')
                        ->on('t1.LGORT','=','t3.LGORT')
                        ->on('t1.WERKS','=','t3.WERKS')
                        ;

                        
                  })
                  ->where('t1.LABST','<>','0')
                  ->where('t1.MANDT','888')
                  ->where('t2.EAN11',$isbn)
                  // ->limit(1)
                  ->selectRaw('
                        t1.MANDT, 
                        t1.MATNR, 
                        t2.EAN11, 
                        t3.WERKS, 
                        t3.LGORT, 
                        LABST, 
                        LGOBE
                        ')
                  ->orderBy('LGOBE','ASC')
                  
                  ->get();

      return $query;


      }
      function getUserDivisionList() {

            $div = OPTv2User::select(DB::raw("LTRIM(RTRIM(DIVISION)) as DIVISION"))
                              ->whereNotNull('DIVISION')
                              ->where('DIVISION', '!=', '')
                              ->where('DIVISION', '!=', 'ALL')
                              ->orderBy(DB::raw('LTRIM(RTRIM(DIVISION))'), 'ASC')
                              ->groupBy(DB::raw("LTRIM(RTRIM(DIVISION))"))
                              ;


            return $div;

      }

      function modifyAllocated($isbn,$pernr, $basedocnum, $alloctype,$qty,$modtype = 'add',$projection = 0) {
            
            $date_now = date_now();
            $status = '404';

            $query = OPTv2Allocated::where('EAN11', $isbn)
                                    ->where('PERNR',$pernr)
                                    ->where('ALLOCTYPE',$alloctype)
                                    ->where('BASEDOCNUM',$basedocnum)
            ;
            $queryFirst = $query->first();
      
   
            $qprojperiod = projection_period_details($basedocnum);
            $qisbndetails = getISBNDetails($isbn);
            $matnr = $qisbndetails->MATNR;
            $description = $qisbndetails->MAKTX;
            $projectionid = $qprojperiod->PROJECTIONID;

            
            $alloctypeFinal = $alloctype;
            if($alloctype == 'nonbsa') {
                $alloctypeFinal = 'nbsa';

            }
            $pernrleadzeros = ltrim(trim($pernr), '0');

            if ($matnr !== "N/A") {

           
            if (empty($queryFirst)) {
               
                  OPTv2Allocated::create([
                        "MATNR" => $matnr,
                        "EAN11" => $isbn,
                        // "DESCRIPTION" => $description,
                        "BASEDOCNUM" => $basedocnum,
                        "PROJECTIONID" => $projectionid,
                        "ALLOCTYPE" => $alloctype,
                        "QTY" => $qty,
                        "PERNR" => $pernr,
                        "PROJECTION" => $projection,
                        "created_at" => date_now(),
                        "updated_at" => date_now()
                  ]);
      
                  CrmAllocationHeader::create([
                        "isbn" => $isbn,
                        "aActual" => 0,
                        "aType" => $alloctypeFinal,
                        "aAE" => $pernrleadzeros,
                        "aBatch" => $basedocnum,
                        "matnr" => $matnr,
                        "aQty" => $qty,
                        "DateAdded" => date_now(),
                        "DateUpdated" => date_now(),
                        "prsCode" => '-',
                        "prCode" => '-',
                        "poCode" => '-',
                  ]);
     

                  $status = '2';
            } else {
                 
                  $crmallocquery = CrmAllocationHeader::where('isbn', $isbn)
                  ->where('aAE',$pernrleadzeros)
                  ->where('aType',$alloctypeFinal)
                  ->where('aBatch',$basedocnum)
;

                  $curAllocQty = $queryFirst->QTY;
      
                  if ($modtype == 'add') {
                        $query->update([
                        "QTY" => $curAllocQty + $qty
                        ]);
      
                        $crmallocquery->update([
                              "aQty" => $curAllocQty + $qty
                        ]);
                        
                        $status = '2';

                  } elseif ($modtype == 'deduct') {

                        $query->update([
                        "QTY" => $curAllocQty - $qty
                        ]);
      
                        $crmallocquery->update([
                              "aQty" => $curAllocQty - $qty
                        ]);
             
                        $status = '2';

                  } else {
                        
                        $status = '404';
                  }
            }
            } else {
         
                  $status = '404';
            }
      
            // Return the status
            return $status;
      }

      function get_soh_isbn($isbn) {

       
            $exclSOH = OPTv2SOHQtyExc::where('STATUS','0')
                                    ->pluck('SLOC')
                                    ->toArray();

            
            $query = Mard::from('prd.MARD as t1')
            ->join('prd.MARA as t2',function($q) {
                  $q->on('t1.MANDT','=','t2.MANDT')
                  ->on('t1.MATNR','=','t2.MATNR');

                  
            })
            ->where('t1.MANDT','888')
            ->whereIn('t2.EAN11',$isbn)
            ->whereNotIn('t1.LGORT',$exclSOH)
            // ->whereNotIn()
            // ->limit(1)
            ->selectRaw('
                  SUM(LABST) as SOHQTY,
                  t1.MANDT, 
                  t1.MATNR, 
                  t2.EAN11
                  ')
            ->groupBy('t1.MANDT','t1.MATNR', 't2.EAN11')
            ->get();

          
            return $query;


      }

      function get_pullout_isbn($isbn) {

       

            $query = ZsdOmsh::from('prd.ZSD_OMSH as t1')

                  ->Join('prd.ZSD_OMSD as t2', function($q) {
                        $q->on('t1.ORDNUM', '=', 't2.ORDNUM')
                              ->on('t1.MANDT', '=', 't2.MANDT');

                  })
                  ->selectRaw('
                        t1.MANDT, 
                        t2.MATNR, 
                        t2.EAN11, 
                        SUM(t2.QTYORDERED) as PULLOUTQTY 
                  ')
                  ->where('t1.MANDT','888')
                  ->where('t1.SOTYPE','ZKA1')
                  ->where('t1.UPLOADTAG','')
                  ->where('t1.DISAPPROVE','<>','1')
                  ->whereIn('t2.EAN11',$isbn)
                  ->groupBy('t1.MANDT','t2.MATNR','t2.EAN11')
                  ->get();


            return $query;


      }

      
      function get_onpo_isbn($isbn) {

            // where(function( $querya) use ($customer)

            $query = Ekko::from('prd.EKKO as t1')

                        ->Join('prd.EKPO as t2', function($q) {
                              $q ->on('t1.MANDT', '=', 't2.MANDT')
                                    ->on('t1.EBELN', '=', 't2.EBELN')
                                   ;

                        })
                        ->selectRaw('
                              t1.EBELN, 
                              t2.MATNR, 
                              t2.EAN11, 
                              t2.MENGE as ONPOQTY 
                        ')
                        
                        ->where('t1.MANDT','888')
                        ->where(function($q) {
                              $q->where('t1.EBELN', 'like', '47%')
                              ->orWhere('t1.EBELN', 'like', '49%')
                              ->orWhere('t1.EBELN', 'like', '57%')
                              ->orWhere('t1.EBELN', 'like', '68%');
                              

                        })
                        ->where('t2.LOEKZ','<>','L')
                        ->where('t2.WEPOS','<>','X')
                        ->whereIn('t2.EAN11',$isbn)
                        ->get();

                  return $query;


      }
      function get_year_omstransact_isbn($isbn,$pernr = '1',$year = '1') 
      {

            $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')

                  ->Join('prd.ZSD_OMSD as t2', function($q) {
                        $q->on('t1.ORDNUM', '=', 't2.ORDNUM')
                              ->on('t1.MANDT', '=', 't2.MANDT');

                  })
                  ->selectRaw('
                        t1.MANDT, 
                        t2.MATNR, 
                        t2.EAN11, 
                        SUM(t2.QTYORDERED) as OMSQTY 
                  ')
                  ->where('t1.MANDT','888')
                  ->whereNotIn('t1.SOTYPE',['ZKE','ZKA','ZKB'])
                  ->where('t1.UPLOADTAG','')
                  ->where('t1.DISAPPROVE','<>','1')
                  ->whereIn('t2.EAN11',$isbn)
                  ->groupBy('t1.MANDT','t2.MATNR','t2.EAN11')
                  ;

            if($pernr !== '1') {

                  $pernrlist[] = $pernr;
                  $q->whereIn('t1.PERNR',$pernrlist);
                  
            }

            $query = $q->get();
            return $query;

      }
      function get_omstransact_isbn($isbn,$pernr = '1') {

       

            $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')

                  ->Join('prd.ZSD_OMSD as t2', function($q) {
                        $q->on('t1.ORDNUM', '=', 't2.ORDNUM')
                              ->on('t1.MANDT', '=', 't2.MANDT');

                  })
                  ->selectRaw('
                        t1.MANDT, 
                        t2.MATNR, 
                        t2.EAN11, 
                        SUM(t2.QTYORDERED) as OMSQTY 
                  ')
                  ->where('t1.MANDT','888')
                  ->whereNotIn('t1.SOTYPE',['ZKE','ZKA','ZKB'])
                  ->where('t1.UPLOADTAG','')
                  ->where('t1.DISAPPROVE','<>','1')
                  ->whereIn('t2.EAN11',$isbn)
                  ->groupBy('t1.MANDT','t2.MATNR','t2.EAN11')
                  ;

            if($pernr !== '1') {

                  $pernrlist[] = $pernr;
                  $q->whereIn('t1.PERNR',$pernrlist);
                  
            }

            $query = $q->get();
            return $query;

            //get allocation_cancel------------------------
                  //     $allocused_cancel=DB::table(
                  //       DB::raw("(select zod.MATNR, zod.QTYORDERED, ISNULL(dt.NORD, 0) as nOrd
                  //   from prd.ZSD_OMS_SSY zoy
                  //   join prd.ZSD_OMSH zoh on zoy.MANDT = zoh.MANDT and zoy.ORDNUM = zoh.ORDNUM
                  //   join prd.ZSD_OMSD zod on zoy.MANDT = zod.MANDT and zoy.ORDNUM = zod.ORDNUM
                  //   left join
                  //       (SELECT vk.BSTNK, vk.MANDT, vp.MATNR, vp.KWMENG, ls.LFIMG, (vp.KWMENG - ls.LFIMG) AS NORD
                  //       FROM prd.VBAK vk
                  //           INNER JOIN prd.VBAP vp ON vk.MANDT = vp.MANDT AND vk.VBELN = vp.VBELN
                  //           INNER JOIN prd.LIPS ls ON vp.MANDT = ls.MANDT AND vp.VBELN = ls.VGBEL AND vp.MATNR = ls.MATNR
                  //       WHERE vk.MANDT = '".env('MANDT', '')."'
                  //           AND ls.LFIMG IS NOT NULL
                  //           AND vp.KWMENG <> ls.LFIMG
                  //       ) dt on dt.BSTNK = zoy.ORDNUM and dt.MANDT = zoy.MANDT and dt.MATNR = zod.MATNR
                  //       where zoy.MANDT='".env('MANDT', '')."'
                  //           and zoh.PERNR = '".$PERNR."'
                  //           and zod.EAN11='".$isbn."'
                  //           and zoy.BATCHID='".$batch1."'
                  //           and zoy.SCHOOLOPENING='".$dPeriod."'
                  //           and zoh.SOTYPE!='ZKB1'
                  //           and zoh.DISAPPROVE='') as a"))
                  //    ->select('a.MATNR',DB::raw('sum(a.QTYORDERED) as allocationUsed'),DB::raw('sum(a.nOrd) as qtyCancelled'))
                  //   ->groupBy('a.MATNR')
                  //       ->get();


      }

      //ADDED BY EMRICK oct 6 2026
      function get_year_omstransact_isbn_pernr($pernr, $basedocnum)
      {
            $projectionPeriod = projection_period_details($basedocnum);

            if (!$projectionPeriod) {
                  return collect();
            }

            // Example: "2026-2027" -> 2026
            $schoolYear = trim($projectionPeriod->YEAR);
            $yearParts  = explode('-', $schoolYear);
            $year       = trim($yearParts[0]);

            $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
                  ->join('prd.ZSD_OMSD as t2', function ($q) {
                        $q->on('t1.ORDNUM', '=', 't2.ORDNUM')
                        ->on('t1.MANDT', '=', 't2.MANDT');
                  })
                  ->selectRaw('
                        t1.MANDT,
                        t1.PERNR,
                        t2.MATNR,
                        t2.EAN11,
                        SUM(t2.QTYORDERED) as OMSQTY
                  ')
                  ->where('t1.MANDT', '888')
                  ->whereNotIn('t1.SOTYPE', ['ZKE', 'ZKA', 'ZKB'])
                  ->where('t1.UPLOADTAG', '')
                  ->where('t1.DISAPPROVE', '<>', '1')
                  ->where('t1.ERDAT', 'LIKE', '%' . $year . '%')
                  ->whereNotNull('t2.EAN11')
                  ->groupBy(
                        't1.MANDT',
                        't1.PERNR',
                        't2.MATNR',
                        't2.EAN11'
                  );

            if (!empty($pernr)) {
                  if (is_array($pernr)) {
                        $q->whereIn('t1.PERNR', $pernr);
                  } else {
                        $q->where('t1.PERNR', $pernr);
                  }
            }

            return $q->get();
      }


      function get_year_omstransact_isbn_pernr_pertitle($isbn, $pernr, $basedocnum)
      {
            $projectionPeriod = projection_period_details($basedocnum);

            if (!$projectionPeriod) {
                  return 0;
            }

            // Example: "2026-2027" -> 2026
            $schoolYear = trim($projectionPeriod->YEAR);
            $yearParts  = explode('-', $schoolYear);
            $year       = trim($yearParts[0]);

            $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
                  ->join('prd.ZSD_OMSD as t2', function ($q) {
                        $q->on('t1.ORDNUM', '=', 't2.ORDNUM')
                        ->on('t1.MANDT', '=', 't2.MANDT');
                  })
                  ->where('t1.MANDT', '888')
                  ->whereNotIn('t1.SOTYPE', ['ZKE', 'ZKA', 'ZKB'])
                  ->where('t1.UPLOADTAG', '')
                  ->where('t1.DISAPPROVE', '<>', '1')
                  ->where('t1.ERDAT', 'LIKE', '%' . $year . '%')
                  ->where('t1.PERNR', $pernr)
                  ->where('t2.EAN11', $isbn)
                  ->selectRaw('COALESCE(SUM(t2.QTYORDERED), 0) as OMSQTY')
                  ->first();

            return $q ? (float) $q->OMSQTY : 0;
      }

      function projection_period_details($basedocnum) {


            $query = OPTv2ProjectionPeriod::where('DOCNUM',$basedocnum)
                                    ->first();


                      
            return $query;


      }

      function allocReqHDetails($docnum) {
          $h =  OPTv2AllocReqh::where('DOCNUM',$docnum)
                              ->first();

            return $h;

      }
      function updateLinenumprojectiond($docnum) {

            $rows = OPTv2Projectiond::where('DOCNUM', $docnum)
            ->orderByRaw('LINENUM + 0 ASC')
            ->lockForUpdate()
            ->get();
    
            $line = 1;
    
            foreach ($rows as $row) {
                $row->update([
                    'LINENUM' => $line++
                ]);
            }

      }
      function projectionDetails($id,$username) {


            $q = OPTv2Projectionh::where('BASEDOCNUM',$id)
                                    ->where('USERNAME',$username)
                                    ->selectRaw("*,
                                          
                                                (SELECT COUNT(DISTINCT APPROVER1) FROM OPTV2PROJECTIONH b 
                                                      WHERE 
                                                      BASEDOCNUM = '".$id."' AND 
                                                      USERNAME = '".$username."' AND 
                                                      APPROVER1 IS NOT NULL) as CNTAPPROVER1,
                                                (SELECT COUNT(DISTINCT APPROVER2) FROM OPTV2PROJECTIONH b 
                                                      WHERE 
                                                      BASEDOCNUM = '".$id."' AND 
                                                      USERNAME = '".$username."' AND 
                                                      APPROVER1 IS NOT NULL) as CNTAPPROVER2
                                    ")
                                    ->first();
             
                            
            return $q;


      }

      function projection_period_display($id) {


            $query = OPTv2ProjectionPeriod::where('DOCNUM',$id)
                                    ->first();
             
            $projectionid =   $query->PROJECTIONID;                      
            $level =   $query->LEVEL;                      
            $year =   $query->YEAR;                      
            $period =   $query->PERIOD;                      
            $projectionidDisplay =  'PID: ' . $projectionid . ' | Level: ' . $level . ' | Year: ' . $year . ' | ' . periodList()[$period];

                      
            return $projectionidDisplay;


      }

      

    function formatDate($date,$type = "datetime") {
        
            if (is_null($date)) {
            $finalDate = "";
            } else {
            $dateCreate = date_create($date);
                  if($type == "datetime") {
                        $finalDate = date_format($dateCreate, "F j, Y, g:i a");
                  }
                  elseif($type== "time") {
                        $finalDate = date_format($dateCreate, "g:i a");
                  }
                  elseif($type== "monthday") {
                        $finalDate = date_format($dateCreate, "F j ");
                  }
                  elseif($type== "y-m-d") {
                        $finalDate = date_format($dateCreate, "Y-m-d");
                  }
                  elseif($type== "mdy") {
                        $finalDate = date_format($dateCreate, "m/d/Y");
                  }
                  elseif($type== "mdyts") {
                        $finalDate = date_format($dateCreate, "m/d/Y g:i a");
                  }
                  else {
                        $finalDate = date_format($dateCreate, "F j, Y ");
                  }
            
            }
            return $finalDate;
      }

      


      function getPreviousYear(int $offset = 1): int {
            return now()->subYears($offset)->year;
        }

      function reference_details ($refnum,$col = '') {

         
            $query = OPTv2PreITIRef::where('REFNUM', $refnum)
                                    ->first();

            if(empty($query)) {
                  $colDisplay = '';
            }
            else {
                  $colDisplay = $query->$col;
            }

            return $colDisplay;

      }


    function br_second_word($string)
    {
         
          $words = explode(' ', $string);
          
          if (count($words) >= 2) {
       
                $words[0] .= '<br>';
          }
          
          // Join the words back together
          $newString = implode(' ', $words);

          return $newString;
    }

      function generateRandomString($length = 6) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
      
            for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
      
            return $randomString;
      }


      function extractIdFromToken(string $token): string
      {
            $len = ord($token[0]) - 65;       // reverse ng chr(65 + len)
            return substr($token, 1, $len);
      }


  
      function secureTokenWithId(string $id, int $randLen = 25): string
      {
      $len = strlen($id);               // e.g. 1..10
      $prefix = chr(65 + $len);         // 1->'B', 2->'C', 3->'D', ... (A reserved)
      return $prefix . $id . generateRandomString($randLen);
      }


function capitalizeDecent($string) {
      $exceptions = ['or', 'the', 'and', 'is', 'of', 'for','to']; // Add more words as needed
      $words = explode(' ', strtolower($string));
      $newWords = [];
      
      foreach ($words as $word) {
            if (!in_array($word, $exceptions)) {
                  $word = ucfirst($word);
            }
            $newWords[] = $word;
      }
      
      return implode(' ', $newWords);
      }
      
    function mobile() {
	      $useragent = $_SERVER['HTTP_USER_AGENT'];

            if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            {

                  return 1;
            }
            else {
		      return 0 ;  
            }
      }

      function date_now($type = "date") {
            $date = new DateTime('now', new DateTimeZone('Asia/Manila'));
       

           if($type == 'dateonly') {
                 $date_now = $date->format('Y-m-d');             
           } 
           else if($type== 'year') {
                 $date_now = $date->format('Y');  
           }
           else if($type =='Ymd') {
                 $date_now = $date->format('Ymd'); 
           }
           else {
                 $date_now = $date->format('Y-m-d H:i:s');       
           }

           return $date_now;   
   }


      function last3YearsSchoolYearFormat($a = 'schoolyearformat') {
          
            $currentYear = now()->year;

            $s = [];
  
            for ($i = 0; $i <= 2; $i++){ 

                  
                  if($a == 'schoolyearformat') {
                        
                        $s[] =  ($currentYear + $i - 1 ) . '-' . ($currentYear + $i);

                  } else {

                        $s[] =  $currentYear + $i - 1;
                  }

                
            }

            
            return $s;


      }

      function periodList() {
          
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

      // function topCustomerPrevYear ($year,$kunnr,$pernr = '1') {
      //       $q = ZsdOmsh::from('prd.ZSD_OMSH as t1')
      //       ->leftJoin('prd.ZSD_OMSD as t2', 't1.ORDNUM', '=', 't2.ORDNUM')
      //       ->where('t1.PERNR',$pernr)
      //       ->where('t1.ERDAT','LIKE','%'.$year.'%')
      //       ->groupBy('t1.KUNNR');

      //       return $q;
      // }


      function ProjectionPeriodList($active = '1', $level = '1',$pernr = '1',$submitted = '1',$showallnofilter="0") {


            $cur = getPreviousYear(0);

            // $q = ProjectionPeriodList('0',trim(session('division')))->get();

            $rank = trim(session('rank'));

            $query = OPTv2ProjectionPeriod::orderBy('id','DESC')
                                    ->where('YEAR','LIKE','%'.$cur.'-%');
              

            $salesteamrank = [
                  'AE',
                  'RSM',
                  'SSM'
            ];

                  
            if(in_array(trim($rank),$salesteamrank)) {


                  if($showallnofilter == '0') {

                        
                        if($active == '1') {
                              $query->where('STATUS','1');
                        }
                  
                        if($level !== '1') {
                              $query->where('LEVEL','LIKE','%'.$level.'%');
                        }    
                        

                        $x = OPTv2Projectionh::where('PERNR',$pernr)
                                          ->where('SUBMIT','1')
                                          ->groupBy('BASEDOCNUM')
                                          ->pluck('BASEDOCNUM')
                                          ->toArray();

                        if($submitted == '1') {
                              $query->whereNotIn('DOCNUM',$x);
                        }
                        else {
                              $query->whereIn('DOCNUM',$x);

                        }
            
                        

                  }
                  else {

                  }
                        
            }
            return $query;
          

      }


      function userNameDetails($username) {
            $users =  OPTv2User::where('USERNAME', ''.$username.'')
                        ->take(1)
                        ->first();

            return $users;

      }
      
 

      function userteam() {

            $currentpernr = session('pernr');
      
            $pernr = trim($currentpernr);
      
            $teamquery = OPTv2User::where(function ($teamquery) use ($pernr) {
                                                $teamquery->where('RSM','LIKE','%'.$pernr.'%')
                                                      ->orWhere('SSM','LIKE','%'.$pernr.'%');
                                    })  
                                    ->where('ACTIVE','1')
                                    ->pluck('PERNR')
                                    ->toArray();
            return $teamquery;
      }
      
 
      function arrayFilterPernr() {

            $filterPernr = filter_user_list('0')->get();
            $arrayFilterPernr= [];
    
            foreach($filterPernr as $h){
                    $arrayFilterPernr[] = $h->PERNR;
            }

            return $arrayFilterPernr;
      }


      
      function acronymFullWord($val) {
            if ($val == 'nonbsa') {
                $value = "Non-BSA";
            } else if ($val == 'bsa') {
                $value = "BSA";
            } else {
                $value = $val;
            }
        
            return $value;
        }
        

      function type_display($v) {

            $text = [
                  'bsa_to_nonbsa' => ['primary', 'BSA to Non-BSA'],
                  'bsa_to_bsa' => ['primary', 'BSA to BSA'],
                  'nonbsa_to_bsa' => ['primary', 'Non-BSA to BSA'],
                  'nonbsa_to_nonbsa' => ['primary', 'Non-BSA to Non-BSA'],
              
              ];

              if (isset($text[$v])) {

                  $t = $text[$v];

                  $aa = $t[1];
    
                
              } else {
    
                  $aa = $v;
                 
              }

              return $aa;

            
      }

      function status_display($val, $type = 'badge',$fs= '0.7',$additional_text = '',$title = '') 
      {
          $style = 'style="font-size:0.8rem; position:sticky !important;"';
          
          $badges = [
              'saved' => ['primary-500', 'Saved'],
              'pending' => ['warning', 'Pending'],
              'for_rsm_approval' => ['warning', 'For RSM Approval'],
              'for_imd_approval' => ['primary', 'For IMD Approval'],
              'for_ssm_approval' => ['info', 'For SSM Approval'],
              'for_ae_rsm_approval' => ['warning-500', "For AE's RSM Approval"],
              'for_ae_ssm_approval' => ['info-600', "For AE's SSM Approval"],
              'for_ae_approval' => ['primary-600', 'For AE Approval'],
              'for_avp_approval' => ['primary', 'For AVP Approval'],
              'approved' => ['success', 'Approved'],
              'rsm_approved' => ['success', 'RSM Approved'],
              'ssm_approved' => ['success', 'SSM Approved'],
              'cc_approved' => ['success', 'CC Approved'],
              'avp_approved' => ['success', 'AVP Approved'],
              'imd_approved' => ['success', 'IMD Approved'],
              'returned_isbn' => ['warning', 'Returned'],
              'returned' => ['warning', 'Returned'],
              'cancelled' => ['danger', 'Cancelled'],
              'rsm_disapproved' => ['danger', 'RSM Disapproved'],
              'ssm_disapproved' => ['danger', 'SSM Disapproved'],
              'ae_rsm_disapproved' => ['danger', "AE's RSM Disapproved"],
              'ae_ssm_disapproved' => ['danger', "AE's SSM Disapproved"],
              'avp_disapproved' => ['danger', 'AVP Disapproved'],
              'cc_disapproved' => ['danger', 'CC Disapproved'],
              'imd_disapproved' => ['danger', 'IMD Disapproved'],
              'ae_disapproved' => ['danger', 'AE Disapproved'],
          ];
      
          // Check if $val exists in the $badges array
          if (isset($badges[$val])) {

              $badge = $badges[$val];

              if ($type == 'badge') {

                  $style = 'style="font-size:'.$fs.'rem; position:sticky !important;"';
                  $status = '<span ' . $style . ' class="badge bg-' . $badge[0] . '" title="'.$title.'"><span class="badge-label">' . $badge[1] . $additional_text . '</span></span>';

              } else {
                  // Return plain text if $type is not 'badge'
                  $status = "<span class='text-".$badge[0]."' title='".$title."'>" . strip_tags($badge[1]) . $additional_text . "</span>";
              }
          } else {

                  if($val == '' && empty($val)){
                        $status = '-';
                  } else{
                        
                        $status = $val . $additional_text;
                        
                  }
             
          }
      
          return $status;
      }

      function rankView(...$ranks)
      {
          // Get current user's rank from session
          $userRank = trim(session('rank') ?? '');
  
          // Always allow admin
          if (stripos($userRank, 'admin') !== false) {
              return true;
          }
  
          // If no ranks passed, return false (except admin)
          if (empty($ranks)) {
              return false;
          }
  
          // Check if the user rank matches any of the provided ones
          foreach ($ranks as $rank) {
              if (stripos($userRank, $rank) !== false) {
                  return true;
              }
          }
  
          return false;
      }


      function filter_user_list_in_projection($includemypernr = '1') {
      
            $staff = session('staff');
            $user_staff = session('user_staff');
            $pernr = trim(session('pernr'));
            $rsm = session('rsm');
            $ssm = session('ssm');
            $rank = session('rank');
            $aplevel = session('aplevel');
            
            $rankupperlevel = [
                  'RSM',
                  'SSM'
            ];
      
            $salesteamrank = [
                  'AE',
                  'RSM',
                  'SSM'
            ];
      
            $users = OPTv2Projectionh::selectRaw('MAX(PERNRNAME) as FULLNAME,PERNR')
                  ->groupBy('PERNR')
                  ->orderBy('FULLNAME','ASC');
      
                  
            if(in_array(trim($rank),$salesteamrank)) {
                  
                        $users->where(function ($users) use ($pernr,$includemypernr) {
                                $users->whereIn('PERNR', userteam());

                                if($includemypernr == '1'){
                                    $users->orWhere('PERNR', $pernr);
                                }
                                    
                        });
      
            }
            
       
      
            return $users;
                  
      }

      function filter_user_list($includemypernr = '1',$myapproveonly = '0') {
      
            $staff = session('staff');
            $user_staff = session('user_staff');
            $pernr = trim(session('pernr'));
            $rsm = session('rsm');
            $ssm = session('ssm');
            $rank = session('rank');
            $aplevel = session('aplevel');
            
            $rankupperlevel = [
                  'RSM',
                  'SSM'
            ];
      
            $salesteamrank = [
                  'AE',
                  'RSM',
                  'SSM'
            ];
      
            $users = OPTv2User::selectRaw('MAX(FULLNAME) as FULLNAME,PERNR')
                  ->where('ACTIVE','1')
                  ->groupBy('PERNR')
                  ->orderBy('FULLNAME','ASC');
      
                  
            if(in_array(trim($rank),$salesteamrank) || $myapproveonly == '1') {
                  
                        $users->where(function ($users) use ($pernr,$includemypernr) {
                                $users->whereIn('PERNR', userteam());

                                if($includemypernr == '1'){
                                    $users->orWhere('PERNR', $pernr);
                                }
                                    
                        });
      
            }
            
       
      
            return $users;
                  
      }

   



      function getMinutesFromTimeString($timeString) {

            preg_match('/(\d+)\s*([a-z]+)/', $timeString, $matches);
            $value = (int)$matches[1];
            $unit = strtolower($matches[2]);
        
            // Convert to minutes
            switch ($unit) {
                case 'min':
                case 'mins':
                    return $value;
                case 'hr':
                case 'hrs':
                    return $value * 60;
                default:
                    return 0;
            }
        }

      }

?>