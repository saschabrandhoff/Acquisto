<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Ajax
 *
 * Provide methods to handle Ajax requests.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class acquistoShopAjax extends Backend
{

    /**
     * Ajax action
     * @var string
     */
    protected $strAction;

    /**
     * Ajax id
     * @var string
     */
    protected $strAjaxId;

    /**
     * Ajax key
     * @var string
     */
    protected $strAjaxKey;

    /**
     * Ajax name
     * @var string
     */
    protected $strAjaxName;

    /**
     * Ajax actions that do not require a data container object
     */
    public function PRECategorieActions($strAction)
    {
        $this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('id'));
        $this->strAjaxKey = str_replace('_' . $this->strAjaxId, '', $this->Input->post('id'));

        if ($this->Input->get('act') == 'editAll')
        {
            $this->strAjaxKey = preg_replace('/(.*)_[0-9a-zA-Z]+$/i', '$1', $this->strAjaxKey);
            $this->strAjaxName = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('name'));
        }

        $nodes = $this->Session->get($this->strAjaxKey);
        $nodes[$this->strAjaxId] = intval($this->Input->post('state'));
        $this->Session->set($this->strAjaxKey, $nodes);
    }


    /**
     * Ajax actions that do require a data container object
     * @param DataContainer
     */
    public function POSTCategorieActions($strAction, $dc)
    {
        $arrData['strTable'] = $dc->table;
        $arrData['id'] = strlen($this->strAjaxName) ? $this->strAjaxName : $dc->id;
        $arrData['name'] = $this->Input->post('name');

        $objWidget = new $GLOBALS['BE_FFL']['categorieTree']($arrData, $dc);

        echo $objWidget->generateAjax($this->strAjaxId, $this->Input->post('field'), intval($this->Input->post('level')));
    }
}

?>