<div class="row-fluid">
	<div class="page-header">
		<h2><?= $model[0]->Subject ?></h2>
	</div>
	<div class="well well-small">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span3 status">
					<div class=""><b>Criado</b></div>
					<div><?= date('d/m/Y H:i:s', strtotime($model[0]->Date)) ?></div>
				</div>
				<div class="span3 status">
					<div class=""><b>Tempo</b></div>
					<div><?= isset($timer->Time) ? $timer->Time : '00:00:00' ?></div>
				</div>
				<div class="span3 status">
					<div class=""><b>Prioridade</b></div>
					<?php $priority = array('Baixa','Média','Alta') ?>
					<div><?= $priority[$model[0]->Priority] ?></div>
				</div>
				<div class="span3 status">
					<div class=""><b>Status</b></div>
					<div><span class="label <?= Label::status_class($model[0]->Status) ?>"><?= Label::status($model[0]->Status, true) ?></span></div>
				</div>
			</div>
		</div>
	</div>

	<div class="btn-controls">
		<div class="left">
			<h3>Ticket #<?= $id ?></h3>
		</div>
		<div class="right">
			<?php if($model[0]->Status != 2): ?>
			<a href="~/ticket/close/<?= $id ?>" class="btn btn-danger"><i class="icon-ban-circle icon-white"></i> Fechar</a>
			<?php endif ?>
		</div>
	</div>

	<div class="topic-view">
	<?php foreach($model as $ticket): ?>
		<div class="row-fluid line <?= $ticket->Email != $model[0]->Email ? 'response' : '' ?>">
			<div class="span3">
				<div class="topic-profile">
					<img src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim($ticket->Email))) ?>?s=50&d=mm" alt="<?= $ticket->Name ?>" class="img-polaroid hidden-phone" />
					<div class="topic-profile-info">
						<div><b><?= $ticket->Name ?></b></div>
					</div>
				</div>
			</div>
			<div class="span9">
				<div class="topic-content">
					<p class="muted"><small>Postando em <?= date('d/m/Y H:i:s', strtotime($ticket->Date)) ?></small></p>
					<p><?= nl2br($ticket->Message) ?></p>

					<?php if($ticket->Attachment): ?>
					<div class="">
						<a href="~/attachment/<?= $ticket->Attachment ?>" target="_blank"><i class="icon-picture"></i> <?= $ticket->Attachment ?></a>
					</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	<?php endforeach ?>
	</div>

	<div class="btn-controls">
		<div class="right">
			<?php if($model[0]->Status != 2): ?>
				<a href="~/ticket/close/<?= $id ?>" class="btn btn-danger"><i class="icon-ban-circle icon-white"></i> Fechar</a>
			<?php endif ?>
		</div>
	</div>
	
		<?php if(count($timers)): ?>
	<h4>Logs de Tempo</h4>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Data</th>
				<th>Descrição</th>
				<th>Início</th>
				<th>Fim</th>
				<th>Tempo</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($timers as $t): ?>
			<tr>
				<td><?= date('d/m/Y', strtotime($t->StartDate)) ?></td>
				<td><?= $t->Description ?></td>
				<td><?= date('H:i', strtotime($t->StartDate)) ?></td>
				<td><?= date('H:i', strtotime($t->EndDate)) ?></td>
				<td><?= Timer::calcToString(strtotime($t->StartDate), strtotime($t->EndDate)) ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<?php endif ?>

	<?php if($model[0]->Status != 2): ?>
	<form method="post" action="~/ticket/reply/<?= $model[0]->Id ?>" enctype="multipart/form-data">
		<fieldset>
			<legend>Responder</legend>
			<div class="row-fluid">
				<div class="span4">
					<label>Nome</label>
					<input type="text" value="<?= $model[0]->Name ?>" disabled class="span12">
				</div>
				<div class="span4">
					<label>E-mail</label>
					<input type="text" value="<?= $model[0]->Email ?>" disabled class="span12">
				</div>
			</div>
			<div class="row-fluid">
				<label>Mensagem</label>
				<textarea rows="7" name="Message" class="span8"></textarea>
			</div>
			<div class="row-fluid">
				<label>Anexo (imagem)</label>
				<input type="file" name="File" />
			</div>
			<br>
			<div class="controls-row">
				<button type="submit" class="btn btn-primary">Enviar</button>
			</div>
		</fieldset>
	</form>
	<?php else: ?>
	<div class="alert alert-block fade in">
		<h4 class="alert-heading">Ticket fechado!</h4>
		<p>Este ticket está fechado, mas caso necessite contactar-nos novamente você pode reabri-lo. Porém, se o problema tratar sobre outro assunto, por favor, abra um novo ticket.</p>
		<p>
			<a class="btn btn-primary" href="~/ticket/open/<?= $id ?>">Responder Este</a>
			<a class="btn" href="~/ticket/add">Criar Novo</a>
		</p>
	</div>
	<?php endif ?>
</div>