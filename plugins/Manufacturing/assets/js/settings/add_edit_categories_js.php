<script>
	(function($) {
		"use strict"; 

		var InvoiceServerParams={};

		var unit_of_measure_category_table = $('.table-unit_of_measure_category_table');
		

		initDataTable(unit_of_measure_category_table, "<?php echo get_uri("manufacturing/unit_of_measure_category_table") ?>",[0],[0], InvoiceServerParams, [0 ,'desc']);

		$('#date_add').on('change', function() {
			unit_of_measure_category_table.DataTable().ajax.reload();
		});

		var hidden_columns = [0];
		$('.table-unit_of_measure_category_table').DataTable().columns(hidden_columns).visible(false, false);
	})(jQuery);
	
	function new_category(){
		"use strict";

		$('#measure_category').modal('show');
		$('.edit-title').addClass('hide');
		$('.add-title').removeClass('hide');
		$('#categories_id').html('');

		$('#measure_category input[name="category_name"]').val('');
	}

	function edit_category(invoker,id){
		"use strict";

		$('#measure_category').modal('show');
		$('.edit-title').removeClass('hide');
		$('.add-title').addClass('hide');

		$('#categories_id').html('');
		$('#categories_id').append(hidden_input('id',id));

		$('#measure_category input[name="category_name"]').val($(invoker).data('category_name'));


	}
</script>