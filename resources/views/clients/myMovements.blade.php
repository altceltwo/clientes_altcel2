@extends('layouts.octopustwo')
@section('content')
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

        <h2 class="panel-title">Pagos Cobrados General</h2>
    </header>
    <div class="col-md-12 pl-xl row">
        <div class="checkbox-custom checkbox-success col-md-4">
            <input type="checkbox" id="checkboxExample3">
            <label for="checkboxExample3">Recargas</label>
        </div>
        <div class="checkbox-custom checkbox-warning col-md-4">
            <input type="checkbox" id="checkboxExample3">
            <label for="checkboxExample3">Cambios de Producto</label>
        </div>
        <div class="checkbox-custom checkbox-danger col-md-4">
            <input type="checkbox" id="checkboxExample3">
            <label for="checkboxExample3">Mensualidades</label>
        </div>
    </div>
    <div class="panel-body" id="monthlyPayments">
        <table class="table table-bordered table-striped mb-none" id="myTable">
            <thead style="cursor: pointer;">
                <tr>
                <th>Cliente</th>
                <th>Empresa</th>
                <th>Cobrado por</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Servicio</th>
                <th>Plan</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Extra</th>
                </tr>
            </thead>
            <tbody>
            @foreach($recharges as $recharge)
                <tr class="success" style="cursor: pointer;" >
                    <td>{{$recharge['client_name'].' '.$recharge['client_lastname']}}</td>
                    <td>{{$recharge['company']}}</td>
                    <td>{{$recharge['who_did_it']}}</td>
                    <td>{{$recharge['number']}}</td>
                    <td>EXCEDENTE</td>
                    <td>{{$recharge['product']}}</td>
                    <td>{{$recharge['rate_name']}}</td>
                    <td>{{$recharge['date_purchase']}}</td>
                    <td>${{number_format($recharge['amount'],2)}}</td>
                    <td>N/A</td>
                </tr>
            @endforeach
            @foreach($changes as $change)
                <tr class="warning" style="cursor: pointer;" >
                    <td>{{$change['client_name'].' '.$change['client_lastname']}}</td>
                    <td>{{$change['company']}}</td>
                    <td>{{$change['who_did_it']}}</td>
                    <td>{{$change['number']}}</td>
                    <td>CAMBIO DE PRODUCTO</td>
                    <td>{{$change['product']}}</td>
                    <td>{{$change['rate_name']}}</td>
                    <td>{{$change['date_purchase']}}</td>
                    <td>${{number_format($change['amount'],2)}}</td>
                    <td>N/A</td>
                </tr>
            @endforeach
            @foreach($paymentsCompleted as $paymentCompleted)
                <tr class="danger" style="cursor: pointer;" >
                    <td>{{$paymentCompleted['client_name'].' '.$paymentCompleted['client_lastname']}}</td>
                    <td>{{$paymentCompleted['company']}}</td>
                    <td>{{$paymentCompleted['who_did_it']}}</td>
                    <td>{{$paymentCompleted['DN']}}</td>
                    <td>MENSUALIDAD</td>
                    <td>{{$paymentCompleted['number_product']}}</td>
                    <td>{{$paymentCompleted['rate_name']}}</td>
                    <td>{{$paymentCompleted['date_pay']}}</td>
                    <td>${{number_format($paymentCompleted['amount_received'],2)}}</td>
                    <td>${{number_format($paymentCompleted['extra'],2)}}</td>
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
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    } );
</script>
@endsection