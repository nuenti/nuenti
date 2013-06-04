<?php
	if(mysqli_num_rows($q_fotos)){
		//Navegacion de fotos
		$navegar_primera="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$r_fotos['primera'];
		if($r_fotos['anterior'])
			$navegar_anterior="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$r_fotos['anterior'];
		if($r_fotos['siguiente'])
			$navegar_siguiente="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$r_fotos['siguiente'];
		$navegar_ultima="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$r_fotos['ultima'];
		?>
		<script>
			//Declarando variables
			<?php if($_GET['iduser'] == $global_idusuarios){
				echo "propietario = true;";
			}else{
				echo "propietario = false;";
			} ?>
			
			iduser = "<?php echo $_GET['iduser']; ?>";
			idalbum = "<?php echo $r_fotos['albums_idalbums']; ?>";
			idfoto = "<?php echo $r_fotos['idfotos']; ?>";
			
			tecla_primera = "<?php echo $navegar_primera; ?>";
			tecla_anterior = "<?php echo $navegar_anterior; ?>";
			tecla_siguiente = "<?php echo $navegar_siguiente; ?>";
			tecla_ultima = "<?php echo $navegar_ultima; ?>";
		</script>
		
		<div class="barra_izq_centro" style="height: 600px !important;">
			<div class="marco">
		<?php
		###### TITULO FOTO
		print "<div id='titulo'>
			<div class='original'>{$r_fotos['titulo']}</div>
			<div class='input edicion'>
				<span>
					<input name='foto_titulo' type='text' value='{$r_fotos['titulo']}' autocomplete='off' placeholder='Titulo de la foto'>
				</span>
			</div>
		</div>\n";
		
			//echo "<div class='original'>".$r_fotos['titulo']."</div>";
			//echo "<input type='text' name='foto_titulo' value='".$r_fotos['titulo']."' class='edicion' size='80'>";
		
		
		####### FOTO	
		echo "<div id='foto_marco_padre'><div id='foto_marco_medio' style='width:700px;'><div id='foto_marco'>";
			echo "<img id='foto' style='max-width:700px;max-height:600px;' alt='".$r_fotos['titulo']."' src='".$r_fotos['archivo']."'";
				if($r_fotos['siguiente']){
					?>
					onclick="location.href='<?php echo $navegar_siguiente; ?>'"
					<?php
				}
			echo "/>";
			$etiquetados = mysqli_query($link,"SELECT idusuarios, nombre, apellidos, archivo, x, y
												FROM etiquetas, usuarios
												LEFT JOIN fotos ON idfotos_princi = idfotos
												WHERE fotos_idfotos = '{$r_fotos['idfotos']}'
												AND usuarios_idusuarios = idusuarios");
			while($p = mysqli_fetch_assoc($etiquetados)){
				echo "<div class='etiquetado etiqueta_".$p['idusuarios']."' style='left:".$p['x']."px;top:".$p['y']."px;' id='etiqueta_".$p['idusuarios']."'>
					<div class='etiqueta_nombre'>".$p['nombre']." ".$p['apellidos']."</div>
				</div>";
			}
			
		####### BARRA NAVEGACION
		echo "<div id='barra_navegacion'>";
		if($r_fotos['actual']>1){
			echo "<img class='flecha_back_top' src='css/flechas/flecha_left_top.jpg' onclick=\"location.href='".$navegar_primera."'\">";
			echo "<img class='flecha_back' src='css/flechas/flecha_left.jpg' onclick=\"location.href='".$navegar_anterior."'\">";
		}
		echo "<div class='texto'>".$r_fotos['actual']." de ".$r_fotos['totales']."</div>";
		if($r_fotos['actual']<$r_fotos['totales']){
			echo "<img class='flecha_next' src='css/flechas/flecha_right.jpg' onclick=\"location.href='".$navegar_siguiente."'\">";
			echo "<img class='flecha_next_top' src='css/flechas/flecha_right_top.jpg' onclick=\"location.href='".$navegar_ultima."'\">";
		}
		echo "</div>";
	}else{
		?>
		<div class="centrar">
		<div class="error_ajustable">
		<?php
		if($_GET['iduser']==$global_idusuarios){
			if($_GET['idalbums']=='subidas'){
				echo "No has subido ninguna foto";
			}elseif($_GET['idalbums']=='etiquetadas'){
				echo "No estas etiquetado en ninguna foto";
			}else{
				echo "Este album todavia no contiene ninguna fotografia";
			}
		}else{
			if($_GET['idalbums']=='subidas'){
				echo $global_nombre." no ha subido ninguna foto";
			}elseif($_GET['idalbums']=='etiquetadas'){
				echo $global_nombre." no esta etiquetado en ninguna foto";
			}else{
				echo "Este album todavia no contiene ninguna fotografia";
			}
		}
		echo "</div></div>";
		die();
	}
	?>
	</div>
	</div>
</div>
</div>
</div>