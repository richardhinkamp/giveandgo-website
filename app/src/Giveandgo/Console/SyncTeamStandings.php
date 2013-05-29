<?php

namespace Giveandgo\Console;

use Bolt\Nut\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Guzzle\Service\Client;
use Symfony\Component\DomCrawler\Crawler;

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
        $this->guzzleclient = new Client('http://west.basketball.nl/db/wedstrijd/stand.pl');
        $teams = $this->app['storage']->getContent('teams',array());
        foreach($teams as $team) {
            if($team->get('nbb_team_id')>0 && $team->get('nbb_competitie_id')>0) {
                //$output->writeln("<info>".$team->get('title')."</info>");
                $this->doTeam($team->get('team_id'),$team->get('nbb_competitie_id'),$team->get('nbb_team_id'));
            }
        }
    }

    protected function doTeam($teamId,$nbbCompId) {
        $html = $this->guzzleclient->get('?szn_Naam='.$this->app['season'].'&cmp_ID='.$nbbCompId.'&menubalk=off&title=off&link=off&grafiek=off')->send()->getBody(true);
        if ($html) {
            $standings = $this->app['storage']->getContent('standings',array('limit'=>9999),$p=array(),array('team_id'=>$teamId,'season'=>$this->app['season']));
            $crawler = new Crawler($html);
            $tdVals = $crawler->filter('table > tr > td')->extract(array('_text'));
            $baseGame = new \Bolt\Content($this->app,'standings',array());
            $baseGame->values['team_id'] = $teamId;
            $baseGame->values['season'] = $this->app['season'];
            $baseGame->values['slug'] = '';
            $baseGame->values['username'] = 'richard';
            $baseGame->values['status'] = 'published';
            $game = clone($baseGame);
            foreach($tdVals as $idx=>$val) {
                if ($idx>0) { // contains all td text??
                    switch($idx%7) {
                        case 1:
                            $game->values['place'] = trim($val);
                            break;
                        case 2:
                            $game->values['team_name'] = trim($val);
                            break;
                        case 3:
                            $game->values['played'] = trim($val);
                            break;
                        case 4:
                            $game->values['points'] = trim($val);
                            break;
                        case 5:
                            $game->values['goal_difference'] = trim($val);
                            break;
                        case 6:
                            if(preg_match('/^([0-9]+) \- ([0-9]+)/',trim($val),$m)) {
                                $game->values['goals_scored'] = $m[1];
                                $game->values['goals_allowed'] = $m[2];
                            }
                            break;
                        case 0:
                            $game->values['win_percentage'] = trim($val);
                            foreach($standings as $idx => $standing) {
                                if($standing->get('place')==$game->values['place']) {
                                    $game->values['id']=$standing->id;
                                    unset($standings[$idx]);
                                }
                            }
                            $this->app['storage']->saveContent($game);
                            $game = clone($baseGame);
                            break;
                    }
                }
            }
            foreach($standings as $standing) {
                $this->app['storage']->deleteContent('standings', $standing->id);
            }
        }
    }
}
