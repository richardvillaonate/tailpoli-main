<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
    body {
        background: #e5e5e5;
        font-family: Arial;
    }

    .contenedor {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
        margin-top: 50px;
    }

    /* 🔴 GUIAS */
    .guia {
        width: 324px;
        border-top: 2px dashed red;
        text-align: center;
        font-size: 10px;
        color: red;
        margin: 5px 0;
    }

    /* 🪪 CARNET */
    .carnet {
        width: 324px;
        height: 204px;
        background: white;
        border: 2px solid black;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* HEADER */
    .header {
        display: flex;
        align-items: center;
        background: #0d3b66;
        color: white;
        padding: 4px;
        gap: 5px;
    }

    .logo {
        width: 35px;
        height: 35px;
        padding: 1%;
    }

    .logo img {
        width: 100%;
    }

    .titulo {
        font-size: 12px;
    }

    .nombre {
        font-weight: bold;
        font-size: 21px;
    }

    /* FRANJA */
    .franja {
        background: #16a34a;
        color: white;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        padding: 2px;
    }
    .franj1{
        background: #0d3b66;
        color: white;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        padding: 10px;
    }

    /* CONTENIDO */
    .contenido {
        display: flex;
        padding: 5px;
        gap: 5px;
        flex: 1;
    }

    .foto {
        width: 105px;
        height: 140px;
        border: 1px solid #ccc;
    }

    .foto img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .datos {
        font-size: 9px;
        flex: 1;
    }

    .fila {
        margin-bottom: 3px;
        font-size: 12px;
    }

    .label {
        font-weight: bold;
    }

    .valor {
        text-transform: uppercase;
    }

    /* FOOTER */
    .footer {
        background: #0d3b66;
        color: white;
        text-align: center;
        font-size: 13px;
        padding: 2px;
    }

    /* REVERSO */
    .reverso {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 8px;
        font-size: 12px;
        text-align: center;
        height: 100%;
    }

    .firma img {
        width: 120px;
    }

    .pie img {
        width: 100%;
        height: 25px;
        object-fit: cover;
    }
    .texto{
        padding: 2%;
    }

</style>

</head>

<body>

<div class="contenedor">

    <!-- 🔴 GUIA SUPERIOR -->
    <div class="guia">--- CORTE ---</div>

    <!-- 🪪 FRENTE -->
    <div class="carnet">

        <div class="header">
            <div class="logo">
                <img src="img/icono.png">
            </div>

            <div class="titulo">
                <div class="nombre">INSTITUTO POLIANDINO</div>
                Resolución. 18031 de 02 de Nov. 2017 Bogotá
               
                    
            </div>
        </div>

        <div class="franja">ESTUDIANTE</div>

        <div class="contenido">

            <div class="foto">
                <!-- <img src="https://via.placeholder.com/60x75"> -->
            </div>

            <div class="datos">
                <div class="fila"><span class="label">Nombre:</span> <span class="valor">{{ $matricula->alumno->perfil->name }}</span></div>
                <div class="fila"><span class="label">Apellidos:</span> <span class="valor">{{ $matricula->alumno->perfil->lastname }}</span></div>
                <div class="fila"><span class="label">Identificación:</span> <span class="valor">{{ $matricula->alumno->perfil->documento }}</span></div>
                <div class="fila"><span class="label">Curso:</span> <span class="valor">{{ $matricula->curso->name }}</span></div>
            </div>

        </div>

        <div class="footer">
            Carnet institucional
        </div>

    </div>

    <!-- 🔴 GUIA CORTE ENTRE CARAS -->
    <div class="guia">--- CORTE / DOBLEZ ---</div>

 <!-- 🔁 REVERSO -->
<div class="carnet">

    <div class="reverso">

        <!-- TEXTO SUPERIOR -->
        <div class="texto">
            ESTE CARNET ES PERSONAL E INTRANSFERIBLE <br> <br>
            EN CASO DE PÉRDIDA COMUNICARSE EN BOGOTÁ D.C. <br>
            TEL: 601 732 7627
        </div>

        <!-- FIRMA -->
        <div class="firma">
            <img src="{{ public_path('img/firma_directora.png') }}">
            <div class="linea"></div>
            <!-- <div class="cargo">DIRECCIÓN</div> -->
        </div>

        <!-- PIE -->
        <div class="pie">
            <div class="franj1">ESTUDIANTE</div>
            <!-- <img src="https://via.placeholder.com/324x25"> -->
        </div>

    </div>

</div>

    <!-- 🔴 GUIA INFERIOR -->
    <div class="guia">--- CORTE ---</div>

</div>

</body>
</html>
<!-- 
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
</html> -->


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
