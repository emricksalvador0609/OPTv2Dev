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
                    <h4 class="mb-0">Password changes</h4>
                    <p class="mb-0 text-600 fs--1">Every completed password change, newest first.</p>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive scrollbar">
                        <table id="audit-log-table" class="table mb-0 audit-log-table">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-600">#</th>
                                    <th>Username</th>
                                    <th>Employee no.</th>
                                    <th>Action</th>
                                    <th>Date and time</th>
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
