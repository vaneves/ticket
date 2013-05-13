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
			<?php if($model[0]->Status != 3): ?>
			<a href="~/admin/ticket/start/<?= $id ?>" class="btn btn-inverse"><i class="icon-time icon-white"></i> Iniciar</a>
			<?php elseif($model[0]->Status == 3): ?>
			<a href="#modal-timer" class="btn btn-inverse" role="button" data-toggle="modal"><i class="icon-time icon-white"></i> Parar</a>
			<?php endif ?>
			<?php if($model[0]->Status != 2): ?>
				<a href="~/admin/ticket/close/<?= $id ?>" class="btn btn-danger"><i class="icon-ban-circle icon-white"></i> Fechar</a>
			<?php else: ?>
				<a href="~/admin/ticket/open/<?= $id ?>" class="btn">Abrir</a>
			<?php endif ?>
			<a href="~/admin/ticket/delete/<?= $id ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Excluir</a>
		</div>
	</div>

	<div class="topic-view">
	<?php $me = Session::get('user') ?>
	<?php foreach($model as $ticket): ?>
		<div class="row-fluid line <?= $ticket->Email != $me->Email ? 'response' : '' ?>">
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
					
					<?php if($ticket->Note): ?>
					<div class="alert alert-info"><?= $ticket->Note ?></div>
					<?php endif ?>
					
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
		<div class="left">
			<?php if(Auth::is('admin','employee')): ?>
			<a href="#modal-add-timer" class="btn" role="button" data-toggle="modal"><i class="icon icon-time"></i> Add Tempo</a>
			<?php endif ?>
		</div>
		<div class="right">
			<?php if($model[0]->Status != 2): ?>
				<a href="~/admin/ticket/close/<?= $id ?>" class="btn btn-danger"><i class="icon-ban-circle icon-white"></i> Fechar</a>
			<?php else: ?>
				<a href="~/admin/ticket/open/<?= $id ?>" class="btn">Abrir</a>
			<?php endif ?>
			<a href="~/admin/ticket/delete/<?= $id ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Excluir</a>
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
	<form method="post" action="~/admin/ticket/reply/<?= $model[0]->Id ?>" enctype="multipart/form-data">
		<fieldset>
			<legend>Responder</legend>
			<div class="row-fluid">
				<div class="span8">
					<label>Mensagem</label>
					<textarea rows="7" name="Message" id="Message" class="span12"></textarea>
				</div>
				<div class="span4">
					<label>Notas</label>
					<textarea rows="7" name="Note" id="Note" class="span12"></textarea>
					<span class="muted">As notas <b>não</b> serão visualizadas pelo cliente.</span>
				</div>
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
	<?php endif ?>
</div>

<div id="modal-timer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-timer-label" aria-hidden="true">
	<form method="post" action="~/admin/ticket/stop/<?= $id ?>">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="modal-timer-label">Cronometar Tempo</h3>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span12">
					<label>Descrição</label>
					<textarea rows="4" name="Description" id="Description" class="span12"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-primary" type="submit">Salvar</button>
		</div>
	</form>
</div>
<div id="modal-add-timer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-add-timer-label" aria-hidden="true">
	<form method="post" action="~/admin/ticket/add-time/<?= $id ?>">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="modal-add-timer-label">Adicionar Tempo</h3>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span6">
					<label>Data Inicial</label>
					<input type="text" name="StartDate" class="span12" value="<?= date('Y-m-d H:i:s') ?>">
				</div>
				<div class="span6">
					<label>Data Final</label>
					<input type="text" name="EndDate" class="span12" value="<?= date('Y-m-d H:i:s') ?>">
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<label>Descrição</label>
					<textarea rows="4" name="Description" id="Description" class="span12"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			<button class="btn btn-primary" type="submit">Salvar</button>
		</div>
	</form>
</div>