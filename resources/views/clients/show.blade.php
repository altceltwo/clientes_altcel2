@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>datos de <strong>{{$name.' '.$lastname}}</strong></h2>
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

                <h2 class="panel-title">Editar</h2>
            </header>
            <div class="panel-body">
                <form class="form-horizontal form-bordered" action="{{route('clients.update', ['client' => $id])}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                                                <input class="form-control" type="text" placeholder="Nombre" id="name" name="name" value="{{$name}}">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Apellido" id="lastname" name="lastname" value="{{$lastname}}">
                                            </div>

                                            <div class="input-group finput-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input autocomplete="off" class="form-control" type="text" placeholder="RFC" id="rfc" name="rfc" value="{{$rfc}}">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-calendar"></i></span>
                                                </span>
                                                <input class="form-control" type="date" id="date_born" name="date_born" value="{{$date_born}}">
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
                                                <input class="form-control" type="text" placeholder="Dirección" id="address" name="address" value="{{$address}}">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-envelope"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Email" id="email" name="email" value="{{$email}}">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-user"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Código INE" id="ine_code" name="ine_code" value="{{$ine_code}}">
                                            </div>

                                            <div class="input-group input-group-icon">
                                                <span class="input-group-addon">
                                                    <span class="icon"><i class="fa fa-phone"></i></span>
                                                </span>
                                                <input class="form-control" type="text" placeholder="Teléfono Contacto" id="cellphone" name="celphone" maxlength="10" value="{{$cellphone}}">
                                            </div>
                                        </section>
                                    </div>
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
<script>
$('#rfc').keyup(function(){
        let rfc = $(this).val();
        let anio = rfc.substring(4,6);
        let mes = rfc.substring(6,8);
        let dia = rfc.substring(8,10);
        let date = '';
        let id = $(this).attr('id');

        if(rfc.length == 10){
            anio = new Date(anio);
            anio = anio.getFullYear();
            date = anio+'-'+mes+'-'+dia;
            $('#date_born').val(date);
        }
    });
document.getElementById("ine_front").onchange = function(event) {
    console.log($(this).val());
    var file = event.target.files[0];
  var reader = new FileReader();
  reader.onload = function(event) {
    var img = document.getElementById('ine_front_img');
    img.src= event.target.result;
  }
  reader.readAsDataURL(file);
}

document.getElementById("ine_behind").onchange = function(event) {
    console.log($(this).val());
    var file = event.target.files[0];
  var reader = new FileReader();
  reader.onload = function(event) {
    var img = document.getElementById('ine_behind_img');
    img.src= event.target.result;
  }
  reader.readAsDataURL(file);
}

document.getElementById("comprobante_address").onchange = function(event) {
    console.log($(this).val());
    var file = event.target.files[0];
  var reader = new FileReader();
  reader.onload = function(event) {
    var img = document.getElementById('comprobante_address_img');
    img.src= event.target.result;
  }
  reader.readAsDataURL(file);
}
</script>
@endsection