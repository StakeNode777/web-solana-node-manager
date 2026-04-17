<?php

use yii\helpers\BaseUrl;

$baseUrl = BaseUrl::base();

?>
<div class="project-list">
    <?php foreach($projects as $p) : ?>
    <div class="project-container <?=isset($p['extra_css_class']) ? $p['extra_css_class'] : ''?>" title="<?=isset($p['info']) && $p['info'] ? $p['info'] : ""?>">                            
        <div class="project-image">
            <img src="<?=$baseUrl?>/images/crypto-projects/<?=$p['img']?>" alt="<?=$p['title']?>">
        </div>
        <div class="project-title"><?=$p['title']?></div>
        <div class="project-links">
            <?php if (isset($p['stake']) && $p['stake']) : ?><a class="stake-now" href="<?=$p['stake']?>">Stake Now</a> <?php endif ?>
            <?php if (isset($p['more']) && $p['more']) : ?><a href="<?=$p['more']?>">Read More</a> <?php endif ?>            
        </div> 
        <?php if (isset($p['info']) && $p['info']) : ?>
        <div class="custom-tooltip"><?=$p['info']?></div>
        <?php endif ?>
    </div> 
    <?php endforeach ?>
</div>
