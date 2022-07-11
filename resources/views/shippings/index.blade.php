@extends('layouts.octopustwo')
@section('content')
<header class="page-header">
    <h2>Envíos Pendientes</h2>
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

<section class="panel"  id="tblPending">
    <header class="panel-heading">
        <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
        </div>

        <h2 class="panel-title">Pendientes</h2>
    </header>
    <div class="panel-body" >
        <table class="table table-bordered table-striped mb-none" id="myTable">
            <thead style="cursor: pointer;">
                <tr>
                    <th>CP</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Canal</th>
                    <th>Creado Por</th>
                    <th>Vendido Por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendings as $pending)
                <tr>
                    <td>{{ $pending['cp']}}</td>
                    <td>{{ $pending['estado']}}</td>
                    <td>{{ $pending['municipio']}}</td>
                    <td>{{ $pending['to']}}</td>
                    <td>{{ $pending['phone_contact']}}</td>
                    <td>{{ $pending['canal']}}</td>
                    <td>{{ $pending['created_by']}}</td>
                    <td>{{ $pending['sold_by']}}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-shipping" data-idshipping="{{$pending['id']}}"><i class="fa fa-trash"></i> Cancelar</button>
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

    $('.delete-shipping').click(function(){
        let url = "{{route('shipping.destroy',['shipping'=>'temp'])}}";
        let shipping_id = $(this).data('idshipping');
        url = url.replace('temp',shipping_id);

        Swal.fire({
            title: 'ATENCIÓN',
            html: "¿Está seguro de eliminar el envío?",
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
                    url,
                    method: "DELETE",
                    data: {_token: '{{csrf_token()}}', _method: 'DELETE'},
                    success: function(response){
                        if(response == 1){
                            Swal.fire({
                                icon: 'success',
                                title: 'Well done!!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            setTimeout(function(){ location.reload(); }, 700);
                        }else if(response == 2){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Woops!!',
                                text: 'El envío ya fue tomado, contacte a Logística LO MÁS PRONTO POSIBLE. RECARGUE LA PÁGINA PARA ACTUALIZAR.'
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Woops!!',
                                text: 'No se pudo eliminar el envío, intente de nuevo o contacte a Desarrollo.',
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
                })
            }
        })
    });
</script>
@endsection