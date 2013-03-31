<section class="middle">
	<h2>Inscription</h2>
	<form action="<?php echo url(REQUEST_URI) ?>" method="post">
		<label for="username">Pseudo</label>
		<input type="text" id="username" name="username" required>
		<label for="email">Email</label>
		<input type="email" id="email" name="email" required>
		<label for="pwd">Mot de passe</label>
		<input type="password" id="pwd" name="pwd" required>
		<div class="actions">
			<input type="submit" value="Valider" class="btn">
		</div>
	</form>
</section>
<section class="middle">
	<h2>Connexion</h2>
	<form action="<?php echo url(REQUEST_URI) ?>" method="post">
		<label for="pseudo">Pseudo</label>
		<input type="text" id="pseudo" name="pseudo" required>
		<label for="mdp">Mot de passe</label>
		<input type="password" id="mdp" name="mdp" required>
		<div class="actions">
			<input type="submit" value="Valider" class="btn">
		</div>
	</form>
</section>
<div class="clearfix"></div>