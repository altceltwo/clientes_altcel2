@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Pago de Servicios <strong>{{$data_client['service']}}</strong> con tarjeta</h2>
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

                <h2 class="panel-title">Tarjeta</h2>
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
                                        <input class="form-control" type="text" id="name" placeholder="Nombre" value="{{$data_client['client_name']}}">
                                    </div>
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-user"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="lastname" placeholder="Apellidos" value="{{$data_client['client_lastname']}}">
                                    </div>
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-envelope"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="email" placeholder="Email" value="{{$data_client['client_email']}}">
                                    </div>
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fa fa-phone"></i></span>
                                        </span>
                                        <input class="form-control" type="text" id="cellphone" placeholder="Celular" value="{{$data_client['client_phone']}}">
                                    </div>
                                   
                                </section>
                            </div>
                        </div>
                        <h3>Datos Extras</h3>
                        
                        @if($datos['referencestype'] == 1)
                        <div class="form-group col-md-6">
                            <label for="lastname">Monto: </label>
                            <input type="text" class="form-control form-control-sm" id="amount" placeholder="Monto" value="{{$datos['rate_price']}}" readonly>
                        </div>
                        <input type="hidden" id="offer_id" value="{{$datos['offer_id']}}">
                        <input type="hidden" id="rate_id" value="{{$datos['rate_id']}}">
                        <input type="hidden" id="number_id" value="{{$datos['number_id']}}">
                        @elseif($datos['referencestype'] == 2)
                        <div class="form-group col-md-6">
                            <label for="lastname">Monto: </label>
                            <input type="text" class="form-control form-control-sm" id="amount" placeholder="Monto" value="{{$datos['pack_price']}}" readonly>
                        </div>
                        <input type="hidden" id="pack_id" value="{{$datos['pack_id']}}">
                        @endif

                        <div class="form-group col-md-6">
                            <label for="lastname">Concepto: </label>
                            <input type="text" class="form-control form-control-sm" id="concepto" placeholder="Concepto" value="{{$datos['concepto']}}">
                        </div>

                        <input type="hidden" id="referencestype" value="{{$datos['referencestype']}}">
                        <input type="hidden" id="channel_id" value="{{$datos['channel_id']}}">
                        <input type="hidden" id="pay_id" value="{{$data_client['pay_id']}}">
                        <input type="hidden" id="client_id" value="{{$data_client['client_id']}}">
                        
                        <div class="form-actions col-md-12">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" id="card_button">
                                <span class="spinner-border spinner-border-sm" id="spinner" role="status" aria-hidden="true"></span>
                                <i class="fas fa-file-invoice-dollar"></i> Pagar
                            </button>
                        </div>
                    </div>              

                </form>
            </div>
        </section>

    </div>
</div>
    
<script>
$('#card_button').click(function(){
    let name = $('#name').val();
    let lastname = $('#lastname').val();
    let email = $('#email').val();
    let cellphone = $('#cellphone').val();
    let amount = $('#amount').val();
    let concepto = $('#concepto').val();
    let token = $('meta[name="csrf-token"]').attr('content');
    let channel_id = $('#channel_id').val();
    let client_id = $('#client_id').val();
    let pay_id = $('#pay_id').val();
    let link = '';
    let data = {};
    let referencestype = $('#referencestype').val();
    $(this).attr('disabled',true);
    
    if(referencestype == 1){
        let offer_id = $('#offer_id').val();
        let rate_id = $('#rate_id').val();
        let number_id = $('#number_id').val();
        data = {
            _token:token,
            name:name,lastname: lastname,
            email:email, cellphone:cellphone,
            amount:amount,concepto:concepto,
            referencestype:referencestype,
            offer_id: offer_id, rate_id:rate_id,
            number_id:number_id, channel_id:channel_id,
            client_id:client_id, pay_id:pay_id
        }
    }else if(referencestype == 2){
        let pack_id = $('#pack_id').val();
        data = {
            _token:token,
            name:name,lastname: lastname,
            email:email, cellphone:cellphone,
            amount:amount,concepto:concepto,
            referencestype:referencestype,
            channel_id:channel_id, client_id:client_id,
            pay_id:pay_id
        }
    }
    
    $.ajax({
        url: "{{url('/send-card-payment')}}",
        method: 'POST',
        data: data,
        success: function(data){
            console.log(data);
            link = data.url
            window.location.href = link;
        }
    });
});
</script>
@endsection