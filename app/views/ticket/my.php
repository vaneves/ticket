<div class="row-fluid">
	<div class="page-header">
		<h2>Meus Tickets</h2>
	</div>
	<div class="btn-controls">
		<div class="left hidden-phone">
			<a href="~/ticket/add" class="btn btn-primary">Criar Ticket</a>
		</div>
		<div class="right">
			<?= Pagination::create('ticket/list', $model->Count, $p, 20) ?>
		</div>
	</div>
	
	<?php if(count($model->Data)): ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="hidden-phone">Número</th>
				<?php $t = $t == 'DESC' ? 'ASC': 'DESC' ?>
				<th><a href="~/ticket/list/<?= $p ?>/status/<?= $t ?>/" class="<?= $o == 'status' ? $t : '' ?>">Status</a></th>
				<th><a href="~/ticket/list/<?= $p ?>/subject/<?= $t ?>/" class="<?= $o == 'subject' ? $t : '' ?>">Assunto</a></th>
				<th><a href="~/ticket/list/<?= $p ?>/date/<?= $t ?>/" class="<?= $o == 'date' ? $t : '' ?>">Data</a></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($model->Data as $ticket): ?>
			<tr>
				<td class="hidden-phone">#<?= $ticket->Id ?></td>
				<td><span class="label <?= Label::status_class($ticket->Status) ?>"><?= Label::status($ticket->Status, true) ?></span></td>
				<td>
					<a href="~/ticket/view/<?= $ticket->Id ?>"><?= $ticket->Subject ?></a>
				</td>
				<td><?= date('d/m H:i', strtotime($ticket->Date)) ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<div class="btn-controls">
		<div class="left hidden-phone">
			<a href="~/ticket/add" class="btn btn-primary">Criar Ticket</a>
		</div>
		<div class="right">
			<?= Pagination::create('ticket/list', $model->Count, $p, 20) ?>
		</div>
	</div>
	<?php else: ?>
		<p>Você não criou tickets ainda.</p>
	<?php endif ?>
</div>