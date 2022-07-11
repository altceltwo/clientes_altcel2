@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Consumos </h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="{{route('home')}}">
                    <i class="fa fa-home"></i>
                </a>
            </li>
        </ol>
        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
    </div>
</header>


<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                            <a href="#" class="fa fa-times"></a>
                        </div>

                        <h2 class="panel-title">Unidades Libres</h2>
                        <p class="panel-subtitle"></p>
                    </header>
                    <div class="panel-body">
						<div class="row mb-md">
							<div class="col-md-4">
								<span class="badge label label-{{$status_color}}">Estado: {{$status}}</span>
							</div>
							<div class="col-md-4">
								<span class="badge label label-success">IMEI: {{$imei}}</span>
							</div>
							<div class="col-md-4">
								<span class="badge label label-success">ICC: {{$icc}}</span>
							</div>
						</div>
						
                        @if($service == 'MIFI' || $service == 'HBB')
						<h4 class="h4 m-none text-dark text-bold">Plan contratado: {{$consultUF['rate']}}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <meter min="0" max="100" value="{{$FreeUnits['freePercentage']}}" id="meter"></meter>
                                <h5 class="text-center text-bold text-dark">Porcentaje restante de tus GB's</h5>

								<div class="col-md-12 mt-2" >
									<ul class="list-group col-md-12" id="data-dn-list">
										<li class="list-group-item d-flex justify-content-between align-items-center text-dark">
											Unidades Totales: 
											<span class="badge label label-success">{{number_format($FreeUnits['totalAmt'],2)}} GB</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center text-dark">
											Unidades Restantes: 
											<span class="badge label label-success">{{number_format($FreeUnits['unusedAmt'],2)}} GB</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center text-dark">
											Inicio: 
											<span class="badge label label-success">{{$effectiveDatePrimary}}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center text-dark">
											Expira: 
											<span class="badge label label-danger">{{$expireDatePrimary}}</span>
										</li>
									</ul>
								</div>
                            </div>
                        </div>
                        @elseif($service == 'MOV')
						<h4 class="h4 m-none text-dark text-bold">Plan contratado: {{$consultUF['rate']}}</h4>
                        <div class="panel-body">
                            @foreach($consultUF['freeUnits']['nacionales']  as $units)
                                <div class="col-md-4 text-dark mb-md">
                                    <h4 class="h4 m-none text-dark text-bold">{{$units['name']}}</h4>
                                    <h6 class="m-none text-dark text-bold">Total: {{number_format($units['totalAmt'],2).' '.$units['description']}}</h6>
                                    <h6 class="m-none text-dark text-bold">Restante: {{number_format($units['unusedAmt'],2).' '.$units['description']}}</h6>
                                    <div class="progress progress-striped light active m-md">
                                        <div class="progress-bar progress-bar-dark" role="progressbar" aria-valuenow="{{number_format($units['freePercentage'],2)}}" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($units['freePercentage'],2)}}%;">
                                            {{number_format($units['freePercentage'])}}%
                                        </div>
                                    </div>
                                    Inicio: <span class="badge label label-success">{{$units['effectiveDate']}}</span><br>
                                    Expira: <span class="badge label label-danger">{{$units['expireDate']}}</span>
                                </div>
                            @endforeach
                            @foreach($consultUF['freeUnits']['ri']  as $units)
                                <div class="col-md-4 text-dark mb-md">
                                    <h4 class="h4 m-none text-dark text-bold">{{$units['name']}}</h4>
                                    <h6 class="m-none text-dark text-bold">Total: {{number_format($units['totalAmt'],2).' '.$units['description']}}</h6>
                                    <h6 class="m-none text-dark text-bold">Restante: {{number_format($units['unusedAmt'],2).' '.$units['description']}}</h6>
                                    <div class="progress progress-striped light active m-md">
                                        <div class="progress-bar progress-bar-dark" role="progressbar" aria-valuenow="{{number_format($units['freePercentage'],2)}}" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($units['freePercentage'],2)}}%;">
                                            {{number_format($units['freePercentage'])}}%
                                        </div>
                                    </div>
                                    Inicio: <span class="badge label label-success">{{$units['effectiveDate']}}</span><br>
                                    Expira: <span class="badge label label-danger">{{$units['expireDate']}}</span>
                                </div>
                            @endforeach
							<hr> <br>
                            @foreach($consultUF['freeUnits']['extra']  as $units)
                                <div class="col-md-6 text-dark mb-md">
                                    <h4 class="h4 m-none text-dark text-bold">{{$units['name']}}</h4>
                                    <h6 class="m-none text-dark text-bold">Total: {{number_format($units['totalAmt'],2).' '.$units['description']}}</h6>
                                    <h6 class="m-none text-dark text-bold">Restante: {{number_format($units['unusedAmt'],2).' '.$units['description']}}</h6>
                                    <div class="progress progress-striped light active m-md">
                                        <div class="progress-bar progress-bar-dark" role="progressbar" aria-valuenow="{{number_format($units['freePercentage'],2)}}" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($units['freePercentage'],2)}}%;">
                                            {{number_format($units['freePercentage'])}}%
                                        </div>
                                    </div>
                                    Inicio: <span class="badge label label-success">{{$units['effectiveDate']}}</span><br>
                                    Expira: <span class="badge label label-danger">{{$units['expireDate']}}</span>
                                </div>
                            @endforeach 
                        </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script> -->
<script src="{{asset('octopus/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/liquid-meter/liquid.meter.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/snap-svg/snap.svg.js')}}"></script>
<script>

$('#meter').liquidMeter({
		shape: 'circle',
		color: '#0088CC',
		background: '#272A31',
        stroke: '#33363F',
		fontSize: '24px',
		fontWeight: '600',
		textColor: '#FFFFFF',
		liquidOpacity: 0.9,
		liquidPalette: ['#0088CC'],
		speed: 3000
	});

	/*
	Liquid Meter Dark
	*/
	$('#meterDark').liquidMeter({
		shape: 'circle',
		color: '#0088CC',
		background: '#272A31',
		stroke: '#33363F',
		fontSize: '24px',
		fontWeight: '600',
		textColor: '#FFFFFF',
		liquidOpacity: 0.9,
		liquidPalette: ['#0088CC'],
		speed: 3000
	});

</script>

@endsection