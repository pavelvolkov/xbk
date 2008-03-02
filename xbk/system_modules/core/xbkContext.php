<?php

/**
 * xbkContext
 *
 * �������� � ������� ���������� �������
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkContext
{
    /**
     * ����� ������ ����������
     *
	 * @access	public
	 * @var  	float
     */
    public $TIMESTART;

    /**
     * ����� ��������� ����������
     *
	 * @access	public
	 * @var  	float
     */
    public $TIMEEND;
    /**
     * ������ Doctrine_Connection
     *
	 * @access	public
	 * @var  	object Doctrine_Connection
     */
    public $DB;

    /**
     * ������ http-����������
     *
	 * @access	public
	 * @var  	array
     */
    public $headers = Array();

    /**
     * ������ cookies
     *
	 * @access	public
	 * @var  	array
     */
    public $cookies = Array();

    /**
     * ����� �������������
     *
	 * @access	public
	 * @var  	string
     */
    public $redirect = null;

    /**
     * ����������
     *
	 * @access	public
	 * @var  	array
     */
    public $content = null;

    /**
     * ��� ������
     *
	 * @access	public
	 * @var  	array
     */
    public $sectionType = 'content';

    /**
     * ���������� �������� ���������
     */
    public
        $HTTP_RAW_POST_DATA,
        $_GET,
        $_POST,
        $_COOKIE,
        $_FILES,
        $_SERVER;
    /**
     * ����������� ������
     *
	 * @access	public
	 * @param	string or null	������� URL-������
     */
    public function __construct ($uri = null)
    {
        global $CONFIG;

        $this->TIMESTART = microtime();

        $this->uri = $uri;
	}

    /**
     * ���������� ����������
     *
	 * @access	public
	 * @param	object
    */
	public function setConnection (&$DB)
	{		$this->DB = $DB;	}

    /**
     * ������������� ���������� ��� ������ ���������
     *
	 * @access	public
	 * @param	string		��� ���������� ���������
	 * @param	mixed		�������� ���������� ���������
     */
    public function set ($name, $value)
    {
        switch ($name) {
            case 'HTTP_RAW_POST_DATA':
                if (is_string($value)) $this->HTTP_RAW_POST_DATA = $value;
                break;
            case '_GET':
                if (is_array($value)) $this->_GET = $value;
                break;
            case '_POST':
                if (is_array($value)) $this->_POST = $value;
                break;
            case '_COOKIE':
                if (is_array($value)) $this->_COOKIE = $value;
                break;
            case '_FILES':
                if (is_array($value)) $this->_FILES = $value;
                break;
            case '_SERVER':
                if (is_array($value)) $this->_SERVER = $value;
                break;
        }
    }
    /**
     * ������ �� ����������
     *
	 * @access	public
    */
    public function execute ()
    {
        global $CONFIG;

        // ��������� ������
        setlocale(LC_ALL, $CONFIG['interface'][$CONFIG['lang']]['locale']);

        // ������ ����������� ����������
        $this->_Registry = New xbkRegistry(false);
        $this->_Registry->set('_GET', $this->_GET);
        $this->_Registry->set('_POST', $this->_POST);
        $this->_Registry->set('HTTP_RAW_POST_DATA', $this->HTTP_RAW_POST_DATA);
        $this->_Registry->set('_COOKIE', $this->_COOKIE);
        $this->_Registry->set('_FILES', $this->_FILES);
        $this->_Registry->set('_SERVER', $this->_SERVER);

        // ���������� ������� ���������� � ��
        $this->_Registry->setConnection($this->DB);

        $this->_Registry->prepare();

        // ������ ������� ������
        if ($this->uri != null)
        {
            $ThisUri =& $this->_Registry->factory('xbkUri', $this->uri);
        } else {
        	$ThisUri =& $this->_Registry->factory('xbkUri');
        }

        // ������������� ��������
        $Router =& $this->_Registry->factory('xbkRouter', $ThisUri);

        // ������� ���������� � ������
        $ModuleSection =& $Router->getModuleSection();

        // ��� ������
        $this->sectionType = $ModuleSection->getType();

        // ������ ������
        $Section =& $this->_Registry->factory($ModuleSection->class);
        if (!is_object($Section))
        {        	// ����� �����������
        	$Section =& $this->_Registry->factory('xbk404Error');
        	$Section->setType('content');        }

        // ������������ ��� ������
        if ($Section->getType() != null)
        {        	$this->sectionType = $Section->getType();        }

        // ��������� Cookies
        if ($Section->hasCookies())
        {
            $this->cookies = $Section->getCookies();
        } else $this->cookies = null;

        // �������� �� �������������
        if ($Section->isRedirect())
        {            $this->redirect = $Section->getRedirect();
            return;
        } else $this->redirect = false;

        // ��������� ���������� ������
        $this->headers = $Section->getHeaders();
        $this->title = $Section->getTitle();
        //$this->keywords = $Section->getKeywords();
        //$this->desctription = $Section->getDescription();

        	if ($this->sectionType == 'content')
        	{        		// ��������� ����������
        		$Section->setHeader("Content-type: text/html; charset=".$CONFIG['interface'][$CONFIG['lang']]['charset']);        		$this->headers = $Section->getHeaders();
        		// ��������� �����������
                $content = $Section->getContent();

                // ��������� ������
                $Page =& $this->_Registry->factory('xbkPage', $content);
                if (isset($this->title)) $Page->setTitle($this->title);
                $Page->setCss($this->_Registry->getCss());
                $Page->setJs($this->_Registry->getJs());
                $this->content = $Page->getContent();

            } else if ($this->sectionType == 'page') {
                // ��������� ����������
        		$Section->setHeader("Content-type: text/html; charset=".$CONFIG['interface'][$CONFIG['lang']]['charset']);
        		$this->headers = $Section->getHeaders();            	// ��������� �����������            	$this->content = $Section->getContent();
            } else if ($this->sectionType == 'image') {            	// ��������� �����������            	$this->content = $Section->getContent();

            	// ��������� ���� �����������
            	$image_type = $Section->getImageType();
            	array_push($this->headers, "Content-type: image/".$image_type);
            }

            // ����� ��������� ����������
            $this->TIMEEND = microtime();

            $this->TIMESTART = xbkFunctions::microtimeToTime($this->TIMESTART);
            $this->TIMEEND = xbkFunctions::microtimeToTime($this->TIMEEND);
            $runtime = ceil(($this->TIMEEND - $this->TIMESTART)*1000)/1000;

            // ������ ������� ����������
            if (in_array($this->sectionType, Array('content', 'page')))
            {            	$this->content .= "\n<!--\n  Runtime: ".$runtime."\n-->";            }    }

    /**
     * �������� ����������� � �������
     *
	 * @access	public
     */
    public function flush ()
    {    	global $CONFIG;
        // �������� headers
        if ($this->headers != null)
        {
        	foreach ($this->headers as $header)
        	{
        		if (is_string($header))
        		{
            		header($header);
        		}
        	}
        }
        // ��������� cookies
        if ($this->cookies != null)
        {        	foreach ($this->cookies as $cookie)
        	{        		setcookie(
            		$cookie['name'],
            		$cookie['value'],
            		$cookie['expire'],
            		$cookie['path'],
            		$cookie['domain'],
            		$cookie['secure'],
            		$cookie['httponly']
        		);        	}        }    	// �������������    	if ($this->redirect != false && $this->redirect != null)
    	{    		if (is_string($this->redirect))
    		{
        		header("Location: ".$this->redirect);
    		}
        	exit;
        }

        // �������� g-zip ��������� �� ������� �������
        $gzip_accept_able = false;
        if (isset($this->_SERVER['HTTP_ACCEPT_ENCODING']))
        {            if (stristr($this->_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
            {                $gzip_accept_able = true;            }
        }

        // ����� �����������
        if ($gzip_accept_able && $CONFIG['gzip']['html'] && in_array($this->sectionType, Array('content', 'page', 'document')))
        {        	ob_start("ob_gzhandler");
        } else {        	ob_start();
        }
        echo $this->content;
        flush();    }

}

?>