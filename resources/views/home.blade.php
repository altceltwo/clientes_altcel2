@extends('layouts.octopus')
@extends('layouts.datatablescss')
@section('content')
<header class="page-header">
    <h2>Dashboard</h2>
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
<div class="search-control-wrapper">
    <form action="pages-search-results.html">
        <div class="form-group">
            <label for="msisdnHome">Ingresa tu número de SIM</label>
            <div class="input-group">
                <input type="text" class="form-control" maxlength="10" id="msisdnHome">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="consultUF">Consultar</button>
                </span>
            </div>
        </div>
    </form>
</div>

@if(session('message'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4 class="alert-heading">Well done!!</h4>
        <p>{{session('message')}}</p>
    </div>
@endif
<!-- Dashboard -->
@if(Auth::user()->role_id != 7)

@endif
<!-- Final Dashboard -->

@if(Auth::user()->role_id == 7)
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>

            <h2 class="panel-title">Mis Clientes</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead >
                    <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">RFC</th>
                    <th scope="col">Interesado en...</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($myClients as $myClient)
                    <tr style="cursor: pointer;" onclick="redirectTo(this.id)" id="{{$myClient->user_id}}">
                        <td>{{$myClient->name.' '.$myClient->lastname}}</td>
                        <td>{{$myClient->email}}</td>
                        <td>{{$myClient->address}}</td>
                        <td>{{$myClient->rfc}}</td>
                        <td>{{$x = $myClient->interests == null ? 'N/A' : $myClient->interests}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif

@if(Auth::user()->role_id == 3)

    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>

            <h2 class="panel-title">Servicios Contratados</h2>
        </header>
        <div class="panel-body">
        
            <table class="table table-bordered table-striped mb-none" id="datatable-default2">
            <thead style="cursor: pointer;">
                <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Servicio</th>
                <th scope="col">Número</th>
                <th scope="col">Paquete</th>
                <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $activations as $activation )
            <tr style="cursor: pointer;">
                <td>{{ $activation->date_activation }}</td>
                <td>{{ $activation->service }}</td>
                <td>{{ $activation->DN }}</td>
                <td>{{ $activation->pack_name }}</td>
                <td>
                    <a class="btn btn-success btn-xs" href="{{url('/surplusRates/'.$activation->DN)}}" data-toggle="tooltip" data-placement="top" data-original-title="Realizar una recarga">
                    <i class="fa fa-refresh mr-xs"></i><i class="fa fa-dollar"></i>
                    </a>

                    <a class="btn btn-info btn-xs" href="{{url('/consultUF/'.$activation->DN)}}" data-toggle="tooltip" data-placement="top" data-original-title="Consulta tu plan">
                    <i class="fa fa-info-circle"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            @foreach( $instalations as $instalation )
            <tr style="cursor: pointer;">
                <td>{{ $instalation->date_instalation }}</td>
                <td>{{ $instalation->service }}</td>
                <td>N/A</td>
                <td>{{ $instalation->pack_name }}</td>
                <td>
                    
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

            <h2 class="panel-title">Próximos pagos</h2>
        </header>
        <div class="panel-body">
        
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead style="cursor: pointer;">
                    <tr>
                    <th scope="col">Servicio</th>
                    <th scope="col">Número</th>
                    <th scope="col">Plan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Fecha de Pago</th>
                    <th scope="col">Fecha Límite</th>
                    <th scope="col">Días restantes</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                @foreach( $mypays as $mypay )
                <tr style="cursor: pointer;">
                    <td>{{ $mypay->number_product }}</td>
                    <td>{{ $mypay->DN }}</td>
                    <td>{{ $mypay->rate_name }}</td>
                    <td>{{ $mypay->status }}</td>
                    <td>{{ $mypay->date_pay }}</td>
                    <td>{{ $mypay->date_pay_limit }}</td>
                    @php
                    $fecha1= new DateTime('NOW');
                    $fecha2= new DateTime($mypay->date_pay);
                    $diff = $fecha1->diff($fecha2);
                    @endphp
                    <td>{{$diff->days.' DÍAS'}}</td>
                    <td>{{ '$'.number_format($mypay->rate_price,2) }}</td>
                    @php
                        $ref = $mypay->reference_id == null ? 'N/A' : $mypay->reference_id
                    @endphp

                    @if($ref == 'N/A')
                    <td>
                        <a href="{{url('/generateReference/'.$mypay->id.'/'.$mypay->number_product.'/'.Auth::user()->id)}}" class="btn btn-success btn-sm mt-sm"><i class="fa fa-money"></i></a>
                        <a href="{{url('/card-payment/'.$mypay->id.'/'.$mypay->number_product.'/'.Auth::user()->id)}}" class="btn btn-success btn-sm mt-sm"><i class="fa fa-credit-card"></i></a>
                    </td>
                    @else
                    <td><button type="button" onclick="ref(this.id)" class="btn btn-warning btn-sm ref-generated" id="{{ $ref }}"><i class="fa fa-eye"></i></button>
                    </td>
                    @endif
                    
                </tr>
                @endforeach
                @foreach( $my2pays as $my2pay )
                <tr>
                    <td>{{ $my2pay->service_name }}</td>
                    <td>N/A</td>
                    <td>{{ $my2pay->pack_name }}</td>
                    <td>{{ $my2pay->status }}</td>
                    <td>{{ $my2pay->date_pay }}</td>
                    <td>{{ $my2pay->date_pay_limit }}</td>
                    @php
                    $fecha1= new DateTime('NOW');
                    $fecha2= new DateTime($my2pay->date_pay);
                    $diff = $fecha1->diff($fecha2);
                    @endphp
                    <td>{{$diff->days.' DÍAS'}}</td>
                    <td>{{ '$'.number_format($my2pay->pack_price,2) }}</td>
                    @php
                        $ref = $my2pay->reference_id == null ? 'N/A' : $my2pay->reference_id
                    @endphp

                    @if($ref == 'N/A')
                    <td>
                        <a href="{{url('/generateReference/'.$my2pay->id.'/'.$my2pay->service_name.'/'.Auth::user()->id)}}" class="btn btn-success btn-sm mt-sm"><i class="fa fa-money"></i></a>
                        <a href="{{url('/card-payment/'.$my2pay->id.'/'.$my2pay->service_name.'/'.Auth::user()->id)}}" class="btn btn-success btn-sm mt-sm"><i class="fa fa-credit-card"></i></a>
                    </td>
                    @else
                    <td><button onclick="ref(this.id)" class="btn btn-warning btn-sm ref-generated" id="{{ $ref }}"><i class="fa fa-eye"></i></button>
                    </td>
                    @endif
                    
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

<div class="modal fade" id="reference" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleRef"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe class="col-md-12" id="reference-pdf" style="height: 400px;" src=""></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de referencia OxxoPay -->
<div class="modal fade" id="referenceOxxo" tabindex="-1" aria-labelledby="referenceOxxo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="referenceOxxoLabel">Referencia OXXOPay</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="opps">
                <div class="opps-header">
                    <div class="opps-reminder">Ficha digital, puedes capturar la pantalla. No es necesario imprimir.</div>
                        <div class="opps-info">
                            <div class="opps-brand"><img src="{{asset('storage/uploads/oxxopay_brand.png')}}" alt="OXXOPay"></div>
                            <div class="opps-ammount">
                                <h3 class="title-3">Monto a pagar</h3>
                                <!-- <h2>$ 0,000.00 <sup>MXN</sup></h2> -->
                                <h2 id="montoOxxo"></h2>
                                <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3 class="title-3">Referencia</h3>
                            <h1 class="referenceOxxoCard" id="referenceOxxoCard"></h1>
                        </div>
                    </div>
                    <div class="opps-instructions">
                        <h3 class="title-3">Instrucciones</h3>
                        <ol class="instructions">
                            <li style="margin-top: 10px;color: #000000;">Acude a la tienda OXXO más cercana. <a class="search-oxxo" href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
                            <li style="margin-top: 10px;color: #000000;">Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
                            <li style="margin-top: 10px;color: #000000;">Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
                            <li style="margin-top: 10px;color: #000000;">Realiza el pago correspondiente con dinero en efectivo.</li>
                            <li style="margin-top: 10px;color: #000000;">Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
                        </ol>
                        <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div>
                    </div>
                </div>	
            <div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
        </div>
    </div>
</div>
@endif



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
                        // x=data;
                        if(data.channel_id == 1){
                            $('#modalTitleRef').html('Referencia '+data.reference)
                            $('#reference-pdf').attr('src', link+data.reference);
                            $('#reference').modal('show');
                        }else if(data.channel_id == 2){
                            $('#montoOxxo').html('$'+data.amount+'<sup>MXN</sup>');
                            $('#referenceOxxoCard').html(data.reference);
                            
                            $('#referenceOxxo').modal('show');
                            console.log('referencia OxxoPay');
                        }
                        console.log(data.reference);
                    }
                }); 
    }

    function redirectTo(id){
        var url = "{{ route('clients.show', ['client' => 'temp']) }}";
        url = url.replace('temp', id);
        location.href = url;
    }

    $('#consultUF').click(function(){
        let msisdn = $('#msisdnHome').val();
        let url = "{{ route('consultUF.get', ['msisdn' => 'temp']) }}";
        url = url.replace('temp',msisdn);
        Swal.fire({
            title: 'Estamos extrayendo la información de tu SIM...',
            html: 'Espera un poco, un poquito más...',
            didOpen: () => {
                Swal.showLoading();
                location.href = url;
            }
        });
    });
</script>
@endsection