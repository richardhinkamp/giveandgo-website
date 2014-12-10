<?php

namespace Giveandgo\Console;

use Bolt\Nut\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Guzzle\Service\Client;

// See: http://db.basketball.nl/help/koppelingen/json

class SyncTeamStandings extends BaseCommand
{
    /**
     * @var Client
     */
    protected $guzzleclient;

    protected function configure()
    {
        $this
            ->setName('team:standings')
            ->setDescription('Sync team standings');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->guzzleclient = new Client('http://db.basketball.nl/db/json/stand.pl');
        $teams = $this->app['storage']->getContent('teams',array());
        foreach($teams as $team) {
            if($team->get('nbb_team_id')>0 && $team->get('nbb_competitie_id')>0) {
                $this->doTeam($team->get('team_id'),$team->get('nbb_competitie_id'),$team->get('nbb_team_id'));
            }
        }
    }

    protected function doTeam($teamId,$nbbCompId) {
        $json = $this->guzzleclient->get('?seizoen='.$this->app['season'].'&cmp_ID='.$nbbCompId)->send()->getBody(true);
        if ($json) {
            $standings = $this->app['storage']->getContent('standings',array('limit'=>9999),$p=array(),array('team_id'=>$teamId,'season'=>$this->app['season']));
            $baseGame = new \Bolt\Content($this->app,'standings',array());
            $baseGame->values['team_id'] = $teamId;
            $baseGame->values['season'] = $this->app['season'];
            $baseGame->values['slug'] = '';
            $baseGame->values['username'] = 'richard';
            $baseGame->values['status'] = 'published';
            $data = json_decode($json);
            foreach($data->stand as $val) {
                $game = clone($baseGame);
                $game->values['place'] = $val->positie;
                $game->values['team_name'] = $val->team;
                $game->values['played'] = $val->gespeeld;
                $game->values['points'] = $val->punten;
                $game->values['goal_difference'] = $val->saldo;
                $game->values['goals_scored'] = $val->eigenscore;
                $game->values['goals_allowed'] = $val->tegenscore;
                $game->values['win_percentage'] = $val->percentage;
                foreach($standings as $idx => $standing) {
                    if($standing->get('place')==$game->values['place']) {
                        $game->values['id']=$standing->id;
                        unset($standings[$idx]);
                    }
                }
                $this->app['storage']->saveContent($game);
            }
            foreach($standings as $standing) {
                $this->app['storage']->deleteContent('standings', $standing->id);
            }
        }
    }
}
