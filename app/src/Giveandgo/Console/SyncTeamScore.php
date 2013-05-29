<?php

namespace Giveandgo\Console;

use Bolt\Nut\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Guzzle\Service\Client;
use Symfony\Component\DomCrawler\Crawler;

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
        $this->guzzleclient = new Client('http://west.basketball.nl/db/wedstrijd/uitslag.pl');
        $teams = $this->app['storage']->getContent('teams',array());
        foreach($teams as $team) {
            if($team->get('nbb_team_id')>0 && $team->get('nbb_competitie_id')>0) {
                //$output->writeln("<info>".$team->get('title')."</info>");
                $this->doTeam($team->get('team_id'),$team->get('nbb_competitie_id'),$team->get('nbb_team_id'));
            }
        }
    }

    protected function doTeam($teamId,$nbbCompId, $nbbTeamId) {
        $html = $this->guzzleclient->get('?szn_Naam='.$this->app['season'].'&cmp_ID='.$nbbCompId.'&Sorteer=wed_Datum&LVactie=Wedstrijdgegevens&plg_ID='.$nbbTeamId.'&menubalk=off&title=off&link=off&statistieken=off&hal=off&nummer=off&warning=off')->send()->getBody(true);
        if ($html) {
            $scores = $this->app['storage']->getContent('scores',array('limit'=>9999),$p=array(),array('team_id'=>$teamId,'season'=>$this->app['season']));
            $crawler = new Crawler($html);
            $tdVals = $crawler->filter('table > tr > td')->extract(array('_text'));
            $baseGame = new \Bolt\Content($this->app,'scores',array());
            $baseGame->values['team_id'] = $teamId;
            $baseGame->values['season'] = $this->app['season'];
            $baseGame->values['slug'] = '';
            $baseGame->values['username'] = 'richard';
            $baseGame->values['status'] = 'published';
            $game = clone($baseGame);
            foreach($tdVals as $idx=>$val) {
                if ($idx>0) { // contains all td text??
                    switch($idx%5) {
                        //case 1:
                        //    $game['date'] = $val;
                        //    break;
                        case 2:
                            $dt = new \DateTime($tdVals[$idx-1].' '.$val);
                            $game->values['datetime'] = date('Y-m-d H:i:s',$dt->getTimestamp());
                            break;
                        case 3:
                            $game->values['team_home'] = trim($val);
                            break;
                        case 4:
                            $game->values['team_away'] = trim($val);
                            break;
                        case 0:
                            if(preg_match('/^([0-9]+) \- ([0-9]+)/',$val,$m)) {
                                $game->values['score_home'] = $m[1];
                                $game->values['score_away'] = $m[2];
                                foreach($scores as $idx => $score) {
                                    if($score->get('datetime')==$game->values['datetime']) {
                                        $game->values['id']=$score->id;
                                        unset($scores[$idx]);
                                    }
                                }
                                $this->app['storage']->saveContent($game);
                                $game = clone($baseGame);
                            }
                            break;
                    }
                }
            }
            foreach($scores as $score) {
                $this->app['storage']->deleteContent('scores', $score->id);
            }
        }
    }
}
