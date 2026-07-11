@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])
@section('content')

    <link rel="stylesheet" href="{{ asset('public/css/style-dashboard.css')}}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-dashboard.css')}}">
    <link href="{{ asset('public/multi-select/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('public/multi-select/css/select2-bootstrap4.css')}}" rel="stylesheet" />

            <div class="ps-main__wrapper">

                @if (session('status'))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                                <strong>Success! </strong> {{ session('status') }}
                            </div>
                        </div>
                    </div>
                @endif


                @if (count($errors) > 0)
                    <div class = "alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div {{-- class="page-content" --}}>

                    <div class="container-fluid home-container">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Dasboard</h4>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Total Leads </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center">{{$leads_count}}</h4>
                                                <!--<div class="text-muted"> {{date('M')}} Month Leads </div>-->
                                            </div>
                                            <svg id="SvgjsSvg2564" width="270" height="50" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG2566" class="apexcharts-inner apexcharts-graphical" transform="translate(18, 0)"><defs id="SvgjsDefs2565"><linearGradient id="SvgjsLinearGradient2569" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2570" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop2571" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop2572" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMask136ztng1"><rect id="SvgjsRect2574" width="274" height="50" x="-16" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask136ztng1"></clipPath><clipPath id="nonForecastMask136ztng1"></clipPath><clipPath id="gridRectMarkerMask136ztng1"><rect id="SvgjsRect2575" width="246" height="54" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter2595" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feComponentTransfer id="SvgjsFeComponentTransfer2596" result="SvgjsFeComponentTransfer2596Out" in="SourceGraphic"><feFuncR id="SvgjsFeFuncR2597" type="linear" slope="0.5"></feFuncR><feFuncG id="SvgjsFeFuncG2598" type="linear" slope="0.5"></feFuncG><feFuncB id="SvgjsFeFuncB2599" type="linear" slope="0.5"></feFuncB><feFuncA id="SvgjsFeFuncA2600" type="identity"></feFuncA></feComponentTransfer></filter></defs><rect id="SvgjsRect2573" width="12.1" height="50" x="138.89999694824218" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2569)" class="apexcharts-xcrosshairs" y2="50" filter="none" fill-opacity="0.9" x1="138.89999694824218" x2="138.89999694824218"></rect><g id="SvgjsG2609" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2610" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2618" class="apexcharts-grid"><g id="SvgjsG2619" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2621" x1="-14" y1="0" x2="256" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2622" x1="-14" y1="12.5" x2="256" y2="12.5" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2623" x1="-14" y1="25" x2="256" y2="25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2624" x1="-14" y1="37.5" x2="256" y2="37.5" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2625" x1="-14" y1="50" x2="256" y2="50" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2620" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2627" x1="0" y1="50" x2="242" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2626" x1="0" y1="1" x2="0" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2576" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG2577" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0"><path id="SvgjsPath2581" d="M -6.05 50L -6.05 45.833333333333336Q -6.05 45.833333333333336 -6.05 45.833333333333336L 6.05 45.833333333333336Q 6.05 45.833333333333336 6.05 45.833333333333336L 6.05 45.833333333333336L 6.05 50L 6.05 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M -6.05 50L -6.05 45.833333333333336Q -6.05 45.833333333333336 -6.05 45.833333333333336L 6.05 45.833333333333336Q 6.05 45.833333333333336 6.05 45.833333333333336L 6.05 45.833333333333336L 6.05 50L 6.05 50z" pathFrom="M -6.05 50L -6.05 50L 6.05 50L 6.05 50L 6.05 50L 6.05 50L 6.05 50L -6.05 50" cy="45.833333333333336" cx="6.05" j="0" val="10" barHeight="4.166666666666667" barWidth="12.1"></path><path id="SvgjsPath2583" d="M 18.15 50L 18.15 41.666666666666664Q 18.15 41.666666666666664 18.15 41.666666666666664L 30.25 41.666666666666664Q 30.25 41.666666666666664 30.25 41.666666666666664L 30.25 41.666666666666664L 30.25 50L 30.25 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 18.15 50L 18.15 41.666666666666664Q 18.15 41.666666666666664 18.15 41.666666666666664L 30.25 41.666666666666664Q 30.25 41.666666666666664 30.25 41.666666666666664L 30.25 41.666666666666664L 30.25 50L 30.25 50z" pathFrom="M 18.15 50L 18.15 50L 30.25 50L 30.25 50L 30.25 50L 30.25 50L 30.25 50L 18.15 50" cy="41.666666666666664" cx="30.25" j="1" val="20" barHeight="8.333333333333334" barWidth="12.1"></path><path id="SvgjsPath2585" d="M 42.35 50L 42.35 43.75Q 42.35 43.75 42.35 43.75L 54.45 43.75Q 54.45 43.75 54.45 43.75L 54.45 43.75L 54.45 50L 54.45 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 42.35 50L 42.35 43.75Q 42.35 43.75 42.35 43.75L 54.45 43.75Q 54.45 43.75 54.45 43.75L 54.45 43.75L 54.45 50L 54.45 50z" pathFrom="M 42.35 50L 42.35 50L 54.45 50L 54.45 50L 54.45 50L 54.45 50L 54.45 50L 42.35 50" cy="43.75" cx="54.45" j="2" val="15" barHeight="6.25" barWidth="12.1"></path><path id="SvgjsPath2587" d="M 66.55 50L 66.55 33.33333333333333Q 66.55 33.33333333333333 66.55 33.33333333333333L 78.64999999999999 33.33333333333333Q 78.64999999999999 33.33333333333333 78.64999999999999 33.33333333333333L 78.64999999999999 33.33333333333333L 78.64999999999999 50L 78.64999999999999 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 66.55 50L 66.55 33.33333333333333Q 66.55 33.33333333333333 66.55 33.33333333333333L 78.64999999999999 33.33333333333333Q 78.64999999999999 33.33333333333333 78.64999999999999 33.33333333333333L 78.64999999999999 33.33333333333333L 78.64999999999999 50L 78.64999999999999 50z" pathFrom="M 66.55 50L 66.55 50L 78.64999999999999 50L 78.64999999999999 50L 78.64999999999999 50L 78.64999999999999 50L 78.64999999999999 50L 66.55 50" cy="33.33333333333333" cx="78.64999999999999" j="3" val="40" barHeight="16.666666666666668" barWidth="12.1"></path><path id="SvgjsPath2589" d="M 90.75 50L 90.75 41.666666666666664Q 90.75 41.666666666666664 90.75 41.666666666666664L 102.85 41.666666666666664Q 102.85 41.666666666666664 102.85 41.666666666666664L 102.85 41.666666666666664L 102.85 50L 102.85 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 90.75 50L 90.75 41.666666666666664Q 90.75 41.666666666666664 90.75 41.666666666666664L 102.85 41.666666666666664Q 102.85 41.666666666666664 102.85 41.666666666666664L 102.85 41.666666666666664L 102.85 50L 102.85 50z" pathFrom="M 90.75 50L 90.75 50L 102.85 50L 102.85 50L 102.85 50L 102.85 50L 102.85 50L 90.75 50" cy="41.666666666666664" cx="102.85" j="4" val="20" barHeight="8.333333333333334" barWidth="12.1"></path><path id="SvgjsPath2591" d="M 114.95 50L 114.95 29.166666666666664Q 114.95 29.166666666666664 114.95 29.166666666666664L 127.05 29.166666666666664Q 127.05 29.166666666666664 127.05 29.166666666666664L 127.05 29.166666666666664L 127.05 50L 127.05 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 114.95 50L 114.95 29.166666666666664Q 114.95 29.166666666666664 114.95 29.166666666666664L 127.05 29.166666666666664Q 127.05 29.166666666666664 127.05 29.166666666666664L 127.05 29.166666666666664L 127.05 50L 127.05 50z" pathFrom="M 114.95 50L 114.95 50L 127.05 50L 127.05 50L 127.05 50L 127.05 50L 127.05 50L 114.95 50" cy="29.166666666666664" cx="127.05" j="5" val="50" barHeight="20.833333333333336" barWidth="12.1"></path><path id="SvgjsPath2593" d="M 139.14999999999998 50L 139.14999999999998 20.833333333333332Q 139.14999999999998 20.833333333333332 139.14999999999998 20.833333333333332L 151.24999999999997 20.833333333333332Q 151.24999999999997 20.833333333333332 151.24999999999997 20.833333333333332L 151.24999999999997 20.833333333333332L 151.24999999999997 50L 151.24999999999997 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 139.14999999999998 50L 139.14999999999998 20.833333333333332Q 139.14999999999998 20.833333333333332 139.14999999999998 20.833333333333332L 151.24999999999997 20.833333333333332Q 151.24999999999997 20.833333333333332 151.24999999999997 20.833333333333332L 151.24999999999997 20.833333333333332L 151.24999999999997 50L 151.24999999999997 50z" pathFrom="M 139.14999999999998 50L 139.14999999999998 50L 151.24999999999997 50L 151.24999999999997 50L 151.24999999999997 50L 151.24999999999997 50L 151.24999999999997 50L 139.14999999999998 50" selected="true" filter="url(#SvgjsFilter2595)" cy="20.833333333333332" cx="151.24999999999997" j="6" val="70" barHeight="29.166666666666668" barWidth="12.1"></path><path id="SvgjsPath2602" d="M 163.35 50L 163.35 25Q 163.35 25 163.35 25L 175.45 25Q 175.45 25 175.45 25L 175.45 25L 175.45 50L 175.45 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 163.35 50L 163.35 25Q 163.35 25 163.35 25L 175.45 25Q 175.45 25 175.45 25L 175.45 25L 175.45 50L 175.45 50z" pathFrom="M 163.35 50L 163.35 50L 175.45 50L 175.45 50L 175.45 50L 175.45 50L 175.45 50L 163.35 50" cy="25" cx="175.45" j="7" val="60" barHeight="25" barWidth="12.1"></path><path id="SvgjsPath2604" d="M 187.54999999999998 50L 187.54999999999998 12.5Q 187.54999999999998 12.5 187.54999999999998 12.5L 199.64999999999998 12.5Q 199.64999999999998 12.5 199.64999999999998 12.5L 199.64999999999998 12.5L 199.64999999999998 50L 199.64999999999998 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 187.54999999999998 50L 187.54999999999998 12.5Q 187.54999999999998 12.5 187.54999999999998 12.5L 199.64999999999998 12.5Q 199.64999999999998 12.5 199.64999999999998 12.5L 199.64999999999998 12.5L 199.64999999999998 50L 199.64999999999998 50z" pathFrom="M 187.54999999999998 50L 187.54999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 187.54999999999998 50" cy="12.5" cx="199.64999999999998" j="8" val="90" barHeight="37.5" barWidth="12.1"></path><path id="SvgjsPath2606" d="M 211.74999999999997 50L 211.74999999999997 20.833333333333332Q 211.74999999999997 20.833333333333332 211.74999999999997 20.833333333333332L 223.84999999999997 20.833333333333332Q 223.84999999999997 20.833333333333332 223.84999999999997 20.833333333333332L 223.84999999999997 20.833333333333332L 223.84999999999997 50L 223.84999999999997 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 211.74999999999997 50L 211.74999999999997 20.833333333333332Q 211.74999999999997 20.833333333333332 211.74999999999997 20.833333333333332L 223.84999999999997 20.833333333333332Q 223.84999999999997 20.833333333333332 223.84999999999997 20.833333333333332L 223.84999999999997 20.833333333333332L 223.84999999999997 50L 223.84999999999997 50z" pathFrom="M 211.74999999999997 50L 211.74999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 211.74999999999997 50" cy="20.833333333333332" cx="223.84999999999997" j="9" val="70" barHeight="29.166666666666668" barWidth="12.1"></path><path id="SvgjsPath2608" d="M 235.95 50L 235.95 4.166666666666664Q 235.95 4.166666666666664 235.95 4.166666666666664L 248.04999999999998 4.166666666666664Q 248.04999999999998 4.166666666666664 248.04999999999998 4.166666666666664L 248.04999999999998 4.166666666666664L 248.04999999999998 50L 248.04999999999998 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 235.95 50L 235.95 4.166666666666664Q 235.95 4.166666666666664 235.95 4.166666666666664L 248.04999999999998 4.166666666666664Q 248.04999999999998 4.166666666666664 248.04999999999998 4.166666666666664L 248.04999999999998 4.166666666666664L 248.04999999999998 50L 248.04999999999998 50z" pathFrom="M 235.95 50L 235.95 50L 248.04999999999998 50L 248.04999999999998 50L 248.04999999999998 50L 248.04999999999998 50L 248.04999999999998 50L 235.95 50" cy="4.166666666666664" cx="248.04999999999998" j="10" val="110" barHeight="45.833333333333336" barWidth="12.1"></path><g id="SvgjsG2579" class="apexcharts-bar-goals-markers" style="pointer-events: none"><g id="SvgjsG2580" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2582" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2584" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2586" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2588" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2590" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2592" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2601" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2603" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2605" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2607" className="apexcharts-bar-goals-groups"></g></g></g><g id="SvgjsG2578" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2628" x1="-14" y1="0" x2="256" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2629" x1="-14" y1="0" x2="256" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2630" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2631" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2632" class="apexcharts-point-annotations"></g></g><g id="SvgjsG2617" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2567" class="apexcharts-annotations"></g></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Total Clients </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center">{{$clients_count}}</h4>
                                                <!--<div class="text-muted"> {{date('M')}} Month Clients </div>-->
                                            </div>
                                            <svg id="SvgjsSvg2634" width="270" height="50" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG2636" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs2635"><clipPath id="gridRectMaskvbaqto9o"><rect id="SvgjsRect2641" width="276" height="52" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskvbaqto9o"></clipPath><clipPath id="nonForecastMaskvbaqto9o"></clipPath><clipPath id="gridRectMarkerMaskvbaqto9o"><rect id="SvgjsRect2642" width="274" height="54" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient2647" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2648" stop-opacity="0.45" stop-color="rgba(3,142,220,0.45)" offset="0.5"></stop><stop id="SvgjsStop2649" stop-opacity="0.05" stop-color="rgba(255,255,255,0.05)" offset="1"></stop><stop id="SvgjsStop2650" stop-opacity="0.05" stop-color="rgba(255,255,255,0.05)" offset="1"></stop><stop id="SvgjsStop2651" stop-opacity="0.45" stop-color="rgba(3,142,220,0.45)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine2640" x1="-0.5" y1="0" x2="-0.5" y2="50" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="-0.5" y="0" width="1" height="50" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2654" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2655" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2667" class="apexcharts-grid"><g id="SvgjsG2668" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2670" x1="0" y1="0" x2="270" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2671" x1="0" y1="10" x2="270" y2="10" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2672" x1="0" y1="20" x2="270" y2="20" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2673" x1="0" y1="30" x2="270" y2="30" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2674" x1="0" y1="40" x2="270" y2="40" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2675" x1="0" y1="50" x2="270" y2="50" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2669" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2677" x1="0" y1="50" x2="270" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2676" x1="0" y1="1" x2="0" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2643" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG2644" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2652" d="M 0 50L 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30C 270 30 270 30 270 50M 270 30z" fill="url(#SvgjsLinearGradient2647)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskvbaqto9o)" pathTo="M 0 50L 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30C 270 30 270 30 270 50M 270 30z" pathFrom="M -1 50L -1 50L 30 50L 60 50L 90 50L 120 50L 150 50L 180 50L 210 50L 240 50L 270 50"></path><path id="SvgjsPath2653" d="M 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30" fill="none" fill-opacity="1" stroke="#038edc" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskvbaqto9o)" pathTo="M 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30" pathFrom="M -1 50L -1 50L 30 50L 60 50L 90 50L 120 50L 150 50L 180 50L 210 50L 240 50L 270 50"></path><g id="SvgjsG2645" class="apexcharts-series-markers-wrap" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2683" r="0" cx="0" cy="45" class="apexcharts-marker wquhj9eku no-pointer-events" stroke="#ffffff" fill="#038edc" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2646" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2678" x1="0" y1="0" x2="270" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2679" x1="0" y1="0" x2="270" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2680" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2681" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2682" class="apexcharts-point-annotations"></g></g><rect id="SvgjsRect2639" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2666" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2637" class="apexcharts-annotations"></g></svg>                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Total Affiliators </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span>{{$affiliator_count}}</span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Active: </label><span>{{$aaffiliator_count}}</span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>InActive: </label><span>{{$affiliator_count - $aaffiliator_count}}</span></h4>
                                                <!--<div class="text-muted"> {{date('M')}} Month Affiliators </div>-->
                                            </div>
                                            <svg id="SvgjsSvg2564" width="270" height="50" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG2566" class="apexcharts-inner apexcharts-graphical" transform="translate(18, 0)"><defs id="SvgjsDefs2565"><linearGradient id="SvgjsLinearGradient2569" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2570" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop2571" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop2572" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMask136ztng1"><rect id="SvgjsRect2574" width="274" height="50" x="-16" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask136ztng1"></clipPath><clipPath id="nonForecastMask136ztng1"></clipPath><clipPath id="gridRectMarkerMask136ztng1"><rect id="SvgjsRect2575" width="246" height="54" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter2595" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feComponentTransfer id="SvgjsFeComponentTransfer2596" result="SvgjsFeComponentTransfer2596Out" in="SourceGraphic"><feFuncR id="SvgjsFeFuncR2597" type="linear" slope="0.5"></feFuncR><feFuncG id="SvgjsFeFuncG2598" type="linear" slope="0.5"></feFuncG><feFuncB id="SvgjsFeFuncB2599" type="linear" slope="0.5"></feFuncB><feFuncA id="SvgjsFeFuncA2600" type="identity"></feFuncA></feComponentTransfer></filter></defs><rect id="SvgjsRect2573" width="12.1" height="50" x="138.89999694824218" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2569)" class="apexcharts-xcrosshairs" y2="50" filter="none" fill-opacity="0.9" x1="138.89999694824218" x2="138.89999694824218"></rect><g id="SvgjsG2609" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2610" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2618" class="apexcharts-grid"><g id="SvgjsG2619" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2621" x1="-14" y1="0" x2="256" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2622" x1="-14" y1="12.5" x2="256" y2="12.5" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2623" x1="-14" y1="25" x2="256" y2="25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2624" x1="-14" y1="37.5" x2="256" y2="37.5" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2625" x1="-14" y1="50" x2="256" y2="50" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2620" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2627" x1="0" y1="50" x2="242" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2626" x1="0" y1="1" x2="0" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2576" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG2577" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0"><path id="SvgjsPath2581" d="M -6.05 50L -6.05 45.833333333333336Q -6.05 45.833333333333336 -6.05 45.833333333333336L 6.05 45.833333333333336Q 6.05 45.833333333333336 6.05 45.833333333333336L 6.05 45.833333333333336L 6.05 50L 6.05 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M -6.05 50L -6.05 45.833333333333336Q -6.05 45.833333333333336 -6.05 45.833333333333336L 6.05 45.833333333333336Q 6.05 45.833333333333336 6.05 45.833333333333336L 6.05 45.833333333333336L 6.05 50L 6.05 50z" pathFrom="M -6.05 50L -6.05 50L 6.05 50L 6.05 50L 6.05 50L 6.05 50L 6.05 50L -6.05 50" cy="45.833333333333336" cx="6.05" j="0" val="10" barHeight="4.166666666666667" barWidth="12.1"></path><path id="SvgjsPath2583" d="M 18.15 50L 18.15 41.666666666666664Q 18.15 41.666666666666664 18.15 41.666666666666664L 30.25 41.666666666666664Q 30.25 41.666666666666664 30.25 41.666666666666664L 30.25 41.666666666666664L 30.25 50L 30.25 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 18.15 50L 18.15 41.666666666666664Q 18.15 41.666666666666664 18.15 41.666666666666664L 30.25 41.666666666666664Q 30.25 41.666666666666664 30.25 41.666666666666664L 30.25 41.666666666666664L 30.25 50L 30.25 50z" pathFrom="M 18.15 50L 18.15 50L 30.25 50L 30.25 50L 30.25 50L 30.25 50L 30.25 50L 18.15 50" cy="41.666666666666664" cx="30.25" j="1" val="20" barHeight="8.333333333333334" barWidth="12.1"></path><path id="SvgjsPath2585" d="M 42.35 50L 42.35 43.75Q 42.35 43.75 42.35 43.75L 54.45 43.75Q 54.45 43.75 54.45 43.75L 54.45 43.75L 54.45 50L 54.45 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 42.35 50L 42.35 43.75Q 42.35 43.75 42.35 43.75L 54.45 43.75Q 54.45 43.75 54.45 43.75L 54.45 43.75L 54.45 50L 54.45 50z" pathFrom="M 42.35 50L 42.35 50L 54.45 50L 54.45 50L 54.45 50L 54.45 50L 54.45 50L 42.35 50" cy="43.75" cx="54.45" j="2" val="15" barHeight="6.25" barWidth="12.1"></path><path id="SvgjsPath2587" d="M 66.55 50L 66.55 33.33333333333333Q 66.55 33.33333333333333 66.55 33.33333333333333L 78.64999999999999 33.33333333333333Q 78.64999999999999 33.33333333333333 78.64999999999999 33.33333333333333L 78.64999999999999 33.33333333333333L 78.64999999999999 50L 78.64999999999999 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 66.55 50L 66.55 33.33333333333333Q 66.55 33.33333333333333 66.55 33.33333333333333L 78.64999999999999 33.33333333333333Q 78.64999999999999 33.33333333333333 78.64999999999999 33.33333333333333L 78.64999999999999 33.33333333333333L 78.64999999999999 50L 78.64999999999999 50z" pathFrom="M 66.55 50L 66.55 50L 78.64999999999999 50L 78.64999999999999 50L 78.64999999999999 50L 78.64999999999999 50L 78.64999999999999 50L 66.55 50" cy="33.33333333333333" cx="78.64999999999999" j="3" val="40" barHeight="16.666666666666668" barWidth="12.1"></path><path id="SvgjsPath2589" d="M 90.75 50L 90.75 41.666666666666664Q 90.75 41.666666666666664 90.75 41.666666666666664L 102.85 41.666666666666664Q 102.85 41.666666666666664 102.85 41.666666666666664L 102.85 41.666666666666664L 102.85 50L 102.85 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 90.75 50L 90.75 41.666666666666664Q 90.75 41.666666666666664 90.75 41.666666666666664L 102.85 41.666666666666664Q 102.85 41.666666666666664 102.85 41.666666666666664L 102.85 41.666666666666664L 102.85 50L 102.85 50z" pathFrom="M 90.75 50L 90.75 50L 102.85 50L 102.85 50L 102.85 50L 102.85 50L 102.85 50L 90.75 50" cy="41.666666666666664" cx="102.85" j="4" val="20" barHeight="8.333333333333334" barWidth="12.1"></path><path id="SvgjsPath2591" d="M 114.95 50L 114.95 29.166666666666664Q 114.95 29.166666666666664 114.95 29.166666666666664L 127.05 29.166666666666664Q 127.05 29.166666666666664 127.05 29.166666666666664L 127.05 29.166666666666664L 127.05 50L 127.05 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 114.95 50L 114.95 29.166666666666664Q 114.95 29.166666666666664 114.95 29.166666666666664L 127.05 29.166666666666664Q 127.05 29.166666666666664 127.05 29.166666666666664L 127.05 29.166666666666664L 127.05 50L 127.05 50z" pathFrom="M 114.95 50L 114.95 50L 127.05 50L 127.05 50L 127.05 50L 127.05 50L 127.05 50L 114.95 50" cy="29.166666666666664" cx="127.05" j="5" val="50" barHeight="20.833333333333336" barWidth="12.1"></path><path id="SvgjsPath2593" d="M 139.14999999999998 50L 139.14999999999998 20.833333333333332Q 139.14999999999998 20.833333333333332 139.14999999999998 20.833333333333332L 151.24999999999997 20.833333333333332Q 151.24999999999997 20.833333333333332 151.24999999999997 20.833333333333332L 151.24999999999997 20.833333333333332L 151.24999999999997 50L 151.24999999999997 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 139.14999999999998 50L 139.14999999999998 20.833333333333332Q 139.14999999999998 20.833333333333332 139.14999999999998 20.833333333333332L 151.24999999999997 20.833333333333332Q 151.24999999999997 20.833333333333332 151.24999999999997 20.833333333333332L 151.24999999999997 20.833333333333332L 151.24999999999997 50L 151.24999999999997 50z" pathFrom="M 139.14999999999998 50L 139.14999999999998 50L 151.24999999999997 50L 151.24999999999997 50L 151.24999999999997 50L 151.24999999999997 50L 151.24999999999997 50L 139.14999999999998 50" selected="true" filter="url(#SvgjsFilter2595)" cy="20.833333333333332" cx="151.24999999999997" j="6" val="70" barHeight="29.166666666666668" barWidth="12.1"></path><path id="SvgjsPath2602" d="M 163.35 50L 163.35 25Q 163.35 25 163.35 25L 175.45 25Q 175.45 25 175.45 25L 175.45 25L 175.45 50L 175.45 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 163.35 50L 163.35 25Q 163.35 25 163.35 25L 175.45 25Q 175.45 25 175.45 25L 175.45 25L 175.45 50L 175.45 50z" pathFrom="M 163.35 50L 163.35 50L 175.45 50L 175.45 50L 175.45 50L 175.45 50L 175.45 50L 163.35 50" cy="25" cx="175.45" j="7" val="60" barHeight="25" barWidth="12.1"></path><path id="SvgjsPath2604" d="M 187.54999999999998 50L 187.54999999999998 12.5Q 187.54999999999998 12.5 187.54999999999998 12.5L 199.64999999999998 12.5Q 199.64999999999998 12.5 199.64999999999998 12.5L 199.64999999999998 12.5L 199.64999999999998 50L 199.64999999999998 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 187.54999999999998 50L 187.54999999999998 12.5Q 187.54999999999998 12.5 187.54999999999998 12.5L 199.64999999999998 12.5Q 199.64999999999998 12.5 199.64999999999998 12.5L 199.64999999999998 12.5L 199.64999999999998 50L 199.64999999999998 50z" pathFrom="M 187.54999999999998 50L 187.54999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 199.64999999999998 50L 187.54999999999998 50" cy="12.5" cx="199.64999999999998" j="8" val="90" barHeight="37.5" barWidth="12.1"></path><path id="SvgjsPath2606" d="M 211.74999999999997 50L 211.74999999999997 20.833333333333332Q 211.74999999999997 20.833333333333332 211.74999999999997 20.833333333333332L 223.84999999999997 20.833333333333332Q 223.84999999999997 20.833333333333332 223.84999999999997 20.833333333333332L 223.84999999999997 20.833333333333332L 223.84999999999997 50L 223.84999999999997 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 211.74999999999997 50L 211.74999999999997 20.833333333333332Q 211.74999999999997 20.833333333333332 211.74999999999997 20.833333333333332L 223.84999999999997 20.833333333333332Q 223.84999999999997 20.833333333333332 223.84999999999997 20.833333333333332L 223.84999999999997 20.833333333333332L 223.84999999999997 50L 223.84999999999997 50z" pathFrom="M 211.74999999999997 50L 211.74999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 223.84999999999997 50L 211.74999999999997 50" cy="20.833333333333332" cx="223.84999999999997" j="9" val="70" barHeight="29.166666666666668" barWidth="12.1"></path><path id="SvgjsPath2608" d="M 235.95 50L 235.95 4.166666666666664Q 235.95 4.166666666666664 235.95 4.166666666666664L 248.04999999999998 4.166666666666664Q 248.04999999999998 4.166666666666664 248.04999999999998 4.166666666666664L 248.04999999999998 4.166666666666664L 248.04999999999998 50L 248.04999999999998 50z" fill="rgba(3,142,220,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask136ztng1)" pathTo="M 235.95 50L 235.95 4.166666666666664Q 235.95 4.166666666666664 235.95 4.166666666666664L 248.04999999999998 4.166666666666664Q 248.04999999999998 4.166666666666664 248.04999999999998 4.166666666666664L 248.04999999999998 4.166666666666664L 248.04999999999998 50L 248.04999999999998 50z" pathFrom="M 235.95 50L 235.95 50L 248.04999999999998 50L 248.04999999999998 50L 248.04999999999998 50L 248.04999999999998 50L 248.04999999999998 50L 235.95 50" cy="4.166666666666664" cx="248.04999999999998" j="10" val="110" barHeight="45.833333333333336" barWidth="12.1"></path><g id="SvgjsG2579" class="apexcharts-bar-goals-markers" style="pointer-events: none"><g id="SvgjsG2580" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2582" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2584" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2586" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2588" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2590" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2592" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2601" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2603" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2605" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG2607" className="apexcharts-bar-goals-groups"></g></g></g><g id="SvgjsG2578" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2628" x1="-14" y1="0" x2="256" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2629" x1="-14" y1="0" x2="256" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2630" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2631" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2632" class="apexcharts-point-annotations"></g></g><g id="SvgjsG2617" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2567" class="apexcharts-annotations"></g></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Total Sales </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center">{{$leads_count}}</h4>
                                                <!--<div class="text-muted"> {{date('M')}} Month Sales </div>-->
                                            </div>
                                            <svg id="SvgjsSvg2634" width="270" height="50" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG2636" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs2635"><clipPath id="gridRectMaskvbaqto9o"><rect id="SvgjsRect2641" width="276" height="52" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskvbaqto9o"></clipPath><clipPath id="nonForecastMaskvbaqto9o"></clipPath><clipPath id="gridRectMarkerMaskvbaqto9o"><rect id="SvgjsRect2642" width="274" height="54" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient2647" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2648" stop-opacity="0.45" stop-color="rgba(3,142,220,0.45)" offset="0.5"></stop><stop id="SvgjsStop2649" stop-opacity="0.05" stop-color="rgba(255,255,255,0.05)" offset="1"></stop><stop id="SvgjsStop2650" stop-opacity="0.05" stop-color="rgba(255,255,255,0.05)" offset="1"></stop><stop id="SvgjsStop2651" stop-opacity="0.45" stop-color="rgba(3,142,220,0.45)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine2640" x1="-0.5" y1="0" x2="-0.5" y2="50" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="-0.5" y="0" width="1" height="50" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2654" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2655" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2667" class="apexcharts-grid"><g id="SvgjsG2668" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2670" x1="0" y1="0" x2="270" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2671" x1="0" y1="10" x2="270" y2="10" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2672" x1="0" y1="20" x2="270" y2="20" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2673" x1="0" y1="30" x2="270" y2="30" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2674" x1="0" y1="40" x2="270" y2="40" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2675" x1="0" y1="50" x2="270" y2="50" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2669" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2677" x1="0" y1="50" x2="270" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2676" x1="0" y1="1" x2="0" y2="50" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2643" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG2644" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2652" d="M 0 50L 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30C 270 30 270 30 270 50M 270 30z" fill="url(#SvgjsLinearGradient2647)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskvbaqto9o)" pathTo="M 0 50L 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30C 270 30 270 30 270 50M 270 30z" pathFrom="M -1 50L -1 50L 30 50L 60 50L 90 50L 120 50L 150 50L 180 50L 210 50L 240 50L 270 50"></path><path id="SvgjsPath2653" d="M 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30" fill="none" fill-opacity="1" stroke="#038edc" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskvbaqto9o)" pathTo="M 0 45C 10.5 45 19.5 5 30 5C 40.5 5 49.5 35 60 35C 70.5 35 79.5 20 90 20C 100.5 20 109.5 25 120 25C 130.5 25 139.5 5 150 5C 160.5 5 169.5 37.5 180 37.5C 190.5 37.5 199.5 22.5 210 22.5C 220.5 22.5 229.5 35 240 35C 250.5 35 259.5 30 270 30" pathFrom="M -1 50L -1 50L 30 50L 60 50L 90 50L 120 50L 150 50L 180 50L 210 50L 240 50L 270 50"></path><g id="SvgjsG2645" class="apexcharts-series-markers-wrap" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2683" r="0" cx="0" cy="45" class="apexcharts-marker wquhj9eku no-pointer-events" stroke="#ffffff" fill="#038edc" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2646" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2678" x1="0" y1="0" x2="270" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2679" x1="0" y1="0" x2="270" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2680" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2681" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2682" class="apexcharts-point-annotations"></g></g><rect id="SvgjsRect2639" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2666" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2637" class="apexcharts-annotations"></g></svg>                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec mb-4" >
                            <form class="ps-form--filter" action="{{url('users/sorting')}}" method="post" >
                                @csrf
                                @method('post')
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 custom_date_option_div">
                                    <div class="form-group">
                                        <label>Select Time Period </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <select class="form-control custom_date_option" id="change_option" name="time_period"  required >
                                                {{-- <option value="" selected disabled> Select </option> --}}
                                                <option <?php
                                                if (app('request')->input('time_period') == 'today') {
                                                    echo 'selected';
                                                }
                                                ?> value="today">Today</option>

                                                <option <?php
                                                if (app('request')->input('time_period') == 'this_week') {
                                                    echo 'selected';
                                                }
                                                ?> value="this_week">This Week</option>

                                                <option <?php
                                                if (app('request')->input('time_period') == 'last_week') {
                                                    echo 'selected';
                                                }
                                                ?> value="last_week">Last Week</option>

                                                <option <?php
                                                if (app('request')->input('time_period') == 'this_month') {
                                                    echo 'selected';
                                                }
                                                ?> value="this_month">This Month</option>
                                                <option <?php
                                                if (app('request')->input('time_period') == 'last_month') {
                                                    echo 'selected';
                                                }
                                                ?> value="last_month">Last Month</option>
                                                <option <?php
                                                if (app('request')->input('time_period') == 'three_month') {
                                                    echo 'selected';
                                                }
                                                ?> value="three_month">3 Months</option>
                                                <option <?php
                                                if (app('request')->input('time_period') == 'six_month') {
                                                    echo 'selected';
                                                }
                                                ?> value="six_month"> 6 Months</option>
                                                <option <?php
                                                if (app('request')->input('time_period') == 'this_year') {
                                                    echo 'selected';
                                                }
                                                ?> value="this_year">This Year</option>
                                                <option
                                                    <?php
                                                        if (app('request')->input('time_period') == 'custom_date') {echo 'selected';}
                                                    ?>
                                                    value="custom_date" >Custome Date</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom_date_div pl-15" style="display: none;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label> Start Date </label>
                                                <div class="bootstrap-select fm-cmp-mg">
                                                    <input type="text" readonly name="startDate" value="{{app('request')->input('startDate')}}" class="{{-- datepicker --}} form-control start_date_input" placeholder="Enter Start Date" >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label> End Date </label>
                                                <div class="bootstrap-select fm-cmp-mg" style="margin-left: 5px;">
                                                    <input type="text" readonly name="endDate"
                                                    value="{{app('request')->input('endDate')}}"  class="{{-- datepicker --}} form-control end_date_input" placeholder="Enter End Date" >
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#" class="graph_undo" style="font-size:16px"> Go Back <i class="fa fa-undo"></i></span></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-8 ">
                                    <div class="form-group">
                                        <label>Select User </label>
                                        <div class="bootstrap-select fm-cmp-mg border rounded">
                                            @php
                                            // Fetch selected user IDs from request or default to current user ID
                                            $selectedUserIds = app('request')->input('users', [Auth::user()->id]);

                                            // Check if 'all_active_user' is in the selected user IDs
                                            if (in_array('all_active_user', $selectedUserIds)) {
                                                $selectedUserIds = ['all_active_user']; // Set it to an array with just 'all_active_user'
                                            }
                                        @endphp

                                        <select name="users[]" class="form-control multiple-select" id="select-tag" multiple="multiple" data-placeholder="Choose anything" >
                                            @if (Auth::user()->role == 13 || Auth::user()->role == 14)
                                                <option {{ in_array('all_active_user', $selectedUserIds) ? 'selected' : '' }} value="all_active_user">All</option>
                                            @endif
                                            @foreach ($graph_users as $user)
                                                <option {{ in_array($user->id, $selectedUserIds) ? 'selected' : '' }} value="{{ $user->id }}" > {{ $user->name }} </option>
                                            @endforeach
                                        </select>


                                            {{-- <div class="row">
                                                <div class="col-xl-9 mx-auto">
                                                    <h6 class="mb-0 text-uppercase">Select2 Controls</h6>
                                                    <hr/>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="border p-3 rounded">
                                                                <!-- ================= -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Select2 Multiple Control</label>
                                                                    <select class="multiple-select" data-placeholder="Choose anything" multiple="multiple">
                                                                        <option value="United States" selected>United States</option>
                                                                        <option value="United Kingdom" selected>United Kingdom</option>
                                                                        <option value="Afghanistan" selected>Afghanistan</option>
                                                                        <option value="Aland Islands">Aland Islands</option>
                                                                        <option value="Albania">Albania</option>

                                                                    </select>
                                                                </div>

                                                                <!-- =============== -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                    <div class="form-group"><label>&nbsp; </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <input class="ps-btn success form-control" type="submit" name="action" value="Filter">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-3 col-xs-12">
                                    <div class="form-group"><label>&nbsp; </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <input class="ps-btn success form-control" style="padding: 0%" type="submit" name="action" value="PDF">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>


                        <?php

                            if ($user_target['set_sales_target' ] > 0){
                                $achive_sales_target = number_format(($user_target['achive_sales_target' ] * 100)/$user_target['set_sales_target' ] , 2) .' %';
                            }else {
                                $achive_sales_target =  number_format($user_target['set_sales_target' ] , 2) .' %';
                            }

                            if ($user_target['set_unit_target' ] > 0){
                                $achive_unit_target = number_format(($user_target['achive_unit_target' ] * 100)/$user_target['set_unit_target' ] , 2) .' %';
                            }else {
                                $achive_unit_target = number_format($user_target['set_unit_target' ] , 2) .' %';
                            }


                            if ($user_target['set_verified_calls_target' ] > 0){
                                $achive_verified_calls_target = number_format(($user_target['achive_verified_calls_target' ] * 100)/$user_target['set_verified_calls_target' ] , 2) .' %';
                            }else {
                                $achive_verified_calls_target = number_format($user_target['set_verified_calls_target' ] , 2) .' %';
                            }


                            if ($user_target['set_client_meetings_target' ] > 0){
                                $achive_client_meetings_target = number_format(($user_target['achive_client_meetings_target' ] * 100)/$user_target['set_client_meetings_target' ] , 2) .' %';
                            }else {
                                $achive_client_meetings_target = number_format($user_target['set_client_meetings_target' ] , 2) .' %';
                            }

                            if ($user_target['set_site_visit_target' ] > 0){
                                $achive_site_visit_target = number_format(($user_target['achive_site_visit_target' ] * 100)/$user_target['set_site_visit_target' ] , 2) .' %';
                            }else {
                                $achive_site_visit_target = number_format($user_target['set_site_visit_target' ] , 2) .' %';
                            }

                            if ($user_target['set_leads_target' ] > 0){
                                $achive_new_leads_target = number_format(($user_target['achive_new_leads_target' ] * 100)/$user_target['set_leads_target' ] , 2) .' %';
                            }else {
                                $achive_new_leads_target = number_format($user_target['set_leads_target' ] , 2) .' %';
                            }

                            if ($user_target['set_affiliator_target' ] > 0){
                                $achive_affiliator_target = number_format(($user_target['achive_affiliator_target' ] * 100)/$user_target['set_affiliator_target' ] , 2) .' %';
                            }else {
                                $achive_affiliator_target = number_format($user_target['set_affiliator_target' ] , 2) .' %';
                            }

                            if ($user_target['set_dealer_meetings_target' ] > 0){
                                $achive_dealer_meetings_target = number_format(($user_target['achive_dealer_meetings_target' ] * 100)/$user_target['set_dealer_meetings_target' ] , 2) .' %';
                            }else {
                                $achive_dealer_meetings_target = number_format($user_target['set_dealer_meetings_target' ] , 2) .' %';
                            }

                            if ($user_target['set_freelancer_meetings_target' ] > 0){
                                $achive_freelancer_meetings_target = number_format(($user_target['achive_freelancer_meetings_target' ] * 100)/$user_target['set_freelancer_meetings_target' ] , 2) .' %';
                            }else {
                                $achive_freelancer_meetings_target = number_format($user_target['set_freelancer_meetings_target' ] , 2) .' %';
                            }

                        ?>

                        <div class="row">

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> Total Units </h6>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_unit_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive:</label>
                                            <span>
                                                @if ($user_target['achive_unit_target' ] > 0)
                                                    <a href="{{ url('get-unit-ids/'.$user_target['achive_unit_target_unitids'])}}"> {{ number_format($user_target['achive_unit_target']) }}</a>
                                                @else
                                                    {{ number_format($user_target['achive_unit_target']) }}
                                                @endif

                                                </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_unit_target}}</span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_sale">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="font-size-xs text-uppercase"> Total Sales </h6>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_sales_target']). ' Rs' }} </span></h4>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive: </label><span> {{ number_format($user_target['achive_sales_target']). ' Rs' }} </span></h4>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span> {{  $achive_sales_target }}</span></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> Tokens </h6>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Token:</label> <span> {{ number_format($user_target['achive_number_of_token_target']) }} </span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> Contacted Calls </h6>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_verified_calls_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive:</label><span> {{ number_format($user_target['achive_verified_calls_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_verified_calls_target }}</span></h4>
                                        {{-- <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Today:</label> <span> {{ $user_target['today_achive_contacted_clients_target'] .'/'. number_format($user_target['today_set_verified_calls_target']) }} </span></h4> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> Client Meetings </h6>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_client_meetings_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive:</label><span> {{ number_format($user_target['achive_client_meetings_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_client_meetings_target }}</span></h4>
                                        {{-- <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Today:</label> <span> {{ $user_target['today_achive_client_meetings_target'] .'/'. number_format($user_target['today_set_client_meetings_target']) }} </span></h4> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> Site Vists </h6>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_site_visit_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive:</label><span> {{ number_format($user_target['achive_site_visit_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_site_visit_target }}</span></h4>
                                    </div>
                                </div>
                            </div>

                            <style>
                                /* .height_155{
                                    min-height:155px !important;
                                }
                                .height_143{
                                    min-height:143px !important;
                                }
                                .height_100{
                                    min-height:100px!important;
                                } */
                                .unset_padding{
                                    padding: unset;
                                }
                                .custom_label_32{
                                    width: 32% !important;
                                }

                                .graph_padding{
                                    padding: 5px;
                                }
                                .green{
                                    color: green;
                                }

                            </style>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_whole_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> Total Affiliators</h6>
                                        {{-- <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Total:</label> <span> {{ number_format($user_target['set_affiliator_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Achive :</label><span> {{ number_format($user_target['achive_affiliator_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Dealer :</label><span> {{ number_format($user_target['dealer']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Freelancer :</label><span> {{ number_format($user_target['freelancer']) }} </span></h4>--}}

                                        {{-- <div class="row">

                                            <div class="col-12" >
                                                <div class="col-sm-6 col-xl-6 unset_padding">
                                                    <h4 class="mt-4 font-weight-bold mb-2 "><label>Total:</label> <span> {{ number_format($user_target['set_affiliator_target']) }} </span></h4>
                                                </div>

                                                <div class="col-sm-6 col-xl-6">
                                                    <h4 class="mt-4 font-weight-bold mb-2 "><label>Achive :</label><span> {{ number_format($user_target['achive_affiliator_target']) }} </span></h4>
                                                </div>
                                            </div>

                                        </div> --}}
                                        <h4 class="mt-4 font-weight-bold mb-2 "><label>Total:</label> <span> {{ number_format($user_target['set_affiliator_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 "><label>Achive :</label><span> {{ number_format($user_target['achive_affiliator_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label_32">Dealer :</label><span> {{ number_format($user_target['dealer']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label_32">Freelancer :</label><span> {{ number_format($user_target['freelancer']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label class="custom_label">Percent:</label><span>{{ $achive_affiliator_target }}</span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card card-home main_total_unit">
                                    <div class="card-body">
                                        <h6 class="font-size-xs text-uppercase"> New Leads </h6>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_leads_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive:</label><span> {{ number_format($user_target['achive_new_leads_target']) }} </span></h4>
                                        <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_new_leads_target }}</span></h4>
                                    </div>
                                </div>
                            </div>



                            <div class="detail_btn" style="float: right; margin-bottom:10px;">
                                <input type="button" id="detail_toggle_btn" class="btn btn-default ps-btn success" value="More Details">
                            </div>


                            <div class="row detail_boxes_div" id="detail_boxes"  style="display:none">

                                <div class="col-sm-6 col-xl-6">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <h6 class="font-size-xs text-uppercase"> Dealer Meetings </h6>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_dealer_meetings_target']) }} </span></h4>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive :</label><span> {{ number_format($user_target['achive_dealer_meetings_target']) }} </span></h4>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_dealer_meetings_target }}</span></h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-6">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <h6 class="font-size-xs text-uppercase"> Freelancer Meetings </h6>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Total:</label> <span> {{ number_format($user_target['set_freelancer_meetings_target']) }} </span></h4>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label>Achive :</label><span> {{ number_format($user_target['achive_freelancer_meetings_target']) }} </span></h4>
                                            <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label>Percent:</label><span>{{ $achive_freelancer_meetings_target }}</span></h4>
                                        </div>
                                    </div>
                                </div>

                                {{-- <?php
                                    if ($user_target['overall_set_sales_target' ] > 0){
                                        $overall_achive_sales_target = number_format(($user_target['overall_achive_sales_target' ] * 100)/$user_target['overall_set_sales_target' ] , 2) .' %';
                                    }else {
                                        $overall_achive_sales_target = number_format($user_target['overall_set_sales_target' ] , 2) .' %';
                                    }

                                    if ($user_target['overall_set_unit_target' ] > 0){
                                        $overall_achive_unit_target = number_format(($user_target['overall_achive_unit_target' ] * 100)/$user_target['overall_set_unit_target' ] , 2) .' %';
                                    }else {
                                        $overall_achive_unit_target = number_format($user_target['overall_set_unit_target' ] , 2) .' %';
                                    }

                                    if ($user_target['overall_set_lead_target' ] > 0){
                                        $overall_achive_leads_target = number_format(($user_target['overall_achive_leads_target' ] * 100)/$user_target['overall_set_lead_target' ] , 2) .' %';
                                    }else {
                                        $overall_achive_leads_target = number_format($user_target['overall_set_lead_target' ] , 2) .' %';
                                    }

                                    if ($user_target['overall_set_verified_calls_target' ] > 0){
                                        $overall_achive_contacted_clients_target = number_format(($user_target['overall_achive_contacted_clients_target' ] * 100)/$user_target['overall_set_verified_calls_target' ] , 2) .' %';
                                    }else {
                                        $overall_achive_contacted_clients_target = number_format($user_target['overall_set_verified_calls_target' ] , 2) .' %';
                                    }


                                    if ($user_target['overall_set_client_meetings_target' ] > 0){
                                        $overall_achive_client_meetings_target = number_format(($user_target['overall_achive_client_meetings_target' ] * 100)/$user_target['overall_set_client_meetings_target' ] , 2) .' %';
                                    }else {
                                        $overall_achive_client_meetings_target = number_format($user_target['overall_set_client_meetings_target' ] , 2) .' %';
                                    }
                                ?> --}}
                                {{-- <div class="col-sm-6 col-xl-2">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Overall Sale </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Total:</label> <span> {{ number_format($user_target['overall_set_sales_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Achive :</label><span> {{ number_format($user_target['overall_achive_sales_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label class="custom_label">Percent:</label><span>{{ $overall_achive_sales_target }}</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-2">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Overall Units </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Total:</label> <span> {{ number_format($user_target['overall_set_unit_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Achive :</label><span> {{ number_format($user_target['overall_achive_unit_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label class="custom_label">Percent:</label><span>{{ $overall_achive_unit_target }}</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-2">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <div>
                                                <h6 class="font-size-xs text-uppercase"> Overall Leads </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Total:</label> <span> {{ number_format($user_target['overall_set_lead_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Achive :</label><span> {{ number_format($user_target['overall_achive_leads_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label class="custom_label">Percent:</label><span>{{ $overall_achive_leads_target }}</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <div style="min-height:100px;">
                                                <h6 class="font-size-xs text-uppercase"> Overall Contacted  Calls </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Total:</label> <span> {{ number_format($user_target['overall_set_verified_calls_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Achive :</label><span> {{ number_format($user_target['overall_achive_contacted_clients_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label class="custom_label">Percent:</label><span>{{ $overall_achive_contacted_clients_target }}</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-home main_whole_unit">
                                        <div class="card-body">
                                            <div style="min-height:100px;">
                                                <h6 class="font-size-xs text-uppercase"> Overall Client Meetings </h6>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Total:</label> <span> {{ number_format($user_target['overall_set_client_meetings_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center"><label class="custom_label">Achive :</label><span> {{ number_format($user_target['overall_achive_client_meetings_target']) }} </span></h4>
                                                <h4 class="mt-4 font-weight-bold mb-2 d-flex align-items-center green"><label class="custom_label">Percent:</label><span>{{ $overall_achive_client_meetings_target }}</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>


                        </div>

                    </div>



                        {{-- <div class="row">
                            <div class="col-xl-12">
                                <div class="ps-section__content">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mb-4">Today Todos</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table ps-table">
                                            <thead>
                                                <tr>
                                                    <th>Client</th>
                                                    <th>Lead ID</th>
                                                    <th>Allocated To</th>
                                                    <th>Deadline</th>
                                                    <th>#Task</th>
                                                    <th>Interest</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tasks as $record)
                                                <tr class="gradeX">
                                                    <td>
                                                        @if(!empty($record->client))
                                                        {{ $record->client->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span style="color: #fcb800;">{{ $record->lead_id }}</span></br>
                                                        {{date('M d, Y', strtotime($record->created_at))}}
                                                    </td>
                                                    <td>
                                                        {{ $record->addedBy->name }}
                                                    </td>
                                                    <td>
                                                        <span style="color: #e65c65 !important">{{ date('M d, Y', strtotime($record->deadline)) }} </span>
                                                    </td>
                                                    <td>
                                                        {{ $record->ntype }}
                                                    </td>
                                                    <td>
                                                        @if($record->leadsTask->project)
                                                        {{$record->leadsTask->project->name}}
                                                        @endif
                                                        @if(!empty($record->item))
                                                        <span style="color: #fcb800;">{{ $record->item->name }}</span></br> Buy Primary
                                                        @endif
                                                        @if(!empty($record->item) && $record->leadsTask->project)
                                                        Nothing
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="ps-section__content">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mb-4">Today Clients</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table ps-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($todayclients as $client)
                                                <tr>
                                                    <td>{{ $client->id }}</td>
                                                    <td>{{ $client->name }}</td>
                                                    <td>{{ $client->email }}</td>
                                                    <td>{{ $client->phone }}</td>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="ps-section__content">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mb-4">Today Leads</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table ps-table">
                                            <thead>
                                                <tr>
                                                    <th>Client</th>
                                                    <th>Lead-ID</th>
                                                    <th>Source</th>
                                                    <th>Interest</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($todayleads as $lead)
                                                <tr>
                                                    <td>
                                                        @if(!empty($lead->client))
                                                        {{ $lead->client->name }}
                                                        {{ $lead->client->leads_count()->count() }} Lead
                                                        @else
                                                        Nothing
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a style="color: #fcb800;" href="{{url('lead/edit',$lead->id)}}">
                                                            {{ $lead->id }}
                                                        </a>
                                                        </br>
                                                        {{date('M d, Y', strtotime($lead->created_at))}}
                                                    </td>
                                                    <td>
                                                        @if( $lead->client->leadSource->subtype == 'user' )
                                                        {{ $lead->client->leadSource->type }}
                                                        @else
                                                        {{ucfirst($lead->client->leadSource->subtype)}}
                                                        @if($lead->client->afflilate_id > 0)
                                                        <?php
                                                        $affiliator = $lead->client->leadAffiliator;
                                                        ?>
                                                        <br>
                                                        <div class="client-title tooltip">
                                                            <div class="tooltiptext client-info">
                                                                <div class="tooltip-head">Affiliator Information:</div>
                                                                <div class="inner-side"><label>Name: </label><span>{{ ucfirst($affiliator->name) }}</span></div>
                                                                <div class="inner-side"><label>Number: </label><span>{{ str_replace(' ','',$affiliator->phone) }}</span></div>
                                                                <div class="inner-side"><label>Email: </label><span>{{ $affiliator->email }}</span></div>
                                                            </div>
                                                            <a href="#" class="client-title">
                                                                <div class="title-flag">
                                                                    <strong>{{ ucfirst($affiliator->name )}}</strong>
                                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($lead->project_id > 0))
                                                        {{$lead->project->name}}
                                                        @endif
                                                        @if(!empty($lead->item))
                                                        {{$lead->item->name}}</br>
                                                        Buy Primar
                                                        @else
                                                        </br>Unit Not Selected
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                <div>
            </div>


            <div class="graph_btn" style="float: right; margin-bottom:50px;">
                <input type="button" id="graph_toggle_btn" class="btn btn-default ps-btn success" value="Show Graphs">
            </div>


            <div class="page-content main_graph_div" id="graphs"  style="display:none">

                {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec" >
                    <form class="ps-form--filter" action="{{url('users/sorting')}}" method="post" >
                        @csrf
                        @method('post')

                        <div class="col-xs-12 custom_date_option_div">
                            <div class="form-group">
                                <label>Select Time Period </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control custom_date_option" id="change_option" name="time_period"  required >

                                        <option <?php
                                        if (app('request')->input('time_period') == 'today') {
                                            echo 'selected';
                                        }
                                        ?> value="today">Today</option>

                                        <option <?php
                                        if (app('request')->input('time_period') == 'this_week') {
                                            echo 'selected';
                                        }
                                        ?> value="this_week">This Week</option>

                                        <option <?php
                                        if (app('request')->input('time_period') == 'last_week') {
                                            echo 'selected';
                                        }
                                        ?> value="last_week">Last Week</option>

                                        <option <?php
                                        if (app('request')->input('time_period') == 'this_month') {
                                            echo 'selected';
                                        }
                                        ?> value="this_month">This Month</option>
                                        <option <?php
                                        if (app('request')->input('time_period') == 'last_month') {
                                            echo 'selected';
                                        }
                                        ?> value="last_month">Last Month</option>
                                        <option <?php
                                        if (app('request')->input('time_period') == 'three_month') {
                                            echo 'selected';
                                        }
                                        ?> value="three_month">3 Months</option>
                                        <option <?php
                                        if (app('request')->input('time_period') == 'six_month') {
                                            echo 'selected';
                                        }
                                        ?> value="six_month"> 6 Months</option>
                                        <option <?php
                                        if (app('request')->input('time_period') == 'this_year') {
                                            echo 'selected';
                                        }
                                        ?> value="this_year">This Year</option>
                                        <option
                                            <?php
                                                if (app('request')->input('time_period') == 'custom_date') {echo 'selected';}
                                            ?>
                                            value="custom_date" >Custome Date</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="custom_date_div pl-15" style="display: none;">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label> Start Date </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <input type="text" readonly name="startDate" value="{{app('request')->input('startDate')}}" class="form-control start_date_input" placeholder="Enter Start Date" >
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label> End Date </label>
                                        <div class="bootstrap-select fm-cmp-mg" style="margin-left: 5px;">
                                            <input type="text" readonly name="endDate"
                                            value="{{app('request')->input('endDate')}}"  class="form-control end_date_input" placeholder="Enter End Date" >
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="graph_undo" style="font-size:16px"> Go Back <i class="fa fa-undo"></i></span></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Select User </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <?php
                                        if(app('request')->input('users') !="" ){
                                            $user_id = app('request')->input('users');
                                        }else {
                                            $user_id =Auth::user()->id;
                                        }
                                    ?>
                                    <select name="users" class="form-control multi_user">
                                        @foreach ($graph_users as $user)
                                            <option <?php if($user_id ==$user->id ) {echo 'selected';}  ?>  value="{{ $user->id }}" > {{ $user->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group"><label>&nbsp; </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">
                                </div>
                            </div>
                        </div>
                    </form>
                </div> --}}

                <div class="row target_charts">
                    <div class="col-sm-4 col-xs-4" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">Verified Calls</h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart1" class="graph_padding" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-4" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">Client Meetings</h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart2" class="graph_padding" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-4" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">Dealer Meetings</h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart3" class="graph_padding"  height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row target_charts">
                    <div class="col-sm-4 col-xs-4" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">Freelancer Meetings</h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart4" class="graph_padding"  height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-4" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase"> Site Visit </h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart5" class="graph_padding"  height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-4" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">  Unit </h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart6" class="graph_padding"  height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row target_charts">

                    <div class="col-sm-6 col-xs-6" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">  Sales </h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="bar-chart7" class="graph_padding"  height="174"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-6" >
                        <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                            <h4 class="pt-10 mb-0 text-center text-uppercase">  Lead Chart </h4>
                            <hr/>
                            <div class="card">
                                <div class="card-body">
                                    <div id="lead-pie-chart" class="graph_padding" ></div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </div>


    <!-- Vendor JS -->
    <script src="{{ asset('public/progress_chart/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/progress_chart/assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('public/progress_chart/assets/vendor_components/chart.js-master/Chart.min.js') }}"></script>

    {{-- // Only Use For Lead (Pie) Chart --}}
    <script src="{{ asset('public/progress_chart/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/progress_chart/plugins/apexcharts-bundle/js/apex-custom.js') }}"></script>

    <script>
        var all_targets_data = {!! json_encode($user_target) !!};
        // console.log(all_targets_data);
        // Verified Calls Persentage
        if (all_targets_data['set_verified_calls_target' ] > 0){
            achive_verified_calls_target = ((all_targets_data['achive_verified_calls_target' ] * 100)/all_targets_data['set_verified_calls_target' ]).toFixed(0) + "%";
        }else {
            achive_verified_calls_target = all_targets_data['set_verified_calls_target'];
        }
        // Client Meetings Persentage
        if (all_targets_data['set_client_meetings_target' ] > 0){
            achive_client_meetings_target = ((all_targets_data['achive_client_meetings_target' ] * 100)/all_targets_data['set_client_meetings_target' ]).toFixed(0) + "%";
        }else {
            achive_client_meetings_target = all_targets_data['set_client_meetings_target'];
        }
        // Dealer Meetings Persentage
        if (all_targets_data['set_dealer_meetings_target' ] > 0){
            achive_dealer_meetings_target = ((all_targets_data['achive_dealer_meetings_target' ] * 100)/all_targets_data['set_dealer_meetings_target' ]).toFixed(0) + "%";
        }else {
            achive_dealer_meetings_target = all_targets_data['set_dealer_meetings_target'];
        }
        // Freelancer Meetings Persentage
        if (all_targets_data['set_freelancer_meetings_target' ] > 0){
            achive_freelancer_meetings_target = ((all_targets_data['achive_freelancer_meetings_target' ] * 100)/all_targets_data['set_freelancer_meetings_target' ]).toFixed(0) + "%";
        }else {
            achive_freelancer_meetings_target = all_targets_data['set_freelancer_meetings_target'];
        }
        // Site Visit Persentage
        if (all_targets_data['set_site_visit_target' ] > 0){
            achive_site_visit_target = ((all_targets_data['achive_site_visit_target' ] * 100)/all_targets_data['set_site_visit_target' ]).toFixed(0) + "%";
        }else {
            achive_site_visit_target = all_targets_data['set_site_visit_target'];
        }
        // Unit Persentage
        if (all_targets_data['set_unit_target' ] > 0){
            achive_unit_target = ((all_targets_data['achive_unit_target' ] * 100)/all_targets_data['set_unit_target' ]).toFixed(0) + "%";
        }else {
            achive_unit_target = all_targets_data['set_unit_target'];
        }
        // Sales Persentage
        if (all_targets_data['set_sales_target' ] > 0){
            achive_sales_target = ((all_targets_data['achive_sales_target' ] * 100)/all_targets_data['set_sales_target' ]).toFixed(0) + "%";
        }else {
            achive_sales_target = all_targets_data['set_sales_target'];
        }
        $( document ).ready(function() {
            "use strict";
            // Bar chart 1
            new Chart(document.getElementById("bar-chart1"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_verified_calls_target' ] + ")" , "Achive ("+all_targets_data['achive_verified_calls_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_verified_calls_target' ] + ")" , "Achive ("+((all_targets_data['achive_verified_calls_target' ] * 100)/all_targets_data['set_verified_calls_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_verified_calls_target' ]).toFixed(0) + ")" , "Achive ("+achive_verified_calls_target +")"],
                datasets: [
                        {
                            label: "Calls",
                            backgroundColor: ["#689f38", "#38649f"],
                            data: [(all_targets_data['set_verified_calls_target' ]).toFixed(2) , all_targets_data['achive_verified_calls_target' ] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Verified Calls'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 2
            new Chart(document.getElementById("bar-chart2"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_client_meetings_target' ] + ")" , "Achive ("+all_targets_data['achive_client_meetings_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_client_meetings_target' ] + ")" , "Achive ("+((all_targets_data['achive_client_meetings_target' ] * 100)/all_targets_data['set_client_meetings_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_client_meetings_target' ]).toFixed(0) + ")" , "Achive ("+achive_client_meetings_target +")"],
                datasets: [
                        {
                            label: "Client Meetings",
                            backgroundColor: ["#689f38", "#389f99"],
                            data: [(all_targets_data['set_client_meetings_target' ]).toFixed(2), all_targets_data['achive_client_meetings_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Client Meetings'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 3
            new Chart(document.getElementById("bar-chart3"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_dealer_meetings_target' ] + ")" , "Achive ("+all_targets_data['achive_dealer_meetings_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_dealer_meetings_target' ] + ")" , "Achive ("+((all_targets_data['achive_dealer_meetings_target' ] * 100)/all_targets_data['set_dealer_meetings_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_dealer_meetings_target' ]).toFixed(0) + ")" , "Achive ("+achive_dealer_meetings_target +")"],
                datasets: [
                        {
                            label: "Dealer Meetings",
                            backgroundColor: ["#689f38", "#ee1044"],
                            data: [(all_targets_data['set_dealer_meetings_target' ]).toFixed(2) , all_targets_data['achive_dealer_meetings_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Dealer Meetings'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 4
            new Chart(document.getElementById("bar-chart4"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_freelancer_meetings_target' ] + ")" , "Achive ("+all_targets_data['achive_freelancer_meetings_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_freelancer_meetings_target' ] + ")" , "Achive ("+((all_targets_data['achive_freelancer_meetings_target' ] * 100)/all_targets_data['set_freelancer_meetings_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_freelancer_meetings_target' ]).toFixed(0) + ")" , "Achive ("+achive_freelancer_meetings_target +")"],
                datasets: [
                        {
                            label: "Freelancer Meetings",
                            backgroundColor: ["#689f38", "#ff8f00"],
                            data: [(all_targets_data['set_freelancer_meetings_target' ]).toFixed(2) , all_targets_data['achive_freelancer_meetings_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Freelancer Meetings'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 5
            new Chart(document.getElementById("bar-chart5"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_site_visit_target' ] + ")" , "Achive ("+all_targets_data['achive_site_visit_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_site_visit_target' ] + ")" , "Achive ("+((all_targets_data['achive_site_visit_target' ] * 100)/all_targets_data['set_site_visit_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_site_visit_target' ].toFixed(0)) + ")" , "Achive ("+achive_site_visit_target +")"],
                datasets: [
                        {
                            label: "Site Vist",
                            backgroundColor: ["#689f38", "#FF007F"],
                            data: [(all_targets_data['set_site_visit_target' ]).toFixed(2) , all_targets_data['achive_site_visit_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Site Vist'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 6
            new Chart(document.getElementById("bar-chart6"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_unit_target' ] + ")" , "Achive ("+all_targets_data['achive_unit_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_unit_target' ] + ")" , "Achive ("+((all_targets_data['achive_unit_target' ] * 100)/all_targets_data['set_unit_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_unit_target' ]).toFixed(0) + ")" , "Achive ("+achive_unit_target +")"],
                datasets: [
                        {
                            label:'Unit',
                            backgroundColor: ["#689f38", "#800000"],
                            data: [(all_targets_data['set_unit_target' ]).toFixed(2) , all_targets_data['achive_unit_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Unit'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            var set_number = all_targets_data['set_sales_target' ];
            // var set_number = 5570989.9;
            // Format the number with thousands separators and decimal places
            var set_formattedPrice = set_number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            // withoutr Decimal
            // var set_formattedPrice = Math.floor(set_number).toLocaleString('en-PK');
            // Add the PKR currency symbol
            set_formattedPrice += ' Rs';
            // Bar chart 7
            new Chart(document.getElementById("bar-chart7"),
            {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_sales_target' ] + ")" , "Achive ("+all_targets_data['achive_sales_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_sales_target' ] + ")" , "Achive ("+((all_targets_data['achive_sales_target' ] * 100)/all_targets_data['set_sales_target' ]).toFixed(0)  + "%)"],
                // labels: ["Target ("+ all_targets_data['set_sales_target' ] + ")" , "Achive ("+achive_sales_target +")"],
                labels: ["Target ("+ set_formattedPrice + ")" , "Achive ("+achive_sales_target +")"],
                datasets: [
                        {
                            label: "Sales",
                            backgroundColor: ["#689f38", "#AB47BC"],
                            // data: [ (all_targets_data['set_sales_target' ]).toFixed(2)  , all_targets_data['achive_sales_target'] ]
                            data: [ (all_targets_data['set_sales_target' ]).toFixed(2)  , all_targets_data['achive_sales_target'] ]
                        }
                    ]
                },
                options: {
                    legend: {display: false},
                    title: {
                        display: true,
                        text: 'Sales',
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });


            // lead-pie-chart 8
            var options =
            {
                series:
                [
                    all_targets_data['facebook_leads']      ,
                    all_targets_data['dealer_leads']        ,
                    all_targets_data['freelancer_leads']    ,
                    all_targets_data['personal_leads']      ,
                    all_targets_data['uan']                 ,
                    all_targets_data['website_leads']       ,

                ],

                chart:
                {
                    foreColor: '#9ba7b2',
                    height: 330,
                    type: 'pie',
                },

                colors: ["#0d6efd", "#6c757d", "#17a00e", "#f41127", "#ffc107", "#a832a0"] ,

                labels:
                    [
                        'Facebook Leads'    + ' (' +all_targets_data['facebook_leads']  +')',
                        'Dealer Leads'      + ' (' +all_targets_data['dealer_leads']    +')',
                        'Freelancer Leads'  + ' (' +all_targets_data['freelancer_leads']+')',
                        'Personal Leads'    + ' (' +all_targets_data['personal_leads']  +')',
                        'UAN Leads'         + ' (' +all_targets_data['uan']             +')',
                        'Website Leads'     + ' (' +all_targets_data['website_leads']   +')',
                    ],

                responsive: [{
                    breakpoint: 480,
                    options:
                    {
                        chart: {
                            height: 360
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#lead-pie-chart"), options);
            chart.render();

        }); // End of use strict
    </script>


    <script>
        $('.custom_date_option').on('click', function()
        {
            var value=$( ".custom_date_option option:selected" ).val();

            // alert('abc');

            if(value=='custom_date')
            {
                $('.custom_date_div').css('display', 'block');
                $('.custom_date_option_div').css('display', 'none');


                $(".start_date_input").datepicker({
                    // minDate: '-1D',
                    dateFormat:'dd-mm-yy',// Y-m-d
                    format:'dd-mm-yy',
                    maxDate: '+0D',
                    // // currentText: 'Today',
                    // numberOfMonths: 1,
                    // gotoCurrent: true,
                    // changeMonth: false,
                    // changeYear: false,
                    // stepMonths: 0,
                    // //showButtonPanel: true
                }).datepicker("setDate",'now');


                $(".end_date_input").datepicker({
                    // minDate: '-1D',
                    dateFormat:'dd-mm-yy',// Y-m-d
                    format:'dd-mm-yy',
                    maxDate: '+0D',
                    // // currentText: 'Today',
                    // numberOfMonths: 1,
                    // gotoCurrent: true,
                    // changeMonth: false,
                    // changeYear: false,
                    // stepMonths: 0,
                    // //showButtonPanel: true
                }).datepicker("setDate",'now');
            }
            else{
                $('.custom_date_div').css('display', 'none');
                $('.custom_date_option_div').css('display', 'block');
            }
        });
        $(document).ready(function ()
        {
            var value=$( ".custom_date_option option:selected" ).val();
            if (value=='custom_date')
            {
                $('.custom_date_div').css('display', 'block');
                $('.custom_date_option_div').css('display', 'none');
                var value1=$(".start_date_input" ).val();
                if(value1 !='' || value1 =='')
                {
                    $(".start_date_input").datepicker({
                        dateFormat:'dd-mm-yy',
                        maxDate: '+0D',
                        // // currentText: 'Today',
                        // numberOfMonths: 1,
                        // gotoCurrent: true,
                        // changeMonth: false,
                        // changeYear: false,
                        // stepMonths: 0,
                        // //showButtonPanel: true
                    })
                    $('.custom_date_option').val('custom_date');
                }
                var value2=$(".end_date_input" ).val();
                if(value2 !='' || value2 =='')
                {
                    $(".end_date_input").datepicker({
                        dateFormat:'dd-mm-yy',
                        maxDate: '+0D',
                        // // currentText: 'Today',
                        // numberOfMonths: 1,
                        // gotoCurrent: true,
                        // changeMonth: false,
                        // changeYear: false,
                        // stepMonths: 0,
                        // //showButtonPanel: true
                    });
                    $('.custom_date_option').val('custom_date');
                }
            }
            else
            {
                $('.custom_date_div').css('display', 'none');
                $('.custom_date_option_div').css('display', 'block');
            }
            $('.graph_undo').on('click', function()
            {
                var value=$( ".custom_date_option option:selected" ).val();
                if(value=='custom_date')
                {
                    $(".custom_date_option option:selected").prop("selected", false)
                    $('.custom_date_div').css('display', 'none');
                    $('.custom_date_option_div').css('display', 'block');
                }
            });
        });
    </script>


    <script type="text/javascript">

        $("#graph_toggle_btn").click(function ()
        {
            var val= $(this).val();

            if(val == 'Show Graphs'){
                $(this).val('Hide Graphs');
            }else{
                $(this).val('Show Graphs');
            }

            $("#graphs").toggle();
        });


        $("#detail_toggle_btn").click(function ()
        {
            var val= $(this).val();

            if(val == 'More Details'){
                $(this).val('Hide Details');
            }else{
                $(this).val('More Details');
            }

            $("#detail_boxes").toggle();
        });


    </script>


<script src="{{ asset('public/multi-select/js/select2.min.js')}}"></script>
<script>
    $('.multiple-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
</script>

@endsection
