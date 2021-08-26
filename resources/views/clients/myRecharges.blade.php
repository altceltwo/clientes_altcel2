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
                    <th scope="col">Recarga</th>
                    <th scope="col">Plan Activo</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Monto</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($recharges as $recharge)
                    <tr style="cursor: pointer;" >
                        <td>{{$recharge->number}}</td>
                        <td>{{$recharge->product}}</td>
                        <td>{{$recharge->offer_name}}</td>
                        <td>{{$recharge->rate_name}}</td>
                        <td>{{$recharge->date_purchase}}</td>
                        <td>${{number_format($recharge->amount,2)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif



<script>
    
    

    
</script>
@endsection