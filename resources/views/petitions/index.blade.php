@extends('layouts.octopustwo')
@section('content')
<header class="page-header">
    <h2>Mis Solicitudes Pendientes</h2>
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

        <h2 class="panel-title">Solicitudes Pendientes</h2>
    </header>
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped mb-none" id="myTable">
            <thead >
                <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Producto</th>
                <th scope="col">Plan Activación</th>
                <th scope="col">Plan Subsecuente</th>
                <th scope="col">Status</th>
                <th scope="col">Fecha Solicitud</th>
                <th scope="col">Solicitado Por</th>
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
                <td>{{ $petition['rate_secondary'] }}</td>
                <td><span class="badge label-{{$petition['colorStatus']}}">{{ $petition['status'] }}</span></td>
                <td>{{ $petition['date_sent'] }}</td>
                <td>{{ $petition['who_sent'] }}</td>
                <td><span class="badge label-{{$petition['colorActivated']}}">{{ $petition['who_activated'] }}</span></td>
                <td>{{ $petition['date_activated'] }}</td>
                <td>{{ $petition['comment'] }}</td>
                <td>
                    @if($petition['sender'] == Auth::user()->id)
                    <button type="button" class="btn btn-danger btn-xs cancel-petition" data-petition-id="{{ $petition['id'] }}">Cancelar</button>
                    @endif
                </td>
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
    });
</script>
<script>
    $('.cancel-petition').click(function(){
        let petition = $(this).data('petition-id');
        let url = "{{route('petition.destroy',['petition'=>'temp'])}}";
        url = url.replace('temp',petition);

        Swal.fire({
            title: 'ATENCIÓN',
            html: "¿Está seguro de cancelar la petición?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'SÍ, ESTOY SEGURO',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary mr-md',
                cancelButton: 'btn btn-danger '
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {_token:'{{ csrf_token() }}',_method:'delete'},
                    beforeSend: function(){
                        Swal.fire({
                            title: 'Estamos trabajando en ello.',
                            html: 'Espera un poco, un poquito más...',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response){
                        if(response == 1){
                            Swal.fire({
                                icon: 'success',
                                title: '¡Hecho!',
                                text: 'Petición cancelada.',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            setTimeout(function(){ location.reload(); }, 2500);
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Woops!!',
                                text: 'No se pudo cancelar la petición, intente de nuevo o contacte a Desarrollo.'
                            });
                        }
                    }
                });
                
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Operación cancelada',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        })
    });
</script>
@endsection