<?php

require("inc/verify_login.php");
$pdo = getDB();
head("Subir fotos");
require_once('inc/head.php');
require_once('inc/header.php');
require_once('inc/bottom-navigation.php'); ?>
	<div class="barra_full">
	<div class="marco">
		<?php
		$mensajes = [];
		$errores = [];

		function procesar_imagen_cuadrada($origen, $destino, $calidad = 85) {
			$info = @getimagesize($origen);
			if (!$info) {
				throw new RuntimeException('El archivo no es una imagen válida.');
			}
			list($ancho, $alto, $tipo) = $info;
			switch ($tipo) {
				case IMAGETYPE_JPEG:
					$imagen = @imagecreatefromjpeg($origen);
					break;
				case IMAGETYPE_PNG:
					$imagen = @imagecreatefrompng($origen);
					break;
				case IMAGETYPE_GIF:
					$imagen = @imagecreatefromgif($origen);
					break;
				default:
					throw new RuntimeException('Formato no soportado.');
			}
			if (!$imagen) {
				throw new RuntimeException('No se pudo procesar la imagen.');
			}
			$lado = min($ancho, $alto);
			$offsetX = (int)(($ancho - $lado) / 2);
			$offsetY = (int)(($alto - $lado) / 2);
			$dimensionDestino = min(1080, $lado);
			$canvas = imagecreatetruecolor($dimensionDestino, $dimensionDestino);
			$blanco = imagecolorallocate($canvas, 255, 255, 255);
			imagefill($canvas, 0, 0, $blanco);
			imagecopyresampled($canvas, $imagen, 0, 0, $offsetX, $offsetY, $dimensionDestino, $dimensionDestino, $lado, $lado);
			if (!imagejpeg($canvas, $destino, $calidad)) {
				imagedestroy($canvas);
				imagedestroy($imagen);
				throw new RuntimeException('No se pudo guardar la imagen.');
			}
			imagedestroy($canvas);
			imagedestroy($imagen);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir_fotos'])) {
			$tituloOriginal = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
			$titulo = $tituloOriginal !== '' ? $tituloOriginal : 'Sin título';
			$albumRaw = $_POST['idalbums'] ?? 'NULL';
			$albumId = ($albumRaw === '' || strtoupper($albumRaw) === 'NULL') ? null : (int)$albumRaw;
			$archivos = $_FILES['imagenes'] ?? null;
			$exitos = [];

			if ($archivos && is_array($archivos['tmp_name'])) {
				$directorio = __DIR__ . DIRECTORY_SEPARATOR . 'user_fotos';
				if (!is_dir($directorio) && !mkdir($directorio, 0777, true)) {
					$errores[] = 'No se pudo preparar la carpeta de destino.';
				}
				if (empty($errores)) {
					for ($i = 0; $i < count($archivos['tmp_name']); $i++) {
						if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
							$errores[] = 'No se pudo subir "' . htmlspecialchars($archivos['name'][$i], ENT_QUOTES, 'UTF-8') . '" (código ' . $archivos['error'][$i] . ').';
							continue;
						}
						$tempFile = $archivos['tmp_name'][$i];
						try {
							$hash = sha1($global_idusuarios . microtime(true) . random_bytes(8));
							$nombreArchivo = $hash . '.jpg';
							$destinoFinal = $directorio . DIRECTORY_SEPARATOR . $nombreArchivo;
							procesar_imagen_cuadrada($tempFile, $destinoFinal);
							$exitos[] = $nombreArchivo;
						} catch (Throwable $e) {
							$errores[] = 'Error con "' . htmlspecialchars($archivos['name'][$i], ENT_QUOTES, 'UTF-8') . '": ' . $e->getMessage();
						}
					}
				}
			} else {
				$errores[] = 'Debes seleccionar al menos una imagen.';
			}

			if (!empty($exitos)) {
				try {
					$pdo->beginTransaction();
					$insert = $pdo->prepare("INSERT INTO fotos (titulo, archivo, uploader, fecha, albums_idalbums) VALUES (:titulo, :archivo, :uploader, NOW(), :album)");
					foreach ($exitos as $archivoFinal) {
						$insert->execute([
							':titulo' => $titulo,
							':archivo' => 'user_fotos/' . $archivoFinal,
							':uploader' => $global_idusuarios,
							':album' => $albumId
						]);
					}

					$qNovedades = $pdo->prepare("SELECT idnovedades, datos FROM novedades WHERE propietario = :propietario AND tipo = 'subida_fotos' AND fecha > ADDTIME(NOW(), '-7 0:0:0') LIMIT 1");
					$qNovedades->execute([':propietario' => $global_idusuarios]);
					$novedad = $qNovedades->fetch();

					if ($novedad) {
						$nuevoTotal = $novedad['datos'] + count($exitos);
						$upd = $pdo->prepare("UPDATE novedades SET datos = :datos WHERE idnovedades = :id");
						$upd->execute([
							':datos' => $nuevoTotal,
							':id' => $novedad['idnovedades']
						]);
					} else {
						$insNov = $pdo->prepare("INSERT INTO novedades (fecha, tipo, propietario, datos) VALUES (NOW(), 'subida_fotos', :propietario, :datos)");
						$insNov->execute([
							':propietario' => $global_idusuarios,
							':datos' => count($exitos)
						]);
					}

					$pdo->commit();
					$mensajes[] = count($exitos) . ' imagen(es) se procesaron y guardaron correctamente.';
				} catch (Throwable $e) {
					if ($pdo->inTransaction()) {
						$pdo->rollBack();
					}
					$errores[] = 'No se pudieron registrar las fotos en la base de datos: ' . $e->getMessage();
					foreach ($exitos as $archivoFinal) {
						@unlink($directorio . DIRECTORY_SEPARATOR . $archivoFinal);
					}
				}
			}
		}

		foreach ($mensajes as $msg) {
			echo "<div class='centrar'><div class='ok_ajustable'>" . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</div></div>";
		}
		foreach ($errores as $err) {
			echo "<div class='centrar'><div class='error_ajustable'>" . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . "</div></div>";
		}

		try {
			$albumsStmt = $pdo->prepare("SELECT idalbums, album FROM albums WHERE usuarios_idusuarios = :usuario ORDER BY album ASC");
			$albumsStmt->execute([':usuario' => $global_idusuarios]);
			$albums = $albumsStmt->fetchAll();
		} catch (Throwable $e) {
			$albums = [];
		}
		?>

		<form method="post" action="subir_fotos.php" enctype="multipart/form-data">
			<input type="hidden" name="subir_fotos" value="1">
			<b>Título para las fotos:</b>
			<div class="input">
				<span>
					<input type="text" name="titulo" placeholder="Escribe un título para las fotos" size="55" value="<?= isset($tituloOriginal) ? htmlspecialchars($tituloOriginal, ENT_QUOTES, 'UTF-8') : '' ?>">
				</span>
			</div>
			<br />
			<b>Álbum:</b>
			<?php if (!empty($albums)) : ?>
				<div class='input'>
					<span class='select'>
						<select name='idalbums'>
							<option value='NULL'>Ninguno</option>
							<?php foreach ($albums as $row) : ?>
								<option value='<?= (int)$row['idalbums']; ?>'><?= htmlspecialchars($row['album'], ENT_QUOTES, 'UTF-8'); ?></option>
							<?php endforeach; ?>
						</select>
					</span>
				</div>
			<?php else : ?>
				<span class='ayuda' title='Primero debes crear un álbum'>Ninguno</span>
			<?php endif; ?>
			<br />
			<b>Imágenes:</b>
			<div class="input">
				<span>
					<input type="file" name="imagenes[]" accept="image/jpeg,image/png,image/gif" multiple required>
				</span>
			</div>
			<br />
			<button type='submit' class="azul"><span><b>Subir fotos</b></span></button>
		</form>
	</div>
	</div>

<?php require("inc/chat.php"); ?>
</body>
</html>