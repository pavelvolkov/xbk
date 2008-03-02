<?php

/**
 * xbkSuperadmin
 *
 * ���� � ������ �����������
 *
 * @version    1.0   2008-01-28
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminLogin extends xbkSection
{
    /**
     * ����������� ������
     *
     * @access	  public
     */
    public function __construct2()
    {
        global $CONFIG;
    	// ������ ����������� - ����� �����
    	$Superadmin =& $this->factory('xbkSuperadmin', $this);
    	$this->isAuthorized = $Superadmin->isAuthorized();

        // ������� ������
    	$this->ErrorStack =& $this->factory('xbkErrorStack');

    	// �����������
    	if (isset($this->_POST['login'], $this->_POST['pass']))
    	{    		if ($CONFIG['superadmin']['enable'])
    		{    			if ( $CONFIG['superadmin']['ip_mask'] == null ? true :
        			$Superadmin->verifyIp() )
        		{            		if (
                		$this->_POST['login'] == $CONFIG['superadmin']['login'] &&
                		$this->_POST['pass'] == $CONFIG['superadmin']['pass']
            		) {                		// �������� �����������
                		// ��������� cookies
                        $Superadmin->setSessionCookies();
                		// �������������
                		if (isset($this->_GET['redirect']))
                		{                			// �� ���������� �������� ���������� ������
                    		$location = $this->_GET['redirect'];
                    	} else {                			// �� ������� �������� ���������� ������
                    		$uri =& $this->factory('xbkUri');
                    		$uri->gotoSection($Superadmin->sectionRepresentations['main']);
                    		$location = $uri->build();
                		}
                		$this->setRedirect($location);
                		return;
            		} else {            			// ��������� �����������
            			$this->ErrorStack->push($this->_LANG['superadmin_auth_error_invalid']);            		}
            	} else {
        			// ������ �� IP ��������
        			$this->ErrorStack->push($this->_LANG['superadmin_auth_error_unallowed_ip']);
        		}
    		} else {    			// ������ �������������
    			$this->ErrorStack->push($this->_LANG['superadmin_auth_error_disabled']);    		}    	}
    	// ���������    	$this->setTitle($this->_LANG['superadmin_login_title']);

    	// ����������
    	$action_uri =& $this->factory('xbkUri');
    	$action_uri->addInheritedParameters('redirect');    	$tmpl = $this->template();
        $tmpl->readTemplatesFromInput('superadmin_login');
        $tmpl->addGlobalVar('ERROR', $this->ErrorStack);
        $tmpl->addGlobalVar('ACTION', $action_uri);
        $tmpl->addGlobalVar('IS_AUTHORIZED', $this->isAuthorized);
        $this->setContent($tmpl->getParsedTemplate('body'));    }

}

?>