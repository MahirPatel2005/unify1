<script>
	
	(function($) {
		"use strict";  

		$("#product_id,#unit_id,#apply_on_variants,#operation_id").select2();
	})(jQuery); 


	$('select[name="product_id"]').on('change', function() {
		"use strict";
		
		var product_id =$(this).val();


		$.get("<?php echo get_uri("manufacturing/get_product_variants/") ?>" + product_id, function (response) {

			$("select[name='unit_id']").val(response.unit_id).change();

		}, 'json');

	});

</script>