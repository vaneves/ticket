<?php if(count($model->Data)): ?>
<table>
	<thead>
		<tr>
			<th>Número</th>
			<th><a href="~/admin/ticket/list/<?php echo $s ?>/<?php echo $p ?>/date/<?php echo $r ?>/" class="<?php echo $o == 'date' ? $t : '' ?>">Data</a></th>
			<th><a href="~/admin/ticket/list/<?php echo $s ?>/<?php echo $p ?>/subject/<?php echo $r ?>/" class="<?php echo $o == 'subject' ? $t : '' ?>">Assunto</a></th>
			<th><a href="~/admin/ticket/list/<?php echo $s ?>/<?php echo $p ?>/priority/<?php echo $r ?>/" class="<?php echo $o == 'priority' ? $t : '' ?>">Prioridade</a></th>
			<th><a href="~/admin/ticket/list/<?php echo $s ?>/<?php echo $p ?>/status/<?php echo $r ?>/" class="<?php echo $o == 'status' ? $t : '' ?>">Status</a></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($model->Data as $ticket): ?>
		<tr>
			<td>#<?php echo $ticket->Id ?></td>
			<td><?php echo date('d/m/Y H:i:s', strtotime($ticket->Date)) ?></td>
			<td><?php echo $ticket->Subject ?></td>
			<td><?php echo $ticket->Priority ?></td>
			<td><?php echo $ticket->Status ?></td>
			<td><a href="~/admin/ticket/view/<?php echo $ticket->Id ?>">[ ver ]</a></td>
		<tr>
		<?php endforeach ?>
	</tbody>
<table>
<ul class="pagination">
	<?php for($i = 1; $i <= ceil($model->Count / 20); $i++): ?>
		<li><a href="~/admin/ticket/list/<?php echo $s ?>/<?php echo $p ?>/<?php echo $o ?>/<?php echo $r ?>/" class="<?php echo $p == $i ? 'actual' : '' ?>"><?php echo $i ?></a></li>
	<?php endfor ?>
</ul>
<?php else: ?>
	<p>Você não criou tickets ainda.</p>
<?php endif ?>