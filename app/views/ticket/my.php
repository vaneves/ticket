<div class="row-fluid">
	
	<div class="btn-controls">
		<div class="left">
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
				<th>Número</th>
				<?php $t = $t == 'DESC' ? 'ASC': 'DESC' ?>
				<th><a href="~/ticket/list/<?= $p ?>/status/<?= $t ?>/" class="<?= $o == 'status' ? $t : '' ?>">Status</a></th>
				<th><a href="~/ticket/list/<?= $p ?>/subject/<?= $t ?>/" class="<?= $o == 'subject' ? $t : '' ?>">Assunto</a></th>
				<th><a href="~/ticket/list/<?= $p ?>/date/<?= $t ?>/" class="<?= $o == 'date' ? $t : '' ?>">Data</a></th>
			</tr>
		</thead>
		<tbody>
			<?php $status = array('<span class="label label-warning">Aberto</span>','<span class="label label-success">Respondido</span>','<span class="label">Fechado</span>'); ?>
			<?php foreach($model->Data as $ticket): ?>
			<tr>
				<td>#<?= $ticket->Id ?></td>
				<td><?= $status[$ticket->Status] ?></td>
				<td>
					<a href="~/ticket/view/<?= $ticket->Id ?>"><?= $ticket->Subject ?></a>
				</td>
				<td><?= date('d M Y H:i', strtotime($ticket->Date)) ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<div class="btn-controls">
		<div class="left">
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