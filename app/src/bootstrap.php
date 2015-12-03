<?php

require_once(__DIR__ . "/../../vendor/autoload.php");
$configuration = new Bolt\Configuration\Composer(__DIR__ . '/../..');
$configuration->setPath("web", "web");
$configuration->setPath("files", "web/files");
$configuration->setPath("themebase", "web/theme");
//$configuration->getVerifier()->disableApacheChecks();
$configuration->verify();
$app = new Bolt\Application(array('resources'=>$configuration));
$app->initialize();
