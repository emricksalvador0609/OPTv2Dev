@if(!rankView('IMD','CRM','PEAM'))
<script>
    window.location.href = "{{ route('notfound') }}";
</script>
@endif

@extends('layouts.admin_app')

@section('title') Finalize Requirements @endsection

@section('belowcontent')

  

  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder">Finalize Requirements Adjustment</h4>
          {{-- <a class="fw-bold h5 add-new-op text-primary " href="#!" data-bs-toggle="modal" data-bs-target="#AddNewProjectionPeriod">+ Add New</a>      --}}
       </div>
    </div>
    {{-- <div class="col-md-2 order-1 d-flex align-items-center d-none">
       <div class=" text-center"> 
          <span class="h4  text-700"> 24<br>  
          <span class="h5 text-warning fw-bold"> 
          <i>Total Quantity </i> </span>  
          </span>
       </div>
    </div>
    <div class="col-md-2 order-1 d-flex align-items-center d-none">
       <div class=" text-center"> 
          <span class="h4  text-700"> 3<br>  
          <span class="h5 text-warning fw-bold"> 
          <i>Book Titles </i> </span>  
          </span>
       </div>
    </div>
    <div class="col-md-2 order-1 d-flex align-items-center d-none">
       <div class=" text-center"> 
          <span class="h4  text-700"> 3<br>  
          <span class="h5 text-warning fw-bold"> 
          <i>Customer </i> </span>  
          </span>
       </div>
    </div> --}}
 </div>


  @endsection

  
  @php 
   
   $q = ProjectionPeriodList('0')->get();

  @endphp 

  <div class="mini-db pb-2">
  <div class="row">
      <div class="col-md-8">
          <div class="input-group">
              <span class="input-group-text" id="basic-addon1">
                  Projection Period
              </span>
              
              <select class="form-select selected_projection_id" aria-label="Default select example">
                  <option option="" selected disabled >Select in the list</option>
                 
                  @foreach ($q as $r)
                        <option value="{{ $r->DOCNUM }}"> {{ projection_period_display($r->DOCNUM) }}</option>
        
                @endforeach
              </select>
              <input type="text" class="d-none selected_projection_id_text" hidden>
  
          </div>
      
     
      </div>
      <div class="col-md-4 text-end">
          <div class="input-group d-flex justify-content-end ">
              <span class="input-group-text" id="basic-addon1">
                  Status
              </span>
              <span class="input-group-text projectionid_status_text text-danger" style="background-color:white !important;" id="basic-addon1">
                  -
              </span>
              <span class="input-group-text d-none w-25 text-danger" style="background-color:transparent !important;" id="basic-addon1">
                <div class="mb-0 form-switch h4 d-flex justify-content-center">
    
                    <input class="form-check-input projectionid-status-sw" title="Change Status" id="flexSwitchCheckChecked" value="" type="checkbox" >
    
                </div>
            </span>
          </div>
      
      </div>
  </div>


      
    <div class="row">
        
        <div class="col-md-6 mt-1 col-lg-6 h-100 ">
                    <div class="card-body cardaccordionapprvprogress p-0">

                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item border-0  p-0 ">
                            <h2 class="accordion-header" id="headingOne">

                                <button class="accordion-button px-2 text-secondary btn_approvalprogres_finalreq_showbtn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApprovalProgress" >
                                    <span class="col-4">Approval Progress</span>  
                                <div class="col-8  mx-1 h-75 progress finalreqprojtnpercentage" style="height:15px">
                                    <div class="">-</div>
                                </div>

                                </button>
                            </h2>
                            <div class="accordion-collapse collapse " id="collapseApprovalProgress" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body pt-0">
                                    <div class="table-responsive ms-n1 ps-1 scrollbar">

                                        <div class="card">
                                            <div class="card-body p-2">
                                                <table id="finalreq-projapproval-status-list" class="fs--1 table table-striped  text-center">
                                                    <thead class="border border-1">
                                                        <tr role="row">
                                                    
                                                        
                                                        </tr>
                                                        <tr>
                                                            {{-- <th scope="col" class="text-center" width="3%">#</th> --}}
                                                            <th scope="col" class="text-center" width="25%">RSM</th>
                                                            <th scope="col" class="text-center" width="37%">Name</th>
                                                            <th scope="col" class="text-center" title="Total Projection" width="8%">Projtn.</th>
                                                            <th scope="col" class="text-center" width="8%">Approved</th>
                        
                                                            <th scope="col" class="text-center" title="Percentage (%)" width="12%">Completed</th>
                                                    </tr>
                                                    </thead>
                                                    
                                                </table>
                                            </div>
                                        </div>
                        
                        
                                    
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-6 mt-1 col-lg-6 h-100 ">
                <div class="card-body cardaccordionapprvprogress p-0">

                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item border-0  p-0 ">
                        <h2 class="accordion-header bg-white rounded border" id="headingOne">

                            <button class="accordion-button px-2 text-secondary btn_temp_showbtn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTempCustomerTitles" >
                                <span class="col-8">TEMP Customer/Titles    <span class="text-warning">(<span class="temp_count_text">-</span>)</span></span>     
                    

                            </button>
                        </h2>
                        <div class="accordion-collapse collapse " id="collapseTempCustomerTitles" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body pt-0">
                                <div class="table-responsive ms-n1 ps-1 scrollbar">

                                    <div class="card">
                                        <div class="card-body p-2">
                                            <table id="finalreq-tempcustomertitle-list" class="fs--1 table table-striped  text-center" width="100%">
                                                <thead class="border border-1">
                                                    <tr role="row">
                                                
                                                    
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center" width="3%">#</th>
                                                        <th scope="col" class="text-center" width="20%">Type</th>
                                                        <th scope="col" class="text-center" width="30%">Temp</th>
                                                        <th scope="col" class="text-center" width="47%">Name</th>
                                                </tr>
                                                </thead>
                                                
                                            </table>
                                        </div>
                                    </div>
                    
                    
                                
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>


<div class="finalizereq_card ">

    <div class="input-group d-none mb-2 ">
        <span class="input-group-text" id="basic-addon1">
            Change Status
        </span>
        <span class="input-group-text text-danger" style="background-color:white !important;" id="basic-addon1">
            <div class="mb-0 form-switch h4 d-flex justify-content-center">

                <input class="form-check-input projectionid-status-sw" id="flexSwitchCheckChecked" value="" type="checkbox" >

            </div>
        </span>
    </div>

   

        <div class="card h-100 mb-3">
            <div class="card-body p-0">

                <div class="accordion accordion_finalreq un-cl" id="accordionExample">
                    <div class="accordion-item  p-0  border-300">
                    <h2 class="accordion-header" id="headingOne">

                        <button class="accordion-button px-2  btn_projsummary_finalreq_showbtn text-info-500 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Projection Summary 

                        </button>
                    </h2>
                    <div class="accordion-collapse collapse " id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body pt-0">
                        <div class="p-0">
                            
                            <div style="height:50vh; overflow-y:auto;">
                                <div class="card_projsummary">
                                    <table id="insertedisbn-finalreq-list-table" class="fs--1 p-2 table-responsive table-striped table text-center">
                                        <thead class="border border-1 sticky-top bg-white" style="z-index: 10;">
                                            <tr>
                                                <th class="bg-white" colspan="99">
                                                    <ul class="nav nav-underline" id="myTab" role="tablist">
                                                        <li class="nav-item allproposalfilter" role="presentation"><a class="nav-link active" id="home-tab" data-bs-toggle="tab"  role="tab" aria-controls="tab-home" aria-selected="true">All</a></li>
                                                        <li class="nav-item withoutproposalfilter" role="presentation"><a class="nav-link" id="profile-tab" data-bs-toggle="tab"  role="tab" aria-controls="tab-profile" aria-selected="false" tabindex="-1">Without Proposal</a></li>
                                                        <li class="nav-item withproposalfilter" role="presentation"><a class="nav-link" id="contact-tab" data-bs-toggle="tab" role="tab" aria-controls="tab-contact" aria-selected="false" tabindex="-1">With Proposal</a></li>
                                                      </ul>
                                                    
                                                </th>

                                            </tr>
                                            <tr>
                                                <th scope="col" width="5%">#</th>
                                                <th scope="col" width="12%">ISBN</th>
                                                <th scope="col" width="20%">Title</th>
                                                <th scope="col" width="6%">Total<br>Projtn</th>
                                                <th scope="col" width="5%">SOH</th>
                                                <th scope="col" width="5%">Pull Out<br> In-Transit</th>
                                                <th scope="col" width="5%">OMS</th>
                                                <th scope="col" width="5%">On<br>PO</th>
                                                <th scope="col" width="7%">Buff.<br>Stock</th>
                                                <th scope="col" width="5%">Adj.<br>Stock</th>
                                                <th scope="col" width="5%">Req.</th>
                                                <th scope="col" width="8%" title="Proposed Requirement">Propose<br>Req.</th>
                                                <th scope="col" width="5%">{{ getPreviousYear(1) }}</th>
                                                <th scope="col" width="5%">{{ getPreviousYear(2) }}</th>
                                                <th scope="col" width="5%">{{ getPreviousYear(3) }}</th>
                                                <th scope="col" width="5%" class="text-start px-2">
                                                    <div class="d-flex justify-content-center">
                                                        <input class="form-check-input for_approval_checkallfinalreq" type="checkbox">
                                                      </div>
                                                  </th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        
                                        </table>

                                  <table id="projsummary-finalreq-list-table" class="fs--1 p-2 d-none table-responsive table-striped table text-center">
                                    <thead class="border border-1 sticky-top bg-white" style="z-index: 10;">
                                      <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="8%">ISBN</th>
                                        <th scope="col" width="25%">Title</th>
                                        <th scope="col" width="5%">Total<br>Projtn</th>
                                        <th scope="col" width="5%">SOH</th>
                                        <th scope="col" width="5%">Pull Out<br>In-Transit</th>
                                        <th scope="col" width="5%">OMS</th>
                                        <th scope="col" width="5%">On<br>PO</th>
                                        <th scope="col" width="5%">Buff.<br>Stock</th>
                                        <th scope="col" width="5%">Adj.<br>Stock</th>
                                        <th scope="col" width="5%">Req.</th>
                                        <th scope="col" width="10%" title="Proposed Requirement">Propose<br>Req.</th>
                                        <th scope="col" width="5%">{{ getPreviousYear(1) }}</th>
                                        <th scope="col" width="5%">{{ getPreviousYear(2) }}</th>
                                        <th scope="col" width="5%">{{ getPreviousYear(3) }}</th>
                                        <th scope="col" width="5%" class="text-start px-2">
                                          <div class="d-flex justify-content-center">
                                            <input class="form-check-input projsummary_checkallfinalreq" type="checkbox">
                                          </div>
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                  </table>
                                </div>
                              </div>
                            <div class="row px-3">
                    
                                
                                <div class="col-md-6 col-lg-6 border-top p-3 pt-2">
                                    <div class="dropdown font-sans-serif d-inline-block">

                                        <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-download me-2"></i> Download
                                        </button><span class="caret"> </span>
                                        <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                                            <div class="border-0 dropdown-divider"></div>   
                                            <span class="p-1 text-danger-600"> PDF</span>
                                                <a class="dropdown-item btn-printpdf-zeroproposal-finalreq text-danger-600" href="#"><i class="fa fa-file-pdf"></i> <span class="text-700"> Without Proposal </span> </a>
                                                <a class="dropdown-item btn-printpdf-withproposal-finalreq text-danger-600" href="#"><i class="fa fa-file-pdf"></i>  <span class="text-700"> With Proposal </span> </a>
                                        <div class="dropdown-divider"></div> 
                                        
                                                <a class="dropdown-item btn-exportexcel-forapproval-finalreq text-success-600" href="#"><i class="fa fa-file-excel"></i> Excel</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-end col-md-6 col-lg-6 border-top p-3 pt-2">
                                    <div>
                                        {{-- <button class="btn btn-sm btn-phoenix-secondary btn-exportexcel-forapproval-finalreq">
                                            <i class="fa fa-download me-2"></i> Download    
                                        </button> --}}
                                        
                                    
                                        {{-- <button type="button" class="btn btn-warning btn-sm btn-projsummary-forapproval">For Approval</button> --}}
                                        {{-- <button type="button" class="btn btn-success btn-sm btn-projsummary-approve">Approve</button> --}}
                                        <button type="button" class="btn btn-success btn-sm btn-forapproval-approve-finalreq">Approve</button>
                                    </div>
                                </div>
                            </div>
                  
                        
                        </div>
                    </div>
                    </div>
                    <div class="accordion-item border-top p-0 d-none">
                        <h2 class="accordion-header" id="headingTwo">
    
                            <button class="accordion-button btn_forapproval_finalreq_showbtn px-2 text-warning-500 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            For Approval (<span class="finalreq_forapproval_cnt">-</span>)
    
                            </button>
                        </h2>
                        <div class="accordion-collapse collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body pt-0">
                            <div class="" style="height:45vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">
                              
                                    <div class="">
                                       
                                    </div>
                            </div>
                            <div class="">
                        
                                    
                                <div class="text-end border-top p-1 pt-2">
                                    <div>
                                        <div class="dropdown font-sans-serif d-inline-block">

                                            <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-download me-2"></i> Download
                                            </button><span class="caret"> </span>
                                            <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item btn-printpdf-forapproval-finalreq text-danger-600" href="#"><i class="fa fa-file-pdf"></i> PDF</a>
                                                <a class="dropdown-item btn-exportexcel-forapproval-finalreq text-success-600" href="#"><i class="fa fa-file-excel"></i> Excel</a>
                                            </div>
                                        </div>
                            
                                        <button type="button" class="btn btn-success btn-sm btn-forapproval-approve-finalreq">Approve</button>
                                    </div>
                                </div>
                            </div>
                            
                            </div>
                        </div>
                        </div>
                    <div class="accordion-item border-top p-0">
                    <h2 class="accordion-header" id="headingThree">

                        <button class="accordion-button btn_approved_finalreq_showbtn px-2 text-success-500 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Approved (<span class="finalreq_approved_cnt">-</span>)

                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" id="collapseThree" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body pt-0">
                        <div class="" style="height:45vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">
                          
                                <div class="">
                                    <table id="approved-finalreq-list-table" class="fs--1 p-2 table-responsive table-striped table text-center">
                                    <thead class="border border-1 sticky-top bg-white" style="z-index: 10;">
                                        <tr>
                                            <th scope="col" class="" width="5%">#</th>
                                            <th scope="col" class="" width="10%">ISBN</th>
                                            <th scope="col" width="25%">Title</th>
                                            <th scope="col" width="6%">Total<br>Projtn</th>
                                            <th scope="col" width="6%">SOH</th>
                                            <th scope="col" width="6%">Pull Out<br>In-Transit</th>
                                            <th scope="col" width="6%">OMS</th>
                                            <th scope="col" width="6%">On<br>PO</th>
                                            <th scope="col" width="6%">Buff.<br>Stock</th>
                                            <th scope="col" width="6%">Adj.<br>Stock</th>
                                            <th scope="col" width="6%">Req.</th>
                                            <th scope="col" width="7%" title="Proposed Requirement">Prop.<br>Req.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    
                                    </table>
                                </div>
                        </div>
                        <div class="">
                    
                                
                            <div class="text-start px-3 border-top p-1 pt-2">
                                <div>
                                <div class="dropdown font-sans-serif d-inline-block">

                                    <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-download me-2"></i> Download
                                    </button>
                                        <span class="caret"> </span>
                                    <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item text-danger-600" href="#"><i class="fa fa-file-pdf"></i> PDF</a>
                                        <a class="dropdown-item btn-exportexcel-approved-finalreq text-success-600" href="#"><i class="fa fa-file-excel"></i> Excel</a>
                                    </div>
                                </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    </div>
                    <div class="accordion-item d-none border-top p-0">
                    <h2 class="accordion-header" id="headingTwo">

                        <button class="accordion-button px-2 text-danger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsethree" aria-expanded="false" aria-controls="collapsethree">
                        Disapproved (<span class="finalreq_disapproved_cnt">-</span>)

                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" id="collapsethree" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body pt-0">
                        <div class="" style="height:45vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">

                            <div class="table-responsive ms-n1 scrollbar">
                                <table class="fs--1 table table-striped  text-center">
                                <thead class="border border-1">
                                    <tr>
                                        <th scope="col" class="" width="5%">#</th>
                                        <th scope="col" class="" width="10%">ISBN</th>
                                        <th scope="col" width="25%">Title</th>
                                        <th scope="col" width="6%">Total<br>Projtn</th>
                                        <th scope="col" width="6%">SOH</th>
                                        <th scope="col" width="6%">Pull Out<br>In-Transit</th>
                                        <th scope="col" width="6%">OMS</th>
                                        <th scope="col" width="6%">On<br>PO</th>
                                        <th scope="col" width="6%">Buff.<br>Stock</th>
                                        <th scope="col" width="6%">Adj.<br>Stock</th>
                                        <th scope="col" width="6%">Req.</th>
                                        <th scope="col" width="7%" title="Proposed Requirement">Prop.<br>Req.</th>
                                        <th scope="col" class="" width="5%"> <div class="mb-0 justify-content-start">

                                            <input class="form-check-input" id="flexCheckDefault" type="checkbox" value="">
                    
                                            
                                        </div></th>
                                    </tr>
                                </thead>
                              
                                </table>
                            </div>
                    </div>
                        <div class="">
                    
                                
                            <div class="text-end border-top p-1 pt-2">
                                <div>
                                    <div class="dropdown font-sans-serif d-inline-block">

                                        <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Download</button><span class="caret"> </span>
                                        <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item text-danger-700" href="#">PDF</a>
                                          <a class="dropdown-item text-success-700" href="#">Excel</a>
                                        </div>
                                      </div>
                                <button type="button" class="btn btn-primary btn-sm ">Return to For Approval</button>
                                </div>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    </div>
                </div>

            

                
            </div>

            <div class="card-body d-none">
                <form class="submit_addnewcustomer_projection" method="POST">
                <div class="row g-3 " > 

                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                Customer
                            </span>
                                    
                            <select class="form-select customer_select" aria-label="Default select example">
                                <option selected="">Search Customer...</option>
                                <option value="angelicum">ANGELICUM LEARNING CENTRE, INC - BSA</option>
                                <option value="christian">CHRISTIAN SAMARITAN HEALTH SERVICES & TECHNICAL SCH. - BASIC EDUCATION - -</option>
                            </select>
                                <input class="input-group-text" title="Customer Code" name=""  type="text" placeholder="Customer Code...">
                            
                
                        </div>
                    </div>

                    
                    <div class="col-md-6 mt-0">

                        <div class="input-group w-75">
                            <span class="input-group-text" id="basic-addon1">
                                Grade/Year Level
                            </span>
                            
                            <select class="form-select" aria-label="Default select example">
                                <option selected="">Select in the list</option>
                                <option value="1">One</option>
                            </select>
                
                        </div>

                

                        <div class="input-group mt-0 w-75">
                            <span class="input-group-text" id="basic-addon1">
                                Semester
                            </span>
                            
                            <select class="form-select" aria-label="Default select example">
                                <option selected="">Select in the list</option>
                                <option value="1">Yearly</option>
                            </select>
                
                        </div>

                        
                    </div>

                    <div class="col-md-6 mt-0">
            
                        <div class="input-group w-75">
                            <span class="input-group-text" id="basic-addon1">
                                School Opening
                            </span>
                            
                            <select class="form-select" aria-label="Default select example">
                                <option selected="">Select in the list</option>
                                <option value="1">June</option>
                                <option value="1">August</option>
                            </select>
                
                        </div>
                        <div class="input-group mt-0 w-75">
                            <span class="input-group-text" id="basic-addon1">
                                Bookshop Branch
                            </span>
                            
                            <select class="form-select bookshop_branch un-cl pm-2" aria-label="Default select example">
                                <option selected="">Select in the list</option>
                                <option value="1">CDO</option>
                                <option value="1">DAVAO</option>
                            </select>
                
                        </div>

                
                        <!-- <div class="mt-0 text-end mt-6 ">
                            <button class="btn btn-danger">Reset Fields</button>
                        </div> -->
                    </div>
                    <hr class="mb-0">
                    <div class="col-lg-12 mt-0 pt-4">
                        <label class="text-1000 fs--1 fw-bold">Book Titles &nbsp&nbsp
                            
                                
                                <a class="fw-bold " href="#!" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-html="true" 
                                data-bs-content='<a class="customer" data-id="1" data-customercode="0001802148" data-customername="ANGELICUM LEARNING CENTRE, INC. - BASIC EDUCATION">ANGELICUM LEARNING CENTRE, INC. - BASIC EDUCATION &nbsp 0001802148 </a><hr class="my-2"><a class="customer" data-id="1" data-customercode="0001802148" data-customername="CHRISTIAN SAMARITAN HEALTH SERVICES & TECHNICAL SCH. - BASIC EDUCATION">CHRISTIAN SAMARITAN HEALTH SERVICES & TECHNICAL SCH. - BASIC EDUCATION &nbsp 0001805266 </a>'
                                >
                                    <span class="text-900 text-primary" data-feather="copy"></span>
                                Copy From Customer</a>
                        
                        </label>   
                        <div><a class="fw-bold" href="#!">+ Add New</a></div>
                        
                    
                            
                        <div style="height:55vh; max-height:100vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">
                            <table id="projection-creation-new-books-table" class="table fs--1 mb-0" width="100%">
                                <thead class="thead-bgcolor text-center" style="position: sticky; top: 0; background-color: white; z-index: 10;">
                                <tr>
                                    <th width="1%"></th>
                                    <th width="25%">Book Title</th>
                                    <th width="10%">ISBN</th>
                                    <th width="10%">Author</th>
                                    <th width="10%">Copyright</th>
                                    <th width="10%">Population </br> Qty</th>
                                    <th width="10%">Projection </br> Qty</th>
                                    <th width="10%">Sales </br> History</th>
                                </tr>
                                </thead>
                                <tbody>
            
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-0 text-end mt-6 "><button class="btn btn-primary">Save</button></div>
                </form>
            </div>



            </div>
        </div>
  </div>



@endsection

@section('scriptJS')


<script>




function get_projectionperiodstatus (projdocnum) {

    $.ajax({
        url:"/get_projection_period_status?projdocnum="+projdocnum, 
        type:'GET',
        headers: {
                'X-CSRF-TOKEN': getCsrfToken() 
        },
        beforeSend: function() {

       
                // showLoadingDiv('.mini-db');
                
        },
        success:function(data){
            console.log(data);
                var d = data[0];
                
                var projperiodstatus = d.projperiodstatus; 
                var countforapprovalfinalreq = d.countforapprovalfinalreq; 
                var countapprovedfinalreq = d.countapprovedfinalreq; 
                var projectionid = d.projectionid; 

                $('.selected_projection_id_text').val(projectionid)

                $('.accordion .accordion-item .accordion-collapse').removeClass('show')
                // $('#collapseOne').addClass('collapse show')

                $('.finalreq_forapproval_cnt').text(countforapprovalfinalreq)
                $('.finalreq_approved_cnt').text(countapprovedfinalreq)

         
                
                hideLoadingDiv('.mini-db');

            
        },

            error:function(data){
                console.log(data)
                
                hideLoadingDiv('.mini-db');
            }
        });



}

function approved_finalreq_list(projdocnum){
    $.ajax({
           url:"/datatable_approved_finalreq_list?basedocnum="+projdocnum, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

     
          
                showLoadingDiv('#approved-finalreq-list-table');
                
           },
           success:function(data){
                $('#approved-finalreq-list-table tbody tr').empty()
               console.log(data);
                var data = data;
          
                hideLoadingDiv('#approved-finalreq-list-table');
                let aa = '';

                if(data.num === '0'){
                    aa += '<tr><td colspan="99">No Approved Requirements.</td></tr>';

                }

                for (let i = 0; i < data.length; i++) {
                    let d = data[i];

                    // raw values muna
                    let num         = d.num;
                    let isbn        = d.isbn;
                    let description = d.description;
                    let totalproj   = d.totalproj;
                    let soh         = d.soh;
                    let pullout     = d.pullouttransit;
                    let onorderoms  = d.onorderoms;
                    let onpo        = d.onpo;
                    let bufferstock = d.bufferstock;
                    let adjstock    = d.adjstock;
                    let requireqty  = d.requireqty;
                    let proprequire = d.proprequireqty;
                    let propreqval = d.propreqval;

                    // tsaka na lang ilagay sa <td>
                    aa += `
                        <tr>
                            <td class="text-center">${num}</td>
                            <td class="text-center">${isbn}</td>
                            <td class="text-center">${description}</td>
                            <td class="text-center">${totalproj}</td>
                            <td class="text-center">${soh}</td>
                            <td class="text-center">${pullout}</td>
                            <td class="text-center">${onorderoms}</td>
                            <td class="text-center">${onpo}</td>
                            <td class="text-center">${bufferstock}</td>
                            <td class="text-center">${adjstock}</td>
                            <td class="text-center">${requireqty}</td>
                            <td class="text-center">${propreqval}</td>
                        </tr>
                    `;
                }


                    $('#approved-finalreq-list-table tbody').append(aa)


           },

            error:function(data){

                    
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });


}

function projsummary_finalreq_list(projdocnum){

$.ajax({
       url:"/datatable_projsummary_finalreq_list?basedocnum="+projdocnum, 
       type:'GET',
       headers: {
               'X-CSRF-TOKEN': getCsrfToken() 
       },
       beforeSend: function() {


            showLoadingDiv('.card_projsummary');
            $('#projsummary-finalreq-list-table tbody').empty();
            
            
       },
       success:function(data){
            $('#projsummary-finalreq-list-table tbody').empty()
           console.log(data);
            var data = data;
      
            hideLoadingDiv('.card_projsummary');
            let aa = '';

            if(data.num === '0'){
                aa += '<tr><td colspan="99">No Approved ISBN.</td></tr>';

            }

            for (let i = 0; i < data.length; i++) {
                let d = data[i];

                // raw values muna
                let num         = d.num;
                let isbn        = d.isbn;
                let description = d.description;
                let totalproj   = d.totalproj;
                let soh         = d.soh;
                let pullout     = d.pullouttransit;
                let onorderoms  = d.onorderoms;
                let onpo        = d.onpo;
                let bufferstock = d.bufferstock;
                let adjstock    = d.adjstock;
                let requireqty  = d.requireqty;
                let proprequire = d.proprequireqty;
                let checkisbn   = d.checkisbn;
                let total_1   = d.total_1;
                let total_2   = d.total_2;
                let total_3   = d.total_3;

                // tsaka na lang ilagay sa <td>
                aa += `
                    <tr>
                        <td class="text-center">${num}</td>
                        <td class="text-center">${isbn}</td>
                        <td class="text-center">${description}</td>
                        <td class="text-center">${totalproj}</td>
                        <td class="text-center">${soh}</td>
                        <td class="text-center">${pullout}</td>
                        <td class="text-center">${onorderoms}</td>
                        <td class="text-center">${onpo}</td>
                        <td class="text-center">${bufferstock}</td>
                        <td class="text-center">${adjstock}</td>
                        <td class="text-center">${requireqty}</td>
                        <td class="text-center">${proprequire}</td>
                        <td class="text-center">${total_1}</td>
                        <td class="text-center">${total_2}</td>
                        <td class="text-center">${total_3}</td>
                        <td class="text-center">${checkisbn}</td>
                    </tr>
                `;
            }


                $('#projsummary-finalreq-list-table tbody').append(aa)

                submit_finalreq (projdocnum,'0','.projsummary_finalreq_isbn_check:checked')


       },

        error:function(data){

            hideLoadingDiv('.card_projsummary');

                
                    sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
        }
    });



}

function insertedisbn_finalreq_list(projdocnum,filter='1'){

    $.ajax({
           url:"/datatable_insertedisbn_finalreq_list?basedocnum="+projdocnum+"&filter="+filter, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

              $('#insertedisbn-finalreq-list-table tbody tr').empty()
          
                showLoadingDiv('.card_projsummary');
                
           },
           success:function(data){
                $('#insertedisbn-finalreq-list-table tbody tr').empty()
               console.log(data);
                var data = data;
          
                hideLoadingDiv('.card_projsummary');
                let aa = '';

                if(data.num === '0'){
                    aa += '<tr><td colspan="99">No Records Found.</td></tr>';

                }

                for (let i = 0; i < data.length; i++) {
                    let d = data[i];

                    // raw values muna
                    let num         = d.num;
                    let isbn        = d.isbn;
                    let description = d.description;
                    let totalproj   = d.totalproj;
                    let soh         = d.soh;
                    let pullout     = d.pullouttransit;
                    let onorderoms  = d.onorderoms;
                    let onpo        = d.onpo;
                    let bufferstock = d.bufferstock;
                    let adjstock    = d.adjstock;
                    let requireqty  = d.requireqty;
                    let proprequire = d.proprequireqty;
                    let checkisbn   = d.checkisbn;
                    let total_1   = d.total_1;
                    let total_2   = d.total_2;
                    let total_3   = d.total_3;

                    // tsaka na lang ilagay sa <td>
                    aa += `
                        <tr>
                            <td class="text-center">${num}</td>
                            <td class="text-center">${isbn}</td>
                            <td class="text-center">${description}</td>
                            <td class="text-center">${totalproj}</td>
                            <td class="text-center">${soh}</td>
                            <td class="text-center">${pullout}</td>
                            <td class="text-center">${onorderoms}</td>
                            <td class="text-center">${onpo}</td>
                            <td class="text-center">${bufferstock}</td>
                            <td class="text-center">${adjstock}</td>
                            <td class="text-center">${requireqty}</td>
                            <td class="text-center">${proprequire}</td>
                            <td class="text-center">${total_1}</td>
                            <td class="text-center">${total_2}</td>
                            <td class="text-center">${total_3}</td>
                            
                            <td class="text-center">${checkisbn}</td>
                        </tr>
                    `;
                }


                    $('#insertedisbn-finalreq-list-table tbody').append(aa)


           },

            error:function(data){

                    
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });



}


function submit_finalreq (projdocnum,approve = '0',isbncheckedclass) {

    let formDataApprove = new FormData();

    var isbnchecked = $(isbncheckedclass);

    isbnchecked.each( function(index) {
              
                let $tr = $(this).closest('tr');           
                let $input = $tr.find('.proposedreq_qty'); 

                let customercode   = $input.data('customercode');
                let isbn           = $input.data('isbn');
                let description    = $input.data('description');
                let basedocnum     = $input.data('basedocnum');
                let totalproj      = $input.data('totalproj');
                let soh            = $input.data('soh');
                let pullouttransit = $input.data('pullouttransit');
                let onorderoms     = $input.data('onorderoms');
                let onpo           = $input.data('onpo');
                let bufferstock    = $input.data('bufferstock');
                let adjstock       = $input.data('adjstock');
                let requireqty     = $input.data('requireqty');
                let proposedreqqty = $input.val(); 

                
                formDataApprove.append('customercode[]', customercode);
                formDataApprove.append('isbn[]', isbn);
                formDataApprove.append('description[]', description);
                formDataApprove.append('basedocnum[]', basedocnum);
                formDataApprove.append('totalproj[]', totalproj);
                formDataApprove.append('soh[]', soh);
                formDataApprove.append('pullouttransit[]', pullouttransit);
                formDataApprove.append('onorderoms[]', onorderoms);
                formDataApprove.append('onpo[]', onpo);
                formDataApprove.append('bufferstock[]', bufferstock);
                formDataApprove.append('adjstock[]', adjstock);
                formDataApprove.append('requireqty[]', requireqty);
                formDataApprove.append('proposedreqqty[]', proposedreqqty);


            })


    $.ajax({
            url: "/submit_finalreq?projdocnum="+projdocnum+"&approve="+approve,
            data: formDataApprove,
            processData: false,
            contentType: false,
            type: 'POST',
            headers: {
                    'X-CSRF-TOKEN': getCsrfToken() 
            },

            beforeSend: function() {
                showLoadingDiv('.card_projsummary');

                if(approve === '1') {
                    
                        showLoading();

                }
            },
            success: function(data) {
                console.log(data);
    
                insertedisbn_finalreq_list(projdocnum)

                $('.card_projsummary .nav-link').removeClass('active')
                $('.allproposalfilter .nav-link').addClass('active')

                if(approve === '1') {

                    hideLoading();

                    if (data.status == "2") {

                      
                        sweetalert("Success!","<a class='btn btn-sm btn-primary' href='/imd/stockallocation?pid="+projdocnum+"''> <i class='fas fa-link'></i> Go to Stock Allocation </a> ", icon = 'success', timer = '10000', btn = false);


                        // get_projectionperiodstatus (projdocnum)

                    }
                    else if (data.status == "403") { 
                    sweetalert("Please select ISBN to approve","", icon = 'warning', timer = '3000', btn = false);

                    }
                    else {
                    swal("Oops...", "Please contact the administrator", "error");
                    }

                        ajaxInProgress = false;


                }
    
            },
            error: function(data) {
        
                swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                hideLoading();

                ajaxInProgress = false;
            }
            
        });
}

$(document).ready(function(){


    var projectionperiod = $('.selected_projection_id').val()
   

   
    $(document).on('click','.btn_projsummary_finalreq_showbtn',function (e) {

        var basedocnum =  $('.selected_projection_id').val()

        if (!$('.btn_projsummary_finalreq_showbtn').hasClass('collapsed')) {


            projsummary_finalreq_list(basedocnum);
        }
       


    })

    $(document).on('click','.allproposalfilter',function (e) {

        var projdocnum = $('.selected_projection_id').val();

        insertedisbn_finalreq_list(projdocnum,'1')

    });
    $(document).on('click','.withoutproposalfilter',function (e) {

        var projdocnum = $('.selected_projection_id').val();

         insertedisbn_finalreq_list(projdocnum,'0')

    });
    $(document).on('click','.withproposalfilter',function (e) {

        var projdocnum = $('.selected_projection_id').val();

         insertedisbn_finalreq_list(projdocnum,'2')

        
    })
    $(document).on('click','.btn-exportexcel-forapproval-finalreq',function (e) {

        var projectionperiodtext = $('.selected_projection_id_text').val()

        ExportExcel('insertedisbn-finalreq-list-table', 'Projection Period - '+ projectionperiodtext + ' - Final Req For Approval ')

    });

    $(document).on('click','.btn-printpdf-zeroproposal-finalreq',function (e) {

        var projdocnum = $('.selected_projection_id').val();

        window.open('/PrintWithoutProposalFinalReq?projdocnum='+ projdocnum, '_blank', 'width=800,height=600'); 

    });

    $(document).on('click','.btn-printpdf-withproposal-finalreq',function (e) {

        var projdocnum = $('.selected_projection_id').val();

        window.open('/PrintWithProposalFinalReq?projdocnum='+ projdocnum, '_blank', 'width=800,height=600'); 

    });


    $(document).on('click','.btn-printpdf-forapproval-finalreq',function (e) {

        var projdocnum = $('.selected_projection_id').val();

        window.open('/PrintForApprovalFinalReq?projdocnum='+ projdocnum, '_blank', 'width=800,height=600'); 

    });
    $(document).on('click','.btn-exportexcel-approved-finalreq',function (e) {

        var projectionperiodtext = $('.selected_projection_id_text').val()

        ExportExcel('approved-finalreq-list-table', 'Projection Period - '+ projectionperiodtext + ' - Final Req Approved ')

    });

    $(document).on('click','.projectionid-status-sw',function (e) {
        
        var projdocnum = $('.selected_projection_id').val();

            if($(this).is(':checked')){
               var v = '1';
            }
            else {
                var v = '0';
            }

            $.ajax({
                     url:"/submit_update_status_projectionid", 
                     data: {
                        v : v,
                        docnum : projdocnum,
                     },
                     type:'POST',
                     headers: {
                              'X-CSRF-TOKEN': getCsrfToken() 
                     },
                     beforeSend: function() {
                     
                        showLoadingDiv('#open-projection-list')
                     },
                     success:function(data){
                           console.log(data);
                           hideLoadingDiv('#open-projection-list')
                           
                        //    var data = data[0];
                           
                           if(data.status == '2') {
  
                                //  sweetalert(" ","Status Updated!", icon = 'success', timer = '1000', btn = false);
                                
                                 var html = "" 
                                  + "<span class='text-success fw-bold'>Status Updated!</span>"
                                  + "";

                                toastifyShow(html)  
                           }
                           else   {

                                 swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                           }

                           get_projectionperiodstatus (projdocnum)
                           
                        },
                           error:function(data){
                                 hideLoading();
                                
                                 swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                        }
              });

    });

    $(document).on('click','.projsummary_checkallfinalreq',function (e) {

        
        var editcustomerisbncheck= $('.projsummary_finalreq_isbn_check') 


        if($(this).is(':checked')){

            var v = '1';

            editcustomerisbncheck.prop('checked',true)

        }
        else {
            var v = '0';
            editcustomerisbncheck.prop('checked',false)

        }

        $('.projsummary_finalreq_isbn_check').val(v)

    });

    $(document).on('click','.for_approval_checkallfinalreq',function (e) {

   
        var editcustomerisbncheck= $('.for_approval_finalreq_isbn_approve_check') 


        if($(this).is(':checked')){

            var v = '1';

            editcustomerisbncheck.prop('checked',true)

        }
        else {
            var v = '0';
            editcustomerisbncheck.prop('checked',false)

        }

        $('.for_approval_finalreq_isbn_approve_check').val(v)

    });

    $(document).on('click','.btn-forapproval-finalreq',function (e) {

        var projdocnum = $('.selected_projection_id').val();
            var notemptyselected = false;
            var forapprovalisbnchecked = $('.for_approval_finalreq_isbn_approve_check:checked');

            if(forapprovalisbnchecked.length > 0 ){
                notemptyselected = true;
            }

            if(!notemptyselected) {

                sweetalert(" ","Please select ISBN to approve", icon = 'warning', timer = '5000', btn = false);
                return false;
            }


            swal({
                    title: "Are you sure you want to tag as For Approval?",
                    text: "",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    submit_finalreq (projdocnum,'0','.for_approval_finalreq_isbn_approve_check:checked')

                } 
                else {
                
                        
                }
            });


    });

    $(document).on('click','.btn-projsummary-forapproval',function (e) {

            var projdocnum = $('.selected_projection_id').val();
                var notemptyselected = false;
                var projsummaryisbnchecked = $('.projsummary_finalreq_isbn_check:checked');

                if(projsummaryisbnchecked.length > 0 ){
                    notemptyselected = true;
                }

                if(!notemptyselected) {

                    sweetalert(" ","Please select ISBN to approve", icon = 'warning', timer = '5000', btn = false);
                    return false;
                }


                swal({
                        title: "Are you sure you want to tag as For Approval?",
                        text: "",
                        icon: "info",
                        buttons: true,
                        dangerMode: true,
                })
                .then((willCancel) => {

                    
                    if (willCancel) {
                        
                        submit_finalreq (projdocnum,'0',projsummaryisbnchecked)

                    } 
                    else {
                    
                            
                    }
                });


    });

    $(document).on('click','.btn-forapproval-approve-finalreq',function (e) {

            var projdocnum = $('.selected_projection_id').val();
            var notemptyselected = false;
            var forapprovalisbnchecked = $('.for_approval_finalreq_isbn_approve_check:checked');

            if(forapprovalisbnchecked.length > 0 ){
                notemptyselected = true;
            }

            if(!notemptyselected) {

                sweetalert(" ","Please select ISBN to approve", icon = 'warning', timer = '5000', btn = false);
                return false;
            }


            swal({
                    title: "Are you sure you want to tag as Approved?",
                    text: "",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    submit_finalreq (projdocnum,'1',forapprovalisbnchecked)

                } 
                else {
                
                        
                }
            });

    });

    $(document).on('click','.btn-projsummary-approve',function (e) {

            var projdocnum = $('.selected_projection_id').val();
            var notemptyselected = false;
            var projsummaryisbnchecked = $('.projsummary_finalreq_isbn_check:checked');

            if(projsummaryisbnchecked.length > 0 ){
                notemptyselected = true;
            }

            if(!notemptyselected) {

                sweetalert(" ","Please select ISBN to approve", icon = 'warning', timer = '5000', btn = false);
                return false;
            }


            swal({
                    title: "Are you sure you want to tag as Approved?",
                    text: "",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    submit_finalreq (projdocnum,'1',projsummaryisbnchecked)

                } 
                else {
                
                        
                }
            });

            
    })

    
    $(document).on('click','.btn_approved_finalreq_showbtn',function (e) {

        var projdocnum = $('.selected_projection_id').val();

        approved_finalreq_list(projdocnum)


    });
    // $(document).on('click','.btn_forapproval_finalreq_showbtn',function (e) {

    //     var projdocnum = $('.selected_projection_id').val();
 
    //     insertedisbn_finalreq_list(projdocnum);


    // });

    $(document).on('change','.bufferstock_qty',function (e) {
        let trClosest = $(this).closest('tr');
        
        let bufferstock    = $(this).val();
        let closestProposeReqQTY = trClosest.find('.proposedreq_qty');
        let proposedreqqty = closestProposeReqQTY.val();
        let projdocnum = closestProposeReqQTY.data('basedocnum');
        let isbn = closestProposeReqQTY.data('isbn');
        let customercode   = closestProposeReqQTY.data('customercode');
        let description    = closestProposeReqQTY.data('description');
        let totalproj      = closestProposeReqQTY.data('totalproj');
        let soh            = closestProposeReqQTY.data('soh');
        let pullouttransit = closestProposeReqQTY.data('pullouttransit');
        let onorderoms     = closestProposeReqQTY.data('onorderoms');
        let onpo           = closestProposeReqQTY.data('onpo');
        let adjstock       = closestProposeReqQTY.data('adjstock');
        let requireqty     = closestProposeReqQTY.data('requireqty');

        $.ajax({
                url:"/submit_update_finalreq_buffstock", 
                data: {
                    proposedreqqty : proposedreqqty,
                    basedocnum : projdocnum,
                    isbn : isbn,
                    pullouttransit :  pullouttransit ,
                    totalproj      :  totalproj,
                    soh            :  soh,
                    onorderoms     :  onorderoms,
                    onpo           :  onpo,
                    bufferstock    :  bufferstock,
                    adjstock       :  adjstock,
                    requireqty     :  requireqty,
                },
                type:'POST',
                headers: {
                        'X-CSRF-TOKEN': getCsrfToken() 
                },
                beforeSend: function() {
                
                },
                success:function(data){
                    console.log(data);
            
                    if(data.status == '2') {
                   
                        closestProposeReqQTY.val(data.propreq)
                        trClosest.find('.adstockinsertedisbntext').text(data.adjstock)
                        trClosest.find('.requireqtyinsertedisbntext').text(data.req)

                            var html = "" 
                            + "<span class='text-success fw-bold'>Updated!</span>"
                            + "";

                                toastifyShow(html)  
                    }

                    
                },
                error:function(data){
                            hideLoading();
                        
                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                }
        });

      

    });

    $(document).on('change','.proposedreq_qty',function (e) {

        let proposedreqqty = $(this).val();
        let projdocnum = $(this).data('basedocnum');
        let isbn = $(this).data('isbn');
        let customercode   = $(this).data('customercode');
        let description    = $(this).data('description');
        let totalproj      = $(this).data('totalproj');
        let soh            = $(this).data('soh');
        let pullouttransit = $(this).data('pullouttransit');
        let onorderoms     = $(this).data('onorderoms');
        let onpo           = $(this).data('onpo');
        let bufferstock    = $(this).data('bufferstock');
        let adjstock       = $(this).data('adjstock');
        let requireqty     = $(this).data('requireqty');

        $.ajax({
            url:"/submit_update_proposedreq_qty", 
            data: {
                proposedreqqty : proposedreqqty,
                projdocnum : projdocnum,
                isbn : isbn,
                pullouttransit :  pullouttransit ,
                totalproj      :  totalproj,
                soh            :  soh,
                onorderoms     :  onorderoms,
                onpo           :  onpo,
                bufferstock    :  bufferstock,
                adjstock       :  adjstock,
                requireqty     :  requireqty,
            },
            type:'POST',
            headers: {
                    'X-CSRF-TOKEN': getCsrfToken() 
            },
            beforeSend: function() {
            
            },
            success:function(data){
                console.log(data);
        
                if(data.status == '2') {

                    
                        var html = "" 
                        + "<span class='text-success fw-bold'>Updated!</span>"
                        + "";

                            toastifyShow(html)  
                }

                
            },
            error:function(data){
                        hideLoading();
                    
                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
            }
    });
        

    });
    $(document).on('change','.selected_projection_id',function (e) {
        
        var v = $(this).val();
        var pernr = "{{session('pernr')}}";
        var username = "{{session('user_staff')}}";

        $('.finalizereq_card').removeClass('d-none')

        get_projectionperiodstatus(v)
        get_projperiod_details (v) 



    });


   
//END READY
});





</script>

@endsection