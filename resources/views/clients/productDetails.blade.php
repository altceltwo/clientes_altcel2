@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Producto</h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
        </ol>

        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
    </div>
</header>
<section class="panel">
    <div class="panel-body">
        <div class="invoice">
            <header class="clearfix">
                <div class="row">
                    <div class="col-sm-6 mt-md">
                        @if($service == 'MIFI' || $service == 'HBB')
                        <h4 class="card-title"></h4>
                        <h2 class="h2 mt-none mb-sm text-dark text-bold">{{$service.'/'.$DN}}</h2>
                        @elseif($service == 'Conecta' || $service == 'Telmex')
                        <h2 class="h2 mt-none mb-sm text-dark text-bold">{{$service}}</h2>
                        @endif
                        <h4 class="h4 m-none text-dark text-bold">Paquete/Plan Actual: <br>
                        {{$pack_name}} - {{'$'.number_format($pack_price,2)}}</h4>
                    </div>
                    <div class="col-sm-6 text-right mt-md mb-md">
                        <address class="ib mr-xlg">
                            Fecha de Activación: {{$date_activation}}
                            @if($service == 'MIFI' || $service == 'HBB')
                                <br/>
                                    @php
                                        $tag = $traffic_out == 'activo' ? 'label-success' : 'label-danger';
                                        $tagS = $traffic_out_in == 'activo' ? 'label-success' : 'label-danger'
                                    @endphp
                                Tráfico Saliente: <span class="label {{$tag}}" style="cursor: pointer;" data-msisdn="{{$DN}}" data-status="{{$traffic_out}}" data-toggle="tooltip" data-placement="left" title="Suspensión/Reanudación del Tráfico Saliente" id="traffic_out">{{$traffic_out}}</span>
                                <br/>
                                Tráfico Saliente/Entrante: <span class="label {{$tagS}}" style="cursor: pointer;" data-msisdn="{{$DN}}" data-status="{{$traffic_out_in}}" data-toggle="tooltip" data-placement="left" title="Suspensión/Reanudación del Tráfico Saliente/Entrante" id="traffic_out_in">{{$traffic_out_in}}</span>
                            </div>
                            @endif
                        </address>
                    </div>
                </div>
            </header>
            <br>
        
            <div class="table-responsive">
            <h4 class="h4 m-none text-dark text-bold">Pagos realizados</h4><br>
                <table class="table invoice-items">
                    <thead>
                        <tr class="h4 text-dark">
                            <th id="cell-desc" class="text-semibold">Fecha</th>
                            <th id="cell-desc" class="text-semibold">Canal de Pago</th>
                            <th id="cell-desc" class="text-semibold">Referencia</th>
                            <th id="cell-desc" class="text-right text-semibold">Monto</th>
                        </tr>
                    </thead>
                    <tbody>

                    @php
                        $total = 0;
                        $fee_total = 0;
                        $ingreso = 0;
                    @endphp
                    @foreach($mypays as $mypay)
                    <tr>
                        <td class="text-semibold text-dark">{{$mypay->updated_at}}</td>
                        <td class="text-semibold text-dark">{{$mypay->type_pay}}</td>
                        <td class="text-semibold text-dark">{{$mypay->reference}}</td>
                        <td class="text-right text-semibold text-dark">{{number_format($mypay->amount,2).' '.$mypay->currency}}</td>
                        @php
                            $total += $mypay->amount;
                            $fee_total += $mypay->fee_amount;
                        @endphp
                    </tr>
                    @endforeach

                    @foreach($mypaysManual as $mypayManual)
                    <tr>
                        <td class="text-semibold text-dark">{{$mypayManual->updated_at}}</td>
                        <td class="text-semibold text-dark">{{$mypayManual->type_pay}}</td>
                        <td class="text-semibold text-dark">{{$mypayManual->folio_pay}}</td>
                        <td class="text-right text-semibold text-dark">${{number_format($mypayManual->amount,2).' MXN'}}</td>
                        @php
                            $total += $mypayManual->amount;
                        @endphp
                    </tr>
                    @endforeach

                    @php
                        $ingreso = $total - $fee_total;
                    @endphp
                    </tbody>
                </table>
            </div>
        
            <div class="invoice-summary">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8">
                        <table class="table h5 text-dark">
                            <tbody>
                                <tr class="b-top-none">
                                    <td colspan="2">Subtotal</td>
                                    <td class="text-left">${{number_format($total,2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Comisiones</td>
                                    <td class="text-left">${{number_format($fee_total,2)}}</td>
                                </tr>
                                <tr class="h4">
                                    <td colspan="2">Total</td>
                                    <td class="text-left">${{number_format($ingreso,2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="text-right mr-lg">
            <a href="#" class="btn btn-default">Submit Invoice</a>
            <a href="pages-invoice-print.html" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Print</a>
        </div> -->
    </div>
</section>


<script>
    var token = $('meta[name="csrf-token"]').attr('content'); 

    $('#traffic_out').click(function(){
        let status = $(this).attr('data-status');
        let msisdn = $(this).attr('data-msisdn');
        let type = 'out';
        activateDeactivateTraffic(status,type,msisdn);
    });

    $('#traffic_out_in').click(function(){
        let status = $(this).attr('data-status');
        let msisdn = $(this).attr('data-msisdn');
        let type = 'out_in';
        activateDeactivateTraffic(status,type,msisdn);
    });

    function activateDeactivateTraffic(status, type, msisdn){
        let headers = {headers: {'Content-type': 'application/json'}};
        let data = {
                _token: token, status: status, type: type, msisdn: msisdn
            };

            Swal.fire({
                title: 'Estamos trabajando en ello.',
                html: 'Espera un poco, un poquito más...',
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url:"{{route('activate-deactivate.post')}}",
                        method: "POST",
                        data: data,
                        success: function(data){
                            if(data.bool == 1){
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'success',
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    setTimeout(function(){ location.reload(); }, 1500);
                                    // alert(data.bool)
                                }else if(data.bool == 0){
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error: '+data.errorCode,
                                        text: data.description
                                    });
                                    // alert(data.bool)
                                }
                        }
                    });  
                }
            });
            
                // axios.post('/activate-deactivate/DN', data, headers).then(response => {
                        
                //         // if(response.data == 0){
                //         //     
                //         // }
                //     }).catch(e => {
                //         console.log(e);
                //     })
    }
</script>
@endsection