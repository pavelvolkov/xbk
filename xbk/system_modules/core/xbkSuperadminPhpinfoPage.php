<?php

/**
 * xbkSuperadminPhpinfoPage
 *
 * Панель суперадмина - модули системы
 *
 * @version    1.0   2008-02-09
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminPhpinfoPage extends xbkSection
{

    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2 ()
    {    	$Superadmin =& $this->factory('xbkSuperadmin', $this);    	if (!$Superadmin->prepare()) return;
    	ob_start();
        phpinfo();
        $buffer = ob_get_contents();
        ob_clean();
        $this->setContent($buffer);
    }

}

?>