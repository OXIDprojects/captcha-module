<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

class oeCaptchaNewsletter extends oeCaptchaNewsletter_parent
{
    /**
     * Class handling CAPTCHA image.
     *
     * @var object
     */
    protected $captcha = null;

    /**
     * Composes and sends user written message, returns false if some parameters
     * are missing.
     *
     * @return bool
     */
    public function send()
    {
        if (!$this->getCaptcha()->passCaptcha()) {
            return false;
        }

        return parent::send();
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
