<!DOCTYPE html>
<html lang="es">
	<head>
		<link href="./assets/nuenti_logo.png" rel="icon">
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
		<meta content="#000000" name="theme-color">
		<meta content="" name="description">
		<meta content="yes" name="mobile-web-app-capable">
		<meta content="black" name="apple-mobile-web-app-status-bar-style">
		<meta content="Base44" name="apple-mobile-web-app-title">
		<link href="./assets/nuenti_logo.png" rel="apple-touch-icon">
		<title>nuenti</title>
		<meta content="website" property="og:type">
		<meta content="https://nuenti.xyz" property="og:url">
		<meta content="nuenti" property="og:title">
		<meta content="" property="og:description">
		<meta content="./assets/nuenti_logo.png" property="og:image">
		<meta content="summary_large_image" property="twitter:card">
		<meta content="https://nuenti.xyz" property="twitter:url">
		<meta content="nuenti" property="twitter:title">
		<meta content="" property="twitter:description">
		<meta content="./assets/nuenti_logo.png" property="twitter:image">
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;600;700&display=swap');
			* {
			font-family: Arial, sans-serif;
			}
			.nuenti-blue {
			background: linear-gradient(180deg, #6b9ac4 0%, #5487ba 100%);
			}
			.nuenti-nav-item {
			color: white;
			padding: 8px 12px;
			text-decoration: none;
			font-size: 13px;
			font-weight: 600;
			transition: background 0.2s;
			}
			.nuenti-nav-item:hover {
			background: rgba(255, 255, 255, 0.15);
			}
			.nuenti-nav-item.active {
			background: rgba(255, 255, 255, 0.2);
			}
			.nuenti-button {
			background: linear-gradient(180deg, #4a90e2 0%, #357abd 100%);
			border: 1px solid #2e6da4;
			color: white;
			padding: 6px 14px;
			border-radius: 3px;
			font-size: 12px;
			font-weight: 600;
			cursor: pointer;
			box-shadow: 0 1px 2px rgba(0,0,0,0.1);
			}
			.nuenti-button:hover {
			background: linear-gradient(180deg, #5a9ef2 0%, #4580cd 100%);
			}
			.nuenti-button:disabled {
			opacity: 0.5;
			cursor: not-allowed;
			}
			.nuenti-sidebar {
			background: white;
			border: 1px solid #d3d8dd;
			border-radius: 4px;
			box-shadow: 0 1px 2px rgba(0,0,0,0.05);
			}
			.nuenti-logo {
			font-size: 24px;
			font-weight: 700;
			color: white;
			text-decoration: none;
			letter-spacing: -0.5px;
			}
			.mobile-nav-item {
			flex: 1;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 8px;
			color: rgba(255, 255, 255, 0.7);
			transition: all 0.2s;
			}
			.mobile-nav-item.active {
			color: white;
			background: rgba(255, 255, 255, 0.15);
			}
			.mobile-menu-overlay {
			position: fixed;
			inset: 0;
			background: rgba(0, 0, 0, 0.5);
			z-index: 40;
			animation: fadeIn 0.2s;
			}
			.mobile-menu {
			position: fixed;
			top: 0;
			left: 0;
			bottom: 0;
			width: 280px;
			background: white;
			z-index: 50;
			animation: slideInLeft 0.3s;
			overflow-y: auto;
			}
			@keyframes fadeIn {
			from { opacity: 0; }
			to { opacity: 1; }
			}
			@keyframes slideInLeft {
			from { transform: translateX(-100%); }
			to { transform: translateX(0); }
			}
		</style>
	</head>