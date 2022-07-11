@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Solicitud</h2>
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
@if(session('error'))
    <div class="alert alert-danger" >
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4 class="alert-heading">Upps!!</h4>
        <p>{!!session('error')!!}</p>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success" >
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4 class="alert-heading">Well done!!</h4>
        <p>{!!session('success')!!}</p>
    </div>
@endif
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="#" class="fa fa-caret-down"></a>
                    <a href="#" class="fa fa-times"></a>
                </div>

                <h2 class="panel-title">Solicitud de Activación</h2>
            </header>
            <div class="panel-body">
                <form class="form-horizontal form-bordered" action="{{route('petition.storeDealer')}}" method="POST" enctype="multipart/form-data" id="formPetition">
                @csrf
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">

                                <div class="col-md-12 mb-lg">
                                    <label>Clientes</label>
                                    <select data-plugin-selectTwo class="form-control populate" id="client" onchange="getData()">
                                        <optgroup label="Clientes disponibles">
                                        <option value="0">Elige...</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}"
                                                data-name="{{$client->name}}"
                                                data-lastname="{{$client->lastname}}"
                                                data-address="{{$client->address}}"
                                                data-email="{{$client->email}}"
                                                data-rfc="{{$client->rfc}}"
                                                data-ine_code="{{$client->ine_code}}"
                                                data-cellphone="{{$client->cellphone}}"
                                                data-date_born="{{$client->date_born}}">
                                                {{$client->name.' '.$client->lastname.' - '.$client->email}}
                                            </option>
                                        @endforeach
                                        </optgroup>
                                        
                                    </select>
                                </div>

                                <input type="hidden" name="client_id" id="client_id" value="0">

                                <div class="col-md-4 mb-lg" >
                                    <label>SIM:</label>
                                    <select data-plugin-selectTwo class="form-control populate" id="number" onchange="getDataNumber()">
                                        <optgroup label="SIM's disponibles">
                                        <option value="0">Elige...</option>
                                        @foreach($numbers as $number)
                                            <option value="{{$number->number_id}}" data-product="{{trim($number->product)}}" data-icc="{{$number->icc}}" data-msisdn="{{$number->msisdn}}">{{$number->icc.' - '.$number->product}}</option>
                                        @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-xs mb-md">
                                    <label for="rates">Plan Activación</label>
                                    <select id="rates" name="rate_activation" class="form-control form-control-sm" required="">
                                        <option value="0" selected>Elige...</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-lg" >
                                    <label>Dispositivo:</label>
                                    <select data-plugin-selectTwo class="form-control populate" id="device" onchange="getDataDevice()">
                                        <optgroup label="Dispositivos disponibles">
                                        <option value="0">Elige...</option>
                                        @foreach($devices as $device)
                                            <option value="{{$device->device_id}}" data-imei="{{$device->imei.' - '.$device->description}}">{{$device->imei.' - '.$device->description}}</option>
                                        @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-xs mb-md coordinates d-none">
                                    <label for="rates">Latitud:</label>
                                    <input type="text" class="form-control" name="lat" id="lat">
                                </div>
                                <div class="col-md-4 mt-xs mb-md coordinates d-none">
                                    <label for="rates">Longitud:</label>
                                    <input type="text" class="form-control" name="lng" id="lng">
                                </div>
                                <div class="col-md-4 mt-xs mb-md">
                                    <label for="rates">Fecha de Activación</label>
                                    <input type="date" class="form-control" name="date_activation" id="date_activation">
                                </div>

                                <div class="col-md-4">
                                    <label for="serial_number" class="form-label">No. Serie</label>
                                    <input type="text" class="form-control" id="serial_number" name="serial_number" required >
                                </div>
                                <div class="col-md-4" id="content-MAC">
                                    <label for="mac_address_activation" class="form-label">MAC Address</label>
                                    <input type="text" class="form-control" id="mac_address_activation" name="mac_address" required >
                                    <input type="hidden" id="mac_address_boolean" value="0">
                                </div><br>
               
                                <div class="col-md-12">
                                    <label for="comment">Descripción:</label>
                                    <textarea class="form-control" name="comment" id="comment" cols="30" rows="3" placeholder="Agrega un comentario importante respecto a esta solicitud para evitar confusiones..."></textarea>
                                </div>

                                <input type="hidden" name="user" id="user" value="{{ Auth::user()->id }}" required>
                                <input type="hidden" name="number_id" id="number_id" required>
                                <input type="hidden" name="device_id" id="device_id" required value="0">
                                <input type="hidden" name="productChoosed" id="productChoosed" required value="0">
                                <input type="hidden" name="client_name" id="client_name" required >
                                <input type="hidden" name="client_lastname" id="client_lastname" required >
                                <input type="hidden" name="client_email" id="client_email" required >

                                <div class="alert alert-success col-md-6 mt-md ml-lg mr-lg">
                                    <center><h4><strong>DATOS DE ACTIVACIÓN</strong></h4></center>
                                    <center><h5>Cliente</h5></center>
                                    Nombre: <strong id="nameTag">Sin elegir</strong><br>
                                    Email: <strong id="emailTag">Sin elegir</strong><br>
                                    Teléfono: <strong id="cellphoneTag">Sin elegir</strong><br>
                                    RFC: <strong id="rfcTag">Sin elegir</strong><br>
                                    Dirección: <strong id="addressTag">Sin elegir</strong><br>

                                   <center><h5>SIM Y DIPOSITIVO</h5></center>
                                   ICC: <strong id="iccTag">Sin elegir</strong><br>
                                   Número: <strong id="msisdnTag">Sin elegir</strong><br>
                                   Dispositivo: <strong id="deviceTag">Sin elegir</strong><br>
                                   Plan: <strong id="rateTag">Sin elegir</strong><br>
                                </div>
                    
                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <button type="button" class="btn btn-success" id="send">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<script>

    function getData(){
        let client_id = $('#client').val();
        let name;
        let lastname;
        let address;
        let email;
        let rfc;
        let ine_code;
        let cellphone;
        let date_born;

        if(client_id == 0){
            $('#nameTag').html('Sin elegir');
            $('#rfcTag').html('Sin elegir');
            $('#addressTag').html('Sin elegir');
            $('#emailTag').html('Sin elegir');
            $('#cellphoneTag').html('Sin elegir');
        }else{
            name = $('#client option:selected').attr('data-name');
            lastname = $('#client option:selected').attr('data-lastname');
            address = $('#client option:selected').attr('data-address');
            email = $('#client option:selected').attr('data-email');
            rfc = $('#client option:selected').attr('data-rfc');
            cellphone = $('#client option:selected').attr('data-cellphone');

            $('#client_name').val(name);
            $('#client_lastname').val(lastname);
            $('#client_email').val(email);

            $('#nameTag').html(name+' '+lastname);
            $('#rfcTag').html(rfc);
            $('#addressTag').html(address);
            $('#emailTag').html(email);
            $('#cellphoneTag').html(cellphone);
        }

        $('#client_id').val(client_id);
    }



    function getDataNumber(){
        let number_id = $('#number').val();
        let product = $('#number option:selected').data('product');
        let options = "<option value='0'>Elige...</option>";
        let icc = $('#number option:selected').data('icc');
        let msisdn = $('#number option:selected').data('msisdn');

        if(number_id == 0){
            $('#iccTag').html('Sin elegir');
        }else{
            $.ajax({
                url: "{{route('petition.rates')}}",
                data:{producto: product},
                success: function(response){
                    // console.log(response);
                    let rate_name;
                    
                    response.forEach(function(element){
                        rate_name = element.name;
                        rate_name = rate_name.toLowerCase();
                        if(element.type == 'publico' && !rate_name.includes('alianza')){
                            options+="<option value='"+element.id+"' data-ratename='"+element.name+" - $"+parseFloat(element.price).toFixed(2)+"'>"+element.name+" - $"+parseFloat(element.price).toFixed(2)+"</option>"
                        }
                    });

                    $('#rates').html(options);
                }
            });

            if(product == 'HBB'){
                // console.log('HBB');
                $('.coordinates').removeClass('d-none');
            }else{
                $('.coordinates').addClass('d-none');
                // console.log('IS NOT HBB');
            }

            $('#iccTag').html(icc);
            $('#msisdnTag').html(msisdn);
            $('#productChoosed').val(product);
        }

        $('#number_id').val(number_id);

        
    }

    function getDataDevice(){
        let device_id = $('#device').val();
        let imei = $('#device option:selected').data('imei');

        if(device_id == 0){
            $('#deviceTag').html('Sin elegir');
        }else{
            $('#deviceTag').html(imei);
        }
        $('#device_id').val(device_id);
    }

    $('#rates').change(function(){
        let rate_name = $('#rates option:selected').data('ratename');
        let val = $(this).val();

        if(val == 0){
            $('#rateTag').html('Sin elegir');
        }else{
            $('#rateTag').html(rate_name);
        }
    });

    $('#send').click(function(){
        let client_id = $('#client_id').val();
        let number_id = $('#number_id').val();
        let device_id = $('#device_id').val();
        let rate_activation = $('#rates').val();
        let date_activation = $('#date_activation').val();
        let product = $('#number option:selected').data('product');
        let message;

        if(client_id == 0){
            message = "Por favor elija un cliente.";
            sweetAlertFunction(message);
            return false;
        }

        if(number_id == 0){
            message = "Por favor elija una SIM.";
            sweetAlertFunction(message);
            return false;
        }

        if(rate_activation == 0){
            let message = "Debe elegir un plan de activación.";
            sweetAlertFunction(message);
            document.getElementById('rates').focus();
            return false;
        }

        if(date_activation.length == 0 || /^\s+$/.test(date_activation)){
            let message = "Por favor defina la fecha de activación.";
            sweetAlertFunction(message);
            document.getElementById('date_activation').focus();
            return false;
        }

        if(product == 'HBB'){
            let lat = $('#lat').val();
            let lng = $('#lng').val();

            if(lat.length == 0 || /^\s+$/.test(lat)){
                let message = "Debe ingresar la latitud de donde se ubicará el dispositivo HBB.";
                sweetAlertFunction(message);
                document.getElementById('lat').focus();
                return false;
            }

            if(lng.length == 0 || /^\s+$/.test(lng)){
                let message = "Debe ingresar la longitud de donde se ubicará el dispositivo HBB.";
                sweetAlertFunction(message);
                document.getElementById('lng').focus();
                return false;
            }
        }

        Swal.fire({
            title: '¡ATENCIÓN!',
            text: 'Se sugiere corroborar los datos antes de enviar la solicitud, ¿está seguro de enviar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-success mr-md',
                cancelButton: 'btn btn-danger '
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                // console.log('SUCCESS, TODO GOOD');
                $('#formPetition').submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Operación cancelada',
                    showConfirmButton: false,
                    timer: 1000
                })
            }
        });

    });

    function sweetAlertFunction(message){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            showConfirmButton: false,
            timer: 3000
        });
    }

    $('#mac_address_activation').keyup(function(){
        this.value = 
        (this.value.toUpperCase()
        .replace(/[^\d|A-Z]/g, '')
        .match(/.{1,2}/g) || [])
        .join(":");

        let valor = $(this).val();

        let regex = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
        let tag   = document.getElementById('tag');
        if( regex.test( valor ) ) {
            $('#content-MAC').removeClass('has-error');
            $('#content-MAC').addClass('has-success');
            $('#mac_address_boolean').val(1);
        } else {
            $('#content-MAC').removeClass('has-success');
            $('#content-MAC').addClass('has-error');
            $('#mac_address_boolean').val(0);
        }
    });

</script>
@endsection