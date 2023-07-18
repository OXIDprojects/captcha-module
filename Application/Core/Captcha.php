<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidProfessionalServices\Captcha\Application\Shared\Connection;
use OxidProfessionalServices\Captcha\Application\Shared\Options;

class Captcha
{
    use Connection;
    use Options;
    public const ENCRYPT_KEY = 'fq45QS09_fqyx09239QQ';

    /**
     * CAPTCHA length.
     *
     * @var int
     */
    protected $macLength = 5;

    /**
     * Captcha text.
     *
     * @var string
     */
    protected $text;

    /**
     * Possible CAPTCHA chars, no ambiguities.
     *
     * @var string
     */
    protected $macChars = 'abcdefghijkmnpqrstuvwxyz23456789';

    /**
     * Captcha timeout 60 * 5 = 5 minutes.
     *
     * @var int
     */
    protected $timeout = 300;

    public static function getInstance(): static
    {
        return oxNew(static::class);
    }

    /**
     * Returns text.
     *
     * @return string
     */
    public function getText()
    {
        if (!$this->text) {
            $this->text = '';

            for ($i = 0; $i < $this->macLength; ++$i) {
                $this->text .= strtolower($this->macChars[rand(0, strlen($this->macChars) - 1)]);
            }
        }

        return $this->text;
    }

    /**
     * Returns given string captcha hash.
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
     * Check if captcha is passed.
     *
     * @param mixed $displayError
     *
     * @return bool
     */
    public function passCaptcha($displayError = true)
    {
        $return = true;

        // spam spider prevention
        $mac     = Registry::getConfig()->getRequestParameter('c_mac');
        $macHash = Registry::getConfig()->getRequestParameter('c_mach');

        if (!$this->pass($mac, $macHash)) {
            $return = false;
        }

        if (!$return && $displayError) {
            // even if there is no exception, use this as a default display method
            Registry::getUtilsView()->addErrorToDisplay('MESSAGE_WRONG_VERIFICATION_CODE');
        }

        return $return;
    }

    /**
     * Checks if image could be generated.
     *
     * @return bool
     */
    public function isImageVisible()
    {
        return (function_exists('imagecreatetruecolor') || function_exists('imagecreate')) && Registry::getConfig()->getConfigParam('iUseGDVersion') > 1;
    }

    /**
     * Returns url to CAPTCHA image generator.
     *
     * @return string
     */
    public function getImageUrl()
    {
        $config    = Registry::getConfig();
        $key       = $this->getOeCaptchaKey();
        $encryptor = new \OxidEsales\Eshop\Core\Encryptor();

        return $config->getCurrentShopUrl() . sprintf('?cl=ith_basic_captcha_generator&e_mac=%s&shp=%d', $encryptor->encrypt($this->getText(), $key), $config->getShopId());
    }

    /**
     * Returns text hash.
     *
     * @param string $text User supplie text
     *
     * @return string
     */
    public function getHash($text = null)
    {
        // inserting captcha record
        $time     = time() + $this->timeout;
        $textHash = $this->getTextHash($text);

        // if session is started - storing captcha info here
        $session = Registry::getSession();
        if ($session->isSessionStarted()) {
            $hash             = UtilsObject::getInstance()->generateUID();
            $hashArray        = $session->getVariable('captchaHashes');
            $hashArray[$hash] = [$textHash => $time];
            $session->setVariable('captchaHashes', $hashArray);
        } else {
            $q = $this->getQueryBuilder();
            $q->insert('oecaptcha')
                ->values(
                    [
                        'oxhash' => '?',
                        'oxtime' => '?',
                    ]
                )->setParameter(0, $textHash)->setParameter(1, $time);
            $q->execute();
            $hash = $q->getConnection()->lastInsertId();
        }

        return $hash;
    }

    /**
     * Checks for DB captcha hash validity.
     *
     * @param int    $macHash hash key
     * @param string $hash    captcha hash
     * @param int    $time    check time
     *
     * @return bool
     */
    protected function passFromDb($macHash, $hash, $time)
    {
        $q = $this->getQueryBuilder();
        $q->select('1')
            ->from('oecaptcha')
            ->where('oxid = :macHash')
            ->andWhere('oxhash = :hash')
            ->setParameter('macHash', $macHash)
            ->setParameter('hash', $hash);
        $pass = (bool) $q->execute()->fetchOne();
        if ($pass) {
            // cleanup
            $q = $this->getQueryBuilder()
                ->delete('oecaptcha')
                ->where('oxid = :macHash')
                ->andWhere('oxhash = :hash')
                ->setParameter('macHash', $macHash)
                ->setParameter('hash', $hash);
            $q->execute();
        }

        // garbage cleanup
        $q = $this->getQueryBuilder()
            ->delete('oecaptcha')
            ->where('oxtime < :time')
            ->setParameter('time', $time);
        $q->execute();

        return $pass;
    }

    /**
     * Checks for session captcha hash validity.
     *
     * @param string $macHash hash key
     * @param string $hash    captcha hash
     * @param int    $time    check time
     *
     * @return bool
     */
    protected function passFromSession($macHash, $hash, $time)
    {
        $pass    = null;
        $session = Registry::getSession();

        if ($hashArray = $session->getVariable('captchaHashes')) {
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
        if (null === $pass) {
            $pass = $this->passFromDb((int) $macHash, $hash, $time);
        }

        return (bool) $pass;
    }
}
