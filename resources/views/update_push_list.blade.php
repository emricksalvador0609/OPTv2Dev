@extends('layouts.admin_app')

@section('title') Update Push List @endsection

@section('belowcontent')

  

  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder">IMD - Update Push List (Active Items)</h4>
          
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

  
  <div class="code-to-copy">
    <ul class="nav nav-underline" id="myTab" role="tablist">
      <li class="nav-item"><a class="nav-link p-1 active" id="home-tab" data-bs-toggle="tab" href="#updatepushlistnewtab" role="tab" aria-controls="updatepushlistnewtab" aria-selected="true">New</a></li>
      <li class="nav-item"><a class="nav-link p-1" id="profile-tab" data-bs-toggle="tab" href="#updatepushlistexisting" role="tab" aria-controls="updatepushlistexisting" aria-selected="false">Existing</a></li>
      <li class="nav-item"><a class="nav-link p-1" id="transfer-tab" data-bs-toggle="tab" href="#updatepushlisttransfer" role="tab" aria-controls="updatepushlisttransfer" aria-selected="false">Transfer</a></li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="updatepushlistnewtab" role="tabpanel" aria-labelledby="home-tab">
                
                <div class="card h-100">
                    <div class="card shadow-none border border-300">
                    
                    <div class="card-body p-3">

                        <label class="text-1000 fw-bold ">List 
                        
                            <!-- <a class="fw-bold btn btn-sm p-1 btn-primary projection_creation_add_new_books" data-bs-toggle="modal" data-bs-target="#LinkAccountsModal" href="#!">+ Link Accounts</a> -->
                            <a class="fw-bold h6 add-new-op text-primary " href="#AddNewPushListISBNModal" data-bs-toggle="modal" data-bs-target="#AddNewPushListISBNModal">+ Add New</a>  
                        
                
                    </label> 

                        <div class="cardpushlist">
                            <div class="table-responsive ms-n1  scrollbar">
                                <table id="update-push-list-isbn-table" class=" table table-striped  text-center">
                                <thead class="border border-1">
                                    <tr>
                                        <th scope="col" class="" width="5%">#</th>
                                        <th scope="col" class="" width="17%">ISBN</th>
                                        <th scope="col" class="" width="17%">SAP ISBN</th>
                                        <th scope="col" class="" width="45%">Title</th>
                                        <th scope="col" class="" width="5%">Status</th>
                                        <th scope="col" class="" width="15%">Last</br> Update</th>
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
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade " id="updatepushlistexisting" role="tabpanel" aria-labelledby="home-tab">
                
                <div class="card border-300 h-100">
                    <form onsubmit="return false;">
                    <div class="card-header pb-1 p-2 border-bottom border-300 bg-soft">

                        <div class="row g-3 justify-content-between align-items-end">
                          <div class="col-12 col-md-12 col-sm-auto">
                            <div class="row">
                                <div class="col-md-5 ">
                            
                                    <div class="input-group w-100 mb-1">
             
                                       <span class="input-group-text " id="basic-addon1">
                                              Title
                                       </span>
                                       
                                       <input class="form-control text-center form-control-sm w-50 updatepushlist_filter_titlename ui-autocomplete-input" name="" type="text" placeholder="Search Title Name, ISBN, Name, Author...." autocomplete="off">
    
                                    
                                    </div>
                              </div>

                              <div class="col-md-4 ">
                            
                                    <div class="input-group w-100 mb-1">
             
                                       <span class="input-group-text " id="basic-addon1">
                                          Publisher
                                       </span>
                                       
                                       <input class="form-control text-center form-control-sm w-50 updatepushlist_filter_publisher ui-autocomplete-input" name="" type="text" placeholder="Search Publisher Name..." autocomplete="off">
    
                                    
                                    </div>
                              </div>
                              
                             
             
                           
                            </div>
                            <div class="row mt-2">
                              <div class="text-start col-md-9 col-8 ">
                                 
                              </div>
                              
                              <div class="text-end col-md-3 col-4 ">
                                  <div>
                                      {{-- <button type="submit" class="btn btn-warning resetfilteOPTReport">Reset</button> --}}
                                      <button type="submit" class="btn btn-primary w-50 filterUpdatePushListExistingBtn">Filter</button>
                                  </div>
                              </div>
                           </div>
                          </div>
                          <div class="col-12 col-sm-auto">
                            <div class="d-flex align-items-center">
                             
                            </div>
                          </div>
                        </div>
                    </form>
                    </div>
                    <div class="card-body p-3">

                        <label class="text-1000 fw-bold d-none ">List &nbsp
                      
                
                    </label> 

                        <div class="cardpushlist">
                            <div class="table-responsive ms-n1  scrollbar">
                                <table id="update-push-list-existing-isbn-table" class=" table table-striped  text-center">
                                <thead class="border border-1">
                                    <tr>
                                        <th scope="col" class="" width="5%">#</th>
                                        <th scope="col" class="" width="17%">ISBN</th>
                                        <th scope="col" class="" width="45%">Title</th>
                                        <th scope="col" class="" width="5%">Status</th>
                                        <th scope="col" class="" width="15%">Last</br> Update</th>
                                    </tr>
                                </thead>
                                
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade" id="updatepushlisttransfer" role="tabpanel" aria-labelledby="transfer-tab">

                <div class="card border-300 h-100">
                    <div class="card-header pb-1 p-2 border-bottom border-300 bg-soft">
                        <div class="row g-3 justify-content-between align-items-end">
                            <div class="col-12 col-md-6">
                                <div class="input-group w-100 mb-1">
                                    <span class="input-group-text" id="basic-addon1">
                                        Projection Period
                                    </span>
                                    <select class="form-select changeisbn_projection_period" aria-label="Default select example">
                                        <option value="" selected disabled>Select in the list</option>
                                        @foreach (ProjectionPeriodList('0')->get() as $r)
                                            <option value="{{ $r->DOCNUM }}">{{ projection_period_display($r->DOCNUM) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-md-end text-500 fs--1">
                                    Transfer a projected title to a different ISBN when the title is wrong or a new edition replaces it.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">

                        <label class="text-1000 fw-bold">List</label>

                        <div class="cardpushlist">
                            <div class="table-responsive ms-n1  scrollbar">
                                <table id="changeisbn-projection-table" class=" table table-striped  text-center">
                                <thead class="border border-1">
                                    <tr>
                                        <th scope="col" width="4%">#</th>
                                        <th scope="col" width="15%">Temp ISBN</th>
                                        <th scope="col" width="34%">Title</th>
                                        <th scope="col" width="12%">Total</br> Projection</th>
                                        <th scope="col" width="12%">Allocated</th>
                                        <th scope="col" width="12%">Change To</th>
                                        <th scope="col" width="13%">Last</br> Update</th>
                                        <th scope="col" width="8%">Action</th>
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




 <div class="modal" id="AddNewPushListISBNModal"  tabindex="-1" aria-labelledby="AddNewPushListISBNModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="fw-bold reference_actual_expenses_refnum_text"></span> Add New Title</h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
    
    
        <div class="modal-body pt-0 mt-0 ">

                <div class="card-body pt-2 pb-0">
                    <form class="submit_new_pushlist_isbn" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text text-center" id="basic-addon1">
                                     Temp Title Name
                                    </span>
                                    <input class="form-control form-control-sm text-center new_pushlist_isbn_titlename" name="new_pushlist_isbn_titlename" required  type="text" >
                                 </div>
                            </div>
                            <div class="col-md-6">
                               
                            </div>
                           
                              
                              
                          </div>
                   
        
                </div>
            
        </div>
        
        <div class="modal-footer px-0 pb-0">
            <div class="mt-0 text-end "><button type="submit"class="btn btn-primary">Save</button></div>

                  </form>
        </div>
        

    </div>
    </div>
</div>


<div class="offcanvas offcanvas-end content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" id="UpdatePushListISBNOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
    <div class="offcanvas-header pb-0">
       <h5 id="offcanvasLeftLabel">Update SAP ISBN</h5>
       <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
    </div>
    <hr>
    <div class="offcanvas-body pt-0">
  
    <form class="submit_update_pushlist_sap_isbn" method="POST">
        @csrf
        <div class="row">

                           
            <div class="col-md-12 mt-2">
                
                <div class="input-group">
                    <span class="input-group-text w-25" id="basic-addon1">
                        Temp ISBN

                        <input type="text" class="form-control input-sm  d-none text-center un-cl update_pushlist_isbn_id" name="update_pushlist_isbn_id" readonly="readonly">
                        <input type="text" class="form-control input-sm  d-none text-center un-cl update_pushlist_isbn_tempisbn" name="update_pushlist_isbn_tempisbn" readonly="readonly">
                        
                    </span>
                    <span class="input-group-text status un-cl" style="background-color:white !important;" id="basic-addon1">
                        <span class="tempisbndisplay"></span>
                    </span>
            
        
                </div>
                <div class="input-group">
                   <span class="input-group-text w-25 text-center">New ISBN</span>
                   <input type="text" class="form-control input-sm text-center new_pushlist_isbn_newisbn" name="update_pushlist_isbn_newisbn" placeholder="Type New ISBN..." required="">
                 </div>
              </div>
              
            
          </div>
       <div class="mt-0 text-end mt-3  p-3 border-top"><button type="submit" class="btn btn-sm btn-primary">Save</button></div>
    </form>
    </div>
 </div>

 <div class="modal" id="ChangeISBNModal" tabindex="-1" aria-labelledby="ChangeISBNModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"><span class="fw-bold">Change ISBN</span> - Transfer Projection</h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
        </div>

        <div class="modal-body pt-0 mt-0">

                <div class="card-body pt-2 pb-0">
                    <form class="submit_change_isbn_projection_form" method="POST">
                        @csrf
                        <input type="text" class="form-control d-none change_isbn_existing" name="change_isbn_existing" readonly="readonly">
                        <input type="text" class="form-control d-none change_isbn_basedocnum" name="basedocnum" readonly="readonly">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="input-group">
                                    <span class="input-group-text w-25">Existing ISBN</span>
                                    <span class="input-group-text status" style="background-color:white !important;" id="basic-addon1">
                                        <span class="changeisbn_existing_display"></span>
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text w-25 text-center">New ISBN</span>
                                    <input type="text" class="form-control input-sm text-center change_isbn_newisbn" name="change_isbn_newisbn" placeholder="Type New ISBN..." required="">
                                </div>
                            </div>
                        </div>

                        <div class="mt-0 text-end mt-3 p-3 border-top"><button type="submit" class="btn btn-sm btn-primary">Save</button></div>
                    </form>
                </div>

        </div>

    </div>
    </div>
 </div>

{{-- 
<div class="modal" id="UpdatePushListISBNModal"  tabindex="-1" aria-labelledby="UpdatePushListISBNModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="fw-bold"></span> Update SAP ISBN</h5>
    
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
    
    
        <div class="modal-body pt-0 mt-0 ">

                <div class="card-body pt-2 pb-0">
                    <form class="submit_new_pushlist_isbn" method="POST">
                        <div class="row">

                           
                            <div class="col-md-12 mt-2">
                                
                                <div class="input-group">
                                    <span class="input-group-text w-25" id="basic-addon1">
                                        Temp ISBN

                                        <input type="text" class="form-control input-sm d-none text-center un-cl update_pushlist_isbn_id" name="update_pushlist_isbn_id" readonly="readonly">
                                        
                                    </span>
                                    <span class="input-group-text status un-cl" style="background-color:white !important;" id="basic-addon1">
                                        <span class="tempisbndisplay"></span>
                                    </span>
                            
                        
                                </div>
                                <div class="input-group">
                                   <span class="input-group-text w-25 text-center">New ISBN</span>
                                   <input type="text" class="form-control input-sm text-center new_pushlist_isbn_newisbn" name="update_pushlist_isbn_newisbn" placeholder="Type New ISBN..." required="">
                                 </div>
                              </div>
                              
                            
                          </div>
                   
        
                </div>
            
        </div>
        
        <div class="modal-footer px-0 pb-0">
            <div class="mt-0 text-end "><button class="btn btn-primary">Save</button></div>

                  </form>
        </div>
        

    </div>
    </div>
</div> --}}

@endsection

@section('scriptJS')


<script>

function new_pushlist_isbn_newisbn_autocomplete() {

$('.new_pushlist_isbn_newisbn').each(function() {
    let $input = $(this);

    if ($input.data('ui-autocomplete')) {
        $input.autocomplete('destroy'); // ⚠️ Destroy existing para walang duplicate
    }

    $input.autocomplete({
        source: function(request, response) {
            const minChars = 2;
            if (request.term.length < minChars) return response([]);

            // var customercodesearchtitle = $('#add-new-book-title-customercode-display').val();
            // var customercodesearchtitle = $input.data('customercode');
        

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

            // $('.new_pushlist_isbn_newisbn_isbn').val(ui.item.isbn);

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


            $input.val(ui.item.isbn)


            var randomid = generateRandomStringAndInt();

      

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
            '<strong>Title:</strong> ' + item.description + 
            '<br>' +
          
            '<strong>Author:</strong> ' + item.author +
            '<br>' +
            '<strong>ISBN:</strong> ' + item.isbn +  '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <strong>Copyright:</strong> ' + item.copyright 
          
            
            + '</div>';

        return $("<li>").append(html).appendTo(ul);
    };
});
}


new_pushlist_isbn_newisbn_autocomplete()

// itemAutocomplete('.updatepushlist_filter_titlename','.s') 


$(document).ready(function(){

    
    let $inputpublisher = $('.updatepushlist_filter_publisher');

    $( $inputpublisher).autocomplete({
        source: function(request, response) {
            const minChars = 2;
            if (request.term.length < minChars) return response([]);

            $.ajax({
                url: '/submit_find_publisher',
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
                $inputpublisher.addClass("is-invalid");
                return false;
            }

            var publishername = ui.item.publishername;

     
            $inputpublisher.val(publishername)
        

            return false;

        }
    })


        var updatePushListable = $("#update-push-list-isbn-table");
        var updatePushListableURL =  "/datatable_update_push_list_isbn_table?";
        var updatePushListableColumns = [
                { "data": "num" },
                { "data": "tempisbn" },
                { "data": "sapisbn" },
                { "data": "description" },
                { "data": "status" },
                { "data": "dateupdate" },
        ];

        dTable(updatePushListable, updatePushListableURL, updatePushListableColumns, 250,"",true,'',true,0,0);

   
        $(document).on('click','.filterUpdatePushListExistingBtn',function (e) {

            var publisher = $('.updatepushlist_filter_publisher ').val()
            var titlename = $('.updatepushlist_filter_titlename ').val()

            var updatePushExistingListable = $("#update-push-list-existing-isbn-table");
            var updatePushExistingListableURL =  "/datatable_update_push_list_existing_isbn_list?title="+titlename+"&publisher="+publisher;
            var updatePushExistingListableColumns = [
                    { "data": "num" },
                    { "data": "isbn" },
                    { "data": "description" },
                    { "data": "status" },
                    { "data": "dateupdate" },
            ];

            dTable(updatePushExistingListable, updatePushExistingListableURL, updatePushExistingListableColumns, 250,"",true,'',true,0,0);
            

        });
        $(document).on('click','.updateisbn_pushlist_btn',function (e) {
            var id = $(this).data('id')
            var tempisbn = $(this).data('tempisbn')

            $('#UpdatePushListISBNOffCanvas').offcanvas('show')
            $('.tempisbndisplay').text(tempisbn)
            $('.update_pushlist_isbn_id').val(id)
            $('.update_pushlist_isbn_tempisbn').val(tempisbn)
   
        });

        $(document).on('submit','.submit_update_pushlist_sap_isbn',function (e) {
            e.preventDefault();
              var formData = new FormData(this);
  
            
            $.ajax({
                     url:"/submit_update_pushlist_sap_isbn", 
                     data: formData,
                            processData: false,
                            contentType: false,

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
                           
                        //    var data = data[0];
                           
                           if(data.status == '2') {
  
                            DataTableReload('#update-push-list-isbn-table')
                                  $('.submit_new_pushlist_isbn')[0].reset();
                                $('.offcanvas').offcanvas('hide');
                                sweetalert("Success!","", icon = 'success', timer = '3000', btn = false);
                                
                           }
                           else if(data.status == '403') {

                                    sweetalert("New ISBN not in item list","", icon = 'warning', timer = '3000', btn = false);
                                    
                            }
                            else  {

                                    swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                            }
                           
                        },
                           error:function(data){
                                 hideLoading();
                                
                                 swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                        }
              });

        });

        $(document).on('click','.update_status_existing_pushlistisbn',function (e) {

            var v = $(this).val();
            var isbn = $(this).data('isbn');
            var matnr = $(this).data('matnr');

            if($(this).is(':checked')){

                var v = '1';
            }
            else {
                var v = '0';
            }

                $.ajax({
                            url:"/update_status_existing_pushlistisbn", 
                            data: {
                                v : v,
                                isbn : isbn,
                                matnr : matnr,
                            },
                            type:'POST',
                            headers: {
                                    'X-CSRF-TOKEN': getCsrfToken() 
                            },
                            beforeSend: function() {
                            
                            },
                            success:function(data){
                                console.log(data);

                                DataTableReload('#update-push-list-existing-isbn-table')

                                if(data.status == '2') {

                                        
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

        })
        $(document).on('click','.update_status_pushlistisbn',function (e) {
        
            var id = $(this).data('id');

            if($(this).is(':checked')){

                    var v = '1';
                    }
                    else {
                        var v = '0';
                    }

                    $.ajax({
                            url:"/update_status_pushlistisbn", 
                            data: {
                                v : v,
                                id : id,
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

        });

        $(document).on('submit','.submit_new_pushlist_isbn',function (e) {
               
               e.preventDefault();
              var formData = new FormData(this);
  
            
            $.ajax({
                     url:"/submit_new_pushlist_isbn", 
                     data: formData,
                            processData: false,
                            contentType: false,

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
                           
                        //    var data = data[0];
                           
                           if(data.status == '2') {
  
                            DataTableReload('#update-push-list-isbn-table')
                                  $('.submit_new_pushlist_isbn')[0].reset();
                                $('.offcanvas').offcanvas('hide');
                                sweetalert("Success!","", icon = 'success', timer = '3000', btn = false);
                                
                           }

                           else if(data.status == '403') {
  
                                sweetalert("Name already exist","", icon = 'warning', timer = '3000', btn = false);
                                
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

      });

        // ===== Transfer (Change ISBN) tab =====
        function changeIsbnShowModal() {
            var el = document.getElementById('ChangeISBNModal');
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            } else {
                $('#ChangeISBNModal').modal('show');
            }
        }

        function changeIsbnHideModal() {
            var el = document.getElementById('ChangeISBNModal');
            if (window.bootstrap && bootstrap.Modal) {
                var inst = bootstrap.Modal.getInstance(el);
                if (inst) inst.hide();
            } else {
                $('#ChangeISBNModal').modal('hide');
            }
        }

        function changeIsbnProjectionList() {
            var basedocnum = $('.changeisbn_projection_period').val();

            if (!basedocnum) return;

            var changeIsbnListable = $("#changeisbn-projection-table");
            var changeIsbnListableURL = "/datatable_changeisbn_projection_list?basedocnum=" + basedocnum;
            var changeIsbnListableColumns = [
                    { "data": "num" },
                    { "data": "tempisbn" },
                    { "data": "description" },
                    { "data": "totalprojtn" },
                    { "data": "allocated" },
                    { "data": "changeto" },
                    { "data": "dateupdate" },
                    { "data": "action" },
            ];

            dTable(changeIsbnListable, changeIsbnListableURL, changeIsbnListableColumns, 250, "", true, '', true, 0, 0);
        }

        $(document).on('change', '.changeisbn_projection_period', function (e) {
            changeIsbnProjectionList();
        });

        $(document).on('click', '.changeisbn_btn', function (e) {
            var existing = $(this).data('existing');
            var basedocnum = $('.changeisbn_projection_period').val();

            $('.change_isbn_existing').val(existing);
            $('.change_isbn_basedocnum').val(basedocnum);
            $('.changeisbn_existing_display').text(existing);
            $('.change_isbn_newisbn').val('');

            changeIsbnShowModal();
        });

        $(document).on('submit', '.submit_change_isbn_projection_form', function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "/submit_change_isbn_projection",
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                beforeSend: function () {
                    showLoading()
                },
                success: function (data) {
                    console.log(data);
                    hideLoading();

                    if (data.status == '2') {

                        changeIsbnHideModal();
                        DataTableReload('#changeisbn-projection-table');
                        sweetalert("Success!", "", icon = 'success', timer = '3000', btn = false);

                    }
                    else if (data.status == '403') {

                        sweetalert("New ISBN not in item list", "", icon = 'warning', timer = '3000', btn = false);

                    }
                    else {

                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                    }

                },
                error: function (data) {
                    hideLoading();

                    swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                }
            });

        });

//END READY
});





</script>

@endsection