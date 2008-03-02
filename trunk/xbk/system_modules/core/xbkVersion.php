<?php

/**
 * xbkVersion
 *
 * ������ �������
 *
 * @version       1.0   2008-03-02
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
     * ������� ��������� ������ ����
     */
    public static $coreChangelog = Array(
                                        Array(
                                            'version' => '1.0',
                                            'migration' => '1'
                                        ),
                                        Array(
                                            'version' => '1.1',
                                            'migration' => '2'
                                        )
                                    );

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