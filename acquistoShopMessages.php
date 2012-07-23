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
 
require_once(TL_ROOT . '/plugins/simplepie/simplepie.inc');

if (!class_exists('idna_convert', false))
{
	require_once(TL_ROOT . '/plugins/idna/idna_convert.class.php');
}
 
class acquistoShopMessages extends Backend
{
    private $html;
    protected $feeds = array(
        'http://www.contao-acquisto.de/blog.xml', 
//        'https://api.twitter.com/1/statuses/user_timeline.rss?screen_name=acquistoshop', 
        'http://feeds.feedburner.com/shopbetreiberblog'
    );

    public function __construct() {
        parent::__construct();
        $this->import('acquistoShop', 'Shop');
    }
    
    private function clear() {
        $this->html = null;
    }

    public function getMessages() {
        $this->clear();

        $this->objFeed = new SimplePie();
        $this->objFeed->set_feed_url($this->feeds);
		$this->objFeed->set_output_encoding($GLOBALS['TL_CONFIG']['characterSet']);
        $this->objFeed->set_cache_location(TL_ROOT . '/system/tmp');
		$this->objFeed->enable_cache(false);

		if (!$this->objFeed->init())
		{
			$this->log('Error importing RSS feed "' . $this->rss_feed . '"', 'ModuleRssReader generate()', TL_ERROR);
			return '';
		}

		$this->objFeed->handle_content_type();
		$arrItems = $this->objFeed->get_items(intval($this->skipFirst), intval($this->rss_numberOfItems));

        #echo "<pre>";
        #print_r($arrItems);
        #tl_info, tl_new, tl_confirm, tl_error

		$limit = count($arrItems);
		$offset = 0;

		$items = array();
		$last = min($limit, count($arrItems)) - 1;

#        if($GLOBALS['TL_CONFIG']['numberOfMessages'] >= 1) {        
            $this->html .= '</div><div id="tl_acquisto_news">';
            $this->html .= '<h2>Acquisto Nachrichten</h2>';
    		for ($i=$offset; $i<$GLOBALS['TL_CONFIG']['numberOfMessages'] && $i<count($arrItems); $i++)
    		{
                $this->html .= '<p class="tl_new"><a href="' . $arrItems[$i]->get_permalink() . '">' . date("d.m.Y", $arrItems[$i]->get_date('U')) . ' - ' . $arrItems[$i]->get_title() . '</a></p>';
            }
#        }

        return $this->html;     
    }
    
    public function getDeveloperMessages() {
        $this->clear();
        echo 1;
        $this->objFeed = new SimplePie();
        $this->objFeed->set_feed_url(array('http://partner.contao-acquisto.de/feed.xml?' . $GLOBALS['TL_CONFIG']['serialnumber']));
		$this->objFeed->set_output_encoding($GLOBALS['TL_CONFIG']['characterSet']);
        $this->objFeed->set_cache_location(TL_ROOT . '/system/tmp');
		$this->objFeed->enable_cache(false);

		if (!$this->objFeed->init())
		{
			$this->log('Error importing RSS feed "' . $this->rss_feed . '"', 'ModuleRssReader generate()', TL_ERROR);
			return '';
		}

		$this->objFeed->handle_content_type();
		$arrItems = $this->objFeed->get_items(intval($this->skipFirst), intval($this->rss_numberOfItems));

        #echo "<pre>";
        #print_r($arrItems);
        #tl_info, tl_new, tl_confirm, tl_error

		$limit = count($arrItems);
		$offset = 0;

		$items = array();
		$last = min($limit, count($arrItems)) - 1;

#        if($GLOBALS['TL_CONFIG']['numberOfMessages'] >= 1) {        
            $this->html .= '</div><div id="tl_acquisto_news">';
            $this->html .= '<h2>Acquisto Nachrichten</h2>';
    		for ($i=$offset; $i<$GLOBALS['TL_CONFIG']['numberOfMessages'] && $i<count($arrItems); $i++)
    		{
                $this->html .= '<p class="tl_new"><a href="' . $arrItems[$i]->get_permalink() . '">' . $arrItems[$i]->get_title() . '</a></p>';
            }
#        }

        return $this->html;     
    
    }

    public function getSystemMessages() {
        $this->clear();

        if($GLOBALS['TL_CONFIG']['numberOfSysMessages'] >= 1) {        
            $this->html  = '</div><div id="tl_acquisto_messages">';
            $this->html .= '<h2>Acquisto Systemnachrichten</h2>';

            $objBestellungen = $this->Database->prepare("SELECT * FROM tl_shop_orders ORDER BY tstamp DESC")->limit($GLOBALS['TL_CONFIG']['numberOfSysMessages'])->execute();
            while($objBestellungen->next()) {
                $objCustomer    = $this->Database->prepare("SELECT * FROM tl_shop_orders_customers WHERE pid = ?")->limit(1)->execute($objBestellungen->id);
                
                $this->html .= '<p class="tl_confirm"><a href="?do=acquistoShopOrders">' . date("d.m.Y", $objBestellungen->tstamp) . ' - ' . date("H:i", $objBestellungen->tstamp) . ' Uhr | Bestellung ' . $this->Shop->generateOrderID($objBestellungen->order_id) . ' | ' . $objCustomer->firstname . ' ' . $objCustomer->lastname . '</a></p>';
            }                                                

        }

        return $this->html;       
    }
}

?>