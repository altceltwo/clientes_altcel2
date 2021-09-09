@extends('layouts.octopus')
@section('content')
<header class="page-header">
    <h2>Mis Referencias</h2>
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
      <h2 class="panel-title">Mis Referencias</h2>
  </header>
  <div class="panel-body">
      <table class="table table-bordered table-striped mb-none" id="datatable-default">
          <thead>
              <tr>
              <th scope="col">Servicio</th>
              <th scope="col">Número</th>
              <th scope="col">Plan</th>
              <th scope="col">Status</th>
              <th scope="col">Fecha de Pago</th>
              <th scope="col">Monto</th>
              <th scope="col">Acción</th>
              </tr>
          </thead>
          <tbody>
          @foreach( $references as $reference )
          <tr style="cursor: pointer;">
              <td>{{ $reference ->number_product }}</td>
              <td>{{ $reference ->number_dn }}</td>
              <td>{{ $reference ->rate_name }}</td>
              <td>{{ $reference ->status }}</td>
              <td>{{ $reference ->creation_date }}</td>
              <td>{{ '$'.number_format($reference ->amount,2) }}</td>
              @php
                  $ref = $reference->reference_id == null ? 'N/A' : $reference->reference_id
              @endphp
              <td><button type="button" onclick="ref(this.id)" class="btn btn-warning btn-sm ref-generated" id="{{ $ref }}"><i class="fa fa-eye"></i></button>
              </td>
          </tr>
          @endforeach
          </tbody>
      </table>
  </div>
</section>

<div class="modal fade" id="reference" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleRef"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe class="col-md-12" id="reference-pdf" style="height: 400px;" src=""></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de referencia OxxoPay -->
<div class="modal fade" id="referenceOxxo" tabindex="-1" aria-labelledby="referenceOxxo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="referenceOxxoLabel">Referencia OXXOPay</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="opps">
                <div class="opps-header">
                    <div class="opps-reminder">Ficha digital, puedes capturar la pantalla. No es necesario imprimir.</div>
                        <div class="opps-info">
                            <div class="opps-brand"><img src="{{asset('storage/uploads/oxxopay_brand.png')}}" alt="OXXOPay"></div>
                            <div class="opps-ammount">
                                <h3 class="title-3">Monto a pagar</h3>
                                <!-- <h2>$ 0,000.00 <sup>MXN</sup></h2> -->
                                <h2 id="montoOxxo"></h2>
                                <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3 class="title-3">Referencia</h3>
                            <h1 class="referenceOxxoCard" id="referenceOxxoCard"></h1>
                        </div>
                    </div>
                </div>	
            <div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
        </div>
    </div>
</div>


<script>
    
    function ref(reference_id){
        // let link = 'https://sandbox-dashboard.openpay.mx/paynet-pdf/mvtmmoafnxul8oizkhju/';
        let link = 'https://dashboard.openpay.mx/paynet-pdf/m3one5bybxspoqsygqhz/';
       
        $.ajax({
                    url:"{{url('/show-reference')}}",
                    method: "GET",
                    data: {
                        reference_id: reference_id
                    },
                    success: function(data){
                        x=data;
                        // console.log(x)
                        if(data.channel_id == 1){
                            $('#modalTitleRef').html('Referencia '+data.reference)
                            $('#reference-pdf').attr('src', link+data.reference);
                            $('#reference').modal('show');
                        }else if(data.channel_id == 2){
                            $('#montoOxxo').html('$'+data.amount+'<sup>MXN</sup>');
                            $('#referenceOxxoCard').html(data.reference);
                            
                            $('#referenceOxxo').modal('show');
                            // console.log('referencia OxxoPay');
                        }else if(data.channel_id == 3){
                            location.href = data.url_card_payment;
                            // console.log(data.url_card_payment)
                        }
                        // console.log(data.reference);
                    }
                }); 
    }

    function redirectTo(id){
        var url = "{{ route('clients.show', ['client' => 'temp']) }}";
        url = url.replace('temp', id);
        location.href = url;
    }
</script>
@endsection