<div class="row-fluid">
	<form method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>Criar Ticket</legend>
			<div class="row-fluid">
				<div class="span4">
					<label>Nome</label>
					<input type="text" value="<?= Session::get('user')->Name ?>" disabled class="span12">
				</div>
				<div class="span4">
					<label>E-mail</label>
					<input type="text" value="<?= Session::get('user')->Email ?>" disabled class="span12">
				</div>
			</div>
			<?= BForm::input('Assunto', 'Subject', $model->Subject, 'span8') ?>
			<?= BForm::select('Prioridade', 'Priority', array('Baixa','Média','Alta'), $model->Priority, 'span4') ?>
			<div class="control-group">
				<label class="control-label" for="Message">Mensagem</label>
				<textarea rows="7" name="Message" id="Message" class="span8"></textarea>
			</div>
			<div class="control-group">
				<label class="control-label" for="File">Anexo</label>
				<input type="file" name="File" id="File" />
			</div>
			<br>
			<div class="controls-row">
				<button type="submit" class="btn btn-primary">Enviar</button>
				<button type="button" class="btn">Cancelar</button>
			</div>
		</fieldset>
	</form>
</div>