<?php

/**
 * xbk403Error
 *
 * ������ ������ 403
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
     * ����������� ������
     *
     * @access      public
     */
    public function __construct2()
    {
    	// ���������
    	$this->setTitle($this->_LANG['error403']);

        // ����������
        $tmpl = $this->template('403');
        $this->setContent($tmpl->getParsedTemplate('body'));
    }

}

?>