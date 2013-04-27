<div class="page-header">
	<h2>Visualizar Ticket #<?= $id ?></h2>
</div>

<?php if(count($model)): ?>
	<h3><?= $model[0]->Subject ?></h3>
	
	<div class="well">
		<div class="row-fluid">
			<div class="span3">
				<div class=""><b>Enviado</b></div>
				<p><?= date('d/m/Y H:i:s', strtotime($model[0]->Date)) ?></p>
			</div>
			<div class="span3">
				<div class=""><b>Departamento</b></div>
				<p><?= date('d/m/Y H:i:s', strtotime($model[0]->Date)) ?></p>
			</div>
			<div class="span3">
				<div class=""><b>Prioridade</b></div>
				<p><?= date('d/m/Y H:i:s', strtotime($model[0]->Date)) ?></p>
			</div>
			<div class="span3">
				<div class=""><b>Status</b></div>
				<p><?= date('d/m/Y H:i:s', strtotime($model[0]->Date)) ?></p>
			</div>
		</div>
	</div>

	<div class="btn-controls">
		<div class="right">
			<a href="~/" class="btn">Voltar</a>
			<a href="~/" class="btn btn-danger">Fechar</a>
		</div>
	</div>
	
	<div class="topic-view">
	<?php foreach($model as $ticket): ?>
		<div class="row-fluid">
			<div class="span3">
				<div class="topic-profile">
					<img src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim($ticket->Email))) ?>?s=50&d=mm" alt="<?= $ticket->Name ?>" />
					<div class="topic-profile-info">
						<div><b><?= $ticket->Name ?></b></div>
					</div>
				</div>
			</div>
			<div class="span9">
				<div class="topic-content">
					<p class="muted"><small>Postando em <?= date('d/m/Y H:i:s', strtotime($ticket->Date)) ?></small></p>
					<p><?= $ticket->Message ?></p>
				</div>
			</div>
		</div>
	<?php endforeach ?>
	</div>

	<div class="btn-controls">
		<div class="right">
			<a href="~/forum/reply/<?= $model->Id ?>" class="btn btn-primary">Responder</a>
			<a href="~/forum/new/<?= $model->CategoryId ?>" class="btn">Novo</a>
		</div>
	</div>
	
	<form method="post" action="~/ticket/reply/<?php echo $model[0]->IdParent ? $model[0]->IdParent : $model[0]->Id ?>" enctype="multipart/form-data">
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
<?php else: ?>
	<p>Ticket não encontrado!</p>
<?php endif ?>