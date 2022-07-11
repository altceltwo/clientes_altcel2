@extends('layouts.octopus')
@extends('layouts.datatablescss')
@section('content')
<header class="page-header">
    <h2>Dashboard</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="{{route('home')}}">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Contratos</span></li>
        </ol>
        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
    </div>
</header>

<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
        </div>

        <h2 class="panel-title">Clientes</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
            <thead >
                <tr>
                <th scope="col">Nombre</th>
                <th scope="col">MSISDN</th>
                <th scope="col">Producto</th>
                <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>{{$client->name.' '.$client->lastname}}</td>
                    <td>{{$client->MSISDN}}</td>
                    <td>{{$client->producto}}</td>
                    <td>
                        <button class="mb-xs mt-xs mr-xs btn btn-info btn-sm contract" 
                        data-contract="{{$contractBool = $client->contract_id == null ? 0 : $client->contract_id}}" 
                        data-client-id="{{$client->id}}" 
                        data-msisdn-id="{{$client->msisdn_id}}"
                        ><li class="fa fa-file-pdf-o"></li></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<input type="hidden" id="user" value="{{Auth::user()->id}}">

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
                                <input type="text" class="form-control" id="deviceModel" name="deviceModel">
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

                        <input type="hidden" id="client">

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
                            <div class="col-md-12 ">
                                <textarea id="draw-dataUrl" class="form-control d-none" rows="5"></textarea>
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

<script>
    $('.contract').click(function(){
        let contract_id  = $(this).data('contract');
        let client_id  = $(this).data('client-id');
        let msisdn_id  = $(this).data('msisdn-id');
        let url = "{{ route('descargarPDF.get', ['datos' => 'temp']) }}";
        $('#client').val(client_id);

        $.ajax({
            url: "{{route('getDataContract')}}",
            data: {contract_id: contract_id, client_id: client_id, msisdn_id: msisdn_id},
            success: function(response){
                console.log(response);
                if(contract_id == 0){
                    let dataUser = response.dataUser;
                    let dataClient = response.dataClient;
                    let dataDevice = response.dataDevice;
                    let dataActivation = response.dataActivation;
                    let dataMSISDN = response.dataMSISDN;

                    $('#nameContract').val(dataUser.name);
                    $('#lastnamePContract').val(dataUser.lastname);
                    $('#emailContract').val(dataUser.email);
                    $('#rfcContract').val(dataClient.rfc);
                    $('#cellphoneContract').val(dataClient.cellphone);

                    $('#addressContract').html(dataClient.address);

                    $('#deviceMark').val(dataDevice.description);
                    $('#deviceSerialNumber').val(dataActivation.serial_number);
                    $('#devicePrice').val(dataActivation.amount_device);
                    $('#ratePrice').val(dataActivation.amount_rate);

                    $('#productContract').val(dataMSISDN.producto);
                    $('#msisdnContract').val(dataMSISDN.MSISDN);
                    $('#iccContract').val(dataMSISDN.icc_id);
                    $('#contractModal').modal('show');
                }else{
                    let dataContract = response.dataContract;
                    dataContract = JSON.stringify(dataContract);
                    dataContract = dataContract.replace(new RegExp(',', "g"),'&');
                    dataContract = dataContract.replace(new RegExp('"', "g"),'');
                    dataContract = dataContract.replace(new RegExp(':', "g"),'=');
                    dataContract = dataContract.replace(new RegExp('{', "g"),'');
                    dataContract = dataContract.replace(new RegExp('}', "g"),'');
                    dataContract = dataContract.replace('signature','firma');
                    dataContract = dataContract.replace('firma=0','firma=null');
                    // console.log(dataContract);
                    // return false;
                    location.href = url+"?"+dataContract;
                }
                
            }
        });
    });

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
        let firmaBool = 0;

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

        if(colonia.length == 0 || /^\s+$/.test(colonia)){
            sweetAlertFunction("El campo Colonia no puede estar vacío.");
            document.getElementById('colonia').focus();
            return false;
        }

        if(municipal.length == 0 || /^\s+$/.test(municipal)){
            sweetAlertFunction("El campo Municipio no puede estar vacío.");
            document.getElementById('municipal').focus();
            return false;
        }

        if(state.length == 0 || /^\s+$/.test(state)){
            sweetAlertFunction("El campo Estado no puede estar vacío.");
            document.getElementById('state').focus();
            return false;
        }

        if(postal_code.length == 0 || /^\s+$/.test(postal_code)){
            sweetAlertFunction("El campo de Código Postal no puede estar vacío.");
            document.getElementById('postal_code').focus();
            return false;
        }

        if(marca.length == 0 || /^\s+$/.test(marca)){
            sweetAlertFunction("El campo Marca no puede estar vacío.");
            document.getElementById('deviceMark').focus();
            return false;
        }

        if(modelo.length == 0 || /^\s+$/.test(modelo)){
            sweetAlertFunction("El campo Modelo no puede estar vacío.");
            document.getElementById('modelo').focus();
            return false;
        }

        if(firma.length == 0 || /^\s+$/.test(firma)){
            firmaBool = null;
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Por favor registre una firma.',
            //     showConfirmButton: false,
            //     timer: 1500
            // });
            // return false;
        }else{
            firmaBool = 1; 
        }

        console.log(firma);

        data =
            "name="+name+"&lastnameP="+lastnameP+"&lastnameM="+lastnameM+"&email="+email+"&rfc="+rfc+"&cellphone="+cellphone+"&typePhone="+typePhone+"&client_id="+client_id+
            "&street="+street+"&exterior="+exterior+"&interior="+interior+"&colonia="+colonia+"&municipal="+municipal+"&state="+state+"&postal_code="+postal_code+
            "&marca="+marca+"&modelo="+modelo+"&serialNumber="+serialNumber+"&deviceQuantity="+deviceQuantity+"&devicePrice="+devicePrice+"&ratePrice="+ratePrice+"&product="+product+"&msisdn="+msisdn+
            "&icc="+icc+"&invoiceBool="+invoiceBool+"&rightsMinBool="+rightsMinBool+"&contractAdhesionBool="+contractAdhesionBool+"&useInfoFirst="+useInfoFirst+"&useInfoSecond="+useInfoSecond+"&who_did_id="+who_did_id+"&firma="+firmaBool;


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

        // Obtenenemos un intervalo regular(Tiempo) en la pantalla
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