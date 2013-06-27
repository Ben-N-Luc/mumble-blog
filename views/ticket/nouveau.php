<h2 class="pull-left">Contact</h2>
<a href="<?= url('ticket') ?>" class="btn btn-info pull-right">Accéder à vos tickets déjà existants</a>
<div class="clearfix"></div>
<div>
	<form action="<?php echo url(REQUEST_URI) ?>" method="post" class="parsley">
		<div class="control-group">
			<label for="subject">Sujet</label>
			<input type="text" data-required="true" name="subject" id="subject">
		</div>
		<div class="control-group">
			<label for="type">Type de demande</label>
			<select name="type" id="type" data-required="true" data-trigger="change">
				<option value="">Choissisez une option...</option>
				<?php foreach(Conf::$ticketCategories as $k => $v): ?>
					<option value="<?= $k ?>"><?= $v['text'] ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="control-group">
			<label for="msg">Contenu du message</label>
			<textarea name="msg" id="msg" rows="8" class="input-xxlarge" data-required="true"></textarea>
		</div>
		<div class="actions">
			<input type="submit" class="btn">
		</div>
	</form>
</div>
