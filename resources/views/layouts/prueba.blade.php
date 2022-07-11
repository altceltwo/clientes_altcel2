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

        .right {
          text-align: right !important;
        }

        .justify {
          text-align: justify !important;
        }

        .sangria {
          margin-left: 1.5rem !important;
        }

        .title {
          border: none;
          font-size: 12px;
          font-family: sans-serif !important;
        }

        .bottom {
          margin-bottom: 0.5rem !important;
        }

        .clausule {
          margin-top: 1.9rem !important;
          margin-right: 2rem !important;
        }

        .clausule-two {
          margin-top: 1rem !important;
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
       <td class="field" colspan="1">{{$interior = $interior == 'null' ? '' : $interior}}</td>
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
       <td class="subtitle" rowspan="2" colspan="4"><center>TARIFA</center><br>FOLIO IFT: {{$folioIFT}}</td>
       <td class="subtitle" rowspan="2" colspan="2"><center>FECHA DE PAGO</center><br>Modalidad Pos pago</td>
       <td class="field" rowspan="2" colspan="2">{{$date_activation}}</td>
     </tr>
   </table>
  <table>
    <tr>
        <td class="field" rowspan="3" colspan="3">{{$description}}
        </td>
        <td class="subtitle" colspan="4">Total Mensualidad</td>
        <td class="field" colspan="2">${{number_format($priceMonthly,2)}} M.N</td>
        <td class="subtitle" colspan="3">VIGENCIA Y PENALIDAD</td>
    </tr>
    <tr>
        <td class="subtitle" rowspan="2" colspan="4">Aplica tarifa por reconexión: Sí <input type="radio"> No <input type="radio" checked></td>
        <td class="field" rowspan="2" colspan="2">0.00 M.N</td>
        <td class="subtitle" rowspan="2" colspan="3"><input type="radio" checked> Indefinido <ul><li>Sin Penalidad.</li></ul> </td>
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
  <table>
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
      <td class="field" colspan="4">
        <center>
          
          @if($signature == null)
            <br>
          @else
            <img src="{{asset('storage/uploads/'.$signature)}}" style="width: 35%;">
          @endif
        </center>
      </td>
    </tr>

    <tr>
      <td class="subtitle" colspan="12"><center>AUTORIZACIÓN PARA USO DE INFORMACIÓN DEL SUSCRIPTOR</center></td>
    </tr>
    <tr>
      <td class="field" colspan="12">
        1.	El Suscriptor SI<input type="radio" {{ $checked = $useInfoFirst == 'si' ? 'checked' : '' }} > NO<input type="radio" {{ $checked = $useInfoFirst == 'no' ? 'checked' : '' }} > autoriza que su información sea cedida o transmitida por el proveedor a terceros con fines mercadotécnicos o publicitarios. <b>FIRMA</b> 
          @if($signature == null)
            <br>
          @else
            <img src="{{asset('storage/uploads/'.$signature)}}" style="width: 35%;">
          @endif
        <br>
        2.	El suscriptor acepta SI<input type="radio" {{ $checked = $useInfoSecond == 'si' ? 'checked' : '' }} > NO<input type="radio" {{ $checked = $useInfoSecond == 'no' ? 'checked' : '' }} > recibir llamadas del proveedor de promociones de servicios o paquetes. <b>FIRMA</b> 
          @if($signature == null)
            <br>
          @else
            <img src="{{asset('storage/uploads/'.$signature)}}" style="width: 35%;">
          @endif
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
      <td class="field-empty" colspan="6"><center>
        @if($signature == null)
            
          @else
            <img src="{{asset('storage/uploads/'.$signature)}}" style="width: 35%;">
          @endif
        <br>
        <b>SUSCRIPTOR</b>
      </center>
    </td>
    </tr>
  </table>
  <div style="page-break-after:always;"></div>
  <table >
    <tr>
      <td class="field" colspan="3"><center><img src="{{asset('storage/uploads/altcel_contract.png')}}" style="width: 100%;"></center></td>
      <td class="field right" colspan="9">
        Altcel II<br>
        ALTCEL II S.A.P.I. DE C.V.<br>
        AII180403S53<br>
        Circuito de la 10 Poniente Norte No. 1050 Interior 1 A. Col. Vista Hermosa<br>
        CP: 29030. Tuxtla Gutiérrez, Chiapas.
      </td>
    </tr>
    <tr>
      <td class="title bottom" colspan="12"><b>
      CONTRATO DE PRESTACIÓN DE SERVICIO DE INTERNET FIJO EN CASA QUE CELEBRA POR UNA PARTE EL PROVEEDOR Y POR OTRA PARTE EL SUSCRIPTOR, AL TENOR DE LO SIGUIENTE.
      </b></td>
    </tr>
    <tr>
      <td class="title bottom" colspan="12">
        <center><b>DECLARACIONES</b></center>
      </td>
    </tr>
    <tr>
      <td class="title justify bottom" colspan="12">
        <b>1.  </b> Ambas partes declaran: <br><br>
        <div class="sangria">
          <b>a) </b>Que los datos consistentes en el domicilio, RFC y datos de localización del domicilio son ciertos y se encuentran establecidos en la carátula del presente contrato. <br>
          <b>b) </b>Que tienen pleno goce de sus derechos y capacidad legal para contratar y obligarse en términos del presente contrato. <br>
          <b>c) </b>Que aceptan que el presente contrato se regirá por la Ley Federal de Protección al Consumidor, Ley Federal de Telecomunicaciones y Radiodifusión, la Norma Oficial Mexicana NOM-184-SCFI-2018, Elementos Normativos y Obligaciones Específicas que deben Observar los Proveedores para la Comercialización y/o Prestación de los Servicios de Telecomunicaciones cuando Utilicen una Red Pública de Telecomunicaciones, y demás normatividad aplicable, por lo que los derechos y obligaciones establecidas en dicho marco normativo se tendrán por aquí reproducidas como si a la letra se insertase. <br>
          <b>d) </b>Que la manifestación de la voluntad para adherirse al presente contrato de adhesión y su  carátula (la cual forma parte integrante del referido contrato) son las firmas que plasmen  las partes  en la carátula.  <br>
          <b>e) </b>Que es su voluntad celebrar el presente contrato sujetándose a las siguientes: <br>
        </div>
      </td>
    </tr>
    <tr>
      <td class="title" colspan="12">
        <center><b>CLÁUSULAS</b></center>
      </td>
    </tr>
    <tr>
      <td class="title clausule" colspan="6">
        <p class="justify">
          <b>PRIMERA: OBJETO DEL CONTRATO.</b> El PROVEEDOR se obliga a prestar el servicio de Internet fijo en casa, (en adelante el Servicio), de manera continua, uniforme, regular y eficiente, a cambio del pago de la tarifa, plan o paquete que el SUSCRIPTOR haya seleccionado en la carátula del presente contrato, los cuales no podrán ser menores a los parámetros de calidad que establezca el Instituto Federal de Telecomunicaciones (en adelante IFT), ni menores a los ofrecidos implícitamente o contratados en el presente instrumento.
          <br><br>El presente contrato <b>se regirá bajo el esquema de mensualidades fijas POR ADELANTADO</b>, es decir se va a pagar el servicio de manera  previa a utilizarlo, dicho esquema va a operar bajo los términos y condiciones del pospago exceptuando el momento de pago del servicio. Cualquier cargo por el SERVICIO comienza a partir de la fecha en la que efectivamente el PROVEEDOR inicie la prestación del SERVICIO. 
          <br><br>El PROVEEDOR es el único responsable frente al SUSCRIPTOR por la prestación del SERVICIO, así como, de los bienes o servicios adicionales contratados.
          <br><br>Todo lo pactado o contratado entre el SUSCRIPTOR y el PROVEEDOR  de manera verbal o electrónica se le debe confirmar  por escrito al SUSCRIPTOR a través del medio que él elija, en un plazo máximo de cinco días hábiles, contados a partir del momento en que se realice el pacto o contratación.
          <br></br><b>SEGUNDA: VIGENCIA.</b> Este contrato <b>NO obliga a un plazo forzoso</b>, por lo que al tener una vigencia indeterminada el SUSCRIPTOR puede darlo por terminado en cualquier momento, <b>SIN penalidad alguna</b> y sin necesidad de recabar autorización del PROVEEDOR, únicamente se tendrá que dar aviso a este último a través del mismo medio en el cual contrató el servicio o por los medios de contacto señalados en la carátula.
        </p>
      </td>
      <td class="title clausule-two" colspan="6">
        <p class="justify">
          <b>TERCERA: EQUIPO TERMINAL.</b> Para que el Usuario pueda recibir el Servicio y hacer uso del mismo es necesario que cuente con un módem, así como con sus respectivos accesorios; los cuales deberán encontrarse debidamente homologados por el IFT. El Proveedor informará al Usuario, previo a la contratación del Servicio, cuáles son los requerimientos mínimos del Equipo.
          <br><br>El Usuario podrá adquirir el Equipo del Proveedor –en lo subsecuente, el “Equipo”–, el cual cuenta con la garantía del fabricante, la cual no puede ser menor a 90 días naturales contados a partir de la entrega del Equipo. En caso de que el Equipo falle fuera de la vigencia de la garantía, el Proveedor informará al Usuario, a través de medios físicos o electrónicos o digitales o de cualquier otra nueva tecnología que lo permita, el procedimiento que debe seguir para llevar a cabo la reparación de su Equipo. 
          <br><br>En caso de que el Equipo que sea adquirido directamente del Proveedor se encuentre sujeto a garantía, el Proveedor suspenderá el cobro del Servicio por el periodo que dure la revisión y reparación de dicho Equipo, salvo que al momento de hacer efectiva la garantía del Equipo, el Proveedor proporcione al Usuario un Equipo sustituto de similares características. 
          <br><br>La suspensión del cobro del Servicio no procederá en caso de que el Usuario haga uso del Servicio a través de otro equipo que tenga en su posesión. 
          <br><br>En caso de robo o extravío del Equipo o cualquier otra circunstancia que pudiera tener como consecuencia el uso del Servicio sin el consentimiento del Usuario, éste deberá solicitar la suspensión del Servicio a través de los medios de contacto establecidos en la carátula del Contrato.
          <br><br><b>CUARTA: INSTALACIÓN Y ACTIVACIÓN DEL SERVICIO.</b>-La entrega e instalación del equipo terminal no podrá ser mayor a 20 días hábiles a partir de la firma del presente contrato.
        </p>
      </td>
    </tr>
  </table>
  <div style="page-break-after:always;"></div>
  <table>
    <tr>
      <td class="field" colspan="3"><center><img src="{{asset('storage/uploads/altcel_contract.png')}}" style="width: 100%;"></center></td>
      <td class="field right" colspan="9">
        Altcel II<br>
        ALTCEL II S.A.P.I. DE C.V.<br>
        AII180403S53<br>
        Circuito de la 10 Poniente Norte No. 1050 Interior 1 A. Col. Vista Hermosa<br>
        CP: 29030. Tuxtla Gutiérrez, Chiapas.
      </td>
    </tr>
    <tr>
      <td class="title clausule" colspan="6">
        <p class="justify">
          En caso de que el PROVEEDOR no pueda iniciar la prestación del servicio por causas atribuibles a él por imposibilidad física o técnica para la instalación del equipo, debe devolver al SUSCRIPTOR las cantidades que haya pagado por concepto de anticipo, en un plazo no mayor de 30 días hábiles siguientes a la fecha límite establecida para la instalación, y se tendrá por terminado el contrato de adhesión sin responsabilidad para el SUSCRIPTOR debiendo pagar el PROVEEDOR, una penalidad equivalente al 20% de las cantidades que haya recibido por concepto de anticipo, por su incumplimiento en los casos atribuibles a él.
          <br><br>El SUSCRIPTOR puede negarse, sin responsabilidad alguna para él, a la instalación o activación del servicio ante la negativa del personal del PROVEEDOR a identificarse y/o a mostrar la orden de trabajo. Situación que debe informar al PROVEEDOR en ese momento.
          <br><br>El PROVEEDOR es el único responsable frente al SUSCRIPTOR por la prestación del SERVICIO, así como, de los bienes o servicios adicionales contratados.
          <br><br><b>QUINTA: TARIFAS.</b> Las tarifas del servicio se encuentran inscritas en el Registro Público de Concesiones del IFT y pueden ser consultadas en la página del IFT www.ift.org.mx.
          <br></br>Las tarifas no podrán establecer condiciones contractuales tales como causas de terminación anticipada o cualquier otra condición que deba ser pactada dentro de los contratos de adhesión. De igual manera, no se podrán establecer términos y/o condiciones de aplicación de las tarifas que contravengan a lo establecido en el presente contrato de adhesión. 
          <br><br>Los planes, paquetes, cobertura donde el PROVEEDOR puede prestar el servicio y tarifas se pueden consultar por los medios establecidos en la carátula del presente contrato.
          <br><br><b>SEXTA: SERVICIOS ADICIONALES.</b> El PROVEEDOR puede ofrecer servicios adicionales al SERVICIO originalmente contratado siempre y cuando sea acordado entre las partes y el SUSCRIPTOR lo solicite y autorice a través de medios físicos, electrónicos, digitales o de cualquier otra nueva tecnología que lo permita. El PROVEEDOR deberá contar con la opción de ofrecer al SUSCRIPTOR cada servicio adicional o producto por separado, debiendo dar a conocer el precio previamente a su contratación.
          <br><br>El PROVEEDOR puede ofrecer planes o paquetes que incluyan los servicios y/o productos que considere convenientes, siempre y cuando tenga el consentimiento expreso del SUSCRIPTOR para tal efecto. Sin embargo no puede obligar al SUSCRIPTOR a contratar servicios adicionales como requisito para la contratación o continuación de la prestación del SERVICIO.
          
        </p>
      </td>
      <td class="title" colspan="6" style="margin-top: 1rem !important;">
        <p class="justify">
          El SUSCRIPTOR puede cancelar los servicios adicionales al SERVICIO originalmente contratado en cualquier momento, por los medios señalados en la carátula para tales efectos, para lo que el PROVEEDOR tiene un plazo máximo de 5 días naturales a partir de dicha manifestación para cancelarlo, sin que ello implique la suspensión o cancelación de la prestación del SERVICIO originalmente contratado. La cancelación de los Servicios adicionales al SERVICIO originalmente contratado no exime al SUSCRIPTOR del pago de las cantidades adeudadas por los servicios adicionales utilizados.
          <br><br><b>SÉPTIMA: ESTADO DE CUENTA RECIBO Y/O FACTURA.</b> El PROVEEDOR debe entregar gratuitamente en el domicilio del SUSCRIPTOR, con al menos 10 días naturales antes de la fecha de vencimiento del plazo para el pago del SERVICIO contratado, un estado de cuenta, recibo y/o factura el cual deberá de contener de manera desglosada la descripción de los cargos, costos, conceptos y naturaleza del SERVICIO y de los servicios adicionales contratados.
          <br><br>El SUSCRIPTOR puede pactar con el PROVEEDOR para que, en sustitución de la obligación referida, pueda consultarse el citado estado de cuenta y/o factura, a través de cualquier medio físico, electrónico, digital o de cualquier otra nueva tecnología que lo permita y que al efecto se acuerde entre ambas partes.
          <br><br>La fecha, forma y lugares de pago se pueden consultar por los medios señalados en la carátula del presente contrato.
          <br><br>Tratándose de cargos indebidos, el PROVEEDOR deberá efectuar la devolución correspondiente dentro de un plazo no mayor a los 5 días hábiles posteriores a la reclamación. Dicha devolución se efectuará por el mismo medio en el que se realizó el cargo indebido correspondiente y se deberá bonificar el 20% sobre el monto del cargo realizado indebidamente.
          <br><br><b>OCTAVA: MODIFICACIONES.</b> El PROVEEDOR dará aviso al SUSCRIPTOR, cuando menos con 15 días naturales de anticipación, de cualquier cambio en los términos y condiciones originalmente contratados. Dicho aviso deberá ser notificado, a través de medios físicos, electrónicos, digitales o de cualquier otra nueva tecnología que lo permita.
          <br><br>En caso de que el SUSCRIPTOR no esté de acuerdo con el cambio de los términos y condiciones originalmente contratados, podrá optar por exigir el cumplimiento forzoso del contrato bajo las condiciones en que se firmó el mismo, o a solicitar la terminación del presente contrato sin penalidad alguna para el SUSCRIPTOR.
          <br><br>El PROVEEDOR deberá obtener el consentimiento del SUSCRIPTOR a través de medios físicos, electrónicos, digitales o de cualquier otra nueva tecnología que lo permita, para poder dar por terminado el presente contrato con la finalidad de sustituirlo por otro, o bien para la modificación de sus términos y condiciones. No se requerirá dicho consentimiento cuando la modificación genere un beneficio en favor del SUSCRIPTOR.
        </p>
      </td>
    </tr>
  </table>
  <div style="page-break-after:always;"></div>
  <table>
    <tr>
      <td class="field" colspan="3"><center><img src="{{asset('storage/uploads/altcel_contract.png')}}" style="width: 100%;"></center></td>
      <td class="field right" colspan="9">
        Altcel II<br>
        ALTCEL II S.A.P.I. DE C.V.<br>
        AII180403S53<br>
        Circuito de la 10 Poniente Norte No. 1050 Interior 1 A. Col. Vista Hermosa<br>
        CP: 29030. Tuxtla Gutiérrez, Chiapas.
      </td>
    </tr>
    <tr>
      <td class="title clausule" colspan="6">
        <p class="justify">
          El SUSCRIPTOR puede cambiar de tarifa, paquete o plan, aunque sea de menor monto con el que se contrató, en cualquier momento, pagando en su caso los cargos adicionales que se generen asociados a este cambio.
          <br><br><b>NOVENA: SUSPENSIÓN DEL SERVICIO.</b> El PROVEEDOR podrá suspender el Servicio, previa notificación por escrito al SUSCRIPTOR, si este último incurre en cualquiera de los siguientes supuestos:
          <br><br><b>1. </b>Por pagos parciales de la tarifa aplicable al SERVICIO.
          <br><b>2. </b>Por falta de pago del SERVICIO después de 5 días naturales posteriores a la fecha de pago señalada en la carátula del presente contrato.
          <br><b>3. </b>Por utilizar el servicio de manera contraria a lo previsto en el contrato y/o a las disposiciones aplicables en materia de telecomunicaciones.
          <br><b>4. </b>Por alterar, modificar o mover el equipo terminal.
          <br><b>5. </b>Por declaración judicial o administrativa.
          <br><br>Una vez solucionada la causa que originó la suspensión del servicio, el PROVEEDOR deberá reanudar la prestación del servicio en un periodo máximo de 48 horas, debiendo pagar el SUSCRIPTOR los gastos por reconexión, lo cual no podrá ser superior al 20% del pago de una mensualidad.
          <br><br><b>DÉCIMA: CONTINUIDAD DEL SERVICIO Y BONIFICACIONES POR INTERRUPCIÓN.</b> El proveedor deberá bonificar y compensar al suscriptor en los siguientes casos:
          <br><br><b>1. </b>Cuando <b>por causas atribuibles a el PROVEEDOR</b> no se preste el servicio de telecomunicaciones en la forma y términos convenidos, contratados, ofrecidos o implícitos o información desplegada en la publicidad del proveedor, así como con los índices y parámetros de calidad contratados o establecidos por el IFT, éste debe de compensar al consumidor la parte proporcional del precio del servicio, plan o paquete que se dejó de prestar y como bonificación al menos el 20% del monto del periodo de afectación de la prestación del servicio.
          <br><br><b>2. </b>Cuando la interrupción del servicio sea por casos fortuitos o de fuerza mayor, si la misma dura más de 24 horas consecutivas siguientes al reporte que realice el SUSCRIPTOR, el PROVEEDOR hará la compensación por la parte proporcional del periodo en que se dejó de prestar el servicio contratado, la cual se verá reflejada en el siguiente recibo y/o factura. Además, el PROVEEDOR deberá bonificar por lo menos el 20% del monto del periodo de afectación.
          <br><br><b>3. </b>Cuando se interrumpa el servicio por alguna causa previsible que repercuta de manera generalizada o significativa en la prestación del servicio, la misma no podrá afectar el servicio por más de 24 horas consecutivas; el PROVEEDOR dejará de cobrar al  SUSCRIPTOR la parte proporcional del precio del servicio que se dejó de prestar, y deberá bonificar por lo menos el
        </p>
      </td>
      <td class="title" colspan="6" style="margin-top: 1rem !important;">
        <p class="justify">
          20% del monto del periodo que se afectó.
          <br><br><b>4. </b>Cuando el PROVEEDOR realice cargos indebidos, deberá bonificar el 20% sobre el monto del cargo realizado indebidamente.
          <br><br>A partir de que el PROVEEDOR reciba la llamada por parte del SUSCRIPTOR para reportar las fallas y/o interrupciones en el SERVICIO, el PROVEEDOR procederá a verificar el tipo de falla y con base en ello, se determinará el tiempo necesario para la reparación, el cual no puede exceder las 24 horas siguientes a la recepción del reporte.
          <br><br><b>DÉCIMA PRIMERA. MECANISMOS DE BONIFICACIÓN Y COMPENSACIÓN.</b> En caso de que proceda la bonificación y/o compensación, el PROVEEDOR se obliga a: 
          <br><br><b>1. </b>Realizarlas a más tardar en la siguiente fecha de corte a partir de que se actualice algunos de los supuestos descritos en la cláusula anterior.
          <br><br><b>2. </b>Reflejar en el siguiente estado de cuenta o factura, la bonificación y/o compensación realizada.
          <br><br><b>3. </b>Dicha bonificación y/o compensación se efectuará por los medios que pacten las partes.
          <br><br><b>DÉCIMA SEGUNDA: TERMINACIÓN Y CANCELACIÓN DEL CONTRATO.</b> El Presente contrato se podrá cancelar por cualquiera de las partes sin responsabilidad para ellas en los siguientes casos:
          <br><br><b>a) </b>Por la imposibilidad permanente del PROVEEDOR para continuar con la prestación del SERVICIO, ya sea por caso fortuito o fuerza mayor.
          <br><br><b>b) </b>Si el SUSCRIPTOR no subsana en un término de 90 días naturales cualquiera de las causas que dieron origen a la suspensión del SERVICIO.
          <br><br><b>c) </b>Si el SUSCRIPTOR conecta aparatos adicionales por su propia cuenta, subarrienda, cede o en cualquier forma traspasa los derechos establecidos en el contrato, sin la autorización previa y por escrito del PROVEEDOR.
          <br><br><b>d) </b>Si el PROVEEDOR no presta el SERVICIO en la forma y términos convenidos, contratados, ofrecidos o implícitos en la información desplegada en la publicidad del proveedor, así como con los índices y parámetros de calidad contratados o establecidos por el IFT.
          <br><br><b>e) </b>Si el SUSCRIPTOR proporciona información falsa al PROVEEDOR para la contratación del Servicio.
          <br><br><b>f) </b>En caso de modificación unilateral de los términos, condiciones y tarifas establecidas en el presente contrato por parte del PROVEEDOR.
          <br><br><b>g) </b>Por cualquier otra causa prevista en la legislación aplicable y vigente.
        </p>
      </td>
    </tr>
  </table>
  <div style="page-break-after:always;"></div>
  <table>
    <tr>
      <td class="field" colspan="3"><center><img src="{{asset('storage/uploads/altcel_contract.png')}}" style="width: 100%;"></center></td>
      <td class="field right" colspan="9">
        Altcel II<br>
        ALTCEL II S.A.P.I. DE C.V.<br>
        AII180403S53<br>
        Circuito de la 10 Poniente Norte No. 1050 Interior 1 A. Col. Vista Hermosa<br>
        CP: 29030. Tuxtla Gutiérrez, Chiapas.
      </td>
    </tr>
    <tr>
      <td class="title clausule" colspan="6">
        <p class="justify">
          El SUSCRIPTOR podrá dar por terminado el contrato en cualquier momento, dando únicamente el aviso al proveedor a través del mismo medio en el cual contrató el servicio, o a través los medios físicos o electrónicos o digitales o de cualquier otra nueva tecnología que lo permita. La cancelación o terminación del Contrato no exime al SUCRIPTOR de pagar al PROVEEDOR los adeudos generados por el/los Servicio(s) efectivamente recibido(s).
          <br><br>El CONSESIONARIO realizará la devolución de las cantidades que en su caso el SUSCRIPTOR haya dado por adelantado y que correspondan a la parte proporcional del servicio que con motivo  de la cancelación no se haya prestado efectivamente por parte del PROVEEDOR.
          <br><br>En caso de terminación del presente contrato, el PROVEEDOR debe proporcionar un folio o número de registro al SUSCRIPTOR, mismo que puede ser entregado, a elección del SUSCRIPTOR, a través de medios físicos, electrónicos, digitales o de cualquier otra nueva tecnología que lo permita.
          <br><br><b>DÉCIMA TERCERA: USO DEL SERVICIO DE INTERNET FIJO EN CASA.</b> La utilización del SERVICIO puede integrar imágenes, sonidos, textos y/o contenidos que se pueden considerar ofensivos o no aptos para menores de edad, por lo que el acceso a los mismos corre por cuenta y riesgo del SUSCRIPTOR.
          <br><br>Es responsabilidad del SUSCRIPTOR llevar a cabo las medidas requeridas para cuidar y salvaguardar su información, datos y/o software de su propiedad, de accesos desde internet a sus dispositivos o, en su caso, evitar una contaminación por virus o ataques de usuarios de internet, por lo que el PROVEEDOR no será responsable de cualquier daño y perjuicio causado al SUSCRIPTOR por los hechos antes mencionados.
          <br><br>EL PROVEEDOR no es responsable de la configuración de dispositivos que resulten necesarios para el uso concurrente del o de los Equipos Personales.
          <br><br>El SERVICIO está sujeto a una cuota mensual de navegación de descarga que se determinará en el Paquete contratado por el SUSCRIPTOR, por lo que el PROVEEDOR proporcionará la información del consumo total de datos en el periodo de facturación correspondiente.
          <br><br>El PROVEEDOR cumplirá con la neutralidad de las redes que se encuentra establecida en Ley Federal de Telecomunicaciones y Radiodifusión y en los lineamientos que en su momento emita el Instituto Federal de Telecomunicaciones.
          <br><br><b>DÉCIMA CUARTA. ACCESIBILIDAD PARA PERSONAS CON DISCAPACIDAD.</b> En cuanto a la contratación para usuarios con discapacidad, el PROVEEDOR estará
        </p>
      </td>
      <td class="title" colspan="6" style="margin-top: 1.7rem !important;">
        <p class="justify">
          obligado a poner a disposición del SUSCRIPTOR la utilización de otros medios de comunicación para dar a conocer las condiciones establecidas en el presente contrato, los servicios adicionales y los paquetes que ofrezca  el PROVEEDOR.
          <br><br><b>DÉCIMA QUINTA: NO DISCRIMINACIÓN.</b> El PROVEEDOR debe prestar el SERVICIO en condiciones equitativas a todo aquel que lo solicite, sin establecer privilegios o distinciones en forma discriminatoria, respecto de otros SUSCRIPTORES en la misma área de cobertura y en las mismas condiciones de contratación.
          <br><br>En caso de que el PROVEEDOR ofrezca condiciones más favorables a uno o más suscriptores situados en supuestos equivalentes o similares, el SUSCRIPTOR puede exigir las mismas condiciones, siempre y cuando sea posible técnicamente para la prestación del Servicio.
          <br><br><b>DÉCIMA SEXTA: PROTECCIÓN DE DATOS PERSONALES.</b> El PROVEEDOR está obligado a proteger y tratar conforme a la normatividad aplicable, los datos personales que le sean proporcionados por el  SUSCRIPTOR.
          <br><br>El PROVEEDOR debe poner a disposición del SUSCRIPTOR el aviso de privacidad para que pueda ejercer alguno de sus derechos, de conformidad con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.
          <br><br>El PROVEEDOR para utilizar la información del SUSCRIPTOR con fines mercadotécnicos o publicitarios; así como para enviarle publicidad sobre bienes, productos o servicios, debe obtener el consentimiento expreso en la carátula del presente contrato.
          <br><br><b>DÉCIMA SÉPTIMA: CONSULTAS, DUDAS, ACLARACIONES Y QUEJAS.</b> EL SUSCRIPTOR podrá presentar sus quejas por fallas y/o deficiencias en el servicio y/o equipos; así como consultas, contrataciones, cancelaciones, sugerencias y reclamaciones a EL PROVEEDOR de manera gratuita por los medios señalados en la carátula.
          <br><br><b>DÉCIMA OCTAVA: AUTORIDAD COMPETENTE.</b> La PROFECO es la autoridad  competente en materia administrativa para resolver cualquier controversia que se suscite sobre la interpretación o cumplimiento del presente contrato de adhesión.
          <br><br>Al IFT le corresponde regular y vigilar la calidad de los Servicios de Telecomunicaciones, así como el cumplimiento de las disposiciones administrativas que emita y que sean referidas la Norma Oficial Mexicana NOM-184-SCFI-2018.
          <br><br><b>DÉCIMA NOVENA: PROCEDIMIENTO CONCILIATORIO.</b> Cuando se llegare a iniciar algún procedimiento conciliatorio ante la PROFECO, EL PROVEEDOR no podrá interrumpir los servicios. Si el servicio de telecomunicaciones se suspendió con posterioridad a la presentación de la reclamación y previo a la notificación al PROVEEDOR, la PROFECO deberá solicitar restablecer el SERVICIO. Si el servicio se suspende posterior a la notificación de la reclamación, la PROFECO requerirá al
        </p>
      </td>
    </tr>
  </table>
  <div style="page-break-after:always;"></div>
  <table>
  <tr>
      <td class="field" colspan="3"><center><img src="{{asset('storage/uploads/altcel_contract.png')}}" style="width: 100%;"></center></td>
      <td class="field right" colspan="9">
        Altcel II<br>
        ALTCEL II S.A.P.I. DE C.V.<br>
        AII180403S53<br>
        Circuito de la 10 Poniente Norte No. 1050 Interior 1 A. Col. Vista Hermosa<br>
        CP: 29030. Tuxtla Gutiérrez, Chiapas.
      </td>
    </tr>
    <tr>
      <td class="title clausule" colspan="6">
        <p class="justify">
          PROVEEDOR el restablecimiento del servicio.
          <br><br>En todos los casos, el SUSCRIPTOR no está exento de sus obligaciones de pago de los bienes y/o Servicios contratados y utilizados, salvo cuando se haya determinado su improcedencia.
          <br><br><b>VIGÉSIMA: DATOS REGISTRALES.</b> Este modelo de Contrato de Adhesión, se encuentra registrado en la Procuraduría Federal del Consumidor, con el  número ________ de fecha _____ del mes de ______de 2021.
          <br><br>Asimismo, el SUSCRIPTOR podrá consultar dicho registro en https://burocomercial.profeco.gob.mx/ca_spt/Razon Social!!Nombre Comercial 00-2021.pdf
          <br><br>Cualquier diferencia entre el texto del contrato de adhesión registrado ante la Procuraduría Federal del Consumidor y el utilizado en perjuicio del SUSCRIPTOR, se tendrá por no puesta.
          <br><br>Los contratos de adhesión registrados ante la PROFECO deberán utilizarse en todas sus operaciones comerciales y corresponder fielmente con los modelos de contrato registrados por la misma, estar publicados de manera permanente en la página en Internet del PROVEEDOR y disponibles para su consulta a través de medios electrónicos, digitales o de cualquier otra nueva tecnología que lo permita, sin perjuicio de lo establecido en los Lineamientos Generales de Accesibilidad a Servicios de Telecomunicaciones para los Usuarios con Discapacidad que emita el Instituto.
        </p>
      </td>
      <td class="title" colspan="6" style="margin-top: 1.7rem !important;">

      </td>
    </tr>
  </table>
</body>
</html>