<?php

/**
 * xbkSuperadminCore
 *
 * ������ ����������� - ������ ���� �������
 *
 * @version    1.0   2008-01-21
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminCore extends xbkSection
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

        $Installer =& $this->factory('xbkInstaller', 'core');

        // ������ ������������� �������������
        $question = '';
        if (isset($this->_POST['uninstall_core']))
        {        	$uri_action =& $this->factory('xbkUri');
            $question =& $this->factory('xbkQuestion', 'warning');
            $question->setAction($uri_action->build());
            $question->setText($this->_LANG['superadmin_core_uninstall_question']);
            $question->addSubmit('uninstall_core_confirm', $this->_LANG['superadmin_core_uninstall_confirm']);
            $question->addSubmit('uninstall_core_cancel', $this->_LANG['superadmin_core_uninstall_cancel']);
            $question = $question->build();
        }

        // ��������� ���� �� �������
        if (isset($this->_POST['install_core'])) {        	$Installer->install();
        	$this->setRedirect($this->factory('xbkUri')->build());
        	return;        }

        // ������������� ���� �� �������
        if (isset($this->_POST['uninstall_core_confirm'])) {        	$Installer->uninstall();
        	$this->setRedirect($this->factory('xbkUri')->build());
        	return;
        }

        // ���������
        $this->addTitle($this->_LANG['superadmin_core_title']);

        // ������ ����
        $ModuleAnalyzer =& $this->factory('xbkModelAnalyzer');
        $result = $ModuleAnalyzer->checkTables('core');

        // �����������
        $teaser =& $this->factory('xbkTeaser');

        // ������ ��������������
        $warning = null;
        if ($result == xbkModelAnalyzer::CONTAINS_ALL)
        {
            $teaser->setContent($this->_LANG['superadmin_core_contains_all']);
            $teaser->setType('ok');
        } else if ($result == xbkModelAnalyzer::CONTAINS_PART)
        {
            $teaser->setContent($this->_LANG['superadmin_core_contains_part']);
            $teaser->setType('error');
        } else if ($result == xbkModelAnalyzer::CONTAINS_NONE)
        {
            $teaser->setContent($this->_LANG['superadmin_core_contains_none']);
            $teaser->setType('warning');
        }
        $action =& $this->factory('xbkUri');

        // ����������
        $tmpl = $this->template('superadmin_core');
        $tmpl->addVar('content', 'action', $action->build());
        $tmpl->addVar('content', 'question', $question);
        $tmpl->addVar('content', 'teaser', $teaser->build());
        $content = $tmpl->getParsedTemplate('content');
        $this->setContent($Superadmin->wrap($content));

    }

}

?>