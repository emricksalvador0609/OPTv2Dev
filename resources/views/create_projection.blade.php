@extends('layouts.admin_app')

@section('title') Create Projection @endsection

@section('belowcontent')



  @section('menutitle')
  <div class="row">
    <div class="col-md-6 order-1 d-flex align-items-center">
       <div class="px-2 border-end">
          <h4 class="m-0 fw-bolder">Create Projection</h4>
          {{-- <a class="fw-bold h5 add-new-op text-primary " href="#!" data-bs-toggle="modal" data-bs-target="#AddNewProjectionPeriod">+ Add New</a>      --}}
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
    // ProjectionPeriodList($active = '1', $level = '1',$pernr = '1',$submitted = '1',$showallnofilter="0") 
    
//    $q = ProjectionPeriodList('1',session('division'),session('pernr'),'2','1')->get();

// function ProjectionPeriodList($active = '1', $level = '1',$pernr = '1',$submitted = '1',$showallnofilter="0") {

// if($active == '1') {
//                   $query->where('STATUS','1');
//    }
//    else{
                  
                  
//             }

//         if($showallnofilter == '0') {
      
//                   if($level !== '1') {
//                         $query->where('LEVEL',$level);
//                   }    
                  
                  
//                   if($pernr !== '1') {
      
//                         $x = OPTv2Projectionh::where('PERNR',$pernr)
//                                           ->where('SUBMIT','1')
//                                           ->groupBy('BASEDOCNUM')
//                                           ->pluck('BASEDOCNUM')
//                                           ->toArray();
      
//                         if($submitted == '1') {
//                               $query->whereNotIn('DOCNUM',$x);
//                         }
//                         else {
//                               $query->whereIn('DOCNUM',$x);
      
//                         }
      
//                   }

//             }
//             else {

//             }

$q = ProjectionPeriodList('0',trim(session('division')))->get();

  @endphp 

<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1">
                Projection Period
            </span>
            
            <select class="form-select selected_projection_id" tabindex="-1"  aria-label="Default select example">
                <option option="" selected disabled >Select in the list</option>
               
                @foreach ($q as $r)
                      <option value="{{ $r->DOCNUM }}"> {{ projection_period_display($r->DOCNUM) }}</option>
      
              @endforeach
            </select>

        </div>
    
   
    </div>
    <div class="col-md-4 text-end">
        <div class="input-group d-flex justify-content-end ">
            <span class="input-group-text" id="basic-addon1">
                Status
            </span>
            <span class="input-group-text" style="background-color:white !important;" id="basic-addon1"> 
                   <span class="projection_projperiodstatus"> - </span> 
                   <input type="text" class="create_projection_projperiodstatus d-none un-cl" hidden readonly="readonly'" >
            </span>
         
           
        </div>
    
    </div>
</div>

  {{-- Submission remains available after encoding closes. Keep it outside the customer-list loading overlay. --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 my-3">
      <div class="text-700 projection_submission_help" role="status">
          Saved entries are not yet submitted. Select a projection period to submit them for approval.
      </div>
      <button type="button" class="btn btn-info btn-sm submit_for_approval_btn" disabled>
          Submit - For Approval
      </button>
  </div>

  <div class="mini-db">
  
  {{-- <hr class="mb-0">  --}}

      
    <div class="row g-1 mt-2 flex-between-end mb-2">
        <!-- <div class="col-auto">
                    <h2 class="mb-2 text-1100">Create Budget</h2>
                </div> -->
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><svg class="svg-inline--fa fa-square fa-stack-2x text-warning-300" data-fa-transform="down-4 rotate--10 left-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.1875em 0.75em;"><g transform="translate(224 256)"><g transform="translate(-128, 128)  scale(1, 1)  rotate(-10 0 0)"><path fill="currentColor" d="M0 96C0 60.65 28.65 32 64 32H384C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96z" transform="translate(-224 -256)"></path></g></g></svg><!-- <span class="fa-solid fa-square fa-stack-2x text-warning-300" data-fa-transform="down-4 rotate--10 left-4"></span> Font Awesome fontawesome.com --><svg class="svg-inline--fa fa-circle fa-stack-2x stack-circle text-warning-100" data-fa-transform="up-4 right-3 grow-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.6875em 0.25em;"><g transform="translate(256 256)"><g transform="translate(96, -128)  scale(1.125, 1.125)  rotate(0 0 0)"><path fill="currentColor" d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z" transform="translate(-256 -256)"></path></g></g></svg><!-- <span class="fa-solid fa-circle fa-stack-2x stack-circle text-warning-100" data-fa-transform="up-4 right-3 grow-2"></span> Font Awesome fontawesome.com --><svg class="svg-inline--fa fa-peso-sign fa-stack-1x text-warning" data-fa-transform="shrink-2 up-8 right-6" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="peso-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="" style="transform-origin: 0.75em 0em;"><g transform="translate(192 256)"><g transform="translate(192, -256)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M176 32C244.4 32 303.7 71.01 332.8 128H352C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192H351.3C351.8 197.3 352 202.6 352 208C352 213.4 351.8 218.7 351.3 224H352C369.7 224 384 238.3 384 256C384 273.7 369.7 288 352 288H332.8C303.7 344.1 244.4 384 176 384H96V448C96 465.7 81.67 480 64 480C46.33 480 32 465.7 32 448V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V192C14.33 192 0 177.7 0 160C0 142.3 14.33 128 32 128V64C32 46.33 46.33 32 64 32H176zM254.4 128C234.2 108.2 206.5 96 176 96H96V128H254.4zM96 192V224H286.9C287.6 218.8 288 213.4 288 208C288 202.6 287.6 197.2 286.9 192H96zM254.4 288H96V320H176C206.5 320 234.2 307.8 254.4 288z" transform="translate(-192 -256)"></path></g></g></svg><!-- <span class="fa-stack-1x fa-solid fa-peso-sign text-warning " data-fa-transform="shrink-2 up-8 right-6"></span> Font Awesome fontawesome.com --></span>
                <div class="ms-3">
                    <h4 class="mb-0">
                        <div class="text-warning" id="bty"> <span class="thisprjtnperid_totaldisplay">-</span></div>
                    </h4>
                    <h5 class="mb-0">
                        {{-- <div class="text-600"><span class="thisprjtnperid_projtntotal_display">-</span></div> --}}
                    </h5>
                    <p class="text-800 fs--1 mb-0">This Projtn Period</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><svg class="svg-inline--fa fa-square fa-stack-2x text-danger-300" data-fa-transform="down-4 rotate--10 left-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.1875em 0.75em;"><g transform="translate(224 256)"><g transform="translate(-128, 128)  scale(1, 1)  rotate(-10 0 0)"><path fill="currentColor" d="M0 96C0 60.65 28.65 32 64 32H384C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96z" transform="translate(-224 -256)"></path></g></g></svg><!-- <span class="fa-solid fa-square fa-stack-2x text-danger-300" data-fa-transform="down-4 rotate--10 left-4"></span> Font Awesome fontawesome.com --><svg class="svg-inline--fa fa-circle fa-stack-2x stack-circle text-danger-100" data-fa-transform="up-4 right-3 grow-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.6875em 0.25em;"><g transform="translate(256 256)"><g transform="translate(96, -128)  scale(1.125, 1.125)  rotate(0 0 0)"><path fill="currentColor" d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z" transform="translate(-256 -256)"></path></g></g></svg><!-- <span class="fa-solid fa-circle fa-stack-2x stack-circle text-danger-100" data-fa-transform="up-4 right-3 grow-2"></span> Font Awesome fontawesome.com --><svg class="svg-inline--fa fa-peso-sign fa-stack-1x text-danger" data-fa-transform="shrink-2 up-8 right-6" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="peso-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="" style="transform-origin: 0.75em 0em;"><g transform="translate(192 256)"><g transform="translate(192, -256)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M176 32C244.4 32 303.7 71.01 332.8 128H352C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192H351.3C351.8 197.3 352 202.6 352 208C352 213.4 351.8 218.7 351.3 224H352C369.7 224 384 238.3 384 256C384 273.7 369.7 288 352 288H332.8C303.7 344.1 244.4 384 176 384H96V448C96 465.7 81.67 480 64 480C46.33 480 32 465.7 32 448V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V192C14.33 192 0 177.7 0 160C0 142.3 14.33 128 32 128V64C32 46.33 46.33 32 64 32H176zM254.4 128C234.2 108.2 206.5 96 176 96H96V128H254.4zM96 192V224H286.9C287.6 218.8 288 213.4 288 208C288 202.6 287.6 197.2 286.9 192H96zM254.4 288H96V320H176C206.5 320 234.2 307.8 254.4 288z" transform="translate(-192 -256)"></path></g></g></svg><!-- <span class="fa-stack-1x fa-solid fa-peso-sign text-danger " data-fa-transform="shrink-2 up-8 right-6"></span> Font Awesome fontawesome.com --></span>
                <div class="ms-3">
                    <h4 class="mb-0">
                        <div class="text-danger" id="sly"> <span class="ytdsales_totaldisplay">-</span></div>
                    </h4>
                    <h5 class="mb-0">
                        {{-- <div class="text-600"><span class="thisprjtnperid_projtntotal_display">-</span></div> --}}
                    </h5>
                    <p class=" text-800 fs--1 mb-0">YTD Sales</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><svg class="svg-inline--fa fa-square fa-stack-2x text-primary-300" data-fa-transform="down-4 rotate--10 left-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.1875em 0.75em;"><g transform="translate(224 256)"><g transform="translate(-128, 128)  scale(1, 1)  rotate(-10 0 0)"><path fill="currentColor" d="M0 96C0 60.65 28.65 32 64 32H384C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96z" transform="translate(-224 -256)"></path></g></g></svg><!-- <span class="fa-solid fa-square fa-stack-2x text-primary-300" data-fa-transform="down-4 rotate--10 left-4"></span> Font Awesome fontawesome.com --><svg class="svg-inline--fa fa-circle fa-stack-2x stack-circle text-primary-100" data-fa-transform="up-4 right-3 grow-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.6875em 0.25em;"><g transform="translate(256 256)"><g transform="translate(96, -128)  scale(1.125, 1.125)  rotate(0 0 0)"><path fill="currentColor" d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z" transform="translate(-256 -256)"></path></g></g></svg><!-- <span class="fa-solid fa-circle fa-stack-2x stack-circle text-primary-100" data-fa-transform="up-4 right-3 grow-2"></span> Font Awesome fontawesome.com --><svg class="svg-inline--fa fa-peso-sign fa-stack-1x text-primary" data-fa-transform="shrink-2 up-8 right-6" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="peso-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="" style="transform-origin: 0.75em 0em;"><g transform="translate(192 256)"><g transform="translate(192, -256)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M176 32C244.4 32 303.7 71.01 332.8 128H352C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192H351.3C351.8 197.3 352 202.6 352 208C352 213.4 351.8 218.7 351.3 224H352C369.7 224 384 238.3 384 256C384 273.7 369.7 288 352 288H332.8C303.7 344.1 244.4 384 176 384H96V448C96 465.7 81.67 480 64 480C46.33 480 32 465.7 32 448V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V192C14.33 192 0 177.7 0 160C0 142.3 14.33 128 32 128V64C32 46.33 46.33 32 64 32H176zM254.4 128C234.2 108.2 206.5 96 176 96H96V128H254.4zM96 192V224H286.9C287.6 218.8 288 213.4 288 208C288 202.6 287.6 197.2 286.9 192H96zM254.4 288H96V320H176C206.5 320 234.2 307.8 254.4 288z" transform="translate(-192 -256)"></path></g></g></svg><!-- <span class="fa-stack-1x fa-solid fa-peso-sign text-primary " data-fa-transform="shrink-2 up-8 right-6"></span> Font Awesome fontawesome.com --></span>
                <div class="ms-3">
                    <h4 class="mb-0">
                        <div class="text-primary-500" id="bly"> <span class="tybudget_totaldisplay">-</span></div>
                    </h4>
                    <h5 class="mb-0">
                        {{-- <div class="text-600"><span class="thisprjtnperid_projtntotal_display">-</span></div> --}}
                    </h5>
                    <p class="text-800 fs--1 mb-0">TY Budget</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto" title="Approved Projection">
            <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                <svg class="svg-inline--fa fa-square fa-stack-2x text-success-300" data-fa-transform="down-4 rotate--10 left-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.1875em 0.75em;"><g transform="translate(224 256)"><g transform="translate(-128, 128)  scale(1, 1)  rotate(-10 0 0)"><path fill="currentColor" d="M0 96C0 60.65 28.65 32 64 32H384C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96z" transform="translate(-224 -256)"></path></g></g></svg>
                <svg class="svg-inline--fa fa-circle fa-stack-2x stack-circle text-success-100" data-fa-transform="up-4 right-3 grow-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.6875em 0.25em;"><g transform="translate(256 256)"><g transform="translate(96, -128)  scale(1.125, 1.125)  rotate(0 0 0)"><path fill="currentColor" d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z" transform="translate(-256 -256)"></path></g></g></svg>
                <svg class="svg-inline--fa fa-peso-sign fa-stack-1x text-success" data-fa-transform="shrink-2 up-8 right-6" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="peso-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="" style="transform-origin: 0.75em 0em;"><g transform="translate(192 256)"><g transform="translate(192, -256)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M176 32C244.4 32 303.7 71.01 332.8 128H352C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192H351.3C351.8 197.3 352 202.6 352 208C352 213.4 351.8 218.7 351.3 224H352C369.7 224 384 238.3 384 256C384 273.7 369.7 288 352 288H332.8C303.7 344.1 244.4 384 176 384H96V448C96 465.7 81.67 480 64 480C46.33 480 32 465.7 32 448V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V192C14.33 192 0 177.7 0 160C0 142.3 14.33 128 32 128V64C32 46.33 46.33 32 64 32H176zM254.4 128C234.2 108.2 206.5 96 176 96H96V128H254.4zM96 192V224H286.9C287.6 218.8 288 213.4 288 208C288 202.6 287.6 197.2 286.9 192H96zM254.4 288H96V320H176C206.5 320 234.2 307.8 254.4 288z" transform="translate(-192 -256)"></path></g></g></svg><!-- <span class="fa-stack-1x fa-solid fa-peso-sign text-success " data-fa-transform="shrink-2 up-8 right-6"></span> Font Awesome fontawesome.com --></span>
                <div class="ms-3" >
                    <h4 class="mb-0">
                        <div class="text-success" id="aly"> <span class="ytdprojtn_totaldisplay">-</span></div>
                    </h4>
                    <h5 class="mb-0">
                        {{-- <div class="text-600"><span class="thisprjtnperid_projtntotal_display">-</span></div> --}}
                    </h5>
                    <p   class="text-800 fs--1 mb-0">YTD Projection</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                <svg class="svg-inline--fa fa-square fa-stack-2x text-primary-200" data-fa-transform="down-4 rotate--10 left-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.1875em 0.75em;"><g transform="translate(224 256)"><g transform="translate(-128, 128)  scale(1, 1)  rotate(-10 0 0)"><path fill="currentColor" d="M0 96C0 60.65 28.65 32 64 32H384C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96z" transform="translate(-224 -256)"></path></g></g></svg>
                <svg class="svg-inline--fa fa-circle fa-stack-2x stack-circle text-primary-100" data-fa-transform="up-4 right-3 grow-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.6875em 0.25em;"><g transform="translate(256 256)"><g transform="translate(96, -128)  scale(1.125, 1.125)  rotate(0 0 0)"><path fill="currentColor" d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z" transform="translate(-256 -256)"></path></g></g></svg>
                <svg class="svg-inline--fa fa-peso-sign fa-stack-1x text-primary-300" data-fa-transform="shrink-2 up-8 right-6" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="peso-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="" style="transform-origin: 0.75em 0em;"><g transform="translate(192 256)"><g transform="translate(192, -256)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M176 32C244.4 32 303.7 71.01 332.8 128H352C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192H351.3C351.8 197.3 352 202.6 352 208C352 213.4 351.8 218.7 351.3 224H352C369.7 224 384 238.3 384 256C384 273.7 369.7 288 352 288H332.8C303.7 344.1 244.4 384 176 384H96V448C96 465.7 81.67 480 64 480C46.33 480 32 465.7 32 448V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V192C14.33 192 0 177.7 0 160C0 142.3 14.33 128 32 128V64C32 46.33 46.33 32 64 32H176zM254.4 128C234.2 108.2 206.5 96 176 96H96V128H254.4zM96 192V224H286.9C287.6 218.8 288 213.4 288 208C288 202.6 287.6 197.2 286.9 192H96zM254.4 288H96V320H176C206.5 320 234.2 307.8 254.4 288z" transform="translate(-192 -256)"></path></g></g></svg><!-- <span class="fa-stack-1x fa-solid fa-peso-sign text-primary-300 " data-fa-transform="shrink-2 up-8 right-6"></span> Font Awesome fontawesome.com --></span>
                <div class="ms-3">
                    <h4 class="mb-0">
                        <div class="text-primary-300" id="ably"> <span class="lysales_totaldisplay">-</span>
                        <input type="text" class="lysales_totalval text-primary-300 d-none un-cl" readonly="readonly" hidden id="ably"> 
                        </div>
                    </h4>
                    <h5 class="mb-0">
                        {{-- <div class="text-600"><span class="thisprjtnperid_projtntotal_display">-</span></div> --}}
                    </h5>
                    <p class="text-800 fs--1 mb-0">LY Sales</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                {{-- <i class="fa fa-square fa-stack-2x text-400" data-fa-transform="down-4 rotate--10 left-4"></i>
                <i class="fa fa-circle fa-stack-2x stack-circle text-400" data-fa-transform="up-4 right-3 grow-2" ></i>
                <i class="fa fa-peso-sign fa-stack-1x text-200" data-fa-transform="shrink-2 up-8 right-6"></i> --}}
                <svg class="svg-inline--fa fa-square fa-stack-2x text-500" data-fa-transform="down-4 rotate--10 left-4" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.1875em 0.75em;"><g transform="translate(224 256)"><g transform="translate(-128, 128)  scale(1, 1)  rotate(-10 0 0)"><path fill="currentColor" d="M0 96C0 60.65 28.65 32 64 32H384C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96z" transform="translate(-224 -256)"></path></g></g></svg>
                <svg class="svg-inline--fa fa-circle fa-stack-2x stack-circle text-500" data-fa-transform="up-4 right-3 grow-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.6875em 0.25em;"><g transform="translate(256 256)"><g transform="translate(96, -128)  scale(1.125, 1.125)  rotate(0 0 0)"><path fill="currentColor" d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z" transform="translate(-256 -256)"></path></g></g></svg>
                <svg class="svg-inline--fa fa-peso-sign fa-stack-1x text-200" data-fa-transform="shrink-2 up-8 right-6" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="peso-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="" style="transform-origin: 0.75em 0em;"><g transform="translate(192 256)"><g transform="translate(192, -256)  scale(0.875, 0.875)  rotate(0 0 0)"><path fill="currentColor" d="M176 32C244.4 32 303.7 71.01 332.8 128H352C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192H351.3C351.8 197.3 352 202.6 352 208C352 213.4 351.8 218.7 351.3 224H352C369.7 224 384 238.3 384 256C384 273.7 369.7 288 352 288H332.8C303.7 344.1 244.4 384 176 384H96V448C96 465.7 81.67 480 64 480C46.33 480 32 465.7 32 448V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V192C14.33 192 0 177.7 0 160C0 142.3 14.33 128 32 128V64C32 46.33 46.33 32 64 32H176zM254.4 128C234.2 108.2 206.5 96 176 96H96V128H254.4zM96 192V224H286.9C287.6 218.8 288 213.4 288 208C288 202.6 287.6 197.2 286.9 192H96zM254.4 288H96V320H176C206.5 320 234.2 307.8 254.4 288z" transform="translate(-192 -256)"></path></g></g></svg><!-- <span class="fa-stack-1x fa-solid fa-peso-sign text-primary-300 " data-fa-transform="shrink-2 up-8 right-6"></span> Font Awesome fontawesome.com --></span>
                <div class="ms-3">
                    <h4 class="mb-0">
                        <div class="text-600" id="ably"> <span class="projtnbudget_totaldisplay">-</span></div>
                    </h4>
                    <h5 class="mb-0">
                        {{-- <div class="text-600"><span class="thisprjtnperid_projtntotal_display">-</span></div> --}}
                    </h5>
                    <p class="text-800 fs--1 mb-0">Projtn Over Budget</p>
                </div>
            </div>
        </div>
        
    </div>
</div>



      {{-- <div class="row">
          <div class="col-md-6">
                  <div class="row">
                      <div class="col-6 col-md-4 p-1 pt-2 col-xxl-4 text-left">
                          <div class="card mb-1 bg-warning bg-gradient false">
                             <div class="card-body p-2">
                                <div class="d-flex align-items-center left-content-between">
                                  
                                   <div class="">
                                      <h4 class="mb-0 text-white" id="issue-transact">₱<span id="totalForReleaseAmount" class="thisprjtnperid_totaldisplay">0</span></h4>
                                      <p class="text-800 fs--1 mb-0 text-white thisprjtnperid_projtntotal_display">0</p>
                                      <input type="number" class="d-none form-control un-cl form-control-sm thisprjtnperid_totalvalue" readonly="readonly">
                                      <p class="text-800 fs--1 mb-0 text-white">This Projtn Period</p>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="col-6 col-md-4 p-1 pt-2 col-xxl-4 text-left">
                          <div class="card mb-1 bg-primary bg-gradient false">
                             <div class="card-body p-2">
                                <div class="d-flex align-items-center left-content-between">
                                   
                                   <div class=" ">
                                      <h4 class="mb-0 text-white" id="pullout-transact">₱<span id="totalForReleaseAmount">0</span></h4>
                                      <p class="text-800 fs--1 mb-0 text-white">0</p>
                                      <p class="text-800 fs--1 mb-0 text-white">YTD Sales                                                </p>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="col-6 col-md-4 p-1 pt-2 col-xxl-4 text-left">
                          <div class="card mb-1 bg-danger bg-gradient false">
                             <div class="card-body p-2">
                                <div class="d-flex align-items-center left-content-between">
                                  
                                   <div class="">
                                      <h4 class="mb-0 text-white" id="issue-transact">₱<span id="totalForReleaseAmount">0</span></h4>
                                      <p class="text-800 fs--1 mb-0 text-white">0</p>
                                      <p class="text-800 fs--1 mb-0 text-white">TY Budget</p>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
      
                         <div class="col-6 col-md-4 p-1 pt-2 col-xxl-4 text-left">
                          <div class="card mb-1 bg-success bg-gradient false">
                             <div class="card-body p-2">
                                <div class="d-flex align-items-center left-content-between">
                                   
                                   <div class=" ">
                                      <h4 class="mb-0 text-white" id="pullout-transact">₱<span id="totalForReleaseAmount">0</span></h4>
                                      <p class="text-800 fs--1 mb-0 text-white">0</p>
                                      <p class="text-800 fs--1 mb-0 text-white">YTD Projection</p>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="col-6 col-md-4 p-1 pt-2 col-xxl-4 text-left">
                          <div class="card mb-1 bg-info bg-gradient false">
                             <div class="card-body p-2">
                                <div class="d-flex align-items-center left-content-between">
                                   
                                   <div class=" ">
                                      <h4 class="mb-0 text-white" id="pullout-transact">₱<span id="totalForReleaseAmount">0</span></h4>
                                      <p class="text-800 fs--1 mb-0 text-white">0</p>
                                      <p class="text-800 fs--1 mb-0 text-white">LY Sales</p>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                         
                        <div class="col-6 col-md-4 p-1 pt-2 col-xxl-4 text-left">
                           <div class="card mb-1 bg-700 bg-gradient false">
                              <div class="card-body p-2">
                                 <div class="d-flex align-items-center left-content-between">
                                    
                                    <div class=" ">
                                       <h4 class="mb-0 text-white" id="pullout-transact">₱<span id="totalForReleaseAmount">0</span></h4>
                                       <p class="text-800 fs--1 mb-0 text-white">0</p>
                                       <p class="text-800 fs--1 mb-0 text-white">Projtn Over Budget</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                    
                  </div>
          </div>
          <div class="col-md-6 d-flex justify-content-end">
              <div class="col-6 col-md-3 p-1 pt-2 col-xxl-4 text-left">
                  <div class="card mb-1 bg-200 bg-gradient false">
                      <div class="card-body text-dark p-2">
                          <div class="text-dark text-center">
                          
                              <div class=" ">
                                  <span class="fs-2 lh-1 far fa-address-book text-primary"></span>
                              <h4 class="mb-1 mt-1  " id="issue-transact"><span id="totalForReleaseAmount">-</span></h4>
                              
                              <p class="text-800 fs--1 mb-0 ">Customers</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-6 col-md-3 p-1 pt-2 col-xxl-4 text-left">
                  <div class="card mb-1 bg-200 bg-gradient false">
                      <div class="card-body text-dark p-2">
                          <div class="text-dark text-center">
                          
                              <div class=" ">
                                <span class="fs-2 lh-1 fas fa-swatchbook text-primary"></span>
                              <h4 class="mb-1 mt-1 " id="issue-transact"><span id="totalForReleaseAmount">-</span></h4>
                              
                              <p class="text-800 fs--1 mb-0 ">Titles</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
          </div>

     
      </div> --}}

     
     
  <div class="card h-100 create_projection_card mb-3">
      <div class="card-body p-2">

        <div class="row mb-2">
            {{-- <div class="col-md-1 col-lg-1"> 
                <label class="text-1000 pt-2 fw-bold mb-2">List </label>
            </div> --}}
            <div class="col-md-1 col-lg-1"> 
                
                <li class="nav-item dropdown d-flex justify-content-end">
                    <a class="nav-link px-2 p-1 border rounded icon-indicator itinerary-notification-btn icon-indicator-warning" title="Return"  href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                        {{-- <span class="badge-label fs--3 ">R</span> --}}
                        <span class="text-700" data-feather="file-text" style="height:20px;width:20px;"></span>
                        <span class="icon-indicator-number"><span class="create_projection_customerhasreturncount">-</span> 
                        <div class="itinerary-notification-total-display"></div></span>
                    </a>
        
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                        <div class="card position-relative border-0">
                          <div class="card-header p-2">
                            <div class="d-flex justify-content-between">
                              <h5 class="text-black mb-0">Return</h5>
                             
                            </div>
                          </div>
                          <div class="card-body p-0">
                            <div class="scrollbar-overlay" style="height: 15rem;">
                            
                               <div class="customerhasreturn_display"></div>
                            </div>
                          </div>
                          <div class="card-footer p-0 border-top border-0">
                            <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="#">&nbsp</a></div>
                          </div>
                        </div>
                      </div>
                 </li>
            </div>  
          
            <div class="col-md-3 pt-2 px-0 col-lg-3">
                
                
                    <!-- <a class="fw-bold btn btn-sm p-1 btn-primary projection_creation_add_new_books" data-bs-toggle="modal" data-bs-target="#LinkAccountsModal" href="#!">+ Link Accounts</a> -->
                    <a class="fw-bold p-1 fs--1 btn-primary border  create_projection_add_new_customer_modal_btn" data-bs-toggle="modal" 
                        data-bs-target="#LinkAccountsModal" href="#!">+ Add Customer</a>
            
            </div> 
            <div class="col-md-8 col-lg-8">
                <div class="d-flex justify-content-end">
              
                    <span class="badge ms-1 mt-2 bg-success">
                        Approved (<span class="approved-count">0</span>)
                    </span>
                    
                    <span class="badge ms-1 mt-2 bg-info">
                        For SSM Approval (<span class="ssm-count">0</span>)
                    </span>
                    
                    <span class="badge ms-1 mt-2 bg-warning">
                        For RSM Approval (<span class="rsm-count">0</span>)
                    </span>
                    <li class="nav-item dropdown d-flex justify-content-end">
                        <a class="nav-link badge ms-1 mt-2 bg-primary-500 text-light savedprojtn" title="Return"  href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                            {{-- <span class="badge-label fs--3 ">R</span> --}}
                            Saved (<span class="saved-count">0</span>)
                        </a>
                        
            
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                            <div class="card position-relative border-0">
                              <div class="card-header p-2">
                                <div class="d-flex justify-content-between">
                                  <h5 class="text-black mb-0">Saved</h5>
                                 
                                </div>
                              </div>
                              <div class="card-body p-0">
                                <div class="scrollbar-overlay" style="height: 15rem;">
                                
                                   <div class="savedreturn_display"></div>
                                </div>
                              </div>
                              <div class="card-footer p-0 border-top border-0">
                                <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="#">&nbsp</a></div>
                              </div>
                            </div>
                          </div>
                     </li>
              

                </div>
            </div>
            
        
        </div>

       <div class="" style="height:60vh; max-height:350vh;min-width:60vh;overflow-y:auto;overflow-x:hidden;">
          <table id="create-projection-customer-list-table" class="fs--1 table  table-responsive text-center">
              <thead class="border border-1 sticky-top">
                  <tr role="row">
                      <th colspan="2" class="bg-white border-0 text-center" rowspan="1"><a class="fw-bold btn btn-sm p-1 btn-primary create_projection_refresh"
                        ><i class="fas fa-redo-alt"></i> Refresh</a>
                    </th>
                      
                      <th colspan="3" class="bg-white border border-top text-center" rowspan="1">Projection</th>
                      {{-- <th colspan="0" class="bg-white border text-center" rowspan="1">&nbsp</th> --}}
                      <th colspan="3" class="bg-white border text-center" rowspan="1">Sales History</th>
                   
                  </tr>
                  <tr>
                      <th scope="col" class="text-center" width="5%">#</th>
                      <th scope="col" class="text-center" width="40%">Customer / Titles </th>
                      
                      <th scope="col" class="text-center d-none" width="7%">Amount</th>
                      {{-- <th scope="col" class="text-center" width="7%"># of </br> Titles</th> --}}
                      <th scope="col" class="text-center" width="7%">Projtn</th>
                      <th scope="col" class="text-center" title="This year approved projections" width="7%">This </br> Year</th>
                      <th scope="col" class="text-center text-primary-500" width="7%">Budget</th>
                      <th scope="col" class="text-center" width="7%">{{ getPreviousYear(1) }}</th>
                      <th scope="col" class="text-center" width="7%">{{ getPreviousYear(2) }}</th>
                      <th scope="col" class="text-center" width="7%">{{ getPreviousYear(3) }}</th>
                      <th scope="col" class="text-center d-none" width="7%"></th>
                  </tr>
              </thead>
              <tbody>
                                                 

              </tbody>
          </table>
          </div>

          {{-- <div id="customer-pagination" class="mt-2 text-center"></div> --}}
          {{-- <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end pt-4" id="customPagination"></ul>
        </nav> --}}


          
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
                  
               
                     
                  <div style="height:55vh; max-height:100vh;min-width:60vh;overflow-y:auto;overflow-x:hidden;">
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


<div class="input-group mt-0 d-none un-cl  w-75 pt-3 mt-3">

    <form class="currentprojectionupdate d-none un-cl   sb-form un-c" readonly="readonly" method="POST">
        @csrf
        <input class="input-group-text savedraft" title="Customer Code" name=""  type="text" placeholder="Customer Code...">
        <input class="input-group-text submitforapproval" title="Customer Code" name=""  type="text" placeholder="Customer Code...">

        {{-- <button type="submit" class="submit-projection-btn"></button> --}}
    </form>
</div>


<div class="modal" id="LinkAccountsModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="fw-bold"></span> Add New Customer </h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" onclick="$('#LinkAccountsModal').modal('hide')" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
     
        </div>
    
    
        <div class="modal-body px-0 ">
            <form class="submit_create_projection_add_new_customer">
                <div class="input-group border border-300">
                    <span class="input-group-text" id="basic-addon1">
                        Name
                    </span>
                        <input required class="form-control text-center form-control-sm w-50 create_projection_anc_customername ui-autocomplete-input" name="" data-customercode="0001806118" type="text" placeholder="Type Customer Name..." autocomplete="off">
                        <input class="form-control text-center d-none form-control-sm w-50 create_projection_anc_customercode un-cl ui-autocomplete-input" name="" data-customercode="0001806118" type="text" placeholder="Type Customer Name..." readonly="readonly" autocomplete="off">

                </div>
                
                <div class="text-end border-top mt-3 pt-2">
                    <div>
                        
                        <button type="submit" class="btn btn-primary btn-sm btn-save-add-customer">Save</button>
                    </div>
                </div>

            </form>

            <div class="card d-none ">
             
                        
                <div class="card-body pt-1 pb-0">
                   

                    {{-- <i>*These are the customers linked to you. If a customer is missing, please coordinate with your RSM/SSM to have it linked. </i> --}}
                
                    {{-- <div class="card_add_new_customer" style="height:40vh; max-height:350vh;overflow-y:auto;overflow-x:hidden;"> --}}
                    <div class="border-top card_add_new_customer">

                    {{-- <table id="create-projection-add-new-customer-list-table" width="100%" class="fs--1 table table-striped  border bg-white table-bordered text-center">
                        <thead class="border border-1">
                        
                            <tr>
                                <th scope="col" width="5%" class="text-center"> 
                                  
                                    #
                                </th>
                                <th scope="col" width="15%" class="text-center">Customer Code</th>
                                <th scope="col" width="70%" class="text-center">Name</th>
                                <th scope="col" width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                       
                     </table> --}}
                    </div>

                    <div class="text-end border-top p-3 d-none">
                        <div>
                            
                            <button type="button" class="btn btn-primary btn-sm">Save</button>
                        </div>
                    </div>
                </div>    
            </div>

          </div>
        </div>
    </div>
</div>



<div class="offcanvas offcanvas-end content-offcanvas border offcanvas-backdrop-transparent border-start border-300 shadow-none bg-100" style="width:60rem !important;" id="CreateNewProjectionISBNListOffCanvas" tabindex="-1" aria-labelledby="offcanvasLeftLabel">
    <div class="offcanvas-header pb-0">
       <h5 id="offcanvasLeftLabel">
            <span class="fw-bold text-900 isbnlist_customername"> - 
                
                
            </span><span class="isbnlist_customer_bsastatus"></span>
                </br>
                    <span class="fw-bold text-600 isbnlist_customercode">- </span>    
                    <input class="d-none un-cl form-control isbnlist_docnum_val" readonly="readonly">
                    <input class="d-none un-cl form-control isbnlist_customercode_val" readonly="readonly">
                    <input class="d-none un-cl form-control isbnlist_customername_val" readonly="readonly">
           

       </h5>
       {{-- save_as_draft_btn --}}
        {{-- <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-xmark text-danger"></i></button> --}}
        <button class="btn btn-sm btn-primary " data-bs-dismiss="offcanvas" aria-label="Close">Save</button>
    </div>
    <hr>
    <div class="offcanvas-body pt-0 px-0">
     
        @php
              //select type only
                    $activeWarehouses = activeWarehouses();
                    $activeBranches = activeBranches();
                    
                    $ab = '';
                    $aw = '';
                
            //------
        @endphp

        <div class="card">
            <div class="row mx-2 pt-2">
                <div class="col-md-6">
                    <div class="input-group nt_search border border-300">
                        <span class="input-group-text text-primary" id="basic-addon1">
                            + New Title
                        </span>
                            <input class="form-control text-center form-control-sm w-50 create_projection_search_title ui-autocomplete-input" name="" type="text" placeholder="Search Title Name, ISBN, Author...." autocomplete="off">
    
                    </div>
                
               
                </div>
                <div class="col-md-5 offset-md-1 text-end">
                    <div class="input-group border border-300">
                        <span class="input-group-text" id="basic-addon1">
                            Branch/Whouse
                        </span>
                        <select name="create_projection_branchwhouse_id" id="" class="form-control create_projection_branchwhouse_id_titletable form-control-sm">
                            <option value="" selected="">Choose in the list</option>

                                <optgroup label="Warehouses" class="d-none create_projection_whouselist">
                                    @foreach($activeWarehouses as $c => $d)

                                                    <option value="{{ $c }}">{{ $d }}</option>
                                        
                                        @endforeach
                                </optgroup>

                                        <optgroup label="Branches" class="d-none create_projection_brancheslist">
                                            @foreach($activeBranches as $a => $b)

                                                            <option value="{{ $a }}">{{ $b }}</option>
                                                
                                                @endforeach
                                        </optgroup>


                                   
                                    </select>
                            
                        </select>
                    </div>
                   
                
                </div>
               
               
            </div>
           
              <div class="" style="height:60vh; max-height:350vh;min-width:60vh;overflow-y:auto;overflow-x:hidden;">
                    <table class="fs--1 table  mt-2 table-responsive table-striped table-responsive text-center isbntable" style="position: relative;">
                        <thead class="sticky-top">
                            
                            <tr class="border">
                                <th scope="col" class="text-center" width="5%">Status </th>
                                <th scope="col" class="text-center" width="15%">ISBN </th>
                                <th scope="col" class="text-center" width="38%">Title Name </th>
                                <th scope="col" class="text-center d-none" width="8%">Amount </th>
                                <th scope="col" class="text-center d-none" width="8%">Unit <br> Price </th>
                                <th scope="col" class="text-center" width="8%" title="Population">Popup.</th>
                                <th scope="col" class="text-center" width="8%">Projtn</th>
                                <th scope="col" class="text-center" width="8%">Budget</th>
                        
                                <th scope="col" class="text-center" width="7%">{{ getPreviousYear(1) }} </th>
                                <th scope="col" class="text-center" width="7%">{{ getPreviousYear(2) }} </th>
                                <th scope="col" class="px-1 text-center" width="7%">{{ getPreviousYear(3) }} </th>
                            </tr>
                        </thead>
                            

                        <tbody class="border">     

                        </tbody>

                    </table>
                </div>

                <hr>

        </div>
        
    </div>
 </div>

 
<div class="modal bg-opacity-75 bg-light" id="reasonExceedProjectionModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form class="submit_reason_exceedqty">
          @csrf
        <div class="modal-header">
        
          <h5 class="modal-title" id="">Projection Exceeded </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark fs--1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times fs--1"></span> Font Awesome fontawesome.com --></button>
        </div>
        <div class="modal-body">
          {{-- <h2 class="mb-2 lh-sm calendar-title"></h2> --}}
          <div class="py-2 hs d-none  pl-0" hidden>
              <input type="text" name="reason_exceedqty_isbn" readonly="readonly"  hidden class="p-1 d-none text-center form-control reason_exceedqty_isbn"  tabindex="1" placeholder="Search activity..." aria-describedby="button-addon2">    
              <input type="text" name="reason_exceedqty_customercode" readonly="readonly"  hidden class="p-1 d-none text-center form-control reason_exceedqty_customercode"  tabindex="1" placeholder="Search activity..." aria-describedby="button-addon2">    
        </div>
          {{-- <p class="text-700 lead mb-2 text-center ">Comments before tagging as done:</p> --}}
          <div class="row">
             <div class="col-lg-12">
                 <div class="mb-1 text-start">
                     <label class="">
                        <b> Allowed Qty: <span class="reason_exceedqty_allowedqty_text">-</span></b>  <i class="fs--1"> (110% Of Last Year Sales)</i></br>
                      

                      
                    </label>
                     <textarea class="form-control reason_exceedqty_remarks" name="reason_exceedqty_remarks" maxlength="254" placeholder="Please type in reason why it exceeded..." style="height: 100px" required></textarea>
                 </div>
             </div>
        
         </div>
 
           <div class=" d-flex justify-content-center">
           </div>
          <div class="modal-footer">
             <button class="btn btn-primary" type="submit">Save</button>
          </div>
       </form>
      </div>
    </div>
  </div>
 </div>


<div class="modal" id="AddNewBookTitleModal"  tabindex="-1" aria-labelledby="addDealModal" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-100 p-3">
        <div class="modal-header p-0">
        <h5 class="mb-0"> <span class="fw-bold"></span> Add New Book Title</h5>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><svg class="svg-inline--fa fa-xmark text-danger" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg><!-- <span class="fas fa-times text-danger"></span> Font Awesome fontawesome.com --></button>
        </div>
    
    
        <div class="modal-body card mt-2 ">
            
            <div class="input-group ">
                <span class="input-group-text" id="basic-addon1">
                    Customer
                </span>
                <span class="input-group-text" id="add-new-book-title-customername-display" style="background-color: white !important;" id="basic-addon1">
                    -
                </span>
                <input class="form-control text-center form-control-sm un-cl" type="text" id="add-new-book-title-customercode-display" placeholder="Customer Code" readonly="readonly">

             
    
            </div>
            <hr>
                    <form class="submit_create_projection_add_new_title" method="POST">
                        <div class="row">
                            <div class="input-group mb-2">
                                <span class="input-group-text text-center">Search Title...</span>

                                    <input class="form-control text-center form-control-sm w-50 create_projection_search_title" name="" type="text" placeholder="Title...">

                                    <input class="form-control text-center form-control-sm un-cl addnewtitleisbndisp " type="text" placeholder="ISBN" readonly="readonly">
                               </div>
                               
                               <input class="form-control d-none un-cl text-center form-control-sm un-cl create_projection_search_title_isbn " type="text" placeholder="ISBN" hidden readonly="readonly">
                               <input class="form-control d-none text-center form-control-sm un-cl create_projection_search_title_total1 " type="text" placeholder="ISBN" hidden readonly="readonly">
                               <input class="form-control d-none text-center form-control-sm un-cl create_projection_search_title_total2 " type="text" placeholder="ISBN" hidden readonly="readonly">
                               <input class="form-control d-none text-center form-control-sm un-cl create_projection_search_title_total3 " type="text" placeholder="ISBN" hidden readonly="readonly">
                           
                            <div class="col-md-6 border-end d-none">
                                <tr>

                                    <div class="input-group">
                                        <span class="input-group-text text-center w-50">Population</span>
                                        <input class="form-control text-center form-control-sm add_new_book_title_select_population_qty population_qty un-cl" readonly="readonly" type="number" value="0" min="1">
                                      </div>
                                      <div class="input-group">
                                        <span class="input-group-text text-center w-50">Branch/Whouse</span>
                                        <select name="bookshop_id" id="bookshop_id" class="form-control form-control-sm">
                                            <option class="selectTitle" value="" selected="">No Branch</option>
                                            <option class="selectTitle" value="MULTI">Multiple Branch</option>
                                            <option value="1012">BAGUIO                                            </option>
                                            <option value="1017">BINAN                                             </option>
                                            <option value="1001">CEIRC                                             </option>
                                            <option value="1010">DAGUPAN                                           </option>
                                            <option value="1003">DAPITAN                                           </option>
                                            <option value="1005">FATIMA VAL                                        </option>
                                            <option value="1009">NAGA                                              </option>
                                            <option value="1011">TUGUEGARAO                                        </option>
                                            <option value="1002">UE RECTO                                          </option>
                                         </select>
                                    </div>
                                      <div class="input-group">
                                        <span class="input-group-text text-center w-50">Projection</span>
                                        <input class="form-control text-center form-control-sm add_new_book_title_select_projection_qty projection_qty un-cl" readonly="readonly" min="1" type="number" value="0" min="1">
                                      </div>

                                </tr>
                                

                                  <div class="mt-0 pt-0 text-end mt-3 border-top pt-2"><button type="submit" class="btn btn-primary">Save</button></div>

                              </div>
                              
                              <div class="col-md-6 d-none border-bottom">
                                <div class="" style="height:30vh; max-height:350vh;overflow-y:auto;overflow-x:hidden;">
                                    <table class="fs--1 table_saleshistory table border bg-white table-bordered text-center">
                                        <thead class="border border-1">
                                            <tr>
                                                <th colspan="3" scope="col" class="bg-white border text-center">Sales History</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-center">Year</th>
                                                <th scope="col" class="text-center">Order</th>
                                                <th scope="col" class="text-center">SO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr><td class="text-center fw-bold">{{ getPreviousYear(1) }} </td><td class="text-right"><span class="2024_sales_history_add_new_book">0</span></td><td class="text-right"><span class="2024_sales_history_percentage_add_new_book">0 %</span></td></tr>
                                            <tr><td class="text-center fw-bold">{{ getPreviousYear(2) }} </td><td class="text-right">0</td><td class="text-right">0 %</td></tr>
                                            <tr><td class="text-center fw-bold">{{ getPreviousYear(3) }} </td><td class="text-right">0</td><td class="text-right">0 %</td></tr>
                                            
            
                                        </tbody>
                                    </table>
                                </div>
                              </div>
            
                          
                          </div>
         
            
        </div>
        
        <div class="modal-footer px-0 pb-0 ">
            <div class="mt-0 text-end "><button class="btn btn-sm btn-primary">Save</button></div>

                  </form>
        </div>
        

    </div>
    </div>
</div>




@endsection

@section('scriptJS')


<script>

function newProjectionRow(
  customercode,
  branchwhouse,
  isbn,
  title,
  unitpInt,
  disc,
  populationqtyInt,
  projectionQty,
  linetotal,
  isbnsalestotalprev1,
  isbnsalestotalprev2,
  isbnsalestotalprev3,
  isbntotalbudget,
  isbnremarks,
  isbnstatus = ''
) {

    var isbnremarksinclude ='';

    if(isbnremarks !== '[object Object]') {

        isbnremarksinclude = isbnremarks;
        
    }

    // alert(isbnremarks)
    var r = 
    `
    <tr class="input-group form-control newprojectionrow ${customercode}${isbn}newprojectionrow">
        <td>
        <input name="new_projection_isbn_status[]" value="${isbnstatus}" class="create_new_projection_isbn_status">
        <input name="new_projection_customercode[]" value="${customercode}" class="create_new_projection_isbn_customercode">
        <input name="new_projection_branchwhouse[]" value="${branchwhouse}" class="create_new_projection_isbn_branchwhouse ${customercode}rowbranchwhouse">
        <input name="new_projection_isbn[]" value="${isbn}" class="create_new_projection_isbn">
        <input name="new_projection_isbn_title[]" value="${title}" class="create_new_projection_isbn_title">
        <input name="new_projection_isbn_unitp[]" value="${unitpInt}" class="create_new_projection_isbn_unitp">
        <input name="new_projection_isbn_disc[]" value="${disc}" class="create_new_projection_isbn_disc">
        <input name="new_projection_isbn_population[]" value="${populationqtyInt}" class="create_new_projection_population_qty">
        <input name="new_projection_isbn_qty[]" value="${projectionQty}" class="create_new_projection_projtn_qty" data-customercode="${customercode}">
        <input name="new_projection_isbn_linetotal[]" value="${linetotal}" class="create_new_projection_isbn_linetotal ${customercode}linetotal">
        <input name="new_projection_isbn_total1[]" value="${isbnsalestotalprev1}" class="create_new_projection_isbn_total1 ${customercode}total1">
        <input name="new_projection_isbn_total2[]" value="${isbnsalestotalprev2}" class="create_new_projection_isbn_total2 ${customercode}total2">
        <input name="new_projection_isbn_total3[]" value="${isbnsalestotalprev3}" class="create_new_projection_isbn_total3 ${customercode}total3">
        <input name="new_projection_isbn_budget[]" value="${isbntotalbudget}" class="create_new_projection_isbn_budget ${customercode}isbntotalbudget">
        <input name="new_projection_isbn_remarks[]" value="${isbnremarksinclude}" class="create_new_projection_isbn_remarks ${customercode}isbnremarks">
        </td>
    </tr>
    `;


    return r;

}




// $customercode_input = $request->input('newisbnprojection_customercode');
// $branchwhouse_input = $request->input('newisbnprojection_branchwhouse');
// $isbn_input = $request->input('newisbnprojection_isbn');
// $isbn_title_input = $request->input('newisbnprojection_isbn_title');
// $isbn_unitp_input = $request->input('newisbnprojection_isbn_unitp');
// $isbn_disc_input = $request->input('newisbnprojection_isbn_disc');
// $isbn_qty_population = $request->input('newisbnprojection_isbn_population');
// $isbn_qty_input = $request->input('newisbnprojection_isbn_qty');
// $isbn_linetotal_input = $request->input('newisbnprojection_isbn_linetotal');
// $isbn_remarks_input = $request->input('newisbnprojection_isbn_remarks');


function isbn_create_projection (customercode,customername,projdocnum,branchwhouse,isbn,title,unitp,disc,population,qty,linetotal,remarks,isbnsalestotalprev1 = 0,
                            isbnsalestotalprev2 = 0,
                            isbnsalestotalprev3 = 0,
                            isbntotalbudget = 0) {

    $.ajax({
            url: "/isbn_create_projection?projdocnum="+projdocnum,
            data: {
                newisbnprojection_customercode : customercode,
                newisbnprojection_customername : customername,
                projdocnum : projdocnum,
                newisbnprojection_branchwhouse : branchwhouse,
                newisbnprojection_isbn : isbn,
                newisbnprojection_isbn_title : title,
                newisbnprojection_isbn_unitp : unitp,
                newisbnprojection_isbn_disc : disc,
                newisbnprojection_isbn_population : population,
                newisbnprojection_isbn_qty : qty,
                newisbnprojection_isbn_linetotal : linetotal,
                newisbnprojection_isbn_remarks : remarks
            },
            headers: {
                'X-CSRF-TOKEN': getCsrfToken() 
            },
            type: 'POST',
            beforeSend: function() {
                showLoading();
            },
            success: function(data) {
                console.log(data);
                hideLoading();

                if (data.status == "2") {

                    var html = "" 
                            + "<span class='text-success fw-bold'>Success!</span>"
                            + "";

                        toastifyShow(html)  

                    $('.'+customercode+isbn+'customerisbnprojtnstatus').html(getStatusBadge('saved'))

                      var  _remarks = '';

                    if(remarks !== ' ' && remarks !== 'null' && remarks !== null  ) {
                     
                        var _remarks = remarks;
                    }


                    var iprjl =  newProjectionRow(
                        customercode,
                        branchwhouse,
                        isbn,
                        title,
                        unitp,
                        disc,
                        population,
                        qty,
                        linetotal,
                        isbnsalestotalprev1,
                        isbnsalestotalprev2,
                        isbnsalestotalprev3,
                        isbntotalbudget,
                        _remarks,
                        'saved'
                    )

                    $('.currentprojectionupdate').append(iprjl);

                    updateProjectionValues();

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


}

function createProjectionEditingIsbnRow(
                    randomid, customercode, isbn, title, titleDisplay,
                    isbnunitpriceclean, isbnunitpriceDisplay,
                    population, projection, totalprev1, totalprev2, totalprev3, disc,linetotal = 0,budget = 0,isbnstatus = '',isbnremarks = ''
                ) {

    var  statusBadgeDisplay = getStatusBadge(isbnstatus);
    var  enableEditingQtyStatus = ['for_submit','no_projection','saved','returned_isbn','draft'];
    var projperiodstatus = $('.create_projection_projperiodstatus').val()
    
    var cl = 'border-0 bg-transparent p-0 un-cl';
    var noeditClass = cl;

   if(enableEditingQtyStatus.includes(isbnstatus)){

        var noeditClass = 'form-control-sm';
     
    }

    if(projperiodstatus !== '1' && !enableEditingQtyStatus.includes(isbnstatus)) {
        var noeditClass = cl;
    } 
  
    var editingisbnremarks = '';
    var editingisbnremarksbg = '';
    if(isbnremarks !== '' && isbnremarks !== '[object Object]') {

        var editingisbnremarks = 'title="Exceeded: '+isbnremarks+'"';
        var editingisbnremarksbg = 'border border-warning';

    }
    var ap = `
        <tr class="${randomid}">
            <td> 
                <span class="`+customercode+isbn+`customerisbnprojtnstatus"> `+ statusBadgeDisplay +` </span>
            </td>
            <td class="d-none">
                <a class="rem-nr-projtn d-none" data-customercode="${customercode}" data-isbn="${isbn}">
                    <span class="text-danger fa-2x fas fa-times-circle"></span>
                    <input class="form-control text-center d-none un-cl form-control-sm create_projection_isbn_unitp" type="number" value="${isbnunitpriceclean}" min="1">
                    <input class="form-control un-cl d-none text-center form-control-sm create_projection_isbn_prev1_sales ${customercode}${isbn}totalprev1value" type="number" value="" min="1">
                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_linetotal ${customercode}linetotal ${customercode}${isbn}linetotal" type="number" value="" min="1">
                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_customercode" type="text" value="${customercode}">
                </a>
            </td>
            <td class="text-center isbn-cell">${isbn}</td>
            <td class="text-center" title="${title}"><span class="line-clamp-1">${title}</span></td>
            <td class="d-none">₱<span class="create_projection_linetotal_amount_display ${customercode}${isbn}linetotaltext">${linetotal}</span></td>
            <td class="d-none ">₱${isbnunitpriceDisplay}</td>
            <td><input class="form-control text-center ${noeditClass + ' ' +isbnstatus} alet create_projection_population_qty" type="number" value="${population}" min="1"></td>
            <td><input class="form-control text-center ${noeditClass + ' ' +isbnstatus} aler create_projection_projtn_qty ${editingisbnremarksbg} ${customercode}projtn" ${editingisbnremarks} type="number" data-customercode="${customercode}" data-isbn="${isbn}" value="${projection}"  min="1"></td>
            <td><span class="totalbudget_display ${customercode}${isbn}totalbudget">${budget}</span></td>
            <td><span class="totalprev_display ${customercode}${isbn}totalprev1">${totalprev1}</span></td>
            <td><span class="totalprev_display ${customercode}${isbn}totalprev2">${totalprev2}</span></td>
            <td><span class="totalprev_display ${customercode}${isbn}totalprev3">${totalprev3}</span></td>
            <td class="d-none">    
                <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn" type="text" value="${isbn}">
                <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_title" type="text" value="${title}">
                <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_disc" type="text" value="${disc}">
                <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_remarks" type="text" value="">
            </td>
        </tr>
    `;

    return ap;

}

function updateProjectionValues(className = 1) {


var defaultProjectionQtyClass = $('.create_new_projection_projtn_qty');

// if(className === 1) {
var statusCounts = {
    approved: 0,
    for_ssm_approval: 0,
    for_rsm_approval: 0,
    returned_isbn: 0,
    saved: 0
};

    defaultProjectionQtyClass.each( function() {
        
        var trClosest = $(this).closest('tr');
        var projectionQty = parseInt($(this).val()) || 0;
        var customercode = $(this).data('customercode');
        var isbn = trClosest.find('.create_new_projection_isbn').val();
        var title = trClosest.find('.create_new_projection_isbn_title').val();
        var disc = trClosest.find('.create_new_projection_isbn_disc').val();
        var populationqty = trClosest.find('.create_new_projection_population_qty').val();
        var projtnstatus = trClosest.find('.create_new_projection_isbn_status').val();
        var unitp = trClosest.find('.create_new_projection_isbn_unitp').val();
        var lastyearsale = trClosest.find('.create_new_projection_isbn_prev1_sales').val();
        var lastyearsale = trClosest.find('.create_new_projection_isbn_prev1_sales').val();

        var branchwhouse =  $('.'+ customercode + 'rowbranchwhouse').val();
        var customerlinetotalvalue =  $('.'+ customercode + 'linetotal');
        // var projtntotalvalue =  $('.'+ customercode + 'projtn');
        var projtntotalvalue =  $(`.create_new_projection_projtn_qty[data-customercode="${customercode}"]`);

        if (statusCounts.hasOwnProperty(projtnstatus)) {
            statusCounts[projtnstatus]++;
        }


        var unitpInt = parseInt(unitp) || 0;
        var populationqtyInt = parseInt(populationqty);
        var lastyearsaleInt = parseInt(lastyearsale);
        var projectionQty = parseInt($(this).val()) || 0;
        var lastyearsaleInt110 = lastyearsaleInt * 0.1;
        var lastyearsaleIntAllowed = lastyearsaleInt +  lastyearsaleInt110;
        var linetotal = projectionQty * unitpInt
        var linetotalDisplay = numberFormat(linetotal)
        
        // $('.create_projection_branchwhouse_id_titletable').val(branchwhouse)
       
        $('.thisprjtnperid_totaldisplay').text(0);

//list totals
        var linetotaltext = $('.'+customercode + isbn +'linetotaltext');
      

        linetotaltext.text(linetotalDisplay);
        
        var doctotal = 0;
        var projtntotal = 0;

        customerlinetotalvalue.each(function () {
            var vlt = $(this).val();
            let numeric = parseFloat(vlt) || 0;
            doctotal += numeric;
        });

        projtntotalvalue.each(function () {
            var pjtnt = $(this).val();
            let numeric = parseFloat(pjtnt) || 0;
            projtntotal += numeric;
        });


        var doctotalDisplay = numberFormat(doctotal);
        var projtntotalDisplay = numberFormat( projtntotal);

        $('.'+ customercode + 'doctotalvalue').val(doctotal);
        $('.'+ customercode + 'doctotal').text(doctotalDisplay);
        $('.'+ customercode + 'projtntotal').text(projtntotalDisplay);
        $('.'+ customercode + 'projtntotalvalue').val(projtntotal);

        
        var customersdoctotal = 0;

  
        $('.customersdoctotalvalue').each( function(){
            var cdt = $(this).val();
            let numeric = parseFloat(cdt) || 0;
            customersdoctotal += numeric;
        });

        var customersdoctotalDisplay = numberFormat(customersdoctotal);

        $('.thisprjtnperid_totalvalue').val(customersdoctotal);
        $('.thisprjtnperid_totaldisplay').text(customersdoctotalDisplay);


    //mini dashboard
        var projtntotalvalue = 0;
        
        $('.projtntotalvalue').each( function(){
            var prjt = $(this).val();
            let numeric = parseFloat(prjt) || 0;
            projtntotalvalue += numeric;
        });

        var thisprjtnperid_projtntotal_display = numberFormat(projtntotalvalue);

        $('.thisprjtnperid_projtntotal_display').text(thisprjtnperid_projtntotal_display);
    //-------------
//-------    
    

        
    })

    $('.approved-count').text(statusCounts.approved);
    $('.ssm-count').text(statusCounts.for_ssm_approval);
    $('.rsm-count').text(statusCounts.for_rsm_approval);
    $('.saved-count').text(statusCounts.saved);

// } else {

// }

}



function isbnAppendEditingRow(customercode) {

   
    $(".newprojectionrow").each(function () {
        var row = $(this);


        // check if this row belongs to the clicked customer
        var rowCode = (row.find(".create_new_projection_isbn_customercode").val() || "").trim();
        if (rowCode !== String(customercode).trim()) return true; // continue loop

      
        // get isbn and skip if empty
        var isbn = (row.find(".create_new_projection_isbn").val() || "").trim();
        if (!isbn) return true; // continue loop
 
        // skip if already present
        
        // collect other fields
        var title        = (row.find(".create_new_projection_isbn_title").val() || "").trim();
        var unitpClean   = (row.find(".create_new_projection_isbn_unitp").val() || "").trim();
        var unitpDisplay = numberFormat(unitpClean);
        var population   = (row.find(".create_new_projection_population_qty").val() || "").trim();
        var projection   = (row.find(".create_new_projection_projtn_qty").val() || "").trim();
        var totalprev1   = (row.find(".create_new_projection_isbn_total1").val() || "0").trim();
        var totalprev2   = (row.find(".create_new_projection_isbn_total2").val() || "0").trim();
        var totalprev3   = (row.find(".create_new_projection_isbn_total3").val() || "0").trim();
        var totalbudget  = (row.find(".create_new_projection_isbn_budget").val() || "0").trim();
        var disc         = (row.find(".create_new_projection_isbn_disc").val() || "").trim();
        var isbnstatus         = (row.find(".create_new_projection_isbn_status").val() || "").trim();
        var branchwhouse  = (row.find(".create_new_projection_isbn_branchwhouse").val() || "").trim();
        var isbnremarks  = (row.find(".create_new_projection_isbn_remarks").val() || "").trim();
        var linetotal    = numberFormat((parseInt(projection || 0, 10)) * (parseInt(unitpClean || 0, 10)));


        $('.create_projection_branchwhouse_id_titletable').val(branchwhouse).trigger('change')
        // alert('2');
   
        var randomid = generateRandomStringAndInt();

          // build row using your function
          var newRowHtml = createProjectionEditingIsbnRow(
            randomid,
            customercode,
            isbn,
            title,
            title,                // titleDisplay
            unitpClean,
            unitpDisplay,
            population,
            projection,
            totalprev1,
            totalprev2,
            totalprev3,
            disc,
            linetotal,
            totalbudget,
            isbnstatus,
            isbnremarks
        );

        // append to .isbntable
            // 5) if exists → replace; else → append
            var $existing = ISBNTablefindIsbnEditRow(customercode, isbn);
            var isbnstatusFinal = isbnstatus === '' ? 'for_submit' : isbnstatus; 
            var statusBadgeDisplay = getStatusBadge(isbnstatusFinal);

            if ($existing.length) {

                // update lang values/texts dito, wag palitan buong row
                // alert('b');
                $existing.find(".create_projection_projtn_qty").val(projection);
                $existing.find(".create_projection_population_qty").val(population);
                $existing.find(".create_projection_isbn_unitp").val(unitpClean);
                $existing.find(".create_projection_linetotal_amount_display").text(linetotal);
                // $existing.find(".totalbudget_display").text(totalbudget);

            } else {
                

               
            }

            $(".isbntable tbody").append(newRowHtml);
              
            $('.'+customercode+isbn+'customerisbnprojtnstatus').html(statusBadgeDisplay)
          
    });
}


// helper: get the existing <tr> in .isbntable for a (customercode + isbn) pair
function ISBNTablefindIsbnEditRow(customercode, isbn) {
    return $(".isbntable tbody tr").filter(function () {
        var $tr = $(this);
        var code = ($tr.find(".create_projection_isbn_customercode").val() || "").trim();
        var vIsbn = ($tr.find(".create_projection_isbn").val() || "").trim();
        return code === String(customercode).trim() && vIsbn === String(isbn).trim();
    }).first();
}


function submit_remove_zero_projtn(customercode,projdocnum,isbn) {

    $.ajax({
        url:"/submit_remove_zero_projtn", 
        data: {
            customercode : customercode,
            projdocnum : projdocnum,
            isbn : isbn,
        },
        type:'POST',
        headers: {
                'X-CSRF-TOKEN': getCsrfToken() 
        },
        beforeSend: function() {
        
        },
        success:function(data){
            console.log(data);
        
            
        },
        error:function(data){
               
        }
});
}
function create_projection_customer_list_table(projdocnum) {
    
    $('#navbarDropdownNotfication').removeClass('show');

    $.ajax({
           url:"/datatable_create_projection_customer_list?basedocnum="+projdocnum, 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {

            $('#create-projection-customer-list-table tbody tr').empty()
            // showLoadingDiv('.create_projection_card'),setTimeout( () => hideLoadingDiv('.create_projection_card'),1000);

                showLoadingDiv('.create_projection_card');
                
           },


           success:function(data){
               console.log(data);
                hideLoadingDiv('.create_projection_card');
                if (!data.projectionperiod || !data.projectionperiod.length) {
                    sweetalert(" ","Unable to load the selected projection period. Please refresh and try again.", 'error', '5000', false);
                    return;
                }
                var customerlist = data.customerlist;
                var cshlist = data.cshlist;

                var crproj = data.crproj;
                var prjd = data.projectiond;
                var prjdsubmitted = data.projectiondsubmitted;
                var pagination = data.pagination;
                var budgetlist  = data.budgetlist;
                var projperiod  = data.projectionperiod;
                var customerhasreturn  = data.customerhasreturn;
                var projperiodstatus = projperiod[0].projperiodstatus;
                var customerhasreturncount = data.customerhasreturncount[0].cnt;


                hideLoadingDiv('.create_projection_card');

                // showLoadingDiv('.totalprev_display');

                $('.customerhasreturn_display').html("");
                if(customerhasreturn && customerhasreturn.length > 0){


                    var chrd = '';
                    for(chr=0;chr<customerhasreturn.length;chr++) {

                        chrd += customerhasreturn[chr].customerhasreturn
                    }

                    $('.customerhasreturn_display').html(chrd);
                 
                }


                $('.create_projection_projperiodstatus').val(projperiodstatus);
                $('.create_projection_customerhasreturncount').text(customerhasreturncount);
                if(projperiodstatus === '1'){
                    $('.projection_submission_help').text('Saved entries are not yet submitted. Use Submit - For Approval when ready.');
                    
                    $('.projection_projperiodstatus').html(`<span class="text-success blink-text"> Open </span>`)
                    
                    $('.btn_sv, .nt_search, .create_projection_add_new_customer_modal_btn').removeClass('d-none un-cl')
                    $('.create_projection_branchwhouse_id_titletable').removeClass('un-cl')
                    

                }
                else {
                    $('.projection_submission_help').text('Encoding is closed. Existing saved entries can still be submitted for approval.');
                    $('.projection_projperiodstatus').html(`<span class="text-600"> Closed </span>`)
                    
                    $('.btn_sv, .nt_search, .create_projection_add_new_customer_modal_btn').addClass('d-none un-cl')
                    $('.create_projection_branchwhouse_id_titletable').addClass('un-cl')

                }
                
                if(customerlist[0].systemstatus == '2-2') {
                    sweetalert(" ","No linked customers found.", icon = 'info', timer = '10000', false);

                }

                else if(customerlist[0].systemstatus == '2-1') {

                    var aa = '';
      
                    for(i=0;i<customerlist.length;i++) {
                            var d = customerlist[i]
                            
                            var num = d.num;
                            var numtitles = d.numtitles;
                            var customername = d.customername;
                            var customercode = d.customercode;
                            var motheracct = d.motheracct;
                            var amount = d.amount;
                            var projection = d.projection;
                            var budget = d.budget;
                            var budgetqtydisplay = d.budgetqtydisplay;
                            var saleshistoryprev1 = d.saleshistoryprev1;
                            var saleshistoryprev2 = d.saleshistoryprev2;
                            var saleshistoryprev3 = d.saleshistoryprev3;
                            var curprojtn = d.curprojtn;
                            
                            var manualybgstriped = (num % 2 === 0) ? 'bg-100' : '';

                        
                            // alert(d.customername);

                            // <td>`+numtitles+`</td>

                            aa += `
                            <tr class="`+manualybgstriped+`">
                                    <td scope="row" class="pb-0 text-center px-2 customernum ">
                                            `+num+`

                                            <input name="" value="`+customercode+`" hidden class="d-none un-cl selected_customercode">
                                    </td>
                                    <td scope="row" class="pb-0 text-start ">
                                        
                                            
                                                `+customername+` <span class="text-danger">`+numtitles+`</span>
                    
                                    </td>
                                    
                                    <td class="d-none text-end">`+amount+`</td>
                     
                                    
                                    <td>`+projection+`</td>
                                    <td>`+curprojtn+`</td>
                                    <td>`+budgetqtydisplay+`</td>
                                    
                                    <td>
                                        `+saleshistoryprev1+`

                                    </td>
                                    <td>
                                       `+saleshistoryprev2+`

                                    </td>
                                    
                                    <td>
                                        `+saleshistoryprev3+`

                                    </td>
                            
                               
                                </tr> 
                            `
                            // <td class="d-none">
                                        
                            //             `+isbnTableList+`

                            //     </td>
                                
                    }


                    $('#create-projection-customer-list-table tbody').append(aa)
                    
                    for(c=0;c<cshlist.length;c++){

                        var customercode = cshlist[c].customercode
                        var totalprev1 = cshlist[c].total_1
                        var totalprev2 = cshlist[c].total_2
                        var totalprev3 = cshlist[c].total_3

                        var classtotalprev_display1 = customercode + 'totalprev1';
                        var classtotalprev_display2 = customercode + 'totalprev2';
                        var classtotalprev_display3 = customercode + 'totalprev3';

                        $('.'+classtotalprev_display1).text(totalprev1);
                        $('.'+classtotalprev_display2).text(totalprev2);
                        $('.'+classtotalprev_display3).text(totalprev3);

                       
                        
                    }


                    
                    for(d=0;d<budgetlist.length;d++){

                        var customercode = budgetlist[d].customercode
                        var budgetqty = budgetlist[d].budgetqty

                        var classtotalbudgetqty = customercode + 'budgetqty';

                        $('.'+classtotalbudgetqty).text(budgetqty);



                    }

                    for(cr=0;cr<crproj.length;cr++){

                        var customercode = crproj[cr].customercode
                        var curprojtn = crproj[cr].curprojtn

                        var classcurprojtn = customercode + 'curprojtnqty';

                        $('.'+classcurprojtn).text(curprojtn);



                    }

                       $('.newprojectionrow').remove();
                    
                    if (prjd && prjd.length > 0) {

                         var iprjl = '';
                         var customersavedDisplay = '';
                            for(p=0;p<prjd.length;p++){

                                var customercode = prjd[p].customercode;
                            
                                var branchwhouse =  prjd[p].branchwhouse          
                                var isbn =  prjd[p].isbn          
                                var title =  prjd[p].isbn_title    
                                var unitpInt =  prjd[p].isbn_unitp            
                                var disc =  prjd[p].disc          
                                var populationqtyInt =  prjd[p].population            
                                var hasreturn =  prjd[p].hasreturn
                                var projectionQty =  prjd[p].qty         
                                var linetotal =  prjd[p].linetotal   
                                var customersaved =  prjd[p].customersaved   
                                var isbnremarks =  prjd[p].isbnremarks || '';
                                var isbnstatus =  prjd[p].projtnisbnstatus || 'for_submit';
                                var total1 =  0;   
                                var total2 =  0;   
                                var total3 =  0;   
       
                                customersavedDisplay += prjd[p].customersaved;

                                if(hasreturn === '1') {
                                    $('.create_projection_customer_hasreturn'+customercode ).html(
                                        `
                                            <span class="badge badge-phoenix px-1 badge-phoenix-warning" title="You have a returned isbn in this customer">
                                                <span class="badge-label fs--3 ">R</span>
                                            </span>
                                        `

                                    )
                                }
                                 iprjl += newProjectionRow(
                                                customercode,
                                                branchwhouse,
                                                isbn,
                                                title,
                                                unitpInt,
                                                disc,
                                                populationqtyInt,
                                                projectionQty,
                                                linetotal,
                                                total1,
                                                total2,
                                                total3,
                                                0,
                                                isbnremarks,
                                                isbnstatus
                                            )
                            }       
                 
                        $('.currentprojectionupdate').append(iprjl);
                        $('.savedreturn_display').html(customersavedDisplay);

                    }

                    if (prjdsubmitted && prjdsubmitted.length > 0) {

                        var iprjlpsbt = '';

                        for(psbt=0;psbt<prjdsubmitted.length;psbt++){

                            const customercode = prjdsubmitted[psbt].customercode;
                        
                            const branchwhouse =  prjdsubmitted[psbt].branchwhouse          
                            const isbn =  prjdsubmitted[psbt].isbn          
                            const title =  prjdsubmitted[psbt].isbn_title    
                            const unitpInt =  prjdsubmitted[psbt].isbn_unitp            
                            const disc =  prjdsubmitted[psbt].disc          
                            const populationqtyInt =  prjdsubmitted[psbt].population            
                            const projectionQty =  prjdsubmitted[psbt].qty         
                            const linetotal =  prjdsubmitted[psbt].linetotal   
                            const isbnstatus =  prjdsubmitted[psbt].isbnstatus   
                            const isbnremarks =  prjdsubmitted[psbt].isbnremarks || '';
                            const total1 =  0;   
                            const total2 =  0;   
                            const total3 =  0;   

                                iprjlpsbt +=  newProjectionRow(
                                                customercode,
                                                branchwhouse,
                                                isbn,
                                                title,
                                                unitpInt,
                                                disc,
                                                populationqtyInt,
                                                projectionQty,
                                                linetotal,
                                                total1,
                                                total2,
                                                total3,
                                                0,
                                                isbnremarks,
                                                isbnstatus
                                            )
                                            
                        }       

                        $('.currentprojectionupdate').append(iprjlpsbt);

                       

                        // renderPagination('#customPagination',pagination[0].paginationnum, 1)
                        // bootstrapTablePagination("#create-projection-customer-list-table", "#customer-pagination", 10);


                    }

                    updateProjectionValues();
                    
                //    sweetalert(" ","All your linked customers are encoded.", icon = 'info', timer = '10000', false);

                }

                else {

                    sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);

                }

              
               
           },

            error:function(data){

                      hideLoadingDiv('.create_projection_card');
                        sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
            }
        });



}

function datatable_create_projection_customer_isbn_list(customercode,basedocnum) {
            $.ajax({
                url:"/datatable_create_projection_customer_isbn_list?customercode="+customercode+"&basedocnum="+basedocnum, 
                type:'GET',
                headers: {
                        'X-CSRF-TOKEN': getCsrfToken() 
                },
                beforeSend: function() {

                    
                    // if ($(selector).length <= 0) {
                        
                    //      showLoadingDiv('.'+customercode+.isbntable);
                    //        $(selector).empty();
                    // }
                    $('.isbnlist_customer_bsastatus').html('')

                     showLoadingDiv('.isbntable');
                    // showLoadingDiv('.create_projection_card'),setTimeout( () => hideLoadingDiv('.create_projection_card'),1000);

                        
                },
                success:function(data){
                    // console.log(data);
                    
      
                    $('.isbntable tbody tr').remove()

                    $('.isbnlist_customer_bsastatus').html(type_status_badge('nonbsa'))
                    $('.create_projection_whouselist').removeClass('d-none')
                    $('.create_projection_brancheslist').addClass('d-none')
                  
                    $('.create_projection_branchwhouse_id_titletable').val('').trigger('change')
                    
                    var customercode = $('.isbnlist_customercode_val').val();

                    isbnAppendEditingRow(customercode)

                    hideLoadingDiv('.isbntable');


                    var dd = data.customerisbnlist || [];
                    var cih = data.customerisbnsaleshistorylist || [];
                    var cib = data.customerisbnbudgetqty || [];
                    
                    var bsastatus = cih[0].bsastatus;
                    // alert(bsastatus)
                    if(bsastatus === 1) {
                        $('.isbnlist_customer_bsastatus').html(type_status_badge('bsa'))
                        $('.create_projection_brancheslist').removeClass('d-none')
                        $('.create_projection_whouselist').addClass('d-none')

                    }
                    
                    var projperiodstatus = $('.create_projection_projperiodstatus').val()
                    
                    // var branchwhouse = dd[0].branchwhouse || '';
                    
                    // $('.create_projection_branchwhouse_id_titletable').val(branchwhouse).trigger('change')
                   
                    // if ($(selector).length <= 0) {

                        var aa = '';
                          
                        var ISBNNotFound = true

                        var cl = 'border-0 bg-transparent p-0 un-cl';
                        var noeditClass = 'form-control-sm';


                        if(projperiodstatus !== '1') {
                            var noeditClass = cl;
                        } 


                        for(i=0;i<dd.length;i++) {

                                var d = dd[i]

                                var num         = d.num;
                                var customercode         = d.customercode;
                                var isbn                 = d.isbn;
                                var title                = d.title;
                                var titleDisplay         = d.titleDisplay;
                                var isbnunitpriceclean   = d.isbnunitpriceclean;
                                var isbnunitpriceDisplay = d.isbnunitpriceDisplay;
                                var population           = d.population;
                                var disc                 = d.disc;
                                var isbnremarks          = d.isbnremarks;
                                
                                if(num !== '0'){
                                    var ISBNNotFound = false
                                }


                                    var $existing = ISBNTablefindIsbnEditRow(customercode, isbn);

                                    if (!$existing.length) {

                                        aa +=  `
                                            <tr>
                                                <td>
                                                    <span class="${customercode}${isbn}customerisbnprojtnstatus"> ` + getStatusBadge('no_projection')+ ` </span>
                                                </td>
                                                <td class="d-none">
                                                    <a class="rem-nr-projtn d-none" data-customercode="${customercode}" data-isbn="${isbn}">
                                                    <span class="text-danger fa-2x fas fa-times-circle"></span>
                                                    </a>
                                                    <input class="form-control text-center d-none un-cl form-control-sm create_projection_isbn_unitp" 
                                                        type="number" value="${isbnunitpriceclean}" min="1">
                                                    <input class="form-control un-cl d-none text-center form-control-sm create_projection_isbn_prev1_sales ${customercode}${isbn}totalprev1value" 
                                                        type="number" value="" min="1">
                                                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_linetotal ${customercode}linetotal ${customercode}${isbn}linetotal" 
                                                        type="number" value="" min="1">
                                                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_customercode" 
                                                        type="text" value="${customercode}">
                                                </td>
                                                <td class="text-center isbn-cell">${isbn}</td>
                                                <td class="text-center" title="${title}">${title}</td>
                                                <td class="d-none line-clamp-1">₱<span class="create_projection_linetotal_amount_display ${customercode}${isbn}linetotaltext">0</span></td>
                                                <td class="d-none">₱${isbnunitpriceDisplay}</td>
                                                <td>
                                                    <input class="form-control text-center ${noeditClass} create_projection_population_qty" 
                                                        type="number" value="0" min="1">
                                                </td>
                                                <td>
                                                    <input class="form-control text-center ${noeditClass} create_projection_projtn_qty ${customercode}${isbn}projtn" 
                                                        type="number" data-customercode="${customercode}" data-isbn="${isbn}" value="0" min="1">
                                                    <span class="isbnremarkstooltip"></span>
                                                </td>
                                                <td><span class="totalbudget_display ${customercode}${isbn}totalbudget">0</span></td>
                                                <td><span class="totalprev_display isbnsalestotalprev1 ${customercode}${isbn}totalprev1">0</span></td>
                                                <td><span class="totalprev_display isbnsalestotalprev2 ${customercode}${isbn}totalprev2">0</span></td>
                                                <td><span class="totalprev_display isbnsalestotalprev3 ${customercode}${isbn}totalprev3">0</span></td>
                                                <td class="d-none">
                                                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn" 
                                                        type="text" value="${isbn}">
                                                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_title" 
                                                        type="text" value="${title}">
                                                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_disc" 
                                                        type="text" value="${disc}">
                                                    <input class="form-control d-none text-center form-control-sm un-cl create_projection_isbn_remarks" 
                                                        type="text" value="">
                                                </td>
                                            </tr>
                                        `;
  
                                    }
                                    
                        }
  
                        if(ISBNNotFound) {
                            return false;
                           

                        }


                        $('.isbntable tbody').append(aa)

                   

                        for(e=0;e<cih.length;e++){

                            var customercodecih = cih[e].customercode
                            var isbn = cih[e].isbn
                            var totalprev1 = cih[e].total_1
                            var totalprev2 = cih[e].total_2
                            var totalprev3 = cih[e].total_3
                            var total_1Value = cih[e].total_1Value

                            var classtotalprev_display1 = customercodecih + isbn +'totalprev1';
                            var classtotalprev_value1 = customercodecih + isbn +'totalprev1value';
                            var classtotalprev_display2 = customercodecih + isbn +'totalprev2';
                            var classtotalprev_display3 = customercodecih + isbn +'totalprev3';

                            $('.'+classtotalprev_value1).val(total_1Value);
                            $('.'+classtotalprev_display1).text(totalprev1);
                            $('.'+classtotalprev_display2).text(totalprev2);
                            $('.'+classtotalprev_display3).text(totalprev3);



                        }

                        
                        for(e=0;e<cib.length;e++){

                            var customercodecib = cib[e].customercode
                            var isbn = cib[e].isbn
                            var qty = cib[e].qty

                            var classtotalbudget = customercodecib + isbn +'totalbudget';

                            $('.'+classtotalbudget).text(qty);

                        }

                      

                        
                        // create_projection_search_title_autocomplete();
                        // updateProjectionValues();  

                    // }

                                         
                    
                },

                error:function(data){

                        hideLoadingDiv('.create_projection_card');
                            sweetalert(" ","Please contact the administrator!", icon = 'error', timer = '5000', btn = false);
                }
            });

}
$(document).ready(function(){


    customerAutocomplete('.create_projection_anc_customername', '1', '.create_projection_anc_customercode')


    function create_projection_search_title_autocomplete() {

    $('.create_projection_search_title').each(function() {
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
                var customercodesearchtitle = $('.isbnlist_customercode_val').val();

                $.ajax({
                    url: '/submit_find_item',
                    dataType: 'json',
                    data: {
                        search: request.term,
                        customercode: customercodesearchtitle,
                        include_untagged: 1
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

                if (ui.item.selectable === false) {
                    sweetalert(" ", ui.item.warning || "This ISBN is not yet tagged", icon = 'warning', timer = '5000', btn = false);
                    return false;
                }

                if (ui.item.priced === false) {
                    sweetalert(" ", "This ISBN has no price yet", icon = 'warning', timer = '4000', btn = false);
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

                $('.isbntable tbody tr').each(function () {
                    var existingIsbn = $(this)
                        .find('.isbn-cell')
                        .text()
                        .trim();

                        
                    if (existingIsbn === isbn) {
                        exists = true;
                        return false; // stop loop
                    }
                });

                if(exists){
                    
                    sweetalert(" ","Title already exist", icon = 'warning', timer = '2000', btn = false);
                    return false
                }

                $input.val(ui.item.description)


                var randomid = generateRandomStringAndInt();

                var ap = createProjectionEditingIsbnRow(
                    randomid, customercode, isbn, title, titleDisplay,
                    isbnunitpriceclean, isbnunitpriceDisplay,
                    population, projection, totalprev1, totalprev2, totalprev3, disc,0,0,'no_projection'
                ) 
              
                // $('.'+customercode+'isbntable tbody').append(ap)
                $('.isbntable tbody').append(ap)

                       
                var $wrap = $('.isbntable').parent();
                $wrap.animate({ scrollTop: $wrap[0].scrollHeight }, 600);

                var html = "" 
                    + "<span class='text-success fw-bold'>Title Added!</span>"
                    + "";

                toastifyShow(html)  
                
                blinkEmptyValue('.'+ randomid ,2000,'');
                
                    // .removeClass("is-invalid")
                    // .addClass("is-valid");

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

            var warning = '';
            if (item.warning) {
                var warningColor = item.selectable === false ? '#b02a37' : '#997404';
                warning = '<div style="padding-top:4px;color:' + warningColor + ';">' + item.warning + '</div>';
            }

            var html = '<div>' +
                '<strong>Title:</strong> ' + item.description + 
                '<br>' +
              
                '<strong>Author:</strong> ' + item.author +
                '<br>' +
                '<strong>ISBN:</strong> ' + item.isbn +  '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <strong>Copyright:</strong> ' + item.copyright 
              
                + '</div>' + warning;

            return $("<li>").append(html).appendTo(ul);
        };
    });
}

create_projection_search_title_autocomplete();
       

var v = $('.selected_projection_id').val();
var pernr = "{{session('pernr')}}";
var username = "{{session('user_staff')}}";

// get_minidashboard_pernr(pernr,v,username)

    // $(document).on('change','.create_projection_branchwhouse_id',function (e) {

    $(document).on('click','.create_projection_projtn_qty', function (e) {

        var trClosest = $(this).closest('tr');

        var populationqty = trClosest.find('.create_projection_population_qty');
        var populationqtyInt = parseInt(populationqty.val());

        if(populationqtyInt === 0) {
            populationqty.focus().select();
            var html = "" 
                + "<span class='text-warning fw-bold'>Please declare population</span>"
                + "";

            toastifyShow(html)  
        }

    })

    $(document).on('click','.create_projection_search_title',function (e) {

        if($(this).val() !== '') {
            var v = $(this).val()
        } else {
            var v = 'projectiontop10';
        }

        $(this).autocomplete("search", v);

    })

    $(document).on('click','.create_projection_refresh',function (e) {

        e.preventDefault();
        var v = $('.selected_projection_id').val();
        var pernr = "{{session('pernr')}}";
        var username = "{{session('user_staff')}}";
       

        swal({
                    title: "Unsaved changes will be lost",
                    text: "Are you sure you want to refresh?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willCancel) => {

                
                if (willCancel) {
                    
                    
                    create_projection_customer_list_table(v)

                    get_minidashboard_pernr(pernr,v,username)

                } 
                else {
                
                        
                }
            });

            


    });

    $(document).on('click','.projection_creation_add_new_books',function (e) {
        var customercode = $(this).data('customercode')
        var customername = $(this).data('customername')

        $('#add-new-book-title-customername-display').text(customername )
        $('#add-new-book-title-customercode-display').val(customercode )

    })

    $(document).on('submit','.submit_create_projection_add_new_customer',function (e) {

        e.preventDefault();
        
        var custnameTyped = $('.create_projection_anc_customername').val();
        var numtitles = 0;
        var customername = truncatelimitWords(custnameTyped,27);
        var customernameraw = custnameTyped
        var customercode = '0000TEMP_' + getTwoLettersEachWord(custnameTyped);
        var motheracct = 00;
        var amount = 0;
        var projection = 0;
        var budgetqtydisplay  = 0;
        var prevyear = 0;
        var prevyear2 = 0;
        var prevyear3 = 0;

        var saleshistoryprev1 = 0;
        var saleshistoryprev2 = 0;
        var saleshistoryprev3 = 0;

                    let allNums = document.querySelectorAll('.customernum');
                    let num = (parseInt(allNums[allNums.length-1]?.textContent||0,10))+1;
                    let manualybgstriped = (num%2===0)?'bg-100':'';

                    var exists = false;

                    var aa = `
                            <tr class="`+manualybgstriped+`">
                                    <td scope="row" class="pb-0 text-center px-2 customernum ">
                                            `+num+`

                                            <input name="" value="`+customercode+`" hidden class="d-none un-cl selected_customercode">
                                    </td>
                                    <td scope="row" class="pb-0 text-start ">
                                        
                                         <a class="create_projection_isbn_list_display line-clamp-1" data-customercode="`+customercode+`" data-customername="`+customernameraw+`" role="button" title="`+customernameraw + customercode + `" aria-expanded="true" aria-controls="collapseExample1">
                                                  `+customercode+`  &nbsp&nbsp   `+customername+` 
                                            

                                        </a>
                                            
                    
                                    </td>
                                    
                                    <td class="d-none text-end">`+amount+`</td>
                     
                                    
                                    <td>`+projection+`</td>
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>
                                        0

                                    </td>
                                    <td>
                                       0

                                    </td>
                                    
                                    <td>
                                        0

                                    </td>
                            
                               
                                </tr> 
                            `

                    $('.selected_customercode').each( function(){
                            var v = $(this).val();

                            if(customercode == v){
                                exists = true;
                                return false;
                            }

                    })

                    if(exists) {
                        sweetalert(" ","Customer already added", icon = 'warning', timer = '5000', btn = false);
                        return false;
                    }
                        
                    $('.modal').modal('hide')

                    $('#create-projection-customer-list-table > tbody').append(aa)

                    sweetalert(" ","Customer successfully added!", icon = 'success', timer = '5000', btn = false);

                    var $wrap = $('#create-projection-customer-list-table').parent();
                    $wrap.animate({ scrollTop: $wrap[0].scrollHeight }, 600);
                    blinkEmptyValue('[data-customercode="'+customercode+'"]' ,3000,'');


    })

    // $(document).on('click','.create_projection_add_new_customer',function (e) {
    //     var customercode = $(this).data('customercode')
        
    //     var saleshistoryprev1 = $(this).data('total1');
    //     var saleshistoryprev2 = $(this).data('total2');
    //     var saleshistoryprev3 = $(this).data('total3');

    //     $.ajax({
    //             url:"/submit_create_projection_add_new_customer", 
    //             data: {

    //                 customercode : customercode,
    //             },
    //             type:'POST',
    //             headers: {
    //                     'X-CSRF-TOKEN': getCsrfToken() 
    //             },
    //             beforeSend: function() {
                
                   
    //             },
    //             success:function(data){
    //                 console.log(data);
                    
    //                 var d = data.customerlist[0]
                    
         
    //                 var numtitles = d.numtitles;
    //                 var customername = d.customername;
    //                 var customernameraw = d.customernameraw;
    //                 var customercode = d.customercode;
    //                 var motheracct = d.motheracct;
    //                 var amount = d.amount;
    //                 var isbnTableList = d.isbnTableList;
    //                 var projection = d.projection;
    //                 var budget = d.budget;
    //                 var prevyear = d.prevyear;
    //                 var prevyear2 = d.prevyear2;
    //                 var prevyear3 = d.prevyear3;

    //                 let allNums = document.querySelectorAll('.customernum');
    //                 let num = (parseInt(allNums[allNums.length-1]?.textContent||0,10))+1;
    //                 let manualybgstriped = (num%2===0)?'bg-100':'';

    //                 var exists = false;

    //                 var aa = `
    //                     <tr class="`+manualybgstriped+`">
    //                                             <td scope="row" class="pb-0 text-center customernum px-2 ">
    //                                                     `+num+`

    //                                                      <input name="" value="`+customercode+`" hidden class="d-none un-cl selected_customercode">
    //                                             </td>
    //                                             <td scope="row" class="pb-0 text-start ">
                                                    
                                                        
    //                                                         `+customername+` <span class="text-danger">`+numtitles+`</span>
                                
    //                                             </td>
                                                
    //                                             <td class="text-end">`+amount+`</td>
                                
                                                
    //                                             <td>`+projection+`</td>
    //                                             <td>0</td>
    //                                             <td>`+budget+`</td>
                                                
    //                                             <td><a class="saleshistorycanvas" data-year="`+prevyear+`" data-customercode="`+customercode+`" data-customername="`+customernameraw+`"><span class="totalprev_display `+customercode+`totalprev1">
    //                                                 `+saleshistoryprev1+`

    //                                             </a></td>
    //                                             <td><a class="saleshistorycanvas" data-year="`+prevyear2+`" data-customercode="`+customercode+`" data-customername="`+customernameraw+`"><span class="totalprev_display `+customercode+`totalprev2">
    //                                             `+saleshistoryprev2+`

    //                                             </a></td>
                                                
    //                                             <td><a class="saleshistorycanvas" data-year="`+prevyear3+`" data-customercode="`+customercode+`" data-customername="`+customernameraw+`"><span class="totalprev_display `+customercode+`totalprev3">                                         
    //                                                 `+saleshistoryprev3+`

    //                                             </a></td>
                                        
    //                                             <td class="d-none">
                                                    
    //                                                     `+isbnTableList+`

    //                                             </td>
    //                                         </tr> 
    //                                     `;
    //                 $('.selected_customercode').each( function(){
    //                         var v = $(this).val();

    //                         if(customercode == v){
    //                             exists = true;
    //                             return false;
    //                         }

    //                 })

    //                 // if(exists) {
    //                 //     sweetalert(" ","Customer already added", icon = 'warning', timer = '5000', btn = false);
    //                 //     return false;
    //                 // }
                        
    //                 $('#create-projection-customer-list-table > tbody').append(aa)

    //                 sweetalert(" ","Customer successfully added!", icon = 'success', timer = '5000', btn = false);
                   
                    
    //             },
    //             error:function(data){

                            
    //                         swal("Oops...", "Something went wrong. Please contact your administrator", "error");
    //             }
    //     });
        
    // });


    $(document).on('click','.create_projection_add_new_customer_modal_btn',function (e) {

        var pernr = "{{ session('pernr') }}";
        var createProjectionAddNewCustomerListable = $("#create-projection-add-new-customer-list-table");
        var createProjectionAddNewCustomerListableURL =  "/get_pernr_customer?pernr="+pernr;
        var createProjectionAddNewCustomerListableColumns = [
                { "data": "num" },
                { "data": "customercode" },
                { "data": "customername" },
                { "data": "action" },
        
        ];

        dTable(createProjectionAddNewCustomerListable, createProjectionAddNewCustomerListableURL, createProjectionAddNewCustomerListableColumns, 280,"",true,'',true,0,0);

        
    });
    
    $(document).on('click','.save_as_draft_btn',function (e) {

        $('.savedraft').val(1);
        $('.submitforapproval').val('');
    });


    $(document).on('click','.create_projection_isbn_list_display',function (e) {
            var customercode = $(this).data('customercode');
            var customername = $(this).data('customername');
            var basedocnum = $('.selected_projection_id').val();
            // var selector = '.' + customercode + 'isbntable tbody tr';
            var selector = '.isbntable tbody tr';

            $('.create_projection_search_title').val('');

          
            $('#CreateNewProjectionISBNListOffCanvas').offcanvas('show')
            
            // if($('.isbnlist_customercode').text().trim() == customercode) {
            //     return false
            // }
            
            $('.isbnlist_customercode').text(customercode)
            $('.isbnlist_customername').text(customername)

            $('.isbnlist_customercode_val').val(customercode)
            $('.isbnlist_customername_val').val(customername)

            // Get the target collapse ID for the clicked item
                var targetId = $(this).attr('href'); // ex: "#collapseExample0001804963"

            // Collapse ALL others except the clicked one
                // $('.collapse.show').each(function () {
                //     if ('#' + $(this).attr('id') !== targetId) {
                //         $(this).collapse('hide');
                //     }
                // });     
            
                datatable_create_projection_customer_isbn_list(customercode,basedocnum)
    });

    
    $(document).on('change','.create_projection_projtn_qty', function(e) {
        
        var trClosest = $(this).closest('tr');

        var projdocnum = $('.selected_projection_id').val();

        // var lastyearsale = $(this).data('lastyearsale');
        var customercode = $(this).data('customercode');
        var customercode = $('.isbnlist_customercode_val').val()
        var customername = $('.isbnlist_customername_val').val()
        var isbnremarksdisplay = trClosest.find('.isbnremarkstooltip');
        var isbn = trClosest.find('.create_projection_isbn').val();
        var title = trClosest.find('.create_projection_isbn_title').val();
        var disc = trClosest.find('.create_projection_isbn_disc').val();
        var linetotalinputvalue = trClosest.find('.create_projection_isbn_linetotal');
        var linetotaltext = trClosest.find('.create_projection_linetotal_amount_display');
        // var isbnremarks = trClosest.find('.create_projection_isbn_remarks');
        var populationqty = trClosest.find('.create_projection_population_qty').val();
        var unitp = trClosest.find('.create_projection_isbn_unitp').val();
        var lastyearsale = trClosest.find('.create_projection_isbn_prev1_sales').val();
        var isbnsalestotalprev1 = trClosest.find('.isbnsalestotalprev1').text().trim();
        var isbnsalestotalprev2 = trClosest.find('.isbnsalestotalprev2').text().trim();
        var isbnsalestotalprev3 = trClosest.find('.isbnsalestotalprev3').text().trim();
        var isbntotalbudget = trClosest.find('.totalbudget_display').text().trim();

        // var branchwhouse =  $('.'+ customercode + 'branchwhouse').val();
        var branchwhouse =  $('.create_projection_branchwhouse_id_titletable').val();
        var customerlinetotalvalue =  $('.'+ customercode + 'linetotal');
        var projtntotalvalue =  $('.'+ customercode + 'projtn');

        var unitpInt = parseInt(unitp) || 0;
        var populationqtyInt = parseInt(populationqty);
        var lastyearsaleInt = parseInt(lastyearsale);
        var projectionQty = parseInt($(this).val()) || 0;
        var lastyearsaleInt110 = lastyearsaleInt * 0.1;
        var lastyearsaleIntAllowed = Math.round(lastyearsaleInt +  lastyearsaleInt110);
        var linetotal = projectionQty * unitpInt
        var linetotalDisplay = numberFormat(linetotal)
        // alert(lastyearsaleIntAllowed) 

        // Scenario 1: Projection qty is more than population qty
        if (projectionQty > populationqtyInt) {
            $(this).val();

            sweetalert(" ","Projtn qty is more than population qty.", icon = 'warning', timer = '3000', btn = false);
            // alert('Error: Projtn qty is more than population qty.');
            $(this).focus();
            return false;
        }

        // Scenario 2: Projection qty is not more than population qty but exceeds 110% of last year's sale
        if(lastyearsaleInt > 0 ) {
            if (projectionQty <= populationqtyInt && projectionQty > lastyearsaleIntAllowed) {

                $(this).val();

                $('#reasonExceedProjectionModal').modal('show');
                $('.reason_exceedqty_isbn').val(isbn);
                $('.reason_exceedqty_customercode').val(customercode);

                $('.reason_exceedqty_allowedqty_text').text(lastyearsaleIntAllowed)
                // sweetalert("Allowed: "+ lastyearsaleIntAllowed +"","Projtn qty exceeds 110% of last year sale.", icon = 'warning', timer = '3000', btn = false);

                // $(this).focus();
                return false;
            }
        }

                
        //append input type for submit
        $('.'+customercode+isbn+'isbnremarkstooltip').remove()

        $('.'+customercode+''+isbn+'newprojectionrow').remove();

      

        if( projectionQty > 0) {
            
                        // isbnremarks.val('')
                
                        
                        isbn_create_projection (customercode,customername,projdocnum,branchwhouse,isbn,title,unitpInt,disc,populationqtyInt,projectionQty,linetotal,null,
                            isbnsalestotalprev1,
                            isbnsalestotalprev2,
                            isbnsalestotalprev3,
                            isbntotalbudget)

                        $(this)
                            .removeAttr('title')
                            .removeClass('border border-warning');
                                
            
        }
        else {
                       var html = "" 
                            + "<span class='text-danger fw-bold'>Projection Removed!</span>"
                            + "";

                        toastifyShow(html)  

                        $('.'+customercode+isbn+'customerisbnprojtnstatus').html(getStatusBadge('no_projection'))
                        trClosest.find('.create_projection_population_qty').val(0)

                        submit_remove_zero_projtn(customercode,projdocnum,isbn)

        }
       
        //-------

   


        updateProjectionValues();

        // isbnAppendEditingRow(customercode);


                                          
           
    });


    $(document).on('change','.create_projection_population_qty',function (e) {
            var trClosest = $(this).closest('tr');
            var projtnQty = trClosest.find('.create_projection_projtn_qty')
            projtnQty.val('')

    });
    
    $(document).on('change','.create_projection_branchwhouse_id_titletable',function (e) {

        $(this).removeClass('blink-text border border-warning');

        var customercode = $('.isbnlist_customercode_val').val();
        var v = $(this).val();

        if(v !== '') {

            $('.' + customercode + 'rowbranchwhouse').val(v)

        }
      

        

    });

    $(document).on('click','.save_as_draft_btn', function(e) {

        $('.savedraft').val(1);

        $(".submit-projection-btn").trigger("click");

        // swal({
        //             title: "Are you sure you want to only save this projection?",
        //             text: "",
        //             icon: "info",
        //             buttons: true,
        //             dangerMode: true,
        //     })
        //     .then((willCancel) => {

                
        //         if (willCancel) {
                    
                

        //         } 
        //         else {
                
                        
        //         }
        //     });

    });

    $(document).on('click','.submit_for_approval_btn', function(e) {
        e.preventDefault();
        var projdocnum = $('.selected_projection_id').val();
        if (!projdocnum) {
            swal('Select a projection period', 'Choose the period containing your saved entries first.', 'info');
            return;
        }

        swal({
            title: "Are you sure you want to submit this projection for approval?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willCancel) => {

            
            if (willCancel) {

                var username = "{{session('user_staff')}}";
                var pernr = "{{session('pernr')}}";

                $.ajax({
                    url: "/submit_create_projection_forapproval?projdocnum="+projdocnum,
                    data: {
                        projdocnum : projdocnum,
                    },
                    type: 'POST',
                    headers: {
                            'X-CSRF-TOKEN': getCsrfToken() 
                    },
                    beforeSend: function() {
                        $('.submit_for_approval_btn, .selected_projection_id').prop('disabled', true);
                        showLoading();
                    },
                    success: function(data) {
                        console.log(data);
                        hideLoading();
                        var data = data[0];

                        if (data.status == "2") {

                                sweetalert("Refreshing...","Your projection has been submitted for approval", icon = 'success', timer = '6000', btn = false);
                                create_projection_customer_list_table(projdocnum)
                                get_minidashboard_pernr(pernr,projdocnum,username)
                            
                        

                        }
                        else if (data.status == '410') {
                            sweetalert(" ","No new projection to be submitted", icon = 'warning', timer = '5000', btn = false);
                        }
                        else {
                            swal("Oops...", "Please contact the administrator", "error");
                        }

                    },
                    error: function(data) {
                
                        swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                        hideLoading();

                    },
                    complete: function() {
                        $('.selected_projection_id').prop('disabled', false);
                        $('.submit_for_approval_btn').prop('disabled', !$('.selected_projection_id').val());
                        hideLoading();
                    }
                    
                });

            } 
            else {
            
                    
            }
        });

    });

    $(document).on('click','.rem-nr-projtn', function(e) {

        var customercode = $(this).data('customercode');
        var isbn = $(this).data('isbn');

        var trClosest = $(this).closest("tr");

        // var numtitles = $('.create_projection_'+customer+'_numtitles');
        // var numtitlesInt = parseInt(numtitles.text());
        
        // numtitles.text(numtitlesInt - 1);
        
        trClosest.remove();
        var className = '.'+customercode+''+isbn+'newprojectionrow';

        $(className).remove();

        updateProjectionValues();


    });

    $(document).on('click','.isbntable tbody',function (e) {
           var trClosest = $(this).closest('tr')
        //    var title_table = trClosest.find('.create_projection_branchwhouse_id_titletable');
           var title_table_branchwhouse = $('.create_projection_branchwhouse_id_titletable');

           if(title_table_branchwhouse.val() === '') {
                
             title_table_branchwhouse.addClass('blink-text border border-warning'),setTimeout( () => title_table_branchwhouse.removeClass('blink-text'),1000);
             title_table_branchwhouse.focus();
             title_table_branchwhouse[0].scrollIntoView();

              var html = "" 
                    + "<span class='text-warning fw-bold'>Please select branch or warehouse id</span>"
                    + "";

                toastifyShow(html)  

                
                //  return false;
           }


    });

    $(document).on('click','.add-new-op',function (e) {


        $.ajax({
           url:"/get_mainprojection_list", 
           type:'GET',
           headers: {
                   'X-CSRF-TOKEN': getCsrfToken() 
           },
           beforeSend: function() {
           
             
           },
           success:function(data){
               console.log(data);

               $('#add_new_projection_supplemental').empty()

               var opt = `   <option value="" disabled selected> Select in the list</option> `;

               for(i=0;i<data.length;i++) {
                        var d = data[i]
                        
                        var docnum = d.docnum
                        var projectionid = d.projectionid
                        var projectioniddisplay = d.projectioniddisplay

                        opt += `
                            <option value="`+docnum+`">`+projectioniddisplay+`</option>
                                    `;

                         
               }

               $('#add_new_projection_supplemental').append(opt)
               
           },

           error:function(data){
              
           }
           });

    });

    $(document).on('click','.projectionid-status-sw',function (e) {
        
        var docnum = $(this).data('docnum');

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
                        docnum : docnum,
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
                           
                        },
                           error:function(data){
                                 hideLoading();
                                
                                 swal("Oops...", "Something went wrong. Please contact your administrator", "error");
                        }
              });

    });
    $(document).on('change','.create_projection_branchwhouse_id_titletable ',function (e) {

        var v = $(this).val();
        var customercode = $('.isbnlist_customercode_val').val();
        var customername = $('.isbnlist_customername_val').val();
        var basedocnum = $('.selected_projection_id').val();
        
        $.ajax({
            url:"/update_projection_branchwhouse_customer", 
            data: {
                branchwhouse : v,
                customercode : customercode,
                basedocnum : basedocnum,
            },
            type:'POST',
            headers: {
                    'X-CSRF-TOKEN': getCsrfToken() 
            },
            beforeSend: function() {
            
            },
            success:function(data){
                console.log(data);

        
                
                },
                error:function(data){
                        hideLoading();
                        
                        swal("Oops...", "Something went wrong updating branch/whouse. Please contact your administrator", "error");
                }
    });


    })

    $(document).on('change','.selected_projection_id',function (e) {
        
        var v = $(this).val();
        $('.submit_for_approval_btn').prop('disabled', !v);
        var pernr = "{{session('pernr')}}";
        var username = "{{session('user_staff')}}";
        // showLoading('.table_saleshistory'), setTimeout( () => hideLoading('.table_saleshistory'),1000);

        $('.create_projection_add_new_customer_modal_btn').removeClass('d-none')
        create_projection_customer_list_table(v)

        get_minidashboard_pernr(pernr,v,username)
            
    });


    $(document).on('click','.projection_period_edit',function (e) {
         
         e.preventDefault();
     
            $('#projectionperiodeditcanvas').offcanvas('show')
    });
    
    $(document).on('click','.projection_type',function (e) {

        var value = $(this).val();

        if(value === 'supplemental') {
            $('.projection_period_select').removeClass('d-none')
        }
        else {
            $('.projection_period_select').addClass('d-none')
        }


    });


        
    
    $(document).on('submit', '.submit_reason_exceedqty', function(e) {

        e.preventDefault();
        var isbnremarks = $('.reason_exceedqty_remarks').val();
        // var customercode = $('.reason_exceedqty_customercode').val();
        var customercode = $('.isbnlist_customercode_val').val()
        var customername = $('.isbnlist_customername_val').val()
        var isbn = $('.reason_exceedqty_isbn').val();

        var $findIsbnEditingQtyExceed = ISBNTablefindIsbnEditRow(customercode, isbn);

        if ($findIsbnEditingQtyExceed.length) {

          
            var projdocnum = $('.selected_projection_id').val();
            var isbnremarksdisplay = $findIsbnEditingQtyExceed.find('.isbnremarkstooltip');
            var projtnisbnstatus = $findIsbnEditingQtyExceed.find('.create_new_projection_isbn_status');
            var projQty = $findIsbnEditingQtyExceed.find('.create_projection_projtn_qty');
            var title = $findIsbnEditingQtyExceed.find('.create_projection_isbn_title').val();
            var disc = $findIsbnEditingQtyExceed.find('.create_projection_isbn_disc').val();
            var linetotalinputvalue = $findIsbnEditingQtyExceed.find('.create_projection_isbn_linetotal');
            var linetotaltext = $findIsbnEditingQtyExceed.find('.create_projection_linetotal_amount_display');
            var populationqty = $findIsbnEditingQtyExceed.find('.create_projection_population_qty').val();
            var unitp = $findIsbnEditingQtyExceed.find('.create_projection_isbn_unitp').val();
            var lastyearsale = $findIsbnEditingQtyExceed.find('.create_projection_isbn_prev1_sales').val();
            var isbnsalestotalprev1 = $findIsbnEditingQtyExceed.find('.isbnsalestotalprev1').text().trim();
            var isbnsalestotalprev2 = $findIsbnEditingQtyExceed.find('.isbnsalestotalprev2').text().trim();
            var isbnsalestotalprev3 = $findIsbnEditingQtyExceed.find('.isbnsalestotalprev3').text().trim();
            var isbntotalbudget = $findIsbnEditingQtyExceed.find('.totalbudget_display').text().trim();

            var branchwhouse =  $('.create_projection_branchwhouse_id_titletable').val();
            var customerlinetotalvalue =  $('.'+ customercode + 'linetotal');
            var projtntotalvalue =  $('.'+ customercode + 'projtn');
            var customerisbnstatus =  $('.'+customercode+''+isbn+'customerisbnprojtnstatus');

            var unitpInt = parseInt(unitp) || 0;
            var populationqtyInt = parseInt(populationqty);
            var lastyearsaleInt = parseInt(lastyearsale);
            var projectionQty = parseInt(projQty.val()) || 0;
            var lastyearsaleInt110 = lastyearsaleInt * 0.1;
            var lastyearsaleIntAllowed = Math.round(lastyearsaleInt +  lastyearsaleInt110);
            var linetotal = projectionQty * unitpInt
            var linetotalDisplay = numberFormat(linetotal)
            

            $('.'+customercode+''+isbn+'newprojectionrow').remove();

            isbn_create_projection (customercode,customername,projdocnum,branchwhouse,isbn,title,unitpInt,disc,populationqtyInt,projectionQty,linetotal,isbnremarks,
                            isbnsalestotalprev1,
                            isbnsalestotalprev2,
                            isbnsalestotalprev3,
                            isbntotalbudget)
                            
            projQty
                .removeAttr('title')
                .attr('title', 'Exceeded: ' + isbnremarks)
                .removeClass('border border-warning')
                .addClass('border border-warning');
                
            // var commentIcon = JSshowCommentIcon(isbnremarks,'isbnremarkstooltip '+customercode+isbn+'isbnremarkstooltip');

            // isbnremarksdisplay.html(commentIcon)
            // tooltipReload();

            if( projectionQty > 0) {
                    
                        var html = "" 
                                + "<span class='text-success fw-bold'>Success!</span>"
                                + "";

                            toastifyShow(html)  
                
            }
            else {
                        var html = "" 
                                + "<span class='text-danger fw-bold'>Projection Removed!</span>"
                                + "";

                            toastifyShow(html)  
            }

            updateProjectionValues();

            $('.modal').modal('hide')


        } else {
           
        }

    });

    $(document).on('submit', '.currentprojectionupdate', function(e) {
        e.preventDefault();

        var projdocnum = $('.selected_projection_id').val();
        var savedraft = $('.savedraft').val()
        var submitforapproval = $('.submitforapproval').val()
 

        if($('.create_new_projection_isbn_customercode').length === 0 ) {
            console.log('No projection to be saved');
            return false;
        }
        var formData = new FormData(this);

        // ajaxInProgress = true;

 
        $.ajax({
            url: "/currentprojectionupdate?projdocnum="+projdocnum+"&savedraft="+savedraft+"&forapproval="+submitforapproval,
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
                        //  sweetalert("Refreshing page...","Your projection has been submitted. You can track it in View Projection Progress", icon = 'success', timer = '70000', btn = false);
                         sweetalert("Refreshing...","Your projection has been submitted for approval", icon = 'success', timer = '3000', btn = false);
                         create_projection_customer_list_table(projdocnum)
                         get_minidashboard_pernr(pernr,projdocnum,username)
                      
                         
                    }
                    else {

                        sweetalert("Saved...","Projection Successfully Saved!", icon = 'success', timer = '1000', btn = false);
                        
                    }

                  

                    $('.create_new_projection_isbn_status').each(function () {
                        if ($(this).val() === 'draft') {
                            $(this).val('saved');
                        }
                    });
                    // refreshPage(2500);
                    
                

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
