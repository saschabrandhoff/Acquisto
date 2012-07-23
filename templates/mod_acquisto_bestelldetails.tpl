<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
    <?php endif; ?>

    <div class="order_informations">
        <fieldset>
            <legend>Rechnung und Versand</legend>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <b>Rechnungsempf&auml;nger</b><br>
                        <br>
                        <?php echo $this->Kunde->firstname; ?> <?php echo $this->Kunde->lastname; ?><br>
                        <?php echo $this->Kunde->street; ?><br>
                        <?php echo $this->Kunde->postalcode; ?> <?php echo $this->Kunde->city; ?><br>
                    </td>
                    <?php if($this->Kunde->deliver_firstname OR $this->Kunde->deliver_lastname): ?>
                    <td>
                        <b>Versandadresse</b><br>
                        <br>
                        <?php echo $this->Kunde->deliver_firstname; ?> <?php echo $this->Kunde->deliver_lastname; ?><br>
                        <?php echo $this->Kunde->deliver_street; ?><br>
                        <?php echo $this->Kunde->deliver_postalcode; ?> <?php echo $this->Kunde->deliver_city; ?><br>
                    </td>
                    <?php endif; ?>
                    <td>
                        <b>Zahlungsart &amp; Versandkosten</b><br>
                        <br>
                        Zahlungsart: <?php echo $this->Zahlungsart->bezeichnung; ?><br>
                        Versandzone: <?php echo $this->Versandzonen->bezeichnung; ?><br>
                        Versandkosten: <?php echo $this->Bestellungen->versandpreis; ?> EUR<br>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div class="basket">
        <fieldset>
            <legend>Positionen</legend>

            <?php if($this->Elements): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th class="title_row">Bezeichnung</th>
                    <th class="menge_row">Menge</th>
                    <th class="price_row">Preis</th>
                    <th class="summe_row">Summe</th>
                </tr>
                <?php foreach($this->Elements as $Element): ?>
                <tr class="<?php echo $Element->css; ?>">
                    <td class="title_row"><?php echo $Element->bezeichnung; ?></td>
                    <td class="menge_row"><?php echo $Element->menge; ?></td>
                    <td class="price_row"><?php echo $Element->preis; ?> EUR</td>
                    <td class="summe_row"><?php echo $Element->summe; ?> EUR</td>
                </tr>
                <?php endforeach; ?>
                <?php if($this->Gutscheine): ?>
                <?php foreach($this->Gutscheine as $Gutschein): ?>
                <tr>
                    <td class="title_row" colspan="2">Gutschein: <?php echo $Gutschein->code; ?></td>
                    <td class="price_row">-<?php echo $Gutschein->preis; ?> EUR</td>
                    <td class="summe_row">-<?php echo $Gutschein->preis; ?> EUR</td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td class="title_row" colspan="3">Gesamtsumme:</td>
                    <td class="summe_row"><?php echo $this->Endpreise->gesamtsumme; ?> EUR</td>
                </tr>
                <?php if($this->Gutscheine): ?>
                <tr>
                    <td class="title_row" colspan="3">Gutscheine:</td>
                    <td class="summe_row">-<?php echo $this->Endpreise->gutscheine; ?> EUR</td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="title_row" colspan="3">Endpreis:</td>
                    <td class="summe_row"><?php echo $this->Endpreise->endsumme; ?> EUR</td>
                </tr>
            </table>
            <?php endif; ?>
        </fieldset>
    </div>

    <?php if(is_array($this->Downloads) && count($this->Downloads)): ?>
    <fieldset>
        <legend>Digitale Produkt-Downloads</legend>
        <?php if($Bestellungen->payed): ?>
        <ul>
            <?php foreach($this->Downloads as $Download): ?>
            <li><a href="<?php echo $this->DownloadUrl; ?>?file=<?php echo $Download; ?>"><?php echo $Download; ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Downloads nach Zahlungseingang verf&uuml;gbar.</p>
        <?php endif; ?>
    </fieldset>
    <?php endif; ?>
    <p><a href="javascript:;" onclick="history.back(-1);">Zur&uuml;ck</a></p>
</div>