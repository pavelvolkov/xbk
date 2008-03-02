<?php

/**
 * xbkSection - ����� ������
 *
 * @version       1.0   2008-01-28
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

abstract class xbkSection extends xbkContextObject
{
   /**
    * ������ ���������� ����� ������
    *
    * @access    protected
    * @type      array
    */
    protected $availableTypes = Array('content', 'page', 'document', 'image', 'file', 'blank');
   /**
    * ��� ������: content|page|document|image|file|blank
    *
    * @access    protected
    * @type      string
    */
    protected $type = null;

   /**
    * ������ Cookies
    *
    * @access    protected
    * @type      array
    */
    protected $cookies = Array();

   /**
    * ������ Title
    *
    * @access    protected
    * @type      array
    */
    protected $titles = Array();

   /**
    * ���������� ������
    *
    * @access    protected
    * @type      string
    */
    protected $content = null;

   /**
    * ������������ - ����� ��� false
    *
    * @access    protected
    * @type      string or false
    */
    protected $redirect = false;

   /**
    * ���������
    *
    * @access    protected
    * @type      array
    */
    protected $headers = Array();

   /**
    * ������ ���������� ����� ������
    *
    * @access    protected
    * @type      array
    */
    protected $availableImageTypes = Array('jpeg', 'gif', 'png');

   /**
    * ��� �����������, ���� ������ ����� ��� 'image'
    *
    * @access    protected
    * @type      string
    */
    protected $imageType = 'jpeg';
   /**
    * ������������� ��� ������
    *
    * @access    public
    * @param     string
    */
    public function setType ($type)
    {
    	if (in_array($type, $this->availableTypes)) $this->type = $type;
    }

   /**
    * ���������� ��� ������
    *
    * @access    public
    * @return    string
    */
    final public function getType ()
    {
    	return $this->type;
    }

   /**
    * ������������� cookie
    * ���������� ���������� ������� setcookie()
    *
    * @access    public
    */
    public function setCookie ($name, $value = '', $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {    	global $CONFIG;    	if ($path == null) $path = $CONFIG['cookie']['path'];
    	if ($domain == null) $domain = $CONFIG['cookie']['domain'];    	$cookie = Array(
        	'name' => $name,
        	'value' => $value,
        	'expire' => $expire,
        	'path' => $path,
        	'domain' => $domain,
        	'secure' => $secure,
        	'httponly' => $httponly
    	);
    	array_push($this->cookies, $cookie);
    }

   /**
    * ���������� cookie
    *
    * @access    public
    */
    public function unsetCookie ($name)
    {
    	global $CONFIG;
    	$cookie = Array(
        	'name' => $name,
        	'value' => '',
        	'expire' => null,
        	'path' => null,
        	'domain' => null,
        	'secure' => null,
        	'httponly' => null
    	);
    	array_push($this->cookies, $cookie);
    }

   /**
    * ��������� ������� cookies
    *
    * @access    public
    * @return    boolean
    */
    public function hasCookies ()
    {
    	if (count($this->cookies) > 0) return true;
    	else return false;
    }

   /**
    * ���������� cookies
    *
    * @access    public
    * @return    array
    */
    final public function getCookies ()
    {
    	return $this->cookies;
    }

   /**
    * ������������� ��������� ���������
    *
    * @access    public
    * @param     string
    */
    public function setTitle ($title)
    {
    	$this->titles = Array($title);
    }

   /**
    * ��������� ��������� ���������
    *
    * @access    public
    * @param     string
    */
    public function addTitle ($title)
    {
    	array_push($this->titles, $title);
    }

   /**
    * ���������� ��������� ���������
    *
    * @access    public
    * @return    string
    */
    final public function getTitle ()
    {
    	return implode(' :: ', $this->titles);
    }
   /**
    * ������������� ����������
    *
    * @access    public
    * @param     string
    */
    public function setContent ($content)
    {
    	$this->content = $content;
    }

   /**
    * ���������� ����������
    *
    * @access    public
    * @return    string
    */
    final public function getContent ()
    {
    	return $this->content;
    }

   /**
    * ������������� http-���������
    *
    * @access    public
    * @param     string
    */
    public function setHeader ($header)
    {
    	array_push($this->headers, $header);
    }

   /**
    * ���������� http-���������
    *
    * @access    public
    * @return    string
    */
    final public function getHeaders ()
    {
    	return $this->headers;
    }

   /**
    * ������������� �������������
    *
    * @access    public
    * @param     string
    */
    public function setRedirect ($uri)
    {
    	if (is_string($uri)) $this->redirect = $uri;
    }

   /**
    * ���������� �������������
    *
    * @access    public
    * @return    string or false
    */
    final public function getRedirect ()
    {
    	return $this->redirect;
    }

   /**
    * ��������� �� �������������
    *
    * @access    public
    * @return    boolean
    */
    final public function isRedirect ()
    {
    	if ($this->redirect != false) return true;
    	else return false;
    }

   /**
    * ������������� ��� �����������
    *
    * @access    public
    * @return    boolean
    */
    final public function setImageType ($type)
    {
    	if (in_array($type, $this->availableImageTypes))
    	{    		$this->imageType = $type;    	}
    }

   /**
    * ���������� ��� �����������
    *
    * @access    public
    * @return    boolean
    */
    public function getImageType ()
    {
		return $this->imageType;
    }

   /**
    * ������������� 404 ������
    *
    * @access    public
    * @return    boolean
    */
    public function set404 ()
    {    	$section404 =& $this->factory('xbk404Error');
    	$this->type = 'content';
    	$this->title = $section404->getTitle();
		$this->content = $section404->getContent();
    }

}

?>