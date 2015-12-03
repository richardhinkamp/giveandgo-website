#!/usr/bin/env php
<?php

require_once( 'bootstrap.php' );

use Symfony\Component\Console\Application;

$application = new Application();

$application->setName("Give & Go console tool");
$application->setVersion($app->getVersion());
$application->add(new Giveandgo\Console\SyncTeamScore($app));
$application->add(new Giveandgo\Console\SyncTeamStandings($app));
$application->add(new BoltPicasa\Console\SyncPicasa($app));
$application->run();
