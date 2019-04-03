<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YandexMetrika;
use App\Charts\Metric;
use Carbon\Carbon;
use DatePeriod;
use DateTime;
use DateInterval;

class MetricaController extends Controller
{
    public function metrica()
    {
        $fromDefault = Carbon::now()->subDay(60)->format('Y-m-d');
        $toDefault = Carbon::now()->format('Y-m-d');
        $sourseTypeDefault = 'all';
        $goalIDDefault = '42740183';

        $from = (empty($_GET['from'])) ? $fromDefault : $_GET['from'];
        $to = (empty($_GET['to'])) ? $toDefault : $_GET['to'];
        if ($to < $from) {$from = $to;}
        $sourceType = (empty($_GET['sourceType'])) ? $sourseTypeDefault : $_GET['sourceType'];
        $goalID = (empty($_GET['goal'])) ? $goalIDDefault : $_GET['goal'];

        $st = YandexMetrika::getAllUsersWithTrafficSource($from, $to)->data['data'];
        $goals = YandexMetrika::getGoals($from, $to)->data['data'];
        $goalsValue = YandexMetrika::getThisGoalsValue($from, $to, $goalID)->data['data'];

//dd($goalsValue);

        if ($sourceType == 'search') {
            $source = 1;
            $denyID = 5;
            $firstGoalID = 1;
        } else if ($sourceType == 'AD') {
            $source = 2;
            $denyID = 6;
            $firstGoalID = 2;
        } else if ($sourceType == 'socialNetwork') {
            $source = 3;
            $denyID = 7;
            $firstGoalID = 3;
        } else {
            $source = 0;
            $denyID = 4;
            $firstGoalID = 0;
        }
//        dd($goals);

        for ($i = 0; $i < count($st); $i++) {
            $visitsForGraf[$st[$i]['dimensions'][0]['name']] = $st[$i]['metrics'][$source];
            $deny[$i] = $st[$i]['metrics'][$denyID];
        }

        if(!isset($visitsForGraf)){$visitsForGraf[] = 1;}


        for ($j = 0; $j < count($goals); $j++) {
            $goalsList[$goals[$j]['dimensions'][0]['name']] = $goals[$j]['dimensions'][0]['id'];
        }

//        dd($goals);
        $goalsVisit = ($goalsValue == null) ? 0 : $goalsValue[0]['metrics'][$firstGoalID];

            $denyPercent = (count($st) == 0)?0:number_format(array_sum($deny) / array_sum($visitsForGraf) * 100, 2, '.', '');
            $firstGoalPercent = (count($st) == 0)?0:number_format($goalsVisit / array_sum($visitsForGraf) * 100, 2, '.', '');


        $start = Carbon::create($from);
        $stop = Carbon::create($to);
        while ($start <= $stop) {
            $nulableArr[$start->format('Y-m-d')] = 0;
            $start = $start->addDay(1);
        }

        $VisitsForGrafWithZero = array_merge($nulableArr, $visitsForGraf);

        $graf = new Metric;
        $graf->labels(array_keys($VisitsForGrafWithZero));
        $graf->dataset('First', 'line', array_values($VisitsForGrafWithZero));

        return view('metrica', compact('graf', 'sourceType', 'denyPercent', 'firstGoalPercent', 'goalsList', 'from', 'to'));
    }

    public function test(){
        return view('test');
    }
}
