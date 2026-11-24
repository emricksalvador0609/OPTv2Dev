@extends('layouts.admin_app')

@section('title') Create Alloc. Req. @endsection

@section('belowcontent')


  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
            <a class="fw-bold d-inline  h5 add-new-op text-primary " href="{{ route('allocation_request')}}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back 
            </a> 
          &nbsp<h4 class="d-inline m-0 fw-bolder">Create Allocation Transfer Request</h4>
          
       </div>
    </div>
    
 </div>


  @endsection

  
  @php 
  $q = ProjectionPeriodList('0',trim(session('division')))->get();
  @endphp 

  <div class="mini-db">


      
    <div class="row g-1 flex-between-end mb-2">
   
        
    </div>
</div>
  

<ul class="nav nav-underline d-none" id="myTab" role="tablist">
    <li class="nav-item"><a class="nav-link p-1 active" id="home-tab" data-bs-toggle="tab" href="#RequestToAEtab" role="tab" aria-controls="RequestToAEtab" aria-selected="true">Request to another AE  </a></li>
    <li class="nav-item d-none"><a class="nav-link p-1" id="profile-tab" data-bs-toggle="tab" href="#ConvertAllocationtab" role="tab" aria-controls="updatepushlistexisting" aria-selected="false">Convert Allocation</a></li>
  </ul>
  <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="RequestToAEtab" role="tabpanel" aria-labelledby="home-tab">
              
            <div class="card h-100">
                <div class="card-body card_customer_list">
                    <form class="submit_create_allocation_request" method="POST">
                      @csrf
                    <div class="row g-3 " >
             
                      <input class="input-group-text savedraft d-none un-cl" title="Customer Code" name=""  value='1' type="text" placeholder="Customer Code...">
                      <input class="input-group-text submitforapproval d-none un-cl " title="Customer Code" value='0'  name=""  type="text" placeholder="Customer Code...">
                      
                        <div class="col-md-12 d-none">
                            <div class="input-group">
                                <span class="input-group-text " id="basic-addon1">
                                   Customer
                                </span>
                                         
                                <select class="form-select  customer_select" aria-label="Default select example">
                                    <option selected="">Search Customer...</option>
                                    <option value="angelicum">ANGELICUM LEARNING CENTRE, INC - BSA</option>
                                    <option value="christian">CHRISTIAN SAMARITAN HEALTH SERVICES & TECHNICAL SCH. - BASIC EDUCATION - -</option>
                                </select>
                                   <input class="input-group-text " title="Customer Code" name=""  type="text" placeholder="Customer Code...">
                               
                    
                             </div>
                        </div>
             
                        
                        
             
                        <div class="col-md-6 mt-0 d-none">
                 
                            <div class="input-group w-75">
                                <span class="input-group-text " id="basic-addon1">
                                   School Opening
                                </span>
                                 
                                <select class="form-select " aria-label="Default select example">
                                    <option selected="">Select in the list</option>
                                    <option value="1">June</option>
                                    <option value="1">August</option>
                                </select>
                    
                             </div>
                             <div class="input-group mt-0 w-75">
                                <span class="input-group-text " id="basic-addon1">
                                   Bookshop Branch
                                </span>
                                 
                                <select class="form-select  bookshop_branch un-cl pm-2" aria-label="Default select example">
                                    <option selected="">Select in the list</option>
                                    <option value="1">CDO</option>
                                    <option value="1">DAVAO</option>
                                </select>
                    
                             </div>
             
                  
                            <!-- <div class="mt-0 text-end mt-6 ">
                                <button class="btn btn-danger">Reset Fields</button>
                           </div> -->
                        </div>
                        <hr class="mb-0 d-none">
             
                        <div class="input-group w-50 mb-1">
             
                            <span class="input-group-text " id="basic-addon1">
                               Projection Period
                            </span>
                            
                            <select class="form-select create_allocation_request_projectionperiod" name="create_allocation_request_projectionperiod" required aria-label="Default select example">
                              
                                  
                                  @foreach ($q as $r)
                                        <option value="{{ $r->DOCNUM }}"> {{ projection_period_display($r->DOCNUM) }}</option>
                         
                                  @endforeach
                                  
                                  <option value="" disabled >Select in the list</option>
                            </select>
                
                      </div>
                    
                      
                        <div class="input-group w-50 mb-1">
                           <span class="input-group-text " id="basic-addon1">
                               Transfer Type
                           </span>
                            
                           <select class="form-select create_allocation_request_transfertype" name="create_allocation_request_transfertype"  required aria-label="Default select example">
                                <option value="" selected disabled >Select in the list</option>
                                <option value="bsa_to_nonbsa" >BSA to Non-BSA</option>
                                <option value="bsa_to_bsa" >BSA to BSA</option>
                                <option value="nonbsa_to_bsa">Non-BSA to BSA</option>
                                <option value="nonbsa_to_nonbsa">Non-BSA to Non-BSA</option>
                           </select>
                
                       </div>
             
                        <div class="col-lg-12 border-bottom mt-0 pt-2">
                            <label class="text-1000 fw-bold">Titles <a class="fw-bold fs--1 create_allocation_request_add_new_books" href="#!">+ Add New</a>
                              
                          
                             </label>   
                            
                         
                               
                            <div style="height:35vh; max-height:100vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;">
                               <table id="create-allocation-request-titles-table" class="table fs--1 mb-0" width="100%">
                                  <thead class="thead-bgcolor text-center" style="position: sticky; top: 0; background-color: white; z-index: 10;">
                                     <tr>
                                        <th width="4%"></th>
                                        <th width="15%">ISBN</th>
                                        <th width="20%">Title</th>
                                        <th width="15%">Requested To</th>
                                        <th width="10%">Type</th>
                                        {{-- <th width="10%">Balance</th> --}}
                                        <th width="10%">Request</th>
                                        <th width="20%">To Branch/Whouse</th>
                                     </tr>
                                  </thead>
                                  <tbody>
                
                                </tbody>
                            </table>
                        </div>
                    </div>
             
                    <div class="col-md-6">
                     <div class="input-group">
                       <span class="input-group-text text-center">Reason For Request</span>
                       <textarea class="form-control form-control-sm form-control form-control-sm-sm create_allocation_request_reason" name="create_allocation_request_reason" required value="Main Projection" id="dRemarks" rows="4" style="resize: none;" required=""></textarea>
                     </div>
                   </div>
             
             
                    <div class="mt-0 text-end mt-2 border-top pt-2  ">
                     
                         <button type="button"  class="btn btn-sm btn-info car_submit_for_approval_btn">Submit - For Approval</button>
                         <button type="button"  class="btn btn-sm btn-primary car_save_as_draft_btn"> Save</button>
                         <button type="submit"  class="btn btn-sm btn-primary car_submitbtn d-none un-cl" readonly="readonly"></button>
                     </div>
                    </form>
                </div>
             
             
             
                </div>
             </div>
             
          </div>
      </div>



<div class="modal" id="AddNewBookTitleModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
   <div class="modal-dialog modal-lg modal-dialog-centered">
   <div class="modal-content bg-100 p-3">
       <div class="modal-header p-0">
       <h5 class="mb-0"> <span class="fw-bold reference_actual_expenses_refnum_text"></span> Request Titles</h5>
       <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
       </div>
   
   
       <div class="modal-body card mt-2 ">

               <div class="card-body pt-1 pb-0">
                   {{-- <form class="createallocationrequestaddnewbooktitleform" method="POST"> --}}
                       <div class="row">
                          
                              <div class="px-0 col-md-10 mb-2">
                                    
                                 <div class="input-group">
                                    <span class="input-group-text text-center">Search Title...</span>
                                    <input class="form-control text-center form-control-sm w-50 create_allocation_request_search_title" name="" type="text" placeholder="Search...">

                                 </div>
                                 
                                 <div class="input-group w-50">
                                    <span class="input-group-text text-center">ISBN</span>
                                    <input class="form-control text-center form-control-sm un-cl create_allocation_request_isbn " type="text" readonly="readonly">
                                 </div>

                           </div>

                           {{-- <div style="height:40vh; max-height:100vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;"> --}}
                                 <table id="create-allocation-request-balance-table" width="100%" class="fs--1 w-100 table table-striped bg-white table-bordered text-center">
                                    <thead class="border border-1">
                                       <tr>
                                          <th colspan="0" scope="col" class="bg-white border text-center"></th>
                                          <th colspan="1" scope="col" class="bg-white border text-center">Balance</th>
                                          <th colspan="2" scope="col" class="bg-white border text-center threquestallocname">Request</th>
                                       </tr>
                                       <tr>
                                          <th scope="col" class="text-center" width="45%">Name</th>
                                          <th scope="col" class="text-center thbalancename" width="15%">-</th>
                                          <th scope="col" class="text-center" width="15%">Qty</th>
                                          <th scope="col" class="text-center" width="25%">To  Branch/Whouse</th>
                                          {{-- <th scope="col" class="text-center" width="15%">Non-BSA</th> --}}
                                       </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                 </table>
                           {{-- </div> --}}

                           <div class="mt-0 pt-0 text-end mt-3 border-top pt-2">
                                 <button type="button" class="btn btn-sm btn-primary createallocatonrequestaddnewtitlesavebtn">Add</button>
                           </div>
                                 
                             <div class="col-md-6 border-bottom requestee_balance">
                              
                             </div>
           
                         
                     </div>
               </div>
           
       </div>
       
       <div class="modal-footer px-0 pb-0 d-none">
           <div class="mt-0 text-end ">
             
            </div>

                 {{-- </form> --}}
       </div>
       

   </div>
   </div>
</div>

    

@endsection

@section('scriptJS')


<script>




function create_allocation_request_search_title_autocomplete() {

$('.create_allocation_request_search_title').each(function() {
    let $input = $(this);

    if ($input.data('ui-autocomplete')) {
        $input.autocomplete('destroy'); // ⚠️ Destroy existing para walang duplicate
    }

    $input.autocomplete({
        source: function(request, response) {
            const minChars = 2;
            if (request.term.length < minChars) return response([]);

            // var customercodesearchtitle = $('#add-new-book-title-customercode-display').val();
            var customercodesearchtitle = $input.data('customercode');

            $.ajax({
                url: '/submit_find_item',
                dataType: 'json',
                data: {
                    search: request.term
                },
                beforeSend: function() {
                    response([{ num: 0, description: "Searching..." }]);
                    
                },
                success: function(data) {
                    if (!data || data.length === 0) {
                        response([{ num: 0, description: "No records found." }]);
                    } else {
                        response(data);
                    }
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {

          
            if (ui.item.num == 0) {
                $input.addClass("is-invalid");
                return false;
            }

            // $('.addnewtitleisbndisp').val(ui.item.isbn);

            // $('.create_projection_search_title_isbn').val(ui.item.isbn);

            var isbn = ui.item.isbn;
            var customercode = ui.item.customercode;
            var title = ui.item.description;
            var titleDisplay = ui.item.descriptionDisplay;
            var budget = 0;
            var totalprev1 = ui.item.total1;
            var totalprev2 = ui.item.total2;
            var totalprev3 = ui.item.total3;
            var projection = 0;
            var population = 0;
            var disc = 0;
            var linetotal = 0;
            var unitprice = ui.item.unitprice;
            var isbnunitpriceclean = ui.item.isbnunitpriceclean;
            var isbnunitpriceDisplay = ui.item.unitpriceDisplay;
            
            var exists = false;


            var transfertypeval = $('.create_allocation_request_transfertype').val();

            $input.val(title)
            $('.create_allocation_request_isbn').val(isbn)
            var projectionperiod = $('.create_allocation_request_projectionperiod').val()

            var customerLinkAccountsListable = $("#create-allocation-request-balance-table");
            var customerLinkAccountsListableURL =  "/datatable_create_allocation_request_balance_table?projdocnum="+projectionperiod+"&isbn="+isbn+"&transfertype="+transfertypeval;
            var customerLinkAccountsListableColumns = [
                     { "data": "reqtopernrname" },
                     { "data": "balance" },
                     { "data": "qtyinput" },
                     { "data": "branchwhouse" },
                    //  { "data": "bsaqtyDisplay" },
                    //  { "data": "nonbsaqtyDisplay" },
                    //  { "data": "bsainput" },
                    //  { "data": "nonbsainput" },
            ];

            dTable(customerLinkAccountsListable, customerLinkAccountsListableURL, customerLinkAccountsListableColumns, 220,"",false,'',false,0,0);
            
     
            // var html = "" 
            //     + "<span class='text-success fw-bold'>Title Added!</span>"
            //     + "";

            // toastifyShow(html)  
            

            return false;

        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        ul.css({
            "max-height": "40vh",
            "width": "100%",
            "overflow-y": "auto"
        });

        if (item.num == 0) {
            return $("<li>").append('<div style="padding:5px;color:#999;">' + item.description + '</div>').appendTo(ul);
        }

        var html = '<div>' +
            '<strong>ISBN:</strong> ' + item.isbn +  '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <strong>Copyright:</strong> ' + item.copyright + 
            '<br>' +
            '<strong>Description:</strong> ' + item.description + 
            '<br>' +
            '<strong>Author:</strong> ' + item.author 
            
            + '</div>';

        return $("<li>").append(html).appendTo(ul);
    };
});
}

$(document).ready(function(){

    
   create_allocation_request_search_title_autocomplete();

        var customerLinkAccountsListable = $("#customer-link-accounts-table");
        var customerLinkAccountsListableURL =  "/datatable_customer_link_accounts";
        var customerLinkAccountsListableColumns = [
                { "data": "num" },
                { "data": "customername" },
                { "data": "frompernr" },
                { "data": "topernr" },
                { "data": "docdate" },
        ];

        dTable(customerLinkAccountsListable, customerLinkAccountsListableURL, customerLinkAccountsListableColumns, 300,"",true,'',true,0,0,1,0,true);


 
   // store previous value bago magbago
$(document).on('focusin', '.create_allocation_request_projectionperiod', function () {
      $(this).data('prev', this.value);
});

$(document).on('click','.create_allocation_request_search_title',function (e) {

    if($(this).val() !== '') {
        var v = $(this).val()
    } else {
        var v = 'projectiontop10';
    }
        $(this).autocomplete("search", v);

})

$(document).on('change', '.create_allocation_request_projectionperiod', function () {
      const $sel = $(this);
      const prev = $sel.data('prev');
      const newVal = $sel.val();

      if (!$('#create-allocation-request-titles-table tbody tr').length > 0) { 
         
         $sel.data('prev', newVal); 
         return; 

      }

         swal({
            title: "Titles selected will be removed",
            text: "Are you sure you want to change the projection period?",
            icon: "info",
            buttons: true,
            dangerMode: true
         }).then((ok) => {
                if (ok) {
                $('#create-allocation-request-titles-table tbody').empty();
                $sel.data('prev', newVal);
                } else {
                $sel.val(prev); // balik sa dati
                // kung Select2: $sel.val(prev).trigger('change.select2');
            }

        });

});

    
    $(document).on('change','.allocationrequestaddnewbooktitlerequestqty',function (e) {
      
      var v = $(this).val()
      var balance = $(this).data('balance')
      var balanceInt = parseInt(balance);
      var vInt = parseInt(v);

      if(vInt > balanceInt){

         var html = "" 
               + "<span class='text-warning fw-bold'>Request is more than balance</span>"
               + "";

            toastifyShow(html) 
            $(this).val(0)

            blinkEmptyValue($(this))
            return false;

      }      

    });

    $(document).on('click','.createallocatonrequestaddnewtitlesavebtn',function (e) {

         e.preventDefault();
         const $tbody = $('#create-allocation-request-titles-table tbody');
         $('.allocationrequestaddnewbooktitlerequestqty').each(function () {

            const $el = $(this);
            const trClosest = $el.closest('tr')
            const branchwhouseSelected = trClosest.find('.allocationrequestaddnewbooktitilebranchwhouse').val();
            const branchwhouseSelectedText= trClosest.find('.allocationrequestaddnewbooktitilebranchwhouse option:selected').text();
            const qty = parseInt($el.val());
            if (qty !== 0) {
               
               const remnr = '<a class="rem-nr">   <span class="text-danger fa-2x fas fa-times-circle"></span></a>';
               const isbn    = $el.data('isbn');
               const title   = $el.data('title');
               const reqTo   = $el.data('reqtopernr'); // PERNR
               const reqname   = $el.data('reqtopernrname'); // PERNRNAME
               const type    = $el.data('type');
               const balance = $el.data('balance') ?? 0;

               const isbnDisplay = $el.data('isbn') +
                     '<input name="create_allocation_request_isbn_input[]" value="' + isbn + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">';

               const titleDisplay = `<span class="line-clamp-1" title="`+ $el.data('title') +`">` + $el.data('title') + `</span>` +
                     '<input name="create_allocation_request_title_input[]" value="' + title + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">';

               const reqToDisplay = $el.data('reqtopernr') +
                     '<input name="create_allocation_request_reqto_input[]" value="' + reqTo + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">';

               const reqNameDisplay = $el.data('reqtopernrname') +
                     '<input name="create_allocation_request_reqtoname_input[]" value="' + reqname + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">' +
                      '<input name="create_allocation_request_reqto_input[]" value="' + reqTo + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">'
                     ;

               const typeDisplay = $el.data('type') +
                     '<input name="create_allocation_request_type_input[]" value="' + type + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">';

               const balanceDisplay = ($el.data('balance') ?? 0) +
                     '<input name="create_allocation_request_balance_input[]" value="' + balance + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">';

               const qtyDisplay = qty +
                     '<input name="create_allocation_request_qty_input[]" value="' + qty + '" readonly="readonly" hidden class="form-control d-none un-cl p-0 fs--2">';

                const branchwhouseDisplay = branchwhouseSelectedText +
                '<input name="create_allocation_request_branchwhouse_input[]" value="' + branchwhouseSelected + '" readonly="readonly" class="form-control d-none un-cl p-0 fs--2">';

               // optional: iwas duplicate (ISBN+PERNR+TYPE)
               const key = `${isbn}|${reqTo}|${type}`;
               if (!$tbody.find(`tr[data-key="${key}"]`).length) {

                    if (!branchwhouseSelected) {
                        // halimbawa: error handling
                        sweetalert("Please select Branch/Warehouse","", icon = 'warning', timer = '1500', btn = false);
                        return false; // or continue; depende sa flow mo
                    }
                    
                     const aa = $('<tr/>', { 'class': 'text-center', 'data-key': key }).append(
                        $('<td/>').html(remnr),                          // 1st col (blank)
                        $('<td/>').html(isbnDisplay),               // ISBN
                        $('<td/>').addClass('text-start').html(titleDisplay), // Title
                        $('<td/>').html(reqNameDisplay),              // Requested To (PERNR)
                        $('<td/>').html(typeDisplay),               // Type
                        // $('<td/>').html(balanceDisplay),            // Balance
                        $('<td/>').html(qtyDisplay),                 // Requested Qty (0)
                        $('<td/>').html(branchwhouseDisplay)                 // Requested Qty (0)
                     );

                     $tbody.append(aa);

                     
                     $('.modal').modal('hide')
                     sweetalert("Book titles added!","", icon = 'success', timer = '1500', btn = false);
                  }
                  else {

                     sweetalert("No new qty for any title declared","", icon = 'warning', timer = '1500', btn = false);


                  }
            }
            else {
               // sweetalert("No qty declared","", icon = 'error', timer = '1500', btn = false);

            }
   
         });

    });


    $(document).on('click','.create_allocation_request_add_new_books',function (e) {

      var projectionperiod = $('.create_allocation_request_projectionperiod');
      var transfertype = $('.create_allocation_request_transfertype');
      var transfertypeval = $('.create_allocation_request_transfertype').val();
      var transfertypeSelectedText = $('.create_allocation_request_transfertype option:selected').text();

     $('.threquestallocname').text('Request: ' + transfertypeSelectedText)  

        if(projectionperiod.val() === null){
    
            blinkEmptyValue('.create_allocation_request_projectionperiod')
            projectionperiod.focus();
            return false;
        }

        if(transfertypeval === null){
    
            blinkEmptyValue('.create_allocation_request_transfertype')
            transfertype.focus();
            return false;
        }

            if(transfertypeval === 'nonbsa_to_nonbsa' || transfertypeval === 'nonbsa_to_bsa' )
            {
                $('.thbalancename').text('Non-BSA')
            }
            if(transfertypeval === 'bsa_to_nonbsa' || transfertypeval === 'bsa_to_bsa' )
            {
                $('.thbalancename').text('BSA')
            }
        
        $('#create-allocation-request-balance-table').DataTable().clear().destroy();
        $('.create_allocation_request_search_title').val('')
        $('.create_allocation_request_isbn').val('')

        $('#AddNewBookTitleModal').modal('show')
        
    })

    $(document).on('click','.add_new_link_customer',function (e) {
         var linktocode = $('.addnewlinkaccount_to_customercode').val()
         var linkfromcode = $('.addnewlinkaccount_from_customercode').val()
         var customercode = $(this).data('customercode')
         var customername = $(this).data('customername')

         if(linktocode === '' ){
            blinkEmptyValue('.addnewlinkaccount_to')
            var html = "" 
               + "<span class='text-warning fw-bold'>Please select link to</span>"
               + "";

            toastifyShow(html) 

         }else {

            $.ajax({
                  url: '/submit_add_new_link_customer',
                    data: {
                        customername: customername,
                        customercode: customercode,
                        frompernr: linkfromcode,
                        topernr: linktocode,
                    },
                     type:'POST',
                     headers: {
                              'X-CSRF-TOKEN': getCsrfToken() 
                     },
                    beforeSend: function() {

                        showLoadingDiv('.linkfrom_addnewlinkaccount_from-table');
                        
                    },
                    success: function(data) {

                     hideLoadingDiv('.linkfrom_addnewlinkaccount_from-table');

                        if (data.status === 2) {
                   
                               sweetalert(" ","Customer linked successfully!", icon = 'success', timer = '1500', btn = false);
                               DataTableReload('#customer-link-accounts-table')
                               DataTableReload('#add-new-link-customer-from-table')

                        } else if (data.status === 401) {
                              
                              sweetalert(" ","Customer already linked to this AE code", icon = 'info', timer = '1500', btn = false);

                        }
                        else {
                          
                           sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
                        }
                    },
                    error: function(data) {
                        hideLoadingDiv('.linkfrom_addnewlinkaccount_from-table');
                        
                        sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
                    }
                });

         }

    })

    $(document).on('click','.create_allocation_request_transfertype',function (e) {

            var alloctitletable = $('#create-allocation-request-titles-table tbody tr');
                
            if(alloctitletable.length > 0) {
                
            } else {

                return false;
            }

            swal({
                    title: "Selected titles will be removed",
                    text: "Are you sure you want to change transfer type?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                    
                    if (willCancel) {
                    //    $(".car_submitbtn").trigger("click");

                        alloctitletable.remove();
                    } 
                    else {
                    
                        
                    }
            });


    })

    $(document).on('change','.addnewlinkaccount_to',function (e) {
      
         var pernr = $(this).val();
         var linktocode = $('.addnewlinkaccount_to_customercode').val()
         var linkfromcode = $('.addnewlinkaccount_from_customercode').val()

         $('.addnewlinkaccount_to_customercode').val('')

         if(pernr === linkfromcode) {
            var html = "" 
               + "<span class='text-danger fw-bold'>Link to and Link from are the same</span>"
               + "";

            toastifyShow(html)  
            $(this).val('')
            return false;
         }
         
         $('.addnewlinkaccount_to_customercode').val(pernr)
         
         // $('.addnewlinkaccount_from_customercode').val('')
         // $('.addnewlinkaccount_from').val('')

    })

    $(document).on('change','.addnewlinkaccount_from',function (e) {

      var pernr = $(this).val();
      var linktocode = $('.addnewlinkaccount_to_customercode').val()
      var linkfromcode = $('.addnewlinkaccount_from_customercode').val()

      $('.addnewlinkaccount_to_customercode').val('')
      $('.addnewlinkaccount_to').val('')
      $('.addnewlinkaccount_from_customercode').val(pernr)

      var addNewLinkCustomerFromListable = $("#add-new-link-customer-from-table");
      var addNewLinkCustomerFromListableURL =  "/get_pernr_customer?pernr="+pernr;
      var addNewLinkCustomerFromListableColumns = [
            { "data": "num" },
            { "data": "customercode" },
            { "data": "customername" },
            { "data": "actionlink" },

      ];

      dTable(addNewLinkCustomerFromListable, addNewLinkCustomerFromListableURL, addNewLinkCustomerFromListableColumns, 250,"",false,'',false,0,0);


   });



   $(document).on('click','.car_save_as_draft_btn', function(e) {

    $('.submitforapproval').val(0);
      swal({
            title: "Are you sure you want to only save this following request to other AE?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
      })
      .then((willCancel) => {

            
            if (willCancel) {
               $(".car_submitbtn").trigger("click");

            } 
            else {
            
                  
            }
      });
      
   });

   $(document).on('click','.car_submit_for_approval_btn', function(e) {

         $('.submitforapproval').val(1);

         swal({
                     title: "Are you sure you want to submit for approval this following request to AE?",
                     text: "",
                     icon: "warning",
                     buttons: true,
                     dangerMode: true,
            })
            .then((willCancel) => {

                  
                  if (willCancel) {
                     $(".car_submitbtn").trigger("click");

                  } 
                  else {
                  
                        
                  }
            });

   });

    $(document).on('submit', '.submit_create_allocation_request', function(e) {
        e.preventDefault();

        var submitforapproval = $('.submitforapproval').val()
 
        var formData = new FormData(this);

        $.ajax({
            url: "/submit_create_allocation_request?forapproval="+submitforapproval,
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            beforeSend: function() {
                showLoading();
            },
            success: function(data) {
                console.log(data);
                hideLoading();

                if (data.status == "2") {

                    if(submitforapproval === '1') {
                         sweetalert("Refreshing Page...","Submitted for approval" +  data.html, icon = 'success', timer = '2000', btn = false);
                    }
                    else {
                        sweetalert("Refreshing Page... ","You may edit the following request with the references: " + data.html, icon = 'success', timer = '2000', btn = false);
                        
                    }

                    
                    setTimeout( function() { 
                        window.location.href = "{{ route('allocation_request') }}";
                    },2000)
                    
                

                }
                else if (data.status == '410') {
                    sweetalert(" ","No projection declared", icon = 'warning', timer = '5000', btn = false);
                }
                 else {
                    swal("Oops...", "Please contact the administrator", "error");
                }

                ajaxInProgress = false;
            },
            error: function(data) {
         
                swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                hideLoading();

                ajaxInProgress = false;
            }
            
        });
    });






   
//END READY
});





</script>

@endsection