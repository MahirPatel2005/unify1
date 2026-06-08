<?php

namespace Manufacturing\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
	helper("manufacturing_general");
	helper("manufacturing_datatables");
	helper("manufacturing_convert_field");
	helper("notifications_helper");
});