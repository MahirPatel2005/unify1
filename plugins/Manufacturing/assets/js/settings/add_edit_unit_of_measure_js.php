<script>

	(function($) {
		"use strict";  

		$(".select2").select2();
	})(jQuery);

	$('select[name="unit_measure_type"]').on('change', function() {
	 	"use strict";  

		
		var type =$(this).val();
		if(type == 'bigger'){
			$('.smaller_ratio_hide').addClass('hide');
			$('.bigger_ratio_hide').removeClass('hide');
		}else if(type == 'smaller'){
			$('.bigger_ratio_hide').addClass('hide');
			$('.smaller_ratio_hide').removeClass('hide');

		}else{
			$('.smaller_ratio_hide').addClass('hide');
			$('.bigger_ratio_hide').addClass('hide');
		}
	});
</script>