<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Entrega de SIM y Dispositivo</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .col-format {
            width: 280px;
            border: solid;
        }

        table{
          table-layout: fixed;
            width:100%;
        }
        td{
            border:2px solid black;
        }
        .subtitle {
          border: 1px solid black;
          background-color: #fff !important;
          color: black;
          font-size: 12.5px;
        }
        .field {
          border:1px solid black;
          font-size: 15px;
          font-weight: 500;
        }
        .empty {
            height: 22px;
        }

        .field_empty {
            border: none;
        }

        .footer_text {
            font-size: 13px;
        }

        .signature {
            height: 60px;
        }

        .general_data {
            background-color: #343a40;
            color: white;
        }

        .cut {
            font-size: 13px;
        }

    </style>
</head>
<body>
    <div class="card col-md-10 px-3 m-3">
        <div class="card-body mx-auto">
            <div class="col-md-12 d-flex justify-content-between px-0 mr-3">
                <div>
                    <img src="{{asset('storage/uploads/conecta2.png')}}" >
                </div>
                <div style="left: 100px !important;">
                    <img src="{{asset('storage/uploads/altcel_corporativo.png')}}" >
                </div>
            </div>
            <div class="col-d-12 mt-2 d-flex justify-content-between mr-3">
                <h6 class="my-auto">TUXTLA GUTIÉRREZ, CHIAPAS.</h6>
                <h5 class="my-auto">FORMATO DE ENTREGA DE EQUIPO Y SIM'S</h5>
            </div>
            <div class="col-d-12 mt-2 d-flex justify-content-end mr-3">
                <h5 class="my-auto">Fecha/Hora: {{$fecha}}</h5>
            </div>
            <div class="col-md-12 mt-2">
                <table >
                    <tr>
                        <td class="field" colspan="4">Código: </td>
                        <td class="field" colspan="6"><center>OP-VT-F05</center></td>
                        <td class="field" colspan="4">Version:</td>
                        <td class="field" colspan="4"><center>01</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="5"><center>Especificaciones</center></td>
                        <td class="field" colspan="5"><center>Marca</center></td>
                        <td class="field" colspan="5"><center>Modelo</center></td>
                        <td class="field" colspan="3"><center>N° Equipos</center></td>
                    </tr>
                    <tr>
                        <td class="field empty" colspan="5"><center>{{$especifications}}</center></td>
                        <td class="field empty" colspan="5"><center><input class="col-md-12" type="text" id="mark_txt"></center></td>
                        <td class="field empty" colspan="5"><center><input class="col-md-12" type="text" id="model_txt"></center></td>
                        <td class="field empty" colspan="3"><center>{{$device_quantity}}</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">IMEI del Equipo:</td>
                        <td class="field empty" colspan="14"><center>{{$IMEI}}</center></td>
                    </tr>
                    <tr>
                        <td class="field_empty" colspan="18">Características del Equipo:</td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Red:</td>
                        <td class="field empty" colspan="14"><center>{{$red}}</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Clave:</td>
                        <td class="field empty" colspan="14"><center>{{$password}}</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Marca:</td>
                        <td class="field empty" colspan="14"><center id="mark"></center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Modelo:</td>
                        <td class="field empty" colspan="14"><center id="model"></center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Contraseña:</td>
                        <td class="field empty" colspan="14"><center>{{$password}}</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Número de Serie:</td>
                        <td class="field empty" colspan="14"><center>{{$serial_number}}</center></td>
                    </tr>
                    <tr>
                        <td class="field_empty" colspan="18">Datos de la SIM:</td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">SIM ICC:</td>
                        <td class="field empty" colspan="14"><center>{{$ICC}}</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Número de Línea:</td>
                        <td class="field empty" colspan="14"><center>{{$MSISDN}}</center></td>
                    </tr>
                    <tr>
                        <td class="field" colspan="4">Plan de Activación:</td>
                        <td class="field empty" colspan="14"><center>{{$rate_name}}</center></td>
                    </tr>
                    <tr>
                        <td class="field_empty" colspan="18"></td>
                    </tr>
                    <tr>
                        <td class="field_empty" colspan="18"></td>
                    </tr>
                    <tr>
                        <td class="field_empty" colspan="18"></td>
                    </tr>
                    <tr>
                        <td class="field_empty" colspan="18"></td>
                    </tr>
                    <tr>
                        <td class="field_empty cut" colspan="18">Recorta aquí <i class="fas fa-cut"></i>------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
                    </tr>
                    <tr>
                        <td class="field general_data" colspan="10"><center>DATOS GENERALES</center></td>
                    </tr>
                    <tr>
                        <td class="field general_data" colspan="5"><center>RED</center></td>
                        <td class="field" colspan="5"><center>{{$red}}</center></td>
                    </tr>
                    <tr>
                        <td class="field general_data" colspan="5"><center>CONTRASEÑA</center></td>
                        <td class="field" colspan="5"><center>{{$password}}</center></td>
                    </tr>
                    <tr>
                        <td class="field general_data" colspan="5"><center>NO. SIM</center></td>
                        <td class="field" colspan="5"><center>{{$MSISDN}}</center></td>
                    </tr>
                    <tr>
                        <td class="field general_data" colspan="5"><center>NO. SERIE</center></td>
                        <td class="field" colspan="5"><center>{{$serial_number}}</center></td>
                    </tr>
                </table>
            </div>
        </div>
        <button class="btn btn-primary" type="button" value="Imprimir" id="printBTN" onclick="printFormat()" ><i class="fas fa-print"></i> Imprimir</button>
    </div>
</body>
<script>
    $('#mark_txt').keyup(function(){
        $('#mark').html($(this).val());
    });

    $('#model_txt').keyup(function(){
        $('#model').html($(this).val());
    });

    
    function printFormat(){
        $('#printBTN').addClass('d-none');
        window.print();
        setTimeout(function(){ 
            $('#printBTN').removeClass('d-none');
        }, 1000);
    }
</script>
</html>