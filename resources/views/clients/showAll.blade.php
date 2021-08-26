@extends('layouts.octopus')

@section('content')
<header class="page-header">
    <h2>Mis Clientes</h2>
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

        <h2 class="panel-title">Clientes</h2>
    </header>
    <div class="panel-body">
        
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
            <thead >
                <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Contacto</th>
                <th scope="col">RFC</th>
                <th scope="col">Dirección</th>
                <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $clients as $client )
            <tr style="cursor: pointer;">
                <td>{{ $client->name.' '.$client->lastname }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->client_phone }}</td>
                <td>{{ $client->RFC }}</td>
                <td>{{ $client->client_address }}</td>
                <td>
                    <a href="{{url('/clients-details/'.$client->id)}}" class="btn btn-info btn-sm"><i class="fa fa-info-circle"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table> 
    </div>
</section>

<script>
   
</script>
@endsection