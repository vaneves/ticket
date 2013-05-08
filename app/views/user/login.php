<div class="row-fluid">
	<div class="span8">
		<form method="post" action="">
			<fieldset>
				<legend>Acesso Interno</legend>
				<?= BForm::input('E-mail', 'Email', null, 'span9') ?>
				<?= BForm::password('Senha', 'Password', null, 'span9') ?>

				<button type="submit" class="btn btn-primary">Entrar</button>
			</fieldset>
		</form>
	</div>
	<div class="span4 hidden-phone">
		<div class="thumbnail">
			<p><b>Já tem cadastro?</b></p>
			<p>Se você já efetuou o cadastro, entre e veja o histórico e andamento de todos os seus tickets.</p>
			
			<p><b>Não tem cadastro?</b></p>
			<p>Você pode cadastrar-se gratuitamente.</p>
		</div>
	</div>
</div>