@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Solicitudes de Otro Tipo</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="{{route('home')}}">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Dashboard</span></li>
        </ol>
        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
    </div>
</header>

<div class="col-md-12">
    <form id="formPetition" action="{{route('sendOtherPetition')}}" method="POST" class="form-horizontal">
        @csrf
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="#" class="fa fa-caret-down"></a>
                </div>

                <h2 class="panel-title">Solicitud</h2>
            </header>
            <div class="panel-body">
                <div class="validation-message">
                    <ul></ul>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tipo <span class="required">*</span></label>
                    <div class="col-sm-9">
                        <select id="type" name="type" class="form-control" required>
                            <option value="0">Elige un tipo...</option>
                            <option value="lockImei" data-desc="BLOQUEO DE IMEI">Bloqueo de IMEI</option>
                            <option value="unlockImei" data-desc="DESBLOQUEO DE IMEI">Desbloqueo de IMEI</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Clientes <span class="required">*</span></label>
                    <div class="col-sm-9">
                        <select data-plugin-selectTwo class="form-control populate" id="number_id" name="number_id">
                            <optgroup label="Clientes disponibles">
                            <option value="0">Elige...</option>
                            @foreach($clients as $client)
                                <option value="{{$client->number_id}}"
                                    data-name="{{$client->name}}"
                                    data-lastname="{{$client->lastname}}"
                                    data-email="{{$client->email}}" 
                                    data-msisdn="{{$client->msisdn}}">
                                    {{$client->msisdn.' - '.$client->name.' '.$client->lastname.' - '.$client->email}}
                                </option>
                            @endforeach
                            </optgroup>
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Comentario <span class="required">*</span></label>
                    <div class="col-sm-9">
                        <textarea name="comment" rows="5" class="form-control" placeholder="Escribe una breve descripción para que sea más clara la solicitud" required></textarea>
                    </div>
                </div>

                <input type="hidden" id="description" name="description">
                <input type="hidden" id="name" name="name">
                <input type="hidden" id="lastname" name="lastname">
                <input type="hidden" id="email" name="email">
                <input type="hidden" id="subject" name="subject">
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="button" class="btn btn-success" id="send">Enviar</button>
                    </div>
                </div>
            </footer>
        </section>
    </form>
</div>

<script>
    $('#send').click(function(){
        let type = $('#type').val();
        let type_desc = $('#type option:selected').data('desc');
        let number_id = $('#number_id').val();
        let name = $('#number_id option:selected').data('name');
        let lastname = $('#number_id option:selected').data('lastname');
        let email = $('#number_id option:selected').data('email');
        let msisdn = $('#number_id option:selected').data('msisdn');
        let description;

        if(type == 0){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Elige un tipo de solicitud.',
                showConfirmButton: false,
                timer: 2000
            });
            return false;
        }

        if(number_id == 0){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Elige un cliente al que se asignará la solicitud.',
                showConfirmButton: false,
                timer: 2000
            });
            return false;
        }

        description = "se solicita "+type_desc+" para el número "+msisdn+",";
        $('#description').val(description);
        $('#name').val(name);
        $('#lastname').val(lastname);
        $('#email').val(email);
        $('#subject').val('SOLICITUD DE '+type_desc);
        // alert(description);
        $('#formPetition').submit();
    });
</script>
@endsection