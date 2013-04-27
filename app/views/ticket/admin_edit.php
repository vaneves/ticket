<form method="post" action="" enctype="multipart/form-data">
	<div>
		<label>Prioridade</label>
		<?php echo Form::select('Priority', array('Baixa','Média','Alta'), $model->Priority) ?>
	</div>
	<div>
		<label>Mensagem</label>
		<?php echo Form::textarea('Message', $model->Message) ?>
	</div>
	<div>
		<label>Nota</label>
		<?php echo Form::textarea('Note', $model->Note) ?>
	</div>
	<div>
		<label>Anexo</label>
		<input type="file" name="File" />
	</div>
	<input type="submit" value="Salvar" />
</form>