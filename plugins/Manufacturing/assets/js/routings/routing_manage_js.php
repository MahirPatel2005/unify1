<script>
	(function($) {
		"use strict"; 

		var InvoiceServerParams={};
		var routing_table = $('.table-routing_table');
		

		initDataTable(routing_table, "<?php echo get_uri("manufacturing/routing_table") ?>",[0],[0], InvoiceServerParams, [0 ,'desc']);

		$('#date_add').on('change', function() {
			routing_table.DataTable().ajax.reload();
		});

		var hidden_columns = [0];
		$('.table-routing_table').DataTable().columns(hidden_columns).visible(false, false);
		
	})(jQuery); 

	/**
	 * add routing
	 * @param {[type]} staff_id 
	 * @param {[type]} role_id  
	 * @param {[type]} add_new  
	 */
	 function add_routing(staff_id, role_id, add_new) {
		"use strict";

		$("#modal_wrapper").load("<?php echo site_url('manufacturing/routing_modal'); ?>", {
			slug: 'add',
		}, function() {
			$("body").find('#appointmentModal').modal('show');
			
		});

	 }

	</script>