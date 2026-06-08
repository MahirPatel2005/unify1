
<script>
	
	(function($) {
		"use strict";  

		var InvoiceServerParams={
			"bill_of_material_id": "[name='bill_of_material_id']",
			"bill_of_material_product_id": "[name='bill_of_material_product_id']",
			"bill_of_material_routing_id": "[name='bill_of_material_routing_id']",
		};
		var bill_of_material_detail_table = $('.table-bill_of_material_detail_table');

		initDataTable(bill_of_material_detail_table, "<?php echo get_uri("manufacturing/bill_of_material_detail_table") ?>",[0],[0], InvoiceServerParams, [1 ,'asc']);

		$('#date_add').on('change', function() {
			bill_of_material_detail_table.DataTable().ajax.reload().columns.adjust().responsive.recalc();
		});

		var hidden_columns = [1];
		$('.table-bill_of_material_detail_table').DataTable().columns(hidden_columns).visible(false, false);

	})(jQuery); 

	function add_component(bill_of_material_id, component_id, product_id, routing_id, type) {
		"use strict";

		$("#modal_wrapper").load("<?php echo site_url('manufacturing/bill_of_material_detail_modal'); ?>", {
			bill_of_material_id: bill_of_material_id,
			component_id: component_id,
			bill_of_material_product_id: product_id,
			routing_id: routing_id,
			type: type
		}, function() {

			$("body").find('#appointmentModal').modal('show');
		});

	}

	$('input[name="bom_type"]').on('click', function() {
		"use strict";
		
		var bom_type =$(this).val();

		if(bom_type == 'manufacture_this_product'){
			$('.kit_hide').addClass('hide');
		}else if(bom_type == 'kit'){
			$('.kit_hide').removeClass('hide');

		}
	});   

	function staff_bulk_actions(){
		"use strict";
		$('#bill_of_material_detail_table_bulk_actions').modal('show');
	}


	// Leads bulk action
	function staff_delete_bulk_action(event) {
		"use strict";

			var mass_delete = $('#mass_delete').prop('checked');

			if(mass_delete == true){
				var ids = [];
				var data = {};

				data.mass_delete = true;
				data.rel_type = 'component_bill_of_material';

				var rows = $('.table-bill_of_material_detail_table').find('tbody tr');
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
						$('#bill_of_material_detail_table_bulk_actions').modal('hide');
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