<?php

/**
 * xbkDummyInfo
 *
 * ���������� ������ Dummy
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
     * �������� ������, ������ �� �������� ��������
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