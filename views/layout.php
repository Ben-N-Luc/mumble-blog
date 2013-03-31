<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<script type="text/javascript" src="<?php echo url('js/libs.js') ?>"></script>
		<script type="text/javascript" src="<?php echo url('js/main.js') ?>"></script>
		<style type="text/css">
			<?php echo $this->Css ?>
		</style>
		<title><?php echo (isset($title_for_layout)) ? $title_for_layout . ' - Mumble blog' : "Mumble blog"; ?></title>
	</head>
	<body>
		<div class="global">
			<?php echo $this->nav(); ?>
			<div class="content">
				<header>
					<h1><a href="<?php echo url() ?>">Mumble blog</a></h1>
				</header>
				<?php echo (isset($this)) ? $this->Session->flash() : ''; ?>
				<div>

					<?php echo $content_for_layout; ?>

				</div>
			</div>
			<aside>
				<h3>Viewer</h3>
				<?php echo $this->viewer(); ?>
				<div class="clearfix"></div>
			</aside>
			<div class="clearfix"></div>
			<footer>
				<div class="pull-left">
					&copy; Ben et Luc's 2013.
				</div>
				<div class="pull-right">
					En partenariat avec <a href="http://wtgeek.be" target="_blank">WTGeek</a>
					et <a href="http://wtlink.be" target="_blank">WTLink</a>.
				</div>
				<div class="clearfix"></div>
			</footer>
		</div>
	</body>
</html>