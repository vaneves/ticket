<div class="row-fluid">
	<div class="span8">
		<form method="post" action="">
			<fieldset>
				<legend>Visualizar Ticket</legend>
				<?= BForm::input('Número', 'Id', $model->Id, 'span9') ?>
				<?= BForm::input('E-mail', 'Email', $model->Email, 'span9') ?>

				<button type="submit" class="btn btn-primary">Visualizar</button>
			</fieldset>
		</form>
	</div>
	<div class="span4 hidden-phone">
		<div class="thumbnail">
			<p><b>Como anda o seu ticket?</b></p>
			<p>Caso você não tenha efetuado o cadastro e queira visualizar um ticket específico, basta informar o número deste ticket e o seu e-mail.</p>
			<p>Se você efetuar o cadastro poderá visualizar todos os tickets enviados com o seu e-mail.</p>
		</div>
	</div>
</div>