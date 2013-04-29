<div class="row-fluid">
	
	<div class="btn-controls">
		<div class="right">
			<?= Pagination::create('admin/ticket/list-status', $model->Count, $p, 20) ?>
		</div>
	</div>
	
	<?php if(count($model->Data)): ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php $t = $t == 'DESC' ? 'ASC': 'DESC' ?>
				<th>Autor</th>
				<th><a href="~/admin/ticket/list-status/<?= $p ?>/status/<?= $t ?>/" class="<?= $o == 'status' ? $t : '' ?>">Status</a></th>
				<th><a href="~/admin/ticket/list-status/<?= $p ?>/subject/<?= $t ?>/" class="<?= $o == 'subject' ? $t : '' ?>">Assunto</a></th>
				<th><a href="~/admin/ticket/list-status/<?= $p ?>/priority/<?= $t ?>/" class="<?= $o == 'priority' ? $t : '' ?>">Prioridade</a></th>
				<th><a href="~/admin/ticket/list-status/<?= $p ?>/date/<?= $t ?>/" class="<?= $o == 'date' ? $t : '' ?>">Data</a></th>
			</tr>
		</thead>
		<tbody>
			<?php $status = array('<span class="label label-warning">Aberto</span>','<span class="label label-success">Respondido</span>','<span class="label">Fechado</span>'); ?>
			<?php $priority = array('<span class="badge badge-info">Baixa</span>','<span class="badge badge-warning">Média</span>','<span class="badge badge-important">&nbsp; Alta &nbsp;</span>'); ?>
			<?php foreach($model->Data as $ticket): ?>
			<tr>
				<td><img src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim($ticket->Email))) ?>?s=32&d=mm" alt="<?= $ticket->Name ?>" class="img-polaroid" /></td>
				<td><?= $status[$ticket->Status] ?></td>
				<td>
					<a href="~/admin/ticket/view/<?= $ticket->Id ?>"><?= $ticket->Subject ?></a>
					<div class="muted"><small><?= $ticket->Name ?> (<?= $ticket->Email ?>)</small></div>
				</td>
				<td><?= $priority[$ticket->Priority] ?></td>
				<td><?= date('d M Y H:i', strtotime($ticket->Date)) ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<div class="btn-controls">
		<div class="right">
			<?= Pagination::create('admin/ticket/list-status', $model->Count, $p, 20) ?>
		</div>
	</div>
	<?php else: ?>
		<p>Você não criou tickets ainda.</p>
	<?php endif ?>
</div>