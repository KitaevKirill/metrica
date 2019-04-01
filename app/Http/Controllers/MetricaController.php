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

        $from = (empty($_GET['from'])) ? $fromDefault : $_GET['from'];
        $to = (empty($_GET['to'])) ? $toDefault : $_GET['to'];
        $sourceType = (empty($_GET['sourceType'])) ? $sourseTypeDefault : $_GET['sourceType'];

//        $searchEngine = YandexMetrika::getVisitsUsersSearchEngine(60)->data['data'];
//        $users = YandexMetrika::getVisits(60)->data['data']['0']['metrics'][1];
//        $visits = YandexMetrika::getVisits(60)->data['data']['0']['metrics'][0];
//        $denay = YandexMetrika::getDenay()->data['data']['0']['metrics'][0];
//        $traffic = YandexMetrika::getTrafficSource(60)->data['data'];

//        $visitsForGraf = [];

//        $visitsViewsUsers = YandexMetrika::getVisitsViewsUsersForPeriod($from, $to)->data['data'];
//        $visitsFromSearch = YandexMetrika::getVisitsViewsUsersFromSearch($from, $to)->data['data'];

//dd($visitsFromSearch);
//        $st1 = YandexMetrika::getAllUsersWithTrafficSource($from, $to)->data['data'];
//        $st = YandexMetrika::getAllUsersWithTrafficSource($from, $to)->data['data'];
//        $st1 = YandexMetrika::getUsersWithTrafficSourceAD($from, $to)->data['data'][1]['metrics'][0];
        $st = YandexMetrika::getAllUsersWithTrafficSource($from, $to)->data['data'];

        if ($sourceType == 'search') {
            $source = 1;
            $denyID = 5;
        } else if ($sourceType == 'AD') {
            $source = 2;
            $denyID = 6;
        } else if ($sourceType == 'socialNetwork') {
            $source = 3;
            $denyID = 7;
        } else {
            $source = 0;
            $denyID = 4;
        }

        for ($i = 0; $i < count($st); $i++) {
            $visitsForGraf[$st[$i]['dimensions'][0]['name']] = $st[$i]['metrics'][$source];
            $deny[$i] = $st[$i]['metrics'][$denyID];
        }


        $denyPercent = number_format(array_sum($deny) / array_sum($visitsForGraf) * 100, 2, '.', '');

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

        return view('metrica', compact('graf', 'sourceType', 'denyPercent'));
//            ['graf' => $graf, 'sourceType' => $sourceType, 'denyPercent' => $denyPercent]);
//        'visits' => $visits, 'denay' => $denay, 'users' => $users, 'searchEngine' => $searchEngine, 'traffic' => $traffic,
    }
}
