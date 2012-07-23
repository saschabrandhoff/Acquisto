<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?> - Kundendaten</<?php echo $this->hl; ?>>
    <?php endif; ?>

    <div class="steps">
        <ul>
            <li class="first"><a href="<?php echo $this->WarenkorbUrl; ?>">Warenkorb</a></li>
            <li class="active">Kundendaten</li>
            <li>Zahlung und Versand</li>
            <li>AGB</li>
            <li class="last">Kasse</li>
        </ul>
    </div>

    <div class="customer">
        <form method="post">
            <div class="formbody">
                <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
                <input type="hidden" name="REQUEST_TOKEN" value="<?php echo REQUEST_TOKEN; ?>">
                <fieldset>
                    <legend>Kundendaten</legend>
                    <p>
                        <label for="">Vorname</label>
                        <input type="text" id="" name="customer[firstname]" value="<?php echo $this->Member['firstname']; ?>">
                    </p>

                    <p>
                        <label for="">Nachname</label>
                        <input type="text" id="" name="customer[lastname]" value="<?php echo $this->Member['lastname']; ?>">
                    </p>

                    <p>
                        <label for="">Strasse</label>
                        <input type="text" id="" name="customer[street]" value="<?php echo $this->Member['street']; ?>">
                    </p>

                    <p>
                        <label for="">Postleitzahl</label>
                        <input type="text" id="" name="customer[postal]" value="<?php echo $this->Member['postal']; ?>">
                    </p>

                    <p>
                        <label for="">Ort</label>
                        <input type="text" id="" name="customer[city]" value="<?php echo $this->Member['city']; ?>">
                    </p>

                    <p>
                        <label for="email">E-Mail</label>
                        <input type="text" id="email" name="customer[email]" value="<?php echo $this->Member['email']; ?>">
                    </p>
                </fieldset>

                <fieldset>
                    <legend>Lieferadresse</legend>
                    <p>
                        <label for="">Vorname</label>
                        <input type="text" id="" name="customer[deliver_firstname]" value="">
                    </p>

                    <p>
                        <label for="">Nachname</label>
                        <input type="text" id="" name="customer[deliver_lastname]" value="">
                    </p>

                    <p>
                        <label for="">Firma / Verein</label>
                        <input type="text" id="" name="customer[deliver_company]" value="">
                    </p>

                    <p>
                        <label for="">Strasse</label>
                        <input type="text" id="" name="customer[deliver_street]" value="">
                    </p>

                    <p>
                        <label for="">Postleitzahl</label>
                        <input type="text" id="" name="customer[deliver_postal]" value="">
                    </p>

                    <p>
                        <label for="">Ort</label>
                        <input type="text" id="" name="customer[deliver_city]" value="">
                    </p>
                </fieldset>
                <input type="button" value="&laquo; Zur&uuml;ck zu Warenkorb" onclick="location.href = '<?php echo $this->WarenkorbUrl; ?>';">
                <input type="submit" value="Weiter zu Zahlung und Versand &raquo;" name="action">
            </div>
        </form>
    </div>
</div>