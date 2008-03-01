<?php

/**
 * xbk404Error
 *
 * ������ ������ 404
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
     * ����������� ������
     *
     * @access      public
     */
    public function __construct2()
    {    	// ���������
    	$this->setTitle($this->_LANG['error404']);
        // ����������
        $tmpl = $this->template('404');
        $this->setContent($tmpl->getParsedTemplate('body'));
    }

}

?>