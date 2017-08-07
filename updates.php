<?php

require_once dirname(WP_YMME).'/vendor/plugin-update-checker/plugin-update-checker.php';
$ymme_updates = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Capevace/mass-meta-editor/',
	__FILE__,
	'mass-meta-editor',
  12
);

//Optional: Set the branch that contains the stable release.
// $ymme_updates->setBranch('master');
