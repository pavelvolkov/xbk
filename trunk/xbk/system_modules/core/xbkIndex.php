<?php

/**
 * xbkIndex
 *
 * Стартовая страница по-умолчанию
 *
 * @version    1.0   2008-02-09
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkIndex extends xbkSection
{
    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2 ()
    {    	$this->setTitle($this->_LANG['index']);

    	$SuperadminUri =& $this->factory('xbkUri');
    	$SuperadminUri->goto('xbk');
        $tmpl = $this->template('index');
        $tmpl->addVar('content', 'superadmin_link', $SuperadminUri->build());
        $tmpl->addVar('content', 'xbk_site_link', 'http://code.google.com/p/xbk/');
        $tmpl->addVar('content', 'xbk_forum_link', 'http://xbk.forum24.ru/?0-2');
        $this->setContent($tmpl->getParsedTemplate('content'));
    }
}

?>