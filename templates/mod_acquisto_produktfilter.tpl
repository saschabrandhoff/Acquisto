<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
    <?php endif; ?>

    <?php if($this->Hersteller): ?>
    <ul class="level_0">
        <li>Hersteller
            <ul class="level_1">
                <?php foreach($this->Hersteller as $Hersteller): ?>
                <li<?php if($Hersteller->css): ?> class="<?php echo $Hersteller->css; ?>"<?php endif; ?>><a href="<?php echo $Hersteller->url; ?>"><?php echo $Hersteller->bezeichnung; ?> (<?php echo $Hersteller->counter; ?>)</a></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>

    <?php endif; ?>
    <?php if($this->Preise): ?>
    <ul class="level_0">
        <li>Preis
            <ul class="level_1">
                <?php foreach($this->Preise as $Preis): ?>
                <li<?php if($Preis->css): ?> class="<?php echo $Preis->css; ?>"<?php endif; ?>><a href="<?php echo $Preis->url; ?>"><?php echo $Preis->von; ?> - <?php echo $Preis->bis; ?> EUR  (<?php echo $Preis->counter; ?>)</a></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    <?php endif; ?>
    <?php if($this->Clear): ?>
    <p><a href="<?php echo $this->Clear; ?>">Filter aufheben</a></p>
    <?php endif; ?>
</div>