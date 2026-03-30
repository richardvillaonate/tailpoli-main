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
        font-size: 10px;
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
        padding-top: 8px;
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


    .fila {
    font-size: 11px;
    padding: 2px 0;
}

.label {
    font-weight: bold;
    white-space: nowrap;
    vertical-align: top;
}

.valor {
    text-transform: uppercase;
    padding-left: 4px;
    word-wrap: break-word;
    word-break: break-word;
}

</style>

</head>

<body>

<div class="contenedor">

    <!-- 🔴 GUIA SUPERIOR -->
    <div class="guia">--- CORTE  ---</div>

    <!-- 🪪 FRENTE -->
    <div class="carnet">

       <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <!-- LOGO IZQUIERDA -->
                <td style="width:40px; vertical-align: middle;">
                    <img src="{{public_path('img/icono.png')}}" style="width:35px; height:35px;">
                </td>

                <!-- TEXTO DERECHA -->
                <td style="vertical-align: middle; padding-left:5px;">
                    <div class="titulo">
                <div class="nombre">INSTITUTO POLIANDINO</div>
                Resolución. 18031 de 02 de Nov. 2017 Bogotá
               
                    
            </div>
                </td>
            </tr>
        </table>
    </div>

        <div class="franja">ESTUDIANTE</div>

        <div class="contenido">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <!-- FOTO -->
            <td style="width:110px; vertical-align: top;">
                <div class="foto">
                    <!-- <img src="https://via.placeholder.com/105x140"> -->
                </div>
            </td>

            <!-- DATOS -->
            <td style="vertical-align: top; padding-left:6px;">
               <table width="100%" cellpadding="0" cellspacing="0" style="table-layout: fixed;">
                    <tr>
                        <td class="fila"><span class="label">Nombre:</span></td>
                        <td class="fila valor">{{ $matricula->alumno->perfil->name }}</td>
                    </tr>
                    <tr>
                        <td class="fila"><span class="label">Apellidos:</span></td>
                        <td class="fila valor">{{$matricula->alumno->perfil->lastname}}</td>
                    </tr>
                    <tr>
                        <td class="fila"><span class="label">Identificación:</span></td>
                        <td class="fila valor">{{ number_format($matricula->alumno->perfil->documento, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fila"><span class="label">Curso:</span></td>
                        <td class="fila valor"> {{ $matricula->curso->name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

    </div>

    <!-- 🔴 GUIA CORTE ENTRE CARAS -->
    <div class="guia">--- REVERSO ---</div>

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
            <img src="{{public_path('img/firma_directora.png')}}">
            <div class="linea"></div>
            <!-- <div class="cargo">DIRECCIÓN</div> -->
        </div>

        <!-- PIE -->
        <div class="pie">
            <div class="franj1">SEDE A: BOGOTA D.C. CRA 12A BIS NO. 22-12 SUR </div>
            <!-- <img src="https://via.placeholder.com/324x25"> -->
        </div>

    </div>

</div>

    <!-- 🔴 GUIA INFERIOR -->
    <div class="guia">--- CORTE ---</div>

</div>

</body>
</html>
