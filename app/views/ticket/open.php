<form method="post" action="">
	<div>
		<label>ID</label>
		<?php echo Form::input('Id', $model->Id) ?>
	</div>
	<div>
		<label>E-mail</label>
		<?php echo Form::input('Email', $model->Email) ?>
	</div>
	<input type="submit" value="Salvar" />
</form>