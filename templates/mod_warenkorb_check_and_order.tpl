<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?> - Abschlie&szlig;en</<?php echo $this->hl; ?>>
    <?php endif; ?>

    <div class="steps">
        <ul>
            <li class="first"><a href="<?php echo $this->WarenkorbUrl; ?>">Warenkorb</a></li>
            <li><a href="<?php echo $this->WarenkorbUrl; ?>?do=customer">Kundendaten</a></li>
            <li><a href="<?php echo $this->WarenkorbUrl; ?>?do=payment-and-shipping">Zahlung und Versand</a></li>
            <li><a href="<?php echo $this->WarenkorbUrl; ?>?do=agb">AGB</a></li>
            <li class="last active">Kasse</li>
        </ul>
    </div>


    <p>Bitte &uuml;berpr&uuml;fen Sie Ihre Eingaben. Sie k&ouml;nnen Ihre Eingaben durch einen Klick auf den entsprechenden Schritt korrigieren. Wenn alle Eingaben richtig sind, klicken Sie den Button "Bestellung absenden und Vorgang beenden".</p>


    <div class="basket">
        <fieldset>
            <legend>Gesamt&uuml;bersicht</legend>

            <table cellspacing="0" cellpadding="0">
                <tr>
                    <th class="title_row">Artikel</th>
                    <th class="menge_row">Menge</th>
                    <th class="price_row">Preis</th>
                    <th class="summe_row">Summe</th>
                </tr>
                <?php if($this->Produktliste): ?>
                <?php foreach($this->Produktliste as $key => $item): ?>
                <tr>
                    <td class="title_row"><?php echo $item['bezeichnung']; ?></td>
                    <td class="menge_row"><?php echo $item['menge']; ?></td>
                    <td class="price_row"><?php echo sprintf("%01.2f", $item['preis']); ?> EUR</td>
                    <td class="summe_row"><?php echo sprintf("%01.2f", $item['summe']); ?> EUR</td>
                </tr>
                <?php endforeach; ?>
                <?php if($this->Gutscheine): foreach($this->Gutscheine as $Gutschein): ?>
                <tr>
                    <td class="title_row" colspan="3">Gutschein: <?php echo $Gutschein->code; ?></td>
                    <td class="summe_row">-<?php echo $Gutschein->preis; ?> EUR</td>
                </tr>
                <?php endforeach; endif; ?>
                <?php if($this->Steuern): ?>
                <?php foreach($this->Steuern as $item): ?>
                <tr>
                    <td colspan="3">enth. MwSt. <?php echo $item['satz']; ?>% auf <?php echo sprintf("%01.2f", $item['summe']); ?> EUR:</td>
                    <td class="summe_row" align="right"><?php echo sprintf("%01.2f", $item['wert']); ?> EUR</td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td colspan="3">Gesamtpreis:</td>
                    <td class="summe_row" align="right"><?php echo sprintf("%01.2f", $this->Gesamtpreis); ?> EUR</td>
                </tr>
                <tr>
                    <td colspan="3">Versandkosten (<?php echo $this->Versandzone['bezeichnung']; ?>):</td>
                    <td class="summe_row" align="right"><?php echo sprintf("%01.2f", $this->Versandpreis); ?> EUR</td>
                </tr>
                <tr>
                    <td colspan="3">Endpreis inkl. Versand:</td>
                    <td class="summe_row" align="right"><?php echo sprintf("%01.2f", $this->Endpreis); ?> EUR</td>
                </tr>
                <?php endif; ?>
            </table>
        </fieldset>

        <div class="paymentForm">
            <fieldset>
                <legend>Zahlung per <?php echo $this->Zahlungsart['bezeichnung']; ?></legend>
                <div class="formbody">
                    <?php echo $this->paymentModule; ?>
                </div>
            </fieldset>
        </div>
    </div>
</div>