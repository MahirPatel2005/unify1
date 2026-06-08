<script>

	$(function(){
		'use strict';
		$(".select2").select2();
		var addMoreVendorsInputKey;

	});

	$(document).ready(function () {
		'use strict';

		var uploadUrl = "<?php echo get_uri("items/upload_file"); ?>";
		var validationUri = "<?php echo get_uri("items/validate_items_file"); ?>";
		var dropzone = attachDropzoneWithForm("#items-dropzone", uploadUrl, validationUri);

		$("#item-form").appForm({
			ajaxSubmit: false,
			onSuccess: function (result) {
				if (window.refreshAfterUpdate) {
					window.refreshAfterUpdate = false;
					location.reload();
				} else {
					$("#item-table").appTable({newData: result.data, dataId: result.id});
				}
			}
		});

	});

	$(function(){
		'use strict';

		if($('#dropzoneDragArea').length > 0){
			expenseDropzone = new Dropzone("#add_update_product", appCreateDropzoneOptions({
				autoProcessQueue: false,
				clickable: '#dropzoneDragArea',
				previewsContainer: '.dropzone-previews',
				addRemoveLinks: true,
				maxFiles: 10,

				success:function(file,response){
					response = JSON.parse(response);
					if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

						if(response.add_variant == 'add_variant'){


							$.get("<?php echo get_uri("manufacturing/copy_product_image/") ?>" +response.id+'/'+response.rel_type, function (response1) {
								response1 = JSON.parse(response1);

								window.location.assign(response.url);
							});
						}else{
							window.location.assign(response.url);
						}

					}else{
						expenseDropzone.processQueue();

					}

				},

			}));
		}

	});

	Dropzone.options.expenseForm = false;

	//variation

	addMoreVendorsInputKey = $('.list_approve').length;
	$("body").on('click', '.new_wh_approval', function() {
		'use strict';

		if ($(this).hasClass('disabled')) { return false; }

		var newattachment = $('.list_approve').find('#item_approve').eq(0).clone().appendTo('.list_approve');
		newattachment.find('button[data-toggle="dropdown"]').remove();

		newattachment.find('button[data-id="name[0]"]').attr('data-id', 'name[' + addMoreVendorsInputKey + ']');
		newattachment.find('label[for="name[0]"]').attr('for', 'name[' + addMoreVendorsInputKey + ']');
		newattachment.find('input[name="name[0]"]').attr('name', 'name[' + addMoreVendorsInputKey + ']');
		newattachment.find('input[id="name[0]"]').attr('id', 'name[' + addMoreVendorsInputKey + ']').val('');

		newattachment.find('button[data-id="options[0]"]').attr('data-id', 'options[' + addMoreVendorsInputKey + ']');
		newattachment.find('label[for="options[0]"]').attr('for', 'options[' + addMoreVendorsInputKey + ']');
		newattachment.find('input[name="options[0]"]').attr('name', 'options[' + addMoreVendorsInputKey + ']');
		newattachment.find('input[id="options[0]"]').attr('id', 'options[' + addMoreVendorsInputKey + ']').val('');

		newattachment.find('button[name="add"] svg').removeClass('feather-plus-circle').addClass('feather-x-');
			newattachment.find('button[name="add"]').find('svg').empty('').html('<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>');
		newattachment.find('button[name="add"]').removeClass('new_wh_approval').addClass('remove_wh_approval').removeClass('btn-success').addClass('btn-danger');
		addMoreVendorsInputKey++;

	});
	$("body").on('click', '.remove_wh_approval', function() {
		'use strict';

		$(this).parents('#item_approve').remove();
	});


	$('input[name="can_be_sold"]').on('click', function() {
		'use strict';

		var can_be_sold =$('#can_be_sold').is(':checked');
		if(can_be_sold == true){
			$('.tab_sales_hide').removeClass('hide');
		}else{
			$('.tab_sales_hide').addClass('hide');
		}
	});


	$('input[name="can_be_purchased"]').on('click', function() {
		'use strict';

		var can_be_purchased =$('#can_be_purchased').is(':checked');
		if(can_be_purchased == true){
			$('.tab_purchase_hide').removeClass('hide');
		}else{
			$('.tab_purchase_hide').addClass('hide');
		}
	});


	function productSubmitHandler(form) {
		'use strict';
		
		var data={};
		data.formdata = $( form ).serializeArray();

		var sku_data ={};
		sku_data.sku_code =  $('input[name="sku_code"]').val();
		if($('input[name="id"]').val() != '' && $('input[name="id"]').val() != 0){
			sku_data.item_id =  $('input[name="id"]').val();
		}else{
			sku_data.item_id = '';
		}

		$.post("<?php echo get_uri("manufacturing/check_sku_duplicate") ?>", sku_data).done(function(response) {
			response = JSON.parse(response);

			if(response.message == 'false' || response.message ==  false){

				appAlert.warning("<?php echo app_lang('sku_code_already_exists') ?>")


			}else{

				//show box loading
				var html = '';
				html += '<div class="Box">';
				html += '<span>';
				html += '<span></span>';
				html += '</span>';
				html += '</div>';
				$('#box-loading').html(html);

				$('.submit_button').attr( "disabled", "disabled" );

				$.post(form.action, data).done(function(response) {
					var response = JSON.parse(response);
					if (response.commodityid) {
						if(typeof(expenseDropzone) !== 'undefined'){
							if (expenseDropzone.getQueuedFiles().length > 0) {
								if(response.add_variant){
									var add_variant = 'add_variant';
								}else{
									var add_variant = '';
								}

								expenseDropzone.options.url = "<?php echo get_uri("manufacturing/add_product_attachment/") ?>" + response.commodityid+'/'+response.rel_type+'/'+add_variant;
								expenseDropzone.processQueue();

							} else {
								window.location.assign(response.url);
							}
						} else {
							window.location.assign(response.url);
						}
					} else {
						window.location.assign(response.url);
					}
				});
			}

		});

		return false;

	}


	function delete_product_attachment(wrapper, attachment_id, rel_type) {
		"use strict";  
		
		

		$.get("<?php echo get_uri("manufacturing/delete_product_attachment/") ?>" +attachment_id+'/'+rel_type, function (response) {
			if (response.success == true) {
				$(wrapper).parents('.dz-preview').remove();

				var totalAttachmentsIndicator = $('.dz-preview'+attachment_id);
				var totalAttachments = totalAttachmentsIndicator.text().trim();

				if(totalAttachments == 1) {
					totalAttachmentsIndicator.remove();
				} else {
					totalAttachmentsIndicator.text(totalAttachments-1);
				}
				appAlert.success( "<?php echo app_lang('deleted_product_image_successfully') ?>");


			} else {
				appAlert.warning("<?php echo app_lang('deleted_product_image_failed') ?>")
			}
		}, 'json');

	}
	
</script>