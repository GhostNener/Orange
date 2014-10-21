<?php
header ( "Content-Type:text/html;charset=utf-8" );
if (version_compare ( PHP_VERSION, '5.3.0', '<' )) {
	die ( 'require PHP > 5.3.0 !' );
}
define ( 'APP_DEBUG', True );
define ( 'APP_NAME', 'Orange' );
define ( 'APP_PATH', './Apps/' );
define ( 'RUNTIME_PATH', './Runtime/' );
require './ORG/ThinkPHP/ThinkPHP.php';

