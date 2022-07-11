@extends('layouts.octopustwo')
@section('content')
<header class="page-header">
    <h2>Mis Portabilidades Pendientes</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Dashboard</span></li>
        </ol>
    </div>
</header>


<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
        </div>

        <h2 class="panel-title">Pendientes</h2>
    </header>
    <div class="panel-body table-responsive" >
        <table class="table table-bordered table-striped mb-none" id="myTable">
            <thead style="cursor: pointer;">
                <tr>
                    <th>Número Portado</th>
                    <th>ICC</th>
                    <th>Número Transitorio</th>
                    <th>Fecha para Activar</th>
                    <th>Fecha para Portar</th>
                    <th>NIP</th>
                    <th>Plan Activación</th>
                    <th>Cliente</th>
                    <th>Enviado por</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pendings as $pending)
                <tr style="cursor: pointer;" >
                    <td>{{$pending['msisdnPorted']}}</td>
                    <td>{{$pending['icc']}}</td>
                    <td>{{$pending['msisdnTransitory']}}</td>
                    <td>{{$pending['date']}}</td>
                    <td>{{$pending['approvedDateABD']}}</td>
                    <td>{{$pending['nip']}}</td>
                    <td>{{$pending['rate']}}</td>
                    <td>{{$pending['client']}}</td>
                    <td>{{$pending['who_did_it']}}</td>
                    <td>{{$pending['comments']}}</td>
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

        <h2 class="panel-title">Activadas</h2>
    </header>
    <div class="panel-body table-responsive" >
        <table class="table table-bordered table-striped mb-none" id="myTable2">
            <thead style="cursor: pointer;">
                <tr>
                    <th>Número Portado</th>
                    <th>ICC</th>
                    <th>Número Transitorio</th>
                    <th>Fecha para Activar</th>
                    <th>Fecha para Portar</th>
                    <th>NIP</th>
                    <th>Plan Activación</th>
                    <th>Cliente</th>
                    <th>Enviado por</th>
                    <th>Atendido por</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>
            @foreach($activateds as $activated)
                <tr style="cursor: pointer;" >
                    <td>{{$activated['msisdnPorted']}}</td>
                    <td>{{$activated['icc']}}</td>
                    <td>{{$activated['msisdnTransitory']}}</td>
                    <td>{{$activated['date']}}</td>
                    <td>{{$activated['approvedDateABD']}}</td>
                    <td>{{$activated['nip']}}</td>
                    <td>{{$activated['rate']}}</td>
                    <td>{{$activated['client']}}</td>
                    <td>{{$activated['who_did_it']}}</td>
                    <td>{{$activated['who_attended']}}</td>
                    <td>{{$activated['comments']}}</td>
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

        <h2 class="panel-title">Completadas</h2>
    </header>
    <div class="panel-body table-responsive" >
        <table class="table table-bordered table-striped mb-none" id="myTable3">
            <thead style="cursor: pointer;">
                <tr>
                    <th>Número Portado</th>
                    <th>ICC</th>
                    <th>Número Transitorio</th>
                    <th>Fecha para Activar</th>
                    <th>Fecha para Portar</th>
                    <th>NIP</th>
                    <th>Plan Activación</th>
                    <th>Cliente</th>
                    <th>Enviado por</th>
                    <th>Atendido por</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>
            @foreach($completeds as $completed)
                <tr style="cursor: pointer;" >
                    <td>{{$completed['msisdnPorted']}}</td>
                    <td>{{$completed['icc']}}</td>
                    <td>{{$completed['msisdnTransitory']}}</td>
                    <td>{{$completed['date']}}</td>
                    <td>{{$completed['approvedDateABD']}}</td>
                    <td>{{$completed['nip']}}</td>
                    <td>{{$completed['rate']}}</td>
                    <td>{{$completed['client']}}</td>
                    <td>{{$completed['who_did_it']}}</td>
                    <td>{{$completed['who_attended']}}</td>
                    <td>{{$completed['comments']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
       
    </div>
</section>

<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script >
    $(document).ready( function () {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel',
            ],
        });

        $('#myTable2').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel',
            ],
        });

        $('#myTable3').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel',
            ],
        });
    });
</script>
@endsection