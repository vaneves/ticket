<?php if(count($model)): ?>
	<div class="page-header">
		<h1><?= $model[0]->Subject ?></h1>
	</div>
	<?php foreach($model as $ticket): ?>
	<div>
		<h2><?php echo $ticket->Email ?> <span><?php echo new DateTime($ticket->Date)->format('d/m/Y H:i:s') ?></span></h2>
		<p><?php echo $ticket->Email ?></p>
		<p><?php echo $ticket->Note ?></p>
	</div>
	<?php endforeach ?>
	<form method="post" action="~/ticket/reply/<?php echo $model[0]->IdParent ? $model[0]->IdParent : $model[0]->Id ?>" enctype="multipart/form-data">
		<div>
			<label>Mensagem</label>
			<?php echo Form::textarea('Message', $model->Message) ?>
		</div>
		<div>
			<label>Note</label>
			<?php echo Form::textarea('Note', $model->Note) ?>
		</div>
		<div>
			<label>Anexo</label>
			<input type="file" name="File" />
		</div>
		<input type="submit" value="Salvar" />
	</form>
<?php else: ?>
	<p>Ticket não encontrado!</p>
<?php endif ?>