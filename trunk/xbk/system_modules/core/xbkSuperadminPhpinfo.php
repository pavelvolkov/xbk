<?php

/**
 * xbkSuperadminPhpinfo
 *
 * ������ ����������� - phpinfo()
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
     * ����������� ������
     *
     * @access      public
     */
    public function __construct2()
    {

        global $CONFIG;

        // ������ ����������� - ����� �����
        $Superadmin =& $this->factory('xbkSuperadmin', $this);
        if (!$Superadmin->prepare()) return;

        // ���������
        $this->setTitle($this->_LANG['superadmin_title']);

        $Uri =& $this->factory('xbkUri');
        $Uri->gotoBrother('phpinfo_phpinfo');

        // ����������
        $tmpl = $this->template('phpinfo');
        $tmpl->addVar('content', 'src', $Uri->build());
        $content = $tmpl->getParsedTemplate('content');

        $this->setContent($Superadmin->wrap($content));

    }

}

?>