<?php

if( is_dir(__DIR__ . '/../../public_html/') ) {
    define('BOLT_WEB_DIR', __DIR__ . '/../../public_html/');
} else {
    define('BOLT_WEB_DIR', __DIR__ . '/../../web/');
}

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../../vendor/zend/gdata/library'),
    get_include_path(),
)));

require_once( __DIR__ . '/../../vendor/bolt/bolt/app/bootstrap.php' );

$app['season'] = '2014-2015';
