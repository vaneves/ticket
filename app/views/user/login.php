<form method="post" action="">
	<div>
		<label>E-mail</label>
		<?php echo Form::input('Email', $model->Email) ?>
	</div>
	<div>
		<label>Senha</label>
		<?php echo Form::password('Password') ?>
	</div>
	<input type="submit" value="Logar" />
</form>