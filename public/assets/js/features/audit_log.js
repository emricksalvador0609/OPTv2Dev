/* Audit Log page. Loaded only by resources/views/audit_log.blade.php. */
(() => {
    const table = document.getElementById('audit-log-table');
    if (!table || typeof window.jQuery === 'undefined') return;

    const escape = (value) => $('<div>').text(value === null || value === undefined ? '' : value).html();
    const attribute = (value) => escape(value).replace(/"/g, '&quot;').replace(/'/g, '&#39;');

    // Initialised directly (not via dTable) so the table grows with its rows instead of
    // reserving a fixed scroll height with one entry in it.
    const dataTable = $(table).DataTable({
        ajax: { url: '/datatable_audit_log', dataSrc: '' },
        columns: [
            { data: 'num' },
            { data: 'reference' },
            { data: 'userid' },
            { data: 'event' },
            { data: 'remarks' },
            { data: { _: 'docdate', sort: 'timestamp' } },
            {
                data: 'unlock',
                orderable: false,
                render: (unlockable, type, row) => {
                    if (!unlockable) return '';
                    return '<button type="button" class="btn btn-sm btn-outline-danger audit-unlock"'
                        + ' data-username="' + attribute(row.reference) + '"'
                        + ' data-ip="' + attribute(row.ip || '') + '">Unlock</button>';
                },
            },
        ],
        order: [[5, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        autoWidth: false,
        destroy: true,
        language: {
            emptyTable: 'No security events recorded yet.',
            search: '',
            searchPlaceholder: 'Search',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'No entries to show',
            lengthMenu: 'Show _MENU_ entries',
        },
        columnDefs: [{ targets: '_all', className: 'dt-center' }],
    });

    // Clear a lockout. The server writes an unlock marker; history is kept.
    $(table).on('click', '.audit-unlock', function () {
        const button = $(this);
        const username = button.data('username');
        if (!username) return;

        button.prop('disabled', true).text('Clearing...');

        $.ajax({
            url: '/audit-log/unlock',
            method: 'POST',
            data: { username: username, ip: button.data('ip') || '' },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        }).done(() => {
            dataTable.ajax.reload(null, false);
        }).fail(() => {
            button.prop('disabled', false).text('Unlock');
            window.alert('Could not clear the lockout. Please try again.');
        });
    });
})();
