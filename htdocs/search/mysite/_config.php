<?php
global $project;
$project = 'mysite';

global $database;
$database = 'SS_ss2apisearch';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('ss2apisearch');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

Director::addRules(51, array(
	'opensearch/$Action/$ID/$OtherID' => 'SSAPISearchController'
));