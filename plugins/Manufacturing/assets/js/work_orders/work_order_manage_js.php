
<script>

	(function($) {
		"use strict";

		$(".select2").select2();

		var InvoiceServerParams={
			"manufacturing_order_filter": "[name='manufacturing_order_filter[]']",
			"products_filter": "[name='products_filter[]']",
			"status_filter": "[name='status_filter[]']",
		};
		var work_order_table = $('.table-work_order_table');
		

		initDataTable(work_order_table, "<?php echo get_uri("manufacturing/work_order_table") ?>",[0],[0], InvoiceServerParams, [0 ,'desc']);

		var hidden_columns = [0];
		$('.table-work_order_table').DataTable().columns(hidden_columns).visible(false, false);

		$.each(InvoiceServerParams, function(i, obj) {
			$('select' + obj).on('change', function() {  
				work_order_table.DataTable().ajax.reload();
			});
		});
		
	})(jQuery);

</script>