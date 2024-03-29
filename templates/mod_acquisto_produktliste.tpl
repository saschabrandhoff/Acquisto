<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
    <?php endif; ?>

    <?php if($this->Warengruppe['title']): ?>
    <h1><?php echo $this->Warengruppe['title']; ?></h1>
    <?php endif; ?>

    <?php if(!$this->Warengruppe['imageSrc']->addBefore): ?>
        <?php if($this->Warengruppe['beschreibung']): ?>
        <div class="description"><?php echo $this->Warengruppe['beschreibung']; ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($this->Warengruppe['imageSrc']->addImage): ?>
        <div class="image_container<?php echo $this->Warengruppe['imageSrc']->floatClass; ?>"<?php if ($this->Warengruppe['imageSrc']->margin || $this->Warengruppe['imageSrc']->float): ?> style="<?php echo trim($this->Warengruppe['imageSrc']->margin . $this->Warengruppe['imageSrc']->float); ?>"<?php endif; ?>>
        <?php if ($this->Warengruppe['imageSrc']->href): ?>
        <a href="<?php echo $this->Warengruppe['imageSrc']->href; ?>"<?php echo $this->Warengruppe['imageSrc']->attributes; ?> title="<?php echo $this->Warengruppe['imageSrc']->alt; ?>">
        <?php endif; ?>
            <img src="<?php echo $this->Warengruppe['imageSrc']->src; ?>"<?php echo $this->Warengruppe['imageSrc']->imgSize; ?> alt="<?php echo $this->Warengruppe['imageSrc']->alt; ?>">
        <?php if ($this->Warengruppe['imageSrc']->href): ?>
        </a>
        <?php endif; ?>
        <?php if ($this->Warengruppe['imageSrc']->caption): ?>
        <div class="caption"><?php echo $this->Warengruppe['imageSrc']->caption; ?></div>
        <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if($this->Warengruppe['imageSrc']->addBefore): ?>
        <?php if($this->Warengruppe['beschreibung']): ?>
        <div class="description"><?php echo $this->Warengruppe['beschreibung']; ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if($this->Produkte): ?>
    <?php echo $this->Produkte; ?>
    <?php else: ?>
    <div class="no_result">
        <p>Keine Produkte gefunden.</p>
    </div>
    <?php endif; ?>


    <?php if($this->Next OR $this->Prev): ?>
    <div class="pagination">
        <ul>
            <?php if($this->Prev): ?>
            <li class="prev"><a href="<?php echo $this->Prev; ?>">Vorherige Seite</a></li>
            <?php endif; ?>
            <?php if($this->Pages): foreach($this->Pages as $item): ?>
            <li<?php if($item['Class']): ?> class="<?php echo $item['Class']; ?>"<?php endif; ?>><a href="<?php echo $item['Url']; ?>"><?php echo $item['Page']; ?></a></li>
            <?php endforeach; endif; ?>
            <?php if($this->Next): ?>
            <li class="prev"><a href="<?php echo $this->Next; ?>">N&auml;chste Seite</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
    <div style="clear: both;"></div>
</div>