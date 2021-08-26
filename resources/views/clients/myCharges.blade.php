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

        <h2 class="panel-title">Pagos Cobrados </h2>
    </header>
    <div class="panel-body" id="monthlyPayments">
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
            <thead style="cursor: pointer;">
                <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Servicio</th>
                <th scope="col">Número</th>
                <th scope="col">Monto</th>
                <th scope="col">Extra</th>
                <th scope="col">Fecha Pago</th>
                </tr>
            </thead>
            <tbody>
           
            @foreach($paymentsCompleted as $paymentCompleted)
                <tr>
                    <td>{{$paymentCompleted->client_name.' '.$paymentCompleted->client_lastname}}</td>
                    <td>{{$paymentCompleted->rate_name}}</td>
                    <td>{{$paymentCompleted->DN}}</td>
                    <td>${{number_format($paymentCompleted->amount_received,2)}}</td>
                    <td>${{number_format($paymentCompleted->extra,2)}}</td>
                    <td>{{$paymentCompleted->date_pay}}</td>
                </tr>
            @endforeach
         
            </tbody>
        </table>
       
    </div>

</section>


<script>

</script>
@endsection