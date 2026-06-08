
<script>

	"use strict";

	(function($) {
		"use strict";  

		$(".select2").select2();
		

		var InvoiceServerParams={
			"products_filter": "[name='products_filter[]']",
			"bom_type_filter": "[name='bom_type_filter[]']",
			"routing_filter": "[name='routing_filter[]']",
		};

		var bill_of_material_table = $('.table-bill_of_material_table');
		

		initDataTable(bill_of_material_table, "<?php echo get_uri("manufacturing/bill_of_material_table") ?>",[0],[0], InvoiceServerParams, [0,'desc']);

		$.each(InvoiceServerParams, function(i, obj) {
			$('select' + obj).on('change', function() {  
				bill_of_material_table.DataTable().ajax.reload()
				.columns.adjust()
				.responsive.recalc();
			});
		});

		var hidden_columns = [1];
		$('.table-bill_of_material_table').DataTable().columns(hidden_columns).visible(false, false);

	})(jQuery); 

	/**
	 * add routing
	 * @param {[type]} staff_id 
	 * @param {[type]} role_id  
	 * @param {[type]} add_new  
	 */
	 function add_bill_of_material() {
		"use strict";

		$("#modal_wrapper").load("<?php echo site_url('manufacturing/bill_of_material_modal'); ?>", {
			slug: 'add',
		}, function() {

			$("body").find('#appointmentModal').modal('show');

		});

	 }

	 function staff_bulk_actions(){
		"use strict";
		$('#bill_of_material_table_bulk_actions').modal('show');
	 }


	// Leads bulk action
	function bom_delete_bulk_action(event) {
		"use strict";

		var mass_delete = $('#mass_delete').prop('checked');

		if(mass_delete == true){
			var ids = [];
			var data = {};

			data.mass_delete = true;
			data.rel_type = 'bill_of_material';

			var rows = $('.table-bill_of_material_table').find('tbody tr');
			$.each(rows, function() {
				var checkbox = $($(this).find('td').eq(0)).find('input');
				if (checkbox.prop('checked') === true) {
					ids.push(checkbox.val());
				}
			});

			data.ids = ids;
			$(event).addClass('disabled');
			setTimeout(function() {

				$.post("<?php echo get_uri("manufacturing/mrp_product_delete_bulk_action") ?>", data).done(function() {
					window.location.reload();
				}).fail(function(data) {
					$('#bill_of_material_table_bulk_actions').modal('hide');
					appAlert.warning(data.responseText)

				});
			}, 200);
		}else{
			window.location.reload();
		}

	}

	$("body").on('change', '#mass_select_all', function () {
		"use strict";

		var to, rows, checked;
		to = $(this).data('to-table');

		rows = $('.table-' + to).find('tbody tr');
		checked = $(this).prop('checked');
		$.each(rows, function () {
			$($(this).find('td').eq(0)).find('input').prop('checked', checked);
		});
	});


</script>