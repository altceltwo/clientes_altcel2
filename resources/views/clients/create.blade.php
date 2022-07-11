@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Nuevo Cliente</h2>
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
                </div>

                <h2 class="panel-title">Alta</h2>
            </header>
            <div class="panel-body">
                @if(Auth::user()->role_id != 8)
                <button class="btn btn-primary btn-xs" id="addPM">+ Persona Moral</button>
                @endif
                <form class="form-horizontal form-bordered" action="{{route('clients.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                               
                                <div class="col-md-12">
                                    <h3>Contacto</h3>
                                </div>
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
                                                    <span class="icon"><i class="fa fa-phone"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Teléfono Contacto" id="cellphone" name="celphone" maxlength="10" required>
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                @if(Auth::user()->role_id != 8)
                                <div class="col-md-12 mt-xs mb-md">
                                    <div class="checkbox">
                                        <label class="control-label">
                                            <input type="checkbox" id="type_person">
                                            Persona moral
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-12 d-none moral-person">
                                    <h3>Datos de Persona Moral</h3>
                                </div>

                                <input type="hidden" name="moral_person_bool" id="moral_person_bool" value="0">

                                <div class="form-group col-md-6 d-none moral-person">
                                    <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                                    <div class="col-md-12">
                                        <section class="form-group-vertical">
                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Nombre" id="name2" name="name2" >
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input autocomplete="off" class="form-control" type="text" placeholder="RFC" id="rfc2" name="rfc2">
                                            </div>
                                        </section>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 d-none moral-person">
                                    <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                                    <div class="col-md-12">
                                        <section class="form-group-vertical">
                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-home"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Dirección" id="address2" name="address2" >
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-phone"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Teléfono Contacto" id="cellphone2" name="cellphone2" maxlength="10" >
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-xs mb-md">
                                    <label for="interests">Producto de Interés</label>
                                    <select id="interests" name="interests" class="form-control form-control-sm" required="">
                                        <option selected value="Ninguno">Ninguno...</option>
                                        <option value="HBB">HBB</option>
                                        <option value="MIFI">MIFI</option>
                                        <option value="MOV">MOV</option>
                                        <option value="Portabilidad Telmex">Portabilidad Telmex</option>
                                    </select>
                                </div>

                                <input type="hidden" name="user" id="user" value="{{ Auth::user()->id }}" required>

                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <button type="submit" class="btn btn-primary" id="send">Guardar</button>
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
<!-- Modal de Cliente Nuevo -->
<div class="modal fade" id="personaMoralModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="personaMoralModalLabel">Cliente Nuevo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="display:flex; justify-content:center; margin-left: auto !important;">
                <form class="form-horizontal form-bordered" action="{{route('clients.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                            
                                <div class="col-md-12">
                                    <h3>Añadir Persona Moral</h3>
                                    
                                </div>
                                <div class="col-md-12 mb-lg" id="msisdn-select">
                                    <label>Clientes</label>
                                    <select data-plugin-selectTwo class="form-control populate" id="client" onchange="getData()">
                                        <optgroup label="Clientes disponibles">
                                        <option value="0">Elige...</option>
                                        @foreach($clients as $client)
                                    <option value="{{$client->id}}">
                                        {{$client->name.' '.$client->lastname.' - '.$client->email}}
                                    </option>
                                    @endforeach
                                        </optgroup>
                                        
                                    </select>
                                </div>
                                <input type="hidden" name="client_id" id="client_id">
                                <div class="form-group col-md-6">
                                    <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                                    <div class="col-md-12">
                                        <section class="form-group-vertical">
                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Nombre" id="new_name" name="new_name">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input autocomplete="off" class="form-control" type="text" placeholder="RFC" id="new_rfc" name="new_rfc">
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
                                                <input class="form-control" type="text" placeholder="Dirección" id="new_address" name="new_address">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-phone"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Teléfono Contacto" id="new_cellphone" name="new_cellphone" maxlength="10">
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <button type="button" class="btn btn-primary" id="savePM">Guardar</button>
                                    <!-- <button type="button" class="btn btn-success" id="date-pay">Date Pay</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<script>

$('#cellphone, #ine_code').on('input', function () {
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

    $('#address').keyup(function(){
        let myValue = $(this).val();
        console.log(myValue);
        myValue = myValue.replace(/#/g,'No. ');
        $('#address').val(myValue);
    });

$('#type_person').click(function(){
    $('#name2').val('');
    $('#rfc2').val('');
    $('#address2').val('');
    $('#cellphone2').val('');

    if($('#type_person').prop('checked')) {
       $('.moral-person').removeClass('d-none');
       $('#moral_person_bool').val(1);
    }else{
        $('.moral-person').addClass('d-none');
        $('#moral_person_bool').val(0);
    }
});

    $('#addPM').click(function(){
        $('#personaMoralModal').modal('show');
    });

    function getData(){
        let client_id = $('#client').val();
        $('#client_id').val(client_id);
    }

    $('#savePM').click(function(){
        let client_id = $('#client_id').val();
        let name = $('#new_name').val();
        let rfc = $('#new_rfc').val();
        let address = $('#new_address').val();
        let cellphone = $('#new_cellphone').val();

        if(client_id != 0){
            $.ajax({
                url: "{{route('savePM')}}",
                data: {user_id:client_id, name: name, rfc:rfc, address:address, cellphone:cellphone},
                success: function(response){
                    if(response == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Persona Moral añadida con éxito.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Hubo un problema, consulte a desarrollo.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }else{

        }
    });
</script>
@endsection