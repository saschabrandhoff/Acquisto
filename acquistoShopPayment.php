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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */

/**
 * Class acquistoShopPayment
 *
 * Front end module "acquistoShopPayment".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class acquistoShopPayment extends Controller {
    public $paymentState;

    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function frontendTemplate($paymentModule, $payment_id) {
        if($paymentModule) {
            require_once(TL_ROOT . '/system/modules/acquistoShop/paymentModules/' . $paymentModule . '.php');
            $objModule = new $paymentModule();
            $objTemplate = new FrontendTemplate($objModule->strTemplate);
            return $objModule->generateForm($payment_id);
        } else {
            return '';
        }
    }

    public function pay($paymentModule, $payment_id) {
        require_once(TL_ROOT . '/system/modules/acquistoShop/paymentModules/' . $paymentModule . '.php');
        $objModule = new $paymentModule();
        $returnVal = $objModule->pay($payment_id);

        if($returnVal) {

        }

        return $returnVal;
    }

    public function getModules() {
        if ($handle = opendir(TL_ROOT . '/system/modules/acquistoShop/paymentModules/'))
        {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != ".." && is_file(TL_ROOT . '/system/modules/acquistoShop/paymentModules/' . $file))
                {
                    require_once(TL_ROOT . '/system/modules/acquistoShop/paymentModules/' . $file);
                    $strPaymod = substr($file, 0, -4);
                    $objPaymod = new $strPaymod();
                    $arrModules[$strPaymod] = $objPaymod->getPaymentInfo();
                }
            }

            closedir($handle);
        }

        return $arrModules;
    }
}

?>