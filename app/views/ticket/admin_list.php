<div class="row-fluid">
	<div class="page-header">
		<h2>Lista dos Tickets</h2>
	</div>
	<div class="btn-controls">
		<div class="right">
			<?= Pagination::create('admin/ticket/list', $model->Count, $p, 20) ?>
		</div>
	</div>
	
	<?php if(count($model->Data)): ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php $t = $t == 'DESC' ? 'ASC': 'DESC' ?>
				<th class="hidden-phone">Autor</th>
				<th><a href="~/admin/ticket/list/<?= $p ?>/status/<?= $t ?>/" class="hidden-phone <?= $o == 'status' ? $t : '' ?>">Status</a></th>
				<th><a href="~/admin/ticket/list/<?= $p ?>/subject/<?= $t ?>/" class="<?= $o == 'subject' ? $t : '' ?>">Assunto</a></th>
				<th><a href="~/admin/ticket/list/<?= $p ?>/priority/<?= $t ?>/" class="<?= $o == 'priority' ? $t : '' ?>">Prioridade</a></th>
				<th><a href="~/admin/ticket/list/<?= $p ?>/date/<?= $t ?>/" class="<?= $o == 'date' ? $t : '' ?>">Data</a></th>
			</tr>
		</thead>
		<tbody>
			<?php $priority = array('<span class="badge badge-info">Baixa</span>','<span class="badge badge-warning">Média</span>','<span class="badge badge-important">&nbsp; Alta &nbsp;</span>'); ?>
			<?php foreach($model->Data as $ticket): ?>
			<tr>
				<td class="hidden-phone"><img src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim($ticket->Email))) ?>?s=32&d=mm" alt="<?= $ticket->Name ?>" class="img-polaroid" /></td>
				<td><span class="label <?= Label::status_class($ticket->Status) ?>"><?= Label::status($ticket->Status, true) ?></span></td>
				<td>
					<a href="~/admin/ticket/view/<?= $ticket->Id ?>"><?= $ticket->Subject ?></a>
					<div class="muted hidden-phone">
						<small>
							<?= $ticket->Name ?>
							<span>(<?= $ticket->Email ?>)</span>
						</small>
					</div>
				</td>
				<td><?= $priority[$ticket->Priority] ?></td>
				<td><?= date('d/m H:i', strtotime($ticket->Date)) ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<div class="btn-controls">
		<div class="right">
			<?= Pagination::create('admin/ticket/list', $model->Count, $p, 20) ?>
		</div>
	</div>
	<?php else: ?>
		<p>Você não criou tickets ainda.</p>
	<?php endif ?>
</div>