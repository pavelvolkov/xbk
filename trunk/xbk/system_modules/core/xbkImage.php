<?php

/**
 * xbkImage
 *
 * Работа с изображениями
 *
 * @version    1.0   2008-01-30
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkImage extends xbkContextObject
{
    /**
     * Резурс изображения
     *
     * @access    protected
     * @var       array
     */
    protected $img = null;

    /**
     * Бинарный файл изображения
     *
     * @access    protected
     * @var       array
     */
    protected $buffer = null;

    /**
     * Формат изображения
     *
     * @access    protected
     * @var       array
     */
    protected $type = null;

    /**
     * Ширина изображения в пикселах
     *
     * @access    protected
     * @var       array
     */
    protected $width = null;

    /**
     * Высота изображения в пикселах
     *
     * @access    protected
     * @var       array
     */
    protected $height = null;

    /**
     * Типы изображений
     *
     * @access    public
     * @var       array
     */
    public $typeList = Array('gif', 'png', 'jpeg');
    /**
     * Загружает из файла, возвращает true в случае удачи, иначе - false
     *
     * @access    public
     * @return    boolean
     */
    public function loadFromFile ($file)
    {
    	$type = false;
        $img = @imagecreatefromjpeg($file);
        if ($img != false) $type = 'jpeg';
        else {
            $img = @imagecreatefromgif($file);
            if ($img != false) $type = 'gif';
            else {
                $img = @imagecreatefrompng($file);
                if ($img != false) $type = 'png';
            }
        }
        if ($img == false) return false;
        else {        	$this->type = $type;        	$this->img = $img;
        	$this->buffer = file_get_contents($file);        	return true;        }
    }

    /**
     * Возвращает формат изображения, если оно было успешно загружено,
     * иначе - null
     *
     * @access    public
     * @return    boolean
     */
    public function getType ()
    {    	return $this->type;
    }

    /**
     * Возвращает ширину изображения, если оно было успешно загружено,
     * иначе - null
     *
     * @access    public
     * @return    boolean
     */
    public function getWidth ()
    {    	if ($this->img != null)
    	{    		$this->width = imageSX($this->img);    	}
    	return $this->width;
    }

    /**
     * Возвращает высоту изображения, если оно было успешно загружено,
     * иначе - null
     *
     * @access    public
     * @return    boolean
     */
    public function getHeight ()
    {    	if ($this->img != null)
    	{
    		$this->height = imageSY($this->img);
    	}
    	return $this->height;
    }

    /**
     * Возвращает буфер изображения, если оно было успешно загружено,
     * иначе - null
     *
     * @access    public
     * @return    boolean
     */
    public function getBuffer ()
    {
    	return $this->buffer;
    }
}

?>