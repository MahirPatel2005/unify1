
<script>
	(function($) {
		"use strict"; 

		var InvoiceServerParams={};

		var working_hour_table = $('.table-working_hour_table');

		initDataTable(working_hour_table, "<?php echo get_uri("manufacturing/working_hour_table") ?>",[0],[0], InvoiceServerParams, [0 ,'desc']);

		$('#date_add').on('change', function() {
			working_hour_table.DataTable().ajax.reload();
		});


		var hidden_columns = [0];
		$('.table-working_hour_table').DataTable().columns(hidden_columns).visible(false, false);
	})(jQuery); 
	
</script>
