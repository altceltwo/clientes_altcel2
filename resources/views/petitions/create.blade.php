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

                <h2 class="panel-title">Alta de Solicitud</h2>
            </header>
            <div class="panel-body">
                <form class="form-horizontal form-bordered" action="{{route('petition.store')}}" method="POST" enctype="multipart/form-data" id="formPetition">
                @csrf
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                               
                                <div class="col-md-12">
                                    <h3>Cliente</h3>
                                    
                                </div>

                                <div class="col-md-12 mb-lg" id="msisdn-select">
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
                                        data-date_born="{{$client->date_born}}"
                                    >
                                        {{$client->name.' '.$client->lastname}}
                                    </option>
                                    @endforeach
                                        </optgroup>
                                        
                                    </select>
                                </div>

                                <input type="hidden" name="client_id" id="client_id" value="0">

                                <div class="form-group col-md-6">
                                    <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                                    <div class="col-md-12">
                                        <section class="form-group-vertical">
                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Nombre" id="name" name="name" required>
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Apellido" id="lastname" name="lastname" required>
                                            </div>

                                            <div class="input-group finput-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input autocomplete="off" class="form-control" type="text" placeholder="RFC" id="rfc" name="rfc">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-calendar"></i></span>
                                                </span>
                                                <input class="form-control" type="date" id="date_born" name="date_born">
                                            </div>
                                        </section>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                                    <div class="col-md-12">
                                        <section class="form-group-vertical">
                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-home"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Dirección" id="address" name="address" required>
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-envelope"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Email" id="email" name="email">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Código INE, Cédula o Pasaporte" id="ine_code" name="ine_code">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-phone"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Teléfono Contacto" id="cellphone" name="cellphone" maxlength="10" required>
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-xs mb-md">
                                    <label for="product">Producto</label>
                                    <select id="product" name="product" class="form-control form-control-sm" required="">
                                        <option selected value="Ninguno">Ninguno...</option>
                                        <option value="HBB">HBB</option>
                                        <option value="MIFI">MIFI</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-xs mb-md">
                                    <div class="col-md-4">
                                        <label for="comment">Comentario (opcional):</label>
                                        <textarea class="form-control" name="comment" id="comment" cols="30" rows="3"></textarea>
                                    </div>
                                </div>

                                <input type="hidden" name="user" id="user" value="{{ Auth::user()->id }}" required>

                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <button type="button" class="btn btn-primary" id="send">Guardar</button>
                                    <!-- <button type="button" class="btn btn-success" id="date-pay">Date Pay</button> -->
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
            $('#client_id').val(0);
            $('#name').val('');
            $('#lastname').val('');
            $('#rfc').val('');
            $('#date_born').val('');

            $('#address').val('');
            $('#email').val('');
            $('#ine_code').val('');
            $('#cellphone').val('');
        }
        else{
            name = $('#client option:selected').attr('data-name');
            lastname = $('#client option:selected').attr('data-lastname');
            address = $('#client option:selected').attr('data-address');
            email = $('#client option:selected').attr('data-email');
            rfc = $('#client option:selected').attr('data-rfc');
            ine_code = $('#client option:selected').attr('data-ine_code');
            cellphone = $('#client option:selected').attr('data-cellphone');
            date_born = $('#client option:selected').attr('data-date_born');

            $('#client_id').val(client_id);
            $('#name').val(name);
            $('#lastname').val(lastname);
            $('#rfc').val(rfc);
            $('#date_born').val(date_born);

            $('#address').val(address);
            $('#email').val(email);
            $('#ine_code').val(ine_code);
            $('#cellphone').val(cellphone);
        }
    }

    $('#cellphone').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('#rfc').keyup(function(){
        let rfc = $(this).val();
        let anio = rfc.substring(4,6);
        let mes = rfc.substring(6,8);
        let dia = rfc.substring(8,10);
        let date = '';
        let id = $(this).attr('id');
        $(this).val($(this).val().toUpperCase());
        if(rfc.length == 10){
            anio = new Date(anio);
            anio = anio.getFullYear();
            date = anio+'-'+mes+'-'+dia;
            $('#date_born').val(date);
        }
    });

    $('#send').click(function(){
        let client_id = $('#client_id').val();
        let name = $('#name').val();
        let lastname = $('#lastname').val();
        let rfc = $('#rfc').val();
        let date_born = $('#date_born').val();
        let address = $('#address').val();
        let email = $('#email').val();
        let ine_code = $('#ine_code').val();
        let cellphone = $('#cellphone').val();
        let product = $('#product').val();

        if(name.length == 0 || /^\s+$/.test(name)){
            let message = "El campo Nombre no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('name').focus();
            return false;
        }

        if(lastname.length == 0 || /^\s+$/.test(lastname)){
            let message = "El campo Apellido no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('lastname').focus();
            return false;
        }

        if(rfc.length == 0 || /^\s+$/.test(rfc)){
            let message = "El campo RFC no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('rfc').focus();
            return false;
        }

        if(date_born.length == 0 || /^\s+$/.test(date_born)){
            let message = "El campo Fecha Nacimiento no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('date_born').focus();
            return false;
        }

        if(address.length == 0 || /^\s+$/.test(address)){
            let message = "El campo Dirección no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('address').focus();
            return false;
        }

        if(email.length == 0 || /^\s+$/.test(email)){
            let message = "El campo Email no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('email').focus();
            return false;
        }

        if(ine_code.length == 0 || /^\s+$/.test(ine_code)){
            let message = "El campo Código INE no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('ine_code').focus();
            return false;
        }

        if(cellphone.length == 0 || /^\s+$/.test(cellphone)){
            let message = "El campo Teléfono Contacto no puede estar vacío.";
            sweetAlertFunction(message);
            document.getElementById('cellphone').focus();
            return false;
        }

        if(product == 'Ninguno'){
            let message = "Debe elegir un tipo de producto.";
            sweetAlertFunction(message);
            document.getElementById('product').focus();
            return false;
        }

        $('#formPetition').submit();

    });

    function sweetAlertFunction(message){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            showConfirmButton: false,
            timer: 2000
        });
    }
</script>
@endsection