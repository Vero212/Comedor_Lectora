<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Ingreso</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/materialize.min.css') ?>">

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/materialize.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/ingreso.js') ?>"></script>

    <style>
        html {
            background-color: #000;
            background-size: cover;
            overflow: hidden;
        }
        input {
           /* margin: 0 !important;
            height: 0 !important;
            border: none !important;
            color: #000 !important;
            font-size: 1px !important; */
        }
        form {
            margin: 0 !important;
        }
        h2 {
            font-size: 40px;
        }
    </style>
</head>

<body>
<main style="width:800px; height: 474px; margin: auto;">
    <center>
        <img class="responsive-img" style="width: 200px; margin-top:80px; margin-bottom: -30px;"
             src="<?= base_url('assets/images/logo_nasa_negro.png') ?>" />
        <h1 style="color: #FFFFFF; margin:25px 0">Registro de Ingreso</h1>

        <div class="container">

            <div class="col s6" style="width: 480px; display: none;" id="cont-foto">
                <div class="card horizontal">
                    <div class="card-stacked">
                        <div class="card-content">
                            <p id="bienvenida" style="font-size: 14px;"></p>
                            <p style="font-size: 14px">Imprimiendo Ticket</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cont-msg" class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 0 48px; border: 1px solid #EEE; min-width: 600px">
                <form id="form-val" method="post" autocomplete="off" onsubmit="return false;">
                    <div class="row" style="margin-bottom: 0 !important">
                        <label id="mensaje" for="tarjeta" style="font-size: 18px; color:black;"></label>
                        <!-- modo poduccion - descomentar -->
                        <div class="input-field col s12" style="position: absolute; top:500px;">
                        <input class="validate center-align" type="text" name="tarjeta" id="tarjeta"/>

                        <!-- modo desarrollo para pruebas -->
                            <!-- <div class="input-field col s12" style="position: relative; top:0; margin-top: 40px;">
                            <input class="validate center-align" type="text" name="tarjeta" id="tarjeta" style="height:40px; font-size:20px;" /> -->
                            
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </center>

    <h2 id="footer" style="color: #FFF; text-align: center;">DPTO. TECNOLOGÍA INFORMÁTICA ATUCHA</h2>
    <div style="color: #FFF; text-align: right;"><small><?= esc($server_ip) ?></small></div>
    <input class="noEnterSubmit center-align" type="text" name="temp" id="temp" style="color:black"/>
</main>

<iframe id="genTicket" style="display:none"></iframe>
</body>
</html>
