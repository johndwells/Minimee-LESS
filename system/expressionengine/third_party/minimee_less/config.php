<?php
if (! defined('MINIMEE_LESS_VER'))
{
	define('MINIMEE_LESS_NAME', 'Minimee+LESS');
	define('MINIMEE_LESS_VER',  '1.0.2');
	define('MINIMEE_LESS_AUTHOR',  'John D Wells');
	define('MINIMEE_LESS_DOCS',  'http://johndwells.com/software/minimee-less');
	define('MINIMEE_LESS_DESC',  'A extension that brings LESS integration to Minimee.');
}

$config['name'] = MINIMEE_LESS_NAME;
$config['version'] = MINIMEE_LESS_VER;
$config['nsm_addon_updater']['versions_xml'] = 'http://johndwells.com/software/versions/minimee-less';