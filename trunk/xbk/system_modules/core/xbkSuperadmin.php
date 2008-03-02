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
        // ������������� ������ �� ������ ������

    }

    /**
     * ���������, ����������� �� ������������
     *
     * @access      public
     */
    public function isAuthorized ()
    {

    	{
    		{
                {
                    	$this->_COOKIE['xbk_superadmin_login'] == $CONFIG['superadmin']['login'] &&
                    	$this->_COOKIE['xbk_superadmin_pass'] == $CONFIG['superadmin']['pass']
                	) {
    		else return false;
    }

    /**
     * ��������� ������������ IP-������
     *
     * @access      public
     */
    public function verifyIp ()
    {

        $ip = $this->_SERVER['HTTP_HOST'];

        // ����� �� ������ - ���������� ��������
        if ($CONFIG['superadmin']['ip_mask'] == null) return true;

        if (is_array($CONFIG['superadmin']['ip_mask']))
        {
        	{
        	return false;
        else if (is_string($CONFIG['superadmin']['ip_mask']))
        {
        	else return false;

    /**
     * ���������� ������� ������� � ������
     * � ��������� ����������� �������� ��� ������� � ������ �������
     *
     * @access      public
     * @access      object xbkSection
     */
    public function prepare ()
    {

    	// ������ ����������� �� ������� �������
    	$this->ref->setHeader("Cache-Control: no-store, no-cache, must-revalidate");

    	$this_uri = $this->factory('xbkUri');
    	$this_uri->setThisUri();

    		$uri->gotoSideSection('login');
    		$location = $uri->build(Array('redirect' => $this_uri));
    	} else if (isset($this->_GET['action']) ? ($this->_GET['action'] == 'logout') : false) {
    		if ($CONFIG['superadmin']['auth_type'] == 'cookie')
    		{
    			$this->ref->unsetCookie('xbk_superadmin_login');
                $this->ref->unsetCookie('xbk_superadmin_pass');

                // ������������� �� �����������
                $uri =& $this->factory('xbkUri');
        		$uri->gotoSideSection('login');
                $location = $uri->build(Array('redirect' => $this_uri->build('', 'action')));
        		$this->ref->setRedirect($location);
    		}
    		return false;
    	} else {
    		{
    		$this->ref->setTitle($this->_LANG['superadmin_title']);
    	}

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
    {
    	$uri_logout->setThisUri();

        $tmpl->readTemplatesFromInput('superadmin');
        $tmpl->addVar('content', 'content', $content);
        $tmpl->addVar('content', 'logout_link', $uri_logout->build('action=logout'));
        $tmpl->addVar('content', 'version', xbkVersion::VERSION);
        $tmpl->addRows('menu', $this->menu);
        return $tmpl->getParsedTemplate('content');

}

?>