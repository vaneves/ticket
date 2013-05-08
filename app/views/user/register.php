<div class="row-fluid">
	<div class="span8">
		<div class="">
			<form method="post" action="">
				<fieldset>
					<legend>Efetuar Cadastro</legend>
					<?= BForm::input('Nome', 'Name', $model->Name, 'span8') ?>
					<?= BForm::input('E-mail', 'Email', $model->Email, 'span8') ?>
					<div class="row-fluid">
						<div class="span4">
							<?= BForm::password('Senha', 'Password', null, 'span12') ?>
						</div>
						<div class="span4">
							<?= BForm::password('Confirmar Senha', 'Confirm', null, 'span12') ?>
						</div>
					</div>

					<button type="submit" class="btn btn-primary">Cadastrar</button>
				</fieldset>
			</form>
		</div>
	</div>
	<div class="span4 hidden-phone">
		<div class="thumbnail">
			<p><b>Cadastre-se e tenha mais vantagens</b></p>
			<p>Efetue o cadastro e veja o histórico de todos os seus tickets. Assim não precisará decorar o número para pode visualizá-los</p>
		</div>
	</div>
</div>