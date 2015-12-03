<?php

require_once(__DIR__ . "/../../vendor/autoload.php");
$configuration = new Bolt\Configuration\Composer(__DIR__ . '/../..');
$dir = 'web';
if( is_dir(__DIR__ . "/../../public_html") ) {
    $dir = 'public_html';
}
$configuration->setPath("web", "$dir");
$configuration->setPath("files", "$dir/files");
$configuration->setPath("themebase", "$dir/theme");
//$configuration->getVerifier()->disableApacheChecks();
$configuration->verify();
$app = new Bolt\Application(array('resources'=>$configuration));
$app->initialize();
