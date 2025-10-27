<?php

require('inc/config.php');

ini_set('session.gc_maxlifetime', 43200);

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    session_set_cookie_params([
        'lifetime' => 0, // La cookie expira cuando el navegador se cierra
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
} else {
    // Para entornos de desarrollo sin HTTPS (NO RECOMENDADO EN PRODUCCIÓN)
    session_set_cookie_params([
        'lifetime' => 0, // La cookie expira cuando el navegador se cierra
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

session_start();
header('X-Frame-Options: DENY');

if (isset($_SESSION['idusuarios']) && !isset($_GET['activacion'])) {
    header("Location: inicio.php");
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    if (empty($email) || empty($password_input)) {
        $error = "Por favor, ingresa tu email y contraseña.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del email no es válido.";
    } else {
        $stmt = mysqli_prepare($link, "SELECT idusuarios, password, activacion FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $idusuarios, $password_hash, $activacion);

        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_fetch($stmt);

            if ($activacion == '1' && password_verify($password_input, $password_hash)) {
                // LOGIN OK
                session_regenerate_id(true);
                
                $codigo_temp = bin2hex(random_bytes(32)); // Usamos más bytes para mayor seguridad

                $update_stmt = mysqli_prepare($link, "UPDATE usuarios SET idsesion = ? WHERE idusuarios = ?");
                mysqli_stmt_bind_param($update_stmt, "si", $codigo_temp, $idusuarios);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);

                $_SESSION['idusuarios'] = $idusuarios; 
                $_SESSION['idsesion'] = $codigo_temp;

                mysqli_query($link, "INSERT INTO accesos (usuarios_idusuarios, ip, fecha) VALUES ('{$idusuarios}','{$_SERVER["REMOTE_ADDR"]}',now())");

                header("Location: inicio.php");
                exit();
            } else {
                $error = ($activacion != '1') ? "activacion" : "datos";
            }
        } else {
            $error = "datos";
        }
        mysqli_stmt_close($stmt);
    }
}

head("Login");
?>
<body id="seccion_login">
    <div class="centrar">
        <div id="titulo">
            <h1><?php echo SITE; ?></h1>
        </div>
        
        <?php
        if ($error) {
            echo "<div class='centrar'><div class='error_ajustable'>";
            if ($error == "datos") {
                print "El email o la contraseña introducidos son incorrectos.<br>
                        <a href='otros.php?restore_pass=1'>¿Quieres recuperar la contrase&ntilde;a?</a>";
            } elseif ($error == "activacion") {
                echo "La cuenta no est&aacute; activada a&uacute;n, revisa tu cuenta de email";
            } else {
                echo $error; // Para otros errores de validación
            }
            echo "</div></div>";
        }
        
        if (isset($_GET['activacion'])) {
            if ($_GET['activacion'] == "ok") {
                echo "<div class='centrar'><div class='ok_ajustable'>La cuenta se ha activado correctamente, ahora puedes entrar con tus datos</div></div>";
            } elseif ($_GET['activacion'] == "fail") {
                echo "<div class='centrar'><div class='error_ajustable'>La activacion de la cuenta ha fallado</div></div>";
            } elseif ($_GET['activacion'] == "fail_email") {
                print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al enviar el correo</div></div>";
            }
        }
        ?>    
        <form id='form_login' method='POST' action='login.php'>
            Email:                     
            <div class="input">
                <span>
                    <input id="email" name="email" class="validable" type="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" maxlength='40' autofocus placeholder="ejemplo@mail.com" required>
                </span>
            </div><br>
            
            Clave:                      
            <div class="input">
                <span>
                    <input id="password" name="password" class="validable" type="password" value="" maxlength='30' placeholder="Contrase&ntilde;a" required>
                </span>
            </div><br>
            <button type='submit' name='entrar' value='Entrar' class="azul"><span><b>Entrar</b></span></button>
            <button type='button' class="azul" onclick="location.href='registro.php';"><span><b>Registrarse</b></span></button>
        </form>
    </div>
		<!-- MOVER CREDITOS A NUEVA PAGINA -->
		<!--
		<div id="creditos">
			<div>
				<p>Social &copy; 2012 - 2013</p>
				<p>Javier González Rastrojo</p>
			</div>
		</div>
		-->
</body>