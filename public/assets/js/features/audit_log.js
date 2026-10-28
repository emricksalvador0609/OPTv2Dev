/* Audit Log page. Loaded only by resources/views/audit_log.blade.php. */
(() => {
    const table = document.getElementById('audit-log-table');
    if (!table || typeof dTable !== 'function') return;

    dTable(
        $(table),
        '/datatable_audit_log',
        [
            { data: 'num' },
            { data: 'reference' },
            { data: 'userid' },
            { data: 'remarks' },
            { data: 'docdate' },
        ],
        500,
        'No password changes recorded yet.',
        true,
        null,
        true,
        0,
        0
    );
})();
