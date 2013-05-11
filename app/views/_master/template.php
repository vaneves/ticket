<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Ticket</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<link href="~/css/all.css" rel="stylesheet">
		<?php if (IS_MOBILE || IS_TABLET): ?>
			<link href="~/css/bootstrap-responsive.min.css" rel="stylesheet">
		<?php endif ?>
	</head>

	<body>
		<div class="header hidden-phone">
			<div class="container">
				<div class="row-fluid">
					<div class="span5">
						<div class="logo"><h1 class="muted">Ticket</h1></div>
					</div>
					<div class="span5">
						
					</div>
					<div class="span2">
						<?php if(Session::get('timer')): ?>
						<div class="logo"><h1 class="muted" id="timer"><?= Timer::calc(Session::get('timer'), time()) ?></h1></div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>

		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand visible-phone" href="~/" style="display: none">Ticket</a>
					<div class="nav-collapse collapse">
						<?php if (Auth::isLogged() && !Auth::is('ticket')): ?>
						<ul class="nav">
						<?php if (Auth::is('admin', 'employee')): ?>
							<li><a href="~/admin/ticket/list">Todos os Tickets</a></li>
							<li><a href="~/admin/ticket/list/open">Ticket Abertos</a></li>
						<?php elseif (Auth::is('client')): ?>
							<li><a href="~/ticket/add">Criar Ticket</a></li>
							<li><a href="~/ticket/my">Meus Tickets</a></li>
						<?php endif ?>
						</ul>
						<ul class="nav pull-right">
							<li><a href="~/logout">Sair</a></li>
						</ul>
						<?php else: ?>
						<ul class="nav">
							<li><a href="~/ticket/add">Criar Ticket</a></li>
							<li><a href="~/ticket/check">Visualizar Ticket</a></li>
						</ul>
						<ul class="nav pull-right">
							<li><a href="~/user/login">Login</a></li>
							<li><a href="~/user/register">Cadastre-se</a></li>
						</ul>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<?= FLASH ?>
			<?= CONTENT ?>
			<hr>
			<div class="footer">
				<p>&copy; Van Neves 2011 - <?= date('Y') ?></p>
			</div>
		</div>
		<script>var ROOT = '<?= ROOT_VIRTUAL ?>';</script>
		<script>var timer_full = '<?= Session::get('timer') ? strtotime(Timer::calc(Session::get('timer'), time())) : '0' ?>';</script>
		<script src="~/js/jquery-1.9.1.min.js"></script>
		<script src="~/js/bootstrap.min.js"></script>
	</body>
</html>