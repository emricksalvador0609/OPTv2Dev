@extends('layouts.admin_app')


@section('title') Convert Allocation @endsection

@section('belowcontent')



  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder d-inline">Convert Allocation</h4>
          <a class="fw-bold h5 add-new-op text-primary d-inline " href="#" data-bs-toggle="modal" data-bs-target="#NewConvertAllocModal" >+ Add New</a>     
       </div>
    </div>
    <div class="col-md-2 order-1 d-flex align-items-center d-none">
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
    </div>
 </div>


  @endsection

  
  @php 
 $q = ProjectionPeriodList('0',trim(session('division')))->get();
  @endphp 

  <div class="mini-db">
  <div class="row">
      <div class="col-md-8">
          <div class="input-group">
              <span class="input-group-text" id="basic-addon1">
                  Projection Period
              </span>
              
              <select class="form-select selected_projection_id" aria-label="Default select example">
                
                 
                  @foreach ($q as $r)
                        <option value="{{ $r->DOCNUM }}"> {{ projection_period_display($r->DOCNUM) }}</option>
        
                @endforeach

                <option option="" disabled >Select in the list</option>
              </select>
  
          </div>
      
     
      </div>
      <div class="col-md-4 text-end d-none">
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

      
    <div class="row g-1 flex-between-end mb-2">
   
        
    </div>
</div>
  

 <div class="card h-100">
    <div class="card shadow-none border-0 border-300">
       <div class="card-header p-0 border-0 border-300 bg-soft">
          <div class="col-md-4" hidden>
             <div class="input-group">
                <span class="input-group-text" id="basic-addon1"></span>
            
             </div>
          </div>
          <div class="row">
             <div class="text-start col-md-9 col-8">
             </div>
             <div class="text-end col-md-3 col-4" hidden>
                <div>
                   <button type="button" class="btn btn-primary w-50">Filter</button>
                </div>
             </div>
          </div>
       </div>
       <div class="card-body p-2 border-0 ">
        <table id="convert-alloc-list-table" class="fs--1 table table-striped  ">
            <thead class="border border-1">
             
                <tr>
                    <th scope="col" class="text-center" width="5%">#</th>
                    <th scope="col" class="text-center" width="12%">ISBN</th>
                    <th scope="col" class="text-center" width="23%">Title</th>
                    <th scope="col" class="text-center" width="12%">Convert </br> Type</th>
                    <th scope="col" class="text-center" width="8%">Qty</th>
                    <th scope="col" class="text-center" width="15%">Branch</th>
                    <th scope="col" class="text-center" width="12%">Status</th>
                    <th scope="col" class="text-center" width="8%">History</th>
                    <th scope="col" class="text-center" width="8%">Date Submit</th>
                    <th scope="col" class="text-center" width="5%">Action</th>
                </tr>
            </thead>
        </table>
       </div>
    </div>
 </div>


<div class="modal" id="NewConvertAllocModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="fw-bold reference_actual_expenses_refnum_text"></span> Convert Allocation - New</h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
    
        <div class="modal-body card pt-0 ">
 
        <form class="submit_convertalloc_new" method="POST">
            @csrf
                <div class="card-body p-3">
                  
                        <div class="row">
                           
                            <div class="input-group px-0 w-50 ">
                                <span class="input-group-text " id="basic-addon1">
                                    Convert Type
                                </span>
                                 
                                <select class="form-select convertallocation_type" name="convertallocation_type"  required aria-label="Default select example">
                                     <option value="" selected disabled >Select in the list</option>
                                     <option value="nonbsa">Non-BSA to BSA</option>
                                     <option value="bsa" >BSA to Non-BSA</option>
                                </select>
                     
                            </div>
 
                 
                            <div class="p-0">
                                  <table id="convertalloc-balance-table" width="100%" class="fs--1 mt-2 w-100 table table-striped bg-white table-bordered text-center">
                                     <thead class="border border-1">
                                    
                                        <tr>
                                            <th colspan="5" class="bg-white border text-center">Your Balance</th>
                                        </tr>
                                        <tr>
                                           <th scope="col" class="text-center" width="20%">ISBN</th>
                                           <th scope="col" class="text-center" width="40%">Title</th>
                                           <th scope="col" class="text-center thbalancename" width="15%">Balance</th>
                                           <th scope="col" class="text-center" width="15%"><span class="toconverttype_text"></span> Convert</th>
                                           <th scope="col" class="text-center" width="10%">Branch/Whouse</th>
                                     
                                        </tr>
                                     </thead>
                                  </table>

                            </div>
                            <div class="col-md-8 mt-3 convertalloc_justification_wrapper d-none">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        Justification
                                    </span>

                                    <textarea
                                        class="form-control convertalloc_new_justification"
                                        name="convertalloc_new_justification"
                                        rows="3"
                                        style="resize:none;"
                                        placeholder="Enter justification..."
                                    ></textarea>
                                </div>
                            </div>

                            <div class="mt-0 pt-0 text-end mt-3 border-top pt-2">
                                  <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>

                          
                      </div>
                </div>
            
        </div>
        
        <div class="modal-footer px-0 pb-0 d-none">
            <div class="mt-0 text-end ">
              
             </div>
 
                  </form>
        </div>
        
 
    </div>
    </div>
 </div>

 
    
<!-- //MODAL start here by emrick aug 18 2026 -->
<div class="modal"
     id="ConvertAllocMessageModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-100 p-3">

            <div class="modal-header p-0 pb-2">

                <h5 class="mb-0">
                    Message History -
                    <span class="convert_message_reference"></span>
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input
                    type="hidden"
                    class="convert_message_reference_value">

                <div
                    class="convert_message_history"
                    style="max-height:300px; overflow-y:auto;">
                </div>

                <div class="border-top mt-3 pt-3">

                    <textarea
                        class="form-control convert_message_text"
                        rows="3"
                        placeholder="POST MESSAGE..."
                        style="resize:none;">
                    </textarea>

                    <div class="text-end mt-2">

                        <button
                            type="button"
                            class="btn btn-sm btn-primary btn_convert_post_message">
                            POST MESSAGE
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- //MODAL end here by emrick aug 18 2026 -->

@endsection

@section('scriptJS')




<script>


function datatable_convertalloc_details(docnum){

$.ajax({
       url:"/datatable_convertalloc_details?docnum="+docnum, 
       type:'GET',
       headers: {
               'X-CSRF-TOKEN': getCsrfToken() 
       },
       beforeSend: function() {

        //   $('#for-approval-finalreq-list-table tbody tr').empty()
      
            showLoadingDiv('#convertalloc-details-table');
            
       },
       success:function(data){
            $('#convertalloc-details-table tbody tr').empty()
        //    console.log(data);
            var data = data;
      
            hideLoadingDiv('#convertalloc-details-table');
            let aa = '';
            var total = 0;

            if(data.num === '0'){
                aa += '<tr><td colspan="99">-</td></tr>';

            }

            for (let i = 0; i < data.length; i++) {
                let d = data[i];

                // raw values muna
    

                let num         = d.num;
                let isbn         = d.isbn;
                let description         = d.description;
                let alloctype         = d.alloctype;
                let balance         = d.balance;
                let qty         = d.qty;
                let status         = d.status;
                let action         = d.action;

                // tsaka na lang ilagay sa <td>
                aa += `
                    <tr>
                        <td class="text-center">${num}</td>
                        <td class="text-center">${isbn}</td>
                        <td class="text-center">${description}</td>
                        <td class="text-center">${alloctype}</td>
                        <td class="text-center">${balance}</td>
                        <td class="text-center">${qty}</td>
                        <td class="text-center">${status}</td>
                        <td class="text-center">${action}</td>
                    </tr>
                `;
            }


                $('#convertalloc-details-table tbody').append(aa)

           


       },

        error:function(data){

                
                    sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
        }
    });


}


$(document).ready(function(){

  
    $('.convertalloc_newtitle_title').each(function() {

        let $input = $(this);

        if ($input.data('ui-autocomplete')) {
            $input.autocomplete('destroy'); // ⚠️ Destroy existing para walang duplicate
        }

        $input.autocomplete({
            source: function(request, response) {
                const minChars = 2;
                if (request.term.length < minChars) return response([]);

          
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


                $input.val(title)
                $('.convertalloc_newtitle_isbn').val(isbn)

        
                

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



    $(document).on('change','.convertallocation_type',function (e) {

        var converttypeval = $(this).val();

        if(converttypeval === 'nonbsa'  )
        {
            $('.thbalancename').text('Non-BSA')

            $('.convertalloc_justification_wrapper')
            .addClass('d-none');

            $('.convertalloc_new_justification')
                .prop('required', false);
        }
        if(converttypeval === 'bsa'  )
        {
            $('.thbalancename').text('BSA')

            $('.convertalloc_justification_wrapper')
                .removeClass('d-none');

            $('.convertalloc_new_justification')
                .prop('required', true);
        }

                var basedocnum = $('.selected_projection_id').val()

                var convertAllocBalanceListable = $("#convertalloc-balance-table");
                var convertAllocBalanceListableURL =  "/datatable_convertalloc_balance_table?basedocnum="+basedocnum+"&converttype="+converttypeval;
                var convertAllocBalanceListableColumns = [
                        { "data": "isbn" },
                        { "data": "titlename" },
                        { "data": "balance" },
                        { "data": "qtyinput" },
                        { "data": "selectbranchwhouse" },
                ];

                dTable(convertAllocBalanceListable, convertAllocBalanceListableURL, convertAllocBalanceListableColumns, 210,"",true,'',false,0,0);


    });
    $(document).on('click','.add-new-op',function (e) {

        var converttypeval = $('.convertallocation_type').val()
        var basedocnum = $('.selected_projection_id').val()

        DataTableReload('#convertalloc-balance-table');
     
        if($(`.convertalloc_new_qty[data-basedocnum="${basedocnum}"]`).length > 0 ) {

            return false;

        }
        var convertAllocBalanceListable = $("#convertalloc-balance-table");
        var convertAllocBalanceListableURL =  "/datatable_convertalloc_balance_table?basedocnum="+basedocnum+"&converttype="+converttypeval;
        var convertAllocBalanceListableColumns = [
                { "data": "isbn" },
                { "data": "titlename" },
                { "data": "balance" },
                { "data": "qtyinput" },
                { "data": "selectbranchwhouse" },
        ];

        dTable(convertAllocBalanceListable, convertAllocBalanceListableURL, convertAllocBalanceListableColumns, 210,"No Allocation Balance.",true,'',false,0,0);


    })


    $(document).on('change','.convertalloc_new_qty',function (e) {

        var v = $(this).val();
        var vInt = parseInt(v);
        var trClosest = $(this).closest("tr");

        if(vInt > 0) {

            trClosest.find('.convertalloc_new_branchwhouse').removeClass('d-none')

        } else {

            trClosest.find('.convertalloc_new_branchwhouse').addClass('d-none')

        }
       


    })
    $(document).on('submit','.submit_convertalloc_new',function (e) {
        var convertType =
            $('.convertallocation_type').val();

        var justification =
            $('.convertalloc_new_justification')
                .val()
                .trim();

        if (
            convertType === 'bsa' &&
            justification === ''
        ) {

            blinkEmptyValue(
                '.convertalloc_new_justification'
            );

            sweetalert(
                "Justification Required",
                "Please provide a justification for BSA to Non-BSA conversion.",
                icon = 'warning',
                timer = '3000',
                btn = false
            );

            return false;
        }

        e.preventDefault();

        var noQty = true;

        $('.convertalloc_new_qty').each(function () {
                const v = $(this).val();
                const vInt = parseInt(v);
                if(vInt > 0) {

                    noQty = false;
                }
        })

        if(noQty) {

              sweetalert(" ","No qty to be converted.", icon = 'warning', timer = '2000', btn = false);

              return false;

        }

        var formData = new FormData(this);

            // ajaxInProgress = true;

      

        swal({
            title: "Proceed To Approval?",
            text: "Are you sure you want to request to convert the declared quantities?",
            icon: "info",
            buttons: true,
            dangerMode: true
         }).then((ok) => {

             var basedocnum = $('.selected_projection_id').val();

                if (ok) {
               
                    $.ajax({
                        url: "/submit_convertalloc_new?basedocnum="+basedocnum,
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
                            
                                $('#NewConvertAllocModal').modal('hide')

                                 sweetalert(" ","Success!", icon = 'success', timer = '1000', btn = false);
                                 DataTableReload('#convert-alloc-list-table');
                                 DataTableReload('#convertalloc-balance-table');

                            }
                            else {
                                swal("Oops...", "Please contact the administrator", "error");
                            }

                        },
                        error: function(data) {
                    
                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                            hideLoading();

                        }
                        
                    });

                } else {
              
            }

        });

       

    })

    $(document).on('click','.btn_convertalloc_submitforapproval',function (e) {

        var docnum = $('.convertalloc_docnum').val();

        swal({
                    title: "Submit For Approval",
                    text: "Are you sure you want to submit?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    
                    $.ajax({
                            url:"/submit_convertalloc_forapproval", 
                            data: {
                                docnum : docnum,
                            },
                            type:'POST',
                            headers: {
                                    'X-CSRF-TOKEN': getCsrfToken() 
                            },
                            beforeSend: function() {
                            
                                showLoading()
                            },
                            success:function(data){
                                console.log(data);
                                hideLoading()
                                
                                //    var data = data[0];
                                
                                if(data.status == '2') {
        
                                        //  sweetalert(" ","Status Updated!", icon = 'success', timer = '1000', btn = false);

                                        $('.modal').modal('hide')

                                        DataTableReload('#convert-alloc-list-table')

                                        var html = "" 
                                        + "<span class='text-success fw-bold'>Status Updated!</span>"
                                        + "";

                                        toastifyShow(html)  
                                        
                                }
                                else if(data.status == '403')   {

                                          sweetalert(" ","Please add at least one title to request", icon = 'success', timer = '2000', btn = false);


                                }
                                
                                else{

                                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                }
                                
                                },
                                error:function(data){
                                        hideLoading();
                                        
                                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                }
                    });

                } 
                else {
                
                        
                }
            });

        

    })
    $(document).on('click','.btn_convertalloc_cancel',function (e) {

        var docnum = $('.convertalloc_docnum').val();
        swal({
                    title: "Cancel Request",
                    text: "Are you sure you want to cancel?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    $.ajax({
                            url:"/submit_convertalloc_cancel", 
                            data: {
                                docnum : docnum,
                            },
                            type:'POST',
                            headers: {
                                    'X-CSRF-TOKEN': getCsrfToken() 
                            },
                            beforeSend: function() {
                            
                                showLoading()
                            },
                            success:function(data){
                                console.log(data);
                                hideLoading()
                                
                                
                                //    var data = data[0];
                                
                                if(data.status == '2') {
        
                                        //  sweetalert(" ","Status Updated!", icon = 'success', timer = '1000', btn = false);
                                        
                                        
                                        $('.modal').modal('hide')

                                        DataTableReload('#convert-alloc-list-table')
                                        
                                        var html = "" 
                                        + "<span class='text-success fw-bold'>Status Updated!</span>"
                                        + "";

                                        toastifyShow(html)  
                                }
                                else   {

                                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                }
                                
                                },
                                error:function(data){
                                        hideLoading();
                                        
                                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                }
                    });

                } 
                else {
                
                        
                }
            });



    })


    $(document).on('click','.cancel-btn-convertallocd-isbn',function (e) {
        
        var id = $(this).data('id')

    

    })

    $(document).on('click','.remove-btn-convertallocd-isbn',function (e) {
        var id = $(this).data('id')

        
        swal({
                    title: "Remove ISBN",
                    text: "Are you sure you want to remove?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    $.ajax({
                            url:"/submit_convertalloc_isbn_remove", 
                            data: {
                                id : id,
                            },
                            type:'POST',
                            headers: {
                                    'X-CSRF-TOKEN': getCsrfToken() 
                            },
                            beforeSend: function() {
                            
                                showLoading()
                            },
                            success:function(data){
                                console.log(data);
                                hideLoading()
                                
                                var docnum = $('.convertalloc_docnum').val()
                                
                                //    var data = data[0];
                                
                                if(data.status == '2') {
        
                                     
                                        $('.modal').modal('hide')

                                        datatable_allocrdocnumeq_details(docnum)

                                        
                                        var html = "" 
                                        + "<span class='text-success fw-bold'>Removed!</span>"
                                        + "";

                                        toastifyShow(html)  
                                }
                                else   {

                                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                }
                                
                                },
                                error:function(data){
                                        hideLoading();
                                        
                                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                                }
                    });

                } 
                else {
                
                        
                }
            });
    })

    
    $(document).on('change','.update_convertalloc_qty',function (e) {
        var id = $(this).data('id')
        var balance = parseInt($(this).data('balance'))
        var v = parseInt($(this).val());

        if(v > balance){
            
            sweetalert(" ","Your declared qty is more than the AE's balance", icon = 'warning', timer = '2000', btn = false);
            $(this).val('')
            return false;
        }

        $.ajax({
                url:"/submit_update_convertalloc_qty", 
                data: {
                v : v,
                id : id,
                balance : balance
                },
                type:'POST',
                headers: {
                        'X-CSRF-TOKEN': getCsrfToken() 
                },
                beforeSend: function() {
                
                    showLoading()
                },
                success:function(data){
                    console.log(data);
                    hideLoading();
                    
        
                    
                    if(data.status == '2') {

                        
                            var html = "" 
                            + "<span class='text-success fw-bold'>Qty Updated</span>"
                            + "";

                            toastifyShow(html)  
                    }
                    else   {

                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                    }
                    
                },
                    error:function(data){
                            hideLoading();
                        
                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                }
        });


    })
    

    var basedocnum = $('.selected_projection_id').val()

    var convertAllocListable = $("#convert-alloc-list-table");
    var convertAllocListableURL =  "/datatable_convert_alloc_list_table?basedocnum="+basedocnum;
    var convertAllocListableColumns = [
            { "data": "num" },
            { "data": "isbn" },
            { "data": "description" },
            { "data": "converttypedisplay" },
            { "data": "qty" },
            { "data": "branchwhouse" },
            { "data": "status" },
            { "data": "history" },
            { "data": "datecreate" },
            { "data": "action" },
    ];

    dTable(convertAllocListable, convertAllocListableURL, convertAllocListableColumns, 250,"",true,'',false,0,0);


    $(document).on('change','.selected_projection_id',function (e) {

        var v = $(this).val();

        var convertAllocListable = $("#convert-alloc-list-table");
        var convertAllocListableURL =  "/datatable_convert_alloc_list_table?basedocnum="+v;
        var convertAllocListableColumns = [
                { "data": "num" },
                { "data": "isbn" },
                { "data": "description" },
                { "data": "converttypedisplay" },
                { "data": "qty" },
                { "data": "branchwhouse" },
                { "data": "status" },
                { "data": "history" },
                { "data": "datecreate" },
                { "data": "action" },
        ];

        dTable(convertAllocListable, convertAllocListableURL, convertAllocListableColumns, 250,"",true,'',false,0,0);



    });
    
    $(document).on('change','.update_convertallocd_branchwhouse',function (e) {
        
        var id = $(this).data('id')
        var v = $(this).val();


        $.ajax({
                url:"/submit_update_convertallocd_branchwhouse", 
                data: {
                v : v,
                id : id
                },
                type:'POST',
                headers: {
                        'X-CSRF-TOKEN': getCsrfToken() 
                },
                beforeSend: function() {
                
                    showLoading()
                },
                success:function(data){
                    console.log(data);
                    hideLoading();
                    
        
                    
                    if(data.status == '2') {

                        
                            var html = "" 
                            + "<span class='text-success fw-bold'>Branch/Whouse Updated</span>"
                            + "";

                            toastifyShow(html)  
                    }
                    else   {

                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                    }
                    
                },
                    error:function(data){
                            hideLoading();
                        
                            swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                }
        });


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


    $(document).on(
        'click',
        '.btn_convert_view_messages',
        function(e) {

            e.preventDefault();

            var reference =
                $(this).data('reference');

            $('.convert_message_reference')
                .text(reference);

            $('.convert_message_reference_value')
                .val(reference);

            $('.convert_message_text')
                .val('');

            loadConvertMessageHistory(reference);

            $('#ConvertAllocMessageModal')
                .modal('show');
        }
    );

    function loadConvertMessageHistory(reference) {

        var $history = $('.convert_message_history');

        $history.html(
            '<div class="text-center py-3 text-600">Loading...</div>'
        );

        $.ajax({
            url: '/get_allocreq_justification'
                + '?reference=' + encodeURIComponent(reference)
                + '&reftype=CA',

            type: 'GET',

            success: function(data) {

                console.log('CA HISTORY:', data);

                $history.empty();

                if (
                    data.status != 2 ||
                    !data.data ||
                    data.data.length === 0
                ) {

                    $history.html(
                        '<div class="text-center py-3 text-600">'
                        + 'No messages yet.'
                        + '</div>'
                    );

                    return;
                }

                $.each(data.data, function(index, item) {

                    var username = item.USERNAME || '-';

                    var $box = $('<div/>', {
                        class: 'border-bottom pb-2 mb-3'
                    });

                    var $message = $('<div/>', {
                        class: 'fw-semi-bold text-dark'
                    }).text(
                        item.JUSTIFICATION || ''
                    );

                    var $info = $('<div/>', {
                        class: 'fs--1 text-600 mt-1'
                    }).text(
                        'Posted by '
                        + username
                        + ' - '
                        + (item.created_at || '')
                    );

                    $box.append(
                        $message,
                        $info
                    );

                    $history.append($box);
                });
            },

            error: function(xhr) {

                console.log(
                    'CA HISTORY ERROR:',
                    xhr.responseText
                );

                $history.html(
                    '<div class="text-danger">'
                    + 'Unable to load messages.'
                    + '</div>'
                );
            }
        });
    }

    $(document).on(
        'click',
        '.btn_convert_post_message',
        function(e) {

            e.preventDefault();

            var reference =
                $('.convert_message_reference_value')
                    .val();

            var message =
                $('.convert_message_text')
                    .val()
                    .trim();

            if (!message) {

                sweetalert(
                    " ",
                    "Please enter a message.",
                    icon = 'warning',
                    timer = '2000',
                    btn = false
                );

                return false;
            }

            $.ajax({
                url: '/submit_allocreq_justification_message',

                type: 'POST',

                data: {
                    reference: reference,
                    reftype: 'CA',
                    message: message
                },

                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                },

                beforeSend: function() {

                    $('.btn_convert_post_message')
                        .prop('disabled', true)
                        .text('POSTING...');
                },

                success: function(data) {

                    $('.btn_convert_post_message')
                        .prop('disabled', false)
                        .text('POST MESSAGE');

                    if (data.status == 2) {

                        $('.convert_message_text')
                            .val('');

                        loadConvertMessageHistory(
                            reference
                        );

                        DataTableReload(
                            '#convert-alloc-list-table'
                        );

                    } else {

                        swal(
                            "Oops...",
                            data.message ||
                            "Unable to post message.",
                            "error"
                        );
                    }
                },

                error: function(xhr) {

                    $('.btn_convert_post_message')
                        .prop('disabled', false)
                        .text('POST MESSAGE');

                    console.log(
                        'CA POST ERROR:',
                        xhr.responseText
                    );

                    swal(
                        "Oops...",
                        "Unable to post message.",
                        "error"
                    );
                }
            });
        }
    );
    
//END READY
});





</script>

@endsection