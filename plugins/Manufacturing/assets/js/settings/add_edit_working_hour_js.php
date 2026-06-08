<script>
	
	var working_hours;
	var global_time_off;


	(function($) {
		"use strict";  


		<?php if(isset($working_hour_details)){ ?>
			var dataObject_pu = <?php echo json_encode($working_hour_details) ; ?>;
		<?php }else{?>
			var dataObject_pu = <?php echo json_encode($working_hour_sample_data); ?>;
		<?php } ?>

		setTimeout(function(){

			var hotElement1 = document.getElementById('working_hour_hs');

			working_hours = new Handsontable(hotElement1, {
				licenseKey: 'non-commercial-and-evaluation',

				contextMenu: true,
				manualRowMove: true,
				manualColumnMove: true,
				stretchH: 'all',
				autoWrapRow: true,
				rowHeights: 30,
				defaultRowHeight: 100,
				minRows: 20,
				maxRows: 40,
				width: '100%',
				height: '500px',

				rowHeaders: true,
				colHeaders: true,
				autoColumnSize: {
					samplingRatio: 23
				},

				filters: true,
				manualRowResize: true,
				manualColumnResize: true,
				allowInsertRow: true,
				allowRemoveRow: true,
				columnHeaderHeight: 40,

				colWidths: [10,10,15,15,15,20,20,20,20],
				rowHeights: 30,
				rowHeaderWidth: [44],
				minSpareRows: 1,
				hiddenColumns: {
					columns: [0,1],
					indicators: true
				},

				columns: [
				{
					type: 'text',
					data: 'id',
				},
				{
					type: 'text',
					data: 'working_hour_id',
				},
				{
					type: 'text',
					data: 'working_hour_name',
				},
				{
					type: 'text',
					data: 'day_of_week',
					renderer: customDropdownRenderer,
					editor: "chosen",
					chosenOptions: {
						data: <?php echo json_encode($day_of_week_types); ?>
					},
				},
				{
					type: 'text',
					data: 'day_period',
					renderer: customDropdownRenderer,
					editor: "chosen",
					chosenOptions: {
						data: <?php echo json_encode($day_period_type); ?>
					}

				},
				{
					data: 'work_from',
					type: 'time',
					timeFormat: 'H:mm',
					correctFormat: true
				},
				{
					data: 'work_to',
					type: 'time',
					timeFormat: 'H:mm',
					correctFormat: true
				},


				{
					type: 'date',
					data: 'starting_date',
					dateFormat: 'YYYY-MM-DD',
					correctFormat: true,
					defaultDate: "<?php echo _d(date('Y-m-d')) ?>"
				},
				{
					type: 'date',
					data: 'end_date',
					dateFormat: 'YYYY-MM-DD',
					correctFormat: true,
					defaultDate: "<?php echo _d(date('Y-m-d')) ?>"
				},

				],

				colHeaders: [

				'<?php echo app_lang('id'); ?>',
				'<?php echo app_lang('working_hour_id'); ?>',
				'<?php echo app_lang('working_hour_name'); ?>',
				'<?php echo app_lang('day_of_week'); ?>',
				'<?php echo app_lang('day_period'); ?>',
				'<?php echo app_lang('work_from'); ?>',
				'<?php echo app_lang('work_to'); ?>',
				'<?php echo app_lang('starting_date'); ?>',
				'<?php echo app_lang('end_date'); ?>',

				],

				data: dataObject_pu,
			});

		},300);


	//global time off
	<?php if(isset($time_off)){ ?>
		var global_time_data = <?php echo json_encode($time_off) ; ?>;
	<?php }else{?>
		var global_time_data = [];
	<?php } ?>

	setTimeout(function(){
		var hotElement2 = document.getElementById('global_time_off_hs');

		global_time_off = new Handsontable(hotElement2, {
			licenseKey: 'non-commercial-and-evaluation',

			contextMenu: true,
			manualRowMove: true,
			manualColumnMove: true,
			stretchH: 'all',
			autoWrapRow: true,
			rowHeights: 30,
			defaultRowHeight: 100,
			minRows: 8,
			maxRows: 40,
			width: '100%',
			height: 300,


			rowHeaders: true,
			colHeaders: true,
			autoColumnSize: {
				samplingRatio: 23
			},

			filters: true,
			manualRowResize: true,
			manualColumnResize: true,
			allowInsertRow: true,
			allowRemoveRow: true,
			columnHeaderHeight: 40,

			rowHeights: 30,
			rowHeaderWidth: [44],
			minSpareRows: 1,
			hiddenColumns: {
				columns: [0,1],
				indicators: true
			},

			columns: [
			{
				type: 'text',
				data: 'id',
			},
			{
				type: 'text',
				data: 'working_hour_id',
			},
			{
				type: 'text',
				data: 'reason',
			},

			{
				type: 'date',
				data: 'starting_date',
				dateFormat: 'YYYY-MM-DD',
				correctFormat: true,
				defaultDate: "<?php echo _d(date('Y-m-d')) ?>"
			},
			{
				type: 'date',
				data: 'end_date',
				dateFormat: 'YYYY-MM-DD',
				correctFormat: true,
				defaultDate: "<?php echo _d(date('Y-m-d')) ?>"
			},

			],

			colHeaders: [

			'<?php echo app_lang('id'); ?>',
			'<?php echo app_lang('working_hour_id'); ?>',
			'<?php echo app_lang('working_time_reason'); ?>',
			'<?php echo app_lang('starting_date'); ?>',
			'<?php echo app_lang('end_date'); ?>',

			],

			data: global_time_data,
		});

	},300)

})(jQuery);

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
	"use strict";
	var selectedId;
	var optionsList = cellProperties.chosenOptions.data;

	if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
		Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
		return td;
	}

	var values = (value + "").split("|");
	value = [];
	for (var index = 0; index < optionsList.length; index++) {

		if (values.indexOf(optionsList[index].id + "") > -1) {
			selectedId = optionsList[index].id;
			value.push(optionsList[index].label);
		}
	}
	value = value.join(", ");

	Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	return td;
}

$('.add_working_hours').on('click', function() {
	'use strict';

	var working_hour_name = $('input[name="working_hour_name"]').val();
	var valid_working_hour = $('#working_hour_hs').find('.htInvalid').html();
	var valid_global_time_off = $('#global_time_off_hs').find('.htInvalid').html();

	if(working_hour_name.length == 0){
		appAlert.warning("<?php echo app_lang('Please_enter_name') ; ?>")

	}else if(valid_working_hour || valid_global_time_off){
		appAlert.warning("<?php echo app_lang('data_must_number') ; ?>")

	}else{

		$('input[name="working_hour_hs"]').val(JSON.stringify(working_hours.getData()));   
		$('input[name="global_time_off_hs"]').val(JSON.stringify(global_time_off.getData()));   
		$('#add_update_working_hour').submit(); 

	}
});
</script>