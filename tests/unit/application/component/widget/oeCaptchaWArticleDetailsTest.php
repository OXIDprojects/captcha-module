<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../../../CaptchaTestCase.php';

class oeCaptchaWArticleDetailsTest extends CaptchaTestCase
{
    public function testGetCaptcha()
    {
        $articleDetails = oxNew('oxwarticledetails');
        $captcha = $articleDetails->getCaptcha();
        $this->assertInstanceOf("oeCaptcha", $captcha);
    }
}
