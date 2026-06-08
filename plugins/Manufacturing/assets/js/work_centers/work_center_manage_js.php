
<script>

	(function($) {
		"use strict";

		var InvoiceServerParams={};
		var work_center_table = $('.table-work_center_table');

		initDataTable(work_center_table, "<?php echo get_uri("manufacturing/work_center_table") ?>",[0],[0], InvoiceServerParams, [0 ,'desc']);

		$('#date_add').on('change', function() {
			work_center_table.DataTable().ajax.reload();
		});


		var hidden_columns = [0];
		$('.table-work_center_table').DataTable().columns(hidden_columns).visible(false, false);
	})(jQuery);
</script>