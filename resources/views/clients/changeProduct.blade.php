@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Cambio de Producto </h2>
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

<div class="col-md-12 d-none" id="changeProductForm">
    <section class="panel form-wizard" id="w5">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>

            <h2 class="panel-title">Cambio de Paquete</h2>
        </header>
        <div class="panel-body">
            <div class="wizard-tabs hidden">
                <ul class="wizard-steps">
                    <li class="active">
                        <a href="#w5-msisdn-number" data-toggle="tab"><span class="badge">1</span>Número MSISDN</a>
                    </li>
                    <li>
                        <a href="#w5-rate" data-toggle="tab"><span class="badge">2</span>Planes</a>
                    </li>
                    <li>
                        <a href="#w5-effectiveDate" data-toggle="tab"><span class="badge">3</span>Fecha Efectiva</a>
                    </li>
                </ul>
            </div>
            <div class="progress progress-striped ligth active m-md light">
                <div class="progress-bar progress-bar-danger" id="progress-bar-content" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    <span class="sr-only">60%</span>
                </div>
            </div>
            <div class="alert alert-info">
                <strong>Datos de confirmación.</strong>
                <ul>
                    <li>Nombre: {{$dataMSISDN['name_user'].' '.$dataMSISDN['lastname_user']}}</li>
                    <li>Email: {{$dataMSISDN['email_user']}}</li>
                    <li>SIM: {{$dataMSISDN['MSISDN']}}</li>
                </ul>
            </div>
            <div class="alert alert-info d-none" id="infoDN">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul>
                    <li id="typeProduct"></li>
                    <li id="rateName"></li>
                    <li id="offerName"></li>
                    <li id="coordenadasInfo"></li>
                </ul>
            </div>
            <form class="form-horizontal" novalidate="novalidate">
                <div class="tab-content">
                    <div id="w5-msisdn-number" class="tab-pane active">
                        <div class="form-group">
                            <div class="col-sm-12 col-md-4">
                                <label for="w5-msisdn">MSISDN</label>
                                <input type="text" class="form-control" name="msisdn" id="w5-msisdn" maxlength="10" value="{{$dataMSISDN['MSISDN']}}" required readonly disabled>
                            </div>
                        </div>
                        
                    </div>
                    <div id="w5-rate" class="tab-pane">
                        <div class="row d-none" id="coordenadasContent">
                            <div class="col-sm-12 col-md-4 mr-md">
                                <label for="w5-lat">Latitud</label>
                                <div class="row">
                                    <input type="text" class="form-control" name="lat" id="w5-lat" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="w5-lng">Longitud</label>
                                <div class="row">
                                    <input type="text" class="form-control" name="lng" id="w5-lng" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 col-md-6 row">
                                <label for="ratesChangeProduct">Planes</label>
                                <select class="form-control" name="ratesChangeProduct" id="ratesChangeProduct" required>
                                    
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="originalRate">
                        <input type="hidden" id="originalOffer">
                        <input type="hidden" id="product">
                        <input type="hidden" id="amount">
                        <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                        <input type="hidden" id="payment" value="{{$payment}}">
                        <input type="hidden" id="extraAmount" value="{{$extraAmount}}">
                        <input type="hidden" id="service" value="{{$service}}">
                    </div>
                    <div id="w5-effectiveDate" class="tab-pane">
                        <div class="form-group">
                            <div class="alert alert-warning">
                                <strong>Verifique los datos de la recarga </strong>
                                <ul>
                                    <li id="pack-warning">Paquete: </li>
                                    <li>SIM: {{$dataMSISDN['MSISDN']}}</li>
                                    <li id="price-warning">Importe: </li>
                                </ul>
                            </div>
                            <div class="col-sm-12 col-md-4 row" >
                                <label >Fecha de la Operación</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="date" class="form-control" id="scheduleDateChange">
                                </div>
                            </div>
                        </div>
                      
                        
                    </div>
                    
                </div>
            </form>
        </div>
        <div class="panel-footer">
            <ul class="pager">
                <li class="previous disabled">
                    <a><i class="fa fa-angle-left"></i> Previous</a>
                </li>
                <li class="finish hidden pull-right">
                    <a>Finish</a>
                </li>
                <li class="next" id="nextChangeProduct">
                    <a id="nextChangeProductLink">Next <i class="fa fa-angle-right"></i></a>
                </li>
            </ul>
        </div>
    </section>
</div>
<script src="{{asset('octopus/assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/jquery-validation/jquery.validate.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/bootstrap-wizard/jquery.bootstrap.wizard.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/pnotify/pnotify.custom.js')}}"></script>
<script>
    var token;
    $(document).ready(function(){
        (async () => {
            const { value: pass } = await Swal.fire({
                title: 'Hola, {{Auth::user()->email}}, ingresa tu contraseña:',
                input: 'password',
                inputLabel: 'Tu contraseña',
                inputPlaceholder: 'Password'
            })

            if (pass) {
                $.ajax({
                    url: "{{url('createJWTTen')}}",
                    method: "POST",
                    data: {email:"{{Auth::user()->email}}",password:pass},
                    success:function(response){
                        if(response.error){
                            token = null;
                            Swal.fire({
                                icon: 'error',
                                title: 'Permiso denegado, se recargará la página. Ocurrió lo siguiente:',
                                text: response.error,
                                showConfirmButton: false,
                                timer: 3000
                            });
                            setTimeout(function(){ location.reload(); }, 3500);
                        }else{
                            token = response.token;
                            Swal.fire({
                                icon: 'success',
                                title: 'Permiso concedido.',
                                text: 'Puede continuar...',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#changeProductForm').removeClass('d-none');
                        }
                    }
                });
            }
        })()
    });


    (function( $ ) {

'use strict';

var $w5finish = $('#w5').find('ul.pager li.finish'),
    $w5validator = $("#w5 form").validate({
    highlight: function(element) {
        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
        $(element).remove();
    },
    errorPlacement: function( error, element ) {
        element.parent().append( error );
    }
});

$w5finish.on('click', function( ev ) {
    ev.preventDefault();
    let msisdn = $('#w5-msisdn').val();
    let rate = $('#ratesChangeProduct').val();
    let offerID = $('#ratesChangeProduct option:selected').attr('data-offerid');
    let offer_id = $('#ratesChangeProduct option:selected').attr('data-offer-id');
    let type = "internalExternalChange";
    let producto = $('#product').val();
    let amount = $('#amount').val();
    let scheduleDateFirst = $('#scheduleDateChange').val();
    let scheduleDate = scheduleDateFirst.replace(/-/g, "");
    let user_id = $('#user_id').val();
    let tokenCSRF = $('meta[name="csrf-token"]').attr('content');
    let lat = '', lng = '', address = '';
    let data, route = '';

    let payment = $('#payment').val();
    let extraAmount = $('#extraAmount').val();
    let service = $('#service').val();

    let comment = '', reason = 'cobro', status = 'pendiente';

    if(scheduleDateFirst.length == 0 || /^\s+$/.test(scheduleDateFirst)){
        scheduleDate = '';
    }

    if(producto == 'HBB'){
        lat = $('#w5-lat').val();
        lng = $('#w5-lng').val();
        address = lat+','+lng;
    }else{
        address = null;
    }

    let originalRate = $('#originalRate').val();
    let originalOffer = $('#originalOffer').val();

    var validated = $('#w5 form').valid();
    if(validated){

        
        data = {
            token:token,
            _token:tokenCSRF, 
            msisdn:msisdn, 
            rate_id:rate, 
            offerID:offerID, 
            offer_id:offer_id, 
            type:type, 
            scheduleDate:scheduleDate, 
            address:address,
            producto:producto,
            user_id:user_id,
            amount:amount,
            service: service,
            payID: payment,
            monto: amount,
            typePay: 'efectivo',
            folioPay: 'N/A',
            estadoPay: 'completado',
            montoExtra: extraAmount,
            comment:comment,
            reason:reason,
            status:status
        }
        route = "{{route('changeProduct.post')}}";

        Swal.fire({
            title: 'Estamos trabajando en ello...',
            html: 'Espera un poco, un poquito más...',
            didOpen: () => {
                Swal.showLoading();

                $.ajax({
                    url: route,
                    method: "POST",
                    data: data,
                    success: function(response){
                        // response = JSON.parse(response);

                        if(response.status){
                            if(response.status == 'Token is Invalid'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Token de acceso inválido.',
                                    text: 'Se recargará la página...',
                                    showConfirmButton: false,
                                });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }else if(response.status == 'Token is Expired'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Token de acceso expirado.',
                                    text: 'Se recargará la página...',
                                    showConfirmButton: false,
                                });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }else if(response.status == 'Authorization Token not found'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Token de acceso no encontrado.',
                                    text: 'Se recargará la página...',
                                    showConfirmButton: false,
                                });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                            
                            return false;
                        }

                        // console.log(response);
                        if(response.http_code == 1){
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Pago guardado con éxito.',
                                text: 'El servicio se está reanudando...',
                                showConfirmButton: false
                            });

                        }else if(response.http_code == 2 || response.http_code == 3){
                            Swal.close();
                            new PNotify({
                                title: 'Ooops!',
                                text: response.message,
                                type: 'custom',
                                addclass: 'notification-warning',
                                icon: 'fa fa-check'
                            });
                        }else if(response.http_code == 0){
                            Swal.close();
                            new PNotify({
                                title: 'Ooops!',
                                text: response.message,
                                type: 'custom',
                                addclass: 'notification-danger',
                                icon: 'fa fa-check'
                            });
                        }
                    }
                });
            }
        });
    }
});

$('#w5').bootstrapWizard({
    tabClass: 'wizard-steps',
    nextSelector: 'ul.pager li.next',
    previousSelector: 'ul.pager li.previous',
    firstSelector: null,
    lastSelector: null,
    onNext: function( tab, navigation, index, newindex ) {
        var validated = $('#w5 form').valid();
        
        if( !validated ) {
            $w5validator.focusInvalid();
            return false;
        }
    },
    onTabChange: function( tab, navigation, index, newindex ) {
        // console.log('onTabChange1: '+tab+' - '+navigation+' - '+index+' - '+newindex);
        if(index == 0){
            let msisdn = $('#w5-msisdn').val();
            getOffersRatesDiff(msisdn);
            $('#nextChangeProduct').addClass('disabled');
            $('#nextChangeProductLink').addClass('not-action');

        }else if(index == 1){
            // console.log('Captar oferta');
        }else if(index == 2){
            // console.log('Do change');
        }
        var $total = navigation.find('li').size() - 1;
        $w5finish[ newindex != $total ? 'addClass' : 'removeClass' ]( 'hidden' );
        $('#w5').find(this.nextSelector)[ newindex == $total ? 'addClass' : 'removeClass' ]( 'hidden' );
    },
    onTabShow: function( tab, navigation, index ) {
        var $total = navigation.find('li').length;
        var $current = index + 1;
        var $percent = ( $current / $total ) * 100;
        $('#w5').find('.progress-bar').css({ 'width': $percent + '%' });
        
        if(index == 0){
            let msisdn = $('#w5-msisdn').val();

            $('#infoDN').addClass('d-none');

            $('#progress-bar-content').addClass('progress-bar-danger');
            $('#progress-bar-content').removeClass('progress-bar-warning');
            $('#progress-bar-content').removeClass('progress-bar-success');

            if(msisdn.length < 10){
                $('#nextChangeProduct').addClass('disabled');
                $('#nextChangeProductLink').addClass('not-action');
            }else{
                $('#nextChangeProduct').removeClass('disabled');
                $('#nextChangeProductLink').removeClass('not-action');
            }
        }else if(index == 1){
            let rate = $('#ratesChangeProduct').val();
            // console.log('rate: '+rate)
            $('#progress-bar-content').removeClass('progress-bar-danger');
            $('#progress-bar-content').addClass('progress-bar-warning');
            $('#progress-bar-content').removeClass('progress-bar-success');

            if(rate == 0 || rate == null){
                $('#nextChangeProduct').addClass('disabled');
                $('#nextChangeProductLink').addClass('not-action');
            }else{
                $('#nextChangeProduct').removeClass('disabled');
                $('#nextChangeProductLink').removeClass('not-action');
            }
        }else if(index == 2){
            $('#progress-bar-content').removeClass('progress-bar-danger');
            $('#progress-bar-content').removeClass('progress-bar-warning');
            $('#progress-bar-content').addClass('progress-bar-success');
        }
    }
});

}).apply( this, [ jQuery ]);

function getOffersRatesDiff(msisdn){
    let options = "<option select value='0'>Elige un plan...</option>";

    $.ajax({
        url: "{{route('getOffersRatesDiff.get')}}",
        data: {token:token,msisdn:msisdn},
        success: function(response){
            if(response.status){
                if(response.status == 'Token is Invalid'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Token de acceso inválido.',
                        text: 'Se recargará la página...',
                        showConfirmButton: false,
                    });
                    setTimeout(function(){ location.reload(); }, 2000);
                }else if(response.status == 'Token is Expired'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Token de acceso expirado.',
                        text: 'Se recargará la página...',
                        showConfirmButton: false,
                    });
                    setTimeout(function(){ location.reload(); }, 2000);
                }else if(response.status == 'Authorization Token not found'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Token de acceso no encontrado.',
                        text: 'Se recargará la página...',
                        showConfirmButton: false,
                    });
                    setTimeout(function(){ location.reload(); }, 2000);
                }
                
                return false;
            }

            response = JSON.parse(response);
            let offersAndRates = response.offersAndRates;
            console.log(offersAndRates);
            $('#product').val(response.dataMSISDN.producto);
            $('#infoDN').removeClass('d-none');
            $('#typeProduct').html('Producto: <b>'+response.dataMSISDN.producto+'</b>');
            $('#rateName').html('Plan actual: <b>'+response.dataMSISDN.rate_name+'</b>');
            $('#offerName').html('Oferta actual: <b>'+response.dataMSISDN.offer_name+'</b>');

            if(response.dataMSISDN.producto == 'HBB'){
                // $('#coordenadasContent').removeClass('d-none');
                // $('#coordenadasInfo').removeClass('d-none');
                $('#coordenadasInfo').html('LatLng: <b>'+response.dataMSISDN.lat+', '+response.dataMSISDN.lng+'</b>');
                $('#w5-lat').val(response.dataMSISDN.lat);
                $('#w5-lng').val(response.dataMSISDN.lng);
            }else{
                $('#coordenadasContent').addClass('d-none');
                $('#coordenadasInfo').addClass('d-none');
                $('#w5-lat').val('');
                $('#w5-lng').val('');
            }
            
            $('#originalRate').val(response.dataMSISDN.rate_id);
            $('#originalOffer').val(response.dataMSISDN.offer_id);
            offersAndRates.forEach(function(element){
                options+="<option value='"+element.rate_id+"' data-rate-id='"+response.dataMSISDN.rate_id+"' data-rate-price='"+element.rate_price+"' data-offerID='"+element.offerID+"' data-offer-id='"+element.offer_id+"' data-rate-name='"+element.rate_name+"'>"+element.rate_name+" - $"+parseFloat(element.rate_price).toFixed(2)+"</option>"
            })
            $('#ratesChangeProduct').html(options);
        }
    });
}

$('#ratesChangeProduct').change(function(){
    let valor = $(this).val();
    if(valor == 0){
        $('#nextChangeProduct').addClass('disabled');
        $('#nextChangeProductLink').addClass('not-action');
    }else{
        $('#nextChangeProduct').removeClass('disabled');
        $('#nextChangeProductLink').removeClass('not-action');
        let amount = $('#ratesChangeProduct option:selected').attr('data-rate-price');
        let rate_name = $('#ratesChangeProduct option:selected').attr('data-rate-name');

        $('#amount').val(parseFloat(amount).toFixed(2));
        $('#price-warning').html("Importe: $"+parseFloat(amount).toFixed(2));
        $('#pack-warning').html("Paquete: "+rate_name);
    }
});
</script>
@endsection