<?php
session_start();
require('inc/config.php');

if(isset($_SESSION['idsesion']) AND !$_GET['activacion'])
	header("Location: inicio.php");


if ($_POST['email']) {
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    $stmt = mysqli_prepare($link, "SELECT idusuarios, password, activacion FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $idusuarios, $password_hash, $activacion);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);

        if ($activacion == '1' && password_verify($password_input, $password_hash)) {
            // LOGIN OK
            $codigo_temp = rand(0, 99999999999);
            mysqli_query($link, "UPDATE usuarios SET idsesion='" . $codigo_temp . "' WHERE idusuarios='" . $idusuarios . "'");
            mysqli_query($link, "INSERT INTO accesos (usuarios_idusuarios, ip, fecha) VALUES ('{$idusuarios}','{$_SERVER["REMOTE_ADDR"]}',now())");
            $_SESSION['idsesion'] = $codigo_temp;
            header("Location: inicio.php");
            die();
        } else {
            // Contraseña incorrecta o cuenta no activada
            $error = ($activacion != '1') ? "activacion" : "datos";
        }
    } else {
        // Usuario no encontrado
        $error = "datos";
    }

    mysqli_stmt_close($stmt);
}

head("Login");
?>
<body id="seccion_login">
		<div class="centrar">
			<div id="titulo">
				<img src="css/logosocial.png">
				<h1><?php echo SITE; ?></h1>
			</div>
			
			<?php
			if($error){
				echo "<div class='centrar'><div class='error_ajustable'>";
				if($error == "datos"){
					print "El email o la contraseña introducidos son incorrectos.<br>
							<a href='otros.php?restore_pass=1'>¿Quieres recuperar la contrase&ntilde;a?</a>
						";
				}elseif($error == "activacion"){
					echo "La cuenta no est&aacute; activada a&uacute;n, revisa tu cuenta de email";
				}
				echo "</div></div>";
			}
			
			if($_GET['activacion']){
				if($_GET['activacion'] == "ok"){
					echo "<div class='centrar'><div class='ok_ajustable'>La cuenta se ha activado correctamente, ahora puedes entrar con tus datos</div></div>";
				}elseif($_GET['activacion'] == "fail"){
					echo "<div class='centrar'><div class='error_ajustable'>La activacion de la cuenta ha fallado</div></div>";
				}elseif($_GET['activacion'] == "fail_email"){
					print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al enviar el correo</div></div>";
				}
			}
			?>	
				<form id='form_login' method='POST' action='login.php'>
					Email: 					
						<div class="input">
							<span>
								<input id="email" name="email" class="validable" type="text" value="<?php echo $_POST['email']; ?>" maxlength='40' autofocus placeholder="ejemplo@mail.com">
							</span>
						</div><br>
					
					Clave:  					
						<div class="input">
							<span>
								<input id="password" name="password" class="validable" type="password" value="" maxlength='30' placeholder="Contrase&ntilde;a">
							</span>
						</div><br>
					<button type='submit' name='registro' value='Registrarse' class="azul"><span><b>Entrar</b></span></button>
					<button type='button' class="azul" onclick="location.href='registro.php';"><span><b>Registrarse</b></span></button>
				  </form>
			</div>
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