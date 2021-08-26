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
                        <div class="row">
                            <div class="col-md-6">
                                <meter min="0" max="100" value="{{$FreeUnits['freePercentage']}}" id="meter"></meter>
                                <h5 class="text-center text-bold text-dark">Porcentaje restante del plan contratado</h5>

                                <section class="panel col-md-6">
									<div class="panel-body bg-tertiary">
										<div class="widget-summary widget-summary-xs">
											<div class="widget-summary-col widget-summary-col-icon">
												
											</div>
											<div class="widget-summary-col">
												<div class="summary">
													<h4 class="title text-bold">Unidades Totales: {{number_format($FreeUnits['totalAmt'],2)}}GB</h4>
												
												</div>
											</div>
										</div>
									</div>
								</section>

                                <section class="panel col-md-6">
									<div class="panel-body bg-secondary">
										<div class="widget-summary widget-summary-xs">
											<div class="widget-summary-col widget-summary-col-icon">
												
											</div>
											<div class="widget-summary-col">
												<div class="summary">
													<h4 class="title text-bold">Unidades Restantes: {{number_format($FreeUnits['unusedAmt'],2)}}GB</h4>
													
												</div>
											</div>
										</div>
									</div>
								</section>

                            </div>
                            <div class="col-md-6">
                            @if($FreeUnits2Boolean == 1)
                                <meter min="0" max="100" value="{{$FreeUnits2['freePercentage']}}" id="meterDark"></meter>
                                <h5 class="text-center text-bold text-dark">Porcentaje de unidades libres en GB extras (recarga)</h5>
                            @else
                                <meter min="0" max="100" value="{{$FreeUnits2['freePercentage']}}" id="meterDark"></meter>
                                <h5 class="text-center text-bold text-dark">Porcentaje de unidades libres en GB extras (sin recarga realizada)</h5>
                            @endif

                            <section class="panel col-md-6">
									<div class="panel-body bg-tertiary">
										<div class="widget-summary widget-summary-xs">
											<div class="widget-summary-col widget-summary-col-icon">
												
											</div>
											<div class="widget-summary-col">
												<div class="summary">
													<h4 class="title text-bold">Unidades Totales: {{number_format($FreeUnits2['totalAmt'],2)}}GB</h4>
												
												</div>
											</div>
										</div>
									</div>
								</section>

                                <section class="panel col-md-6">
									<div class="panel-body bg-secondary">
										<div class="widget-summary widget-summary-xs">
											<div class="widget-summary-col widget-summary-col-icon">
												
											</div>
											<div class="widget-summary-col">
												<div class="summary">
													<h4 class="title text-bold">Unidades Restantes: {{number_format($FreeUnits2['unusedAmt'],2)}}GB</h4>
													
												</div>
											</div>
										</div>
									</div>
								</section>
                            </div>
                            
                        </div>
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