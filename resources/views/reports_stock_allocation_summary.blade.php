@extends('layouts.admin_app')

@section('title') Users @endsection

@section('belowcontent')

  

  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder">Reports - Stock Allocation Summary</h4>    
       </div>
    </div>

 </div>


  @endsection

  
    
@php
    $rank = trim(session('rank'));
    $pernr = request('pernr');
    $q = ProjectionPeriodList('0',trim(session('division')))->get();
@endphp


  <div class="row">
   <div class="col-12 col-md-12 col-xxl-12 text-left">
       

       <div class="card border border-300 p-0" >  
        <div class="card-header pb-1 p-2 border-bottom border-300 bg-soft">

            <div class="row g-3 justify-content-between align-items-end">
              <div class="col-12 col-md-12 col-sm-auto">
                <div class="row">
                    <div class="col-md-5 ">
                
                        <div class="input-group w-100 mb-1">

                           <span class="input-group-text " id="basic-addon1">
                              Projection Period
                           </span>
                           
                           <select class="form-select reportsstockallocationsummary_period" name="reportsstockallocationsummary_period" required aria-label="Default select example">
                                 {{-- <option value="" selected disabled >Select in the list</option> --}}
                                 
                                 

                                 @foreach ($q as $r)
                                       <option value="{{ $r->DOCNUM }}"> {{ projection_period_display($r->DOCNUM) }}</option>
                        
                                 @endforeach
                           </select>
                        
                        </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1">Name</span>
                        <select class="form-control reportsstockallocationsummary_pernr"  required name="reportsstockallocationsummary_pernr" id="reportsstockallocationsummary_pernr" aria-label="Default select example">
                         
                     
                            @if($users->count() > 1 ) 
                            
                                <option value="1" selected >All</option> 
                                
                            @endif    
                            

                          @foreach($users as $user)
                            <option value="{{$user->PERNR}}">{{$user->PERNR ." " . $user->FULLNAME}}</option>
                          @endforeach
                        </select>
                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1">Division</span>
                        <select class="form-control reportsstockallocationsummary_division"  required name="reportsstockallocationsummary_division" id="reportsstockallocationsummary_division" aria-label="Default select example">
                        <option value="1" selected >All</option>
                           @foreach($division as $div)
                              <option value="{{$div->DIVISION}}">{{$div->DIVISION}}</option>
                           @endforeach
                        
                        </select>
                        
                   </div>

                </div>
                <div class="col-md-5">
            
               </div>


            
                </div>
                <div class="row">
                  <div class="text-start col-md-9 col-8 mt-3">
                    <div class="text-start col-md-9 col-8 ">
                        <div class="dropdown font-sans-serif d-inline-block">
     
                           <button class="btn btn-phoenix-secondary dropdown-toggle btn-sm" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fa fa-download me-2"></i> Download
                           </button><span class="caret"> </span>
                           <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                               {{-- <div class="border-0 dropdown-divider"></div>    --}}
                   
                           
                                   <a class="dropdown-item btn-exportexcel-reports-stockallocationsummary text-success-600" href="#"><i class="fa fa-file-excel"></i> Excel</a>
                           </div>
                       </div>
                        
                     </div>
                     
                     
                  </div>
                  
                  <div class="text-end col-md-3 col-4 mt-3">
                      <div>
                          <button type="submit" class="btn btn-danger resetfilterStockAllocationSummaryReport">Reset</button>
                          <button type="submit" class="btn btn-primary w-50 filterStockAllocationSummaryReport">Filter</button>
                      </div>
                  </div>
               </div>
              </div>
              <div class="col-12 col-sm-auto">
                <div class="d-flex align-items-center">
                 
                </div>
              </div>
            </div>
            
        </div>

        <div class="card-body p-1 ">

            <div class="table-responsive ms-n1 ps-1 scrollbar">

                <table id="reports-stockallocationsummary-list" class="fs--1 table table-striped  text-center">
                    <thead class="border border-1">
                        <tr role="row">
                    
                        
                        </tr>
                        <tr>
                            <th scope="col" class="text-center" width="4%">#</th>
                            <th scope="col" class="text-center" width="12%">ISBN</th>
                            <th scope="col" class="text-center" width="20%">Title</th>
                            <th scope="col" class="text-center" width="18%">AE</th>
                          
                            <th scope="col" class="text-center" width="10%">Type</th>
                            
                            <th scope="col" class="text-center" width="8%">Projtn</th>
                            <th scope="col" class="text-center" width="8%">Alloc.</th>
                            <th scope="col" class="text-center" width="26%">Projtn. Breakdown</th>
                            <th scope="col" class="text-center" width="8%">Ordered</th>
                            
                            {{-- <th scope="col" class="text-center" width="8%">Transfer <br> In</th>
                            <th scope="col" class="text-center" width="8%">Transfer <br> Out</th>
                            <th scope="col" class="text-center" width="8%">Adjusted <br>Allocation</th>
                            <th scope="col" class="text-center" width="8%">Allocation <br>Balance</th> --}}
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


<script>


$(document).ready(function(){

    var pernrOneChoice = oneChoices('.reportsstockallocationsummary_pernr')


        $(document).on('click','.resetfilterStockAllocationSummaryReport',function (e) {

            let myChoices = $('.reportsstockallocationsummary_pernr');

            // assuming gusto mong palitan yung unang instance:
            pernrOneChoice[0].setChoiceByValue('1');


        });

        @if(request()->has('pid'))

            var pidurl = "{{ request()->get('pid')}}";
            $('.reportsstockallocationsummary_period').val(pidurl).trigger('change')
            var reportsStockAllocationSummaryListable = $("#reports-stockallocationsummary-list");
            var reportsStockAllocationSummaryListableURL =  "/datatable_reports_stockallocationsummary?basedocnum="+pidurl+"&pernr=1&division=1";
            var reportsStockAllocationSummaryListableColumns = [
                    { "data": "num" },
                    { "data": "isbn" },
                    { "data": "description" },
                    { "data": "pernrname" },
                   
                    { "data": "type" },
                    { "data": "projection" },
                    { "data": "allocation" },
                    { "data": "breakdown" },
                    { "data": "ordered" },
            ];

            dTable(reportsStockAllocationSummaryListable, reportsStockAllocationSummaryListableURL, reportsStockAllocationSummaryListableColumns, 250,"",true,'',false,0,0);


        @endif


        $(document).on('click','.filterStockAllocationSummaryReport',function (e) {

            var basedocnum = $('.reportsstockallocationsummary_period').val();
            var pernr=  $('.reportsstockallocationsummary_pernr').val();
            var division=  $('.reportsstockallocationsummary_division').val();
            

            var reportsStockAllocationSummaryListable = $("#reports-stockallocationsummary-list");
            var reportsStockAllocationSummaryListableURL =  "/datatable_reports_stockallocationsummary?basedocnum="+basedocnum+"&pernr="+pernr+"&division="+division;
            var reportsStockAllocationSummaryListableColumns = [
                    { "data": "num" },
                    { "data": "isbn" },
                    { "data": "description" },
                    { "data": "pernrname" },
                   
                    { "data": "type" },
                    { "data": "projection" },
                    { "data": "allocation" },
                    { "data": "breakdown" },
                    { "data": "ordered" },
            ];

            dTable(reportsStockAllocationSummaryListable, reportsStockAllocationSummaryListableURL, reportsStockAllocationSummaryListableColumns, 250,"",true,'',false,0,0);




        });

        $(document).on('change','.reportsstockallocationsummary_pernr',function (e) {

            $('.reportsstockallocationsummary_division').val('1')

        });
        
        $(document).on('change','.reportsstockallocationsummary_division',function (e) {

            pernrOneChoice[0].setChoiceByValue('1');


        });

        $(document).on('click','.btn-exportexcel-reports-stockallocationsummary',function (e) {

                var projectionperiodtext = $('.reportsstockallocationsummary_period option:selected').text()
                var pernr = $('.reportsstockallocationsummary_pernr option:selected').text();

                ExportExcel('reports-stockallocationsummary-list', 'StockAllocationSummary - '+ projectionperiodtext )

        });

//END READY
});





</script>

@endsection