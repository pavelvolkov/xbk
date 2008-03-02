<?php

/**
 * Адаптированная версия patTemplate_Reader_File из библиотеки patTemplate
 *
 * @version    1.0   2008-15-01
 * @package    xBk
 * @author     Pavel Bakanov
 * @license	   LGPL
 * @link	   http://bakanov.info
 */
class xbkTemplateFileReader extends patTemplate_Reader
{
   /**
    * reader name
    * @access    private
    * @var        string
    */
    var $_name = 'xbkFile';

   /**
    * flag to indicate, that current file is remote
    *
    * @access    private
    * @var        boolean
    */
    var $_isRemote = true;

   /**
    * all files, that have been opened
    *
    * @access    private
    * @var        array
    */
    var $_files = array();

   /**
    * read templates from any input
    *
    * @final
    * @access    public
    * @param    string    file to parse
    * @return    array    templates
    */
    function readTemplates( $input )
    {    	global $CONFIG;
    	if (isset($this->_rootAtts['relative'])) {
			$relative = $this->_rootAtts['relative'];
		} else {
			$relative = false;
		}
		if ($relative === false) {
       		$this->_currentInput = $input;
		} else {
			$this->_currentInput = dirname($relative) . DIRECTORY_SEPARATOR . $input;
		}

		$content = $this->_getFileContents($this->getTemplateRoot().$input.'.'.$CONFIG['tpl']['ext']);
		if (patErrorManager::isError($content)) {
			return $content;
		}

		$templates = $this->parseString($content);

		return	$templates;
    }

   /**
    * load template from any input
    *
    * If the a template is loaded, the content will not get
    * analyzed but the whole content is returned as a string.
    *
    * @abstract    must be implemented in the template readers
    * @param    mixed    input to load from.
    *                    This can be a string, a filename, a resource or whatever the derived class needs to read from
    * @return    string  template content
    */
    function loadTemplate( $input )
    {
        if (isset($this->_rootAtts['relative'])) {
            $relative = $this->_rootAtts['relative'];
        } else {
            $relative = false;
        }
        return $this->_getFileContents($this->getTemplateRoot().$input.'.'.$CONFIG['tpl']['ext']);
    }

   /**
    * get the contents of a file
    *
    * @access    private
    * @param    string        filename
    * @return    string        file contents
    */
    function _getFileContents( $file )
    {
        if (function_exists('file_get_contents')) {
            $content = @file_get_contents( $file );
        } else {
            $content = implode('', file($file));
        }

        /**
         * store the file name
         */
        array_push($this->_files, $file);

        return $content;
    }

   /**
	* handle start element
	*
	* @access	private
	* @param	string		element name
	* @param	array		attributes
	*/
	function _startElement( $ns, $name, $attributes )
	{
		array_push( $this->_elStack, array(
                                            'ns'			=>  $ns,
											'name'			=>	$name,
											'attributes'	=>	$attributes,
										)
				 );

		$this->_depth++;

		$this->_data[$this->_depth]	=	'';

		/**
		 * handle tag
		 */
		switch( $name )
		{
			/**
			 * template
			 */
			case 'tmpl':
				$result	=	$this->_initTemplate( $attributes );
				break;

			/**
			 * sub-template
			 */
			case 'sub':
				$result	=	$this->_initSubTemplate( $attributes );
				break;

			/**
			 * link
			 */
			case 'link':
				$result	=	$this->_initLink( $attributes );
				break;

			/**
			 * variable
			 */
			case 'var':
				$result	=	false;
				break;

			/**
			 * instance
			 */
			case 'instance':
			case 'comment':
				$result	=	false;
				break;

			/**
			 * any other tag
			 */
			default:
				if (isset($this->_funcAliases[strtolower($name)])) {
					$name = $this->_funcAliases[strtolower($name)];
				}
				$name = ucfirst( $name );

				if( !$this->_tmpl->moduleExists( 'Function', $name ) ) {

					if (isset($this->_options['defaultFunction']) && !empty($this->_options['defaultFunction'])) {
						$attributes['_originalTag'] = $name;
						$name = ucfirst($this->_options['defaultFunction']);
					} else {
					}
				}
				$result = array(
								'type'       => 'custom',
								'function'   => $name,
								'attributes' => $attributes
								);
				break;
		}

		if( patErrorManager::isError( $result ) ) {
			return	$result;
		}

		array_push( $this->_tmplStack, $result );
		return true;
	}

}
?>