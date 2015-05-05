<?php

print('<h1>Standings</h1>');
require_once( '../../app/src/bootstrap.php' );

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

$job = new Giveandgo\Console\SyncTeamStandings($app);
$input = new ArrayInput(array());
$output = new BufferedOutput();
print('<p>Go!</p>');
var_dump($job->run($input, $output));
print('<pre>');
print($output->fetch());
print('</pre>');
print('<p>...done</p>');
