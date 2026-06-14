<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php

    include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <title>Login - Sistema de mapa do site</title>
</head>

<body>
    <div class="container">
        <div class="text-center">
            <img src="../../Desenvolvimento-Para-Servidores-II/assets/img/logo_fatecSR.png" alt="logo da empresa" id="logo_login"">
        </div>

        <div class=" panel-body">
            <form autocomplete="off" id="login">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="usuario" id="txtUsuario" name="txtUsuario" type="text" autofocus required>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" placeholder="senha" id="txtSenha" name="txtSenha" type="password" required>
                            <div class="input-group-append">
                                <i id="togglePassword" class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="btnEntrar" class="btn btn-block btn-success" onclick="validaLogin()">Entrar</button>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>
<script src="../Desenvolvimento-Para-Servidores-II/assets/js/login.js" defer></script>