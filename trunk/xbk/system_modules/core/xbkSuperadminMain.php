<?php

/**
 * xbkSuperadminMain
 *
 * Панель суперадмина - главная страница
 *
 * @version    1.0   2008-01-18
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminMain extends xbkSection
{

    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2()
    {

        global $CONFIG;

        // Панель суперадмина - общий класс
        $Superadmin =& $this->factory('xbkSuperadmin', $this);
        if (!$Superadmin->prepare()) return;

        // Заголовок
        $this->addTitle($this->_LANG['superadmin_main_title']);

        // Содержимое
        $tmpl = $this->template();
        $tmpl->readTemplatesFromInput('superadmin_main');
        $content = $tmpl->getParsedTemplate('content');
        $this->setContent($Superadmin->wrap($content));

    }

}

?>