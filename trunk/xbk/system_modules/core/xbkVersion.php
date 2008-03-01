<?php

/**
 * xbkVersion
 *
 * ������� ������ �������
 *
 * @version       1.0   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

final class xbkVersion
{
    /**
     * ������� ������ ������� xBk
     */
    const VERSION = '0.3';

    /**
     * ��������� ��������� � ������� ������� �������
     *
     * @param  string
     * @return integer
     */
    public static function compareVersion($version)
    {
        return version_compare($version, self::VERSION);
    }
}

?>