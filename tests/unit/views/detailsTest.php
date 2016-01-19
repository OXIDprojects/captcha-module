<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../CaptchaTestCase.php';

/**
 * Testing details class
 */
class Unit_detailsTest extends CaptchaTestCase
{
    /**
     * Test get Captcha.
     *
     * @return null
     */
    public function testGetCaptcha()
    {
        $details = $this->getProxyClass('oecaptchadetails');
        $this->assertEquals(oxNew('oeCaptcha'), $details->getCaptcha());
    }

    /**
     * Invalid captcha test case.
     */
    public function testAddmeInvalidCaptcha()
    {
        $captcha = $this->getMock('oeCaptcha', array('passCaptcha'));
        $captcha->expects($this->once())->method('passCaptcha')->will($this->returnValue(false));

        $email = $this->getMock('oxEmail', array('sendPricealarmNotification'));
        $email->expects($this->never())->method('sendPricealarmNotification');
        oxTestModules::addModuleObject('oxEmail', $email);

        $details = $this->getMock($this->getProxyClassName('oecaptchadetails'), array('getCaptcha'));
        $details->expects($this->once())->method('getCaptcha')->will($this->returnValue($captcha));

        $details->addme();
        $this->assertSame(2, $details->getNonPublicVar('_iPriceAlarmStatus'));
    }

}


