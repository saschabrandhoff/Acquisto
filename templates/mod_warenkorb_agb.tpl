<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?> - AGB</<?php echo $this->hl; ?>>
    <?php endif; ?>

    <div class="steps">
        <ul>
            <li class="first"><a href="<?php echo $this->WarenkorbUrl; ?>">Warenkorb</a></li>
            <li><a href="<?php echo $this->WarenkorbUrl; ?>?do=customer">Kundendaten</a></li>
            <li><a href="<?php echo $this->WarenkorbUrl; ?>?do=payment-and-shipping">Zahlung und Versand</a></li>
            <li class="active">AGB</li>
            <li class="last">Kasse</li>
        </ul>
    </div>

    <div class="agb">
        <fieldset>
            <legend>Allgemeine Gesch&auml;ftsbedingungen</legend>
            <?php echo $this->AGB; ?>
        </fieldset>

        <form method="post">
            <div class="formbody">
                <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
                <input type="hidden" name="REQUEST_TOKEN" value="<?php echo REQUEST_TOKEN; ?>">

                <p>
                    <input type="checkbox" value="1" name="agb" id="checkbox_agb"<?php if($this->sel_agb): ?> checked="checked"<?php endif; ?>>
                    <label for="checkbox_agb">Ich akzeptiere die Allgemeinen Gesch&auml;ftsbedingungen</label>
                </p>

                <input type="button" value="&laquo; Zur&uuml;ck zu Zahlung und Versand" onclick="location.href = '<?php echo $this->WarenkorbUrl; ?>?do=payment-and-shipping';">
                <input type="submit" value="Weiter zur Kasse &raquo;" name="action">
            </div>
        </form>
    </div>
</div>