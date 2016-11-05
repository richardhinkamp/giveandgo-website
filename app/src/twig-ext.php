<?php
/** @global $app \Bolt\Application */

$app['twig']->addFilter(new Twig_SimpleFilter('articleDateSort', function( array $articles ) {
    usort($articles, function( \Bolt\Content $a, \Bolt\Content $b ) {
        $dateA = new DateTime($a->get('date'));
        $dateB = new DateTime($b->get('date'));
        return $dateB->getTimestamp() - $dateA->getTimestamp();
    });
    return $articles;
}));

function getTeamFromResult( \Bolt\Content $result ) {
    $team = $result->get('team_home');
    if( stripos($team, 'Pytheas') === false && stripos($team, 'Give and Go') === false ) {
        $team = $result->get('team_away');
    }
    $teamId = trim(str_ireplace(array('Pytheas', 'Give and Go'), '', $team));
    list($teamCat, $teamNr) = explode(' ', $teamId);
    switch( $teamCat ) {
        case 'X2':
            $teamCat = 'U12';
            break;
        case 'X4':
            $teamCat = 'U14';
            break;
        case 'J8':
            $teamCat = 'U18J';
            break;
        case 'D0':
            $teamCat = 'U20M';
            break;
    }
    return $teamCat . ' ' . $teamNr;
}

$app['twig']->addFilter(new Twig_SimpleFilter('getTeamFromGameResult', 'getTeamFromResult'));

$app['twig']->addFilter(new Twig_SimpleFilter('getOpponentFromGameResult', function( \Bolt\Content $result ) {
    $team = $result->get('team_home');
    $homeAway = '@';
    if( stripos($team, 'Pytheas') !== false || stripos($team, 'Give and Go') !== false ) {
        $team = $result->get('team_away');
        $homeAway = 'vs';
    }
    $teamParts = explode(' ', trim($team));
    unset($teamParts[count($teamParts)-2]);
    return $homeAway . ' ' . implode(' ', $teamParts);
}));

$app['twig']->addFilter(new Twig_SimpleFilter('getWinLoseGameResult', function( \Bolt\Content $result ) {
    $team = $result->get('team_home');
    $ourScore = $result->get('score_home');
    $theirScore = $result->get('score_away');
    if( stripos($team, 'Pytheas') === false && stripos($team, 'Give and Go') === false ) {
        $ourScore = $result->get('score_away');
        $theirScore = $result->get('score_home');
    }
    if( $ourScore == 0 && $theirScore == 0 ) {
        return 'unplayed';
    }
    if( $ourScore == $theirScore ) {
        return 'tie';
    }
    if( $ourScore > $theirScore ) {
        return 'win';
    }
    return 'lose';
}));

$app['twig']->addFilter(new Twig_SimpleFilter('filterResultsHome', function( array $results ) {
    $played = array_filter($results, function( \Bolt\Content $result ) {
        return $result->get('score_home') != 0 || $result->get('score_away') != 0;
    });
    $teams = array_unique(array_map(function( \Bolt\Content $result ) {
        return getTeamFromResult($result);
    }, $played));
    $default = array_slice($played, 0, 5);
    $teamsDisplayed = array_unique(array_map(function( \Bolt\Content $result ) {
        return getTeamFromResult($result);
    }, $default));
    $addTeam = array();
    foreach( $teams as $team ) {
        if( array_search($team, $teamsDisplayed) === false ) {
            $addTeam[] = $team;
        }
    }
    $perTeam = array();
    foreach( $addTeam as $team ) {
        foreach( $played as $result ) {
            if( getTeamFromResult($result) == $team ) {
                $perTeam[] = $result;
                break;
            }
        }
    }
    return array_merge($default, $perTeam);
}));

$app['twig']->addFilter(new Twig_SimpleFilter('filterNextGamesHome', function( array $results ) {
    $notPlayedFuture = array_filter($results, function( \Bolt\Content $result ) {
        return (new DateTime($result->get('datetime')))->getTimestamp() > time();
    });
    $teams = array_unique(array_map(function( \Bolt\Content $result ) {
        return getTeamFromResult($result);
    }, $notPlayedFuture));
    $perTeam = array();
    foreach( $teams as $team ) {
        foreach( $notPlayedFuture as $result ) {
            if( getTeamFromResult($result) == $team ) {
                $perTeam[] = $result;
                break;
            }
        }
    }
    return $perTeam;
}));
