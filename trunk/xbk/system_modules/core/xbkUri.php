<?php

/**
 * xbkUri - ����� ������ � URI
 *
 * @version    1.0   2008-02-09
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkUri extends xbkContextObject
{
	private
		$baseDir = null,
		$uriParameters = null,
		$queryInheritedParameters = Array('xbk_session_id', 'xbk_theme', 'xbk_lang'),
		$queryParameters = Array(),
		$currentBranch = Array();

    /**
     * ����������� ������
     *
     * @access     public
     * @param      string
     */
	public function __construct2($uri = null)
	{		// ��������� URI � ������ $this->uriParameters		if ($uri != null && is_string($uri)) {			// ���� ������ ������			$this->uriParameters = xbkUriParser::parse($uri);
		} else if (isset($this->_SERVER['REQUEST_URI'])) {			// ���� ������ �� ������			$this->uriParameters = xbkUriParser::parse($this->_SERVER['REQUEST_URI']);
		} else $this->uriParameters = xbkUriParser::parse('/');
		// ��������� ������ ������
		if (isset($this->uriParameters['path_dir'])) {			$this->parseBranch();
		}
	}

    /**
     * ������������� ������� ������ �� ������ ��������� ���� �� �����
     *
     * @access     private
     * @param      string
     */
	private function parseBranch ($section_str = null)
	{
		global $CONFIG;
    	if ($section_str == null)
    	{
    		if (isset($this->uriParameters['path']))
    		{
    			if ($CONFIG['path']['web']['root'] == substr($this->uriParameters['path'], 0, strlen($CONFIG['path']['web']['root'])))
    			{
    				$section_str = substr($this->uriParameters['path'], strlen($CONFIG['path']['web']['root']));
    			} else $section_str = $this->uriParameters['path'];
    		} else $section_str = '';
    	}
    	if ($this->isValidPathName($section_str))
    	{
        	$this->currentBranch = explode('/', trim($section_str, '/'));
    	}
	}

    /**
     * ������������� ����� ����
     *
     * @access     public
     * @param      string    ���� �� �����
     */
    public function goto ($path)
    {
        if ($this->isValidPathName($path)) {
            $path = trim($path, '/');
            $this->currentBranch = explode('/', $path);
        }
    }

    /**
     * �������� goto
     *
     * @access     public
     * @param      string
     */
    public function setCurrentBranch ($path)
    {        $this->goto($path);    }

    /**
     * ��������� ������� setCurrentBranch
     *
     * @access     public
     * @param      string    ���� ������
     */
	public function gotoSection ($path)
	{
		$this->setCurrentBranch($path);
	}

    /**
     * ���������� ������� ����
     *
     * @access     public
     * @param      boolean
     * @return     mixed
     */
    public function getPath ($string = true)
    {
        if ($string) return implode('/', $this->currentBranch);
        else return $this->currentBranch;
    }

    /**
     * ��������  getPath
     *
     * @access     public
     * @param      boolean
     * @return     mixed
     */
    public function getCurrentSection ($string = false)
    {
        return $this->getPath($string);
    }

    /**
     * ������� �� ������� ����
     *
     * @access     public
     */
	public function gotoParent ()
	{
		if (count($this->currentBranch) > 0) array_pop($this->currentBranch);
	}

    /**
     * ��������� gotoParent
     *
     * @access     public
     */
	public function gotoParentSection ()
	{
		$this->gotoParent();
	}

    /**
     * ������� �� �������� ����� �������� ������
     *
     * @access     public
     * @param      string     ��� �����
     */
	public function gotoBrother ($name)
	{
		if ($this->isValidQueryParameterName($name))
		{
    		$this->currentBranch[(count($this->currentBranch)-1)] = $name;
		}
	}

    /**
     * ��������� gotoBrother
     *
     * @access     public
     * @param      string     ��� �����
     */
	public function gotoSideSection ($name)
	{
		$this->gotoBrother($name);
	}

    /**
     * ������� �� �������� �����
     *
     * @access     public
     * @param      string     ��� �����
     */
	public function gotoChild ($name)
	{
		if ($this->isValidQueryParameterName($name)) array_push($this->currentBranch, $name);
	}

    /**
     * ������� �� ������ ������ ��������� ������
     *
     * @access     public
     * @param      string     ��� ������
     */
	public function gotoChildSection ($name)
	{
		$this->gotoChild($name);
	}

    /**
     * �������� ���� �� ������� ������ � ��������� ����
     *
     * @access     private
     * @return     string
     */
	private function constructPath ()
	{		global $CONFIG;
		$sub_path = implode('/', $this->currentBranch);
        if ($sub_path != '') return $CONFIG['path']['web']['root'].$sub_path.'/';
        else return $CONFIG['path']['web']['root'].$sub_path;	}

    /**
     * �������� ����������� ���������
     *
     * ����� ����������� ������ ��� ���������� ��� ������ ��� ����������, ���������� �����������
     *
     * @access     public
     * @param      mixed     Array ��� string - �������� ��� ����������
     */
	public function addInheritedParameters ($add = Array())
	{        if (is_array($add))
        {        	foreach ($add as $par)
        	{                if (!in_array($par, $this->queryInheritedParameters) && is_string($par))
                if ($this->isValidQueryParameterName($par)) array_push($this->queryInheritedParameters, $par);        	}        } else if (is_string($add))
        {        	$parts = explode('&', trim($add, '&'));
        	foreach ($parts as $par)
        	{        		if (!in_array($par, $this->queryInheritedParameters) && is_string($par))
        		if ($this->isValidQueryParameterName($par)) array_push($this->queryInheritedParameters, $par);
         	}        }
	}

    /**
     * ������� (��������) ����������� ���������
     *
     * ����� ����������� ������ ��� ���������� ��� ������ ��� ����������, ���������� �����������
     *
     * @access     public
     * @param      mixed     Array ��� string - �������� ��� ����������
     */
	public function removeInheritedParameters ($remove = Array())
	{
        if (is_array($remove)) {
        	foreach ($remove as $par) {
                if (in_array($par, $this->queryInheritedParameters))
                unset($this->queryInheritedParameters[array_search($par, $this->queryInheritedParameters)]);
        	}
        } else if (is_string($remove)) {
        	$parts = explode('&', trim($remove, '&'));
        	foreach ($parts as $par) {
        		if (in_array($par, $this->queryInheritedParameters))
                unset($this->queryInheritedParameters[array_search($par, $this->queryInheritedParameters)]);
         	}
        }
	}

    /**
     * ������� (��������) ��� ����������� ���������
     *
     * @access     public
     * @param      mixed     Array ��� string - �������� ��� ����������
     */
	public function removeAllInheritedParameters ()
	{
        $this->queryInheritedParameters = Array();
	}

    /**
     * �������� �� ������������ ����� ���������
     *
     * @access     public
     * @param      string     ��� ���������
     */
	public function isValidQueryParameterName ($name)
	{		$pattern = "`^[a-zA-Z0-9\-_\[\]]{1,}$`";
		if (preg_match($pattern, $name) > 0) return true;
		else return false;	}

    /**
     * �������� �� ������������ ����� ����
     *
     * @access     public
     * @param      string     ����
     */
	public function isValidPathName ($name)
	{		$valid = true;		$branches = explode('/', trim($name, '/'));
		foreach ($branches as $branch) {			if (!$this->isValidQueryParameterName($branch)) {				$valid = false;
				break;
			}
		}
		return $valid;
	}

    /**
     * ���������� ������� �������� (http ��� https)
     *
     * @access     public
     * @return     string
     */
	public function getScheme ()
	{
        if (!isset($this->uriParameters['scheme']))
        {
            if (isset($this->_SERVER['HTTPS'])) $protocol = 'https';
            else $protocol = 'http';
        } else $protocol = $this->uriParameters['scheme'];
        return $protocol;
	}

    /**
     * �������� getScheme
     *
     * @access     public
     * @return     string
     */
	public function getProtocol ()
	{
        return $this->getScheme();
	}

    /**
     * ������������� �������� (http ��� https)
     *
     * @access     public
     * @param      string
     */
	public function setScheme ($protocol = 'http')
	{
        if ($protocol == 'http' || $protocol == 'https')
        {            $this->uriParameters['scheme'] = $protocol;
            $this->uriParameters['host'] = $this->_SERVER['HTTP_HOST'];        }
	}

    /**
     * �������� setScheme
     *
     * @access     public
     * @return     string
     */
	public function setProtocol ($protocol = 'http')
	{
        $this->setScheme();
	}

    /**
     * ����������� �������� (http �� https � ��������)
     *
     * @access     public
     */
	public function swapProtocol ()
	{
    	if ($this->getProtocol() == 'http') $this->setProtocol('https');
    	else $this->setProtocol('http');
	}

    /**
     * ������������� ��������� ������� ������
     *
     * @access     public
     */
	public function setThisUri ()
	{
    	$this->uriParameters = xbkUriParser::parse($this->_SERVER['REQUEST_URI']);
    	if (isset($this->uriParameters['query'])) {
        	$parameters_array = xbkUriParser::parseQuery($this->uriParameters['query']);
        	$queryInheritedParameters = Array();
        	foreach ($parameters_array as $key => $value) {            	array_push($queryInheritedParameters, $key);        	}
        	$this->queryInheritedParameters = array_merge($this->queryInheritedParameters, $queryInheritedParameters);
    	}
    	if (isset($this->uriParameters['path_dir'])) $this->parseBranch();
	}

    /**
     * ���������� � ���������� ������
     *
     * @access     public
     * @param      mixed     �������������� ���������
     * @param      mixed     ����������� �������� ��� ����������� ����������
     * @return     string
     */
	public function build ($parameters = Array(), $excludeParameters = Array())
	{		// ������ ���������� �������� ������        $finalParameters_array = Array();
        // ��������� ����������� � �������
        if (is_array($parameters)) $parameters_array = $parameters;
        else {            $parameters_array = xbkUriParser::parseQuery($parameters);        }
        // ���������, ����������� �� �������� ������ ����������� ����������
        if (is_array($excludeParameters)) $excludeParameters_list = $excludeParameters;
        else {
            $excludeParameters_list = explode('&', $excludeParameters);
        }
        // ������ ����������� ���������� ����������
        $inheritedParameters_list = $this->queryInheritedParameters;
        // ������������ ��������� �� ������ ������
        $existsParameters_array = Array();
        if (isset($this->uriParameters['query'])) {        	$existsParameters_array = xbkUriParser::parseQuery($this->uriParameters['query']);
        }
        // ���������� ����������� ����������
        foreach ($inheritedParameters_list as $key => $value) {        	if (!in_array($value, $excludeParameters_list) && isset($existsParameters_array[$value])) {
            	$finalParameters_array[$value] = $existsParameters_array[$value];
        	}        }
        // ������ ���������� � �������� �����
        $finalParameters_array = array_merge($finalParameters_array, $parameters_array);
        // ��������� ������
        $uri_parameters = array_merge($this->uriParameters, Array('path' => $this->constructPath(), 'query' => xbkUriParser::glueQuery($finalParameters_array)));
        return xbkUriParser::glue($uri_parameters);	}

    /**
     * ��������� ������ build()
     */
	public function generate ($parameters = Array(), $excludeParameters = Array())
	{		return $this->build($parameters, $excludeParameters);	}


	public function __toString ()
	{		return $this->build();
	}

}

?>