<?php

require_once dirname(WP_YMME).'/vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Capevace/mass-meta-editor/',
	__FILE__,
	'mass-meta-editor'
);

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
