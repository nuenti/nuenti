<div id="root">
    <div class="min-h-screen w-full relative app-preview">
        <div class="flex flex-col w-full h-full" id="app-demo">
            <div class="bg-white w-full min-h-full overflow-auto">
                <div id="component-preview-container">
                    <div class="min-h-screen bg-[#e8eef4] pb-16 md:pb-0">

                          <?php
                            // Detecta el archivo actual
                            $current_file = basename($_SERVER['PHP_SELF']);
                            // Detectar páginas por querystring
                            $mp_recibidos = ($current_file == 'mp.php' && isset($_GET['modo']) && $_GET['modo'] == 'recibidos');
                            ?>

                            <header class="nuenti-blue shadow-md sticky top-0 z-30">
                                <div class="max-w-[1200px] mx-auto px-4">
                                    <div class="flex items-center justify-between h-[48px]">
                                        <button id="burger" class="md:hidden text-white p-2 hover:bg-white/10 rounded">
                                            <!-- Icono menú -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line></svg>
                                        </button>
                                        <a class="nuenti-logo flex items-center gap-2" href="inicio.php">
                                            <div class="w-6 h-6 bg-white rounded-sm flex items-center justify-center">
                                                <span class="text-[#5487ba] text-xl font-bold">n</span>
                                            </div>
                                            <span class="hidden sm:inline">nuenti</span>
                                        </a>
                                        <nav class="hidden md:flex items-center gap-1">
                                            <a class="nuenti-nav-item <?php echo ($current_file == 'inicio.php') ? 'active' : ''; ?>" href="inicio.php">Inicio</a>
                                            <a class="nuenti-nav-item <?php echo ($current_file == 'perfil.php') ? 'active' : ''; ?>" href="perfil.php">Perfil</a>
                                            <a class="nuenti-nav-item <?php echo $mp_recibidos ? 'active' : ''; ?>" href="mp.php?modo=recibidos">Mensajes</a>
                                            <a class="nuenti-nav-item <?php echo ($current_file == 'gente.php') ? 'active' : ''; ?>" href="gente.php">Gente</a>
                                        </nav>
                                        <div class="flex items-center gap-2">
                                            <a class="hidden md:flex items-center nuenti-nav-item" href="subir_fotos.php">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
													<path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/>
													<circle cx="12" cy="13" r="3"/>
												</svg>
												Subir fotos
											</a>

											<a class="hidden md:flex items-center nuenti-nav-item" href="ajustes.php">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
													<path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"/>
													<circle cx="12" cy="12" r="3"/>
												</svg>
												Ajustes
											</a>
                                        </div>
                                    </div>
                                </div>
                            </header>
                            <!-- FINAL NAVBAR HEADER -->


                          <!-- MAIN CONTENT -->
                          <div class="max-w-[1200px] mx-auto px-2 sm:px-4 py-4">