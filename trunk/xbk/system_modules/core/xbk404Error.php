<?php

/**
 * xbk404Error
 *
 * Секция ошибка 404
 *
 * @version    1.0   2008-01-25
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbk404Error extends xbkSection
{

    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2()
    {    	// Заголовок
    	$this->setTitle($this->_LANG['error404']);
        // Содержимое
        $tmpl = $this->template('404');
        $this->setContent($tmpl->getParsedTemplate('body'));
    }

}

?>