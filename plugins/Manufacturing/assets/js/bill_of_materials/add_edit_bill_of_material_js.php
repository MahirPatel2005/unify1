<script>
	
	(function($) {
		"use strict";  

		$("#product_id,#product_variant_id,#unit_id,#routing_id,#ready_to_produce,#consumption").select2();
	})(jQuery); 

	$('input[name="bom_type"]').on('click', function() {
	"use strict";

		var bom_type =$(this).val();
		if(bom_type == 'manufacture_this_product'){
			$('.kit_hide').addClass('hide');
		}else if(bom_type == 'kit'){
			$('.kit_hide').removeClass('hide');

		}
	});

	$('select[name="product_id"]').on('change', function() {
	"use strict";
		
		var product_id =$(this).val();
		

		$.get("<?php echo get_uri("manufacturing/get_product_variants/") ?>" + product_id, function (response) {
			$("select[name='product_variant_id']").html('');

			$("select[name='product_variant_id']").append(response.product_variants);

			$("select[name='unit_id']").val(response.unit_id).change();


				
		}, 'json');

	});


</script>