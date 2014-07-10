<?php var_dump($posts) ?>
<section class="tickets">
    <?php foreach($posts as $post): ?>
        <article>
            <h3><?= $post->title ?></h3>
            <div class="msg">
                <?= $post->content ?>
            </div>
            <div class="infos">
                <ul>
                    <li>
                        <?= $post->date ?>
                    </li>
                </ul>
            </div>
        </article>
    <?php endforeach; ?>
</section>
