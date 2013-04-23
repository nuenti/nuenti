<form>
<?php

	/*if($_GET['idfotos'] AND $_GET['uploader']){
		$fotos=mysqli_query($link,"SELECT * from fotos WHERE uploader='".$_GET['uploader']."' AND idfotos<='".$_GET['idfotos']."' ORDER BY idfotos DESC LIMIT 2");
	}else{
		$fotos=mysqli_query($link,"SELECT * from fotos WHERE uploader='".$global_idusuarios."' ORDER BY idfotos DESC LIMIT 2");
		if(mysqli_num_rows($fotos)<1){
			echo "Todavia no has subido ninguna foto, <a href='subir_fotos.php'>hazlo ahora</a>";
			die();
		}
		$_GET['uploader']=$global_idusuarios;
	}*/
	
	
	if(mysqli_num_rows($fotos)){
		//Navegacion de fotos
		$navegar_primera="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$row_fotos['primera'];
		if($row_fotos['anterior'])
			$navegar_anterior="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$row_fotos['anterior'];
		if($row_fotos['siguiente'])
			$navegar_siguiente="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$row_fotos['siguiente'];
		$navegar_ultima="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&idfotos=".$row_fotos['ultima'];
		?>
		<script>
			//Declarando variables
			iduser = "<?php echo $_GET['iduser']; ?>";
			idalbum = "<?php echo $row_fotos['albums_idalbums']; ?>";
			idfoto = "<?php echo $row_fotos['idfotos']; ?>";
			
			tecla_primera = "<?php echo $navegar_primera; ?>";
			tecla_anterior = "<?php echo $navegar_anterior; ?>";
			tecla_siguiente = "<?php echo $navegar_siguiente; ?>";
			tecla_ultima = "<?php echo $navegar_ultima; ?>";
		</script>
		
		<div class="barra_izq_centro" style="height: 600px !important;">
			<div id='coors1'></div>
			<div id='coors2'></div>
		<?php
		echo "<div id='foto_titulo'>";
			echo "<div class='original'>".$row_fotos['titulo']."</div>";
			echo "<input type='text' name='foto_titulo' value='".$row_fotos['titulo']."' class='edicion'>";
		echo "</div>\n";
				
		echo "<div id='foto_marco_padre'><div id='foto_marco_medio'><div id='foto_marco'>";
			echo "<img id='foto' style='max-width:700px;max-height:600px;' alt='".$row_fotos['titulo']."' src='".$row_fotos['archivo']."'";
				if($row_fotos['siguiente']){
					?>
					onclick="location.href='<?php echo $navegar_siguiente; ?>'"
					<?php
				}
			echo "/>";
			$etiquetados = mysqli_query($link,"SELECT nombre,apellidos,idusuarios,x,y FROM fotos LEFT JOIN etiquetas ON fotos_idfotos=idfotos INNER JOIN usuarios ON idusuarios=usuarios_idusuarios WHERE fotos.idfotos='".$row_fotos['idfotos']."'");
			while($p = mysqli_fetch_assoc($etiquetados)){
				echo "<div class='etiquetado etiqueta_".$p['idusuarios']."' style='left:".$p['x']."px;top:".$p['y']."px;' id='etiqueta_".$p['idusuarios']."'>
					<div class='etiqueta_nombre'>".$p['nombre']." ".$p['apellidos']."</div>
				</div>";
			}
		//BARRA NAVEGACION
		echo "<div>";
		if($row_fotos['actual']>1){
			echo "<div class='flecha_back_top' onclick=\"location.href='".$navegar_primera."'\"></div>";
			echo "<div class='flecha_back' onclick=\"location.href='".$navegar_anterior."'\"></div>";
		}
		echo "foto ".$row_fotos['actual']." de ".$row_fotos['totales'];
		if($row_fotos['actual']<$row_fotos['totales']){
			echo "<div class='flecha_next' onclick=\"location.href='".$navegar_siguiente."'\"></div>";
			echo "<div class='flecha_next_top' onclick=\"location.href='".$navegar_ultima."'\"></div>";
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
	//echo "<br>ID: ".$row['idfotos']." - File: ".$row['archivo']."<br>\n";
	//echo mysqli_num_rows($fotos);

		?>
		
	</div>
</div>
</div>
</div>