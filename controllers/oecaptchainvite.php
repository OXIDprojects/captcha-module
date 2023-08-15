<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

/**
 * Article suggestion page.
 * Collects some article base information, sets default recommendation text,
 * sends suggestion mail to user.
 */
class oeCaptchaInvite extends oeCaptchaInvite_parent
{
    /**
     * Class handling CAPTCHA image.
     *
     * @var object
     */
    protected $captcha = null;

    /**
     * Sends product suggestion mail and returns a URL according to
     * URL formatting rules.
     *
     * @return  null
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
