@extends('layouts.admin_app')

<style>
.projperiod-sticky{
    position: sticky;
    top: 65px;
    z-index: 100;
    background: #fff;
    padding-top: 6px;
    padding-bottom: 6px;
}


</style>
@section('title') Dashboard @endsection

@section('belowcontent')


@section('menutitle') Dashboard @endsection

@php
  $rank = trim(session('rank'));
  $pernr = request('pernr');
  $q = ProjectionPeriodList('0',trim(session('division')))->get();
@endphp



<div class="col-md-8 @if(rankView('IMD','CRM','CRM','AVP','CC')) {{ "d-none" }} @endif">
    <div class="input-group @if($users->count() < 2) {{ "d-none" }} @else {{ "mb-1"}}  @endif ">
      <span class="input-group-text " id="basic-addon1">AE</span>
      <select class="form-control filterGroup"  required name="userFilterDashboard" id="userFilterDashboard" aria-label="Default select example">
        
        @if($users->count() > 1) 
            <option value="1" selected> All</option>
        @endif 

        @foreach($users as $user)
          <option value="{{$user->PERNR}}" @if($pernr == $user->PERNR) {{ 'selected' }} @endif>{{$user->PERNR ." " . $user->FULLNAME}}</option>
        @endforeach

      </select>
      
  </div>

</div>



<div class="projperiod-sticky">
  <div class="row">
      <div class="col-md-7 px-4">
        <div class="input-group mb-1">
          <span class="input-group-text" id="basic-addon1">
            Projection Period
          </span>
          
          <select class="form-select dashboard_projection_period filterGroup" name="dashboard_projection_period" required aria-label="Default select example">
            @foreach ($q as $r)
              <option value="{{ $r->DOCNUM }}"> {{ projection_period_display($r->DOCNUM) }}</option>
            @endforeach
          </select>

          <span class="input-group-text" style="background-color:white !important;" id="basic-addon1"> 
            <span class="dashboard_projperiodstatus"> - </span> 
          </span>
        </div>
      </div>

      <div class="col-md-5 mt-0">
        <div class="d-flex mt-2 px-2 justify-content-between text-700 fw-semi-bold">
          <p class="mb-0"> Approval Progress: </p>
          <div class="w-50 h-75 progress dashboardprojtnpercentage" style="height:15px">
            <div class="">-</div>
          </div>
        </div>
      </div>
  </div>
</div>



@if(rankView('AE','RSM','SSM','AVP'))


<div class="col-12 mt-1 col-xl-12 col-xxl-12">
  <div class="row g-3 mb-0">
    <div class="col-12 col-md-12">
      <div class="col-md-12">
        <div class="row">
        
          
          <div class="col-3 col-md-3 col-xxl-4 text-left">
            <div class="card  bg-info bg-gradient false">
              <div class="card-body p-3 fs--1">
                  <div class="d-flex align-items-center left-content-between">
                    <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                      
                        <span class="fa-solid fa-square fa-stack-2x text-info-300" data-fa-transform="down-4 rotate--10 left-4"></span>
                    
                        <span class="fa-solid fa-circle fa-stack-2x stack-circle text-info-100" data-fa-transform="up-4 right-3 grow-2"></span>
                      
                        <span class="fa-stack-1x fa-solid fab fa-stack-overflow text-info " data-fa-transform="shrink-2 up-8 right-6"></span>
                    </span>
                    <div class="ms-3">
                        <h5 class="mb-0 text-white" id="issue-transact">₱<span class="totalprojection_amount">0</span></h5>
                        <p class="text-800  mb-0 text-white"><span class="totalprojection_count">0</span></p>
                        <p class="text-800  mb-0 text-white">Total Projection</p>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    
          
          <div class="col-3 col-md-3 col-xxl-4 text-left">
            <div class="card  bg-primary-600 bg-gradient false">
              <div class="card-body p-3 fs--1">
                  <div class="d-flex align-items-center left-content-between">
                    <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                    
                        <span class="fa-solid fa-square fa-stack-2x text-primary-300" data-fa-transform="down-4 rotate--10 left-4"></span>
                      
                        <span class="fa-solid fa-circle fa-stack-2x stack-circle text-primary-100" data-fa-transform="up-4 right-3 grow-2"></span>
                      
                        <span class="fa-stack-1x fa-solid fab fa-stack-overflow text-primary " data-fa-transform="shrink-2 up-8 right-6"></span>
                    </span>
                    <div class="ms-3">
                      <h5 class="mb-0 text-white" id="issue-transact">₱<span class="totalprojectionpending_amount">0</span></h5>
                      <p class="text-800  mb-0 text-white"><span class="totalprojectionpending_count">0</span></p>
                        <p class="text-800  mb-0 text-white">Pending</p>
                    </div>
                  </div>
              </div>
            </div>
        </div>
        <div class="col-3 col-md-3 col-xxl-4 text-left">
          <div class="card  bg-warning bg-gradient false">
            <div class="card-body p-3 fs--1">
                <div class="d-flex align-items-center left-content-between">
                  <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                  
                      <span class="fa-solid fa-square fa-stack-2x text-warning-300" data-fa-transform="down-4 rotate--10 left-4"></span>
                    
                      <span class="fa-solid fa-circle fa-stack-2x stack-circle text-warning-100" data-fa-transform="up-4 right-3 grow-2"></span>
                    
                      <span class="fa-stack-1x fa-solid fab fa-stack-overflow text-warning " data-fa-transform="shrink-2 up-8 right-6"></span>
                  </span>
                  <div class="ms-3">
                    <h5 class="mb-0 text-white" id="issue-transact">₱<span class="totalprojectionreturned_amount">0</span></h5>
                    <p class="text-800  mb-0 text-white"><span class="totalprojectionreturned_count">0</span></p>
                      <p class="text-800  mb-0 text-white">Returned</p>
                  </div>
                </div>
            </div>
          </div>
      </div>
          <div class="col-3 col-md-3 col-xxl-4 text-left">
            <div class="card  bg-success bg-gradient false">
                <div class="card-body p-3 fs--1">
                  <div class="d-flex align-items-center left-content-between">
                      <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                      
                        <span class="fa-solid fa-square fa-stack-2x text-success-300" data-fa-transform="down-4 rotate--10 left-4"></span>
                      
                        <span class="fa-solid fa-circle fa-stack-2x stack-circle text-success-100" data-fa-transform="up-4 right-3 grow-2"></span>
                        
                        <span class="fa-stack-1x fa-solid fab fa-stack-overflow text-success " data-fa-transform="shrink-2 up-8 right-6"></span>
                      </span>
                      <div class="ms-3 ">
                        <h5 class="mb-0 text-white" id="issue-transact">₱<span class="totalprojectionapproved_amount">0</span></h5>
                        <p class="text-800  mb-0 text-white"><span class="totalprojectionapproved_count">0</span></p>
                        <p class="text-800  mb-0 text-white">Approved</p>
                      </div>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-3 d-none col-md-3 col-xxl-4 text-left">
              <div class="card  bg-primary bg-gradient false">
                <div class="card-body p-3 fs--1">
                    <div class="d-flex align-items-center left-content-between">
                      <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                        <!-- <span class="fa fa-square fa-stack-2x text-primary-300" data-fa-transform="down-4 rotate--10 left-4"></span> -->
                          <span class="fa-solid fa-square fa-stack-2x text-primary-300" data-fa-transform="down-4 rotate--10 left-4"></span> 
                          <!-- <span class="fa-circle fa-stack-2x text-primary-300 stack-circle" data-fa-transform="up-4 right-3 grow-2"></span> -->
                          <span class="fa-solid fa-circle fa-stack-2x stack-circle text-primary-100" data-fa-transform="up-4 right-3 grow-2"></span> 
                          <!-- <span class="fa-stack-1x fa-solid fab fa-stack-overflow text-primary" data-fa-transform="shrink-2 up-8 right-6"></span> -->
                          <span class="fa-stack-1x fa-solid fab fa-stack-overflow text-primary " data-fa-transform="shrink-2 up-8 right-6"></span> 
                      </span>
                      <div class="ms-3 ">
                        <h4 class="mb-0 text-white" id="issue-transact"><span class="totalallocation_count">0</span></h4>
                        <p class="text-800  mb-0 text-white">₱<span class="totalallocation_amount">0.00</span></p>
                          <p class="text-800  mb-0 text-white">Total Allocation</p>
                      </div>
                    </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-none col-12 mt-1 px-4 col-md-3">
      <h3 class="mt-3 text-1100 text-nowrap">Account Summary </h3>
      <p class="text-700 mb-md-3"> </p>
      <div class="d-flex align-items-center justify-content-between">
        <p class="mb-0 fw-bold">Type </p>
        <p class="mb-0 fs--1">Count </p>
      </div>
      <hr class="bg-200 mb-2 mt-2" />
      <div class="d-flex align-items-center mb-1"><span class="d-inline-block bg-info-300 bullet-item me-2"></span>
        <p class="mb-0 fw-semi-bold text-900 lh-sm flex-1">BSA</p>
        <h5 class="mb-0 text-900 bsa_text_count">-</h5>
      </div>
 
      <div class="d-flex align-items-center mb-1"><span class="d-inline-block bg-success-300 bullet-item me-2"></span>
        <p class="mb-0 fw-semi-bold text-900 lh-sm flex-1">Non-BSA</p>
        <h5 class="mb-0 text-900 nonbsa_text_count">-</h5>
      </div>
   
      

    </div>
    <div class="d-none  col-12 mt-1 px-0 col-md-4">
      <div class="position-relative mb-sm-0 mb-xl-0">
        <div class="echart-typesummmary-chart" style="top:-100px; min-height:220px;width:100%"></div>
      </div>
    </div>

    <div class="d-none  col-12 mt-1 col-md-5">

      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h5 class="mb-0">Allocation</h5>
              <a class="text-primary dashboard_allocationsummary_btn mt-0 fs--1 "><i class="fas fa-search fs--1"></i> Click to see summary</a>
            </div>
          </div>
          <div class="pb-4 pt-3">
            <div class="echart-allocated" style="height: 115px; width: 100%; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;" _echarts_instance_="ec_1764057013800"><div style="position: relative; width: 451px; height: 115px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas data-zr-dom-id="zr_0" width="451" height="115" style="position: absolute; left: 0px; top: 0px; width: 451px; height: 115px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div class="" style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; background-color: rgb(239, 242, 246); border-width: 1px; border-radius: 4px; color: rgb(20, 24, 36); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 7px 10px; top: 0px; left: 0px; transform: translate3d(14px, 79px, 0px); border-color: rgb(203, 208, 221); pointer-events: none; visibility: hidden; opacity: 0;"><strong>Percentage discount:</strong> 0%</div></div>
          </div>
          <div>
            <div>
              <div class="d-flex align-items-center mb-2">
                <div class="bullet-item bg-primary me-2"></div>
                <h6 class="text-900 fw-semi-bold flex-1 mb-0">Allocated</h6>
                <h6 class="text-900 fw-semi-bold mb-0 allocationgraph_allocated_text">%</h6>
              </div>
              <div class="d-flex align-items-center mb-2">
                <div class="bullet-item bg-primary-200 me-2"></div>
                <h6 class="text-900 fw-semi-bold flex-1 mb-0">Not Yet Allocated</h6>
                <h6 class="text-900 fw-semi-bold mb-0 allocationgraph_notyetallocated_text">%</h6>
              </div>
              <div class="d-flex align-items-center">
                <div class="bullet-item bg-info-500 me-2"></div>
                <h6 class="text-900 fw-semi-bold flex-1 mb-0">Unserved</h6>
                <h6 class="text-900 fw-semi-bold mb-0 allocationgraph_unserved_text">%</h6>
              </div>
            </div>
           
          </div>
        </div>
      </div>
    </div>

  

  
  </div>

</div>

<div class="d-none  col-12 mt-1 mb-3 col-xxl-6">
  <div class="row g-3">
    <div class="col-12 col-md-12">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              {{-- {{ date_now('year') }}  --}}
              <h5 class=""> Top 10 Title Projection</h5>
            </div>
            <h4></h4>
          </div>
          <div class="d-flex justify-content-center ">
            <div class="top10isbnprojection_echart" style="min-height:70vh;width:100%"></div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-12 mt-1 mb-3 col-xxl-6">
  <div class="row g-3">
    <div class="col-12 col-md-12">
      <div class="card h-100">
        <div class="card-body p-3">
          <div class="pb-2 border-bottom">
            <div class="row">
              <div class="col-md-5">
                <h4 class=""> Allocation Summary</h4>
               
              </div>
              <div class="col-md-7 text-end">
                <div class="dropdown font-sans-serif d-inline-block">

                  <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-download me-2"></i> Download
                  </button><span class="caret"> </span>
                  <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                      {{-- <div class="border-0 dropdown-divider"></div>    --}}
          
                  
                          <a class="dropdown-item btn-exportexcel-dashboard-allocsummary text-success-600" href="#"><i class="fa fa-file-excel"></i> Excel</a>
                  </div>
                </div>
             
              </div>
            
          
            </div>
          
          </div>
          <table class="fs--1 table table-striped bg-white text-center" id="dashboard-allocationsummary-list">
            <thead class="border border-1" width="100%">
            
              <tr>
                
            
                  <th scope="col" class="text-center" width="14%">ISBN</th>
                  <th scope="col" class="text-center" width="30%">Description</th>
                  <th scope="col" class="text-center" width="8%">Projection</th>
                  <th scope="col" title="Allocation" class="text-center" width="8%">Alloc.</th>
                  <th scope="col" class="text-center" width="8%">Alloc. Rate</th>
                  <th scope="col" class="text-center" width="8%">Alloc. </br> Transfer </br> In</th>
                  <th scope="col" class="text-center" width="8%">Alloc. </br> Transfer </br>  Out</th>
                  <th scope="col" class="text-center" width="8%">Ordered</th>
                  <th scope="col" title="Allocation Balance" class="text-center" width="8%">Alloc. </br> Bal.</th>
                 
              </tr>
            </thead>
        

        </table>

        </div>
      </div>
    </div>
  </div>
</div>
@endif



@if(rankView('IMD','CRM','CC','RSM','SSM','AVP'))

<div class="col-12 mt-1 mb-3 col-xxl-6">
  <div class="row g-3">
    <div class="col-12 col-md-12">
      <div class="card h-100">
        <div class="card-body p-3">
          <div class="pb-2 border-bottom">
            <div class="row">
              <div class="col-md-5">
                <h4 class=""> Approval Status</h4>
               
              </div>
              <div class="col-md-7 text-end">
                <div class="dropdown font-sans-serif d-inline-block">

                  <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-download me-2"></i> Download
                  </button><span class="caret"> </span>
                  <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                      {{-- <div class="border-0 dropdown-divider"></div>    --}}
          
                  
                          <a class="dropdown-item btn-exportexcel-dashboard-allocsummary text-success-600" href="#"><i class="fa fa-file-excel"></i> Excel</a>
                  </div>
                </div>
             
              </div>
            
          
            </div>
          
          </div>
          <table id="reports-projapproval-status-list" class="fs--1 table table-striped  text-center">
            <thead class="border border-1">
               <tr role="row">
            
               
               </tr>
               <tr>
                    {{-- <th scope="col" class="text-center" width="3%">#</th> --}}
                    <th scope="col" class="text-center" width="20%">RSM</th>
                    <th scope="col" class="text-center" width="26%">Name</th>
                    <th scope="col" class="text-center" width="8%">Total <br> Projection</th>
                    <th scope="col" class="text-center" width="8%">Saved</th>
                    <th scope="col" class="text-center" width="8%">Returned</th>
                    <th scope="col" class="text-center" width="8%">Pending <br> (RSM)</th>
                    <th scope="col" class="text-center" width="8%">Pending <br> (SSM)</th>
                    <th scope="col" class="text-center" width="8%">Approved</th>
                    <th scope="col" class="text-center" width="6%">Status</th>
            </tr>
            </thead>
            
      </table>

        </div>
      </div>
    </div>
  </div>
</div>


@endif


@if(rankView('IMD','CRM','CRM','AVP','AVP','CC'))

<div class="col-12 mt-1 mb-3 col-xxl-6">
  <div class="row g-3">
    <div class="col-12 col-md-12">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex mb-2 justify-content-between border-bottom">
            <div class=" ">
              {{-- {{ date_now('year') }}  --}}
              <h4 class=""> Projection Summary</h4>
            </div>
          
          </div>
          <table class="fs--1 table table-striped bg-white text-center" id="dashboard-projectionsummary-list">
            <thead class="border border-1" width="100%">
            
              <tr>
                
            
                  <th scope="col" class="text-center" width="15%">ISBN</th>
                  <th scope="col" class="text-center" width="40%">Description</th>
                  <th scope="col" class="text-center" width="11%">Edition</th>
                  <th scope="col" class="text-center" width="12%">Projection</th>
                  <th scope="col" title="Allocation" class="text-center" width="12%">Alloc.</th>
                  <th scope="col" class="text-center" width="10%">Alloc. Rate</th>
          
                 
              </tr>
            </thead>
        

        </table>

        </div>
      </div>
    </div>
  </div>
</div>


@endif

<div class="offcanvas offcanvas-start content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" id="DashboardTitleCustomerProjtnOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
  <div class="offcanvas-header pb-0">
        <div id="offcanvasLeftLabel">
          <span class="isbntitlename h5"> - </span>
          <br>
              <span class="text-secondary">Customer Projection</span>  
          </div>
    
     <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
    
  </div>
  <hr>
  <div class="offcanvas-body pt-0">
   
      <div class="row">
          
          <div class="col-md-12 p-0">
            <div class="card p-2">

                      <table class="fs--1 table table-striped bg-white table-bordered text-center" id="dashboard-offcanvas-titlecustomerprojection-list">
                          <thead class="border border-1" width="100%">
                          
                            <tr>
                                <th scope="col" class="text-center" width="5%">#</th>
                                <th scope="col" class="text-center" width="45%">Name</th>
                                <th scope="col" title="Projection" class="text-center" width="15%">Projtn.</th>
                            </tr>
                          </thead>
                      
      
                      </table>

            </div>
         </div>

      </div>
      
  </div>
</div>

<div class="offcanvas offcanvas-start content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" id="DashboardBSOAllocationOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
  <div class="offcanvas-header pb-0">
     <h5 id="offcanvasLeftLabel">BSO Allocation</h5>
     <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
    
  </div>
  <hr>
  <div class="offcanvas-body pt-0">
   
      <div class="row">
         
           <div class="col-md-12 p-0">
              <div class="card p-2">

                      <table class="fs--1 table table-striped bg-white table-bordered text-center" id="dashboard-offcanvas-bsoallocation-list">
                          <thead class="border border-1" width="100%">
                          
                            <tr>
                                <th scope="col" class="text-center" width="5%">#</th>
                                <th scope="col" class="text-center" width="45%">Name</th>
                                <th scope="col" title="Projection" class="text-center" width="15%">Projtn.</th>
                                <th scope="col" title="Allocation" class="text-center" width="15%">Alloc.</th>
                                <th scope="col" class="text-center" width="15%">Allocation Rate</th>
                            </tr>
                          </thead>
                      
      
                      </table>

            </div>
         </div>

      </div>
      
  </div>
</div>



<div class="offcanvas offcanvas-start content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" id="DashboardAllocationSummaryOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
  <div class="offcanvas-header pb-0">
     <h5 id="offcanvasLeftLabel">--</h5>
     <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
    
  </div>
  <hr>
  <div class="offcanvas-body pt-0">
   
      <div class="row aeprojectiontitle_card_body">
         
           <div class="card col-md-12">
              <div class=" p-2">

                      <table class="fs--1 table table-striped bg-white table-bordered text-center" id="dashboard-offcanvas-allocationsummary-list">
                          <thead class="border border-1" width="100%">
                          
                            <tr>
                                <th scope="col" class="text-center" width="25%">ISBN</th>
                                <th scope="col" class="text-center" width="45%">Description</th>
                                <th scope="col" class="text-center" width="15%">Projection</th>
                                <th scope="col" class="text-center" width="15%">Allocation</th>
                            </tr>
                          </thead>
                      
      
                      </table>

            </div>
         </div>

      </div>
      
  </div>
</div>





   
@endsection

@section('scriptJS')


<script src="{{ asset('assets/js/echarts-example.js') }}"></script>
<script src="{{ asset('vendors/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('vendors/dayjs/dayjs.min.js') }}"></script>

<script>
    
//Config the JS for E-Chart--------------------------
function echartSetOption(chart, userOptions, getDefaultOptions, responsiveOptions) {
      const { merge } = window._;
      const { breakpoints, resize } = window.phoenix.utils;

      const handleResize = options => {
          Object.keys(options).forEach(item => {
              if (window.innerWidth > breakpoints[item]) {
                  chart.setOption(options[item]);
              }
          });
      };

      const themeController = document.body;

      chart.setOption(merge(getDefaultOptions(), userOptions));

      const navbarVerticalToggle = $('.navbar-vertical-toggle');
      if (navbarVerticalToggle.length > 0) {
          navbarVerticalToggle.on('navbar.vertical.toggle', () => {
              chart.resize();
              if (responsiveOptions) {
                  handleResize(responsiveOptions);
              }
          });
      }

      resize(() => {
          chart.resize();
          if (responsiveOptions) {
              handleResize(responsiveOptions);
          }
      });

      if (responsiveOptions) {
          handleResize(responsiveOptions);
      }

      themeController.addEventListener('clickControl', ({ detail: { control } }) => {
          if (control === 'phoenixTheme') {
              chart.setOption(window._.merge(getDefaultOptions(), userOptions));
          }
      });
  }


    function tooltipFormatter (params, dateFormatter = 'MMM DD') {
        let tooltipItem = ``;
        params.forEach(el => {
          tooltipItem += `<div class='ms-1'>
            <h6 class="text-700"><span class="fas fa-circle me-1 " style="color:${
              el.borderColor ? el.borderColor : el.color
            }"></span>
              ${el.seriesName} : ${
          typeof el.value === 'object' ? el.value[1] : el.value
        }
            </h6>
          </div>`;
        });
        return `<div>
                <p class='mb-2 text-600'>
                  ${
                    window.dayjs(params[0].axisValue).isValid()
                      ? window.dayjs(params[0].axisValue).format(dateFormatter)
                      : params[0].axisValue
                  }
                </p>
                ${tooltipItem}
              </div>`;
    }
    

    function TopISBNEchart(data) {
        const $chartEl = $(data.selector);

        if ($chartEl.length > 0) {
            const chart = window.echarts.init($chartEl[0]);

            const defaultOptions = {
              tooltip: {
                trigger: 'axis', // You can also use 'item' depending on your preference
                axisPointer: {
                    type: 'shadow' // 'line' or 'shadow' based on your need
                }
            },
                dataset: {
                    source: data.valueData
                },
                grid: { containLabel: true },
                xAxis: { name: 'count' },
                yAxis: { type: 'category' },
                series: [
                    {
                        type: 'bar',
                        encode: {
                            x: 'amount',
                            y: 'product'
                        }
                    }
                ],
                
            };

            const options = $.extend(true, {}, defaultOptions, data.userOptions || {});
            chart.setOption(options);
        }
    }
var pernrOneChoice = oneChoices('#userFilterDashboard')

function projApprovalStatus(basedocnum,pernr) {

            var projApprovalstatusListable = $("#reports-projapproval-status-list");
            var projApprovalstatusListableURL =  "/datatable_reports_projapprovalstatus?basedocnum="+basedocnum+"&pernr="+pernr;
            var projApprovalstatusListableColumns = [
                   


                     { "data": "pernrrsmname" },
                     { "data": "pernrname" },
                     { "data": "totalprojtnvalue" },
                     { "data": "totalsavedvalue" },
                     { "data": "totalreturnedvalue" },
                     { "data": "totalpendingrsmvalue" },
                     { "data": "totalpendingssmvalue" },
                     { "data": "totalapprovedvalue" },
                     { "data": "statusbadge" },
            ];

       

            dTableRowGroup(projApprovalstatusListable,0, projApprovalstatusListableURL, projApprovalstatusListableColumns, 250,"",true,'',false,0,0);


}
function dashboard_graphs_data(pernrurl,basedocnum) {

  var pernr = pernrurl;

  $.ajax({
              url: '/dashboard_graphs_data?pernr='+pernr+'&basedocnum='+basedocnum,
              method: 'GET',
              dataType: 'json',
              beforeSend: function() {

                showLoading();
               
              },
              success: function(data) {

                hideLoading();

                  var maindashboardcount = data.maindashboardcount;
                  var typesummarygraph = data.typesummarygraph;
                  var top10isbn = data.top10isbn;

                  $('.totalprojection_count').text(maindashboardcount.totalprojection)
                  $('.totalprojection_amount').text(maindashboardcount.totalprojection_amount)

                  $('.totalprojectionpending_count').text(maindashboardcount.totalprojectionpending)
                  $('.totalprojectionpending_amount').text(maindashboardcount.totalprojectionpending_amount)

                  $('.totalprojectionreturned_count').text(maindashboardcount.totalreturned)
                  $('.totalprojectionreturned_amount').text(maindashboardcount.totalreturned_amount)

                  $('.totalprojectionapproved_count').text(maindashboardcount.totalprojectionapproved)
                  $('.totalprojectionapproved_amount').text(maindashboardcount.totalprojectionapproved_amount)

                  $('.totalprojectionallocation_count').text(maindashboardcount.totalallocation)
                  $('.totalprojectionallocation_amount').text(maindashboardcount.totalallocation_amount)
                  $('.dashboardprojtnpercentage').html(maindashboardcount.projtnpercentcomplete)

                  if(maindashboardcount.projperiodstatus === '1'){
                    
                    var projperiodstatusDisplay = `<span class="text-success blink-text"> Open </span>`;
      

                  }
                  else {

                    var projperiodstatusDisplay = `<span class="text-600"> Closed </span>`;

                  }
                  
                  $('.dashboard_projperiodstatus').html(projperiodstatusDisplay)

//   //Type Summary Graph------------------
      
//                  var bsacount =  typesummarygraph.bsasummary_count;
//                  var nonbsacount =  typesummarygraph.nonbsasummary_count;

//                   const getColor = window.phoenix.utils.getColor;
//                   const getData = window.phoenix.utils.getData;

//                   const accountSummaryChart = $('.echart-typesummmary-chart');

//                   if (accountSummaryChart.length) {
//                       const userOptions = getData(accountSummaryChart[0], 'echarts');
//                       const chart = window.echarts.init(accountSummaryChart[0]);

//                       const getDefaultOptions = () => ({
//                           color: [
//                               getColor('info-300'),
//                               getColor('success-300'),
//                               getColor('primary-300'),
//                               getColor('success-300'),
//                               getColor('secondary'),
//                               getColor('primary')
//                           ],
//                           responsive: true,
//                           maintainAspectRatio: false,

//                           series: [
//                               {
//                                   name: 'Tasks assigned to me',
//                                   type: 'pie',
//                                   radius: ['48%', '90%'],
//                                   startAngle: 30,
//                                   avoidLabelOverlap: false,

//                                   label: {
//                                       show: false,
//                                       position: 'center',
//                                       formatter: '{x|{d}%} \n {y|{b}}',
//                                       rich: {
//                                           x: {
//                                               fontSize: 31.25,
//                                               fontWeight: 800,
//                                               color: getColor('gray-700'),
//                                               padding: [0, 0, 5, 15]
//                                           },
//                                           y: {
//                                               fontSize: 12.8,
//                                               color: getColor('gray-700'),
//                                               fontWeight: 600
//                                           }
//                                       }
//                                   },
//                                   emphasis: {
//                                       label: {
//                                           show: true
//                                       }
//                                   },
//                                   labelLine: {
//                                       show: false
//                                   },
//                                   data: [

//                                       { value: bsacount, name: 'BSA' },
//                                       { value: nonbsacount, name: 'Non-BSA' },
//                                   ]
//                               }
//                           ],
//                           grid: {
//                               bottom: 0,
//                               top: 0,
//                               left: 0,
//                               right: 0,
//                               containLabel: false
//                           }
//                       });

//                       $('.bsa_text_count').text(number_format(bsacount));
//                       $('.nonbsa_text_count').text(number_format(nonbsacount));


//                       echartSetOption(chart, userOptions, getDefaultOptions);
//                   }    
// //------------------Type Summary Graph

// //Allocated Graph----------------------



//        const eChartAllocated = $('.echart-allocated');

//         const userOptions = getData(eChartAllocated[0], 'options');
//         const chart = echarts.init(eChartAllocated[0]);

//         const getDefaultOptions = () => ({
//           color: [
//             getColor('primary'),
//             getColor('primary-200'),
//             getColor('info-500')
//           ],

//           tooltip: {
//             trigger: 'item',
//             padding: [7, 10],
//             backgroundColor: getColor('gray-100'),
//             borderColor: getColor('gray-300'),
//             textStyle: { color: getColor('dark') },
//             borderWidth: 1,
//             transitionDuration: 0,
//             position(pos, params, el, elRect, size) {
//               const obj = { top: pos[1] - 35 }; // set tooltip position over 35px from pointer
//               if (window.innerWidth > 540) {
//                 if (pos[0] <= size.viewSize[0] / 2) {
//                   obj.left = pos[0] + 20; // 'move in right';
//                 } else {
//                   obj.left = pos[0] - size.contentSize[0] - 20;
//                 }
//               } else {
//                 obj[pos[0] < size.viewSize[0] / 2 ? 'left' : 'right'] = 0;
//               }
//               return obj;
//             },
//             formatter: params => {
//               return `<strong>${params.data.name}:</strong> ${params.percent}%`;
//             }
//           },
//           legend: { show: false },
//           series: [
//             {
//               name: maindashboardcount.allocatedPercent + '%',
//               type: 'pie',
//               radius: ['100%', '87%'],
//               avoidLabelOverlap: false,
//               emphasis: {
//                 scale: false,
//                 itemStyle: {
//                   color: 'inherit'
//                 }
//               },
//               itemStyle: {
//                 borderWidth: 2,
//                 borderColor: getColor('gray-soft')
//               },
//               label: {
//                 show: true,
//                 position: 'center',
//                 formatter: '{a}',
//                 fontSize: 23,
//                 color: getColor('dark')
//               },
//               data: [
//                 { value: maindashboardcount.allocatedPercent, name: 'Allocated Qty' },
//                 { value: maindashboardcount.notYetAllocatedPercent, name: 'Not Yet Allocated Qty' },
//                 { value: maindashboardcount.unservedPercent, name: 'Unserved Qty' }
//               ]
//             }
//           ],
//           grid: { containLabel: true }
//         });

//         echartSetOption(chart, userOptions, getDefaultOptions);

//         $('.allocationgraph_allocated_text').text( maindashboardcount.allocatedPercent + '%')
//         $('.allocationgraph_notyetallocated_text').text( maindashboardcount.notYetAllocatedPercent + '%')
//         $('.allocationgraph_unserved_text').text( maindashboardcount.unservedPercent + '%')

// //-------------------Allocated Graph

// const Top10TitleProjectionEchartData = [['amount', 'product']];;

// for (var o = 0; o < top10isbn.length; o++) {
//     var dat = top10isbn[o];
//     Top10TitleProjectionEchartData.push(dat);
// }

// //Summary of Activities Echart--------------------- 
// const dtopISBNEchart = {
//                       selector: '.top10isbnprojection_echart',
//                       valueData: Top10TitleProjectionEchartData
//                   };

// TopISBNEchart(dtopISBNEchart);
// //--------------------- Summary of Activities Echart


 

              var dashboardAllocationSummaryListable = $("#dashboard-allocationsummary-list");
              var dashboardAllocationSummaryListableURL =  "/datatable_dashboard_allocationsummary_list?basedocnum="+basedocnum+"&pernr="+pernrurl;
              var dashboardAllocationSummaryListableColumns = [


                      { "data": "isbn" },
                      { "data": "description" },
                      { "data": "totalprojtn" },
                      { "data": "totalallocated" },
                      { "data": "allocrate" },
                      { "data": "alloctransferin" },
                      { "data": "alloctransferout" },
                      { "data": "ordered" },
                      { "data": "allocbal" },
              ];

              dTable(dashboardAllocationSummaryListable, dashboardAllocationSummaryListableURL, dashboardAllocationSummaryListableColumns, 250,"There is currently no allocation.",true,'',false,0,0,1);

                  
              
              var dashboardProjectionSummaryListable = $("#dashboard-projectionsummary-list");
              var dashboardProjectionSummaryListableURL =  "/datatable_dashboard_projectionsummary_list?basedocnum="+basedocnum+"&pernr="+pernrurl;
              var dashboardProjectionSummaryListableColumns = [


                      { "data": "isbn" },
                      { "data": "description" },
                      { "data": "edition" },
                      { "data": "totalprojtn" },
                      { "data": "totalallocated" },
                      { "data": "allocrate" },
              ];

              dTable(dashboardProjectionSummaryListable, dashboardProjectionSummaryListableURL, dashboardProjectionSummaryListableColumns, 250,"There is currently no allocation.",true,'',false,0,0,1);

                  

              }      

          })
}
$(document).ready(function () {

    var pernr = $('#userFilterDashboard').val();
    var basedocnum = $('.dashboard_projection_period').val();

  projApprovalStatus(basedocnum,pernr)
  if (basedocnum) dashboard_graphs_data(pernr,basedocnum)


  $(document).on('click','.btn-exportexcel-dashboard-allocsummary',function (e) {

    var projectionperiodtext = $('.dashboard_projection_period option:selected').text()

    ExportExcel('dashboard-allocationsummary-list', 'Dashboard - Allocation Summary - '+ projectionperiodtext )

  });

  $(document).on('change','.filterGroup',function (e) {

    var pernr = $('#userFilterDashboard').val();
    var basedocnum = $('.dashboard_projection_period').val();

    
    if (basedocnum) dashboard_graphs_data(pernr,basedocnum);
    projApprovalStatus(basedocnum,pernr)

  });


  $(document).on('click','.dashboardisbnprojtncustomerlist',function (e) {

    e.preventDefault();
    $('#DashboardTitleCustomerProjtnOffCanvas').offcanvas('show')

   
    var basedocnum = $(this).data('basedocnum');
    var pernr = $('#userFilterDashboard').val();
    var isbn = $(this).data('isbn');
    var title = $(this).data('title');

    $('.isbntitlename').html(isbn + ' &nbsp ' + title)

    var dashboardTitleCustomerProjectionListable = $("#dashboard-offcanvas-titlecustomerprojection-list");
    var dashboardTitleCustomerProjectionListableURL =  "/datatable_dashboard_titlecustomerprojection_list?basedocnum="+basedocnum+"&pernr="+pernr+"&isbn="+isbn;
    var dashboardTitleCustomerProjectionListableColumns = [
            { "data": "num" },
            { "data": "customer" },
            { "data": "projection" },
    ];

    dTable(dashboardTitleCustomerProjectionListable, dashboardTitleCustomerProjectionListableURL, dashboardTitleCustomerProjectionListableColumns, 250,"",true,'',true,0,0);
    
  })

  $(document).on('click','.dashboardbsoallocationbtn',function (e) {

    e.preventDefault();
    $('#DashboardBSOAllocationOffCanvas').offcanvas('show')

    
    var basedocnum = $(this).data('basedocnum');
    var pernr = $('#userFilterDashboard').val();
    var isbn = $(this).data('isbn');

    var dashboardBSOAllocationListable = $("#dashboard-offcanvas-bsoallocation-list");
    var dashboardBSOAllocationListableURL =  "/datatable_dashboard_bsoallocation_list?basedocnum="+basedocnum+"&pernr="+pernr+"&isbn="+isbn;
    var dashboardBSOAllocationListableColumns = [
            { "data": "num" },
            { "data": "pernrname" },
            { "data": "projection" },
            { "data": "allocation" },
            { "data": "allocrate" },
    ];

    dTable(dashboardBSOAllocationListable, dashboardBSOAllocationListableURL, dashboardBSOAllocationListableColumns, 250,"",true,'',true,0,0);


  });
  $(document).on('click','.dashboard_allocationsummary_btn',function (e) {

    // var basedocnum = $('.dashboard_projection_period').val();
    // var pernr = $('#userFilterDashboard').val();

    // $('#DashboardAllocationSummaryOffCanvas').offcanvas('show')

    // var dashboardAllocationSummaryListable = $("#dashboard-allocationsummary-list");
    // var dashboardAllocationSummaryListableURL =  "/datatable_dashboard_allocationsummary_list?basedocnum="+basedocnum+"&pernr="+pernr;
    // var dashboardAllocationSummaryListableColumns = [
    //         { "data": "isbn" },
    //         { "data": "description" },
    //         { "data": "totalprojtn" },
    //         { "data": "totalallocated" },
    // ];

    // dTable(dashboardAllocationSummaryListable, dashboardAllocationSummaryListableURL, dashboardAllocationSummaryListableColumns, 250,"",true,'',true,0,0);


  });

//END READY
});

</script>

@endsection