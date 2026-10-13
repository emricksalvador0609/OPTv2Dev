@if(!rankView('IMD','CRM'))
<script>
    window.location.href = "{{ route('notfound') }}";
</script>
@endif

@extends('layouts.admin_app')

@section('title') Stock Allocation @endsection

@section('belowcontent')



  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder">Stock Allocation</h4>
          
       </div>
    </div>

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
          </div>
      
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

<div class="card mt-2 shadow-none border border-300">
    <div class="card-body p-1">

   

        <div style="height:50vh; overflow-y:auto;">
                <div class="">
                    <table id="stock-allocation-list-table" class="fs--1 p-2 table-responsive table-striped table text-center">
                        <thead class="border border-1 sticky-top bg-white" style="z-index: 10;">
                                    
                            <tr>
                                <th scope="col" class="text-center" width="4%">#</th>
                                <th scope="col" class="text-center" width="12%">ISBN</th>
                                <th scope="col" class="text-center" width="30%">Title</th>
                                <th scope="col" class="text-center" width="7%"># of <br> AE</th>
                                <th scope="col" class="text-center" width="24%">Author</th>
                                {{-- <th scope="col" class="text-center" width="7%">Finalized <br> Req.</th> --}}
                                <th scope="col" class="text-center" width="7%">Total </br> Projection</th>
                                {{-- <th scope="col" class="text-center px-3" width="5%">Action</th> --}}
                                <th scope="col" class="text-center" width="3%">
                                    <div class="d-flex ">
                                        <input class="form-check-input stockallocateisbn_all" checked type="checkbox" value="">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        
                        </tbody>
                </table>
            </div>
        </div>
        <div class="">

        
            <div class="text-end border-top p-3">
                <div>
                    
                    <button type="button" data-bs-toggle="modal" data-bs-target="#" class="btn btn-primary btn-sm btn-generate-autoallocation">Generate Allocation</button>
                    {{-- <button type="button" class="btn btn-success btn-sm btn-approve">Approve</button> --}}
                </div>
            </div>


        </div>
    </div>
</div>


@endsection

<div class="modal" id="UserStockAllocateQtyModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="text-600"> Allocate Qty -  </span>  <b class="stockallocateqty_title_text"> - </b> </h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
    
    
        <div class="modal-body pt-0 mt-0 ">
            <div class="row pt-2">

                <div class="col-md-4 border-end">
                    <div class="card">
                        <div class="card-body ">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item border-top border-300">
                                  <h2 class="accordion-header" id="headingOne">
        
                                    <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Branch/Whouse Selected
        
                                    </button>
                                  </h2>
                                  <div class="accordion-collapse collapse show" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body pt-0">
                                        <table id="stockallocate-branchwhouse-list" class="fs--1 table table-striped  border bg-white table-bordered text-center">
                                            <thead class="border border-1">
                                             
                                               <tr>
                                                  <th scope="col" class="text-center" width="70%" >Name</th>
                                                  <th scope="col" class="text-center" width="30%">Qty</th>
                                               </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot hidden>
                                                <tr>
                                                    <th scope="col" class="text-center">Total</th>
                                                    <th scope="col" class="text-center"> <span class="">-</span> </th>
                                                </tr>
                                            </tfoot>
                                         </table>

                                      
                                    
                                    </div>
                                  </div>
                                </div>
                                <div class="accordion-item">
                                  <h2 class="accordion-header" id="headingTwo">
        
                                    <button class="accordion-button  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Stocks Per Locations
        
                                    </button>
                                  </h2>
                                  <div class="accordion-collapse collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body pt-0">
                                        <table id="stocks-per-location-list" class="fs--1 table table-striped  border bg-white table-bordered text-center">
                                            <thead class="border border-1">
                                             
                                               <tr>
                                                  <th scope="col" class="text-center" width="70%" >Location</th>
                                                  <th scope="col" class="text-center" width="30%">Qty</th>
                                               </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th scope="col" class="text-center">Total</th>
                                                    <th scope="col" class="text-center"> <span class="soh_total">-</span> </th>
                                                </tr>
                                            </tfoot>
                                         </table>
                                    
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-8 p-0">
                    <div class="card">
                        <div class="">
                                <input type="number" class="d-none form-control un-cl stockallocate_approve_req_val" hidden readonly="readonly'">
                                <input type="number" class="d-none form-control un-cl stockallocate_totalproj_val" hidden readonly="readonly'">
                                <input type="number" class="d-none form-control un-cl stockallocate_isbn_val" hidden readonly="readonly'">
                        </div>
                        <div class="card-body p-2 border-bottom">
                            <div class="row mb-1">
                            
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text text-success-600" id="basic-addon1">
                                            Approved Req.
                                        </span>
                                        <span class="input-group-text " style="background-color:white !important;" id="basic-addon1"> 
                                            <span class="stockallocate_approve_req_text">-</span> 
                                        </span>
                            
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <button class="btn btn-sm btn_autoallocate btn-primary" data-aprvqty="500"> Auto Allocate  Qty</button>
                                </div>
                            </div>
                            <form class="submit_stock_allocateqty" method="POST">
                                <div  style="height:55vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">
                                    <div class="table-responsive" >
                                        <table id="stockallocate-user-list-table" class="fs--1 p-2 table-striped table text-center" width="100%" style="position: relative;">
                                            <thead class="border border-1 sticky-top bg-white" style="z-index: 10;">
                                    
                                            
                                            <tr role="row">
                                                <th colspan="2" class="bg-white text-center" rowspan="1">&nbsp</th>
                                                <th colspan="2" class="bg-white border-top text-center" rowspan="1">Projection</th>
                                                <th colspan="2" class="bg-white border-top text-center" rowspan="1">Allocate Qty</th>
                                                <th colspan="3" class="bg-white border-top text-center" rowspan="1">Sales History</th>
                                            
                                            </tr>

                                            <tr>
                                                <th scope="col" class="text-center" width="4%">#</th>
                                                <th scope="col" class="text-center" width="26%">AE Name</th>
                                                <th scope="col" class="text-center" width="7%">BSA</th>
                                                <th scope="col" class="text-center" width="7%">Non-BSA</th>
                                                <th scope="col" class="text-center" width="10%">BSA</th>
                                                <th scope="col" class="text-center" width="10%">Non-BSA</th>
                                                <th scope="col" class="text-center" width="5%">{{ getPreviousYear(1) }} </th>
                                                <th scope="col" class="text-center" width="5%">{{ getPreviousYear(2) }} </th>
                                                <th scope="col" class="px-1 text-center" width="5%">{{ getPreviousYear(3) }} </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    
                                        
                                            {{-- <td><input class="form-control form-control-sm border text-center border-primary allocate_qty_nonbsa allocate_qty_input" data-projqty="60" type="number" value="0" min="1"></td>
                                            <td><input class="form-control mx-1  form-control-sm border text-center border-primary allocate_qty_bsa allocate_qty_input" data-projqty="100" type="number" value="0" min="1"></td>
                                        --}}
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row p-2 ">
                                <div class="col-md-5">
                                    <div class="input-group mx-2">
                                        <span class="input-group-text text-info-500" id="basic-addon1">
                                            Total Projection
                                        </span>
                                        <span class="input-group-text stockallocate_total_projection" style="background-color:white !important;" id="basic-addon1">  0
                                        </span>
                            
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text text-primary-600" id="basic-addon1">
                                            Total Allocation
                                        </span>
                                        <span class="input-group-text total_allocation " style="background-color:white !important;" id="basic-addon1">  0
                                        </span>
                                        <input class="d-none un-cl form-control total_allocation_val" hidden readonly="readonly">
                            
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex justify-content-end ">
                                    <div class="mt-0 text-end ">
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>

                            </form>
                    
            
                    </div>

                </div>
            </div>
               
            
        </div>
        
        <div class="modal-footer d-none px-0 pb-0">
          
        </div>
        

    </div>
    </div>
</div>

<div class="modal" id="AutoAllocateAllISBNModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="text-600"> Auto Allocation - </span>  <b class="projectionperiod_text_display"> - </b> </h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
    
    
        <div class="modal-body p-1 pt-0 mt-0 ">
            <div class="row pt-2">

                
                <div class="col-md-12 p-0">
                    <div class="card">
                        <div class="">
                          
                        </div>
                        <div class="card-body p-2 border-bottom">
                          
                            <form class="submit_autoallocate_all_isbn" method="POST">
                                <div  style="height:60vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">
                                    <div class="" >
                                        <table id="autoallocate-user-list-table" class="fs--1 p-2 table text-center" width="100%" style="position: relative;">
                                            <thead class="border border-1 sticky-top bg-white" style="z-index: 10;">
                                    
                                            
                                            <tr role="row">
                                                <th colspan="1" class="bg-white border-end text-center">&nbsp</th>
                                                <th colspan="2" class="bg-white border-end text-center">Projection</th>
                                                <th colspan="2" class="bg-white border-end text-center">Auto Allocation</th>
                                                <th colspan="3" class="bg-white border-top text-center">Sales History</th>
                                            
                                            </tr>

                                            <tr>
                                            
                                                {{-- <th scope="col" class="text-center" width="12%">ISBN</th> --}}
                                                <th scope="col" class="text-center" width="26%">Title / AE</th>
                                                <th scope="col" class="text-center" width="7%">BSA</th>
                                                <th scope="col" class="text-center" width="7%">Non-BSA</th>
                                                <th scope="col" class="text-center" width="10%">BSA</th>
                                                <th scope="col" class="text-center" width="10%">Non-BSA</th>
                                                <th scope="col" class="text-center" width="5%">{{ getPreviousYear(1) }}</th>
                                                <th scope="col" class="text-center" width="5%">{{ getPreviousYear(2) }}</th>
                                                <th scope="col" class="text-center" width="5%">{{ getPreviousYear(3) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>

                         
                            <div class="row p-2 ">
                               
                                <div class="col-md-12 d-flex justify-content-end ">
                                    <div class="mt-0 text-end ">
                                        <button type="button" class="btn btn-submit-allocation-all-isbn btn-sm btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                    
            
                    </div>

                </div>
            </div>
               
            
        </div>
        
        <div class="modal-footer d-none px-0 pb-0">
          
        </div>
        

    </div>
    </div>
</div>


@section('scriptJS')


<script>


function autoAllocatePerIsbn() {

// loop per ISBN group row
$('.isbn-group').each(function () {
    const group = $(this);
    const isbn = group.data('isbn');
    const approveQty = parseInt(group.data('proposereq')) || 0;

    // find all rows under this isbn group
    const bsaRows = $(`.allocate_qty_bsa[data-isbn="${isbn}"]`);
    const nonBsaRows = $(`.allocate_qty_nonbsa[data-isbn="${isbn}"]`);

    let totalProjBSA = 0;
    let totalProjNonBSA = 0;

    // Step 1️⃣: Compute projected totals
    bsaRows.each(function () {
        totalProjBSA += parseInt($(this).data('projqty')) || 0;
    });
    nonBsaRows.each(function () {
        totalProjNonBSA += parseInt($(this).data('projqty')) || 0;
    });

    const totalProjQty = totalProjBSA + totalProjNonBSA;

    // Step 2️⃣: Apply allocation rules
    if (approveQty >= totalProjQty) {
        // Rule #1 — Sapat ang approved qty sa lahat
        bsaRows.each(function () {
            $(this).val($(this).data('projqty'));
        });
        nonBsaRows.each(function () {
            $(this).val($(this).data('projqty'));
        });
    } 
    else if (approveQty >= totalProjBSA) {
        // Rule #2 — Sapat sa BSA, kulang sa total
        bsaRows.each(function () {
            $(this).val($(this).data('projqty'));
        });

        const remaining = approveQty - totalProjBSA;
        let totalNonBSAUsed = 0;

        nonBsaRows.each(function () {
            const proj = parseInt($(this).data('projqty')) || 0;
            const share = proj / totalProjNonBSA;
            const alloc = Math.min(Math.floor(share * remaining), proj);
            $(this).val(alloc);
            totalNonBSAUsed += alloc;
        });

        // distribute remaining sobra
        let remainNonBSA = remaining - totalNonBSAUsed;
        nonBsaRows.each(function () {
            if (remainNonBSA <= 0) return;
            const proj = parseInt($(this).data('projqty')) || 0;
            const current = parseInt($(this).val()) || 0;
            if (current < proj) {
                $(this).val(current + 1);
                remainNonBSA--;
            }
        });
    } 
    else {
        // Rule #3 — Hindi sapat kahit sa BSA → 80/20 split
        const allocBSA = Math.min(approveQty * 0.8, totalProjBSA);
        const allocNonBSA = Math.min(approveQty * 0.2, totalProjNonBSA);
        let usedBSA = 0, usedNonBSA = 0;

        bsaRows.each(function () {
            const proj = parseInt($(this).data('projqty')) || 0;
            const share = proj / totalProjBSA;
            const alloc = Math.min(Math.floor(share * allocBSA), proj);
            $(this).val(alloc);
            usedBSA += alloc;
        });

        nonBsaRows.each(function () {
            const proj = parseInt($(this).data('projqty')) || 0;
            const share = proj / totalProjNonBSA;
            const alloc = Math.min(Math.floor(share * allocNonBSA), proj);
            $(this).val(alloc);
            usedNonBSA += alloc;
        });

        // distribute remaining BSA
        let remainBSA = Math.round(allocBSA - usedBSA);
        bsaRows.each(function () {
            if (remainBSA <= 0) return;
            const proj = parseInt($(this).data('projqty')) || 0;
            const current = parseInt($(this).val()) || 0;
            if (current < proj) {
                $(this).val(current + 1);
                remainBSA--;
            }
        });

        // distribute remaining Non-BSA
        let remainNonBSA = Math.round(allocNonBSA - usedNonBSA);
        nonBsaRows.each(function () {
            if (remainNonBSA <= 0) return;
            const proj = parseInt($(this).data('projqty')) || 0;
            const current = parseInt($(this).val()) || 0;
            if (current < proj) {
                $(this).val(current + 1);
                remainNonBSA--;
            }
        });
    }

    // Step 3️⃣: Update totals per group (optional display)
    const totalAllocated = bsaRows.add(nonBsaRows).toArray()
        .reduce((sum, el) => sum + (parseInt($(el).val()) || 0), 0);

    group.find('.total_allocation').text(totalAllocated);

    console.log(`ISBN ${isbn} → Total Allocated: ${totalAllocated}`);
});
}


function get_projectionperiodstatus (projdocnum) {

    $.ajax({
        url:"/get_projection_period_status?projdocnum="+projdocnum, 
        type:'GET',
        headers: {
                'X-CSRF-TOKEN': getCsrfToken() 
        },
        beforeSend: function() {

       
                showLoadingDiv('.mini-db');
                
        },
        success:function(data){
            // console.log(data);
                var d = data[0];
                
                var projperiodstatus = d.projperiodstatus; 
                var projectionid = d.projectionid; 

                $('.selected_projection_id_text').val(projectionid)
                datatable_stock_allocation_list(projdocnum)

               

                if(projperiodstatus === '1'){
                    
                    $('.stockalloc_card').addClass('un-cl')             
                    $('.projectionid_status_text ').html(`<span class="text-success blink-text"> Open </span>`)
                    $('.projectionid-status-sw' ).prop('checked',true)
                    

                }
                else {
                    $('.stockalloc_card').removeClass('un-cl')
                    $('.projectionid-status-sw' ).prop('checked',false)
                    $('.projectionid_status_text ').html(`<span class="text-600"> Closed </span>`)
            

                }
                
                hideLoadingDiv('.mini-db');

            
        },

            error:function(data){
                console.log(data)
                
                hideLoadingDiv('.mini-db');
            }
        });



}

function datatable_soh_isbn_per_location_list(isbn){

    $.ajax({
           url:"/datatable_soh_isbn_per_location_list?isbn="+isbn, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

            //   $('#for-approval-finalreq-list-table tbody tr').empty()
          
                showLoadingDiv('#stocks-per-location-list');
                
           },
           success:function(data){
                $('#stocks-per-location-list tbody tr').empty()
            //    console.log(data);
                var data = data;
          
                hideLoadingDiv('#stocks-per-location-list');
                let aa = '';
                var total = 0;

                if(data.num === '0'){
                    aa += '<tr><td colspan="99">-</td></tr>';

                }

                for (let i = 0; i < data.length; i++) {
                    let d = data[i];

                    // raw values muna
                    let num         = d.num;
                    let location         = d.location;
                    let qty         = d.qty;
                    var total         = d.total;

                    // tsaka na lang ilagay sa <td>
                    aa += `
                        <tr>
                            <td class="text-center">${location}</td>
                            <td class="text-center">${qty}</td>
                        </tr>
                    `;
                }

                    $('.soh_total').text(total);

                    $('#stocks-per-location-list tbody').append(aa)

               


           },

            error:function(data){

                    
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });


}

function datatable_stockallocate_isbn_user_list(projdocnum,isbn){
    $.ajax({
           url:"/datatable_stockallocate_isbn_user_list?basedocnum="+projdocnum+"&isbn="+isbn, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

            //   $('#for-approval-finalreq-list-table tbody tr').empty()
          
                showLoadingDiv('#stockallocate-user-list-table');
                $('#stockallocate-user-list-table tbody tr').empty()
                $('#stockallocate-branchwhouse-list tbody tr').empty()
                
           },
           success:function(data){

                $('#stockallocate-user-list-table tbody tr').empty()
                $('#stockallocate-branchwhouse-list tbody tr').empty()
            //    console.log(data);
                var stuserlist = data.stockallocateuserlist;
                var bwqty = data.branchwhouseqty;

          
                hideLoadingDiv('#stockallocate-user-list-table');
                let aa = '';
                var emptyList = true;
                if(data.num === '0'){
                    // aa += '<tr><td colspan="99">No Approved ISBN.</td></tr>';

                }

                for (let i = 0; i < stuserlist.length; i++) {
                    let d = stuserlist[i];

                    // raw values muna
                    var num         = d.num;
                    let username         = d.username;
                    let bsa         = d.bsa;
                    let nonbsa         = d.nonbsa;
                    let bsaallocateqty         = d.bsaallocateqty;
                    let nonbsaallocateqty         = d.nonbsaallocateqty;
                    let total1         = d.total1;
                    let total2         = d.total2;
                    let total3         = d.total3;
               

                    // tsaka na lang ilagay sa <td>
                    if(num !== '0'){
                        emptyList = false;

                    }

                        aa += `
                            <tr>
                                <td class="text-center">${num}</td>
                                <td class="text-center">${username}</td>
                                <td class="text-center">${bsa}</td>
                                <td class="text-center">${nonbsa}</td>
                                <td class="text-center">${bsaallocateqty}</td>
                                <td class="text-center">${nonbsaallocateqty}</td>
                                <td class="text-center">${total1}</td>
                                <td class="text-center">${total2}</td>
                                <td class="text-center">${total3}</td>
                            </tr>
                        `;
              
                }


                    $('#stockallocate-user-list-table tbody').append(aa)

                   

                let bb = '';
                
                for (let s = 0; s < bwqty.length; s++) {

                    let sa = bwqty[s];

                    // raw values muna
                    var branchwhouse         = sa.branchwhouse;
                    let qtybwqty         = sa.qtybwqty;
                     
                    bb += `
                            <tr>
                                <td class="text-center">${branchwhouse}</td>
                                <td class="text-center">${qtybwqty}</td>
                            </tr>
                        `;

                }

                $('#stockallocate-branchwhouse-list tbody').append(bb)

                // if($('#stockallocate-user-list-table tbody tr').length === 0) {
                if(emptyList) {

                    $('#UserStockAllocateQtyModal').modal('hide')
                    datatable_stock_allocation_list(projdocnum)

                }


           },

            error:function(data){

                    
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });


}

function datatable_generate_allocation_isbn(projdocnum,isbn){

    $.ajax({
           url:"/datatable_generate_allocation_isbn?projdocnum="+projdocnum+"&isbn="+isbn, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

            //   $('#for-approval-finalreq-list-table tbody tr').empty()
          
                 $('#autoallocate-user-list-table tbody tr').empty()
                showLoading()
                
           },
           success:function(data){

                $('#autoallocate-user-list-table tbody tr').empty()
        
                var data = data;
                hideLoading()

                if (!data || data.length === 0) {
                    $('#autoallocate-user-list-table tbody').html('<tr><td colspan="99">No ISBN For Stock Allocation</td></tr>');
                    return;
                }
                
           
                let aa = '';

                for (let i = 0; i < data.length; i++) {

                    let d = data[i];
                    let duserlist = data[i].userlist;

                    let isbn        = d.isbn;
                    let description        = d.description;
                    let proposereq        = d.proposereq;
             
                    aa += `
                              <tr class="isbn-group" 
                                    data-isbn="${isbn}" 
                                    data-proposereq="${proposereq}"
                                    >
                                <td class="bg-bootstrap text-start px-1"colspan="99">  <b>${isbn}</b> &nbsp <i>${description}</i> <span class="mx-5 ">SOH/Approved Req.: <b>${proposereq}</b></span> </td>
                            </tr>
                        `;

                    for (let du = 0; du < duserlist.length; du++) {

                        let pernr = duserlist[du].pernr;
                        let pernrname = duserlist[du].pernrname;
                        let bsaqty = duserlist[du].bsaqty;
                        let nonbsaqty = duserlist[du].nonbsaqty;
                        let bsaallocateqty = duserlist[du].bsaallocateqty;
                        let nonbsaallocateqty = duserlist[du].nonbsaallocateqty;
                        let proposereq = duserlist[du].proposereq;
                        let total_1 = duserlist[du].total_1;
                        let total_2 = duserlist[du].total_2;
                        let total_3 = duserlist[du].total_3;

                        aa += `
                            <tr>
                            
                                <td class="text-center">${pernr} &nbsp ${pernrname}</td>
                                <td class="text-center">${bsaqty}</td>
                                <td class="text-center">${nonbsaqty}</td>
                                <td class="text-center">${bsaallocateqty}</td>
                                <td class="text-center">${nonbsaallocateqty}</td>
                                <td class="text-center">${total_1}</td>
                                <td class="text-center">${total_2}</td>
                                <td class="text-center">${total_3}</td>
                            </tr>
                        `;

                    }
                    
               
                }


                    $('#autoallocate-user-list-table tbody').append(aa)

                    autoAllocatePerIsbn();


           },

            error:function(data){
                    hideLoading();
                    
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });


}

function datatable_stock_allocation_list(projdocnum){
    $.ajax({
           url:"/datatable_stock_allocation_list?basedocnum="+projdocnum, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

            //   $('#for-approval-finalreq-list-table tbody tr').empty()
          
                showLoadingDiv('#stock-allocation-list-table');
                
           },
           success:function(data){
                $('#stock-allocation-list-table tbody tr').empty()
            //    console.log(data);
                var data = data;
          
                hideLoadingDiv('#stock-allocation-list-table');
                let aa = '';

                if(data.num === '0'){
                    aa += '<tr><td colspan="99">No ISBN For Stock Allocation.</td></tr>';

                }

                for (let i = 0; i < data.length; i++) {
                    let d = data[i];

                    // raw values muna
                    let num         = d.num;
                    let isbn        = d.isbn;
                    let description = d.description;
                    let totalproj   = d.totalproj;
                    let countae = d.countae
                    let author = d.author
                    let propreqval = d.propreqval;
                    let action = d.action;
                    let checkbox = d.checkbox;

                    aa += `
                        <tr>
                            <td class="text-center">${num}</td>
                            <td class="text-center">${isbn}</td>
                            <td class="text-start">${description}</td>
                            <td class="text-center">${countae}</td>
                            <td class="text-start">${author}</td>
                          
                            <td class="text-center">${totalproj}</td>
                            <td class="text-center">${checkbox}</td>
                        </tr>
                    `;
                }
                // <td class="text-center">${propreqval}</td>

                    $('#stock-allocation-list-table tbody').append(aa)


           },

            error:function(data){

                    
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });


}




$(document).ready(function(){


    var projdocnum = $('.selected_projection_id').val()



    $(document).on('input','.allocate_qty_input',function (e) {

    let totalAllocation = 0;

        $('.allocate_qty_input').each(function () {
            const val = parseInt($(this).val());
                if (!isNaN(val)) {
                    totalAllocation += val;
                }
        });

        $('.total_allocation').text(totalAllocation)
        $('.total_allocation_val').val(totalAllocation)

    });

  
    $(document).on('submit', '.submit_stock_allocateqty', function(e) {


        e.preventDefault();
        
        var totalAllocationInput = $('.total_allocation_val').val() 
        var totalProjtnInput = $('.stockallocate_totalproj_val').val() ;
        var approveReqInput = $('.stockallocate_approve_req_val').val();

     
        var totalAllocation = parseInt(totalAllocationInput) || 0;
        var totalProjtn = parseInt(totalProjtnInput) || 0;
        var approveReq = parseInt(approveReqInput) || 0;

        
        if(totalAllocation <= 0) {
            sweetalert(" ","Please allocate qty", icon = 'warning', timer = '2000', btn = false);
            return false;
        }

        if(totalAllocation > totalProjtnInput  ) {

            sweetalert(" ","Your total allocated is more than the total projection", icon = 'warning', timer = '2000', btn = false);
            return false;


        }

        if(totalAllocation > approveReqInput  ) {

            sweetalert(" ","Your total allocated is more than the approved req. qty", icon = 'warning', timer = '2000', btn = false);
            return false;


        }

        swal({
                title: "Are you sure you want to proceed with allocating these quantities?",
                text: "",
                icon: "info",
                buttons: true,
                dangerMode: true,
        })
        .then((willCancel) => {


            if (willCancel) {
                
                var allocate_qty_input = $('.allocate_qty_input')
      
                var formData = new FormData(this);

                allocate_qty_input.each( function() { 
                    var allocateqty = parseInt($(this).val());
                    var isbn = $(this).data('isbn');
                    var matnr = $(this).data('matnr');
                    var alloctype = $(this).data('alloctype');
                    var basedocnum = $(this).data('basedocnum');
                    var pernr = $(this).data('pernr');
                    var projqty = $(this).data('projqty');

                    if(allocateqty > 0 ) {

                        formData.append('stock_allocate_qty[]', $(this).val());
                        formData.append('isbn[]', isbn);
                        formData.append('matnr[]', matnr);
                        formData.append('alloctype[]', alloctype);
                        formData.append('basedocnum[]', basedocnum);
                        formData.append('pernr[]', pernr);
                        formData.append('projqty[]', projqty);

                    }
                })

                
                $.ajax({
                        url: "/submit_allocate_qty",
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        headers: {
                                'X-CSRF-TOKEN': getCsrfToken() 
                        },

                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(data) {
                            // console.log(data);
                            hideLoading();

                            let isbn = $('.stockallocate_isbn_val').val()
                            let projdocnum = $('.selected_projection_id').val()

                            if (data.status == "2") {

                                // sweetalert(" ","Success!", icon = 'success', timer = '3000', btn = false);
                                sweetalert("Success!","<a class='btn btn-sm btn-primary' href='/reports/stockallocationsummary?pid="+projdocnum+"''> <i class='fas fa-link'></i> See Stock Allocation Summary </a> ", icon = 'success', timer = '10000', btn = false);


        

                            }
                            else if (data.status == "403") { 
                                sweetalert("Please select ISBN to allocate","", icon = 'warning', timer = '3000', btn = false);
                            
                            }
                            else {
                                swal("Oops...", "Please contact the administrator", "error");
                            }

                            datatable_stockallocate_isbn_user_list(projdocnum,isbn)

                        },
                        error: function(data) {
                    
                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                            hideLoading();

                        }
                        
                    });

            } 
            else {
            
                    
            }

        });


    })


    $(document).on('click','.btn-submit-allocation-all-isbn',function (e) {

        e.preventDefault();

         swal({
                    title: "Are you sure you want to allocate all this qty?",
                    text: "",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {

                    var projperiodtext = $('.selected_projection_id option:checked').text();
                    var projdocnum = $('.selected_projection_id').val();

                    var allocate_qty_input = $('.allocate_qty_input')
      
                    var formData = new FormData();

                    allocate_qty_input.each( function() { 
                        var allocateqty = parseInt($(this).val());
                        var matnr = $(this).data('matnr');
                        var isbn = $(this).data('isbn');
                        var alloctype = $(this).data('alloctype');
                        var basedocnum = $(this).data('basedocnum');
                        var pernr = $(this).data('pernr');
                        var projqty = $(this).data('projqty');

                        if(allocateqty > 0 ) {

                            formData.append('stock_allocate_qty[]', $(this).val());
                            formData.append('isbn[]', isbn);
                            formData.append('matnr[]', matnr);
                            formData.append('alloctype[]', alloctype);
                            formData.append('basedocnum[]', basedocnum);
                            formData.append('pernr[]', pernr);
                            formData.append('projqty[]', projqty);

                        }
                    })

                    
                    $.ajax({
                            url: "/submit_allocate_qty",
                            data: formData,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            headers: {
                                    'X-CSRF-TOKEN': getCsrfToken() 
                            },

                            beforeSend: function() {
                                showLoading();
                            },
                            success: function(data) {
                                // console.log(data);
                                hideLoading();

                                let isbn = $('.stockallocate_isbn_val').val()
                                let projdocnum = $('.selected_projection_id').val()

                                if (data.status == "2") {

                                    sweetalert("Success!","<a class='btn btn-sm btn-primary' href='/reports/stockallocationsummary?pid="+projdocnum+"''> <i class='fas fa-link'></i> See Stock Allocation Summary </a> ", icon = 'success', timer = '10000', btn = false);

                                    $('#AutoAllocateAllISBNModal').modal('hide')
                                    get_projectionperiodstatus(projdocnum)


                                }
                                else if (data.status == "403") { 
                                    sweetalert("Please select ISBN to allocate","", icon = 'warning', timer = '3000', btn = false);
                                
                                }
                                else {
                                    swal("Oops...", "Please contact the administrator", "error");
                                }

                                // datatable_stockallocate_isbn_user_list(projdocnum,isbn)

                            },
                            error: function(data) {
                        
                                swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                hideLoading();

                            }
                            
                        });

       

                } 
                else {
                
                        
                }
            });

    });
    $(document).on('click','.btn-generate-autoallocation',function (e) {

        var projdocnum = $('.selected_projection_id').val();
    
        var stockallocateisbn = $('.stockallocateisbn:checked');

    
        if(stockallocateisbn.length === 0) {

            sweetalert(" ","Please select ISBN to allocate", icon = 'warning', timer = '5000', btn = false);
            return false;
        }


            swal({
                    title: "Are you sure you want to generate allocation?",
                    text: "",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {

                    var projperiodtext = $('.selected_projection_id option:checked').text();

                    var projdocnum = $('.selected_projection_id').val();
                    $('.projectionperiod_text_display').text(projperiodtext)
                    $('#AutoAllocateAllISBNModal').modal('show')

                    var isbnList = [];

                    stockallocateisbn.each(function() {

                        isbnList.push($(this).data('isbn'));

                    })

                    var isbn = isbnList.join(',');

                    datatable_generate_allocation_isbn(projdocnum,isbn)
                    // submit_approve_finalreq (projdocnum,'0')

                } 
                else {
                
                        
                }
            });


    });

    $(document).on('click','.stockallocateisbn_all',function (e) {

   
        var checkallocreqisbn= $('.stockallocateisbn') 


        if($(this).is(':checked')){

            var v = '1';

            checkallocreqisbn.prop('checked',true)

        }
        else {
            var v = '0';
            checkallocreqisbn.prop('checked',false)

        }


    });


    $(document).on('click','.btn_stockallocatedetails',function (e) {
        
        var projdocnum = $('.selected_projection_id').val()

        $('.total_allocation').text(0)
        $('.total_allocation_val').val(0)

        var title = $(this).data('title')
        var isbn = $(this).data('isbn')
        var approvereq = $(this).data('approvereq')
        var doctotalproj = $(this).data('doctotalproj');

        var stockAllocateQtyTitleDisplay = `
            ${isbn} &nbsp ${title} 
        `

        $('.stockallocate_approve_req_text').text(approvereq)
        $('.stockallocate_approve_req_val').val(approvereq)
        $('.stockallocate_totalproj_val').val(doctotalproj)
        $('.stockallocate_isbn_val').val(isbn)
        $('.stockallocateqty_title_text').html(stockAllocateQtyTitleDisplay)

        $('.stockallocate_total_projection').text(doctotalproj);
        datatable_stockallocate_isbn_user_list(projdocnum,isbn)
        datatable_soh_isbn_per_location_list(isbn);


    })

    $(document).on('click','.btn_autoallocate',function (e) {
          

            showLoadingDiv('#stockallocate-user-list-table'), setTimeout( () => hideLoadingDiv('#stockallocate-user-list-table'),1000);
            
            let approveQty  = parseInt($('.stockallocate_approve_req_val').val()) || 0;
            let totalProjBSA = 0;
            let totalProjNonBSA = 0;

            // Step 1: Calculate total projected BSA and Non-BSA
            $(".allocate_qty_bsa").each(function () {
                totalProjBSA += parseInt($(this).data("projqty")) || 0;
            });

            $(".allocate_qty_nonbsa").each(function () {
                totalProjNonBSA += parseInt($(this).data("projqty")) || 0;
            });

            const totalProjQty = totalProjBSA + totalProjNonBSA;

            // RULE #1: Sapat ang approved qty sa lahat
                if (approveQty >= totalProjQty) {
                    $(".allocate_qty_bsa").each(function () {
                        $(this).val($(this).data("projqty"));
                    });
                    $(".allocate_qty_nonbsa").each(function () {
                        $(this).val($(this).data("projqty"));
                    });
                }
                // RULE #2: Sapat sa lahat ng BSA, pero kulang sa total
                else if (approveQty >= totalProjBSA) {
                    // Ibigay lahat ng BSA
                    $(".allocate_qty_bsa").each(function () {
                        $(this).val($(this).data("projqty"));
                    });

                    // Matira -> Non-BSA
                    const remaining = approveQty - totalProjBSA;
                    let totalNonBSAUsed = 0; 

                    $(".allocate_qty_nonbsa").each(function () {
                        const proj = parseInt($(this).data("projqty")) || 0;
                        const share = proj / totalProjNonBSA;
                        const alloc = Math.min(Math.floor(share * remaining), proj);
                        $(this).val(alloc);
                        totalNonBSAUsed += alloc;
                    });

                    // Distribute remaining sobra kung may tira pa
                    let remainNonBSA = remaining - totalNonBSAUsed;
                    $(".allocate_qty_nonbsa").each(function () {
                        if (remainNonBSA <= 0) return;
                        const proj = parseInt($(this).data("projqty")) || 0;
                        const current = parseInt($(this).val()) || 0;
                        if (current < proj) {
                            $(this).val(current + 1);
                            remainNonBSA--;
                        }
                    });
                }
                // RULE #3: Hindi sapat kahit sa BSA → 80/20 split
                else {
                    const allocBSA = Math.min(approveQty * 0.8, totalProjBSA);
                    const allocNonBSA = Math.min(approveQty * 0.2, totalProjNonBSA);
                    let usedBSA = 0, usedNonBSA = 0;

                    $(".allocate_qty_bsa").each(function () {
                        const proj = parseInt($(this).data("projqty")) || 0;
                        const share = proj / totalProjBSA;
                        const alloc = Math.min(Math.floor(share * allocBSA), proj);
                        $(this).val(alloc);
                        usedBSA += alloc;
                    });

                    $(".allocate_qty_nonbsa").each(function () {
                        const proj = parseInt($(this).data("projqty")) || 0;
                        const share = proj / totalProjNonBSA;
                        const alloc = Math.min(Math.floor(share * allocNonBSA), proj);
                        $(this).val(alloc);
                        usedNonBSA += alloc;
                    });

                    // distribute remaining
                    let remainBSA = Math.round(allocBSA - usedBSA);
                    $(".allocate_qty_bsa").each(function () {
                        if (remainBSA <= 0) return;
                        const proj = parseInt($(this).data("projqty")) || 0;
                        const current = parseInt($(this).val()) || 0;
                        if (current < proj) {
                            $(this).val(current + 1);
                            remainBSA--;
                        }
                    });

                    let remainNonBSA = Math.round(allocNonBSA - usedNonBSA);
                    $(".allocate_qty_nonbsa").each(function () {
                        if (remainNonBSA <= 0) return;
                        const proj = parseInt($(this).data("projqty")) || 0;
                        const current = parseInt($(this).val()) || 0;
                        if (current < proj) {
                            $(this).val(current + 1);
                            remainNonBSA--;
                        }
                    });
                }

                // update totals
                const totalAllocated = $(".allocate_qty_bsa, .allocate_qty_nonbsa").toArray()
                    .reduce((sum, el) => sum + (parseInt($(el).val()) || 0), 0);
                $('.total_projection').text(totalProjQty);
                $('.total_allocation').text(totalAllocated);
                $('.total_allocation_val').val(totalAllocated)



            

    })

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

  

   @if(request()->has('pid'))

        var pidurl = "{{ request()->get('pid')}}";
        $('.selected_projection_id').val(pidurl).trigger('change')
        get_projectionperiodstatus(pidurl)
        get_projperiod_details (pidurl) 

   @endif

    $(document).on('change','.selected_projection_id',function (e) {
        
        var v = $(this).val();
        var pernr = "{{session('pernr')}}";
        var username = "{{session('user_staff')}}";
     
        $('.stockalloc_card').removeClass('d-none')

        get_projectionperiodstatus(v)
        get_projperiod_details (v) 

    });


   
//END READY
});





</script>

@endsection