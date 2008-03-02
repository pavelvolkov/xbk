<?php

/**
 * xbkSuperadmin
 *
 * Вход в панель суперадмина
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
     * Конструктор класса
     *
     * @access	  public
     */
    public function __construct2()
    {
        global $CONFIG;
    	// Панель суперадмина - общий класс
    	$Superadmin =& $this->factory('xbkSuperadmin', $this);
    	$this->isAuthorized = $Superadmin->isAuthorized();

        // Ведение ошибок
    	$this->ErrorStack =& $this->factory('xbkErrorStack');

    	// Авторизация
    	if (isset($this->_POST['login'], $this->_POST['pass']))
    	{    		if ($CONFIG['superadmin']['enable'])
    		{    			if ( $CONFIG['superadmin']['ip_mask'] == null ? true :
        			$Superadmin->verifyIp() )
        		{            		if (
                		$this->_POST['login'] == $CONFIG['superadmin']['login'] &&
                		$this->_POST['pass'] == $CONFIG['superadmin']['pass']
            		) {                		// Успешная авторизация
                		// Установка cookies
                        $Superadmin->setSessionCookies();
                		// Переадресация
                		if (isset($this->_GET['redirect']))
                		{                			// На предыдущую страницу внутренней панели
                    		$location = $this->_GET['redirect'];
                    	} else {                			// На главную страницу внутренней панели
                    		$uri =& $this->factory('xbkUri');
                    		$uri->gotoSection($Superadmin->sectionRepresentations['main']);
                    		$location = $uri->build();
                		}
                		$this->setRedirect($location);
                		return;
            		} else {            			// Неудачная авторизация
            			$this->ErrorStack->push($this->_LANG['superadmin_auth_error_invalid']);            		}
            	} else {
        			// Доступ по IP запрещён
        			$this->ErrorStack->push($this->_LANG['superadmin_auth_error_unallowed_ip']);
        		}
    		} else {    			// Панель заблокирована
    			$this->ErrorStack->push($this->_LANG['superadmin_auth_error_disabled']);    		}    	}
    	// Заголовок    	$this->setTitle($this->_LANG['superadmin_login_title']);

    	// Содержимое
    	$action_uri =& $this->factory('xbkUri');
    	$action_uri->addInheritedParameters('redirect');    	$tmpl = $this->template();
        $tmpl->readTemplatesFromInput('superadmin_login');
        $tmpl->addGlobalVar('ERROR', $this->ErrorStack);
        $tmpl->addGlobalVar('ACTION', $action_uri);
        $tmpl->addGlobalVar('IS_AUTHORIZED', $this->isAuthorized);
        $this->setContent($tmpl->getParsedTemplate('body'));    }

}

?>