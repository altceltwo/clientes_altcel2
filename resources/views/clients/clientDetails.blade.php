@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Detalles de <strong>{{$client_name}}</strong></h2>
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
<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
        </div>

        <h2 class="panel-title">Próximos Pagos</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
            <thead style="cursor: pointer;">
                <tr>
                <th scope="col">Servicio</th>
                <th scope="col">Número</th>
                <th scope="col">Plan</th>
                <!-- <th scope="col">Status</th> -->
                <th scope="col">Fecha de Pago</th>
                <th scope="col">Fecha Límite</th>
                <th scope="col">Días restantes</th>
                <th scope="col">Monto</th>
                <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $mypays as $mypay )
            <tr style="cursor: pointer;">
                <td>{{ $mypay->number_product }}</td>
                <td>{{ $mypay->DN }}</td>
                <td>{{ $mypay->rate_name }}</td>
                <!-- <td>{{ $mypay->status }}</td> -->
                <td>{{ $mypay->date_pay }}</td>
                <td>{{ $mypay->date_pay_limit }}</td>
                @php
                $fecha1= new DateTime('NOW');
                $fecha2= new DateTime($mypay->date_pay);
                $diff = $fecha1->diff($fecha2);
                @endphp
                <td>{{$diff->days.' DÍAS'}}</td>
                <td>{{ '$'.number_format($mypay->amount,2) }}</td>
                <td>
                @php
                    $ref = $mypay->reference_id == null ? 'N/A' : $mypay->reference_id
                @endphp

                @if($ref == 'N/A')
                    
                    <a href="{{url('/generateReference/'.$mypay->id.'/'.$mypay->number_product.'/'.$client_id)}}" class="btn btn-success btn-sm mt-xs"><i class="fa fa-money"></i>Referencia</a>
                @else
                    <button type="button" onclick="ref(this.id)" class="btn btn-warning btn-sm ref-generated" id="{{ $ref }}"><i class="fa fa-eye"></i></button>
                @endif
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
                        <button type="button" class="btn btn-success btn-sm mt-xs register-pay-manual" payID="{{$mypay->id}}" service="{{$mypay->number_product}}"><i class="fa fa-money"></i>Registrar</button>
                    @endif
                    <a href="{{url('/show-product-details/'.$mypay->number_id.'/'.$mypay->id.'/'.$mypay->activation_id.'/'.$mypay->number_product)}}" class="btn btn-info btn-sm mt-xs"><i class="fa fa-info-circle"></i></a>
                </td>
            </tr>
            @endforeach
            @foreach( $my2pays as $my2pay )
            <tr style="cursor: pointer;">
                <td>{{ $my2pay->service_name }}</td>
                <td>N/A</td>
                <td>{{ $my2pay->pack_name }}</td>
                <!-- <td>{{ $my2pay->status }}</td> -->
                <td>{{ $my2pay->date_pay }}</td>
                <td>{{ $my2pay->date_pay_limit }}</td>
                @php
                $fecha1= new DateTime('NOW');
                $fecha2= new DateTime($my2pay->date_pay);
                $diff = $fecha1->diff($fecha2);
                @endphp
                <td>{{$diff->days.' DÍAS'}}</td>
                <td>{{ '$'.number_format($my2pay->amount,2) }}</td>
                <td>
                @php
                    $ref = $my2pay->reference_id == null ? 'N/A' : $my2pay->reference_id
                @endphp

                @if($ref == 'N/A')
                    
                    <a href="{{url('/generateReference/'.$my2pay->id.'/'.$my2pay->service_name.'/'.$client_id)}}" class="btn btn-success btn-sm mt-xs"><i class="fa fa-money"></i>Referencia</a>
                @else
                    <button type="button" onclick="ref(this.id)" class="btn btn-warning btn-sm ref-generated mt-xs" id="{{ $ref }}"><i class="fa fa-eye"></i></button>
                @endif
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
                        <button type="button" class="btn btn-success btn-sm mt-xs register-pay-manual" payID="{{$my2pay->id}}" service="{{$my2pay->service_name}}"><i class="fa fa-money"></i>Registrar</button>
                    @endif
                    <a href="{{url('/show-product-details/null/'.$my2pay->id.'/'.$my2pay->instalation_id.'/'.$my2pay->service_name)}}" class="btn btn-info btn-sm mt-xs"><i class="fa fa-info-circle"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>

<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
        </div>

        <h2 class="panel-title">Pagos completados</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped mb-none" id="datatable-default2">
            <thead style="cursor: pointer;">
                <tr>
                <th scope="col">Servicio</th>
                <th scope="col">Número</th>
                <th scope="col">Plan</th>
                <th scope="col">Status</th>
                <th scope="col">Fecha de Pago</th>
                <th scope="col">Fecha Límite</th>
                <th scope="col">Monto</th>
                <th scope="col">Forma de Pago</th>
                <th scope="col">Monto Recibido</th>
                <th scope="col">Folio</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $completemypays as $completemypay )
            <tr style="cursor: pointer;">
                <td>{{ $completemypay->number_product }}</td>
                <td>{{ $completemypay->DN }}</td>
                <td>{{ $completemypay->rate_name }}</td>
                <td>{{ $completemypay->status }}</td>
                <td>{{ $completemypay->date_pay }}</td>
                <td>{{ $completemypay->date_pay_limit }}</td>
                <td>{{ '$'.number_format($completemypay->amount,2) }}</td>
                <td>{{ $completemypay->type_pay }}</td>
                <td>{{ '$'.number_format($completemypay->amount_received,2) }}</td>
            @php
                $folio = $completemypay->reference_id == null ? $completemypay->folio_pay : $completemypay->reference_id;
            @endphp
                <td>{{ $folio }}</td>
            </tr>
            @endforeach
            @foreach( $completemy2pays as $completemy2pay )
            <tr style="cursor: pointer;">
                <td>{{ $completemy2pay->service_name }}</td>
                <td>N/A</td>
                <td>{{ $completemy2pay->pack_name }}</td>
                <td>{{ $completemy2pay->status }}</td>
                <td>{{ $completemy2pay->date_pay }}</td>
                <td>{{ $completemy2pay->date_pay_limit }}</td>
                <td>{{ '$'.number_format($completemy2pay->amount,2) }}</td>
                <td>{{ $completemy2pay->type_pay }}</td>
                <td>{{ '$'.number_format($completemy2pay->amount_received,2) }}</td>
            @php
                $folio = $completemy2pay->reference_id == null ? $completemy2pay->folio_pay : $completemy2pay->reference_id;
            @endphp
                <td>{{ $folio }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>

<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
        </div>

        <h2 class="panel-title">Servicios Contratados</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped mb-none" id="datatable-default3">
            <thead >
                <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Servicio</th>
                <th scope="col">Número</th>
                <th scope="col">Paquete</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $activations as $activation )
            <tr style="cursor: pointer;">
                <td>{{ $activation->date_activation }}</td>
                <td>{{ $activation->service }}</td>
                <td>{{ $activation->DN }}</td>
                <td>{{ $activation->pack_name }}</td>
            </tr>
            @endforeach
            @foreach( $instalations as $instalation )
            <tr style="cursor: pointer;">
                <td>{{ $instalation->date_instalation }}</td>
                <td>{{ $instalation->service }}</td>
                <td>N/A</td>
                <td>{{ $instalation->pack_name }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>   
    </div>
</section>

<!-- Modal Referencia -->
<div class="modal fade" id="reference" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleRef">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe class="col-md-12" id="reference-pdf" style=" width: 100%; height: 400px;" src=""></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    function ref(reference_id){
        // let link = 'https://sandbox-dashboard.openpay.mx/paynet-pdf/mvtmmoafnxul8oizkhju/';
        let link = 'https://dashboard.openpay.mx/paynet-pdf/m3one5bybxspoqsygqhz/';

            $.ajax({
                    url:"{{url('/show-reference')}}",
                    method: "GET",
                    data: {
                        reference_id: reference_id
                    },
                    success: function(data){
                        console.log(data.reference);
                        $('#modalTitleRef').html('Referencia '+data.reference)
                        $('#reference-pdf').attr('src', link+data.reference);
                        $('#reference').modal('show');
                    }
                }); 
    }

    $('.register-pay-manual').click(function(){
        let service = $(this).attr('service');
        let payID = $(this).attr('payID');
        let preg = /^([0-9]+\.?[0-9]{0,2})$/; 
        let monto = 0;
        let typePay = '';
        let folioPay = '';
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
                const { value: methodPay } = await Swal.fire({
                    title: 'Método de pago por el monto de $'+amount,
                    input: 'select',
                    inputOptions: {
                        'efectivo': 'Efectivo',
                        'deposito': 'Depósito',
                        'transferencia': 'Transferencia',
                    },
                    inputPlaceholder: 'Elige uno...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                        if (value == 'efectivo' || value == 'deposito' || value == 'transferencia') {
                            typePay = value;
                            resolve()
                        } else {
                            resolve('Seleccione un método de pago, por favor')
                        }
                        })
                    }
                })

                if (methodPay) {
                    typePay = methodPay;

                    const { value: folio } = await Swal.fire({
                        title: 'Ingrese el folio de pago, por favor',
                        input: 'text',
                        inputValue: 'N/A',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Ingrese un dato, por favor'
                            }
                        }
                    })

                    if(folio){
                        folioPay = folio;
                        Swal.fire({
                            title: 'Verifique los datos siguientes:',
                            text: "Monto: $"+monto+" - Método: "+typePay+' - Folio: '+folioPay,
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
                                registryPay(service, payID, monto, typePay, folioPay);
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
            }
        })()
    });

    function registryPay(service, payID, monto, typePay, folioPay){
        // console.log(service+' - '+payID+' - '+monto+' - '+typePay+' - '+folioPay);
        let data = {
            service: service,
            payID: payID,
            monto: monto,
            typePay: typePay,
            folioPay: folioPay};

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
                        if(data == 1){
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: 'Éxito'
                            })
                        }else if(data == 0){
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Hubo un pedo',
                                text: 'Bad Request'
                            })
                        }
                    }
                });  
            }
        });
    }
</script>
@endsection