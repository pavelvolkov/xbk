<?php

/**
 * xbkSection - класс секции
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
    * Список допустимых типов секции
    *
    * @access    protected
    * @type      array
    */
    protected $availableTypes = Array('content', 'page', 'document', 'image', 'file', 'blank');
   /**
    * Тип секции: content|page|document|image|file|blank
    *
    * @access    protected
    * @type      string
    */
    protected $type = null;

   /**
    * Список Cookies
    *
    * @access    protected
    * @type      array
    */
    protected $cookies = Array();

   /**
    * Список Title
    *
    * @access    protected
    * @type      array
    */
    protected $titles = Array();

   /**
    * Содержание секции
    *
    * @access    protected
    * @type      string
    */
    protected $content = null;

   /**
    * Переадесация - адрес или false
    *
    * @access    protected
    * @type      string or false
    */
    protected $redirect = false;

   /**
    * Заголовки
    *
    * @access    protected
    * @type      array
    */
    protected $headers = Array();

   /**
    * Список допустимых типов секции
    *
    * @access    protected
    * @type      array
    */
    protected $availableImageTypes = Array('jpeg', 'gif', 'png');

   /**
    * Тип изображения, если секция имеет тип 'image'
    *
    * @access    protected
    * @type      string
    */
    protected $imageType = 'jpeg';
   /**
    * Устанавливает тип секции
    *
    * @access    public
    * @param     string
    */
    public function setType ($type)
    {
    	if (in_array($type, $this->availableTypes)) $this->type = $type;
    }

   /**
    * Возвращает тип секции
    *
    * @access    public
    * @return    string
    */
    final public function getType ()
    {
    	return $this->type;
    }

   /**
    * Устанавливает cookie
    * Повторение параметров функции setcookie()
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
    * Сбрасывает cookie
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
    * Проверяет наличие cookies
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
    * Возвращает cookies
    *
    * @access    public
    * @return    array
    */
    final public function getCookies ()
    {
    	return $this->cookies;
    }

   /**
    * Устанавливает заголовок документа
    *
    * @access    public
    * @param     string
    */
    public function setTitle ($title)
    {
    	$this->titles = Array($title);
    }

   /**
    * Дополняет заголовок документа
    *
    * @access    public
    * @param     string
    */
    public function addTitle ($title)
    {
    	array_push($this->titles, $title);
    }

   /**
    * Возвращает заголовок документа
    *
    * @access    public
    * @return    string
    */
    final public function getTitle ()
    {
    	return implode(' :: ', $this->titles);
    }
   /**
    * Устанавливает содержание
    *
    * @access    public
    * @param     string
    */
    public function setContent ($content)
    {
    	$this->content = $content;
    }

   /**
    * Возвращает содержание
    *
    * @access    public
    * @return    string
    */
    final public function getContent ()
    {
    	return $this->content;
    }

   /**
    * Устанавливает http-заголовок
    *
    * @access    public
    * @param     string
    */
    public function setHeader ($header)
    {
    	array_push($this->headers, $header);
    }

   /**
    * Возвращает http-заголовки
    *
    * @access    public
    * @return    string
    */
    final public function getHeaders ()
    {
    	return $this->headers;
    }

   /**
    * Устанавливает переадресацию
    *
    * @access    public
    * @param     string
    */
    public function setRedirect ($uri)
    {
    	if (is_string($uri)) $this->redirect = $uri;
    }

   /**
    * Возвращает переадресацию
    *
    * @access    public
    * @return    string or false
    */
    final public function getRedirect ()
    {
    	return $this->redirect;
    }

   /**
    * Проверяет на переадресацию
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
    * Устанавливает тип изображения
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
    * Возвращает тип изображения
    *
    * @access    public
    * @return    boolean
    */
    public function getImageType ()
    {
		return $this->imageType;
    }

   /**
    * Устанавливает 404 секцию
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