<?php

/**
 * xbkPage
 *
 * ����� ��������
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    page
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkPage extends xbkContextObject
{
   /**
    * ��������� ��������
    *
    * @access    public
    * @var       string
    */
    public $charset = null;

   /**
    * Expire
    *
    * @access    public
    * @var       string
    */
    public $expire = null;

   /**
    * ������������� ����� �����������
    *
    * @access    public
    * @var       string
    */
    public $lang = null;

   /**
    * Distribution
    *
    * @access    public
    * @var       string
    */
    public $distribution = null;
   /**
    * ��������� ��������
    *
    * @access    public
    * @var       array
    */
    public $title = Array();

   /**
    * �������� �����
    *
    * @access    public
    * @var       array
    */
    public $keywords = Array();

   /**
    * ��������
    *
    * @access    public
    * @var       string
    */
    public $description = null;

   /**
    * ����-����
    *
    * @access    public
    * @var       string
    */
    public $meta = Array();

   /**
    * ���������� ��������� �������
    *
    * @access    public
    * @var       string
    */
    public $robots = null;

   /**
    * ������ ������ �� CSS
    *
    * @access    public
    * @var       array
    */
    public $css = Array();

   /**
    * ������ ������ �� JS
    *
    * @access    public
    * @var       array
    */
    public $js = Array();

   /**
    * ���������� ��������
    *
    * @access    public
    * @var       string
    */
    public $content;

   /**
    * ����������� ������
    *
    * @access    public
    */
    public function __construct2 ($content = null)
    {
    	global $CONFIG;
    	// ��������� ��������� ��-���������
        $this->charset = $CONFIG['interface'][$CONFIG['lang']]['charset'];
        // ��������� ����� ��-���������
        $this->lang = $CONFIG['lang'];
        // ��������� �����������
        if ($content != null) $this->content = $content;
    }

   /**
    * ������������� ���������
    *
    * @access    public
    * @param     string
    */
    public function setCharset ($charset)
    {
    	$this->charset = $charset;
    }

   /**
    * ������������� expire
    *
    * @access    public
    * @param     string
    */
    public function setExpire ($expire)
    {
    	$this->expire = $expire;
    }

   /**
    * ������������� lang
    *
    * @access    public
    * @param     string
    */
    public function setLang ($lang)
    {
    	$this->lang = $lang;
    }

   /**
    * ������������� distribution
    *
    * @access    public
    * @param     string
    */
    public function setDistribution ($distribution)
    {
    	$this->distribution = $distribution;
    }

   /**
    * ������������� �������� �����
    *
    * @access    public
    * @param     array
    */
    public function setKeywords ($keywords)
    {
    	$this->keywords = $keywords;
    }

   /**
    * ������������� ���������
    *
    * @access    public
    * @param     array ��� string
    */
    public function setTitle ($title)
    {    	if (is_string($title)) {    		array_push($this->title, $title);
    	} else if (is_array($title)) {    		array_merge($this->title, $title);    	}
    }

   /**
    * ������������� ��������
    *
    * @access    public
    * @param     string
    */
    public function setDescription ($description)
    {
    	$this->description = $description;
    }

   /**
    * ������������� ���������� ��������� �������
    *
    * @access    public
    * @param     string
    */
    public function setRobots ($robots)
    {
    	$this->robots = $robots;
    }

   /**
    * ������������� ������ ������ �� CSS
    *
    * @access    public
    * @param     array
    */
    public function setCss ($css)
    {
    	$this->css = $css;
    }

   /**
    * ������������� ������ ������ �� JS
    *
    * @access    public
    * @param     array
    */
    public function setJs ($js)
    {
    	$this->js = $js;
    }

   /**
    * ���������� ���� ����������� CSS
    *
    * @access    public
    * @return    string
    */
    public function buildMetaTags ()
    {
    	$meta_tags = '';
    	foreach ($this->meta as $meta)
    	{
    		$meta_tags .= "
    		<meta name=\"".htmlspecialchars($meta['name'])."\" content=\"".htmlspecialchars($meta['content'])."\" />";
    	}
    	return $meta_tags;
    }

   /**
    * ������ � ���������� ���������
    *
    * @access    public
    * @return    string
    */
    public function buildTitle ()
    {
    	if (is_array($this->title))
    	{    		$glue = ' :: ';
    		return implode($glue, $this->title);    	} else return $scc_tags;
    }

   /**
    * ���������� ���� ����������� CSS
    *
    * @access    public
    * @return    string
    */
    public function buildCssTags (&$tmpl)
    {    	global $CONFIG;    	$scc_tags = '';

    	$pathToCssWeb = $tmpl->getPathToSkin('web').$CONFIG['path']['internal']['css'];
    	$pathToCssPhp = $tmpl->getPathToSkin('php').$CONFIG['path']['internal']['css'];
    	$cssDir = trim($pathToCssPhp, "/");

    	// ���������� CSS ����� ������ Page, ������������ ���������
    	foreach(scandir($cssDir) as $css)
    	{    		if (!in_array($css, Array('.', '..')))
    		{        		$scc_tags .= "\n<link href=\"".htmlspecialchars($pathToCssWeb.$css)."\" rel=\"stylesheet\" type=\"text/css\" />";
    		}    	}

    	// ��������� ����������� CSS �����
    	foreach ($this->css as $css)
    	{    		$scc_tags .= "\n<link href=\"".htmlspecialchars($css)."\" rel=\"stylesheet\" type=\"text/css\" />";    	}

    	return $scc_tags;
    }

   /**
    * ���������� ���� ����������� JS
    *
    * @access    public
    * @return    string
    */
    public function buildJsTags ()
    {
    	$js_tags = '';
    	foreach ($this->js as $js)
    	{
    		$js_tags .= "\n<script type=\"text/javascript\" src=\"".$js."\"></script>";
    	}
    	return $js_tags;
    }
   /**
    * ���������� � ���������� ����������
    *
    * @access    public
    * @return    string
    */
    public function getContent ()
    {    	global $CONFIG;
    	$charset = $this->_Registry->getCharset();    	$meta = '';
    	$css = '';
    	$js = '';
    	$title = htmlspecialchars(implode('<<', array_reverse($this->title)), ENT_NOQUOTES, $charset);    	$tmpl = $this->template();
    	$tmpl->readTemplatesFromInput('page');
    	$tmpl->addGlobalVar('LANG', $this->_Registry->getLangIndex());
    	$tmpl->addGlobalVar('CHARSET', $charset);
    	$tmpl->addGlobalVar('META', $this->buildMetaTags());
    	$tmpl->addGlobalVar('TITLE', $this->buildTitle());
    	$tmpl->addGlobalVar('CSS', $this->buildCssTags($tmpl));
    	$tmpl->addGlobalVar('JS', $this->buildJsTags($tmpl));
    	$tmpl->addGlobalVar('CONTENT', $this->content);        return $tmpl->getParsedTemplate('page');    }
}

?>