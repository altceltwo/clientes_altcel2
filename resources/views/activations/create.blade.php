@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Preactivaciones</h2>
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

<div class="col-md-12">
@if(Auth::user()->role_id == 7 || Auth::user()->role_id == 4)
    <div class="tabs tabs-success">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#home" data-toggle="tab"><i class="fa fa-star"></i> Altan Redes</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane active">
            <!-- Altán Services Accordions -->
                <!-- MIFI Content -->
                <div class="toggle-content">
                    <form class="form-horizontal form-bordered">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>MIFI/HBB/MOV</h3>
                                    </div>
                                    <div class="col-md-6 mt-4 mb-2">
                                        <label class="form-label mr-1" for="number">SIM's:</label><br>
                                        <select class="form-control form-control-sm col-md-6" id="number" disabled>
                                            <option selected value="0">Nothing</option>
                                            @foreach($numbers as $number)
                                            <option value="{{$number->number_id}}" 
                                                data-product="{{$number->producto}}" 
                                                data-msisdn="{{$number->MSISDN}}" 
                                                data-icc="{{$number->ICC}}"
                                                data-product="{{$number->producto}}">
                                                {{$number->ICC.' - '.$number->MSISDN.' - '.$number->producto}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-4 mb-2">
                                        <label class="form-label mr-1" for="device">Dispositivos:</label><br>
                                        <select class="form-control form-control-sm col-md-6" id="device" disabled>
                                            <option selected value="0">Nothing</option>
                                            @foreach($devices as $device)
                                            <option value="{{$device->device_id}}" data-imei="{{$device->imei}}" data-price="{{$device->price}}" data-description="{{$device->description}}">{{$device->imei.' - '.$device->material}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-none mt-4 mb-2" id="content-offers">
                                        <label class="form-label mr-1" for="offers">Tarifa:</label><br>
                                        <select class="form-control form-control-sm col-md-6" id="offers" >
                                            <option selected value="0">Nothing</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        
                                        <div class="col-md-6 d-none" id="coordinates">
                                        <label class="control-label col-md-4">Coordenadas:</label>
                                            <section class="form-group-vertical">
                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-globe"></i></span>
                                                    </span>
                                                    <input class="form-control" type="text" placeholder="Latitud" id="lat_hbb" name="lat_hbb">
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-globe"></i></span>
                                                    </span>
                                                    <input class="form-control" type="text" placeholder="Longitud" id="lng_hbb" name="lng_hbb">
                                                </div>

                                            </section>
                                        </div>
                                    </div>

                                
                                        <div class="col-md-6" id="msisdn-select">
                                            <label>Clientes</label>
                                            <select data-plugin-selectTwo class="form-control populate" id="client" disabled onchange="getData()">
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
                                                    <input class="form-control" type="text" placeholder="Nombre" id="name" name="name" disabled>
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-user"></i></span>
                                                    </span>
                                                    <input class="form-control" type="text" placeholder="Apellido" id="lastname" name="lastname" disabled>
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-user"></i></span>
                                                    </span>
                                                    <input class="form-control" type="text" placeholder="RFC" id="rfc" name="rfc" disabled>
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-calendar"></i></span>
                                                    </span>
                                                    <input class="form-control" type="date" id="date_born" name="date_born" disabled>
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
                                                    <input class="form-control" type="text" placeholder="Dirección" id="address" name="address" disabled>
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-envelope"></i></span>
                                                    </span>
                                                    <input class="form-control" type="email" placeholder="Email" id="email" name="email" disabled>
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-user"></i></span>
                                                    </span>
                                                    <input class="form-control" type="text" placeholder="Código INE" id="ine_code" name="ine_code" disabled>
                                                </div>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon"><i class="fa fa-phone"></i></span>
                                                    </span>
                                                    <input class="form-control" type="text" placeholder="Teléfono Contacto" id="cellphone" name="celphone" maxlength="10" disabled>
                                                </div>
                                            </section>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <h3>Dispositivo y SIM</h3>
                                    </div>

                                    <input type="hidden" class="form-control" id="price_device" name="price_device" disabled value="0" >
                                    <input type="hidden" class="form-control" id="price_rate" name="price_rate" disabled value="0" >
                                    <input type="hidden" class="form-control" id="monto" name="monto" required readonly value="0">

                                    <div class="col-md-12 mt-sm">
                                        <div class="col-md-3 well success">
                                        <h3 style="margin-top: 0px;">Desglose</h3>
                                        <h5><span class="label label-warning" id="label-device">Dispositivo: $0.00</span></h5>
                                        <h5><span class="label label-warning" id="label-rate">Tarifa: $0.00</span></h5>
                                        <h5><span class="label label-danger" id="label-total">Total a Cobrar: $0.00</span></h5>
                                        </div>
                                    </div>

                                    <input type="hidden" name="user" id="user" value="{{ Auth::user()->id }}" required>

                                    <div class="col-md-3">
                                        <label for="scheduleDate" class="form-label">Fecha Operación</label>
                                        <input type="date" class="form-control" id="scheduleDate" name="scheduleDate" required >
                                    </div>

                                   
                                    <div class="col-md-12 mt-md">
                                       
                                        <button type="button" id="contract" class="btn btn-info" >Contrato</button>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="checkbox col-md-3">
                                            <label class="control-label ml-sm">
                                                <input type="checkbox" id="statusActivation">
                                                Activar
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="margin-top: 1rem;">
                                        <button type="button" class="btn btn-primary" id="send" disabled>Aceptar</button>
                                        <!-- <button type="button" class="btn btn-success" id="date-pay">Date Pay</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End MIFI Content -->
                    
            <!-- End Altán Services Accordions -->
                
                
            </div>
           
           
          
        </div>
    </div>
    @endif
</div>

<!-- Modal de mapa -->
    <div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contractModalLabel">Datos del Contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-bordered">
                        <div class="form-group"  style="padding-right: 1rem; padding-left: 1rem;">
                            <div class="col-md-12">
                                <h4>SUSCRIPTOR</h4>
                                
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4 mb-sm">
                                    <label for="nameContract" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nameContract" name="nameContract" required>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="lastnamePContract" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="lastnamePContract" name="lastnamePContract" required>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="lastnameMContract" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="lastnameMContract" name="lastnameMContract" required>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="emailContract" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="emailContract" name="emailContract" required>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="rfcContract" class="form-label">RFC</label>
                                    <input type="text" class="form-control" id="rfcContract" name="rfcContract" required>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="cellphoneContract" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="cellphoneContract" name="cellphoneContract" required>
                                </div>
                                <div class=" col-md-4 mb-sm">
                                    <label>El teléfono es:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioTel" value="fijo">
                                            Fijo
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioTel" value="movil" checked>
                                            Móvil
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>DOMICILIO</h4>
                            </div>
                            <div class="col-md-12">
                                <ul>
                                    <li id="addressContract"></li>
                                </ul>
                            </div>
                            <div class="col-md-12 mb-md">
                                <div class="col-md-4">
                                    <label for="street" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="street" name="street" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="exterior" class="form-label">#Exterior</label>
                                    <input type="text" class="form-control" id="exterior" name="exterior" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="interior" class="form-label">#Interior</label>
                                    <input type="text" class="form-control" id="interior" name="interior" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="colonia" class="form-label">Colonia</label>
                                    <input type="text" class="form-control" id="colonia" name="colonia" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="municipal" class="form-label">Alcaldía/Municipio</label>
                                    <input type="text" class="form-control" id="municipal" name="municipal" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="postal_code" class="form-label">C.P.</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>DATOS DEL DISPOSITIVO Y SIM</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4 mb-sm">
                                    <label for="deviceMark" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="deviceMark" name="deviceMark">
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="deviceModel" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="deviceModel" name="deviceModel" disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="deviceSerialNumber" class="form-label">Número de Serie</label>
                                    <input type="text" class="form-control" id="deviceSerialNumber" name="deviceSerialNumber" disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="deviceQuantity" class="form-label">Número de Equipos</label>
                                    <input type="text" class="form-control" id="deviceQuantity" name="deviceQuantity" value="1" disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="devicePrice" class="form-label">Cantidad a pagar por equipo $</label>
                                    <input type="text" class="form-control" id="devicePrice" name="devicePrice"  disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="ratePrice" class="form-label">Cantidad a pagar por mensualidad $</label>
                                    <input type="text" class="form-control" id="ratePrice" name="ratePrice" disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="productContract" class="form-label">Tipo SIM</label>
                                    <input type="text" class="form-control" id="productContract" name="productContract" disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="msisdnContract" class="form-label">Número de Línea</label>
                                    <input type="text" class="form-control" id="msisdnContract" name="msisdnContract" disabled>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <label for="iccContract" class="form-label">ICC de Línea</label>
                                    <input type="text" class="form-control" id="iccContract" name="iccContract"  disabled>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>MÉTODO DE PAGO</h4>
                            </div>
                            <div class="col-md-12">
                                <div class=" col-md-3 mb-sm">
                                    <label>Pago domiciliado:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioPaymentDomiciliado" value="si">
                                            SÍ
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioPaymentDomiciliado" value="no" checked>
                                            NO
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-sm d-none data-card">
                                    <label for="cardBank" class="form-label">Banco</label>
                                    <input type="text" class="form-control" id="cardBank" name="cardBank" >
                                </div>
                                <div class="col-md-4 mb-sm d-none data-card">
                                    <label for="cardNumber" class="form-label">No. Tarjeta</label>
                                    <input type="text" class="form-control" id="cardNumber" name="cardNumber" >
                                </div>
                                <div class="col-md-2 mb-sm d-none data-card">
                                    <label for="cardCVV" class="form-label">CVV2</label>
                                    <input type="text" class="form-control" id="cardCVV" name="cardCVV" >
                                </div>
                                <div class="col-md-12 d-none data-card" style="padding-left: 0;">
                                    <div class="col-md-3">
                                        <label>Fecha de Expiración(MM/YY)</label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control" name="monthExpiration" id="monthExpiration">
                                            <span class="input-group-addon">/</span>
                                            <input type="text" class="form-control" name="yearExpiration" id="yearExpiration">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>EL SUSCRIPTOR AUTORIZA SE LE ENVÍE POR CORREO ELECTRÓNICO LO SIGUIENTE:</h4>
                            </div>
                            <div class="col-md-12">
                                <div class=" col-md-3 mb-sm">
                                    <label>Factura:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioInvoice" value="si">
                                            SÍ
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioInvoice" value="no" checked>
                                            NO
                                        </label>
                                    </div>
                                </div>
                                <div class=" col-md-3 mb-sm">
                                    <label>Carta de Derechos Mínimos:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioRightsMin" value="si">
                                            SÍ
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioRightsMin" value="no" checked>
                                            NO
                                        </label>
                                    </div>
                                </div>
                                <div class=" col-md-3 mb-sm">
                                    <label>Contrato de Adhesión:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioContractAd" value="si">
                                            SÍ
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioContractAd" value="no" checked>
                                            NO
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>AUTORIZACIÓN PARA USO DE INFORMACIÓN DEL SUSCRIPTOR:</h4>
                            </div>
                            <div class="col-md-12">
                                <div class=" col-md-6 mb-sm">
                                    <label>El suscriptor autoriza que su información sea cedida o transmitida por el proveedor a terceros con fines mercadotécnicos o publicitarios:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioAuth1" value="si">
                                            SÍ
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioAuth1" value="no" checked>
                                            NO
                                        </label>
                                    </div>
                                </div>
                                <div class=" col-md-6 mb-sm">
                                    <label>El suscriptor acepta recibir llamadas del proveedor de promociones de servicios o paquetes:</label>
                                    <div class="radio col-md-12">
                                        <label>
                                            <input type="radio" name="optionRadioAuth2" value="si">
                                            SÍ
                                        </label>
                                        <label class="ml-md">
                                            <input type="radio" name="optionRadioAuth2" value="no" checked>
                                            NO
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>FIRMA DEL SUSCRIPTOR:</h4>
                            </div>
                            <div class="col-md-12" style="padding-left:0;">
                                <!-- Firma digital -->
                                <div class="col-md-12 mt-md">
                                    <div class="col-md-5 well wellCanvas">
                                        <canvas id="draw-canvas"  >
                                            No tienes un buen navegador.
                                        </canvas>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <input type="button" class="btn btn-success btn-sm" id="draw-submitBtn" value="Guardar"></input>
                                    <input type="button" class="btn btn-danger btn-sm" id="draw-clearBtn" value="Limpiar"></input>                                        
                                </div>

                                <br/>
                                <div class="col-md-12 d-none">
                                    <textarea id="draw-dataUrl" class="form-control" rows="5"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <h4>Tu firma aparecerá aquí:</h4>
                                    <img class="d-none" id="draw-image" src="" >
                                </div>
                                <!-- END Firma digital -->
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="acceptContract">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
<script src="{{asset('octopus/assets/vendor/pnotify/pnotify.custom.js')}}"></script>
<script>
    var altcel, token;

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
                                title: 'Permiso denegado, ocurrió lo siguiente:',
                                text: response.error
                            });
                        }else{
                            token = response.token;
                            Swal.fire({
                                icon: 'success',
                                title: 'Permiso concedido.',
                                text: 'Puede continuar...',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#number').attr('disabled',false);
                            $('#device').attr('disabled',false);
                            $('#client').attr('disabled',false);
                            $('#send').attr('disabled',false);
                        }
                    }
                });
            }
        })()
    });

    $('#number').change(function(){
        let number_id = $(this).val();
        let product = $('#number option:selected').attr('data-product');
        if(product == 'HBB'){
            $('coordinates').removeClass('d-none');
        }else{
            $('coordinates').addClass('d-none');
        }
        getRates(product);
    });

    $('#device').change(function(){
        let value = $(this).val();
        let priceRate = $('#price_rate').val();
        let monto = 0;
        let price = 0;

        if(value == 0){
            price = parseFloat(price);
            priceRate = parseFloat(priceRate);
            monto = price+priceRate;
        }else{
            price = $('#device option:selected').attr('data-price');
            price = parseFloat(price);
            priceRate = parseFloat(priceRate);
            monto = price+priceRate;
        }

        $('#price_device').val(price);
        $('#monto').val(monto);
        $('#label-device').html('Dispositivo: $'+price.toFixed(2));
        $('#label-total').html('Total a Cobrar: $'+parseFloat(monto).toFixed(2));
        
    });

    $('#offers').change(function(){
        let value = $(this).val();
        let priceDevice = $('#price_device').val();
        let monto = 0;
        let price = 0;

        if(value == 0){
            price = parseFloat(price);
            priceDevice = parseFloat(priceDevice);
            monto = price+priceDevice;
        }else{
            price = $('#offers option:selected').attr('data-plan-price');
            price = parseFloat(price);
            priceDevice = parseFloat(priceDevice);
            monto = price+priceDevice;
        }

        $('#price_rate').val(price);
        $('#monto').val(monto);
        $('#label-rate').html('Tarifa: $'+price.toFixed(2));
        $('#label-total').html('Total a Cobrar: $'+parseFloat(monto).toFixed(2));
        
    });

    function getRates(product) {
        let tokenCSRF = $('meta[name="csrf-token"]').attr('content');
        let options = '<option value="0">Elige un plan...</option>';
        $.ajax({
                url: "{{ route('get-rates-alta.post')}}",
                method: 'POST',
                data:{
                    token:token,
                    _token:tokenCSRF, 
                    product:product
                    },
                success: function(data){
                    // console.log(data);
                    data.forEach(function(element){
                        // console.log(element);
                        options+="<option value='"+element.offerID+"' data-rate-id='"+element.id+"' data-plan-price='"+element.price+"' data-plan-price-subsequent='"+element.price_subsequent+"' data-plan-name='"+element.name+"' data-plan-recurrency='"+element.recurrency+"' data-product='"+element.offer_product+"'>"+element.name+"</option>"
                    });
                    $('#content-offers').removeClass('d-none');
                    $('#offers').html(options);
                }
            });
    }

    $('#send').click(function(){

        if($('#number').val() == 0){
            messageError('Por favor elige una SIM.');
            return false;
        }

        if($('#device').val() == 0){
            messageError('Por favor elige un dispositivo.');
            return false;
        }

        if($('#offers').val() == 0){
            messageError('Por favor elige un plan.');
            return false;
        }

        if($('#client').val() == 0){
            messageError('Por favor seleccione un cliente.');
            return false;
        }

        let name = $('#name').val();
        let lastname = $('#lastname').val();
        let address = $('#address').val();
        let email = $('#email').val();
        let cellphone = $('#cellphone').val();
        let rfc = $('#rfc').val();
        let date_born = $('#date_born').val();
        let ine_code = $('#ine_code').val();
        let type_person = 'física';

        let imei = $('#device option:selected').attr('data-imei');
        let serial_number = '';
        let offer = $('#offers').val();
        let rate =  $('#offers option:selected').attr('data-rate-id');
        let rate_name =  $('#offers option:selected').attr('data-plan-name');
        let rate_recurrency =  $('#offers option:selected').attr('data-plan-recurrency');
        let product =  $('#offers option:selected').attr('data-product');
        let icc_id = $('#number option:selected').attr('data-icc');
        let msisdn = $('#number option:selected').attr('data-msisdn');
        let lat_hbb = $('#lat_hbb').val();
        let lng_hbb = $('#lng_hbb').val();
        let sim_altcel = 'nothing';
        let from = 'promoters';
        let monto = $('#monto').val();
        let amount_device = $('#price_device').val();
        let amount_rate = $('#price_rate').val();
        let tokenCSRF = $('meta[name="csrf-token"]').attr('content');
        let who_did_id = $('#user').val();
        let scheduleDateFirst = $('#scheduleDate').val();
        let scheduleDate = scheduleDateFirst.replace(/-/g, "");
        let email_not = 1;
        let activate_bool = 0;
        let statusActivation = 'preactivated';

        if($('#statusActivation').prop('checked') ) {
            statusActivation = 'activated';
        }else{
            statusActivation = 'preactivated';
        }
        
        if(scheduleDateFirst.length == 0 || /^\s+$/.test(scheduleDateFirst)){
            scheduleDate = '';
        }

        if(product != 'HBB'){
            lat_hbb = null;
            lng_hbb = null;
        }else{
            if(lat_hbb.length == 0 || /^\s+$/.test(lat_hbb)){
                let message = "Ingrese latitud, no puede estar vacío.";
                sweetAlertFunction(message);
                document.getElementById('lat_hbb').focus();
                return false;
            }

            if(lng_hbb.length == 0 || /^\s+$/.test(lng_hbb)){
                let message = "Ingrese longitud, no puede estar vacío.";
                sweetAlertFunction(message);
                document.getElementById('lng_hbb').focus();
                return false;
            }
        }
        // console.log(statusActivation);
        // return false;
        $(this).attr('disabled',true);

        Swal.fire({
            title: 'Realizando activación...',
            html: 'Espera un poco, un poquito más...',
            didOpen: () => {
                Swal.showLoading();
            $.ajax({
                    url: "{{ route('activation-general.post')}}",
                    method: 'POST',
                    data:{
                        token:token,
                        _token:tokenCSRF, 
                        name:name,
                        lastname:lastname,
                        address:address,
                        email:email,
                        cellphone:cellphone,
                        ine_code:ine_code,
                        rfc: rfc,
                        date_born: date_born,
                        type_person:type_person,
                        imei:imei,
                        serial_number:serial_number,
                        offer_id:offer,
                        rate_id:rate,
                        icc_id:icc_id,
                        msisdn:msisdn,
                        lat_hbb: lat_hbb,
                        lng_hbb: lng_hbb,
                        product: product,
                        from: from,
                        sim_altcel: sim_altcel,
                        rate_recurrency: rate_recurrency,
                        price: monto,
                        price_device: amount_device,
                        price_rate: amount_rate,
                        who_did_id: who_did_id,
                        email_not: email_not,
                        activate_bool: activate_bool,
                        scheduleDate:scheduleDate,
                        statusActivation:statusActivation
                        },
                    success: function(data){
                        if(data == 1){
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Preactivación hecha con éxito.',
                                text: 'Se recargará la página...',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            setTimeout(function(){ location.reload(); }, 2500);
                            $('#send').attr('disabled',false);
                        }else if(data == 0){
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un error de tipo 0 con la activación.',
                                showConfirmButton: false,
                                timer: 1500
                            })

                            return false;
                        }else if(data == 2){
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un error de tipo 2 con la activación.',
                                text: 'No se encontró el MSISDN...',
                                showConfirmButton: false,
                                timer: 1500
                            })

                            return false;
                        }else if(data == 3){
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un error de tipo 3 con la activación.',
                                text: 'Sin obtención de token de acceso, consulte a Soporte Técnico.',
                                showConfirmButton: false,
                                timer: 1500
                            })

                            return false;
                        }else{
                            // data = JSON.stringify(data);
                            
                            altcel = data;
                            Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió lo siguiente',
                                html: data,
                            })
                            console.log(data);
                            return false;
                        }
                        console.log(data);
                    
                    }
                });
            }
        });
    });

    $('input[name="optionRadioPaymentDomiciliado"]').click(function() {
        let radioOption = $(this).val();

        if(radioOption == 'si'){
            $('.data-card').removeClass('d-none');
        }else if(radioOption == 'no'){
            $('.data-card').addClass('d-none');
        }
    });

    $('#contract').click(function(){

        // if($('#number').val() == 0){
        //     messageError('Por favor elige una SIM.');
        //     return false;
        // }

        // if($('#device').val() == 0){
        //     messageError('Por favor elige un dispositivo.');
        //     return false;
        // }

        // if($('#offers').val() == 0){
        //     messageError('Por favor elige un plan.');
        //     return false;
        // }

        // if($('#client').val() == 0){
        //     messageError('Por favor seleccione un cliente.');
        //     return false;
        // }

        let name = $('#client option:selected').attr('data-name');
        let lastname = $('#client option:selected').attr('data-lastname');
        let cellphone = $('#client option:selected').attr('data-cellphone');
        let rfc = $('#client option:selected').attr('data-rfc');
        let email = $('#client option:selected').attr('data-email');
        let address = $('#client option:selected').attr('data-address');

        let serialNumber = $('#device option:selected').attr('data-imei');
        let model = $('#device option:selected').attr('data-description');
        let priceDevice = $('#device option:selected').attr('data-price');
        priceDevice = parseFloat(priceDevice);
        let mensualidad = $('#offers option:selected').attr('data-plan-price-subsequent');
        mensualidad = parseFloat(mensualidad);

        let msisdn = $('#number option:selected').attr('data-msisdn');
        let icc = $('#number option:selected').attr('data-icc');
        let product = $('#number option:selected').attr('data-product');

        $('#nameContract').val(name);
        $('#lastnamePContract').val(lastname);
        $('#cellphoneContract').val(cellphone);
        $('#rfcContract').val(rfc);
        $('#emailContract').val(email);
        $('#addressContract').html(address);

        $('#deviceSerialNumber').val(serialNumber);
        $('#deviceMark').val(model);
        $('#deviceModel').val(model);
        $('#devicePrice').val(priceDevice.toFixed(2));
        $('#ratePrice').val(mensualidad.toFixed(2));

        $('#msisdnContract').val(msisdn);
        $('#iccContract').val(icc);
        $('#productContract').val(product);

        $('#contractModal').modal('show');
    });

    function saveSignature(){
        let image = $('#draw-dataUrl').val();
        let client_id = $('#client').val();
        console.log(image);

        $.ajax({
            url: "{{route('saveIMG.post')}}",
            method: "POST",
            data:{base64:image,client:client_id},
            success: function(response){
                console.log(response);
            }
        });
    }

    $('#acceptContract').click(function(){
        // Información del Suscriptor
        let name = $('#nameContract').val();
        let lastnameP = $('#lastnamePContract').val();
        let lastnameM = $('#lastnameMContract').val();
        let email = $('#emailContract').val();
        let rfc = $('#rfcContract').val();
        let cellphone = $('#cellphoneContract').val();
        let typePhone = $('input[name="optionRadioTel"]:checked').val();
        let client_id = $('#client').val();
        // Domicilio del Suscriptor
        let street = $('#street').val();
        let exterior = $('#exterior').val();
        let interior = $('#interior').val();
        let colonia = $('#colonia').val();
        let municipal = $('#municipal').val();
        let state = $('#state').val();
        let postal_code = $('#postal_code').val();
        // Datos del Dispositivo y SIM
        let marca = $('#deviceMark').val();
        let modelo = $('#deviceModel').val();
        let serialNumber = $('#deviceSerialNumber').val();
        let deviceQuantity = $('#deviceQuantity').val();
        let devicePrice = $('#devicePrice').val();
        let ratePrice = $('#ratePrice').val();
        let product = $('#productContract').val();
        let msisdn = $('#msisdnContract').val();
        let icc = $('#iccContract').val();
        // Método de Pago
        let typePayment = $('input[name="optionRadioPaymentDomiciliado"]:checked').val();
        let bank = $('#cardBank').val();
        let cardNumber = $('#cardNumber').val();
        let cvv = $('#cardCVV').val();
        let monthExpiration = $('#monthExpiration').val();
        let yearExpiration = $('#yearExpiration').val();
        // Autorizaciones de Envío de Correos Electrónicos
        let invoiceBool = $('input[name="optionRadioInvoice"]:checked').val();
        let rightsMinBool = $('input[name="optionRadioRightsMin"]:checked').val();
        let contractAdhesionBool = $('input[name="optionRadioContractAd"]:checked').val();
        // Autorización de Uso de Información del Suscriptor
        let useInfoFirst = $('input[name="optionRadioAuth1"]:checked').val();
        let useInfoSecond = $('input[name="optionRadioAuth2"]:checked').val();

        let who_did_id = $('#user').val();

        let firma = $('#draw-dataUrl').val();
        let data, url = "{{ route('descargarPDF.get', ['datos' => 'temp']) }}";

        if(name.length == 0 || /^\s+$/.test(name)){
            sweetAlertFunction("El campo Nombre no puede estar vacío.");
            document.getElementById('nameContract').focus();
            return false;
        }

        if(lastnameP.length == 0 || /^\s+$/.test(lastnameP)){
            sweetAlertFunction("El campo Apellido Paterno no puede estar vacío.");
            document.getElementById('lastnamePContract').focus();
            return false;
        }

        if(lastnameM.length == 0 || /^\s+$/.test(lastnameM)){
            sweetAlertFunction("El campo Apellido Materno no puede estar vacío.");
            document.getElementById('lastnameMContract').focus();
            return false;
        }

        if(email.length == 0 || /^\s+$/.test(email)){
            sweetAlertFunction("El campo Email no puede estar vacío.");
            document.getElementById('emailContract').focus();
            return false;
        }

        if(rfc.length == 0 || /^\s+$/.test(rfc)){
            sweetAlertFunction("El campo RFC no puede estar vacío.");
            document.getElementById('rfcContract').focus();
            return false;
        }

        if(cellphone.length == 0 || /^\s+$/.test(cellphone)){
            sweetAlertFunction("El campo Teléfono no puede estar vacío.");
            document.getElementById('cellphoneContract').focus();
            return false;
        }
        
        if(street.length == 0 || /^\s+$/.test(street)){
            sweetAlertFunction("El campo Calle no puede estar vacío.");
            document.getElementById('street').focus();
            return false;
        }

        if(exterior.length == 0 || /^\s+$/.test(exterior)){
            sweetAlertFunction("El campo #Exterior no puede estar vacío, si no existe No., ingrese N/A.");
            document.getElementById('street').focus();
            return false;
        }

        if(firma.length == 0 || /^\s+$/.test(firma)){
            Swal.fire({
                icon: 'error',
                title: 'Por favor registre una firma.',
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }

        data =
            "name="+name+"&lastnameP="+lastnameP+"&lastnameM="+lastnameM+"&email="+email+"&rfc="+rfc+"&cellphone="+cellphone+"&typePhone="+typePhone+"&client_id="+client_id+
            "&street="+street+"&exterior="+exterior+"&interior="+interior+"&colonia="+colonia+"&municipal="+municipal+"&state="+state+"&postal_code="+postal_code+
            "&marca="+marca+"&modelo="+modelo+"&serialNumber="+serialNumber+"&deviceQuantity="+deviceQuantity+"&devicePrice="+devicePrice+"&ratePrice="+ratePrice+"&product="+product+"&msisdn="+msisdn+
            "&icc="+icc+"&typePayment="+typePayment+"&bank="+bank+"&cardNumber="+cardNumber+"&cvv="+cvv+"&monthExpiration="+monthExpiration+"&yearExpiration="+yearExpiration+
            "&invoiceBool="+invoiceBool+"&rightsMinBool="+rightsMinBool+"&contractAdhesionBool="+contractAdhesionBool+"&useInfoFirst="+useInfoFirst+"&useInfoSecond="+useInfoSecond+"&who_did_id="+who_did_id;


        Swal.fire({
            title: '¿Corroboró los datos ingresados y está seguro de generar el contrato?',
            text: "Le sugiero corroborar nuevamente los datos antes de guardarlos.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#47a447',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SÍ, ESTOY SEGURO.'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('saveIMG.post')}}",
                    method: "POST",
                    data:{base64:firma,client:client_id},
                    success: function(response){
                        if(response == 1){    
                            
                            // url = url.replace('temp', data);
                            location.href = url+"?"+data;
                        }
                    }
                });
                
            }
        })
    });

    function messageError(message){
        Swal.fire({
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function sweetAlertFunction(message){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

// Función autoejecutable para generación de firma digital

(function() { // Comenzamos una funcion auto-ejecutable

    // Obtenenemos un intervalo regular(Tiempo) en la pamtalla
    window.requestAnimFrame = (function (callback) {
        return window.requestAnimationFrame ||
                    window.webkitRequestAnimationFrame ||
                    window.mozRequestAnimationFrame ||
                    window.oRequestAnimationFrame ||
                    window.msRequestAnimaitonFrame ||
                    function (callback) {
                        window.setTimeout(callback, 1000/60);
            // Retrasa la ejecucion de la funcion para mejorar la experiencia
                    };
    })();

    // Traemos el canvas mediante el id del elemento html
    var canvas = document.getElementById("draw-canvas");
    var ctx = canvas.getContext("2d");


    // Mandamos llamar a los Elemetos interactivos de la Interfaz HTML
    var drawText = document.getElementById("draw-dataUrl");
    var drawImage = document.getElementById("draw-image");
    var clearBtn = document.getElementById("draw-clearBtn");
    var submitBtn = document.getElementById("draw-submitBtn");

    clearBtn.addEventListener("click", function (e) {
        // Definimos que pasa cuando el boton draw-clearBtn es pulsado
        clearCanvas();
        drawImage.setAttribute("src", "");
        $('#draw-image').addClass('d-none');
        $('#draw-dataUrl').val('');
    }, false);

    // Definimos que pasa cuando el boton draw-submitBtn es pulsado
    submitBtn.addEventListener("click", function (e) {
    var dataUrl = canvas.toDataURL();
    $('#draw-dataUrl').val(dataUrl);
    // drawText.innerHTML = dataUrl;
    drawImage.setAttribute("src", dataUrl);
    $('#draw-image').removeClass('d-none');
    // saveSignature();
    }, false);

    // Activamos MouseEvent para nuestra pagina
    var drawing = false;
    var mousePos = { x:0, y:0 };
    var lastPos = mousePos;
    canvas.addEventListener("mousedown", function (e)
    {
    /*
    Mas alla de solo llamar a una funcion, usamos function (e){...}
    para mas versatilidad cuando ocurre un evento
    */
        var tint = '#000000';
        var punta = 2;
        // console.log('Tinta: '+tint.value+' - '+'Punta: '+punta.value);
        // console.log(e);
        drawing = true;
        lastPos = getMousePos(canvas, e);
    }, false);

    canvas.addEventListener("mouseup", function (e)
    {
        drawing = false;
    }, false);
    canvas.addEventListener("mousemove", function (e)
    {
        mousePos = getMousePos(canvas, e);
    }, false);

    // Activamos touchEvent para nuestra pagina
    canvas.addEventListener("touchstart", function (e) {
        mousePos = getTouchPos(canvas, e);
    // console.log(mousePos);
    e.preventDefault(); // Prevent scrolling when touching the canvas
        var touch = e.touches[0];
        var mouseEvent = new MouseEvent("mousedown", {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }, false);

    canvas.addEventListener("touchend", function (e) {
    e.preventDefault(); // Prevent scrolling when touching the canvas
        var mouseEvent = new MouseEvent("mouseup", {});
        canvas.dispatchEvent(mouseEvent);
    }, false);

    canvas.addEventListener("touchleave", function (e) {
    // Realiza el mismo proceso que touchend en caso de que el dedo se deslice fuera del canvas
    e.preventDefault(); // Prevent scrolling when touching the canvas
    var mouseEvent = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(mouseEvent);
    }, false);

    canvas.addEventListener("touchmove", function (e) {
    e.preventDefault(); // Prevent scrolling when touching the canvas
        var touch = e.touches[0];
        var mouseEvent = new MouseEvent("mousemove", {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }, false);

    // Get the position of the mouse relative to the canvas
    function getMousePos(canvasDom, mouseEvent) {
        var rect = canvasDom.getBoundingClientRect();
    /*
    Devuelve el tamaño de un elemento y su posición relativa respecto
    a la ventana de visualización (viewport).
    */
        return {
            x: mouseEvent.clientX - rect.left,
            y: mouseEvent.clientY - rect.top
        };
    }

    // Get the position of a touch relative to the canvas
    function getTouchPos(canvasDom, touchEvent) {
        var rect = canvasDom.getBoundingClientRect();
    // console.log(touchEvent);
    /*
    Devuelve el tamaño de un elemento y su posición relativa respecto
    a la ventana de visualización (viewport).
    */
        return {
            x: touchEvent.touches[0].clientX - rect.left, // Popiedad de todo evento Touch
            y: touchEvent.touches[0].clientY - rect.top
        };
    }

    // Draw to the canvas
    function renderCanvas() {
        if (drawing) {
            var tint = '#000000';
            var punta = 2;
            ctx.strokeStyle = tint;
            ctx.beginPath();
                    ctx.moveTo(lastPos.x, lastPos.y);
                    ctx.lineTo(mousePos.x, mousePos.y);
            // console.log(punta);
                ctx.lineWidth = punta;
                    ctx.stroke();
            ctx.closePath();
            lastPos = mousePos;
        }
    }

    function clearCanvas() {
        canvas.width = canvas.width;
    }

    // Allow for animation
    (function drawLoop () {
        requestAnimFrame(drawLoop);
        renderCanvas();
    })();

})();
</script>
@endsection