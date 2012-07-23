<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if($this->headline): ?>
    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
    <?php endif; ?>

    <form method="post">
        <div class="formbody">
            <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_gutschein">
            <input type="hidden" name="REQUEST_TOKEN" value="<?php echo REQUEST_TOKEN; ?>">

            <p>
                <label for="code">Gutschein-Code:</label>
                <input type="text" id="ctrl_code" name="code" value="">
            </p>

            <p>
                <input type="submit" name="send" value="Ein&ouml;sen">
            </p>
        </div>
    </form>

    <?php if(is_array($this->UseList)): ?>
    <fieldset>
        <legend>Zum einl&ouml;sen vorgemerkt</legend>

        <div class="basket">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>Gutschein</th>
                    <th>G&uuml;ltig von</th>
                    <th>G&uuml;ltig bis</th>
                    <th>Wert</th>
                    <th></th>
                </tr>
                <?php foreach($this->UseList as $Gutschein): ?>
                <tr>
                    <td><?php echo $Gutschein->code; ?>1</td>
                    <td><?php echo $Gutschein->gueltig_von; ?></td>
                    <td><?php echo $Gutschein->gueltig_bis; ?></td>
                    <td><?php echo $Gutschein->preis; ?> EUR</td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </fieldset>
    <?php endif; ?>

    <!--
    <?php if(is_array($this->Gutscheine)): ?>
    <fieldset>
        <legend>Pers&ouml;nliche Gutscheine</legend>
    <form method="post">
        <div class="formbody">
            <div class="basket">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th>Gutschein</th>
                        <th>G&uuml;ltig bis</th>
                        <th>Wert</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><input type="submit" name="send" value="Ein&ouml;sen"></td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
    </fieldset>
    <?php endif; ?>
    -->

</div>