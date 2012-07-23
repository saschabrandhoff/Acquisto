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

class sofortueberweisung extends Module {
    protected $paymentName = 'Sofort&uuml;berweisung';
    protected $strTemplate = 'cao_sofortueberweisung';

    public function __construct()
    {
#        parent::__consturct();
    }

    public function getConfigData() {
        $arrConfig = array(
            'user_id' => array
            (
                'label'                   => array('user_id'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'project_id' => array
            (
                'label'                   => array('project_id'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            )
        );

        return $arrConfig;
    }

    public function getPaymentInfo() {
        return $this->paymentName;
    }

    public function generateForm($payment_id) {
        $arrConfig = $this->getConfig($payment_id);

        $paymentForm =
        '<form method="post" action="https://www.sofortueberweisung.de/payment/start">
        <input type="hidden" name="hidelink" value="%s" />
        <input name="amount" type="hidden" value="%01.2f"/>
        <input name="currency_id" type="hidden" value="EUR"/>
        <input name="reason_1" type="hidden" value="Project"/>
        <input name="reason_2" type="hidden" value="Test"/>
        <input name="user_id" type="hidden" value="[__VAR_user_id__]"/>
        <input name="project_id" type="hidden" value="[__VAR_project_id__]"/>
        <input type="submit" value="Kaufen">
        </form>';

        if(is_array($arrConfig)) {
            foreach($arrConfig as $var => $val) {
                $paymentForm = str_replace("[__VAR_" . $var . "__]", $val, $paymentForm);
            }
        }

        return $paymentForm;

    }

    public function getConfig($payment_id) {
        $this->Import('Database');
        $arrConfig = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($payment_id);
        return unserialize($arrConfig->configData);
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