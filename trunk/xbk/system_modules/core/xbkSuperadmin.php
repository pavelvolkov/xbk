<?php

/**
 * xbkSuperadmin
 *
 * Панель суперадмина - общий класс
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
    * Ссылка на объект секции
    *
    * @access    public
    * @var       string
    */
    public $ref = null;

   /**
    * Адреса
    *
    * @access    public
    * @var       string
    */
    public $sectionRepresentations = Array();

   /**
    * Ссылка на объект секции
    *
    * @access    public
    * @var       array
    */
    public $menu = Array();
    /**
     * Конструктор класса
     *
     * @access	  public
     */
    public function __construct2 (&$ref)
    {
    	global $CONFIG;
    	$this->setSectionReference($ref);

    	// Наполняет массив представлений секций в URL
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

        // Наполняет массив меню
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
     * Сообщает ссылку на объект секции
     *
     * @param	  object
     * @access	  public
     */
    public function setSectionReference (&$ref)
    {
        // Устанавливает ссылку на объект секции    	if (is_object($ref)) $this->ref =& $ref;

    }

    /**
     * Проверяет, авторизован ли пользователь
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
     * Проверяет соответствие IP-адреса
     *
     * @access      public
     */
    public function verifyIp ()
    {    	global $CONFIG;

        $ip = $this->_SERVER['HTTP_HOST'];

        // Маска не задана - пропускаем проверку
        if ($CONFIG['superadmin']['ip_mask'] == null) return true;

        if (is_array($CONFIG['superadmin']['ip_mask']))
        {        	// Проверка IP адреса по массиву масок        	foreach ($CONFIG['superadmin']['ip_mask'] as $ip_mask)
        	{        		if (xbkFunctions::matchIp($ip_mask, $ip)) return true;        	}
        	return false;        }
        else if (is_string($CONFIG['superadmin']['ip_mask']))
        {        	// Проверка IP-адреса по единичной маске        	if (xbkFunctions::matchIp($CONFIG['superadmin']['ip_mask'], $ip)) return true;
        	else return false;        } else return false;    }

    /**
     * Определяет наличие доступа в панель
     * и выполняет необходимые действия над секцией в случае неудачи
     *
     * @access      public
     * @access      object xbkSection
     */
    public function prepare ()
    {    	global $CONFIG;

    	// Отмена кеширования на стороне клиента
    	$this->ref->setHeader("Cache-Control: no-store, no-cache, must-revalidate");

    	$this_uri = $this->factory('xbkUri');
    	$this_uri->setThisUri();
    	if (!$this->isAuthorized()) {    		// Переадресация на авторизацию    		$uri =& $this->factory('xbkUri');
    		$uri->gotoSideSection('login');
    		$location = $uri->build(Array('redirect' => $this_uri));    		$this->ref->setRedirect($location);    		return false;
    	} else if (isset($this->_GET['action']) ? ($this->_GET['action'] == 'logout') : false) {    		// Выход из панели
    		if ($CONFIG['superadmin']['auth_type'] == 'cookie')
    		{    			// Сброс cookies
    			$this->ref->unsetCookie('xbk_superadmin_login');
                $this->ref->unsetCookie('xbk_superadmin_pass');

                // Переадресация на авторизацию
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
     * Устанавливает сессионные Cookies
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
     * Внутренний макет панели
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