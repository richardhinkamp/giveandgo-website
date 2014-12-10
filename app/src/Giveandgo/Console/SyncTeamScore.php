<?php

namespace Giveandgo\Console;

use Bolt\Nut\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Guzzle\Service\Client;

// See: http://db.basketball.nl/help/koppelingen/json

class SyncTeamScore extends BaseCommand
{
    /**
     * @var Client
     */
    protected $guzzleclient;

    protected function configure()
    {
        $this
            ->setName('team:score')
            ->setDescription('Sync team scores');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->guzzleclient = new Client('http://db.basketball.nl/db/json/wedstrijd.pl');
        $teams = $this->app['storage']->getContent('teams',array());
        foreach($teams as $team) {
            if($team->get('nbb_team_id')>0 && $team->get('nbb_competitie_id')>0) {
                $this->doTeam($team->get('team_id'), $team->get('nbb_team_id'));
            }
        }
    }

    protected function doTeam($teamId, $nbbTeamId) {
        $json = $this->guzzleclient->get('?szn_Naam='.$this->app['season'].'&plg_ID='.$nbbTeamId)->send()->getBody(true);
        if ($json) {
            $scores = $this->app['storage']->getContent('scores',array('limit'=>9999),$p=array(),array('team_id'=>$teamId,'season'=>$this->app['season']));
            $baseGame = new \Bolt\Content($this->app,'scores',array());
            $baseGame->values['team_id'] = $teamId;
            $baseGame->values['season'] = $this->app['season'];
            $baseGame->values['slug'] = '';
            $baseGame->values['username'] = 'richard';
            $baseGame->values['status'] = 'published';
            $data = json_decode($json);
            foreach($data->wedstrijden as $val) {
                $game = clone($baseGame);
                $game->values['datetime'] = date('Y-m-d H:i:s', strtotime($val->datum));
                $game->values['team_home'] = $val->thuis_ploeg;
                $game->values['team_away'] = $val->uit_ploeg;
                $game->values['score_home'] = $val->score_thuis;
                $game->values['score_away'] = $val->score_uit;
                foreach($scores as $idx => $score) {
                    if($score->get('datetime')==$game->values['datetime']) {
                        $game->values['id']=$score->id;
                        unset($scores[$idx]);
                    }
                }
                $this->app['storage']->saveContent($game);
            }
            foreach($scores as $score) {
                $this->app['storage']->deleteContent('scores', $score->id);
            }
        }
    }
}
