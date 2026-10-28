/* Audit Log page. Loaded only by resources/views/audit_log.blade.php. */
(() => {
    const table = document.getElementById('audit-log-table');
    if (!table || typeof window.jQuery === 'undefined') return;

    // Initialised directly (not via dTable) so the table grows with its rows instead of
    // reserving a fixed scroll height with one entry in it.
    $(table).DataTable({
        ajax: { url: '/datatable_audit_log', dataSrc: '' },
        columns: [
            { data: 'num' },
            { data: 'reference' },
            { data: 'userid' },
            { data: 'remarks' },
            { data: 'docdate' },
        ],
        order: [[4, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        autoWidth: false,
        destroy: true,
        language: {
            emptyTable: 'No password changes recorded yet.',
            search: '',
            searchPlaceholder: 'Search',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'No entries to show',
            lengthMenu: 'Show _MENU_ entries',
        },
        columnDefs: [{ targets: '_all', className: 'dt-center' }],
    });
})();
