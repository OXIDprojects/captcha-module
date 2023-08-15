<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class handling CAPTCHA image
 * This class requires utility file utils/verificationimg.php as image generator
 *
 */
class oeCaptcha extends oxSuperCfg
{
    /**
     * CAPTCHA length
     *
     * @var int
     */
    protected $macLength = 5;

    /**
     * Captcha text
     *
     * @var string
     */
    protected $text = null;

    /**
     * Possible CAPTCHA chars, no ambiguities
     *
     * @var string
     */
    protected $macChars = 'abcdefghijkmnpqrstuvwxyz23456789';

    /**
     * Captcha timeout 60 * 5 = 5 minutes
     *
     * @var int
     */
    protected $timeout = 300;

    /**
     * Returns text
     *
     * @return string
     */
    public function getText()
    {
        if (!$this->text) {
            $this->text = '';
            for ($i = 0; $i < $this->macLength; $i++) {
                $this->text .= strtolower($this->macChars[rand(0, strlen($this->macChars) - 1)]);
            }
        }

        return $this->text;
    }

    /**
     * Returns text hash
     *
     * @param string $text User supplie text
     *
     * @return string
     */
    public function getHash($text = null)
    {
        // inserting captcha record
        $time = time() + $this->timeout;
        $textHash = $this->getTextHash($text);

        // if session is started - storing captcha info here
        $session = $this->getSession();
        if ($session->isSessionStarted()) {
            $hash = oxUtilsObject::getInstance()->generateUID();
            $hashArray = $session->getVariable('captchaHashes');
            $hashArray[$hash] = array($textHash => $time);
            $session->setVariable('captchaHashes', $hashArray);
        } else {
            $database = DatabaseProvider::getDb();
            $query = "insert into oecaptcha (oxhash, oxtime) values (" .
                      $database->quote($textHash) . ", " . $database->quote($time) . ")";
            $database->execute($query);
            $hash = $database->getOne('select LAST_INSERT_ID()', false, false);
        }

        return $hash;
    }

    /**
     * Returns given string captcha hash
     *
     * @param string $text string to hash
     *
     * @return string
     */
    public function getTextHash($text)
    {
        if (!$text) {
            $text = $this->getText();
        }
        $text = strtolower($text);

        return md5('ox' . $text);
    }

    /**
     * Returns url to CAPTCHA image generator.
     *
     * @return string
     */
    public function getImageUrl()
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $url = $config->getCurrentShopUrl() . 'modules/oe/captcha/core/utils/verificationimg.php?e_mac=';
        $key = $config->getConfigParam('oecaptchakey');

        $key = $key ? $key : $config->getConfigParam('sConfigKey');

        $encryptor = new \OxidEsales\Eshop\Core\Encryptor();
        $url .= $encryptor->encrypt($this->getText(), $key);

        return $url;
    }

    /**
     * Checks if image could be generated
     *
     * @return bool
     */
    public function isImageVisible()
    {
        return ((function_exists('imagecreatetruecolor') || function_exists('imagecreate')) && $this->getConfig()->getConfigParam('iUseGDVersion') > 1);
    }

    /**
     * Check if captcha is passed.
     *
     * @return bool
     */
    public function passCaptcha($displayError = true)
    {
        $return = true;

        // spam spider prevention
        $mac = $this->getConfig()->getRequestParameter('c_mac');
        $macHash = $this->getConfig()->getRequestParameter('c_mach');

        if (!$this->pass($mac, $macHash)) {
            $return = false;
        }

        if (!$return && $displayError) {
            // even if there is no exception, use this as a default display method
            oxRegistry::get('oxUtilsView')->addErrorToDisplay('MESSAGE_WRONG_VERIFICATION_CODE');
        }

        return $return;
    }

    /**
     * Verifies captcha input vs supplied hash. Returns true on success.
     *
     * @param string $mac     User supplied text
     * @param string $macHash Generated hash
     *
     * @return bool
     */
    protected function pass($mac, $macHash)
    {
        $time = time();
        $hash = $this->getTextHash($mac);
        $pass = $this->passFromSession($macHash, $hash, $time);

        // if captcha info was NOT stored in session
        if ($pass === null) {
            $pass = $this->passFromDb((int) $macHash, $hash, $time);
        }

        return (bool) $pass;
    }

    /**
     * Checks for session captcha hash validity
     *
     * @param string $macHash hash key
     * @param string $hash    captcha hash
     * @param int    $time    check time
     *
     * @return bool
     */
    protected function passFromSession($macHash, $hash, $time)
    {
        $pass = null;
        $session = $this->getSession();

        if (($hashArray = $session->getVariable('captchaHashes'))) {
            $pass = (isset($hashArray[$macHash][$hash]) && $hashArray[$macHash][$hash] >= $time) ? true : false;
            unset($hashArray[$macHash]);
            if (!empty($hashArray)) {
                $session->setVariable('captchaHashes', $hashArray);
            } else {
                $session->deleteVariable('captchaHashes');
            }
        }

        return $pass;
    }

    /**
     * Checks for DB captcha hash validity
     *
     * @param int    $macHash hash key
     * @param string $hash    captcha hash
     * @param int    $time    check time
     *
     * @return bool
     */
    protected function passFromDb($macHash, $hash, $time)
    {
        $database = DatabaseProvider::getDb();
        $where = "where oxid = " . $database->quote($macHash) . " and oxhash = " . $database->quote($hash);
        $query = "select 1 from oecaptcha " . $where;
        $pass = (bool) $database->getOne($query, false, false);

        if ($pass) {
            // cleanup
            $query = "delete from oecaptcha " . $where;
            $database->execute($query);
        }

        // garbage cleanup
        $query = "delete from oecaptcha where oxtime < $time";
        $database->execute($query);

        return $pass;
    }

}
