<h2>Erreur 404</h2>
<p>
    Désolé, la page que vous cherchez a disparu dans les ténèbres...
</p>
<?php if (Conf::$debugLvl == 'debug'): ?>
    <p class="alert alert-danger">
        <?= $msg ?>...
    </p>
<?php endif ?>
