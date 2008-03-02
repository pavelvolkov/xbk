<?php

/**
 * xbkDummyInfo
 *
 * Информация модуля Dummy
 *
 * @version    1.0   2008-02-07
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkDummyInfo extends xbkModuleInfo
{
    /**
     * Название модуля, взятое из языковых настроек
     *
     * @access      public
     * @param       string
     */
    public function getTitle ()
    {
    	return $this->_LANG['module'];
    }
}

?>