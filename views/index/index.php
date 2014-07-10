<section class="middle">
    <h2><a href="<?= url('posts') ?>">Dernières news</a></h2>
    <?php if (empty($posts)): ?>
        <article>
            Pas de news pour le moment...
        </article>
    <?php endif ?>
    <?php foreach($posts as $post): ?>
        <article>
            <h3>
                <a href="<?= url('posts/view/' . $post->id) ?>"><?= $post->title ?></a>
                <small><?= date('d-m-Y H:i', strtotime($post->date)) ?></small>
            </h3>
            <p>
                <?= substr(strip_tags($post->content, '<a>'), 0, 500) ?>...
            </p>
            <div class="actions">
                <a href="<?= url('posts/view/' . $post->id) ?>" class="more">Voir plus...</a>
            </div>
        </article>
    <?php endforeach ?>
</section>
<section class="middle">
    <h2>Liens utiles</h2>
    <article>
        <h3>Lorem</h3>
        <ul class="link-list">
            <li><a href="">Télécharger Mumble</a></li>
            <li><a href="">Aider mumble à se développer</a></li>
            <li><a href="">Lien 3</a></li>
            <li><a href="">Lien 4</a></li>
            <li><a href="">Lien 5</a></li>
        </ul>
    </article>
</section>
<div class="clearfix"></div>
<p>
    <!--<audio autoplay loop controls>
        <source src="<?php echo url('sounds/nyan.ogg') ?>" type="audio/ogg">
        <source src="<?php echo url('sounds/nyan.mp3') ?>" type="audio/mpeg">
        Votre navigateur ne supporte pas le son...
    </audio>-->
</p>
