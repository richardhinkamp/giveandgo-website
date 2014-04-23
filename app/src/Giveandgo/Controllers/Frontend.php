<?php

namespace Giveandgo\Controllers;

use Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Standard Frontend actions
 *
 * Strictly speaking this is no longer a controller, but logically
 * it still is.
 */
class Frontend
{
    public static function homepage(Silex\Application $app)
    {
        simpleredirect('/pagina/basketbal-clinic-henk-pieterse-10-mei');
    }

    public static function oldClinicUrl(Silex\Application $app)
    {
        self::homepage($app);
    }
}
