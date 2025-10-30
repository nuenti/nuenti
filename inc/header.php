    <body>
        <div id="root">
			<div class="font-sans">
				<div class="min-h-screen w-full relative app-preview">
					<div class="flex flex-col w-full h-full" id="app-demo">
						<div class="bg-white w-full min-h-full overflow-auto">
							<div id="component-preview-container">
								<div class="min-h-screen bg-[#e8eef4] pb-16 md:pb-0">
									
									<!-- top navbar desk/mobile-->
									<header class="nuenti-blue shadow-md sticky top-0 z-30">
										<div class="max-w-[1200px] mx-auto px-4">
											<div class="flex items-center justify-between h-[48px]">
                                                <a class="md:hidden" href="logout.php">
                                                    <button class="text-white hover:bg-white/10 p-2 rounded">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
													</button>
                                                </a>
												<a class="nuenti-logo flex items-center gap-2" href="inicio.php">
													<div class="w-6 h-6 bg-white rounded-sm flex items-center justify-center">
														<span class="text-[#5487ba] text-xl font-bold">n</span>
													</div>
													<span class="hidden sm:inline">nuenti</span>
												</a>
												<nav class="hidden md:flex items-center gap-1">
													<a class="nuenti-nav-item " href="inicio.php">Inicio</a>
													<a class="nuenti-nav-item " href="perfil.php">Perfil</a>
													<a class="nuenti-nav-item " href="mp.php">Mensajes</a>
													<a class="nuenti-nav-item " href="gente.php">Gente</a>
												</nav>
												<div class="flex items-center gap-2">
													<a class="hidden md:block" href="subirfotos.php">
														<button class="nuenti-button flex items-center gap-1">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera w-3 h-3">
																<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path>
																<circle cx="12" cy="13" r="3"></circle>
															</svg>
															Subir fotos
														</button>
													</a>
													<a class="md:hidden" href="subir_fotos.php">
														<button class="text-white p-2 hover:bg-white/10 rounded">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera w-5 h-5">
																<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path>
																<circle cx="12" cy="13" r="3"></circle>
															</svg>
														</button>
													</a>
													<a href="ajustes.php">
														<button class="hidden md:block text-white hover:bg-white/10 p-2 rounded">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings w-4 h-4">
																<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
																<circle cx="12" cy="12" r="3"></circle>
															</svg>
														</button>
													</a>
                                                    <a href="logout.php">
														<button class="hidden md:block text-white hover:bg-white/10 p-2 rounded">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
														</button>
													</a>
                                                </div>
											</div>
										</div>
									</header>