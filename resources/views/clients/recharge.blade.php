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

<!-- Dashboard -->
@if(Auth::user()->role_id != 7)

@endif
<!-- Final Dashboard -->

@if(Auth::user()->role_id == 4)
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
                    <th scope="col">Número</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Plan</th>
                    <th scope="col">Correo</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr style="cursor: pointer;" onclick="redirectTo(this.id,this)" id="{{$client->number}}" data-name="{{$client->name.' '.$client->lastname}}">
                        <td>{{$client->number}}</td>
                        <td>{{$client->product}}</td>
                        <td>{{$client->name.' '.$client->lastname}}</td>
                        <td>{{$client->rate_name}}</td>
                        <td>{{$client->email}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
<div class="modal fade" id="modalPaymentOptions" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-dark text-bold" id="myModalTitle">Opciones de Pago</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered" action="" method="" id="form-update-rate">
                
                    <div class="form-group"  style="padding-right: 1rem; padding-left: 1rem;">
                        <div class="form-group col-md-12">
                            <!-- <label class="control-label col-md-6">Vertical Group w/ icon</label> -->
                            <div class="col-md-12">
                                <center>
                                    <a href="" class="btn btn-success mt-md" id="monthlyPayment">Pago Mensual</a>
                                    <a href="" class="btn btn-primary mt-md" id="surplusPayment">Pago Excedente</a>
                                    <a href="" class="btn btn-warning mt-md" id="changeProduct">Cambio de Producto</a>
                                </center>
                            </div>
                        </div>
                    </div>              

                </form>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
@endif



<script>
    function redirectTo(id,identifier){
        let name = $(identifier).data('name');
        var url = "{{ route('surplusRatesDealer', ['msisdn' => 'temp']) }}";
        var urlMonthly = "{{ route('monthlyPayments', ['msisdn' => 'temp']) }}";
        var urlChangeProduct = "{{ route('changeProduct', ['msisdn' => 'temp']) }}";
        url =  url.replace('temp',id);
        urlMonthly =  urlMonthly.replace('temp',id);
        urlChangeProduct =  urlChangeProduct.replace('temp',id);
        
        $('#surplusPayment').attr('href',url);
        $('#monthlyPayment').attr('href',urlMonthly);
        $('#changeProduct').attr('href',urlChangeProduct);
        $('#myModalTitle').html('Opciones de Pago para '+name+' con SIM '+id);

        $('#modalPaymentOptions').modal('show');
    }
</script>
@endsection