@extends('layouts.app')

@section('content')
    <div class="container">
        {{$sourceType}}<br>
        Процент отказов: {{$denyPercent}}%<br>

        Источник визитов:
        {{--<form method="GET">--}}
        <button form="getForm" name="sourceType" value="all">Все источники</button><br>
        <button form="getForm" name="sourceType" value="search">Из поиска</button><br>
        <button form="getForm" name="sourceType" value="AD">С рекалмы</button><br>
        <button form="getForm" name="sourceType" value="socialNetwork">С соцсетей</button><br>
        {{--</form>--}}

        {{--Количество уникальных посетителей за 60 дней: {{$users}}<br>--}}
        {{--Количество визитов за 60 дней: {{$visits}}<br>--}}
        {{--Количество отказов за 60 дней: {{$denay}}<br>--}}

        {{--Процент отказов: {{number_format($denay/$visits*100, 2, '.', '')}}%<br>--}}
        {{--<hr>--}}

        {{--@foreach($searchEngine as $search)--}}
        {{--{{$search[0]['dimensions']['0']['name']}}--}}
        {{--{{$search['dimensions']['0']['name']}} - {{$search['metric']['0']}}человек<br>--}}
        {{--@endforeach--}}
        {{--<div>--}}
            {{--@for($i = 0; $i<8; $i++)--}}
                {{--{{$searchEngine[$i]['dimensions']['0']['name']}} - {{$searchEngine[$i]['metrics']['0']}} человек<br>--}}
            {{--@endfor--}}
        {{--</div>--}}
        {{--<hr>--}}

        {{--@for($i = 0; $i<8; $i++)--}}
            {{--{{$traffic[$i]['dimensions']['0']['name']}} - {{$searchEngine[$i]['metrics']['0']}} человек<br>--}}
        {{--@endfor--}}

        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
        <div style="width: 100%; height: 350px">{!! $graf->container() !!}</div>

        {!! $graf->script() !!}

        Задать диапазон дат:
        <form id="getForm" method="GET">
            <input type="date" name="from" value=<?php if (isset($_GET['from'])) echo $_GET['from'] ?>
                    min="2012-05-29">
            <input type="date" name="to" value=<?php if (isset($_GET['to'])) echo $_GET['to'] ?>>
            <input type="submit" value="Send">
        </form>
    </div>
@endsection
