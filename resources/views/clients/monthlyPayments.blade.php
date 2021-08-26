@extends('layouts.octopus')
@section('content')
@php
use \Carbon\Carbon;
@endphp
<header class="page-header">
    <h2>Administración de Pagos</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Dashboard</span></li>
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

        <h2 class="panel-title">Pagos Pendientes del Mes </h2>
    </header>
    <div class="panel-body d-none" id="monthlyPayments">
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
            <thead style="cursor: pointer;">
                <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Servicio</th>
                <th scope="col">Número</th>
                <th scope="col">Estado</th>
                <th scope="col">Monto Esperado</th>
                <th scope="col">Fecha Pago</th>
                <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
           
            @foreach($paymentsPendings as $paymentsPending)
                <tr>
                    <td>{{$paymentsPending->client_name.' '.$paymentsPending->client_lastname}}</td>
                    <td>{{$paymentsPending->rate_name}}</td>
                    <td>{{$paymentsPending->DN.' - '.$paymentsPending->producto}}</td>
                    <td>
                        @php
                            $status_traffic = $paymentsPending->producto == 'MOV' ? $paymentsPending->traffic_mov : $paymentsPending->traffic_ethernet;
                            $label_color = $status_traffic == 'inactivo' ? 'danger' : 'success';

                            $status_sim = $paymentsPending->status_sim == 'predeactivate' ? 'Baja Temporal' : 'Activo';
                            $label_color_sim = $paymentsPending->status_sim == 'predeactivate' ? 'danger' : 'success';
                        @endphp
                        <span class="label label-{{$label_color}} mb-sm">Tráfico: {{$status_traffic}}</span>
                        <span class="label label-{{$label_color_sim}}">SIM: {{$status_sim}}</span>
                    </td>
                    <td>${{number_format($paymentsPending->amount,2)}}</td>
                    <td>{{$paymentsPending->date_pay}}</td>
                    <td>
                    @php
                        $ref = $paymentsPending->referenceID == null ? 'N/A' : $paymentsPending->referenceID
                    @endphp

                    
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                        <button type="button" class="btn btn-success btn-sm mt-xs register-pay-manual" payID="{{$paymentsPending->id}}" service="{{$paymentsPending->number_product}}"><i class="fa  fa-dollar"></i></button>
                        <button type="button" class="btn btn-warning btn-sm mt-xs payment-with-change" payID="{{$paymentsPending->id}}" service="{{$paymentsPending->number_product}}" DN="{{$paymentsPending->DN}}"><i class="fa  fa-exchange"></i></button>
                    @endif
                    </td>
                </tr>
            @endforeach
         
            </tbody>
        </table>
       
    </div>
    <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
</section>


<script>
    var token;
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
                                title: 'Permiso denegado, se recargará la página. Ocurrió lo siguiente:',
                                text: response.error,
                                showConfirmButton: false,
                                timer: 3000
                            });
                            setTimeout(function(){ location.reload(); }, 3500);
                        }else{
                            token = response.token;
                            Swal.fire({
                                icon: 'success',
                                title: 'Permiso concedido.',
                                text: 'Puede continuar...',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#monthlyPayments').removeClass('d-none');
                        }
                    }
                });
            }
        })()
    });

    $('.register-pay-manual').click(function(){
        let service = $(this).attr('service');
        let payID = $(this).attr('payID');
        let preg = /^([0-9]+\.?[0-9]{0,2})$/; 
        let monto = 0;
        let montoExtra = 0;
        let typePay = 'efectivo';
        let folioPay = 'N/A';
        let estadoPay = 'completado';
        // console.log(service+' - '+payID);
        
        (async () => {
            const { value: amount } = await Swal.fire({
                title: 'Ingrese el monto, por favor',
                input: 'text',
                inputLabel: '$',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Por favor ingrese un valor'
                    }

                    if(preg.test(value) === false){
                        // monto = value;
                        // methodPay(monto);
                        return 'Sólo se permiten números y dos décimales';
                    }
                }
            })

            if (amount) {
                monto = amount;


                    const { value: extraAmount } = await Swal.fire({
                        title: 'Si hay algún monto extra por instalación o adeudo de dispositivo, ingréselo.',
                        input: 'text',
                        inputValue: '0.00',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Ingrese un dato, por favor'
                            }
                        }
                    })

                    if(extraAmount){
                        montoExtra = extraAmount;
                        
                                Swal.fire({
                                    title: 'Verifique los datos siguientes:',
                                    html: "<li class='list-alert'><b>Monto: </b>$"+monto+"</li><br>"+
                                    "<li class='list-alert'><b>Extra: </b>$"+montoExtra+"</li><br>",
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
                                        registryPay(service, payID, monto, typePay, folioPay, estadoPay, montoExtra);
                                    } else if (
                                        /* Read more about handling dismissals below */
                                        result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Operación cancelada',
                                            text: 'No se registro ningún pago.',
                                            showConfirmButton: false,
                                            timer: 1000
                                        })
                                    }
                                })
                    }

            }
        })()
    });

    $('.payment-with-change').click(function(){
        let payment = $(this).attr('payID');
        let msisdn = $(this).attr('DN');
        let service = $(this).attr('service');
        let preg = /^([0-9]+\.?[0-9]{0,2})$/;
        let url = "{{route('changeProduct',['msisdn'=>'temp'])}}";
        url = url.replace('temp',msisdn);
        let extraAmount = 0;
        
        (async () => {
            const { value: amountExtra } = await Swal.fire({
                title: 'Está a punto de realizar un cambio de producto en la renovación mensual.',
                text: 'Si ha cobrado un monto extra de instalación o dispositivo, por favor ingréselo.',
                input: 'text',
                inputValue: '0.00',
                inputLabel: '$',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Por favor ingrese un valor'
                    }

                    if(preg.test(value) === false){
                        // monto = value;
                        // methodPay(monto);
                        return 'Sólo se permiten números y dos décimales';
                    }
                }
            });

            if(amountExtra){
                extraAmount = amountExtra;
                Swal.fire({
                    title: 'Verifique los datos siguientes antes de proceder a hacer el cambio de producto:',
                    html: "<li class='list-alert'><b>Monto Extra: </b>$"+extraAmount+"</li><br>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ACEPTAR',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-success mr-md',
                        cancelButton: 'btn btn-danger '
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Perfecto.',
                            text: 'Es momento de proceder a hacer el cambio de producto',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        setTimeout(function(){ location.href = url+"?payment="+payment+"&extraAmount="+extraAmount+"&service="+service; }, 1200);
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Operación cancelada',
                            text: 'No se realizó ninguna acción.',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                })
            }
        })();
    });

    function registryPay(service, payID, monto, typePay, folioPay, estadoPay, montoExtra){
        let user_id = $('#user_id').val();
        let data = {
            token:token,
            service: service,
            payID: payID,
            monto: monto,
            typePay: typePay,
            folioPay: folioPay,
            estadoPay: estadoPay,
            montoExtra: montoExtra,
            user_id:user_id};

        Swal.fire({
            title: 'Registrando pago...',
            html: 'Espera un poco, un poquito más...',
            didOpen: () => {
                Swal.showLoading();
                // setTimeout(function(){ Swal.close(); }, 2000);
                $.ajax({
                    url:"{{route('save-manual-pay.get')}}",
                    method: "GET",
                    data: data,
                    success: function(data){
                        // return console.log(data);
                        if(data.status){
                            if(data.status == 'Token is Invalid'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Token de acceso inválido.',
                                    text: 'Se recargará la página...',
                                    showConfirmButton: false,
                                });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }else if(data.status == 'Token is Expired'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Token de acceso expirado.',
                                    text: 'Se recargará la página...',
                                    showConfirmButton: false,
                                });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }else if(data.status == 'Authorization Token not found'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Token de acceso no encontrado.',
                                    text: 'Se recargará la página...',
                                    showConfirmButton: false,
                                });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                            
                            return false;
                        }

                        data = JSON.parse(data);
                        if(data == 1){
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Pago guardado con éxito.',
                                text: 'El servicio se está reanudando...',
                                showConfirmButton: false,
                            });

                            $.ajax({
                                url:"{{route('unbarring.get')}}",
                                method: "GET",
                                data: {payID:payID},
                                success:function(response){
                                    
                                    if(response == 1){
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'El servicio ha sido reanudado.',
                                            showConfirmButton: false,
                                        });
                                        setTimeout(function(){ location.href = "{{route('my-charges')}}"; }, 2500);
                                    }else{
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Hubo un problema al reanudar el servicio.',
                                            text: 'Bad Request'
                                        });
                                    }
                                    
                                }
                            });
                        }else if(data == 0){
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un problema al guardar el pago.',
                                text: 'Bad Request'
                            });
                        }
                    }
                });  
            }
        });
    }
</script>
@endsection