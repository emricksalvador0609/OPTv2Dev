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
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Password changes</h4>
                            <p class="mb-0 text-600 fs--1">Every completed password change, newest first.</p>
                        </div>
                        <form class="d-flex gap-2" method="GET" action="{{ route('audit_log') }}" role="search">
                            <div class="input-group input-group-sm">
                                <input class="form-control" type="search" name="q" value="{{ $search }}"
                                       placeholder="Username or employee no." aria-label="Search the audit log">
                                <button class="btn btn-primary" type="submit">Search</button>
                                @if($search !== '')
                                    <a class="btn btn-phoenix-secondary" href="{{ route('audit_log') }}">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($entries->isEmpty())
                        <p class="text-center text-600 py-4 mb-0">No password changes recorded yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0 audit-log-table">
                                <thead class="bg-soft">
                                    <tr>
                                        <th scope="col" class="text-600">#</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Employee no.</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Date and time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $entry)
                                        @php($changedAt = $entry->DOCDATE ? \Carbon\Carbon::parse($entry->DOCDATE) : null)
                                        <tr>
                                            <td class="text-600">{{ $entries->firstItem() + $loop->index }}</td>
                                            <td class="fw-semibold">{{ $entry->REFERENCE }}</td>
                                            <td>{{ $entry->USERID }}</td>
                                            <td>{{ $entry->REMARKS }}</td>
                                            <td class="text-nowrap">{{ $changedAt ? $changedAt->format('d M Y H:i') : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @if($entries->hasPages())
                    <div class="card-footer bg-soft border-top border-300 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <span class="text-600 fs--1">
                            Showing {{ $entries->firstItem() }}–{{ $entries->lastItem() }} of {{ $entries->total() }}
                        </span>
                        {{ $entries->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scriptJS')
    <script src="{{ asset_v('assets/js/features/audit_log.js') }}"></script>
@endsection
