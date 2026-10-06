{{-- CHECK SESSION EXIST --}}
@if (!Session::has("staff"))
    <script>
        window.location.href = "{{ route('login_page') }}";
    </script>
@endif
{{-- CHECK SESSION EXIST --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> OPT v2 - @yield('title')</title>
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <link href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
<link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
{{-- <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> --}}
<link href="{{ asset('assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
<link href="{{ asset('assets/css/uniconsline.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
<link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
<link href="{{ asset('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
<link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
<link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">

<link href="{{ asset('vendors/fullcalendar/main.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendors/leaflet/leaflet.css') }}" rel="stylesheet">
<link href="{{ asset('vendors/leaflet.markercluster/MarkerCluster.css') }}" rel="stylesheet">
<link href="{{ asset('vendors/leaflet.markercluster/MarkerCluster.Default.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/toastify/toastify.css') }}">
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/simple-datatables/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('mazer_assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/choices.js/choices.min.css')}}" />
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/sweetalert2/sweetalert2.min.css') }}">
<link rel="icon" href="{{ asset('mazer_assets/images/cetablogo.png') }}" type="image/x-icon">
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/iconly/bold.css') }}">
<link href="{{ asset('mazer_assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('mazer_assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('mazer_assets/css/buttons.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('mazer_assets/css/rowReorder.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('mazer_assets/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('mazer_assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('mazer_assets/owl-carousel/owl.carousel.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.4.1/css/rowGroup.dataTables.min.css">


<link rel="stylesheet" type="text/css" href="{{ asset('mazer_assets/pages/notification/notification.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('mazer_assets/css/animate.css/css/animate.css') }}">
<link href="{{ asset('mazer_assets/css/materialdesignicons.css') }}" rel="stylesheet">

<link href="{{ asset('assets/css/daterangepicker.css') }}" type="text/css" rel="stylesheet" id="user-style-default">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


<style>
.ui-autocomplete {
    z-index: 99999999 !important;
    max-height:200px;
    overflow-y: auto;
    width:90vh;
}

input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

.swal-overlay {
    z-index: 99999999 !important;

}

.content-offcanvas {
    height: calc(120vh - (var(--phoenix-navbar-top-height) + 8rem)) !important;
    
}
    .content-offcanvas {
    height: calc(120vh - (var(--phoenix-navbar-top-height) + 8rem)) !important;
    
}


.choices__inner {
    font-size:0.8rem !important;    
}

.choices .choices__list--dropdown .choices__item--selectable {
    font-size:0.8rem !important;    
}

.choices__list--dropdown {
  position: absolute;
  z-index: 9999;
  max-height: 300px; /* Adjust if needed */
  overflow-y: auto;
  bottom: auto !important;
  top: 100% !important; /* Forces it to always open downward */
}


table thead tr  {
    --phoenix-bg-opacity: 1 !important;
    background-color: rgba(var(--phoenix-light-rgb), var(--phoenix-bg-opacity)) !important;

}
.choices__inner .choices__item  {
     !important;
}


.choices .choices__list--dropdown .choices__item--selectable {
     !important;    
}

.choices__list--dropdown {
  position: absolute;
  z-index: 9999;
  max-height: 300px; /* Adjust if needed */
  overflow-y: auto;
  bottom: auto !important;
  top: 100% !important; /* Forces it to always open downward */
}


.border-none {
    border-style: none !important;
}
/* Target previous days from the current month */
.fc-day-past:not(.fc-day-other) {
    opacity: 0.75 !important;
}
.fc-daygrid-event {
    cursor: pointer;
}


 .fc .fc-h-event .fc-event-main::after, .fc .fc-daygrid-event .fc-event-main::after {
  display: none;
}

.fc .fc-daygrid-event {
    padding: .3rem .3rem !important;
    font-size: 0.8rem;
}
.fc .fc-h-event .fc-event-main, .fc .fc-daygrid-event .fc-event-main {
    padding-left:0px !important; 
}

.datepicker-dropdown {
            z-index: 9999 !important;
        }

.ui-autocomplete li {
    font-family: "Nunito Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    font-size : 0.8rem;
}

td {
            padding: 0 !important;
            padding-top: 0.2rem !important;
            font-size : 0.8rem !important;
        }
        
td tr td {
    border: 0 !important;
}
 
th {
    font-size : 0.8rem !important;
}

/* td {
    padding: 0 !important;
    border: 0.5 !important;
} */

.un-cl {
    /* position: absolute; */
    background-color: rgba(196, 192, 192, 0.5); /* Gray with 50% opacity */
    pointer-events: none;
    cursor: not-allowed;
    tabindex: -1; /* Prevent focus via Tab key */
}

.un-cl:focus {
    outline: none;
}

.un-cl-nbg {
/* position: absolute; */
   pointer-events: none;
   tabindex: -1; 
    
}

.un-cl-nbg:focus {
    outline: none;
}

a {
    cursor: pointer;
}

.dropdown-menu {
    z-index: 9999999999999999 !important;
}



.loading {
  position: fixed;
  z-index: 9999999999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

.fs-8 {
    font-size: .8rem !important;
}
/* Remove the border-bottom from frozen header cells */
.DTFC_RightHeadWrapper th {
    background-color: #eff2f6;
    border-bottom: none !important; /* Remove the border-bottom */
}


.popover {
    max-height: 300px; /* Set your desired height */
    overflow-y: auto; /* Enable vertical scrolling */
}

table.dataTable tbody tr.dtrg-group.dtrg-start th {

    padding: .25rem !important;

}
table.dataTable tbody tr.dtrg-group.dtrg-start {
    background: white !important;
    font-weight: 600;
}

/* 
.offcanvas  {
    z-index:99999999999 !important;
} */

/* .fc-daygrid-event {
  width: 100%;
  display: inline-block;
  margin-right: 5px;
  padding: 0px !important;
  white-space: normal;
}

.fc-daygrid-day-events {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.fc-daygrid-day-events > * {
  flex: 0 0 50%;
  max-width: 50%;
  padding: 0px !important;
  box-sizing: border-box;
} */

.fc-more-popover {
    background-color: white !important;
    max-height: 200px; /* Set a maximum height for the popover */
    overflow-y: auto; /* Enable vertical scrolling */
}
/* Additional specificity to override any conflicting styles */
.DTFC_RightHeadWrapper table thead th {
    border-bottom: none !important;
}

.DTFC_RightBodyLiner {
    overflow-x: hidden !important;
}

table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc {
  background-image: none !important;
}

/* Remove extra right padding that icons use */
table.dataTable thead th {
  padding-right: 8px !important; /* adjust to taste */
  padding-left: 8px !important; /* adjust to taste */
}

/* Optional: change cursor to indicate still sortable */
table.dataTable thead th.sorting,
table.dataTable thead th.sorting_asc,
table.dataTable thead th.sorting_desc {
  cursor: pointer !important;
}


/* Remove the border-bottom from frozen header cells */
.DTFC_LeftHeadWrapper th {
    background-color: #fff;
    border-bottom: none !important; /* Remove the border-bottom */
}

/* Additional specificity to override any conflicting styles */
.DTFC_LeftHeadWrapper table thead th {
    border-bottom: none !important;
}

.DTFC_Cloned {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
}
.DTFC_LeftBodyLiner {
    overflow-x: hidden !important;
}

.DTFC_LeftBodyLiner td {
    margin-top: 100px !important;
}

table.dataTable {
    border-collapse: collapse;
    border-spacing: 0;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

.input-small-3 {
  font-size: 0.75rem;
}

.input-small-4 {
  font-size: 0.60rem;
}


.input-small-5 {
  font-size: 0.45rem;
}

table.dataTable th,
table.dataTable td {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Set border color with 10% opacity */
}

.choices--wide {
    width: 100%; /* Ensure it matches the container's width */
    max-width: 100%; /* Avoid over-expansion */
}

/* Additional specificity for table headers */
table.dataTable thead th {
    border: 1px solid rgba(0, 0, 0, 0.1); /* Set border color with 10% opacity */
}

.loading-overlay {
       position: absolute;
       top: 0;
       left: 0;
       right: 0;
       bottom: 0;
       background: rgba(244, 244, 244, 0.5); /* semi-transparent background */
       display: flex;
       align-items: center;
       justify-content: center;
       z-index: 9999; /* High z-index to overlay the canvas content */
     }

/* Transparent Overlay */

/* Transparent Overlay */
.loading {
  position: fixed;
  z-index: 999999;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(244, 244, 244, 0.5); 
  display: flex;
  justify-content: center;
  align-items: center;
}



.spinner {
  position: fixed;
  z-index: 9999999;
  height: 20.2px;
  width: 20.2px;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

.bg-bootstrap {
    background-color: #eeeff0 !important;
}
.spinner div {
   animation: spinner-4t3wzl 1.875s infinite backwards;
   background-color: #ed1d26;
   border-radius: 50%;
   height: 100%;
   position: absolute;
   width: 100%;
}

.spinner div:nth-child(1) {
   animation-delay: 0.15s;
   background-color: rgba(1,108,48,0.9);
}

.spinner div:nth-child(2) {
   animation-delay: 0.3s;
   background-color: rgba(1,108,48,0.8);
}

.spinner div:nth-child(3) {
   animation-delay: 0.45s;
   background-color: rgba(1,108,48,0.7);
}

.spinner div:nth-child(4) {
   animation-delay: 0.6s;
   background-color: rgba(1,108,48,0.6);
}

.spinner div:nth-child(5) {
   animation-delay: 0.75s;
   background-color: rgba(1,108,48,0.5);
}

.dataTables_wrapper .paginate_button:hover {
    background-color: transparent !important;
    color: inherit !important;
}
@keyframes spinner-4t3wzl {
   0% {
      transform: rotate(0deg) translateY(-200%);
   }

   60%, 100% {
      transform: rotate(360deg) translateY(-200%);
   }
}

.ui-menu-item-wrapper {
  width:800px !important;
  min-width:700px !important;
  flex: 1 1 auto !important;
}

@keyframes blinkAnimation {
      0% { opacity: 0; }
      50% { opacity: 1; }
      100% { opacity: 0; }
  }
  
.blink-text {
      animation: blinkAnimation 1.5s infinite !important;
  } 



</style>
</head>

<body>

@php 

    $segment = request()->segments();
    $staff = session('staff');
    $staff_username = session('user_staff');
    $rank = trim(session('rank'));

    $salesteamrank = [
                    'AE',
                    'RSM',
                    'SSM'
              ];


@endphp 

{{-- MENU --}}

<nav class="navbar navbar-vertical navbar-expand-lg">
    <script>
      var navbarStyle = window.config.config.phoenixNavbarStyle;
      if (navbarStyle && navbarStyle !== 'transparent') {
        document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
      }
    </script>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
      <!-- scrollbar removed-->
      <div class="navbar-vertical-content">
        <ul class="navbar-nav flex-column" id="navbarVerticalNav">
            <li class="nav-item">
               <!-- parent pages-->
               <div class="nav-item-wrapper">
                  <a class="nav-link  @if( count($segment) > 0 &&  $segment[0] == 'dashboard')  {{ 'active' }} @endif  label-1" href="{{ route('dashboard_admin')}}"
                     role="button" data-bs-toggle="" aria-expanded="false">
                     <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                        data-feather="home"></span></span><span
                        class="nav-link-text-wrapper"><span
                        class="nav-link-text">Dashboard</span></span>
                     </div>
                  </a>
               </div>
            </li>
            @if(rankView('AE','RSM','SSM','AVP','CC','CRM'))
                <li class="nav-item">
                <!-- label-->
            
                <p class="navbar-vertical-label mt-3 mb-1">Modules
                
                    @if(rankView('AE'))
                <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'createprojection')  {{ 'active' }} @endif label-1" 
                    href="{{ route('create_projection')}}" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="life-buoy"></span></span><span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Create Projection</span>
                    </span>
                    </div>
                    </a>
                </div>
                
                <div class="nav-item-wrapper d-none"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'viewprojectionprogress')  {{ 'active' }} @endif label-1" 
                    href="{{ route('view_projection_progress')}}" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="table"></span></span><span class="nav-link-text-wrapper">
                        <span class="nav-link-text">View Projection  Progress</span>
                    </span>
                    </div>
                    </a>
                </div>
                

                <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  ( $segment[1] == 'allocationrequest' || $segment[1] == 'allocationrequestcreation' ) )  {{ 'active' }} @endif label-1" 
                        href="{{ route('allocation_request')}}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="figma"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Allocation Transfer </br> Request</span>
                        </span>
                        </div>
                    </a>
                </div>
                <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  ( $segment[1] == 'convertallocation'  ) )  {{ 'active' }} @endif label-1" 
                        href="{{ route('convert_allocation')}}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="crop"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Convert Allocation</span>
                        </span>
                        </div>
                    </a>
                </div>
                    @endif
                    @if(rankView('AE','RSM','SSM','AVP','CC','CRM'))
                <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'approvals')  {{ 'active' }} @endif label-1" 
                    href="{{ route('approvals') }}" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Approvals <span class="text-warning">(<span class="approvalscount_display"></span>)</span> </span>
                    </span>
                    </div>
                    </a>
                </div>
                @endif
                
                @if(rankView('RSM'))
                
                <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'customerlinkaccounts')  {{ 'active' }} @endif label-1" 
                    href="{{ route('customer_link_accounts') }}" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="git-merge"></span></span><span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Customer - Link Accounts</span>
                    </span>
                    </div>
                    </a>
                </div>
                @endif
                @if(rankView('CC'))
                <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'updatecustomertemp')  {{ 'active' }} @endif label-1" 
                     href="{{ route('update_customer_temp') }}" role="button" data-bs-toggle="" aria-expanded="false">
                     <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="book"></span></span><span class="nav-link-text-wrapper">
                         <span class="nav-link-text">Update Customer Temp <span class="text-warning">(<span class="customertempcount_display"></span>)</span></span>
                     </span>
                     </div>
                 </a>
                 </div>
                 @endif
            </li>
           @endif	
           @if(rankView('IMD','CRM','CRM','PEAM'))
           <li class="nav-item">
               <!-- label-->
               <p class="navbar-vertical-label mt-3 mb-1">IMD
               
               <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'openprojection')  {{ 'active' }} @endif label-1" 
                   href="{{ route('open_projection') }}" role="button" data-bs-toggle="" aria-expanded="false">
                   <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="book-open"></span></span><span class="nav-link-text-wrapper">
                       <span class="nav-link-text">Open Projection</span>
                   </span>
                   </div>
                 </a>
               </div>
               @endif

               @if(rankView('IMD','CRM','CNC'))
               <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'update_push_list')  {{ 'active' }} @endif label-1" 
                   href="{{ route('update_push_list') }}" role="button" data-bs-toggle="" aria-expanded="false">
                   <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="stop-circle"></span></span><span class="nav-link-text-wrapper">
                       <span class="nav-link-text">Update Push List </br> (Active Items)</span>
                   </span>
                   </div>
                 </a>
               </div>

               @endif

               @if(rankView('IMD','CRM','PEAM'))
               
               <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'finalizereqadjustment')  {{ 'active' }} @endif label-1" 
                   href="{{ route('finalize_req_adjustment') }}" role="button" data-bs-toggle="" aria-expanded="false">
                   <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="key"></span></span><span class="nav-link-text-wrapper">
                       <span class="nav-link-text">Finalize Requirements </br> Adjustment</span>
                   </span>
                   </div>
                 </a>
               </div>
               <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'stockallocation')  {{ 'active' }} @endif label-1" 
                   href="{{ route('stock_allocation') }}" role="button" data-bs-toggle="" aria-expanded="false">
                   <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="list"></span></span><span class="nav-link-text-wrapper">
                       <span class="nav-link-text">Stock Allocation</span>
                   </span>
                   </div>
                 </a>
               </div>
               
           </li>
           @endif

        
           


           {{-- <li class="nav-item">
               <!-- label-->
               <p class="navbar-vertical-label mt-3 mb-1">CC
               
               <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'updatecustomertemp')  {{ 'active' }} @endif label-1" 
                   href="{{ route('update_customer_temp') }}" role="button" data-bs-toggle="" aria-expanded="false">
                   <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="book"></span></span><span class="nav-link-text-wrapper">
                       <span class="nav-link-text">Update Customer Temp <span class="text-warning">(<span class="customertempcount_display"></span>)</span></span>
                   </span>
                   </div>
                 </a>
               </div> --}}

           

               


           <li class="nav-item">
               <!-- label-->
               <p class="navbar-vertical-label mt-3 mb-1">Reports
               
                @if(rankView('RSM','SSM','AE','AVP','CC'))

                    <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'projectionprogress')  {{ 'active' }} @endif label-1" 
                        href="{{ route('reports_projection_progress') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="flag"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Projection Progress </br></span>
                        </span>
                        </div>
                    </a>
                    </div>

                    <div class="nav-item-wrapper d-none "><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'allocationrequestprogress')  {{ 'active' }} @endif label-1" 
                        href="{{ route('reports_allocation_request_progress') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="flag"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Allocation Transfer </br> Request  Progress</span>
                        </span>
                        </div>
                      </a>
                    </div>

                @endif


                @if(rankView('CRM','IMD','AVP'))

                    <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'projectionapprovalstatus')  {{ 'active' }} @endif label-1" 
                        href="{{ route('reports_projection_approval_status') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="flag"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Projection Approval </br> Status</span>
                        </span>
                        </div>
                        </a>
                    </div>


                @endif

                    
                @if(rankView('IMD','CRM'))

                <div class="nav-item-wrapper d-none"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'projectionbreakdown')  {{ 'active' }} @endif label-1" 
                    href="{{ route('reports_projection_breakdown') }}" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="flag"></span></span><span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Book Titles </br> Projection Breakdown</span>
                    </span>
                    </div>
                </a>
                </div>
                
                    <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'stockallocationsummary')  {{ 'active' }} @endif label-1" 
                        href="{{ route('reports_stock_allocation_summary') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="flag"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Stock Allocation </br> Summary</span>
                        </span>
                        </div>
                    </a>
                    </div>
                    <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'alloctransferconvertsummary')  {{ 'active' }} @endif label-1" 
                        href="{{ route('reports_alloctransfer_convert_summary') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="flag"></span></span><span class="nav-link-text-wrapper">
                            <span class="nav-link-text">Alloc. Transfer & Convert </br> Summary</span>
                        </span>
                        </div>
                    </a>
                    </div>
           

                


              @endif

          


             
               
              
               
           </li>
           @if(userAccess($staff,'admin',false,true) == 1 && session('rank') == 'ADMIN' )  
           <li class="nav-item">
             <!-- label-->
             <p class="navbar-vertical-label">Admin
             </p>
             <hr class="navbar-vertical-line" />
             <!-- parent pages-->
 
             @if(userAccess($staff,'usersadmin',false,false) == 1 )
             <div class="nav-item-wrapper"><a class="nav-link @if( count($segment) > 1 &&  $segment[1] == 'users')  {{ 'active' }} @endif label-1" href="{{ route('admin_users')}}" role="button" data-bs-toggle="" aria-expanded="false">
                 <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Users</span></span>
                 </div>
               </a>
             </div>
             @endif
 
     
 
             
           </li>
           @endif

           
         </ul>
      </div>
    </div>
    <div class="navbar-vertical-footer">
      <button class="btn navbar-vertical-toggle border-0 fw-semi-bold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-0"></span><span class="uil uil-arrow-from-right fs-0"></span><span class="navbar-vertical-footer-text ms-2">Collapsed</span></button>
    </div>
  </nav>
 
  <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
    <div class="collapse navbar-collapse justify-content-between">
       <div class="navbar-logo">
          <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
          <a class="navbar-brand me-1 me-sm-3" href="{{ route("dashboard_admin")}}">
          <div class="d-flex align-items-center">
             <div class="d-flex align-items-center">
                <img src="{{ asset("assets/img/C&EALS_Horizontal.jpg") }} " alt="phoenix" />
                 <span class='px-4 h5 text-700 fw-semi-bold'> OPT v2.0 </span>
             </div>
          </div>
          </a>
       </div>
       {{-- @php 
       $uniqueDeviceIdentifier = uniqid('device_', true);
       @endphp
       {{ $uniqueDeviceIdentifier}} --}}
       
       <ul class="navbar-nav navbar-nav-icons flex-row">
            
      
    
          <li class="nav-item">
             <div class="nav-link mx-2">
                {{ formatDate(date_now(),'date') }}  </br> 
                {{-- <span id="clock"></span> --}}
             </div>
          </li>
          <li class="nav-item dropdown">
             <a class="nav-link lh-1 pe-0" id="navbarDropdownUser"  role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-l ">
                   <img class="rounded-circle " src="{{ asset("mazer_assets/images/avatar.png") }}" alt="" />
                </div>
             </a>
             <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
                <div class="card position-relative border-0">
                   <div class="card-body p-0">
                      <div class="text-center pt-4 pb-3">
                         <div class="avatar avatar-xl ">
                            <img class="rounded-circle " src="{{ asset("mazer_assets/images/avatar.png")}}" alt="" />
                         </div>
                         <h6 class="mt-2 text-black">{{ userNameDetails(session('user_staff'))->FULLNAME }}</h6>
                         <h6 class="mt-2 opacity-75 text-black">{{ session('user_staff') }}</h6>
                         <h6 class="opacity-50">{{ session('pernr') }}</h6>
                      </div>
                   </div>
                   {{-- 
                   <div class="overflow-auto scrollbar" style="height: 10rem;">
                      <ul class="nav d-flex flex-column mb-2 pb-1">
                         <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="user"></span><span>Profile</span></a></li>
                         <li class="nav-item"><a class="nav-link px-3" href="#!"><span class="me-2 text-900" data-feather="pie-chart"></span>Dashboard</a></li>
                         <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="lock"></span>Posts &amp; Activity</a></li>
                         <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="settings"></span>Settings &amp; Privacy </a></li>
                         <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="help-circle"></span>Help Center</a></li>
                         <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="globe"></span>Language</a></li>
                      </ul>
                   </div>
                   --}}
                   <div class="card-footer border border-0 p-0">
                      {{-- 
                      <ul class="nav d-flex flex-column my-3">
                         <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="user-plus"></span>Add another account</a></li>
                      </ul>
                      --}}
                      <div class="px-3 mb-2"><a class="btn btn-phoenix-secondary w-100" href="{{ route('password.edit') }}">Change password</a></div>
                      <div class="px-3"> <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="{{ route('logoff_admin') }}"> 
                         <span class="me-2" data-feather="log-out"> </span>Sign out</a>
                      </div>
                      <div class="my-2 text-center fw-bold  text-600">
                         <a class="text-600 pe-none me-1" >Privacy policy</a>&bull;
                         <a class="text-600 pe-none mx-1" >Terms</a>&bull;
                         <a class="text-600 pe-none ms-1" >Cookies</a>
                      </div>
                   </div>
                </div>
             </div>
          </li>
       </ul>
    </div>
 </nav>
 





<div class="content">
    <div class="row">
        <div class="col-12 col-lg-12 ">
            <h4 class="mb-2 text-1100 fw-bold fs-md-2"><span class="calendar-day d-block d-md-inline mb-1">
                </span><span class="h3 d-none d-md-inline"> @yield('menutitle') </span>

            </h4>
   
        </div>   
        {{-- <div class="col-12 col-lg-4 text-end">
            <h5 class="text-700 fw-semi-bold">OPT</h5>
        </div> --}}
    </div>
    <div class="col-2 d-none">
        <div class=" dropdown">
            <a class=" px-3 icon-indicator itinerary-notification-btn icon-indicator" title="Notification"  href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                <span class="text-700" data-feather="bell" style="height:20px;width:20px;"></span>
                <span class="icon-indicator-number"><span class=""></span> <div class="itinerary-notification-total-display"></div></span>
            </a>

        
                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                    <div class="card position-relative border-0">
                    <div class="card-header p-2">
                        <div class="d-flex justify-content-between">
                        <h5 class="text-black mb-0">Kind</h5>
                        
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="scrollbar-overlay" style="height: 27rem;">
                        
                            <div class="itinerary-notification-display"></div>
                        </div>
                    </div>
                    <div class="card-footer p-0 border-top border-0">
                        <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="#">Notification history</a></div>
                    </div>
                    </div>
                </div>

            </div>
      
     </div>
    
     
    @yield('belowcontent')



 

    <footer class="footer position-absolute">
        <div class="row g-0 justify-content-between align-items-center h-100">
          <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 mt-2 mt-sm-0 text-900">OPT<span class="d-none d-sm-inline-block"></span>
                <span class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none">2025 ©<a class="mx-1" href="https://cepublishing.com/">C&amp;E Publishing, Inc.</a></p>
          </div>
          <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-600">v2.0</p>
          </div>
        </div>

  
      </footer>
      
</div>

<div class="offcanvas offcanvas-start content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" id="CustomerSHDetailedHistoryOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
    <div class="offcanvas-header pb-0">
       <h5 id="offcanvasLeftLabel">Customer Sales History (Detailed)</h5>
       <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
      
    </div>
    <hr>
    <div class="offcanvas-body pt-0">
     
        <h5 class="fw-bold text-900 detailedsh_customername"> - </h5>
        <h5 class="fw-bold text-600 detailedsh_customercode">- </h5>
        {{-- <h5 class="fw-bold text-700"> Total:</h5> --}}
        <hr>
        <div class="row detailedsh_card_body">
           
             <div class="col-md-12">
                <!-- <div class="card">
                   <div class="card-body"> -->
                    <table class="fs--1 table table-striped  border bg-white table-bordered text-center" id="customer-sh-detailed-list-table">
                        <thead class="border border-1">
                           <tr>
                              <th colspan="3" scope="col" class="bg-white border text-center"><span class="detailedsh_year">-</span> Sales History</th>
                           </tr>
                           <tr>
                              <th scope="col" width="70%" class="text-center">Month</th>
                              <th scope="col" width="30%" class="text-center">Qty</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col" class="text-center">Total</th>
                                <th scope="col" class="text-center"> <span class="detailedsh_total"> </span> </th>
                            </tr>
                        </tfoot>
                      
                     </table>

                   <!-- </div>
               </div> -->
               
          
              
           </div>

        </div>
        
    </div>
 </div>

 
<div class="offcanvas offcanvas-start content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" id="AEProjectionsTitleOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
    <div class="offcanvas-header pb-0">
       <h5 id="offcanvasLeftLabel">AE Projections</h5>
       <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button>
      
    </div>
    <hr>
    <div class="offcanvas-body pt-0">
     
        <h5 class="fw-bold text-900 aeprojectiontitle_title"> - </h5>
        <h5 class="fw-bold text-600 aeprojectiontitle_isbn">- </h5>
        {{-- <h5 class="fw-bold text-700"> Total:</h5> --}}
        <hr>
        <div class="row aeprojectiontitle_card_body">
           
             <div class="col-md-12">
                <!-- <div class="card">
                   <div class="card-body"> -->
                    <table class="fs--1 table table-striped  border bg-white table-bordered text-center" id="titleprojection-ae-list">
                        <thead class="border border-1">
                        
                           <tr>
                              <th scope="col" width="70%" class="text-center">Name</th>
                              <th scope="col" width="30%" class="text-center">Projection</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col" class="text-center">Total</th>
                                <th scope="col" class="text-center"> <span class="aeprojectiontitle_total"> </span> </th>
                            </tr>
                        </tfoot>
                      
                     </table>

                   <!-- </div>
               </div> -->
               
          
              
           </div>

        </div>
        
    </div>
 </div>





<div class="loading" hidden>
    <div class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>   

<script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
<script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
<script src="{{ asset('vendors/is/is.min.js') }}"></script>
<script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
<script src="{{ asset('vendors/list.js/list.min.js') }}"></script>
<script src="{{ asset('vendors/fullcalendar/main.min.js') }}"></script>
<script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('vendors/dayjs/dayjs.min.js') }}"></script>
<script src="{{ asset('assets/js/phoenix.js') }}"></script>
<script src="{{ asset('vendors/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('vendors/leaflet/leaflet.js') }}"></script>
<script src="{{ asset('vendors/leaflet.markercluster/leaflet.markercluster.js') }}"></script>
<script src="{{ asset('vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js') }}"></script>
<script src="{{ asset('assets/js/ecommerce-dashboard.js') }}"></script>


{{-- MAZER JS --}}

<script src="{{ asset('assets/js/dataTables.min.js') }}"></script>

<script src="{{ asset('assets/js/adapter.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-growl.min.js') }}"></script>
<script src="{{ asset('assets/js/canvasjs.min.js') }}"></script>
<script src="{{ asset('assets/js/classie.js') }}"></script>
<script src="{{ asset('assets/js/common-pages.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.fixedColumns.min.js') }}"></script>

<script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('assets/js/file-size.js') }}"></script>
{{-- <script src="{{ asset('assets/js/instascan.min.js') }}"></script> --}}
<script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.doubleScroll.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('assets/js/lottie-player.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
<script src="{{ asset('assets/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/script.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/sum().js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/time.js') }}"></script>
<script src="{{ asset('assets/js/vendors.js') }}"></script>
<script src="{{ asset('assets/js/vfs_fonts.js') }}"></script>

<script src="{{ asset('assets/js/dataTables.rowGroup.min.js') }}"></script>

<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.twbsPagination.min.js') }}"></script>

<script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>

<script src="{{ asset('mazer_assets/vendors/choices.js/choices.js')}}" ></script>
<script src="{{ asset('mazer_assets/vendors/toastify/toastify.js') }}"></script>
<script src="{{ asset('mazer_assets/js/extensions/toastify.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



<script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
    }
</script>



<script>


function itemAutocomplete(div, isbninput) {

$(div).each(function() {
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


            $input.val(title)
            $(isbninput).val(isbn)

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

function customerAutocomplete(inputSelector, pernr, selectedElementSelector,withcode = '0') {
        $(inputSelector).autocomplete({
            source: function(request, response) {
                // Minimum character limit
                const minChars = 2;

                if (request.term.length < minChars) {
                    response([]);
                } else {
                    $.ajax({
                        url: '/submit_find_customer?customer='+request.term+'&pernr='+pernr+'&withsapcode='+withcode,
                        dataType: 'json',
                        beforeSend: function() {
                            response([{ num: 0, description: "Searching..." }]);
                            
                            $(selectedElementSelector).val("");

                            // $(inputSelector).removeClass("is-valid is-invalid");
                            
                        },
                        success: function(data) {
                            if (!data || data.length === 0) {
                                response([{ num: 0, description: "No records found." }]);
                            } else {
                                response(data);
                            }
                        }
                    });
                }
            },
            minLength: 2, // Minimum characters before triggering autocomplete
            select: function(event, ui) {
                // Handle the selected item
                var label = ui.item.label;
                var num = ui.item.num;
                var kunnr = ui.item.kunnr;
                var customername = ui.item.customername;

                console.log('Selected: ' + customername);

                if (num !== '0') { 
                    $(selectedElementSelector).val(kunnr);
                    $(inputSelector).val(customername);

                    // $(inputSelector).removeClass("is-invalid");
                    // $(inputSelector).addClass("is-valid");
                } else {
                    // $(inputSelector).addClass("is-invalid");
                }

                return false; // Prevent the default behavior of filling the input with the selected value
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
                '<strong>Name:</strong> ' + item.customername +
                '<br>' +
                '<strong>Code:</strong> ' + item.kunnr +  '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <strong>AE:</strong> ' + item.aename 
               
           
                // '<br>' +
                // '<strong>Author:</strong> ' + item.author 
                
                + '</div>';

            return $("<li>").append(html).appendTo(ul);
        };
    }

    
function truncatelimitWords(str, limit = 30) {
    if (str.length > limit) {
        return str.substring(0, limit) + '...'; // Add ellipsis if truncated
    }
    return str;
}

function blinkEmptyValue(c,timer = 2000) {
    
    var classes = 'blink-text';
   $(c).addClass(classes),setTimeout( () => $(c).removeClass(classes),timer);

}
    

function JStruncateLimitWords(text, limit = 30) {
    if (!text) return "";
    return text.length > limit ? text.substring(0, limit) + "…" : text;
}

function filterUsersSalesTeam() {

    var r = [
        'AE',
        'SSM',
        'RSM',
    ]

    return r

}

function selectChoices (className) {
    var choices = new Choices(className, {
        removeItems: true,
        removeItemButton: true,
        placeholder: false,
        position: 'bottom', 
    });
    
    return choices
}

function numberFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function generateRandomStringAndInt(length) {
    var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var randomString = '';
    for (var i = 0; i < length; i++) {
        randomString += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    var randomInt = Math.floor(Math.random() * 1000); // Change 1000 to the maximum integer value you want
    return randomString + randomInt;
}

function getTwoLettersEachWord (str) {
    if (!str) return "";

    // linisin ang input
    str = str.trim();

    // kunin first word
    var firstWord = str.split(/\s+/)[0]
        .replace(/[^a-zA-Z]/g, "");

    var prefix = firstWord.substring(0, 2).toUpperCase();

    // kunin lahat ng letters
    var letters = str.replace(/[^a-zA-Z]/g, "").split("");

    // shuffle letters
    for (var i = letters.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        [letters[i], letters[j]] = [letters[j], letters[i]];
    }

    // kumuha ng random 4 letters
    var randomPart = letters.slice(0, 4).join("").toUpperCase();

    return prefix + randomPart;
}


function getUrlParameter(name) {
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  }

  

  function ExportExcel(table_id, fileName) {
    var table = document.getElementById(table_id);
    var dataTable = null;

    // ✅ Check kung naka-DataTable
    if ($.fn.DataTable.isDataTable(table)) {
        dataTable = $(table).DataTable();
        dataTable.page.len(-1).draw(); // disable pagination
    }

    // ✅ Clone the table to avoid modifying the original
    var clonedTable = table.cloneNode(true);

    // 🔹 Remove hidden elements (d-none or hidden)
    $(clonedTable).find('.d-none, [hidden]').remove();

    // 🔹 Convert <input type="number"> to its value (optional, retain kung gusto mo)
    $(clonedTable).find('input[type="number"]').each(function() {
        var $input = $(this);
        var value = $input.val(); // or keep this for numeric input
        $input.closest('td').text(value);
    });

    // 🔹 Remove unwanted <i> icons or comments
    $(clonedTable).find('i').remove();

    // 🔹 Clean up extra text (like fontawesome residues)
    $(clonedTable).find('td, th').each(function() {
        var text = $(this).text();
        text = text.replace(/Font Awesome fontawesome.com/g, '').replace(/-->/g, '');
        $(this).text(text);
    });

    // 🔹 ✅ MAIN LOGIC: Gamitin 'title' attribute kung meron
    $(clonedTable).find('td').each(function() {
        var $td = $(this);
        var $withTitle = $td.find('[title]').first(); // hanapin kahit anong element na may title

        if ($withTitle.length > 0) {
            // kung may title, yun ang gagamitin
            $td.text($withTitle.attr('title'));
        } else {
            // fallback sa plain text ng cell
            $td.text($td.text().trim());
        }
    });

    // ✅ Create Workbook & Worksheet
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.table_to_sheet(clonedTable);

    // 🔹 Add timestamp at the bottom
    var currentDateTime = new Date();
    var formattedDateTime = currentDateTime.toLocaleString();
    var lastRowIndex = clonedTable.rows.length + 1; 
    XLSX.utils.sheet_add_aoa(ws, [[`Created on: ${formattedDateTime}`]], { origin: `A${lastRowIndex}` });

    // ✅ Append sheet & Save
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    XLSX.writeFile(wb, fileName + '.xlsx');

    // ✅ Restore DataTable pagination
    if (dataTable) {
        dataTable.page.len(10).draw();
    }
}


//   function ExportExcel(table_id, fileName) {
//     var table = document.getElementById(table_id);

//     // Create a new Workbook
//     var wb = XLSX.utils.book_new();
//     var ws = XLSX.utils.table_to_sheet(table);

//     // Get current date and time
//         var currentDateTime = new Date();
//         var formattedDateTime = currentDateTime.toLocaleString(); // Format to readable date and time

//     // Add a new row at the bottom with date and time
//         var lastRowIndex = table.rows.length + 1; // Adding below the last row
//         XLSX.utils.sheet_add_aoa(ws, [[`Created on: ${formattedDateTime}`]], { origin: `A${lastRowIndex}` });
        
//     // Add the worksheet to the workbook
//     XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

//     // Save the workbook to a file
//     XLSX.writeFile(wb, fileName + '.xlsx');
// }


    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // Determine AM/PM
        var ampm = (hours < 12) ? "AM" : "PM";

        // Convert 24-hour format to 12-hour format
        hours = (hours % 12 === 0) ? 12 : hours % 12;

        // Add leading zeros to single-digit hours, minutes, and seconds
        hours = (hours < 10) ? "0" + hours : hours;
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        // Display the formatted time in the #clock element
        $('#clock').text(hours + ":" + minutes + ":" + seconds + " " + ampm);
    }

    // Update the clock every second (1000 milliseconds)
    setInterval(updateClock, 1000);

    // Initial call to display the clock immediately
    updateClock();
  
  function showLoading(divClass = '.loading' ) {
    $(divClass).removeAttr("hidden");
  }

  function hideLoading(divClass = '.loading' ) {
    $(divClass).attr("hidden", "hidden");
  }

  function DataTableReload(id) {
    if ($.fn.DataTable.isDataTable(id)) {
        $(id).DataTable().ajax.reload(null, false);
    }
}

  function fileIcon(ext = '1') {
        const fileIcons = {
            'pdf': 'fa-file-pdf text-danger',
            'doc': 'fa-file-word text-primary',
            'docx': 'fa-file-word text-primary ',
            'xls': 'fa-file-excel text-success',
            'xlsx': 'fa-file-excel text-success',
            'ppt': 'fa-file-powerpoint text-danger',
            'pptx': 'fa-file-powerpoint text-danger',
            'txt': 'fa-file-alt text-dark',
            'jpg': 'fa-file-image text-primary',
            'jpeg': 'fa-file-image text-primary',
            'png': 'fa-file-image text-primary',
            'gif': 'fa-file-image text-primary',
            'zip': 'fa-file-archive text-dark',
            'rar': 'fa-file-archive text-dark'
        };

        const defaultIconClass = 'fa-file-alt';

        var iconClass = fileIcons[ext] || defaultIconClass;

        return iconClass;
  }
  function type_status_badge (val){
    
    if (val == 'bsa') {
        
        var status = '<span class="badge ms-1 bg-info-500 text-white"> BSA </span>';
    }
    else  if (val == 'nonbsa') {
        
        var status = '<span class="badge ms-1 bg-info-600 text-white"> Non-BSA </span>';
    }
    else {
        var status = "";
    }

    return status;
  }

  function updateUrlParam(key, value) {
  // Gamitin built-in URL object
  let url = new URL(window.location.href);

  // Set the param (auto-adds kung wala, auto-updates kung meron)
  url.searchParams.set(key, value);

  // Update the browser URL (no refresh)
  window.history.replaceState({}, '', url);
}

  function sweetalert(_title,_text,icon = 'success',timer = '2000',btn = false) {
    var con1 = document.createElement('div');
    var con2 = document.createElement('div');

    // Set the innerHTML of the containers
    con1.innerHTML = _title;
    con2.innerHTML = _text;

        swal({
            title: con1.innerHTML,
            content: con2,
            icon: icon,
            timer: timer,
            buttons: btn
        });
  }

  $('select').each(function () {
        // Filter out the placeholder or blank options
        var options = $(this).find('option').filter(function () {
            return $(this).val() !== "";
        });

        // If only one valid option remains, select it
        if (options.length === 1) {
            options.prop('selected', true);
        }
    });

  function resetWizard(formClass) {
    // Reset the form fields
    $(formClass).trigger("reset");

    // Remove 'done', 'complete', and 'active' classes from all nav-links
    $('.nav-wizard .nav-link').removeClass('done complete active');

    // Add 'active' class to the first nav-link
    $('.nav-wizard .nav-link').first().addClass('active');

    // Remove 'active show' from all tab panes
    $('.tab-pane').removeClass('active show');

    // Show the first tab content
    $('#bootstrap-wizard-tab1').addClass('active show');



    // Ensure the "Next" button is visible
    $('[data-wizard-next-btn]').removeClass('d-none');

    // Enable the 'Previous' button for the first step
    $('[data-wizard-prev-btn]').addClass('d-none');

    // Re-enable the 'Next' button for the first step
    $('[data-wizard-next-btn]').removeClass('d-none');

    // Re-enable the "Next" button for each step
    $('.nav-wizard .nav-link').each(function(index) {
        var step = $(this).data('wizard-step');
        if (step > 1) {
            $('[data-wizard-next-btn]').removeClass('d-none');
        }
    });

    // Remove 'done' and 'complete' classes from all steps
    $('.nav-wizard .nav-item .nav-link').each(function(index) {
        var step = $(this).data('wizard-step');
        if (step > 1) {
            $(this).removeClass('done complete');
        }
    });

    // Add the 'complete' class to the first step
    $('.nav-wizard .nav-link').first().addClass('complete');
}


  function displayItemPagination(page, itemsPerPage,className) {
            var startIndex = (page - 1 ) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;

            // Hide all items
            $(className).hide();

            // Display items for the current page
            $(className).slice(startIndex, endIndex).show();
    }

  function formatDatePickerDateRange(dateRange) {


        var startDate = dateRange.split(' - ')[0];
        var endDate = dateRange.split(' - ')[1];
        
        startDate = moment(startDate, 'MM/DD/YYYY').format('YYYY-MM-DD');
        endDate = moment(endDate, 'MM/DD/YYYY').format('YYYY-MM-DD');

        return startDate + ' - ' + endDate;
    }

  function refreshPage (time = 3000) {

      setTimeout(function() {
        location.reload(true); 
      }, time);

  }
//   $('a[href]').click(function(event) {
        
//     if ($(this).attr('href').indexOf('#') === -1) {
//         showLoading(); // Call your showLoading function
//     }
// });


$(document).on('change','input', function(e) {

    var value = $(this).val();

    $(this).attr('title',value);
     
});

function formatDate(date, type = "datetime") {
    if (!date) return "";

    const d = new Date(date);
    let options = {
        timeZone: "Asia/Manila" // 🔥 Force Philippine timezone
    };

    switch (type) {
        case "datetime":
            options = { ...options, year: "numeric", month: "long", day: "numeric", hour: "numeric", minute: "2-digit", hour12: true };
            break;
        case "time":
            options = { ...options, hour: "numeric", minute: "2-digit", hour12: true };
            break;
        case "monthday":
            options = { ...options, month: "long", day: "numeric" };
            break;
        case "y-m-d":
            // ISO but still Manila timezone corrected
            const ymd = d.toLocaleString("en-CA", { timeZone: "Asia/Manila" }).split(",")[0];
            return ymd;
        case "mdy":
            return d.toLocaleString("en-US", { timeZone: "Asia/Manila", month: "2-digit", day: "2-digit", year: "numeric" });
        case "mdyts":
            const datePart = d.toLocaleString("en-US", { timeZone: "Asia/Manila", month: "2-digit", day: "2-digit", year: "numeric" });
            const timePart = d.toLocaleString("en-US", { timeZone: "Asia/Manila", hour: "numeric", minute: "2-digit", hour12: true });
            return `${datePart} ${timePart}`;
        default:
            options = { ...options, year: "numeric", month: "long", day: "numeric" };
            break;
    }

    return d.toLocaleString("en-US", options);
}



function modalHideShow(hideModal,showModal) {
        $(hideModal).modal('hide');
        $(showModal).modal('show');
}

function get_minidashboard_pernr (pernr,projdocnum,username = '0',continuerefresh = '1') {
  var lysalesval =   $('.lysales_totalval').val();
//   var lysalesval =   '';

     $('.thisprjtnperid_totaldisplay').text('-');

  if(lysalesval == '') {

        $.ajax({
            url:"/get_projection_minidashboard?projdocnum="+projdocnum+"&username="+username+"&pernr="+pernr, 
            type:'GET',
            headers: {
                    'X-CSRF-TOKEN': getCsrfToken() 
            },
            beforeSend: function() {

                // $('#create-projection-customer-list-table tbody tr').empty()
                // showLoadingDiv('.create_projection_card'),setTimeout( () => hideLoadingDiv('.create_projection_card'),1000);

                    showLoadingDiv('.mini-db');
                    
            },
            success:function(data){
                console.log(data);
                    var d = data[0];
                    
                    var ytdsales = d.ytdsales; 
                    var lastyear = d.lastyear;
                    var tybudget = d.tybudget;    

                    var ytdsalesval = d.ytdsalesval; 
                    var lastyearval = d.lastyearval;
                    var tybudgetval = d.tybudgetval;     
                    var thisprojtnperiod = d.thisprojtnperiod;     
                    var ytdprojtn = d.ytdprojtn;     
                    var projtnoverbudget = d.projtnoverbudget;     
                    var projectionidstatus = d.projectionidstatus;     
                    var projperiodstatus = d.projperiodstatus;     

                    if(projperiodstatus === '1'){
                    
                        var projperiodstatusDisplay = `<span class="text-success blink-text"> Open </span>`;
        

                    }
                    else {

                        var projperiodstatusDisplay = `<span class="text-600"> Closed </span>`;

                    }
                    
                    $('.lysales_totalval').val(lastyear)
                    $('.ytdsales_totaldisplay').text(ytdsales)

               
                    $('.lysales_totaldisplay').text(lastyear)
                    $('.tybudget_totaldisplay').text(tybudget)
                    $('.projectionid_status_text').html(projectionidstatus)
                    $('.thisprjtnperid_totaldisplay').text(thisprojtnperiod)
                    $('.ytdprojtn_totaldisplay').text(ytdprojtn)
                    $('.projtnbudget_totaldisplay').text(projtnoverbudget)

              

                    $('.projperiodstatus').html(projperiodstatusDisplay)
                    hideLoadingDiv('.mini-db');

                
            },

                error:function(data){
                    console.log(data)
                    
                    hideLoadingDiv('.mini-db');
                }
            });
  }
  else {

  }



}


document.addEventListener('shown.bs.dropdown', (e) => {
  const menu = e.target.nextElementSibling;
  if (!menu || !menu.classList.contains('dropdown-menu')) return;

  document.body.appendChild(menu);
  const r = e.target.getBoundingClientRect();
  menu.style.position = 'fixed';
  menu.style.left = (r.right - menu.offsetWidth) + 'px';
  menu.style.top = (r.bottom) + 'px';
  menu.style.zIndex = 999999;
});

document.addEventListener('hide.bs.dropdown', (e) => {
  const menu = document.querySelector('body > .dropdown-menu');
  if (!menu) return;
  e.target.parentNode.appendChild(menu);
  menu.removeAttribute('style');
});

function getStatusBadge(isbnstatus, adhtml = '') {

        const badges = {
            saved: ['primary-500', 'Saved'],
            for_rsm_approval: ['warning', 'For RSM Approval'],
            for_imd_approval: ['primary', 'For IMD Approval'],
            for_ssm_approval: ['info', 'For SSM Approval'],
            for_ae_rsm_approval: ['warning-500', "For AE's RSM Approval"],
            for_ae_ssm_approval: ['info-600', "For AE's SSM Approval"],
            for_ae_approval: ['primary-600', 'For AE Approval'],
            for_avp_approval: ['primary', 'For AVP Approval'],
            approved: ['success', 'Approved'],
            rsm_approved: ['success', 'RSM Approved'],
            ssm_approved: ['success', 'SSM Approved'],
            cc_approved: ['success', 'CC Approved'],
            avp_approved: ['success', 'AVP Approved'],
            imd_approved: ['success', 'IMD Approved'],
            returned_isbn: ['warning', 'Returned'],
            returned: ['warning', 'Returned'],
            cancelled: ['danger', 'Cancelled'],
            rsm_disapproved: ['danger', 'RSM Disapproved'],
            ssm_disapproved: ['danger', 'SSM Disapproved'],
            ae_rsm_disapproved: ['danger', "AE's RSM Disapproved"],
            ae_ssm_disapproved: ['danger', "AE's SSM Disapproved"],
            avp_disapproved: ['danger', 'AVP Disapproved'],
            cc_disapproved: ['danger', 'CC Disapproved'],
            imd_disapproved: ['danger', 'IMD Disapproved'],
            ae_disapproved: ['danger', 'AE Disapproved'],
            no_projection: ['600 text-white', 'No Projection'],
        };

        let commentHtml = '';

        if (adhtml !== '') {
            commentHtml = `
                <a class="text-secondary"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                data-bs-html="true"
                title="${adhtml}"
                href="#!">
                    <i class="far fa-comment-dots"></i>
                </a>
            `;
        }

        // fallback pag walang status
        if (!badges[isbnstatus]) {
            return commentHtml;
        }

        const [badgeColor, badgeLabel] = badges[isbnstatus];

        return `
            <span class="badge ms-1 bg-${badgeColor}">${badgeLabel}</span>
            ${commentHtml}
        `;
}

  
  

$(document).ajaxComplete(function () {

    var $tooltipElements = $('[data-bs-toggle="tooltip"]');
            if ($tooltipElements.length > 0) {
                $tooltipElements.tooltip({
                    container: 'body',
                    trigger: 'hover',
                    html: true
                });
            }

            updateTabIndex();
});



function showLoadingDiv(canvasSelector) {
  var $container = $(canvasSelector);

  // Ensure the container is positioned relative (if not already set)
  if ($container.css('position') === 'static') {
    $container.css('position', 'relative');
  }

  // Check if the overlay already exists
  if ($container.find('.loading-overlay').length === 0) {
    // Append the overlay with the Bootstrap spinner
    $container.append(`
      <div class="loading-overlay">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    `);
  } else {
    // If exists, just show it
    $container.find('.loading-overlay').show();
  }
}

function tooltipReload() {

    $tooltipElements = $('[data-bs-toggle="tooltip"]');

    $tooltipElements.tooltip({
                    container: 'body',
                    trigger: 'hover',
                    html: true
                });
}

function JSshowCommentIcon(comment = "",additonalClass="", bgclass="text-warning") {
    
 

   var $s = `<a class="${bgclass} ${additonalClass}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true"  title="${comment}"

      href="#!"><i class="far fa-comment-dots"></i></a>`


    return $s;

}

function hideLoadingDiv(canvasSelector) {
  $(canvasSelector).find('.loading-overlay').hide();
}


function dateConvertFullCalendar(date,val = 'fulldate') {

    var newDate = new Date(date);
    if(val== 'fulldate'){
        var options = { year: 'numeric', month: 'long', day: 'numeric' };
        var formatter = new Intl.DateTimeFormat('en-PH', options);
        var formattedDate = formatter.format(newDate);
    }
    else {
        var formattedDate = newDate;
    }

    return formattedDate;
}


$(document).on('click','.titleprojectionaelist', function(e) {

    // titleprojection-ae-list

    var isbn = $(this).data('isbn')
    var basedocnum = $(this).data('basedocnum')
    var title = $(this).data('title')
    var totalprojtn = $(this).data('totalprojtn')
   
    $('.aeprojectiontitle_title').html(title)
    $('.aeprojectiontitle_isbn').html(isbn)
    $('.aeprojectiontitle_total').text(totalprojtn)

    $('#AEProjectionsTitleOffCanvas').offcanvas('show')

    $.ajax({
            url:"/datatable_titleprojectionae_list?isbn="+isbn+"&basedocnum="+basedocnum,
            type:'GET',
            headers: {
                    'X-CSRF-TOKEN': getCsrfToken() 
            },
            beforeSend: function() {
                showLoadingDiv('.aeprojectiontitle_card_body')

            },
            success:function(data){

                hideLoadingDiv('.aeprojectiontitle_card_body')
                console.log(data);

                var a = '';

                for(i=0;i<data.length;i++) {

                    var pernrname = data[i].pernrname;
                    var totalproj = data[i].totalproj;

                    a +=  `
                        <tr>
                            <td>`+pernrname+`</td>
                            <td>`+totalproj+`</td>
                        </tr>

                    `;
                   
                }

                $('#titleprojection-ae-list tbody tr').remove();
                $('#titleprojection-ae-list tbody').append(a);
                                
            },
            error:function(data){
                
                hideLoadingDiv('.aeprojectiontitle_card_body')
                
            }
    });



});

$(document).on('click','.saleshistorycanvas', function(e) {

    e.preventDefault();

    var year = $(this).data('year')
    var customercode = $(this).data('customercode')
    var customername = $(this).data('customername')
    var v = $(this).text();
    $('.detailedsh_customercode').text(customercode)
    $('.detailedsh_customername').text(customername)
    $('.detailedsh_total').text(v)
    $('.detailedsh_year').text(year)
    $('#CustomerSHDetailedHistoryOffCanvas').offcanvas('show')

    $.ajax({
            url:"/datatable_customer_saleshistory?customercode="+customercode+"&year="+year,
            type:'GET',
            headers: {
                    'X-CSRF-TOKEN': getCsrfToken() 
            },
            beforeSend: function() {
                showLoadingDiv('.detailedsh_card_body')

            },
            success:function(data){

                hideLoadingDiv('.detailedsh_card_body')
                console.log(data);

                var a = '';

                for(i=0;i<data.length;i++) {

                    var monthname = data[i].monthname;
                    var total = data[i].total;

                    a +=  `
                        <tr>
                            <td>`+monthname+`</td>
                            <td>`+total+`</td>
                        </tr>

                    `;
                   
                }

                $('#customer-sh-detailed-list-table tbody tr').remove();
                $('#customer-sh-detailed-list-table tbody').append(a);
                                
            },
            error:function(data){
                
                hideLoadingDiv('.detailedsh_card_body')
                
            }
    });
            


});
$(document).on('click','.removerow', function(e) {
            var randomString = generateRandomStringAndInt(10);


            var trClosest = $(this).closest("tr");
            trClosest.remove();
  });



function setHidden(className,val = 'hidden') {
        if(val == 'hidden') {
            document.querySelector(className).setAttribute('hidden', 'true');
        }
        else {
            document.querySelector(className).removeAttribute('hidden');
        }
       
       
}

function setHiddenDNone(className,val = 'hidden') {
        if(val == 'hidden') {
            $(className).addClass('d-none');
        }
        else {
            $(className).removeClass('d-none');
        }
       
       
}


function ExportDatatableToExcel(table_id, fileName) {
    var table = document.getElementById(table_id);
    var dataTable = $(table).DataTable();

    // Disable pagination and redraw the table to show all rows
    dataTable.page.len(-1).draw();

    // Clone the table to avoid modifying the original
    var clonedTable = table.cloneNode(true);

    // Clean up the cloned table before exporting
    $(clonedTable)
        .find('i, svg') // Remove Font Awesome icons or SVG elements
        .remove(); 

    $(clonedTable)
        .find('.exclude-from-export') // Remove any elements with the 'exclude-from-export' class
        .remove();

    // Convert the cleaned-up table to an Excel sheet
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.table_to_sheet(clonedTable);

    // Add the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

    // Save the workbook as an Excel file
    XLSX.writeFile(wb, fileName + '.xlsx');

    // Re-enable pagination and redraw the table to restore the original state
    dataTable.page.len(10).draw(); // Reset pagination to its original value
}



    function date_now_js(type = 'datetime') {
        var currentDate = new Date();
        var options = { timeZone: 'Asia/Manila' };

        if (type === 'dateonly') {
            return currentDate.toLocaleDateString('en-US', options);
        } else if (type === 'year') {
            return currentDate.getFullYear();
        } else {
            return currentDate.toLocaleString('en-US', {
                ...options,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        }
    }



function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}





function getTodayDate(type = '' ) {
    var today = new Date();
    var asiaDate = today.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
    var dateParts = asiaDate.split(", ")[0].split("/");
    var formattedDate = dateParts[2] + "-" + dateParts[0].padStart(2, '0') + "-" + dateParts[1].padStart(2, '0');
    var dateslash = dateParts[0].padStart(2, '0') + "/" + dateParts[1].padStart(2, '0') + "/" + dateParts[2] ;
    if(type == 'fullcalendar') {
        var formattedDateDisplay = dateslash + ' - ' + dateslash ;
    }else {
        var formattedDateDisplay = formattedDate;
    }
    
    return formattedDateDisplay;
}

  // function toastifyShow (textDisplay = "", pos="right", bgColor = "linear-gradient(to right, #006B32, #068e46)") {
  function toastifyShow (textDisplay = "", pos="center", bgColor = "linear-gradient(to right, white, white)",timer = 2000) {
    Toastify({
        text: textDisplay,
        duration: timer,
        close:false,
        gravity:"top",
        position: pos,
        backgroundColor: bgColor,
    }).showToast();
  }

  function select_one(element_classname) {
	$(document)(function() {
		$(element_classname).select2({
			maximumSelectionSize: 1
			
		});
	});
}



function oneChoices(className) {
    let choices = document.querySelectorAll(className);
    let initChoice;
    let choiceInstances = [];

    for (let i = 0; i < choices.length; i++) {
        if (choices[i].classList.contains("multiple-remove")) {
            initChoice = new Choices(choices[i], {
                delimiter: ',',
                editItems: true,
                shouldSort: false,
                searchResultLimit: 10,
                maxItemCount: -1,
                removeItemButton: true,
            });
        } else {
            initChoice = new Choices(choices[i], {
                shouldSort: false,
                allowHTML: true, // Prevent the input from rendering unwanted HTML
                searchResultLimit: 10,
                classNames: {
                    containerOuter: 'choices col-9', // Add custom class
                }
            });
        }

           // Push each initialized Choices instance into the array
           choiceInstances.push(initChoice);
    }
    return choiceInstances;
}

function select_one_modal(element_classname,class_modal_parent) {
	$(document).ready(function() {
		$(element_classname).select2({
			dropdownParent: $(class_modal_parent),
			maximumSelectionSize: 1
			
		});
	});
}

function number_format(n, d=0) {
  return Number(n).toLocaleString('en-US', {
    minimumFractionDigits: d,
    maximumFractionDigits: d
  });
}

function bootstrapTablePagination(tableId, paginationId, rowsPerPage) {
    let $table = $(tableId);
    // IGNORE rows na may d-none
    let $rows = $table.find("tbody tr").not(".d-none");
    let totalRows = $rows.length;
    let totalPages = Math.ceil(totalRows / rowsPerPage);

    let $pagination = $(paginationId);
    $pagination.empty();

    let $ul = $('<ul class="pagination justify-content-center"></ul>');
    $pagination.append($ul);

    function renderPagination(currentPage) {
        $ul.empty();

        // Prev
        $ul.append(`<li class="page-item ${currentPage === 0 ? 'disabled':''}">
            <a class="page-link" href="#" data-page="prev">Previous</a></li>`);

        let maxVisible = 5; // ilang numbers visible bago mag "..."
        let startPage = Math.max(0, currentPage - 2);
        let endPage = Math.min(totalPages - 1, currentPage + 2);

        if (startPage > 0) {
            $ul.append(`<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`);
            if (startPage > 1) $ul.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }

        for (let i = startPage; i <= endPage; i++) {
            $ul.append(`<li class="page-item ${i===currentPage?'active':''}">
                <a class="page-link" href="#" data-page="${i}">${i+1}</a></li>`);
        }

        if (endPage < totalPages - 1) {
            if (endPage < totalPages - 2) $ul.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            $ul.append(`<li class="page-item"><a class="page-link" href="#" data-page="${totalPages-1}">${totalPages}</a></li>`);
        }

        // Next
        $ul.append(`<li class="page-item ${currentPage === totalPages-1 ? 'disabled':''}">
            <a class="page-link" href="#" data-page="next">Next</a></li>`);
    }

    function showPage(page) {
        let start = page * rowsPerPage;
        let end = start + rowsPerPage;
        $rows.hide().slice(start, end).show(); // d-none rows ignored!
        renderPagination(page);
    }

    // Init
    showPage(0);

    // Event
    $ul.on("click", "a", function(e) {
        e.preventDefault();
        let page = $(this).data("page");
        let $current = $ul.find("li.active a").data("page");

        if (page === "prev") page = $current - 1;
        else if (page === "next") page = $current + 1;

        if (page >= 0 && page < totalPages) showPage(page);
    });
}


function renderPagination(div,totalPages, currentPage) {
    let pagination = $(div);
    pagination.empty(); // clear muna

    // Previous button
    let prevDisabled = (currentPage === 1) ? " disabled" : "";
    pagination.append(
        `<li class="page-item${prevDisabled}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
        </li>`
    );

    let maxVisible = 5; // ilan lang ipapakita bago mag "..."
    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages, startPage + maxVisible - 1);

    // ayusin pag nasa dulo na
    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }

    // kung may gap bago
    if (startPage > 1) {
        pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`);
        if (startPage > 2) {
            pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
    }

    // mga visible numbers
    for (let i = startPage; i <= endPage; i++) {
        let active = (i === currentPage) ? " active" : "";
        pagination.append(
            `<li class="page-item${active}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`
        );
    }

    // kung may gap pagkatapos
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
        pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`);
    }

    // Next button
    let nextDisabled = (currentPage === totalPages) ? " disabled" : "";
    pagination.append(
        `<li class="page-item${nextDisabled}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
        </li>`
    );
}



function dTable(
    divTable,
    url,
    columns,
    width = 400, // number or string
    $emptyTableText = "",
    searching = true,
    sInfoDisplay = "<span class=''> _START_ to _END_ Items of _TOTAL_ </span>",
    _paging = false,
    LeftfixedColumns = 0,
    RightfixedColumns = 0,
    sortCol = 0,
    disablesortendcol = 0
) {


    // 🔧 Make sure width has units (e.g., 400 → "400px")

    var disablesortend = disablesortendcol == '0' ? '-2': -1

    // 🔧 Make sure width has units (e.g., 400 → "400px")
    if (typeof width === "number") width = width + "px";

    var sInfoDisplay_ = sInfoDisplay || "<span class=''> _START_ to _END_ Items of _TOTAL_ </span>";

    // 🧩 Initialize DataTable
    $(divTable).DataTable({
        stateSave: false,
        stateSaveCallback: function (settings, data) {
            try {
                localStorage.setItem("DataTables_" + settings.sInstance, JSON.stringify(data));
            } catch (e) {
                console.error("State save error: ", e);
            }
        },
        stateLoadCallback: function (settings) {
            try {
                return JSON.parse(localStorage.getItem("DataTables_" + settings.sInstance)) || {};
            } catch (e) {
                console.error("State load error: ", e);
                return {};
            }
        },
        deferRender: true,
        processing: true,
        searchDelay: 1000,
        scrollY: width,
        scrollX: true,
        autoWidth: false,
        searching: searching,
        destroy: true,
        // ordering: false,
        paging: _paging,
        ajax: {
            url: url,
            dataSrc: "",
        },
        language: {
            emptyTable: $emptyTableText,
            sInfo: sInfoDisplay_,
            sInfoEmpty: sInfoDisplay_,
            paginate: {
                previous: '<button class="page-link btn-sm p-0 btn-no-hover fs-8" data-list-pagination="prev"><i class="fa fa-chevron-left"></i></button>',
                next: '<button class="page-link btn-sm p-0 btn-no-hover" data-list-pagination="next"><i class="fa fa-chevron-right"></i></button>',
            },
        },
        columns: columns,
        order: [[sortCol, "asc"]],
        columnDefs: [
            { targets: "_all", className: "dt-center" },
            { targets: disablesortend, 
                orderable: false
            }
        ],
        fixedColumns: {
            leftColumns: LeftfixedColumns,
            rightColumns: RightfixedColumns,
        },

        // ✔ One-time DOM customization
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            api.columns.adjust(); // ✅ ensures widths are respected after data load

            var table = $(settings.nTableWrapper);
            table.find(".dataTables_length label").addClass("fs--1 pt-2 mb-2");
            table.find(".dataTables_filter label").addClass("col-form-label-sm");
            table.find(".dataTables_filter input").addClass("form-control");
            table.find(".dataTables_info").addClass("fs--1");
        },

        // ✔ Lightweight draw logic only
        drawCallback: function (settings) {
            var table = $(settings.nTableWrapper);
            table.find(".paginate_button:not(.btn)").addClass("btn mt-2 p-2");
            table.find(".current:not(.btn-primary)").addClass("btn-primary");
        },
    });

    // ✔ Tooltip setup: delegated and safe
    $(document)
        .off("mouseenter.tooltipInit")
        .on("mouseenter.tooltipInit", "[data-bs-toggle='tooltip']", function () {
            if (!$(this).data("bs.tooltip")) {
                $(this).tooltip({
                    container: "body",
                    trigger: "hover",
                    html: true,
                });
            }
        });
}

function dTableRowGroup(
    divTable,
    rowg = 99,
    url,
    columns,
    width = 400, // number or string
    $emptyTableText = "",
    searching = true,
    sInfoDisplay = "<span class=''> _START_ to _END_ Items of _TOTAL_ </span>",
    _paging = false,
    LeftfixedColumns = 0,
    RightfixedColumns = 0,
    sortCol = 0
) {
    // 🔧 Make sure width has units (e.g., 400 → "400px")
    if (typeof width === "number") width = width + "px";

    var sInfoDisplay_ = sInfoDisplay || "<span class=''> _START_ to _END_ Items of _TOTAL_ </span>";

    // 🧩 Initialize DataTable
    $(divTable).DataTable({
        stateSave: false,
        stateSaveCallback: function (settings, data) {
            try {
                localStorage.setItem("DataTables_" + settings.sInstance, JSON.stringify(data));
            } catch (e) {
                console.error("State save error: ", e);
            }
        },
        stateLoadCallback: function (settings) {
            try {
                return JSON.parse(localStorage.getItem("DataTables_" + settings.sInstance)) || {};
            } catch (e) {
                console.error("State load error: ", e);
                return {};
            }
        },
        deferRender: true,
        processing: true,
        searchDelay: 1000,
        scrollY: width,
        scrollX: true,
        autoWidth: false,
        searching: searching,
        destroy: true,
        // ordering: false,
        paging: _paging,
        ajax: {
            url: url,
            dataSrc: "",
        },
        language: {
            emptyTable: $emptyTableText,
            sInfo: sInfoDisplay_,
            sInfoEmpty: sInfoDisplay_,
            paginate: {
                previous: '<button class="page-link btn-sm p-0 btn-no-hover fs-8" data-list-pagination="prev"><i class="fa fa-chevron-left"></i></button>',
                next: '<button class="page-link btn-sm p-0 btn-no-hover" data-list-pagination="next"><i class="fa fa-chevron-right"></i></button>',
            },
        },
        columns: columns,
        order: [[sortCol, "asc"]],
        rowGroup: {
        dataSrc: columns[rowg].data,  // 2nd column (Customer)
            startRender: function (rows, group) {
                // text ng header; puwede mo baguhin
                return  group 
                // + ' (' + rows.count() + ' items)';
            }
        },
        columnDefs: [
            { targets: rowg, visible: false },  
            { targets: "_all", className: "dt-center" }
        ],
        fixedColumns: {
            leftColumns: LeftfixedColumns,
            rightColumns: RightfixedColumns,
        },

        // ✔ One-time DOM customization
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            api.columns.adjust(); // ✅ ensures widths are respected after data load

            var table = $(settings.nTableWrapper);
            table.find(".dataTables_length label").addClass("fs--1 pt-2 mb-2");
            table.find(".dataTables_filter label").addClass("col-form-label-sm");
            table.find(".dataTables_filter input").addClass("form-control");
            table.find(".dataTables_info").addClass("fs--1");
        },

        // ✔ Lightweight draw logic only
        drawCallback: function (settings) {
            var table = $(settings.nTableWrapper);
            table.find(".paginate_button:not(.btn)").addClass("btn mt-2 p-2");
            table.find(".current:not(.btn-primary)").addClass("btn-primary");
        },
    });

    // ✔ Tooltip setup: delegated and safe
    $(document)
        .off("mouseenter.tooltipInit")
        .on("mouseenter.tooltipInit", "[data-bs-toggle='tooltip']", function () {
            if (!$(this).data("bs.tooltip")) {
                $(this).tooltip({
                    container: "body",
                    trigger: "hover",
                    html: true,
                });
            }
        });
}






    function getCSRFToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }


    // $(document).on('click','.loading',function (e) {
    //     hideLoading();
    // });


    $(document).on('input', 'input[type="number"]', function () {
        var val = $(this).val();
        var len = val.length;

        // Remove previous classes first
        $(this).removeClass('input-small-3 input-small-4 input-small-5');

        if (len === 3) {
            $(this).addClass('input-small-3');
        } else if (len === 4) {
            $(this).addClass('input-small-4');
        } else if (len >= 5) {
            $(this).addClass('input-small-5');
        }
    });

    $(document).on('keypress', 'input', function(e) {
            // Get the character code of the pressed key
            let char = String.fromCharCode(e.which);

            // Check if the character is ' or ,
            if (char === "'" || char === "," || char === "ñ" || char === "Ñ")  {
               e.preventDefault();
            }
    });


    $(document).on('click','.rem-nr', function(e) {
        
        var trClosest = $(this).closest("tr");
        trClosest.remove();
    });


function showApprovalsCount() {

    $.ajax({
        url: '/approvals_count',
        dataType: 'json',
        beforeSend: function() {
            // Add any code you need to run before the request is sent
        },
        success: function(data) {
            var notif = '';
            var approvalscount = data.approvalscount;
            var customertempcount = data.customertempcount;
            
            $('.approvalscount_display').html(approvalscount);
            $('.customertempcount_display').html(customertempcount);
        }
    });

}

showApprovalsCount();

setInterval(function() {
        showApprovalsCount();
    }, 30000);

// var showNotif = showItineraryNotification();
// setInterval(showItineraryNotification, 15000);

showLoading();

function updateTabIndex() {
    // 1️⃣ Lagyan ng tabindex=-1 sa lahat ng .un-cl at .un-cl-nbg
    $('.un-cl, .un-cl-nbg').attr('tabindex', '-1');

    // 2️⃣ Tanggalin naman sa lahat ng input/select/textarea/button
    // na WALA ang .un-cl at .un-cl-nbg
    $('input, select, textarea, button').not('.un-cl, .un-cl-nbg')
        .removeAttr('tabindex');
}


function get_projperiod_details (basedocnum) {

$.ajax({
    url:"/get_projperiod_details?basedocnum="+basedocnum, 
    type:'GET',
    headers: {
            'X-CSRF-TOKEN': getCsrfToken() 
    },
    beforeSend: function() {

   
            showLoadingDiv('.cardaccordionapprvprogress');
            
    },
    success:function(data){
        console.log(data);
        hideLoadingDiv('.cardaccordionapprvprogress');
            var d = data[0];
            
            var projtnpercentcomplete = d.percentCompletedDisplay; 
            var percentCompleted = d.percentCompleted; 
            var projperiodstatus = d.projperiodstatus; 
            var tempcount = d.tempcount; 
            var percentCompletedInt = parseInt(percentCompleted);

            $('.temp_count_text').text(tempcount)
            $('.finalreqprojtnpercentage').html(projtnpercentcomplete)

            if(projperiodstatus === '1') {
                $('.projectionid_status_text ').html(`<span class="text-success blink-text"> Open </span>`)
                
            } else {

                
                $('.projectionid_status_text ').html(`<span class="text-600"> Closed </span>`)

            }

            if(percentCompletedInt < 100){
                
            
                
                $('.accordion_finalreq').addClass('un-cl')
                $('.projectionid-status-sw' ).prop('checked',true)
                

            }
            else {
                $('.projectionid-status-sw' ).prop('checked',false)
                $('.accordion_finalreq').removeClass('un-cl')

            }


    },

    error:function(data){
        hideLoadingDiv('.cardaccordionapprvprogress');
        console.log(data)
        
    }
});


}

$(document).ready(function() {
    hideLoading();
    // oneChoices (".AddNewItemList")
    
    //CHECK SESSION EXPIRES USING LARAVE MIDDLEWARE---
    $(document).ajaxError(function (event, xhr) {
        if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.code === 'PASSWORD_CHANGE_REQUIRED') {
            window.location.replace("{{ route('password.edit') }}");
            return;
        }
        if (xhr.status == 401) {
            alert("Session has expired. Please log in again.");
        }
    });
     //---CHECK SESSION EXPIRES USING LARAVE MIDDLEWARE

     $(document).on('click','.btn_temp_showbtn',function (e) {

        var basedocnum =  $('.selected_projection_id').val()
        1

            var tempCustomerTitleListable = $("#finalreq-tempcustomertitle-list");
            var tempCustomerTitleListableURL =  "/datatable_tempcustomertitle_list?basedocnum="+basedocnum;
            var tempCustomerTitleListableColumns = [
                
                    { "data": "num" },
                    { "data": "type" },
                    { "data": "temp" },
                    { "data": "name" },
            ];



            dTable(tempCustomerTitleListable, tempCustomerTitleListableURL, tempCustomerTitleListableColumns, 250,"",true,'',false,0,0);


        })
        $(document).on('click','.btn_approvalprogres_finalreq_showbtn',function (e) {

        var basedocnum =  $('.selected_projection_id').val()
        

            var projApprovalstatusListable = $("#finalreq-projapproval-status-list");
            var projApprovalstatusListableURL =  "/datatable_reports_projapprovalstatus?basedocnum="+basedocnum+"&pernr=1";
            var projApprovalstatusListableColumns = [
                
                    { "data": "pernrrsmname" },
                    { "data": "pernrname" },
                    { "data": "totalprojtn" },
                    { "data": "totalprojtnapproved" },
                    { "data": "percent_completed" },
            ];



            dTableRowGroup(projApprovalstatusListable,0, projApprovalstatusListableURL, projApprovalstatusListableColumns, 240,"",true,'',false,0,0);


        });

     updateTabIndex();

//END READY 

});




function checkSessionStatus() {
    // Send an AJAX request to check the session status
    $.get('/session-store-check', function(data) {
        if (data.sessionExpired) {
            // Session has expired, take some action (e.g., show a message)
            alert("Please log in again.");

            window.location.href = "{{ route('login_page') }}";
        }
    });
}

checkSessionStatus()

// Check session status every minute
setInterval(checkSessionStatus, 30000); 


</script>

@yield('scriptJS')



</body>

</html>
