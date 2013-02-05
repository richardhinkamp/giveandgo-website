<?php

require_once( '../vendor/bobdenotter/bolt/app/bootstrap.php' );

$app->before(function () use ($app) {

    $content = $app['storage']->getContent('mededelingen', array('old_id' => $_GET['nws'], 'returnsingle' => true));
    if (!$content) {
        $content = $app['storage']->getContent('wedstrijdverslagen', array('old_id' => $_GET['nws'], 'returnsingle' => true));
    }

    if ($content) {
        return new Symfony\Component\HttpFoundation\RedirectResponse($content->link(), 301);
    }

    $app->abort(404, "Page " . $app['request']->getRequestUri() . " not found.");

}, 1000);

$app->run();