<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

/**
 * Pricealarm window.
 * Arranges "pricealarm" window, by sending eMail and storing into Database (etc.)
 * submission. Result - "pricealarm.tpl"  template. After user correctly
 * fulfils all required fields all information is sent to shop owner by
 * email.
 * OXID eShop -> pricealarm.
 */
class oeCaptchaPricealarm extends oeCaptchaPricealarm_parent
{
    /**
     * Validates email
     * address. If email is wrong - returns false and exits. If email
     * address is OK - creates prcealarm object and saves it
     * (oxpricealarm::save()). Sends pricealarm notification mail
     * to shop owner.
     *
     * @return  bool    false on error
     */
    public function addme()
    {
        //control captcha
        $captcha = oxNew('oeCaptcha');
        if (!$captcha->passCaptcha(false)) {
            $this->_iPriceAlarmStatus = 2;

            return;
        }

        return parent::addme();
    }
}
