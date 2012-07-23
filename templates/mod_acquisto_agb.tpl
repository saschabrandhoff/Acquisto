<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?> - AGB</<?php echo $this->hl; ?>>
    <?php endif; ?>

    <div class="contaoShop_agb contaoShop_text">
        <?php echo $this->AGB; ?>
    </div>
</div>