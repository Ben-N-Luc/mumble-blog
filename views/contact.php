<h2>Contact</h2>
<div>
	<form action="<?php echo url(REQUEST_URI) ?>" method="post" class="parsley">
		<div class="control-group">
			<label for="mail">Adresse Email</label>
			<input type="email" data-required="true" name="mail" id="mail" <?php echo ($mail) ? ' value="' . $mail . '"' : '' ?>>
		</div>
		<div class="control-group">
			<label for="pseudo">Pseudo utilisé sur le serveur</label>
			<input type="text" data-required="true" name="pseudo" id="pseudo">
		</div>
		<div class="control-group">
			<label for="subject">Sujet</label>
			<input type="text" data-required="true" name="subject" id="subject">
		</div>
		<div class="control-group">
			<label for="type">Type de demande</label>
			<select name="type" id="type" data-required="true" data-trigger="change">
				<option value="">Choissisez une option...</option>
				<option value="channel">Demande de salon</option>
				<option value="tech">Problème technique</option>
				<option value="renseignement">Renseignement</option>
				<option value="plainte">Plainte</option>
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