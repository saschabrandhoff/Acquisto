<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class nachname extends Module {
    protected $paymentName = 'Nachname';

    public function __construct()
    {
#        parent::__construct();
    }

    public function getConfigData() {
    }

    public function getPaymentInfo() {
        return $this->paymentName;
    }

    public function generateForm($payment_id) {
        return '<form method="get" action="%s">
         <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
         <input type="hidden" name="REQUEST_TOKEN" value="' . REQUEST_TOKEN . '">
         <input type="hidden" name="do" value="pay">
         <input type="submit" value="Kaufen">
         </form>';
    }

    public function pay($payment_id) {
        $this->paymentState = false;
        return true;
    }

    public function compile()
    {

    }
}


?>