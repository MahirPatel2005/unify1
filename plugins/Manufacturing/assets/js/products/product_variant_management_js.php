<script>
	$(function(){
		'use strict';
		var ProposalServerParams = {
			"item_filter": "[name='item_filter[]']",
			"product_type_filter": "[name='product_type_filter[]']",
			"product_category_filter": "[name='product_category_filter[]']",
		};

		var product_variant_table = $('table.table-product_variant_table');
				

		var _table_api = initDataTable(product_variant_table, "<?php echo get_uri("manufacturing/product_variant_table") ?>", [0], [0], ProposalServerParams,  [1, 'desc']);
		$.each(ProposalServerParams, function(i, obj) {
			$('select' + obj).on('change', function() {  
				product_variant_table.DataTable().ajax.reload();
			});
		});

		$(".select2").select2();

	});

	function staff_bulk_actions(){
		"use strict";
		$('#product_variant_table_bulk_actions').modal('show');
	}


	// Leads bulk action
	function warehouse_delete_bulk_action(event) {
		"use strict";

		var mass_delete = $('#mass_delete').prop('checked');

		if(mass_delete == true){
			var ids = [];
			var data = {};

			data.mass_delete = true;
			data.rel_type = 'commodity_list';

			var rows = $('.table-product_variant_table').find('tbody tr');
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
					$('#product_variant_table_bulk_actions').modal('hide');
					appAlert.warning(data.responseText)
					
				});
			}, 200);
		}else{
			window.location.reload();
		}

	}

	function print_barcode_option(invoker) {
		"use strict";
		var data={};
		data.profit_rate_by_purchase_price_sale = invoker.value;

		if(invoker.value == 1){
			$('.display-select-item').removeClass('hide');
		}else if(invoker.value == 0){
			$('.display-select-item').addClass('hide');
		}
	}


	/*print barcode*/
	function print_barcode_bulk_actions(){
		"use strict";
		$('.display-select-item').addClass('hide');
		$("#y_opt_1_").prop("checked", true);

		$("#table_commodity_list_print_barcode option:selected").prop("selected", false).change()

		$('#table_commodity_list_print_barcode').modal('show');
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