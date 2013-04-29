<div class="row-fluid">
	<div class="span8">
		<div class="thumbnail">
			<h2>Visualizar Ticket</h2>

			<form method="post" action="">
				<?= BForm::input('Número', 'Id', $model->Id, 'span10') ?>
				<?= BForm::input('E-mail', 'Email', $model->Email, 'span10') ?>

				<button type="submit" class="btn btn-primary">Visualizar</button>
			</form>
		</div>
	</div>
	<div class="span4">
		<div class="thumbnail">
			<h2>Acesso Interno</h2>

		</div>
	</div>
</div>