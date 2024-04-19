<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
/**
 * Included in application/views/admin/clients/client.php
 */
?>
<script>
document.addEventListener('DOMContentLoaded', (event) => {

    /* Customer profile invoices table */
    initDataTable('.table-call_logs-single-client',
        admin_url + 'call_logs/table/' + customer_id,
        'undefined',
        'undefined',
        'undefined', [
            [3, 'desc'],
            [0, 'desc']
        ]);

});
</script>
