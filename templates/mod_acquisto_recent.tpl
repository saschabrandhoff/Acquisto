<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?=$this->hl;?>><?=$this->headline;?></<?=$this->hl;?>>
    <?php endif; ?>

    <?php if(is_array($this->Viewed)): foreach($this->Viewed as $item): ?>
    <div class="<?=$item['css'];?>element">
        <?php if (!$item['preview_image']->addBefore): ?>
        <div class="text">
            <a href="<?=$item['url'];?>"><?=$item['bezeichnung'];?></a><br>
            <span class="preis"></span>
        </div>
        <?php endif; ?>

        <?php if ($item['preview_image']->addImage): ?>
            <div class="image_container<?php echo $item['preview_image']->floatClass; ?>"<?php if ($item['preview_image']->margin || $item['preview_image']->float): ?> style="<?php echo trim($item['preview_image']->margin . $item['preview_image']->float); ?>"<?php endif; ?>>
            <?php if ($item['preview_image']->href): ?>
            <a href="<?php echo $item['preview_image']->href; ?>"<?php echo $item['preview_image']->attributes; ?> title="<?php echo $item['preview_image']->alt; ?>">
            <?php endif; ?>
                <img src="<?php echo $item['preview_image']->src; ?>"<?php echo $item['preview_image']->imgSize; ?> alt="<?php echo $item['preview_image']->alt; ?>">
            <?php if ($item['preview_image']->href): ?>
            </a>
            <?php endif; ?>
            <?php if ($item['preview_image']->caption): ?>
            <div class="caption"><?php echo $item['preview_image']->caption; ?></div>
            <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($item['preview_image']->addBefore): ?>
        <div class="text">
            <a href="<?=$item['url'];?>"><?=$item['bezeichnung'];?></a><br>
            <span class="preis"><?=$item['preis'];?> EUR</span>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; else: ?>
    <div class="no_recentViews">
        Keine Produkte in der Liste.
    </div>
    <?php endif; ?>
</div>