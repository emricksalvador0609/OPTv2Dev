/* Audit Log page. Loaded only by resources/views/audit_log.blade.php. */
(() => {
    const table = document.getElementById('audit-log-table');
    if (!table || typeof window.jQuery === 'undefined') return;

    const escape = (value) => $('<div>').text(value === null || value === undefined ? '' : value).html();
    const attribute = (value) => escape(value).replace(/"/g, '&quot;').replace(/'/g, '&#39;');

    // Server-side paging: the browser only receives the rows it shows.
    const dataTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 400,
        ajax: {
            url: '/datatable_audit_log',
            data: (query) => {
                query.from = document.getElementById('audit-from')?.value || '';
                query.to = document.getElementById('audit-to')?.value || '';
            },
        },
        columns: [
            { data: 'num', orderable: false },
            { data: 'reference' },
            { data: 'userid' },
            { data: 'event' },
            { data: 'remarks', orderable: false },
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
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        autoWidth: false,
        destroy: true,
        language: {
            emptyTable: 'No security events in this range.',
            search: '',
            searchPlaceholder: 'Search',
            processing: 'Loading...',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'No entries to show',
            infoFiltered: '(filtered from _MAX_ total entries)',
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

    // Date window. Empty means the server default: last 90 days.
    const reload = () => dataTable.ajax.reload();
    document.getElementById('audit-apply')?.addEventListener('click', reload);
    ['audit-from', 'audit-to'].forEach((id) => {
        document.getElementById(id)?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                reload();
            }
        });
    });
})();
