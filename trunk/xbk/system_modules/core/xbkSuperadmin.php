<?php

/**
 * xbkSuperadmin
 *
 * ������ ����������� - ����� �����
 *
 * @version    1.0   2008-02-09
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadmin extends xbkContextObject
{
   /**
    * ������ �� ������ ������
    *
    * @access    public
    * @var       string
    */
    public $ref = null;

   /**
    * ������
    *
    * @access    public
    * @var       string
    */
    public $sectionRepresentations = Array();

   /**
    * ������ �� ������ ������
    *
    * @access    public
    * @var       array
    */
    public $menu = Array();
    /**
     * ����������� ������
     *
     * @access	  public
     */
    public function __construct2 (&$ref)
    {
    	global $CONFIG;
    	$this->setSectionReference($ref);

    	// ��������� ������ ������������� ������ � URL
        $this->sectionRepresentations = Array(
            'main' => 'xbk/main',
            'core' => 'xbk/core',
            'modules' => 'xbk/modules',
            'user' => 'xbk/user',
            'about' => 'xbk/about',
            'phpinfo' => 'xbk/phpinfo'
        );

        $Uri =& $this->factory('xbkUri');
        $CurrentUri =& $this->factory('xbkUri');

        // ��������� ������ ����
        $this->menu = Array();
        $Uri->goto($this->sectionRepresentations['main']);
        array_push(
            $this->menu,
            Array(
                    'text' => $this->_LANG['superadmin_menu_main'],
                    'section' => 'main',
                    'link' => $Uri->build(),
                    'selected' => ($Uri->getPath(true) == $CurrentUri->getPath(true)) ? 'true' : 'false'
            )
        );

        $Uri->goto($this->sectionRepresentations['core']);
        array_push(
            $this->menu,
            Array(
                    'text' => $this->_LANG['superadmin_menu_core'],
                    'section' => 'core',
                    'link' => $Uri->build(),
                    'selected' => ($Uri->getPath(true) == $CurrentUri->getPath(true)) ? 'true' : 'false'
            )
        );

        $Uri->goto($this->sectionRepresentations['modules']);
        array_push(
            $this->menu,
            Array(
                    'text' => $this->_LANG['superadmin_menu_modules'],
                    'section' => 'modules',
                    'link' => $Uri->build(),
                    'selected' => ($Uri->getPath(true) == $CurrentUri->getPath(true)) ? 'true' : 'false'
            )
        );

        $Uri->goto($this->sectionRepresentations['user']);
        array_push(
            $this->menu,
            Array(
                    'text' => $this->_LANG['superadmin_menu_user'],
                    'section' => 'user',
                    'link' => $Uri->build(),
                    'selected' => ($Uri->getPath(true) == $CurrentUri->getPath(true)) ? 'true' : 'false'
            )
        );

        $Uri->goto($this->sectionRepresentations['about']);
        array_push(
            $this->menu,
            Array(
                    'text' => $this->_LANG['superadmin_menu_about'],
                    'section' => 'about',
                    'link' => $Uri->build(),
                    'selected' => ($Uri->getPath(true) == $CurrentUri->getPath(true)) ? 'true' : 'false'
            )
        );

        $Uri->goto($this->sectionRepresentations['phpinfo']);
        array_push(
            $this->menu,
            Array(
                    'text' => $this->_LANG['superadmin_menu_phpinfo'],
                    'section' => 'phpinfo',
                    'link' => $Uri->build(),
                    'selected' => ($Uri->getPath(true) == $CurrentUri->getPath(true)) ? 'true' : 'false'
            )
        );

    }
    /**
     * �������� ������ �� ������ ������
     *
     * @param	  object
     * @access	  public
     */
    public function setSectionReference (&$ref)
    {
        // ������������� ������ �� ������ ������    	if (is_object($ref)) $this->ref =& $ref;

    }

    /**
     * ���������, ����������� �� ������������
     *
     * @access      public
     */
    public function isAuthorized ()
    {    	global $CONFIG;
    	if ($CONFIG['superadmin']['enable'] && $this->verifyIp())
    	{    		if ($CONFIG['superadmin']['auth_type'] == 'cookie')
    		{                if (isset($this->_COOKIE['xbk_superadmin_login']) && isset($this->_COOKIE['xbk_superadmin_pass']))
                {                	if (
                    	$this->_COOKIE['xbk_superadmin_login'] == $CONFIG['superadmin']['login'] &&
                    	$this->_COOKIE['xbk_superadmin_pass'] == $CONFIG['superadmin']['pass']
                	) {                		return true;                	} else return false;                } else return false;    		} else if ($CONFIG['superadmin']['auth_type'] == 'none') return true;
    		else return false;    	} else return false;
    }

    /**
     * ��������� ������������ IP-������
     *
     * @access      public
     */
    public function verifyIp ()
    {    	global $CONFIG;

        $ip = $this->_SERVER['HTTP_HOST'];

        // ����� �� ������ - ���������� ��������
        if ($CONFIG['superadmin']['ip_mask'] == null) return true;

        if (is_array($CONFIG['superadmin']['ip_mask']))
        {        	// �������� IP ������ �� ������� �����        	foreach ($CONFIG['superadmin']['ip_mask'] as $ip_mask)
        	{        		if (xbkFunctions::matchIp($ip_mask, $ip)) return true;        	}
        	return false;        }
        else if (is_string($CONFIG['superadmin']['ip_mask']))
        {        	// �������� IP-������ �� ��������� �����        	if (xbkFunctions::matchIp($CONFIG['superadmin']['ip_mask'], $ip)) return true;
        	else return false;        } else return false;    }

    /**
     * ���������� ������� ������� � ������
     * � ��������� ����������� �������� ��� ������� � ������ �������
     *
     * @access      public
     * @access      object xbkSection
     */
    public function prepare ()
    {    	global $CONFIG;

    	// ������ ����������� �� ������� �������
    	$this->ref->setHeader("Cache-Control: no-store, no-cache, must-revalidate");

    	$this_uri = $this->factory('xbkUri');
    	$this_uri->setThisUri();
    	if (!$this->isAuthorized()) {    		// ������������� �� �����������    		$uri =& $this->factory('xbkUri');
    		$uri->gotoSideSection('login');
    		$location = $uri->build(Array('redirect' => $this_uri));    		$this->ref->setRedirect($location);    		return false;
    	} else if (isset($this->_GET['action']) ? ($this->_GET['action'] == 'logout') : false) {    		// ����� �� ������
    		if ($CONFIG['superadmin']['auth_type'] == 'cookie')
    		{    			// ����� cookies
    			$this->ref->unsetCookie('xbk_superadmin_login');
                $this->ref->unsetCookie('xbk_superadmin_pass');

                // ������������� �� �����������
                $uri =& $this->factory('xbkUri');
        		$uri->gotoSideSection('login');
                $location = $uri->build(Array('redirect' => $this_uri->build('', 'action')));
        		$this->ref->setRedirect($location);
    		}
    		return false;
    	} else {    		if ($CONFIG['superadmin']['auth_type'] == 'cookie')
    		{    			$this->setSessionCookies();    		}
    		$this->ref->setTitle($this->_LANG['superadmin_title']);    		return true;
    	}    }

    /**
     * ������������� ���������� Cookies
     *
     * @access      public
     * @return      object xbkSection
     */
    public function setSessionCookies ()
    {
    	global $CONFIG;
    	$this->ref->setCookie('xbk_superadmin_login', $CONFIG['superadmin']['login'], time()+$CONFIG['superadmin']['inactivity_time']);
        $this->ref->setCookie('xbk_superadmin_pass', $CONFIG['superadmin']['pass'], time()+$CONFIG['superadmin']['inactivity_time']);
    }

    /**
     * ���������� ����� ������
     *
     * @access      public
     * @param       string
     */
    public function wrap ($content)
    {    	$uri_logout = $this->factory('xbkUri');
    	$uri_logout->setThisUri();
    	$tmpl = $this->template();
        $tmpl->readTemplatesFromInput('superadmin');
        $tmpl->addVar('body', 'content', $content);
        $tmpl->addVar('body', 'logout_link', $uri_logout->build('action=logout'));
        $tmpl->addRows('menu', $this->menu);
        return $tmpl->getParsedTemplate('body');    }

}

?>