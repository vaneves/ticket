<div class="row-fluid">
	<div class="span8">
		<div class="">
			<h2>Efetuar Cadastro</h2>

			<form method="post" action="">
				<?= BForm::input('Nome', 'Name', $model->Name, 'span9') ?>
				<?= BForm::input('E-mail', 'Email', $model->Email, 'span9') ?>
				<?= BForm::password('Senha', 'Password', null, 'span9') ?>
				<?= BForm::password('Confirmar Senha', 'Confirm', null, 'span9') ?>

				<button type="submit" class="btn btn-primary">Cadastrar-me</button>
			</form>
		</div>
	</div>
	<div class="span4">
		<div class="thumbnail">
			<h3>Fazer Login</h3>

			<form method="post" action="~/login">
				<?= BForm::input('E-mail', 'Email', null, 'span12') ?>
				<?= BForm::password('Senha', 'Password', null, 'span12') ?>

				<button type="submit" class="btn">Entrar</button>
			</form>
			<hr>
			<button type="submit" class="btn btn-large btn-block">Cadastre-se</button>
		</div>
	</div>
</div>