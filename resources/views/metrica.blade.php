@extends('layouts.app')

@section('content')
    <div id="mtr" class="container">
        {{$sourceType}}<br>
        id: {{ config('yandex-metrika.counter_id') }}<br>
        Процент отказов: {{$denyPercent}}%<br>
        Процент конверсии по выбранной цели: {{$firstGoalPercent}}%<br>

        Источник визитов:<br>
        <select name="sourceType" form="getForm" onchange='this.form.submit()'>
            <option <?php if (isset($_GET['sourceType']) && ($_GET['sourceType'] == 'all')) echo 'selected' ?> value="all">Все источники</option>
            <option <?php if (isset($_GET['sourceType']) && ($_GET['sourceType'] == 'search')) echo 'selected' ?> value="search">Из поиска</option>
            <option <?php if (isset($_GET['sourceType']) && ($_GET['sourceType'] == 'AD')) echo 'selected' ?> value="AD">С рекламы</option>
            <option <?php if (isset($_GET['sourceType']) && ($_GET['sourceType'] == 'socialNetwork')) echo 'selected' ?> value="socialNetwork">С соцсетей</option>
        </select><br>

        Цель:<br>
        <select name="goal" form="getForm" onchange='this.form.submit()'>
           @isset($goalsList) @foreach($goalsList as $name => $id)
                <option <?php if (isset($_GET['goal']) && ($_GET['goal'] == $id)) echo 'selected' ?> value={{$id}}>{{$name}}</option>
                @endforeach
               @endisset
        </select><br>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="width: 100%; height: 350px">{!! $graf->container() !!}</div>

        {!! $graf->script() !!}

        Задать диапазон дат:
        <form id="getForm" method="GET">
            <input id="from" type="date" name="from" @change="changeDate"  v-model="modelFrom" :max="modelTo" value=<?php if (isset($_GET['from'])) echo $from ?> >
            <input id="to" type="date" name="to" @change="changeDate" v-model="modelTo"  value=<?php if (isset($_GET['to'])) echo $to ?> >
            <input id="sub" type="submit" value="Send" :disabled="fromMoreThenTo" >
            <p v-if="fromMoreThenTo" class="alert alert-danger">Дата "От" не может быть больше даты "До"</p>
        </form>
        @{{ message }}<br>
    </div>



@endsection
