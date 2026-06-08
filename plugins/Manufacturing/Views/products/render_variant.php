	
<?php if(isset($product) && $product->parent_attributes != null && count(json_decode($product->parent_attributes)) > 0){ ?>
	<!-- update -->
	<?php 
	$p_variant = json_decode($product->parent_attributes);
	$variation_attr =[];
	$variation_attr['rows'] = '1';
	foreach ($p_variant as $key => $value) { ?>

		<div id="item_approve">
			<div class="col-md-11">
				<div class="row">
					<div class="col-md-4">
						<?php echo render_input1('name['.$key.']', 'variation_name', $value->name, 'text') ;?>
					</div>
					<div class="col-md-8">
						<div class="options_wrapper">
							<span class="pull-left fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="Populate the field by separating the options by coma. eq. apple,orange,banana"></span>

							<?php echo render_input1('options['.$key.']', 'variation_options', implode(",", $value->options) , 'text') ; ?>
						</div>
					</div>
				</div>
				<div class="col-md-1 new_vendor_requests_button" >
					<span class="pull-bot">
						<button name="add" class="btn   <?php if($key == 0){ echo " new_wh_approval btn-success" ;}else{ echo " remove_wh_approval btn-danger" ;}; ?>" data-ticket="true" type="button"><?php if($key == 0 ){ echo '<span data-feather="plus-circle" class="icon-16" ></span>';}else{ echo '<span data-feather="x" class="icon-16" ></span>';} ?></button>
					</span>
				</div>
			</div>
		</div>

	<?php } ?>


<?php }else{ ?>
	<!-- add new -->
	<div id="item_approve">
		<div class="col-md-11">

			<div class="row">
				<div class="col-md-4">
					<?php echo render_input1('name[0]','variation_name','', 'text'); ?>
				</div>
				<div class="col-md-8">
					<div class="options_wrapper">
						<?php 
						$variation_attr =[];
						$variation_attr['rows'] = '1';
						?>
						<span class="pull-left fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="Populate the field by separating the options by coma. eq. apple,orange,banana"></span>
						<?php echo render_input1('options[0]', 'variation_options', '', 'text'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1 new_vendor_requests_button">
			<span class="pull-bot">
				<button name="add" class="btn new_wh_approval btn-success" data-ticket="true" type="button"><span data-feather="plus-circle" class="icon-16" ></span></button>
			</span>
		</div>
	</div>
	<?php } ?>