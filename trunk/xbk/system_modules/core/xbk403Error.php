<?php

/**
 * xbk403Error
 *
 * Секция ошибка 403
 *
 * @version    1.0   2008-01-25
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbk403Error extends xbkSection
{

    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2()
    {
    	// Заголовок
    	$this->setTitle($this->_LANG['error403']);

        // Содержимое
        $tmpl = $this->template('403');
        $this->setContent($tmpl->getParsedTemplate('body'));
    }

}

?>