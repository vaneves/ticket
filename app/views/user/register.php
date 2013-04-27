<form method="post" action="">
	<div>
		<label>Nome</label>
		<?php echo Form::input('Name', $model->Name) ?>
	</div>
	<div>
		<label>E-mail</label>
		<?php echo Form::input('Email', $model->Email) ?>
	</div>
	<div>
		<label>Senha</label>
		<?php echo Form::password('Password') ?>
	</div>
	<div>
		<label>Confirmar Senha</label>
		<?php echo Form::password('Confirm') ?>
	</div>
	<input type="submit" value="Cadastrar" />
</form>