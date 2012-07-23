<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
    <?php endif; ?>

    <?php if(is_array($this->Versandkosten)): foreach($this->Versandkosten as $versandzonen): ?>
    <h2><?php echo $versandzonen['bezeichnung']; ?><?php if($versandzonen['laender']): ?> <span class="laender">(<?php echo $versandzonen['laender']; ?>)</span><?php endif; ?></h2>
    <?php if(is_array($versandzonen['zahlungsarten'])): foreach($versandzonen['zahlungsarten'] as $zahlungsarten): ?>
    <h3><?php echo $zahlungsarten['bezeichnung']; ?></h3>
    <?php if($zahlungsarten['infotext']): ?>
    <div class="infotext">
    <?php echo $zahlungsarten['infotext']; ?>
    </div>
    <?php endif; ?>
    <?php if(is_array($zahlungsarten['versandkosten'])): foreach($zahlungsarten['versandkosten'] as $versandkosten): ?>
    <p>ab <?php echo $versandkosten['ab_einkaufspreis']; ?> <?php echo $this->Symbol; ?> Einkaufwert <?php echo $versandkosten['preis']; ?> EUR Versandkosten</p>
    <?php endforeach; endif; ?>
    <?php endforeach; endif; ?>
    <?php endforeach; endif; ?>
</div>