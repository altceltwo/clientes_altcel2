@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Preactivaciones</h2>
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

@if(Auth::user()->role_id == 7 || Auth::user()->role_id == 4)
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>

            <h2 class="panel-title">Mis Preactivaciones Pendientes</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead >
                    <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">MSISDN</th>
                    <th scope="col">Plan</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($preactivations as $preactivation)
                    <tr style="cursor: pointer;" id="{{$preactivation->id}}">
                        <td>{{$preactivation->name.' '.$preactivation->lastname}}</td>
                        <td>{{$preactivation->MSISDN}}</td>
                        <td>{{$preactivation->rate_name}}</td>
                        <td>{{$preactivation->date_activations}}</td>
                        <td>{{$preactivation->producto}}</td>
                        <td><button class="btn btn-danger cancel" data-preactivation="{{$preactivation->id}}">Cancelar</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif
<script>
    var token = '';
    $('.cancel').click(function(){
        let activation_id = $(this).attr('data-preactivation');
        let email = '{{Auth::user()->email}}';
        let tokenCSRF = $('meta[name="csrf-token"]').attr('content');
       
        (async () => {
            const { value: pass } = await Swal.fire({
                title: 'Hola, {{Auth::user()->email}}, ingresa tu contraseña:',
                input: 'password',
                inputLabel: 'Tu contraseña',
                inputPlaceholder: 'Password'
            })

            if (pass) {
                $.ajax({
                    url: "{{url('createJWT')}}",
                    method: "POST",
                    data: {email:"{{Auth::user()->email}}",password:pass},
                    success:function(response){
                        if(response.error){
                            token = null;
                            Swal.fire({
                                icon: 'error',
                                title: 'Permiso denegado, ocurrió lo siguiente:',
                                text: response.error
                            });
                        }else{
                            token = response.token;
                            csrf = response.tokenCSRF;
                            $.ajax({
                                url:"{{url('cancelActivation')}}",
                                method:'POST',
                                data: {token:token, _token:tokenCSRF, id:activation_id},
                                success:function(response){
                                    if (response == 1) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Cancelación exitosa',
                                            text: 'La cancelación se ha realizado con éxito',
                                            timer: 1500
                                        });
                                        setTimeout(function(){
                                            location.reload();
                                        },1600);                                        
                                    }else{
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Ha ocurrido un error',
                                            text: 'Consulte el area de desarrollo'
                                        });
                                    }
                                }

                            })
                            
                        }
                    }
                });
            }
        })()
})
</script>
@endsection