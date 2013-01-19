<?php

namespace Local\Controllers;

use Silex;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Forwarders extends \Bolt\Controllers\Frontend
{
    public function connect(Silex\Application $app)
    {
        $ctr = $app['controllers_factory'];

        $ctr->match('/nieuws/{id}/{slug}.html', array($this, 'newsForwarder'))
            ->before(array($this, 'before'))
            ->assert('id', '\d*')
        ;

        return $ctr;
    }

    function newsForwarder(Silex\Application $app, $id)
    {

        // First, try to get it by slug.
        $content = $app['storage']->getContent('mededelingen', array('old_id' => $id, 'returnsingle' => true));
        if (!$content) {
            $content = $app['storage']->getContent('wedstrijdverslagen', array('old_id' => $id, 'returnsingle' => true));
        }

        if ($content) {
            return new RedirectResponse($content->link(), 301);
        }

        $app->abort(404, "Page " . $app['request']->getRequestUri() . " not found.");

    }

}
