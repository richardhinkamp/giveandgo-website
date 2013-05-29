<?php

require_once( '../app/src/bootstrap.php' );

$app->before(function () use ( $app) {
    /** @var $app Bolt\Application */

    /** @var $storage \Bolt\Storage */
    $storage = $app['storage'];
    /** @var $request \Symfony\Component\HttpFoundation\Request */
    $request = $app['request'];

    // Old information pages
    if ( preg_match( '#^/pagina/([a-z0-9\-\/]+)\.html$#', $request->getRequestUri(), $m ) ) {
        $newId = $content = null;
        switch( $m[1] ) {
            case 'algemeen/training-trainers':
                $newId = 'trainingen-en-trainers';
                break;
            case 'algemeen/gedragsregels':
                $newId = 'gedragsregels';
                break;
            case 'algemeen/laatste-uitslagen':
                $newId = 'laatste-uitslagen-van-give-and-go-teams';
                break;
            case 'algemeen/wedstrijden-aankomende-periode':
                $newId = 'wedstrijden-give-and-go-teams-komende-weken';
                break;
            case 'algemeen/lid-worden-van-give-and-go':
                $newId = 'lid-worden-van-basketbal-vereniging-give-and-go-uit-aalten';
                break;
            case 'algemeen/bestuur':
                $newId = 'bestuur';
                break;
            case 'algemeen/formulieren':
                $newId = 'formulieren';
                break;
            case 'algemeen/sporthal':
                $newId = 'sporthal-stationsplein';
                break;
            case 'algemeen/sporthallen-tegenstanders':
                $newId = 'sporthallen-tegenstanders';
                break;
            case 'algemeen/clubblad-archief':
                $newId = 'digitaal-archief-clubbladen';
                break;
            case 'wedstrijdschema':
            case 'disclaimer':
                $newId = $m[1];
                break;
        }

        if ($newId) {
            $content = $storage->getContent('pagina', array('slug' => $newId, 'returnsingle' => true));
        }

        if ($content) {
            return new Symfony\Component\HttpFoundation\RedirectResponse($content->link(), 301);
        }
    }

    // Old news items
    if ( preg_match( '#^/nieuws/([0-9]+)/(.+)\.html$#', $request->getRequestUri(), $m ) ) {

        $content = $storage->getContent('mededelingen', array('old_id' => $m[1], 'returnsingle' => true));
        if (!$content) {
            $content = $storage->getContent('wedstrijdverslagen', array('old_id' => $m[1], 'returnsingle' => true));
        }

        if ($content) {
            return new Symfony\Component\HttpFoundation\RedirectResponse($content->link(), 301);
        }
    }

    // Foto album
    if ( preg_match( '#^/foto-album(\.html|/.*)$#', $request->getRequestUri(), $m ) ) {
        return new Symfony\Component\HttpFoundation\RedirectResponse('/pagina/fotos', 301);
    }

    // Other old urls
    switch( $request->getRequestUri() )
    {
        case '/team.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/teams', 301);
        case '/team/1/heren.html':
        case '/team/1/wedstrijdverslagen/heren.html':
        case '/team/1/stand/heren.html':
        case '/team/1/uitslagen/heren.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/heren', 301);
        case '/team/3/dames.html':
        case '/team/3/wedstrijdverslagen/dames.html':
        case '/team/3/stand/dames.html':
        case '/team/3/uitslagen/dames.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/dames', 301);
        case '/team/5/u18-jongens.html':
        case '/team/5/wedstrijdverslagen/u18-jongens.html':
        case '/team/5/stand/u18-jongens.html':
        case '/team/5/uitslagen/u18-jongens.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/u18-jongens', 301);
        case '/team/13/u16-meisjes.html':
        case '/team/13/wedstrijdverslagen/u16-meisjes.html':
        case '/team/13/stand/u16-meisjes.html':
        case '/team/13/uitslagen/u16-meisjes.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/u16-meisjes', 301);
        case '/team/7/u14-mix.html':
        case '/team/7/wedstrijdverslagen/u14-mix.html':
        case '/team/7/stand/u14-mix.html':
        case '/team/7/uitslagen/u14-mix.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/u14-mix', 301);
        case '/team/9/u12-mix.html':
        case '/team/9/wedstrijdverslagen/u12-mix.html':
        case '/team/9/stand/u12-mix.html':
        case '/team/9/uitslagen/u12-mix.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/u12-mix', 301);
        case '/team/10/u10-mix.html':
        case '/team/10/wedstrijdverslagen/u10-mix.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/u10-mix', 301);
        case '/team/8/recreanten.html':
            return new Symfony\Component\HttpFoundation\RedirectResponse('/team/recreanten', 301);
    }

    return true;
}, 1000);

if ($app['debug']) {
    $app->run();
} else {
    /** @var $cache \Silex\HttpCache */
    $cache = $app['http_cache'];
    $cache->run();
}