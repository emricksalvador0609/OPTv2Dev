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
                <div class="card-body p-0">
                    <table id="audit-log-table" class="table table-sm table-hover align-middle mb-0 audit-log-table w-100">
                        <thead class="bg-soft">
                            <tr>
                                <th scope="col" class="text-600">#</th>
                                <th scope="col">Username</th>
                                <th scope="col">Employee no.</th>
                                <th scope="col">Action</th>
                                <th scope="col">Date and time</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptJS')
    <script src="{{ asset_v('assets/js/features/audit_log.js') }}"></script>
@endsection
