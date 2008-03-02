<?PHP
/**
 * Функция регистрации CSS и подключение его в шапку
 *
 * @version    1.0   2008-15-01
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */
class xbkTemplateCssFunction extends patTemplate_Function
{
   /**
	* name of the function
	* @access	private
	* @var		string
	*/
	var $_name	=	'Css';

   /**
    * reference to the patTemplate object that instantiated the module
	*
	* @access	protected
	* @var	object
	*/
	var	$_tmpl;

   /**
    * set a reference to the patTemplate object that instantiated the reader
	*
	* @access	public
	* @param	object		patTemplate object
	*/
	function setTemplateReference( &$tmpl )
	{
		$this->_tmpl		=	&$tmpl;
	}

   /**
	* call the function
	*
	* @access	public
	* @param	array	parameters of the function (= attributes of the tag)
	* @param	string	content of the tag
	* @return	string	content to insert into the template
	*/
	function call( $params, $content )
	{		if (isset($params['name']) && isset($params['order']))
		{
    		$this->_tmpl->addCss($params['name'], $params['order']);
		} else if (isset($params['name']))
		{
			$this->_tmpl->addCss($params['name']);
    	}
		if ($content != '' && $content != null)
		{
			return '<style type="text/css">'.$content.'</style>';		} else return '';
	}
}
?>