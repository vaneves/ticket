<form method="post" action="" enctype="multipart/form-data">
	<div>
		<label>Nome Completo</label>
		<?php echo Form::input('Name', $model->Name) ?>
	</div>
	<div>
		<label>E-mail</label>
		<?php echo Form::input('Email', $model->Email) ?>
	</div>
	<div>
		<label>Assunto</label>
		<?php echo Form::input('Subject', $model->Subject) ?>
	</div>
	<div>
		<label>Prioridade</label>
		<?php echo Form::select('Priority', array('Baixa','Média','Alta'), $model->Priority) ?>
	</div>
	<div>
		<label>Mensagem</label>
		<?php echo Form::textarea('Message', $model->Message) ?>
	</div>
	<div>
		<label>Anexo</label>
		<input type="file" name="File" />
	</div>
	<input type="submit" value="Salvar" />
</form>