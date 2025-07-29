<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Ingreso</title>

    <!-- Estilos y scripts -->
    <link rel="stylesheet" href="<?= base_url('assets/css/materialize.min.css') ?>">
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/materialize.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/ingreso.js') ?>"></script>

    <style>
        /* 🔒 Evitamos el scroll general */
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #000;
            background-size: cover;
            box-sizing: border-box;
        }

        *, *::before, *::after {
            box-sizing: inherit;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 40px;
        }

        main {
            width: 100%;
            max-width: 800px;
            padding: 20px;
        }

        input {
            margin: 0 !important;
            height: 0 !important;
            border: none !important;
            color: #000 !important;
            font-size: 1px !important;
        }

        form {
            margin: 0 !important;
        }

        h1 {
            color: #fff;
            margin: 25px 0;
            font-size: 32px;
            text-align: center;
        }

        h2#footer {
            color: #FFF;
            text-align: center;
            font-size: 18px;
            margin-top: 60px;
        }

        #cont-msg {
            background-color: #f5f5f5;
            padding: 0 48px;
            border: 1px solid #EEE;
            width: 100%;
            max-width: 600px;
            display: inline-block;
        }

        #cont-foto {
            width: 100%;
            max-width: 480px;
            display: none;
        }

        #mensaje {
            font-size: 18px;
            color: black;
        }

        .responsive-img {
            margin-top: 10px !important;
        }

        .input-field.col.s12 {
            margin-top: 10px;
        }

        #mensaje {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 30px;
        text-align: center;
        font-size: 20px;
        }

        @media (max-width: 1024px) {
            main {
                max-width: 90%;
                padding: 10px;
                padding-top: 0 !important;
            }

            #cont-msg {
                max-width: 90%;
                padding: 0 20px;
            }
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }

            #mensaje {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 30px;
                text-align: center;
                font-size: 16px;
            }

            #footer {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="center-align">
            <img class="responsive-img" style="width: 200px; margin:80px auto -30px auto;"
                src="<?= base_url('assets/images/logo_nasa_negro.png') ?>" />
            <h1>Registro de Ingreso</h1>
        </div>

        <div class="container" style="margin-left:13%">
            <div class="col s6" id="cont-foto" style="background-color:blue">
                <div class="card horizontal" >
                    <div class="card-stacked">
                        <div class="card-content">
                            <p id="bienvenida" style="font-size: 14px></p>
                            <p style="font-size: 14px">Imprimiendo Ticket</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cont-msg" class="z-depth-1 grey lighten-4 row" >
                <form id="form-val" method="post" autocomplete="off" onsubmit="return false;">
                    <div class="row" style="margin-bottom: 0 !important">
                        <label id="mensaje" for="tarjeta"></label>
                        <div class="input-field col s12">
                            <input class="validate center-align" type="text" name="tarjeta" id="tarjeta"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h2 id="footer">DPTO. TECNOLOGÍA INFORMÁTICA ATUCHA</h2>
        <div style="color: #FFF; text-align: right;"><small><?= esc($server_ip) ?></small></div>
        <input class="noEnterSubmit center-align" type="text" name="temp" id="temp" style="color:black"/>
    </main>

    <iframe id="genTicket" style="display:none;" width="0" height="0"></iframe>
</body>
</html>
