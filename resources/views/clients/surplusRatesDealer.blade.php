@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Recargas </h2>
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

<div class="col-md-12 d-none" id="productPurchaseForm">
    <section class="panel form-wizard" id="w6">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>

            <h2 class="panel-title">Generación de Recarga</h2>
        </header>
        <div class="panel-body">
            <div class="wizard-tabs hidden">
                <ul class="wizard-steps">
                    <li class="active">
                        <a href="#w6-msisdn-number-purchase" data-toggle="tab"><span class="badge">1</span>Número MSISDN</a>
                    </li>
                    <li>
                        <a href="#w6-offer" data-toggle="tab"><span class="badge">2</span>Oferta</a>
                    </li>
                    <li>
                        <a href="#w6-purchase-mood" data-toggle="tab"><span class="badge">3</span>Modo de Compra</a>
                    </li>
                </ul>
            </div>
            <div class="progress progress-striped ligth active m-md light">
                <div class="progress-bar progress-bar-danger" id="progress-bar-content-purchase" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    <span class="sr-only">60%</span>
                </div>
            </div>
            
            <form class="form-horizontal" novalidate="novalidate">
                <div class="tab-content">
                    <div class="alert alert-info">
                        <strong>Datos de confirmación.</strong>
                        <ul>
                            <li>Nombre: {{$dataMSISDN['name_user'].' '.$dataMSISDN['lastname_user']}}</li>
                            <li>Email: {{$dataMSISDN['email_user']}}</li>
                            <li>Plan Activo: {{$dataMSISDN['rate_name']}}</li>
                        </ul>
                    </div>
                    <div id="w6-msisdn-number-purchase" class="tab-pane active">
                        
                        <div class="form-group">
                            <div class="col-sm-12 col-md-4">
                                <label for="w6-msisdn">MSISDN</label>
                                <input type="text" class="form-control" name="msisdn" id="w6-msisdn" maxlength="10" value="{{$dataMSISDN['MSISDN']}}" required readonly disabled>
                            </div>
                        </div>

                        <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                        
                    </div>
                    <div id="w6-offer" class="tab-pane">
                        <div class="form-group">
                            <div class="col-sm-12 col-md-6 row">
                                <label for="ratesPurchaseProduct">Planes</label>
                                <select class="form-control" name="ratesPurchaseProduct" id="ratesPurchaseProduct" required>
                                    @foreach($packsSurplus as $packSurplus)
                                    <option 
                                        value="{{$packSurplus['offerID']}}" 
                                        data-rate-price="{{$packSurplus['price_sale']}}" 
                                        data-rate-id="{{$dataMSISDN['rate_id']}}" 
                                        data-offer-id="{{$packSurplus['id']}}"
                                        data-rate-name="{{$packSurplus['name']}}">
                                            {{$packSurplus['name'].' - $'.number_format($packSurplus['price_sale'],2)}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="w6-purchase-mood" class="tab-pane">
                        <div class="col md-12">
                            <div class="row">
                                <div class="alert alert-warning">
                                    <strong>Verifique los datos de la recarga </strong>
                                    <ul>
                                        <li id="pack-warning">Paquete: </li>
                                        <li>SIM: {{$dataMSISDN['MSISDN']}}</li>
                                        <li id="price-warning">Importe: </li>
                                    </ul>
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
                <li class="next" id="nextproductPurchase">
                    <a id="nextproductPurchaseLink">Next <i class="fa fa-angle-right"></i></a>
                </li>
            </ul>
        </div>
    </section>
</div>
<script src="{{asset('octopus/assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/jquery-validation/jquery.validate.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/bootstrap-wizard/jquery.bootstrap.wizard.js')}}"></script>
<script src="{{asset('octopus/assets/vendor/pnotify/pnotify.custom.js')}}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script> -->
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
                            $('#productPurchaseForm').removeClass('d-none');
                        }
                    }
                });
            }
        })()
    });


(function( $ ) {

'use strict';

    var $w6finish = $('#w6').find('ul.pager li.finish'),
		$w6validator = $("#w6 form").validate({
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

	$w6finish.on('click', function( ev ) {
		ev.preventDefault();
        let msisdn = $('#w6-msisdn').val();
        let offerID = $('#ratesPurchaseProduct').val();
        let rate_id = $('#ratesPurchaseProduct option:selected').attr('data-rate-id');
        let price = $('#ratesPurchaseProduct option:selected').attr('data-rate-price');
        let offer_id = $('#ratesPurchaseProduct option:selected').attr('data-offer-id');
        let tokenCSRF = $('meta[name="csrf-token"]').attr('content');
        let tokenJWT = token;
        let comment = '', reason = 'cobro', status = 'pendiente';
        let user_id = $('#user_id').val();
    
        var validated = $('#w6 form').valid();

            if(validated){
                
                // console.log(data);
                // return false;

                Swal.fire({
                    title: 'Estamos trabajando en ello...',
                    html: 'Espera un poco, un poquito más...',
                    didOpen: () => {
                        Swal.showLoading();
                        $.ajax({
                            url: "{{route('purchase')}}",
                            method: "POST",
                            data: {token:tokenJWT, _token:tokenCSRF, msisdn:msisdn, offerID:offerID, rate_id:rate_id, offer_id:offer_id, user_id:user_id, price:price, comment:comment, reason:reason, status:status},
                            success: function(response){
                                response = JSON.parse(response);
                                if(response.http_code == 1){
                                    token = token+'xxXXxx';
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Hecho',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(function(){ location.href = "{{route('my-recharges')}}"; }, 2500);
                                }else if(response.http_code == 0){
                                    Swal.fire({
                                        icon: 'Error',
                                        title: 'Ocurrió un error.',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            }
                        });
                    }
                });
                
                
            }
		
	});

	$('#w6').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var validated = $('#w6 form').valid();
            
			if( !validated ) {
				$w6validator.focusInvalid();
				return false;
			}
		},
		onTabChange: function( tab, navigation, index, newindex ) {
            // console.log('onTabChange1: '+tab+' - '+navigation+' - '+index+' - '+newindex);
            if(index == 0){
                let msisdn = $('#w6-msisdn').val();
                // getOffersSurplus(msisdn);
            }else if(index == 1){
                console.log('evento2');
            }else if(index == 2){
                // console.log('Do change');
            }
			var $total = navigation.find('li').size() - 1;
			$w6finish[ newindex != $total ? 'addClass' : 'removeClass' ]( 'hidden' );
			$('#w6').find(this.nextSelector)[ newindex == $total ? 'addClass' : 'removeClass' ]( 'hidden' );
		},
		onTabShow: function( tab, navigation, index ) {
			var $total = navigation.find('li').length;
			var $current = index + 1;
			var $percent = ( $current / $total ) * 100;
            let rate_name = '', rate_price = 0;
			$('#w6').find('.progress-bar').css({ 'width': $percent + '%' });
            
            if(index == 0){
                let msisdn = $('#w6-msisdn').val();

                // $('#infoDN').addClass('d-none');

                $('#progress-bar-content-purchase').addClass('progress-bar-danger');
                $('#progress-bar-content-purchase').removeClass('progress-bar-warning');
                $('#progress-bar-content-purchase').removeClass('progress-bar-success');

                if(msisdn.length < 10){
                    $('#nextproductPurchase').addClass('disabled');
                    $('#nextproductPurchaseLink').addClass('not-action');
                }else{
                    $('#nextproductPurchase').removeClass('disabled');
                    $('#nextproductPurchaseLink').removeClass('not-action');
                }
            }else if(index == 1){
                $('#progress-bar-content-purchase').removeClass('progress-bar-danger');
                $('#progress-bar-content-purchase').addClass('progress-bar-warning');
                $('#progress-bar-content-purchase').removeClass('progress-bar-success');
               
            }else if(index == 2){
                rate_name = $('#ratesPurchaseProduct option:selected').attr('data-rate-name');
                rate_price = $('#ratesPurchaseProduct option:selected').attr('data-rate-price');
                rate_price = parseFloat(rate_price);
                $('#pack-warning').html('Paquete: '+rate_name);
                $('#price-warning').html('<strong>Importe: '+rate_price.toFixed(2)+'</strong>');

                $('#progress-bar-content-purchase').removeClass('progress-bar-danger');
                $('#progress-bar-content-purchase').removeClass('progress-bar-warning');
                $('#progress-bar-content-purchase').addClass('progress-bar-success');
            }
		}
	});

}).apply( this, [ jQuery ]);
</script>
@endsection