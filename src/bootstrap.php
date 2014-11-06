<?php

set_include_path(implode(PATH_SEPARATOR, array(
	realpath(__DIR__ . '/../vendor/zend/gdata/library'),
	get_include_path(),
)));

require_once( __DIR__ . '/../app/src/bootstrap.php' );