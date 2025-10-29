<?php
require_once("inc/verify_login.php");

// GESTIÓN DEL ENVÍO DE PETICIÓN DE AMISTAD
if (isset($_POST['peticion_amistad_enviar']) && isset($_POST['idusuario'])) {
    $idusuario_peticion = mysqli_real_escape_string($link, $_POST['idusuario']);

    // Comprobar si ya son amigos
    $query_amigo = mysqli_query($link, "SELECT COUNT(*) AS cuenta FROM amigos WHERE (user1='{$idusuario_peticion}' AND user2='{$global_idusuarios}') OR (user1='{$global_idusuarios}' AND user2='{$idusuario_peticion}')");
    $row_amigo = mysqli_fetch_assoc($query_amigo);
    if ($row_amigo['cuenta'] == 0) {
        
        // Comprobar si ya existe una petición
        $query_peticion = mysqli_query($link, "SELECT COUNT(*) AS cuenta FROM peticiones WHERE (emisor='{$idusuario_peticion}' AND receptor='{$global_idusuarios}') OR (emisor='{$global_idusuarios}' AND receptor='{$idusuario_peticion}')");
        $row_peticion = mysqli_fetch_assoc($query_peticion);
        
        if ($row_peticion['cuenta'] == 0) {
            // Insertar la nueva petición de amistad
            mysqli_query($link, "INSERT INTO peticiones (emisor, receptor, fecha) VALUES ('{$global_idusuarios}', '{$idusuario_peticion}', NOW())");
            
            // Crear notificación
            $notificacion = array("propietario" => $idusuario_peticion, "tipo" => 'peticion');
            notificacion($notificacion);
        }
    }
    
    // Redirigir para limpiar el POST y evitar reenvíos
    header("Location: gente.php");
    exit();
}

// LÓGICA PARA LA BÚSQUEDA Y FILTROS
$sql = "SELECT *,
        FLOOR(DATEDIFF(CURDATE(), fnac) / 365) AS edad,
        (SELECT count(*) FROM amigos WHERE user1='{$global_idusuarios}' AND user2=idusuarios OR user2='{$global_idusuarios}' AND user1=idusuarios) AS amigo,
        (SELECT count(*) FROM peticiones WHERE emisor='{$global_idusuarios}' AND receptor=idusuarios OR receptor='{$global_idusuarios}' AND emisor=idusuarios) AS enviada
        FROM usuarios LEFT JOIN fotos ON idfotos_princi=idfotos";

$where = " WHERE idusuarios!='$global_idusuarios'";

if (!empty($_POST['nombre'])) {
    $nombre_safe = mysqli_real_escape_string($link, $_POST['nombre']);
    $where .= " AND nombre LIKE '%{$nombre_safe}%'";
}
if (!empty($_POST['apellidos'])) {
    $apellidos_safe = mysqli_real_escape_string($link, $_POST['apellidos']);
    $where .= " AND apellidos LIKE '%{$apellidos_safe}%'";
}
if (!empty($_POST['edad_menor'])) {
    $edad_menor_safe = (int)$_POST['edad_menor'];
    $where .= " AND fnac <= DATE_SUB(CURDATE(), INTERVAL {$edad_menor_safe} YEAR)";
}
if (!empty($_POST['edad_mayor'])) {
    $edad_mayor_safe = (int)$_POST['edad_mayor'];
    $where .= " AND fnac >= DATE_SUB(CURDATE(), INTERVAL {$edad_mayor_safe} YEAR)";
}
if (isset($_POST['provincia']) && $_POST['provincia'] != 'all') {
    $provincia_safe = mysqli_real_escape_string($link, $_POST['provincia']);
    $where .= " AND provincia = '{$provincia_safe}'";
}
if (isset($_POST['sexo']) && !empty($_POST['sexo'])) {
    $sexo_safe = mysqli_real_escape_string($link, $_POST['sexo']);
    $where .= " AND sexo = '{$sexo_safe}'";
}

$where_backup = $where;

// Paginación
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
if ($page < 1) $page = 1;
$limit_per_page = 6;
$limit_start = ($page - 1) * $limit_per_page;
$where .= " LIMIT $limit_start, $limit_per_page";
$sql .= $where;

$q_search = mysqli_query($link, $sql);
error_mysql();

$q_search_nums = mysqli_query($link, "SELECT COUNT(*) AS total FROM usuarios $where_backup");
$r_search_nums = mysqli_fetch_assoc($q_search_nums);
$total_results = $r_search_nums['total'];
$total_pages = ceil($total_results / $limit_per_page);

require_once('inc/head.php');
require_once('inc/header.php');
require_once('inc/bottom-navigation.php');
?>

<div class="max-w-[1200px] mx-auto px-2 sm:px-4 py-4">
    <div class="max-w-6xl mx-auto">
        <div class="grid md:grid-cols-[250px_1fr] gap-4">
            
            <!-- COLUMNA DE FILTROS (SIDEBAR) -->
            <div class="space-y-4">
                <div class="nuenti-sidebar p-4">
                    <h3 class="font-semibold text-sm mb-3">Buscar gente</h3>
                    
                    <form name="busqueda" method='post' action='gente.php' class="space-y-4 text-sm">
                        
                        <div>
                            <p class="font-medium mb-2">Nombre</p>
                            <input type="text" name="nombre" placeholder="Escribe un nombre..." class="flex rounded-md border border-input bg-background px-3 py-2 ring-offset-background md:text-sm w-full h-8 text-xs" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
                        </div>

                        <div>
                            <p class="font-medium mb-2">Apellidos</p>
                            <input type="text" name="apellidos" placeholder="Escribe un apellido..." class="flex rounded-md border border-input bg-background px-3 py-2 ring-offset-background md:text-sm w-full h-8 text-xs" value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>">
                        </div>

                        <!-- Filtro por sexo (con SELECT) -->
                        <div>
                            <p class="font-medium mb-2">Por sexo</p>
                            <select name="sexo" class="flex rounded-md border border-input bg-background px-3 py-2 ring-offset-background md:text-sm w-full h-8 text-xs">
                                <option value="" <?php if(empty($_POST['sexo'])) echo "selected"; ?>>Ambos</option>
                                <option value="m" <?php if(isset($_POST['sexo']) && $_POST['sexo'] == 'm') echo "selected"; ?>>Chica</option>
                                <option value="h" <?php if(isset($_POST['sexo']) && $_POST['sexo'] == 'h') echo "selected"; ?>>Chico</option>
                            </select>
                        </div>

                        <div>
                            <p class="font-medium mb-2">Por edad</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs">De</span>
                                <input type="number" name="edad_menor" class="flex rounded-md border border-input bg-background px-3 py-2 ring-offset-background md:text-sm w-16 h-8 text-xs" value="<?php echo htmlspecialchars($_POST['edad_menor'] ?? ''); ?>">
                                <span class="text-xs">a</span>
                                <input type="number" name="edad_mayor" class="flex rounded-md border border-input bg-background px-3 py-2 ring-offset-background md:text-sm w-16 h-8 text-xs" value="<?php echo htmlspecialchars($_POST['edad_mayor'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div>
                            <p class="font-medium mb-2">Por provincia</p>
                            <select name="provincia" class="flex rounded-md border border-input bg-background px-3 py-2 ring-offset-background md:text-sm w-full h-8 text-xs">
                                <?php require("inc/select_provincias.html"); ?>
                            </select>
                        </div>

                        <input type="hidden" name='page' value="1">
                        
                        <button type='submit' class="nuenti-button w-full">
                            <span><b>Buscar</b></span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- COLUMNA DE RESULTADOS -->
            <div class="space-y-4">
                <div class="nuenti-sidebar p-4">
                    <h1 class="text-xl md:text-2xl font-bold mb-4">Resultados de la búsqueda (<?php echo $total_results; ?>)</h1>
                    
                    <div id="resultados" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3">
                        <?php
                        if(mysqli_num_rows($q_search)){
                            while($r_search = mysqli_fetch_assoc($q_search)){

                                // --- NUEVA LÓGICA PARA LA IMAGEN ---
                                // Comprueba si el campo 'archivo' no está vacío
                                if(!empty($r_search['archivo'])) {
                                    $user_image_html = "<img src='{$r_search['archivo']}' alt='Foto de {$r_search['nombre']}' class='w-full aspect-square object-cover rounded mb-2 cursor-pointer hover:opacity-80'>";
                                } else {
                                    // Si no hay foto, muestra el SVG por defecto
                                    $user_image_html = "
                                    <div class='w-full aspect-square rounded mb-2 bg-gray-100 flex items-center justify-center border'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-user w-1/2 h-1/2 text-gray-400'>
                                            <path d='M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2'/><circle cx='12' cy='7' r='4'/>
                                        </svg>
                                    </div>";
                                }

                                // Lógica para el botón de amistad
                                $estado_amistad_btn = '';
                                if($r_search['amigo']){
                                    $estado_amistad_btn = "<button type='button' disabled class='nuenti-button-disabled w-full text-xs'><span>Amigos</span></button>";
                                    $nombre_link = "<a href='perfil.php?id={$r_search['idusuarios']}' class='hover:text-blue-600'>{$r_search['nombre']} {$r_search['apellidos']}</a>";
                                } elseif($r_search['enviada']){
                                    $estado_amistad_btn = "<button type='button' disabled class='nuenti-button-disabled w-full text-xs'><span>Petición enviada</span></button>";
                                    $nombre_link = "{$r_search['nombre']} {$r_search['apellidos']}";
                                } else {
                                    $estado_amistad_btn = "
                                    <form method='post' action='gente.php' class='w-full'>
                                        <input type='hidden' name='idusuario' value='{$r_search['idusuarios']}'>
                                        <input type='hidden' name='peticion_amistad_enviar' value='1'>
                                        <button type='submit' class='nuenti-button w-full text-xs flex items-center justify-center gap-1'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-user-plus w-3 h-3'><path d='M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2'></path><circle cx='9' cy='7' r='4'></circle><line x1='19' x2='19' y1='8' y2='14'></line><line x1='22' x2='16' y1='11' y2='11'></line></svg>
                                            Añadir amigo
                                        </button>
                                    </form>";
                                    $nombre_link = "{$r_search['nombre']} {$r_search['apellidos']}";
                                }
                                
                                echo "
                                <div class='border border-gray-200 rounded p-3 hover:shadow-md transition'>
                                    <a href='perfil.php?id={$r_search['idusuarios']}'>
                                        {$user_image_html}
                                    </a>
                                    <h3 class='font-semibold text-sm mb-1 truncate'>{$nombre_link}</h3>
                                    <p class='text-xs text-gray-500 mb-2'>".IdProvincia($r_search['provincia'])."</p>
                                    <div class='estado_amistad'>{$estado_amistad_btn}</div>
                                </div>";
                            }
                        } else {
                            echo "<p class='col-span-full text-center text-gray-500'>No se han encontrado resultados.</p>";
                        }
                        ?>
                    </div>

                    <!-- PAGINACIÓN -->
                    <?php if ($total_pages > 1): ?>
                    <div id='barra_navegacion' class="flex justify-center items-center gap-4 mt-6 text-sm">
                        <?php if ($page > 1): ?>
                            <button onclick="cambiar_pagina(1);" class="nuenti-button-secondary">&lt;&lt;</button>
                            <button onclick="cambiar_pagina(<?php echo $page - 1; ?>);" class="nuenti-button-secondary">&lt;</button>
                        <?php endif; ?>
                        
                        <div class='texto'>Página <?php echo $page; ?> de <?php echo $total_pages; ?></div>
                        
                        <?php if ($page < $total_pages): ?>
                            <button onclick="cambiar_pagina(<?php echo $page + 1; ?>);" class="nuenti-button-secondary">&gt;</button>
                            <button onclick="cambiar_pagina(<?php echo $total_pages; ?>);" class="nuenti-button-secondary">&gt;&gt;</button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cambiar_pagina(number){
        document.forms['busqueda'].page.value = number;
        document.forms['busqueda'].submit();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const provinciaSelect = document.querySelector("select[name='provincia']");
        const selectedValue = "<?php echo htmlspecialchars($_POST['provincia'] ?? 'all'); ?>";
        if (provinciaSelect && selectedValue) {
            provinciaSelect.value = selectedValue;
        }
    });
</script>

<?php require("inc/chat.php"); ?>
</body>
</html>