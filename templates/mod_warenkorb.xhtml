<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?> - &Uuml;bersicht</<?php echo $this->hl; ?>>
    <?php endif; ?>
    <div class="steps">
        <ul>
            <li class="active">Warenkorb</li>
            <li>Kundendaten</li>
            <li>Zahlung und Versand</li>
            <li>AGB</li>
            <li class="last">Kasse</li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="basket">
        <fieldset>
            <legend>Ihr Warenkorb</legend>

            <table cellspacing="0" cellpadding="0">
                <tr>
                    <th class="title_row">Artikel</th>
                    <th class="menge_row">Menge</th>
                    <th class="price_row">Preis</th>
                    <th class="summe_row">Summe</th>
                    <th class="remove_row"></th>
                </tr>
                <?php if($this->Produktliste): ?>
                <?php foreach($this->Produktliste as $key => $item): ?>
                <tr>
                    <td class="title_row"><?php echo $item['bezeichnung']; ?></td>
                    <td class="menge_row"><?php echo $item['menge']; ?></td>
                    <td class="price_row"><?php echo sprintf("%01.2f", $item['preis']); ?> EUR</td>
                    <td class="summe_row"><?php echo sprintf("%01.2f", $item['summe']); ?> EUR</td>
                    <td class="remove_row"><a href="<?php echo $this->WarenkorbUrl; ?>?remove=<?php echo $item['id']; ?>"><img src="/system/modules/acquistoShop/html/icons/delete.png" alt="" border="0" width="" height=""></a></td>
                </tr>
                <?php endforeach; ?>
                <?php if($this->Gutscheine): foreach($this->Gutscheine as $Gutschein): ?>
                <tr>
                    <td class="title_row" colspan="3">Gutschein: <?php echo $Gutschein->code; ?></td>
                    <td class="summe_row">-<?php echo $Gutschein->preis; ?> EUR</td>
                    <td></td>
                </tr>
                <?php endforeach; endif; ?>
                <?php if($this->Steuern): ?>
                <?php foreach($this->Steuern as $item): ?>
                <tr>
                    <td colspan="3">enth. MwSt. <?php echo $item['satz']; ?>% auf <?php echo sprintf("%01.2f", $item['summe']); ?> EUR:</td>
                    <td class="summe_row"><?php echo sprintf("%01.2f", $item['wert']); ?> EUR</td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td colspan="3">Endpreis</td>
                    <td class="summe_row"><?php echo sprintf("%01.2f", $this->Endpreis); ?> EUR</td>
                </tr>
                <?php endif; ?>
            </table>
        </fieldset>

        <form method="post" action="<?php echo $this->WarenkorbUrl; ?>?do=customer">
            <div class="formbody">
                <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
                <input type="hidden" name="REQUEST_TOKEN" value="<?php echo REQUEST_TOKEN; ?>">
                <input type="button" value="&laquo; Weiter Einkaufen" onclick="history.back();">
                <?php if($this->Produktliste): ?>
                <input type="submit" value="Weiter zu Zahlung und Versand &raquo;">
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>