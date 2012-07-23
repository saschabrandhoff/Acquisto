<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
    <?php endif; ?>

    <p>
    <?php if($this->Menge): ?>
    Es befinden sich <span class="menge"><?php echo $this->Menge; ?> Produkte</span> im Warenkorb
    <?php else: ?>
    Keine Produkte im Warenkorb
    <?php endif; ?>
    Warenwert <span class="preis"><?php echo sprintf("%01.2f", $this->Endpreis); ?> EUR</span> inkl. MwSt.
    </p>

    <p>
        <a href="<?php echo $this->WarenkorbUrl; ?>">Warenkorb anzeigen</a>
    </p>
</div>