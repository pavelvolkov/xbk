<?php

/**
 * xbkSuperadminPhpinfo
 *
 * Панель суперадмина - phpinfo()
 *
 * @version    1.0   2008-01-19
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminPhpinfo extends xbkSection
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
        $this->setTitle($this->_LANG['superadmin_title']);

        $Uri =& $this->factory('xbkUri');
        $Uri->gotoBrother('phpinfo_phpinfo');

        // Содержимое
        $tmpl = $this->template('phpinfo');
        $tmpl->addVar('content', 'src', $Uri->build());
        $content = $tmpl->getParsedTemplate('content');

        $this->setContent($Superadmin->wrap($content));

    }

}

?>