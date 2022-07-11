@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Mis Solicitudes Completadas</h2>
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

        <h2 class="panel-title">Solicitudes Completadas</h2>
    </header>
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped mb-none" id="datatable-default">
            <thead >
                <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Producto</th>
                <th scope="col">Plan Activación</th>
                <th scope="col">Status</th>
                <th scope="col">Cobro CPE</th>
                <th scope="col">Cobro PLAN</th>
                <th scope="col">Fecha Solicitud</th>
                <th scope="col">Activado por</th>
                <th scope="col">Fecha activación</th>
                <th scope="col">Comentario</th>
                <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $petitions as $petition )
            <tr style="cursor: pointer;">
                <td>{{ $petition['client_name'] }}</td>
                <td>{{ $petition['product'] }}</td>
                <td>{{ $petition['rate_activation'] }}</td>
                <td><span class="badge label-success">{{ $petition['status'] }}</span></td>
                <td>${{ number_format($petition['collected_device'],2) }}</td>
                <td>${{ number_format($petition['collected_rate'],2) }}</td>
                <td>{{ $petition['date_sent'] }}</td>
                <td><span class="badge label-success">{{ $petition['who_activated'] }}</span></td>
                <td>{{ $petition['date_activated'] }}</td>
                <td>{{ $petition['comment'] }}</td>
                <td>
                    <button class="btn btn-success btn-sm format" data-petition-id="{{$petition['id']}}" data-toggle="tooltip" data-placement="top" data-original-title="Formato de Entrega" ><i class="fa fa-file-text-o"></i></button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <table class="table table-bordered table-striped mb-none" id="datatable-default2">
            <thead >
                <tr>
                <th scope="col">MSISDN</th>
                <th scope="col">Tipo</th>
                <th scope="col">Cliente</th>
                <th scope="col">Status</th>
                <th scope="col">Comentario</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $otherpetitions as $otherpetition )
            <tr style="cursor: pointer;">
                <td>{{ $otherpetition->msisdn }}</td>
                <td><span class="badge label-info">{{ $otherpetition->type }}</span></td>
                <td>{{ $otherpetition->client_name.' '.$otherpetition->client_lastname }}</td>
                <td><span class="badge label-success">{{ $otherpetition->status }}</span></td>
                <td>{{ $otherpetition->comment }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
<script>
    $('.format').click(function(){
        let petition = $(this).data('petition-id');
        let url = "{{route('getActivation',['petition'=>'temp'])}}";
        url = url.replace('temp',petition);
        let urlFormat = "{{route('formatDelivery',['activation' => 'temp'])}}";

        $.ajax({
            url: url,
            success: function(response){
                urlFormat = urlFormat.replace('temp',response);
                window.open(urlFormat,'','width=600,height=400,left=50,top=50,toolbar=yes');
            }
        });
    });
</script>
@endsection