
<script>

	(function($) {
		"use strict"; 

		var InvoiceServerParams={};

		var unit_of_measure_table = $('.table-unit_of_measure_table');


		initDataTable(unit_of_measure_table, "<?php echo get_uri("manufacturing/unit_of_measure_table") ?>",[0],[0], InvoiceServerParams, [0 ,'desc']);

		$('#date_add').on('change', function() {
			unit_of_measure_table.DataTable().ajax.reload();
		});


		var hidden_columns = [0];
		$('.table-unit_of_measure_table').DataTable().columns(hidden_columns).visible(false, false);
	})(jQuery);

	function add_edit_unit_measure(unit_id, type) {
		"use strict";

		$("#modal_wrapper").load("<?php echo site_url('manufacturing/unit_of_measure_modal'); ?>", {
			unit_id: unit_id,
			type: type
		}, function() {

			$("body").find('#appointmentModal').modal('show');
		});
	}
</script>
