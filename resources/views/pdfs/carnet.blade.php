<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    /* 🔥 TAMAÑO REAL TIPO CARNET */
    .carnet {
        width: 350px;
        height: 220px;
        border: 2px solid #000;
        padding: 5px;
    }

    .header img {
        width: 100%;
        height: auto;
    }

    .contenido {
        margin-top: 5px;
        font-size: 11px;
    }

    .fila {
        display: table;
        width: 100%;
        margin-bottom: 3px;
    }

    .label {
        display: table-cell;
        width: 40%;
        font-weight: bold;
    }

    .value {
        display: table-cell;
        width: 60%;
        text-transform: uppercase;
        font-weight: bold;
    }

    .curso {
        font-size: 10px;
    }

    .footer {
        width: 350px;
        height: 180px;
        border: 2px solid #000;
        margin-top: 10px;
        padding: 5px;
        text-align: center;
        font-size: 10px;
    }

    .firma img {
        width: 100px;
        margin-top: 10px;
    }

    .pie img {
        width: 100%;
        margin-top: 10px;
    }

</style>
</head>

<body>

{{-- 🔵 FRENTE DEL CARNET --}}
<div class="carnet">

    {{-- Header --}}
    <div class="header">
        <img src="{{ public_path('img/carnet.png') }}">
    </div>

    {{-- Datos --}}
    <div class="contenido">

        <div class="fila">
            <div class="label">Nombre:</div>
            <div class="value">{{ $matricula->alumno->perfil->name }}</div>
        </div>

        <div class="fila">
            <div class="label">Apellidos:</div>
            <div class="value">{{ $matricula->alumno->perfil->lastname }}</div>
        </div>

        <div class="fila">
            <div class="label">ID:</div>
            <div class="value">
                {{ number_format($matricula->alumno->perfil->documento, 0, ',', '.') }}
            </div>
        </div>

        <div class="fila">
            <div class="label">Curso:</div>
            <div class="value curso">
                CENTRO DE ENSEÑANZA<br>
                {{ $matricula->curso->name }}
            </div>
        </div>

    </div>
</div>

{{-- 🔵 REVERSO --}}
<div class="footer">

    ESTE CARNET ES PERSONAL E INTRANSFERIBLE<br>
    EN CASO DE PERDIDA COMUNICARSE EN BOGOTÁ D.C.<br>
    TEL: 601 732 7627

    <div class="firma">
        <img src="{{ public_path('img/firma_directora.png') }}">
    </div>

    <div class="pie">
        <img src="{{ public_path('img/carnet-pie.png') }}">
    </div>

</div>

</body>
</html>


<!-- <!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{public_path('img/icon.ico')}}">
        <link rel="stylesheet" href="{{public_path('css/pdf.css')}}">
    </head>
    <body>

        <div class="flex-container">
            <div class="card">
                <div class="container">
                    <table class="table">
                        <tr>
                            <td colspan="3"  class="centrado">
                                <img class="img" src="{{public_path('img/carnet.png')}}" alt="{{config('instituto.directora')}}">
                            </td>
                        </tr>
                        <tr>
                            <td class=" izquierda capitalize font-l bold">
                                nombre (s):
                            </td>
                            <td colspan="2" class="izquierda uppercase font-l bold">
                                {{$matricula->alumno->perfil->name}}
                            </td>
                        </tr>
                        <tr>
                            <td class=" izquierda capitalize font-l bold">
                                Apellidos:
                            </td>
                            <td colspan="2" class="izquierda uppercase font-l bold">
                                {{$matricula->alumno->perfil->lastname}}
                            </td>
                        </tr>
                        <tr>
                            <td class=" izquierda capitalize font-l bold">
                                Identificación:
                            </td>
                            <td colspan="2" class="izquierda uppercase font-l bold">
                                {{number_format($matricula->alumno->perfil->documento, 0, '.', ' ')}}
                            </td>
                        </tr>
                        <tr>
                            <td class=" izquierda capitalize font-l bold">
                                curso:
                            </td>
                            <td colspan="2" class="izquierda uppercase font-l bold">
                                {{$matricula->curso->name}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="container">
                    <table class="table">
                        <tr>
                            <td colspan="2" class="centrado font-l">
                                ESTE CARNET ES PERSONAL E INTRANSFERIBLE, EN CASO DE PERDIDA COMUNICARSE EN BOGOTÁ D.C. AL SIGUIENTE TEL: 601 732 7627
                            </td>
                            <td class="centrado ">

                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="centrado">
                                <img class="imgfirma" src="{{public_path('img/firma_directora.png')}}" alt="{{config('instituto.directora')}}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="font-l">

                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="centrado">
                                <img class="img" src="{{public_path('img/carnet-pie.png')}}" alt="{{config('instituto.directora')}}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


    </body>
</html> -->
