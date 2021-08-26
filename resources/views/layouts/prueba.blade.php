<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Librería DOMPDF</title>
</head>
<style>
        table{
          table-layout: fixed;
            width:100%;
        }
        td{
            border:2px solid black;
        }
        .subtitle {
          border: 1px solid black;
          background-color: #343a40 !important;
          color: white;
          font-size: 12.5px;
        }
        .field {
          border:1px solid black;
          font-size: 11px;
        }

        .field-empty {
          border:0px;
          font-size: 11px;
        }
    </style>
<body>


<table >
      <!-- Nombres y Apellidos -->
     <tr>
       <td class="subtitle" colspan="12"><center>SUSCRIPTOR</center></td>
     </tr>
     <tr>
       <td class="field" colspan="4">{{$name}}</td>
       <td class="field" colspan="4">{{$lastnameP}}</td>
       <td class="field" colspan="4">{{$lastnameM}}</td>
     </tr>
     <tr>
       <td class="subtitle" colspan="4"><center>Nombre</center></td>
       <td class="subtitle" colspan="4"><center>Apellido Paterno</center></td>
       <td class="subtitle" colspan="4"><center>Apellido Materno</center></td>
     </tr>

     <!-- Información Domiciliaria -->
     <tr>
       <td class="subtitle" colspan="12"><center>DOMICILIO</center></td>
     </tr>
     <tr>
       <td class="field" colspan="3">{{$street}}</td>
       <td class="field" colspan="1">{{$exterior}}</td>
       <td class="field" colspan="1">{{$interior}}</td>
       <td class="field" colspan="2">{{$colonia}}</td>
       <td class="field" colspan="2">{{$municipal}}</td>
       <td class="field" colspan="2">{{$state}}</td>
       <td class="field" colspan="1">{{$postal_code}}</td>
     </tr>
     <tr>
       <td class="subtitle" colspan="3"><center>Calle</center></td>
       <td class="subtitle" colspan="1"><center>#Ext</center></td>
       <td class="subtitle" colspan="1"><center>#Int</center></td>
       <td class="subtitle" colspan="2"><center>Colonia</center></td>
       <td class="subtitle" colspan="2"><center>Alcaldía/Municipio</center></td>
       <td class="subtitle" colspan="2"><center>Estado</center></td>
       <td class="subtitle" colspan="1"><center>C.P.</center></td>
     </tr>
     
     <tr>
       <td class="subtitle" colspan="4">TELÉFONO  Fijo <input type="radio" {{ $checked = $typePhone == 'fijo' ? 'checked' : '' }} > Móvil <input type="radio" {{ $checked = $typePhone == 'movil' ? 'checked' : '' }} ></td>
       <td class="field" colspan="3">{{$cellphone}}</td>
       <td class="subtitle" colspan="1"><center>RFC</center></td>
       <td class="field" colspan="2">{{$rfc}}</td>
       <td class="field" colspan="2"></td>
     </tr>

     <!-- Descripción del Servicio -->
     <tr>
       <td class="subtitle" colspan="12"><center>SERVICIO DE TELEFONÍA MÓVIL</center></td>
     </tr>
     <tr>
       <td class="subtitle" rowspan="2" colspan="4"><center>DESCRIPCIÓN PAQUETE/OFERTA (INCISO I) Nom numeral 5.1.2.1</center></td>
       <td class="subtitle" rowspan="2" colspan="4"><center>TARIFA</center><br>FOLIO IFT: </td>
       <td class="subtitle" rowspan="2" colspan="2"><center>FECHA DE PAGO</center><br>Modalidad Pos pago</td>
       <td class="field" rowspan="2" colspan="2"></td>
     </tr>
   </table>
  <table>
    <tr>
        <td class="field" rowspan="3" colspan="4">Plan 50 GB MIFI 1ER MES GRATIS.
          Autorenvable
        </td>
        <td class="subtitle" colspan="3">Total Mensualidad</td>
        <td class="field" colspan="2">${{$ratePrice}} M.N</td>
        <td class="subtitle" colspan="3">VIGENCIA Y PENALIDAD</td>
    </tr>
    <tr>
        <td class="subtitle" rowspan="2" colspan="3">Aplica tarifa por reconexión: Sí <input type="radio" checked> No <input type="radio" checked></td>
        <td class="field" rowspan="2" colspan="2">$100.00 M.N</td>
        <td class="subtitle" rowspan="2" colspan="3"><input type="radio" checked> Indefinido <ul><li>Sin Penalidad.</li></ul> <input type="radio" checked> Plazo máximo de 3 meses: <ul><li>Pagando el costo remanente del equipo sin penalidad en el servicio.</li></ul> </td>
    </tr>
  </table>
  <table>
    <tr>
       <td class="subtitle" colspan="12"><center>En el Estado de cuenta y/o factura se podrá visualizar la fecha de corte del servicio y fecha de pago.</center></td>
     </tr>
  </table>
  <!-- Datos del Dispositivo -->
  <table style="margin-top: 0.5rem;">
    <tr>
      <td class="subtitle" colspan="12"><center>DATOS DEL EQUIPO DE TELEFONÍA MÓVIL entregado en: COMPRA-VENTA</center></td>
    </tr>
    <tr>
      <td class="field" colspan="6"><b>Marca: </b>{{$marca}}</td>
      <td class="field" colspan="6"><b>Número de Equipos: </b>{{$deviceQuantity}}</td>
    </tr>
    <tr>
      <td class="field" colspan="6"><b>Modelo: </b>{{$modelo}}</td>
      <td class="field" rowspan="2" colspan="6"><b>Cantidad a pagar por equipo: ${{$devicePrice}} </b></td>
    </tr>
    <tr>
      <td class="field" colspan="6"><b>Número de Serie: </b>{{$serialNumber}}</td>
    </tr>
    <tr>
      <td class="field" colspan="6"><b>Número SIM: </b>{{$msisdn}}</td>
      <td class="field" colspan="6"><b>Número ICC: </b>{{$icc}}</td>
    </tr>
  </table>

  <table style="margin-top: 0.5rem;">
    <tr>
      <td class="subtitle" colspan="12"><center>MÉTODO DE PAGO</center></td>
    </tr>
    <tr>
      <td class="subtitle" rowspan="3" colspan="4">
        <input type="radio" {{ $checked = $typePayment == 'si' ? 'checked' : '' }}> Domiciliado con Tarjeta
      </td>
      <td class="subtitle" rowspan="1" colspan="10">Datos para el método de pago elegido.</td>
    </tr>
    <tr>
      <td class="field" rowspan="2" colspan="10">
        <b>Banco: </b>{{ $banco = $typePayment == 'si' ? $bank : 'N/A' }}<br>
        <b>No. Tarjeta: </b>{{ $no_tarjeta = $typePayment == 'si' ? $cardNumber : 'N/A' }}<br>
        <b>CVV: </b>{{ $cvvDesc = $typePayment == 'si' ? $cvv : 'N/A' }}<br>
        <b>Fecha de Expiración (MM/YY): </b>{{ $expirationDate = $typePayment == 'si' ? $monthExpiration.'/'.$yearExpiration : 'N/A' }}<br>
    </td>
    </tr>
  </table>

  <table>
    <tr>
      <td class="subtitle" colspan="12"><center>AUTORIZACIÓN PARA CARGO DE TARJETA DE CRÉDITO O DÉBITO</center></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="12">
        Por medio de la  presente SÍ <input type="radio" {{ $checked = $typePayment == 'si' ? 'checked' : '' }} >  NO <input type="radio" {{ $checked = $typePayment == 'no' ? 'checked' : '' }} >  autorizo a "EL PROVEEDOR", para que cargue a mi tarjeta de crédito o débito, la cantidad por concepto de servicios que mensualmente me presta.
      </td>
    </tr>
    <tr>
      <td class="field" colspan="12">
        <center><img src="{{asset('storage/uploads/'.$signature)}}" style="width: 20%;"><br>FIRMA</center>
      </td>
    </tr>
    <tr>
      <td class="subtitle" colspan="1">Banco: </td>
      <td class="field" colspan="4">{{ $banco = $typePayment == 'si' ? $bank : 'N/A' }}</td>
      <td class="subtitle" colspan="2">Número de Tarjeta: </td>
      <td class="field" colspan="5">{{ $no_tarjeta = $typePayment == 'si' ? $cardNumber : 'N/A' }}</td>
    </tr>
    <tr>
      <td class="subtitle" colspan="12"><center>SERVICIOS ADICIONALES</center></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="1">1.-</td>
      <td class="field" colspan="5"></td>
      <td class="subtitle" colspan="1">2.-</td>
      <td class="field" colspan="5"></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="4">DESCRIPCIÓN</td>
      <td class="subtitle" colspan="1">COSTO:</td>
      <td class="field" colspan="1"></td>
      <td class="subtitle" colspan="4">DESCRIPCIÓN</td>
      <td class="subtitle" colspan="1">COSTO:</td>
      <td class="field" colspan="1"></td>
    </tr>
    <tr>
      <td class="field" colspan="6">&nbsp;<br>&nbsp;</td>
      <td class="field" colspan="6">&nbsp;<br>&nbsp;</td>
    </tr>
    <tr>
      <td class="field" colspan="12"></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="12"><center>CONCEPTOS FACTURABLES</center></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="1">1.-</td>
      <td class="field" colspan="5"></td>
      <td class="subtitle" colspan="1">2.-</td>
      <td class="field" colspan="5"></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="4">DESCRIPCIÓN</td>
      <td class="subtitle" colspan="1">COSTO:</td>
      <td class="field" colspan="1"></td>
      <td class="subtitle" colspan="4">DESCRIPCIÓN</td>
      <td class="subtitle" colspan="1">COSTO:</td>
      <td class="field" colspan="1"></td>
    </tr>
    <tr>
      <td class="field" colspan="6">&nbsp;<br>&nbsp;</td>
      <td class="field" colspan="6">&nbsp;<br>&nbsp;</td>
    </tr>
    <tr>
      <td class="field" colspan="12"></td>
    </tr>

    <tr>
      <td class="subtitle" colspan="12"><center>EL SUSCRIPTOR AUTORIZA SE LE ENVÍE POR CORREO ELECTRÓNICO:</center></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="1">Factura</td>
      <td class="field" colspan="2">Sí <input type="radio" {{ $checked = $invoiceBool == 'si' ? 'checked' : '' }} > No <input type="radio" {{ $checked = $invoiceBool == 'no' ? 'checked' : '' }} ></td>
      <td class="subtitle" colspan="3">Carta de Derechos Mínimos</td>
      <td class="field" colspan="2">Sí <input type="radio" {{ $checked = $rightsMinBool == 'si' ? 'checked' : '' }} > No <input type="radio" {{ $checked = $rightsMinBool == 'no' ? 'checked' : '' }} ></td>
      <td class="subtitle" colspan="3">Contrato de Adhesión</td>
      <td class="field" colspan="1">Sí <input type="radio" {{ $checked = $contractAdhesionBool == 'si' ? 'checked' : '' }} > No <input type="radio" {{ $checked = $contractAdhesionBool == 'no' ? 'checked' : '' }} ></td>
    </tr>

    <tr>
      <td class="subtitle" colspan="3">Correo Electrónico Autorizado</td>
      <td class="field" colspan="3">{{$email}}</td>
      <td class="subtitle" colspan="2">Firma Suscriptor</td>
      <td class="field" colspan="4"><center><img src="{{asset('storage/uploads/'.$signature)}}" style="width: 35%;"></center></td>
    </tr>

    <tr>
      <td class="subtitle" colspan="12"><center>AUTORIZACIÓN PARA USO DE INFORMACIÓN DEL SUSCRIPTOR</center></td>
    </tr>
    <tr>
      <td class="field" colspan="12">
        1.	El Suscriptor SI<input type="radio" {{ $checked = $useInfoFirst == 'si' ? 'checked' : '' }} > NO<input type="radio" {{ $checked = $useInfoFirst == 'no' ? 'checked' : '' }} > autoriza que su información sea cedida o transmitida por el proveedor a terceros con fines mercadotécnicos o publicitarios. <b>FIRMA</b> <img src="{{asset('storage/uploads/'.$signature)}}" style="width: 15%;"><br>
        2.	El suscriptor acepta SI<input type="radio" {{ $checked = $useInfoSecond == 'si' ? 'checked' : '' }} > NO<input type="radio" {{ $checked = $useInfoSecond == 'no' ? 'checked' : '' }} > recibir llamadas del proveedor de promociones de servicios o paquetes. <b>FIRMA</b> <img src="{{asset('storage/uploads/'.$signature)}}" style="width: 15%;">
      </td>
    </tr>

    <tr>
      <td class="subtitle" colspan="12"><center>MEDIOS DE CONTACTO DEL PROVEEDOR PARA QUEJAS, ACLARACIONES, CONSULTAS Y CANCELACIONES</center></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="1">Teléfono:</td>
      <td class="field" colspan="5">8002258235</td>
      <td class="field" colspan="6">Disponible las 24 horas del día los 7 días de la semana</td>
    </tr>
    <tr>
      <td class="subtitle" colspan="2">Correo Electrónico:</td>
      <td class="field" colspan="4">soporte@altcel2.com</td>
      <td class="field" colspan="6">Disponible las 24 horas del día los 7 días de la semana</td>
    </tr>
    <tr>
      <td class="subtitle" colspan="4">Centros de Atención a Clientes:</td>
      <td class="field" colspan="8">Consultar horarios disponibles, días disponibles y centros de atención a clientes disponibles en la página de internet www.altcel2.com</td>
    </tr>
    <tr>
      <td class="subtitle" colspan="12"><center>LA PRESENTE CARÁTULA Y EL CONTRATO DE ADHESIÓN SE ENCUENTRAN DISPONIBLES EN:</center></td>
    </tr>
    <tr>
      <td class="subtitle" colspan="3">1. La página del proveedor</td>
      <td class="field" colspan="9">www.altcel2.com</td>
    </tr>
    <tr>
      <td class="subtitle" colspan="4">2. Buró comercial de PROFECO</td>
      <td class="field" colspan="8">https://burocomercial.profeco.gob.mx</td>
    </tr>
    <tr>
      <td class="subtitle" colspan="6">3. Físicamente en los centros de atención del proveedor</td>
      <td class="field" colspan="6">Consultar centros de atención a clientes en www.altcel2.com</td>
    </tr>
    <tr>
      <td class="field-empty" colspan="12">&nbsp;</td>
    </tr>
    <tr>
      <td class="field-empty" colspan="12">
        <b>LA PRESENTE CARÁTULA SE RIGE CONFORME A LAS CLÁUSULAS DEL CONTRATO DE ADHESIÓN REGISTRADO EN PROFECO EL __/__/____, CON NÚMERO: ______ DISPONIBLE EN EL SIGUIENTE CÓDIGO:</b>
      </td>
    </tr>
    <tr>
      <td class="field-empty" colspan="12">&nbsp;</td>
    </tr>
    <tr>
      <td class="field-empty" colspan="12">
        <b>LAS FIRMAS INSERTADAS ABAJO SON LA ACEPTACIÓN DE LA PRESENTE CARÁTULA Y CLAUSULADO DEL CONTRATO CON NÚMERO __________</b>
      </td>
    </tr>

    <tr>
      <td class="field-empty" colspan="12">Este contrato se firmó por duplicado en la Ciudad de Tuxtla Gutiérrez, Chiapas, a 04 de Agosto de 2021.</td>
    </tr>
    <tr>
      <td class="field-empty" colspan="6"><center>&nbsp;<br><b>PROVEEDOR</b></center></td>
      <td class="field-empty" colspan="6"><center><img src="{{asset('storage/uploads/'.$signature)}}" style="width: 35%;"><br><b>SUSCRIPTOR</b></center></td>
    </tr>
  </table>
</body>
</html>