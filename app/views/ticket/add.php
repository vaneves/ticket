<div class="row-fluid">
	<form method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>Criar Ticket</legend>
			<div class="row-fluid">
				<div class="span4">
					<?php if(Auth::is('client')): ?>
						<?= BForm::input('Nome', 'Name', Session::get('user')->Name, 'span12', array('disabled' => '')) ?>
					<?php else: ?>
						<?= BForm::input('Nome', 'Name', $model->Name, 'span12') ?>
					<?php endif ?>
				</div>
				<div class="span4">
					<?php if(Auth::is('client')): ?>
						<?= BForm::input('E-mail', 'Email', Session::get('user')->Email, 'span12', array('disabled' => '')) ?>
					<?php else: ?>
						<?= BForm::input('E-mail', 'Email', $model->Email, 'span12') ?>
					<?php endif ?>
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
			</div>
		</fieldset>
	</form>
</div>