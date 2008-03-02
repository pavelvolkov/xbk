<?php

/**
 * xbkModuleSection
 *
 * ����� ������ ������
 *
 * @version    1.0   2008-02-04
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkModuleSection
{
    /**
     * ��� ������
     *
     * @access    public
     * @var       string
     */
	public $name;

    /**
     * ��� ������
     *
     * content|page|document|image|file|blank
     *
     * @access    public
     * @var       string
     */
	public $type;

    /**
     * ��� ������
     *
     * @access    public
     * @var       string
     */
	public $class;

    /**
     * ������ �������� �������� ������
     *
     * @access    public
     * @var       array
     */
	public $subsections = Array();

    /**
     * �������������� ����� SSL
     *
     * @access    public
     * @var       boolean
     */
	public $enforceSSL = null;

    /**
     * �������������� ����� �� SSL
     *
     * @access    public
     * @var       boolean
     */
	public $enforceNonSSL = null;
    /**
     * ����������� ������
     *
     * @access     public
     * @param      object DOMNode
     */
    public function __construct ($source = null, &$parentSection = null)
    {    	if ($source != null)
    	{
            $this->parse($source, $parentSection);
        }
    }

    /**
     * ��������� �������� ������
     *
     * @access     public
     * @param      object DOMNode
     */
    public function parse (&$document, $source, &$parentSection = null)
    {    	if (is_object($source) ? (get_class($source) == 'DOMElement') : false)
    	{
            if ($source->hasAttribute('type') &&
                $source->hasAttribute('name') &&
                $source->hasAttribute('class')
            ) {            	$this->name = $source->getAttribute('name');
                $this->type = $source->getAttribute('type');
                $this->class = $source->getAttribute('class');
                // ������������ ��������
                if ($source->hasAttribute('enforceSSL'))
                {
                    $this->enforceSSL = xbkFunctions::str2bool($source->getAttribute('enforceSSL'));
                } else {
                    $this->enforceSSL = false;
                }
                if ($source->hasAttribute('enforceNonSSL'))
                {
                    $this->enforceNonSSL = xbkFunctions::str2bool($source->getAttribute('enforceNonSSL'));
                } else {
                	$this->enforceNonSSL = false;
                }
                // ���������
                $this->subsections = $this->getSections($document, $source, $this);
            }
        }
    }

    /**
     * ���������� ������ ������
     *
     * @access     public
     * @param      object DOMNodeList
	 * @return	   array
    */
    public function getSections (&$document, &$source, &$parentSection = null)
    {    	$sections = Array();
    	// ����������� ���������
        if (get_class($source) == 'DOMNodeList')
        {        	$src = $source;
        }
        else if (get_class($source) == 'DOMElement')
        {        	$xpath = new DOMXPath($document);
        	$src = $xpath->query("section", $source);        }
        else if (get_class($source) == 'DOMDocument')
        {
        	$src = $source->getElementsByTagName('collection')->item(0)
        	->getElementsByTagName('sections')->item(0)
        	->getElementsByTagName('section');
        } else $src = null;
        // ������ ���������
        if ($src != null)
        {
            if ($src->length > 0)
            {
            	for ($s=0; $s<$src->length; $s++)
            	{            		array_push($sections, new xbkModuleSection($document, $src->item($s), $parentSection));
            		// ������������ ���������� �� ����������� ������
            		if (is_object($parentSection) ? (get_class($parentSection) == 'xbkModuleSection') : false)
            		{            			$n = count($sections) - 1;
            			if ($parentSection->enforceSSL != null)
            			{                			$sections[$n]->enforceSSL = $parentSection->enforceSSL;
            			}
            			if ($parentSection->enforceNonSSL != null)
            			{
                			$sections[$n]->enforceNonSSL = $parentSection->enforceNonSSL;
            			}            		}
            	}
            }
        }
        return $sections;
    }

    /**
     * ���������� ������ ������ �� ������� ������
     *
     * @access     public
     * @param      object xbkModule_Record
	 * @return	   array
    */
    public function getSectionsFromModel (&$Record)
    {
        $sections = Array();

        if ($Record instanceof xbkModule_Record)
        {
            foreach ($Record->Section as $Section)
    		{    			$ModuleSection = new xbkModuleSection();    			$ModuleSection->name = $Section->name;
    			$ModuleSection->type = $Section->type;
    			$ModuleSection->class = $Section->class;
    			$ModuleSection->enforceSSL = $Section->enforce_ssl;
    			$ModuleSection->enforceNonSSL = $Section->enforce_non_ssl;
    			$ModuleSection->subsections = self::getSectionsFromModel($Section);
    			array_push($sections, $ModuleSection);
    		}
		} else if ($Record instanceof xbkSection_Record)
		{			foreach ($Record->Subsection as $Subsection)
    		{
    			$ModuleSection = new xbkModuleSection();
    			$ModuleSection->name = $Subsection->name;
    			$ModuleSection->type = $Subsection->type;
    			$ModuleSection->class = $Subsection->class;
    			$ModuleSection->enforceSSL = $Subsection->enforce_ssl;
    			$ModuleSection->enforceNonSSL = $Subsection->enforce_non_ssl;
    			// ������������ ����������
    			if ($Record->enforce_ssl != null)
    			{
    				$ModuleSection->enforceSSL = $Record->enforce_ssl;
        		}
    			if ($Record->enforce_non_ssl != null)
    			{
    				$ModuleSection->enforceNonSSL = $Record->enforce_non_ssl;
    			}
    			$ModuleSection->subsections = self::getSectionsFromModel($Section);
    			array_push($sections, $ModuleSection);
    		}		}

        return $sections;
    }
    /**
     * ��������� �� ������� ���������
     *
     * @access     public
     * @return      boolean
     */
    public function hasSubsections ()
    {    	if (count($this->subsections) > 0)
    	{    		return true;    	} else {    		return false;
    	}
    }

    /**
     * ���������� ������ ��������� �� � �����, ���� false � ������ �������
     *
     * @access     public
     * @param      string
     * @return     object ��� false
     */
    public function getSubsection ($subsectionName)
    {    	foreach ($this->subsections as $subsection)
    	{    		if ($subsection->name == $subsectionName)
    		{                //echo $subsection->name;    			return $subsection;    		}    	}
    }

    /**
     * ���������� ������ ���������
     *
     * @access     public
     * @return     array
     */
    public function getSubsections ()
    {
    	return $this->subsections;
    }

    /**
     * ���������� ��� ������
     *
     * @access     public
     * @return     string
     */
    public function getType ()
    {
    	return $this->type;
    }
}

?>