@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Recarga de <strong>{{$service}}</strong></h2>
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
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="#" class="fa fa-caret-down"></a>
                    <a href="#" class="fa fa-times"></a>
                </div>

                <h2 class="panel-title">Generar Referencia</h2>
            </header>
            <div class="panel-body">
                <form class="form-horizontal form-bordered" method="get">
                    <div class="form-group"  style="padding-right: 1rem; padding-left: 1rem;">
                        <h3>Información personal</h3>
                        <div class="form-group col-md-12">
                            <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                            <div class="col-md-12">
                                <section class="form-group-vertical">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-user"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="name" placeholder="Nombre" value="{{$name}}">
                                    </div>
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-user"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="lastname" placeholder="Apellidos" value="{{$lastname}}">
                                    </div>
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-envelope"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="email" value="{{$email}}" placeholder="Email">
                                    </div>
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-phone"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="cellphone" placeholder="Celular" value="{{$cellphone}}">
                                    </div>
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                            Número al que se enviará la referencia generada.
                                        </div>
                                </section>
                            </div>
                        </div>
                        <h3>Datos Extras</h3>

                        <div class="form-group col-md-6" id="content-rate">
                            <label for="exampleFormControlSelect1">Ofertas: </label>
                            <select class="form-control form-control-sm">
                                <option selected value="{{$rate_id}}">{{$rate_name}}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="lastname">Número de tu Producto: </label>
                            <input type="text" class="form-control form-control-sm" placeholder="Servicio" value="{{$number_product}}" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="lastname">Monto: </label>
                            <input type="text" class="form-control form-control-sm" placeholder="Monto" value="{{number_format($rate_amount,2)}}" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="lastname">Concepto: </label>
                            <input type="text" class="form-control form-control-sm" id="concepto" placeholder="Concepto" value="{{$concept}}">
                        </div>
                        <div class="form-group col-md-6" id="content-rate">
                            <label for="exampleFormControlSelect1">Canal de Pago: </label>
                            <select class="form-control form-control-sm" id="channel" >
                                <option value="0">Nothing</option>
                                @foreach($channels as $channel)
                                <option value="{{$channel->id}}">{{$channel->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" id="rate" value="{{$rate_id}}">
                        <input type="hidden" id="amount" value="{{number_format($rate_amount,2)}}">
                        <input type="hidden" id="number_id" value="{{$number_id}}">
                        <input type="hidden" id="referencestype_id" value="{{$referencestype}}">
                        <input type="hidden" id="offer_id" value="{{$offer_id}}">
                        <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                        <input type="hidden" id="client_id" value="{{Auth::user()->id}}">
                        <input type="hidden" id="activation_id" value="{{$activation_id}}">
                        
                        <div class="form-actions col-md-12">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" id="pay_generate"><span class="spinner-border spinner-border-sm d-none" id="spinner-pay_generate" role="status" aria-hidden="true"></span><i class="fas fa-file-invoice-dollar"></i> Generar</button>
                            
                            <!-- <button type="button" class="btn btn-outline-success" id="oxxo" data-channel="2"><span class="spinner-border spinner-border-sm d-none" id="spinner-oxxo" role="status" aria-hidden="true"></span><i class="fas fa-file-invoice-dollar"></i> Oxxo</button> -->
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-map-marker"></i> Mapa</button>
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary d-none" id="btn-reference-openpay" data-toggle="modal" data-target="#reference"><i class="fa fa-eye"></i> Referencia OpenPay</button>
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary d-none" id="btn-reference-oxxo" data-toggle="modal" data-target="#referenceOxxo"><i class="fa fa-eye"></i> Referencia OXXOPay</button>
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success d-none" id="referenceWhatsapp"><i class="fa fa-mobile-phone"></i> Whatsapp</button>
                        </div>
                    </div>              

                </form>
            </div>
        </section>

    </div>
</div>
<!-- Modal de mapa -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Puntos de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div>
                <iframe class="col-md-12" style="height: 400px;" src="https://www.paynet.com.mx/mapa-tiendas/index.html?locationNotAllowed=true"></iframe>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
            </div>
        </div>
    </div>
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
                        <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
                            <div class="opps-info">
                                <div class="opps-brand"><img src="{{asset('storage/uploads/oxxopay_brand.png')}}" alt="OXXOPay"></div>
                                <div class="opps-ammount">
                                    <h3>Monto a pagar</h3>
                                    <!-- <h2>$ 0,000.00 <sup>MXN</sup></h2> -->
                                    <h2 id="montoOxxo"></h2>
                                    <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                                </div>
                            </div>
                            <div class="opps-reference">
                                <h3>Referencia</h3>
                                <h1 id="referenceOxxoCard"></h1>
                            </div>
                        </div>
                        <div class="opps-instructions">
                            <h3>Instrucciones</h3>
                            <ol>
                                <li style="margin-top: 10px;color: #000000;">Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
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
var dataPay, referenceWhatsapp = '';
// var x = '';

    $('#pay_generate').click(function(){
        let channelID = $(this).attr('id');
        $('#spinner-'+channelID).removeClass('d-none');
        $(this).attr('disabled',true);
        
        let channel = $('#channel').val();
        let number_id = $('#number_id').val();
        let name = $('#name').val();
        let lastname = $('#lastname').val();
        let email = $('#email').val();
        let cel_destiny_reference = $('#cellphone').val();
        let amount = $('#amount').val();
        let offer_id = $('#offer_id').val();
        let concepto = $('#concepto').val();
        let rate_id = $('#rate').val();
        let user_id = $('#user_id').val();
        let client_id = $('#client_id').val();
        let type = $('#referencestype_id').val();
        let activation_id = $('#activation_id').val();
        let token = $('meta[name="csrf-token"]').attr('content'); 
        let headers = {headers: {'Content-type': 'application/json'}};
        let data = {
                _token:token, number_id: number_id, name: name, lastname: lastname, email: email,
                cel_destiny_reference: cel_destiny_reference, amount: amount, offer_id: offer_id,
                concepto: concepto, type: type, channel: channel, rate_id: rate_id, user_id: user_id,
                client_id: client_id, activation_id: activation_id, quantity: 1
            };
            
            if(channel == 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Debe elegir un método de pago.',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#spinner-'+channelID).addClass('d-none');
                $(this).attr('disabled',false);
                $('#channel').focus();
                return false;
            }

            // console.log(data);
            
            $.ajax({
                url: "{{url('/create-reference-openpay')}}",
                method: "POST",
                data: data,
                success: function(response){
                    if(channel == 1){
                        referenceWhatsapp = response.reference;
                        pdfPaynet(response.reference,cel_destiny_reference,name,lastname);
                    }else if(channel == 2){
                        referenceWhatsapp = response.charges.data[0].payment_method.reference;
                        showOxxoPay(response.amount,response.charges.data[0].payment_method.reference);
                    }
                    $('#pay_generate').attr('disabled',false);
                }
            });
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
        $('#btn-reference-oxxo').removeClass('d-none');
        $('#montoOxxo').html('$'+amount.toFixed(2)+'<sup>MXN</sup>');
        $('#referenceOxxoCard').html(reference);
        $('#referenceOxxo').modal('show');
    }

    $('#referenceWhatsapp').click(function(){
        let name = $('#name').val();
        let lastname = $('#lastname').val();
        let cel_destiny_reference = $('#cellphone').val();
        window.open('https://api.whatsapp.com/send?phone=52'+cel_destiny_reference+'&text=Hola, '+name+' '+lastname+', puedes descargar tu referencia de pago de Altcel accediendo a la siguiente dirección: https://dashboard.openpay.mx/paynet-pdf/m3one5bybxspoqsygqhz/'+referenceWhatsapp, '_blank');
    });
</script>
@endsection