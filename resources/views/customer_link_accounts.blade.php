@extends('layouts.admin_app')

@section('title') Customer Link Accounts @endsection

@section('belowcontent')

  

  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder">Customer - Link Accounts</h4>
          <a class="fw-bold h5 add-new-op text-primary d-none" href="#!" data-bs-toggle="modal" data-bs-target="#AddNewLinkAccountModal">+ Add New</a>     
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
   $q = ProjectionPeriodList('2',session('division'),session('pernr'))->get();
  @endphp 

  <div class="mini-db">
  <div class="row">
      <div class="col-md-8 d-none">
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
  
          </div>
      
     
      </div>
      <div class="col-md-4 d-none text-end">
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


 <div class="card h-100">
    <div class="card shadow-none border border-300">
       <div class="card-header p-0 border-0 border-300 bg-soft">
          <div class="col-md-4" hidden>
             <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Customer</span>
                <select class="form-control form-control-sm filterGroup1" required="" name="customerOpenProjection" id="customerOpenProjection" aria-label="Default select example">
                   <option value="1" selected="">All</option>
                   <option value="n"> ANGELICUM LEARNING CENTRE, INC. - BASIC EDUCATION 0001802148 </option>
                   <option value="n"> CHRISTIAN SAMARITAN HEALTH SERVICES & TECHNICAL SCH. - BASIC EDUCATION - - 0001805266 </option>
                </select>
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
       <div class="card-body p-2">
          {{-- <div class="" style="height:60vh; max-height:350vh;min-width:70vh;overflow-y:auto;overflow-x:hidden;"> --}}
             <div class="table-responsive ms-n1  scrollbar">
                <table id="customer-link-accounts-table" class=" table table-striped  text-center">
                    <thead class="thead-bgcolor text-center" style="position: sticky; top: 0; background-color: white; z-index: 10;">
                        <tr>
                           <th width="5%">#</th>
                           <th width="40%">Customer Name</th>
                           <th width="28%">From</th>
                           <th width="18%">Link To</th>
                           <th width="9%">Action</th>
                        </tr>
                     </thead>
                   
                </table>
             </div>
          {{-- </div> --}}
       </div>
    </div>
 </div>



      

 <div class="modal" id="AddNewLinkAccountModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="fw-bold reference_actual_expenses_refnum_text"></span> Add New Link Account</h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
  
        <div class="modal-body card ">

                <div class="card-body pt-1 pb-0">
                    <form class="addnewlinkaccount" method="POST">
                        <div class="row">

                            <div class="col-md-12 p-0">
                                <div class="input-group">
                                    <span class="input-group-text " id="basic-addon1">
                                       Link From
                                    </span>
                                             
                                    <select class="form-select  addnewlinkaccount_from" aria-label="Default select example">
                                    <option value="" selected disabled>Choose in the list</option>
                                    @foreach($users as $user)
                                       <option value="{{$user->PERNR}}">{{$user->PERNR ." " . $user->FULLNAME}}</option>
                                    @endforeach
                                    </select>
                                       <input class="input-group-text w-25 addnewlinkaccount_from_customercode" title="AE Code..." name="" type="text" placeholder="AE Code...">
                                 
                                       
                                   
                                 </div>
                            </div>

                            <div class="col-md-12 p-0">
                                    <div class="input-group">
                                          <span class="input-group-text " id="basic-addon1">
                                             Link To &nbsp;&nbsp;&nbsp;
                                          </span>
                                                   
                                          <select class="form-select addnewlinkaccount_to" aria-label="Default select example">
                                             <option value="" selected disabled>Choose in the list</option>
                                             @foreach($users as $user)
                                             <option value="{{$user->PERNR}}">{{$user->PERNR ." " . $user->FULLNAME}}</option>
                                             @endforeach
                                          </select>
                                             <input class="input-group-text w-25 addnewlinkaccount_to_customercode" title="AE Code..." name="" type="text" placeholder="AE Code...">
                           
                              
                            
                                     </div>
                

                            </div>
                          
        
                            {{-- <div class="input-group w-75">
                                <span class="input-group-text text-center">Selected Customer</span>
                                <input class="form-control select_customer_code text-center form-control-sm un-cl add_new_book_isbn " type="text" readonly="readonly">
                             </div> --}}
                             
                            <div class="border p-0 pt-2 linkfrom_addnewlinkaccount_from-table" style="height: 55vh; max-height: 100vh; min-width: 70vh; overflow: hidden auto; position: relative;">
                                <table id="add-new-link-customer-from-table" class="table table-striped fs--1 mb-0" width="100%">
                                   <thead class="thead-bgcolor text-center" style="position: sticky; top: 0; background-color: white; z-index: 10;">
                                      <tr>
                                         <th width="5%">#</th>
                                         <th width="20%">Customer Code</th>
                                         <th width="60%">Customer Name</th>
                                         <th width="10%">Select</th>
                                      </tr>
                                   </thead>
                                   <tbody>   
                                      
                 
                                 </tbody>
                             </table>
                         
                                </div>

                            <div class="col-md-6">

                                <div class="col-md-12">
                                    
                                </div>
                                

                               <div class="mt-0 pt-0 text-end mt-3 border-top pt-2 d-none">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>

                          </div>

                        </div>
                       
                    
                   
        
                </div>
            
        </div>
           
        
        <div class="modal-footer px-0 pb-0  d-none">
            <div class="mt-0 text-end "><button type="submit" class="btn btn-sm btn-primary">Save</button></div>

                 
        </div>
    </form>
        

    </div>
    </div>
</div>




    



@endsection

@section('scriptJS')


<script>


$(document).ready(function(){

    
        var customerLinkAccountsListable = $("#customer-link-accounts-table");
        var customerLinkAccountsListableURL =  "/datatable_customer_link_accounts";
        var customerLinkAccountsListableColumns = [
                { "data": "num" },
                { "data": "customername" },
                { "data": "frompernrdisplay" },
                { "data": "topernrdisplay" },
                { "data": "action" },
        ];

        dTable(customerLinkAccountsListable, customerLinkAccountsListableURL, customerLinkAccountsListableColumns, 270,"",true,'',true,0,0);



    $(document).on('click','.btn-linkto',function (e) {

      var linktopernr = $(this).data('linktopernr')
      var linktopernrname = $(this).data('linktopernrname')
      var linktocustomercode = $(this).closest("tr").find('.linktocustomercode').val();
      var linktocustomername  = $(this).closest("tr").find('.linktocustomername').val();
      var linkfrompernr  = $(this).closest("tr").find('.linkfrompernr').val();
      
      swal({
                    title: 'Link to ' + linktopernrname,
                    text: "Are you sure you want to link this customer to this AE?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {

                        $.ajax({
                           url: '/submit_link_customer',
                           data: {
                                 customername: linktocustomername,
                                 customercode: linktocustomercode,
                                 linkfrompernr: linkfrompernr,
                                 linktopernrname: linktopernrname,
                                 linktopernr: linktopernr,
                           },
                              type:'POST',
                              headers: {
                                       'X-CSRF-TOKEN': getCsrfToken() 
                              },
                           beforeSend: function() {

                                 showLoading();
                                 
                           },
                           success: function(data) {

                              hideLoading();

                                 if (data.status === 2) {
                           
                                       sweetalert(" ","Customer Linked Successfully!", icon = 'success', timer = '1500', btn = false);
                                       DataTableReload('#customer-link-accounts-table')

                                 }
                                 else {
                                 
                                    sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
                                 }
                           },
                           error: function(data) {
                                 hideLoading(); 
                                 
                                 sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
                           }
                        });
                       

                } 
                else {
                
                        
                }
        });


    });

    $(document).on('click','.btn-unlink',function (e) {

         var customercode = $(this).data('customer')
         var customername = $(this).data('customername')
         var linktoexistpernr = $(this).closest("tr").find('.linktoexistpernr').val();

         if(linktoexistpernr === ''){
            sweetalert(" ","It's not yet linked to other AE", icon = 'warning', timer = '1500', btn = false);
            return false;
         }

         swal({
                    title: 'Unlink Customer',
                    text: "Are you sure you want to unlink this customer?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {

                        $.ajax({
                           url: '/submit_unlink_customer',
                           data: {
                                 customername: customername,
                                 customercode: customercode
                           },
                              type:'POST',
                              headers: {
                                       'X-CSRF-TOKEN': getCsrfToken() 
                              },
                           beforeSend: function() {

                                 showLoading();
                                 
                           },
                           success: function(data) {

                              hideLoading();

                                 if (data.status === 2) {
                           
                                       sweetalert(" ","Customer Unlinked!", icon = 'success', timer = '1500', btn = false);
                                       DataTableReload('#customer-link-accounts-table')

                                 }
                                 else {
                                 
                                    sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
                                 }
                           },
                           error: function(data) {
                                 hideLoading(); 
                                 
                                 sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
                           }
                        });
                       

                } 
                else {
                
                        
                }
        });

    });

   //  $(document).on('click','.add_new_link_customer',function (e) {
   //       var linktocode = $('.addnewlinkaccount_to_customercode').val()
   //       var linkfromcode = $('.addnewlinkaccount_from_customercode').val()
   //       var customercode = $(this).data('customercode')
   //       var customername = $(this).data('customername')

   //       if(linktocode === '' ){
   //          blinkEmptyValue('.addnewlinkaccount_to')
   //          var html = "" 
   //             + "<span class='text-warning fw-bold'>Please select link to</span>"
   //             + "";

   //          toastifyShow(html) 

   //       }else {

   //          $.ajax({
   //                url: '/submit_add_new_link_customer',
   //                  data: {
   //                      customername: customername,
   //                      customercode: customercode,
   //                      frompernr: linkfromcode,
   //                      topernr: linktocode,
   //                  },
   //                   type:'POST',
   //                   headers: {
   //                            'X-CSRF-TOKEN': getCsrfToken() 
   //                   },
   //                  beforeSend: function() {

   //                      showLoadingDiv('.linkfrom_addnewlinkaccount_from-table');
                        
   //                  },
   //                  success: function(data) {

   //                   hideLoadingDiv('.linkfrom_addnewlinkaccount_from-table');

   //                      if (data.status === 2) {
                   
   //                             sweetalert(" ","Customer linked successfully!", icon = 'success', timer = '1500', btn = false);
   //                             DataTableReload('#customer-link-accounts-table')
   //                             DataTableReload('#add-new-link-customer-from-table')

   //                      } else if (data.status === 401) {
                              
   //                            sweetalert(" ","Customer already linked to this AE code", icon = 'info', timer = '1500', btn = false);

   //                      }
   //                      else {
                          
   //                         sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
   //                      }
   //                  },
   //                  error: function(data) {
   //                      hideLoadingDiv('.linkfrom_addnewlinkaccount_from-table');
                        
   //                      sweetalert("Oops...","Please contact the administrator!", icon = 'error', timer = '1500', btn = false);
   //                  }
   //              });

   //       }

   //  })

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





   
//END READY
});





</script>

<script src="{{ asset_v('assets/js/features/customer_link_accounts.js') }}"></script>

@endsection