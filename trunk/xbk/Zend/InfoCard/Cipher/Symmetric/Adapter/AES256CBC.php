<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_InfoCard
 * @subpackage Zend_InfoCard_Cipher
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Exception.php 2794 2007-01-16 01:29:51Z bkarwin $
 * @author     John Coggeshall <john@zend.com>
 */

/**
 * Zend_InfoCard_Cipher_Symmetric_Adapter_Abstract
 */
require_once 'Zend/InfoCard/Cipher/Symmetric/Adapter/Abstract.php';

/**
 * Zend_InfoCard_Cipher_Symmetric_AES256CBC_Interface
 */
require_once 'Zend/InfoCard/Cipher/Symmetric/AES256CBC/Interface.php';

/**
 * Zend_InfoCard_Cipher_Exception
 */
require_once 'Zend/InfoCard/Cipher/Exception.php';

/**
 * Implements AES256 with CBC encryption implemented using the mCrypt extension
 * 
 * @category   Zend
 * @package    Zend_InfoCard
 * @subpackage Zend_InfoCard_Cipher
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @author     John Coggeshall <john@zend.com>
 */
class Zend_InfoCard_Cipher_Symmetric_Adapter_AES256CBC 
    extends Zend_InfoCard_Cipher_Symmetric_Adapter_Abstract
    implements Zend_InfoCard_Cipher_Symmetric_AES256CBC_Interface 
{
	/**
	 * The MCRYPT Cipher constant for this encryption
	 */
	const MCRYPT_CIPHER = MCRYPT_RIJNDAEL_128;
	
	/**
	 * The MCRYPT Mode constant for this encryption
	 */
	const MCRYPT_MODE   = MCRYPT_MODE_CBC;
	
	/**
	 * The default length of the IV to use
	 */
	const IV_LENGTH     = 16;

	/**
	 * The object constructor
	 * 
	 * @throws Zend_InfoCard_Cipher_Exception
	 */
	public function __construct() 
    {
    	// Can't test for this
    	// @codeCoverageIgnoreStart
		if(!extension_loaded('mcrypt')) {
			throw new Zend_InfoCard_Cipher_Exception("Use of the AES256CBC Cipher requires the mcrypt extension");
		}
		// @codeCoveregIgnoreEnd
	}
	
	/**
	 * Decrypts data using the AES Algorithm using the mCrypt extension
	 *
	 * @throws Zend_InfoCard_Cipher_Exception
	 * @param string $encryptedData The encrypted data in binary format
	 * @param string $decryptionKey The decryption key
	 * @param integer $iv_length The IV length to use
	 * @return string the decrypted data with any terminating nulls removed
	 */
	public function decrypt($encryptedData, $decryptionKey, $iv_length = null) 
    {
		
		$iv_length = is_null($iv_length) ? self::IV_LENGTH : $iv_length;
		
		$mcrypt_iv = null;
		
		if($iv_length > 0) {
		 	$mcrypt_iv = substr($encryptedData, 0, $iv_length);
        	$encryptedData = substr($encryptedData, $iv_length);
		}
		
		$decrypted = mcrypt_decrypt(self::MCRYPT_CIPHER, $decryptionKey, $encryptedData, self::MCRYPT_MODE, $mcrypt_iv);
		
		if(!$decrypted) {
			throw new Zend_InfoCard_Cipher_Exception("Failed to decrypt data using AES256CBC Algorithm");
		}

		$decryptedLength = strlen($decrypted);
		$paddingLength = substr($decrypted, $decryptedLength -1, 1);
		$decrypted = substr($decrypted, 0, $decryptedLength - ord($paddingLength));
		
		return rtrim($decrypted, "\0");
	}
}
