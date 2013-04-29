<div class="row-fluid">
	<div class="span8">
		<div class="thumbnail">
			<p></p>
		</div>
	</div>
	<div class="span4">
		<div class="thumbnail">
			<h2>Acesso Interno</h2>

			<form method="post" action="">
				<?= BForm::input('E-mail', 'Email', null, 'span12') ?>
				<?= BForm::password('Senha', 'Password', null, 'span12') ?>

				<button type="submit" class="btn btn-primary">Entrar</button>
			</form>
			<hr>
			<button type="submit" class="btn btn-large btn-block">Cadastre-se</button>
		</div>
	</div>
</div>