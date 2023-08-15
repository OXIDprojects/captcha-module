<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

/**
 * Class oeCaptchaDetails.
 * Extends Details.
 *
 * @see Details
 */
class oeCaptchaDetails extends oeCaptchaDetails_parent
{
    /**
     * Class handling CAPTCHA image.
     *
     * @var object
     */
    protected $captcha = null;

    /**
     * Validates email
     * address. If email is wrong - returns false and exits. If email
     * address is OK - creates price alarm object and saves it
     * (oxpricealarm::save()). Sends price alarm notification mail
     * to shop owner.
     *
     * @return  bool    false on error
     */
    public function addme()
    {
        if (!$this->getCaptcha()->passCaptcha(false)) {
            $this->_iPriceAlarmStatus = 2;
            return;
        }

        return parent::addme();
    }

    /**
     * Template variable getter. Returns object of handling CAPTCHA image
     *
     * @return object
     */
    public function getCaptcha()
    {
        if ($this->captcha === null) {
            $this->captcha = oxNew('oeCaptcha');
        }
        return $this->captcha;
    }
}
