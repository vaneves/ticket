<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Ticket</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<link href="~/css/bootstrap.min.css" rel="stylesheet">
		<link href="~/css/all.css" rel="stylesheet">
		<style type="text/css">
			body {
				padding-top: 20px;
				padding-bottom: 60px;
			}
			
			/* Customize the navbar links to be fill the entire space of the .navbar */
			.navbar .navbar-inner {
				padding: 0;
			}
			.navbar .nav {
				margin: 0;
				display: table;
				width: 100%;
			}
			.navbar .nav li {
				display: table-cell;
				width: 1%;
				float: none;
			}
			.navbar .nav li a {
				text-align: center;
				border-left: 1px solid rgba(255,255,255,.75);
				border-right: 1px solid rgba(0,0,0,.1);
			}
			.navbar .nav li:first-child a {
				border-left: 0;
				border-radius: 3px 0 0 3px;
			}
			.navbar .nav li:last-child a {
				border-right: 0;
				border-radius: 0 3px 3px 0;
			}
		</style>
	</head>

	<body>
		<div class="container">
			<div class="masthead">
				<h1 class="muted">Project Ticket</h1>
				<div class="navbar">
					<div class="navbar-inner">
						<div class="container">
							<ul class="nav">
								<?php if (Auth::isLogged() && !Auth::is('ticket')): ?>
									<?php if (Auth::is('admin', 'employee')): ?>
										<li><a href="~/admin/ticket/add">Criar Ticket</a></li>
										<li><a href="~/admin/ticket/list">Todos os Tickets</a></li>
										<li><a href="~/admin/ticket/list-status">Ticket Abertos</a></li>
									<?php elseif (Auth::is('client')): ?>
										<li><a href="~/ticket/add">Criar Ticket</a></li>
										<li><a href="~/ticket/my">Meus Tickets</a></li>
									<?php endif ?>
									<li><a href="~/logout">Sair</a></li>
								<?php else: ?>
									<li><a href="~/">Home</a></li>
									<li><a href="~/user/login">Login</a></li>
									<li><a href="~/user/register">Cadastre-se</a></li>
									<li><a href="~/ticket/check">Visualizar</a></li>
								<?php endif ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<?= FLASH ?>
			<?= CONTENT ?>

			<hr>
			<div class="footer">
				<p>&copy; Van Neves 2011 - <?= date('Y') ?></p>
			</div>
		</div>
		<script src="~/js/jquery-1.9.1.min.js"></script>
		<script src="~/js/bootstrap.min.js"></script>
	</body>
</html>