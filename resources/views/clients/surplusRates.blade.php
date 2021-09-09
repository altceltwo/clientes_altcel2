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
<h3 class="mt-xs">Elige tu recarga</h3>
<p class="mb-lg">Tenemos los siguientes paquetes para ti.</p>

<div class="row">
    @foreach($packsSurplus as $packSurplus)
    <div class="col-md-4 col-lg-4 col-xl-4">
            <section class="panel">
                <header class="panel-heading bg-white">
                    <div class="panel-heading-icon bg-primary mt-sm" style="color:black; background: #9bfbe1 !important;">
                        <i class="fa fa-dollar"></i>
                    </div>
                </header>
                <div class="panel-body">
                    <h3 class="text-semibold mt-none text-center">{{$packSurplus['name']}}</h3>
                    <h3 class="text-bold text-dark mt-none text-center">${{number_format($packSurplus['price_sale'],2)}}</h3>
                    <div class="text-center">{!! $packSurplus['description'] !!}</div>
                </div>
                <header class="panel-footer bg-white">
                    <a type="button" class="mb-xs mt-xs mr-xs btn btn-block text-bold text-white btn-buy" style="color:black; background: #9bfbe1 !important;" 
                    data-price="{{$packSurplus['price_sale']}}" data-rate-id="{{$dataMSISDN['rate_id']}}" data-offer-id="{{$packSurplus['id']}}"
                     href="#formPayment">
                    COMPRAR <li class="fa fa-arrow-circle-right"></li>
                    </a>
                </header>
            </section>
        </div> 
    @endforeach
</div>

<section class="panel d-none" id="formPayment">
           
    <div class="panel-body">
        <form class="form-horizontal form-bordered" >
            <div class="form-row mt-md " >
                <div class="form-group col-md-12">
                    <h4>Información Personal para Referencia</h4>
                </div>
                <div class="col-md-6">
                    <label for="name">Nombre: </label>
                    <input type="text" class="form-control form-control-sm" id="name" placeholder="Nombre" value="{{$dataMSISDN['name_user']}}">
                </div>
                <div class="col-md-6">
                    <label for="lastname">Apellidos: </label>
                    <input type="text" class="form-control form-control-sm" id="lastname" placeholder="Apellidos" value="{{$dataMSISDN['lastname_user']}}">
                </div>
                <div class="col-md-6">
                    <label for="lastname">Email: </label>
                    <input type="email" class="form-control form-control-sm" id="email" placeholder="Email" value="{{$dataMSISDN['email_user']}}">
                </div>
                <div class="col-md-6">
                    <label for="lastname">Celular: </label>
                    <input type="email" class="form-control form-control-sm" id="cellphone" placeholder="Celular" value="{{$dataMSISDN['cellphone_user']}}">
                </div>
                <div class="col-md-6">
                    <label for="lastname">Monto: </label>
                    <input type="text" class="form-control form-control-sm" id="amountView" placeholder="Monto" readonly>
                </div>
                <div class="col-md-6">
                    <label for="lastname">Concepto: </label>
                    <input type="email" class="form-control form-control-sm" id="concepto" placeholder="Concepto" value="Compra de GB's Altcel.">
                </div>
                <div class="col-md-4">
                    <label for="channel">Métodos de Pago</label>
                    <select id="channel" class="form-control form-control-sm">
                        <option selected value="0">Choose...</option>
                        @foreach($channels as $channel)
                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                <input type="hidden" id="client_id" value="{{$dataMSISDN['id_user']}}">
                <input type="hidden" id="number_id" value="{{$dataMSISDN['number_id']}}">
                <input type="hidden" id="referencestype_id" value="5">
                <input type="hidden" id="amount" value="0">
                <input type="hidden" id="rate_id" value="0">
                <input type="hidden" id="offer_id" value="0">

                <div class="col-md-12 mt-md">
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" id="accept" data-type="referencia">Referencia <i class="fa fa-money"></i></button>
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" id="acceptCard" data-type="tarjeta">Tarjeta <i class="fa fa-money"></i></button>
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" id="cancel">Cancelar <i class="fa fa-times-circle"></i></button>
                </div>
                
            </div>
        </form>
    </div>
</section>

<!-- Modal de referencia OpenPay -->
<div class="modal fade" id="reference" tabindex="-1" aria-labelledby="reference" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="referenceLabel">Referencia</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div>
            <iframe class="col-md-12" id="reference-pdf" style="height: 400px;" src=""></iframe>
        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
        </div>
    </div>
</div>
<!-- Modal de referencia OxxoPay -->
<div class="modal fade" id="referenceOxxo" tabindex="-1" aria-labelledby="referenceOxxo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="referenceOxxoLabel">Referencia OXXOPay</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="opps">
                <div class="opps-header">
                    <div class="opps-reminder">Ficha digital, puedes capturar la pantalla. No es necesario imprimir.</div>
                        <div class="opps-info">
                            <div class="opps-brand"><img src="{{asset('storage/uploads/oxxopay_brand.png')}}" alt="OXXOPay"></div>
                            <div class="opps-ammount">
                                <h3 class="title-3">Monto a pagar</h3>
                                <!-- <h2>$ 0,000.00 <sup>MXN</sup></h2> -->
                                <h2 id="montoOxxo"></h2>
                                <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3 class="title-3">Referencia</h3>
                            <h1 class="referenceOxxoCard" id="referenceOxxoCard"></h1>
                        </div>
                    </div>
                    <div class="opps-instructions">
                        <h3 class="title-3">Instrucciones</h3>
                        <ol class="instructions">
                            <li style="margin-top: 10px;color: #000000;">Acude a la tienda OXXO más cercana. <a class="search-oxxo" href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
                            <li style="margin-top: 10px;color: #000000;">Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
                            <li style="margin-top: 10px;color: #000000;">Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
                            <li style="margin-top: 10px;color: #000000;">Realiza el pago correspondiente con dinero en efectivo.</li>
                            <li style="margin-top: 10px;color: #000000;">Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
                        </ol>
                        <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div>
                    </div>
                </div>	
            <div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
        </div>
    </div>
</div>

    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script> -->
<script>
    $(document).ready(function(){
        let name = "{{$user_complete}}";
        let role = "{{$role}}";
        let id_auth = "{{$user_id}}"
        $('#name_auth').html(name);
        $('#role_auth').html('Perfil: '+role);
        $('#user_id').val(id_auth);
    })

var token = '{{$token}}';
var tokenCSRF = $('meta[name="csrf-token"]').attr('content');

$('.btn-buy').click(function(){
    let price = $(this).attr('data-price');
    let rate_id = $(this).attr('data-rate-id');
    let offer_id = $(this).attr('data-offer-id');
    $('#amountView').val(parseFloat(price).toFixed(2));
    $('#amount').val(price);
    $('#rate_id').val(rate_id);
    $('#offer_id').val(offer_id);
    $('#formPayment').removeClass('d-none');
});

$('#cancel').click(function(){
    $('#formPayment').addClass('d-none');
});

$('#accept, #acceptCard').click(function(){
    let rate = $('#rate_id').val();
    let offer_id = $('#offer_id').val();
    let channel = $('#channel').val();
    let number_id = $('#number_id').val();
    let name = $('#name').val();
    let lastname = $('#lastname').val();
    let email = $('#email').val();
    let cel_destiny_reference = $('#cellphone').val();
    let amount = $('#amount').val();
    let concepto = $('#concepto').val();
    let user_id = $('#user_id').val();
    let client_id = $('#client_id').val();
    let referencestype = $('#referencestype_id').val();
    let pay_id = '';
    let data, url;
    let paymentMethod = $(this).attr('data-type');

    if(paymentMethod == 'referencia'){
        
        if(channel == 0){
            Swal.fire({
                icon: 'error',
                title: 'Método de pago no elegido.',
                text: 'Por favor elige un método de pago.',
                showConfirmButton: false,
                timer: 2000
            });
            return false;
        }

        data = {
            token:token, number_id: number_id, name: name, lastname: lastname, email: email,
            cel_destiny_reference: cel_destiny_reference, amount: amount, offer_id: offer_id,
            concepto: concepto, type: referencestype, channel: channel, rate_id: rate, user_id: user_id,
            client_id: client_id, pay_id: pay_id, quantity: 1,from:'client'
        };
        url = "{{url('/generateReferenceAPI')}}";

        
    }else if(paymentMethod == 'tarjeta'){
        data = {
            _token:tokenCSRF,
            name:name,lastname: lastname,
            email:email, cellphone:cel_destiny_reference,
            amount:amount,concepto:concepto,
            referencestype:referencestype,
            offer_id: offer_id, rate_id:rate,
            number_id:number_id, channel_id:3,
            client_id:client_id, pay_id:pay_id
        }
        url = "{{url('/send-card-payment')}}";
    }

    console.log(data);
    // return false;

    if(paymentMethod == 'referencia'){
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            success: function(response){
                console.log(response);
                if(response.status == 'Token is Expired'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Ooops!',
                        text: 'El tiempo de espera se excedió.',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    setTimeout(function(){ location.reload(); }, 3000);
                    return false;
                }else if(response.status == 'Token is Invalid'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Ooops!',
                        text: 'Al parecer ya has solicitado una recarga, por razones de seguridad se refrescará la pantalla para que puedas solicitar otra recarga.',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    setTimeout(function(){ location.reload(); }, 3000);
                    return false;
                }else if(response.status == 'Authorization Token not found'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Ooops!',
                        text: 'Al parecer no tienes permiso para realizar esta acción, se refrescará la pantalla.',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    setTimeout(function(){ location.reload(); }, 3000);
                    return false;
                }else{
                    response = JSON.parse(response);
                    // console.log(response);
                    token = token+'xxXXxx';
                    if(channel == 1){
                    // referenceWhatsapp = response.reference;
                    pdfPaynet(response.reference,cel_destiny_reference,name,lastname);
                    }else if(channel == 2){
                        // referenceWhatsapp = response.charges.data[0].payment_method.reference;
                        showOxxoPay(response.amount,response.charges.data[0].payment_method.reference);
                    }
                }

                
            }
        });
    }else if(paymentMethod == 'tarjeta'){
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(data){
                console.log(data);
                link = data.url
                window.location.href = link;
            }
        });
    }
});

function pdfPaynet(reference,celphone,name,lastname){
        let link = 'https://dashboard.openpay.mx/paynet-pdf/m3one5bybxspoqsygqhz/'+reference;
        // let link = 'https://sandbox-dashboard.openpay.mx/paynet-pdf/mvtmmoafnxul8oizkhju/'+reference;
        $('#referenceWhatsapp').removeClass('d-none');
        $('#btn-reference-openpay').removeClass('d-none');
        $('#reference-pdf').removeClass('d-none');
        $('#reference-pdf').attr('src', link);
        $('#reference').modal('show');
        // window.open('https://api.whatsapp.com/send?phone=52'+celphone+'&text=Hola, '+name+' '+lastname+', puedes descargar tu referencia de pago de Altcel accediendo a la siguiente dirección: https://sandbox-dashboard.openpay.mx/paynet-pdf/mvtmmoafnxul8oizkhju/'+reference, '_blank');
    }

    function showOxxoPay(amount,reference){
        amount = amount/100;
        $('#referenceWhatsapp2').removeClass('d-none');
        $('#btn-reference-oxxo').removeClass('d-none');
        $('#montoOxxo').html('$'+amount.toFixed(2)+'<sup>MXN</sup>');
        $('#referenceOxxoCard').html(reference);
        $('#referenceOxxo').modal('show');
    }
</script>
@endsection