@extends('layouts.admin_app')

@section('title') Audit Log @endsection

@section('pagestyle')
    <link href="{{ asset_v('assets/css/features/audit_log.css') }}" rel="stylesheet">
@endsection

@section('menutitle')
    Audit Log
@endsection

@section('belowcontent')
    <div class="row">
        <div class="col-12">
            <div class="card border border-300 shadow-none">
                <div class="card-header bg-soft border-bottom border-300">
                    <h4 class="mb-0">Security events</h4>
                    <p class="mb-0 text-600 fs--1">Password changes and sign-in activity, newest first.</p>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap gap-2 align-items-end mb-2 px-1">
                        <label class="mb-0 fs--1 text-600">From
                            <input type="date" id="audit-from" class="form-control form-control-sm">
                        </label>
                        <label class="mb-0 fs--1 text-600">To
                            <input type="date" id="audit-to" class="form-control form-control-sm">
                        </label>
                        <button type="button" id="audit-apply" class="btn btn-sm btn-primary">Apply</button>
                        <span class="text-600 fs--1 ms-2">Empty dates show the last 90 days.</span>
                    </div>
                    <div class="table-responsive scrollbar">
                        <table id="audit-log-table" class="table mb-0 audit-log-table">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-600">#</th>
                                    <th>Username</th>
                                    <th>Employee no.</th>
                                    <th>Event</th>
                                    <th>Details</th>
                                    <th>Date and time</th>
                                    <th>Action</th>
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
    <script src="{{ asset_v('assets/js/features/audit_log.js') }}"></script>
@endsection
