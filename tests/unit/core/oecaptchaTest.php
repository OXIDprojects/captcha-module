<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

use OxidEsales\EshopCommunity\Core\DatabaseProvider;

require_once __DIR__ . '/../CaptchaTestCase.php';

class Unit_Core_oecaptchaTest extends CaptchaTestCase
{
    private $captcha = null;

    /**
     * Fixture set up.
     */
    public function setUp()
    {
        parent::setUp();

        $this->captcha = $this->getProxyClass('oecaptcha');
    }

    /**
     * oeCaptcha::getText() test case
     */
    public function testGetText()
    {
        $this->assertNull($this->captcha->getNonPublicVar('text'));
        $sText = $this->captcha->getText();
        $this->assertEquals($sText, $this->captcha->getNonPublicVar('text'));
        $this->assertEquals(5, strlen($sText));
    }

    /**
     * oeCaptcha::getTextHash() test case
     */
    public function testGetTextHash()
    {
        $this->assertEquals('c4b961848aeff4d9b083fe15a56c9bd0', $this->captcha->getTextHash('test1'));
    }

    /**
     * oeCaptcha::getHash() test case
     */
    public function testGetHashNoSession()
    {
        $session = $this->getMock('oxSession', array('isSessionStarted'));
        $session->expects($this->once())->method('isSessionStarted')->will($this->returnValue(false));

        $captcha = $this->getMock('oeCaptcha', array('getSession'));
        $captcha->expects($this->once())->method('getSession')->will($this->returnValue($session));

        $hash = $captcha->getHash('test');
        $this->assertEquals(DatabaseProvider::getDb()->getOne(
            'select LAST_INSERT_ID()',
            false
        ), $hash);
    }

    /**
     * oeCaptcha::getHash() test case
     * #0004286 adding case for multiple hashes
     */
    public function testGetHashSession()
    {
        $session = $this->getMock('oxSession', array('isSessionStarted'));
        $session->expects($this->exactly(2))->method('isSessionStarted')->will($this->returnValue(true));

        $captcha = $this->getMock('oeCaptcha', array('getSession'));
        $captcha->expects($this->exactly(2))->method('getSession')->will($this->returnValue($session));
        $hash1 = $captcha->getHash('test1');
        $hash2 = $captcha->getHash('test2');

        $captchaHash = oxRegistry::getSession()->getVariable('captchaHashes');
        $this->assertNotNull($captchaHash);
        $this->assertTrue(isset($captchaHash[$hash1]));
        $this->assertTrue(isset($captchaHash[$hash2]));
    }

    /**
     * oeCaptcha::getImageUrl() test case
     */
    public function testGetImageUrl()
    {
        $this->getConfig()->setConfigParam("oecaptchakey", 'someTestCaptchaKey');
        $this->captcha->setNonPublicVar('text', 'test1');
        $expected = $this->getConfig()->getShopUrl() . 'modules/oe/captcha/core/utils/verificationimg.php?e_mac=ox_MAsbCBYgVBoQ';

        $this->assertEquals($expected, $this->captcha->getImageUrl());
    }

    /**
     * oeCaptcha::getImageUrl() test case if sCaptcha key is not set
     */
    public function testGetImageUrlFallbackKey()
    {
        $this->getConfig()->setConfigParam("oecaptchakey", '');
        $this->captcha->setNonPublicVar('text', 'test1');

        $expected = $this->getConfig()->getShopUrl() . 'modules/oe/captcha/core/utils/verificationimg.php?e_mac=ox_MB4FUUYlYlld';
        $this->assertEquals($expected, $this->captcha->getImageUrl());
    }

    /**
     * oeCaptcha::isImageVisible() test case
     */
    public function testIsImageVisible()
    {
        $this->assertTrue($this->captcha->isImageVisible());
    }

    /**
     * oeCaptcha::isImageVisible() test case
     */
    public function testIsImageVisibleLowGD()
    {
        $this->getConfig()->setConfigParam('iUseGDVersion', 0);
        $this->assertFalse($this->captcha->isImageVisible());
    }

    /**
     * oeCaptcha::pass() test case
     */
    public function testDbPassCorrect()
    {
        $captcha = $this->getMock('oeCaptcha', array('passFromSession'));
        $captcha->expects($this->once())->method('passFromSession')->will($this->returnValue(null));

        // reseting session
        $session = oxNew('oxSession');
        $captcha->setSession($session);

        $captcha->getHash('3at8u');
        $hash = DatabaseProvider::getDb()->getOne(
            'select LAST_INSERT_ID()',
            false
        );
        $this->assertTrue($captcha->pass('3at8u', $hash));
    }

    /**
     * oeCaptcha::pass() test case
     */
    public function testDbPassFail()
    {
        $captcha = $this->getMock('oeCaptcha', array('passFromSession'));
        $captcha->expects($this->once())->method('passFromSession')->will($this->returnValue(null));

        $this->assertFalse($captcha->pass('3at8v', 'd9a470912b222133fb913da36c0f50d0'));
    }

    /**
     * oeCaptcha::pass() test case
     * #0004286 adding case for multiple hashes
     */
    public function testSessionPassCorrect()
    {
        $mac1 = '3at8u';
        $mac2 = '3at8u';
        $hash1 = 1234;
        $hash2 = 1235;

        $captcha = oxNew('oeCaptcha');
        $hash = array(
            $hash1 => array($captcha->getTextHash($mac1) => time() + 3600),
            $hash2 => array($captcha->getTextHash($mac2) => time() + 3600)
        );
        $session = $this->getSession();
        $session->setVariable('captchaHashes', $hash);

        $captcha = $this->getMock('oeCaptcha', array('passFromDb'));
        $captcha->expects($this->never())->method('passFromDb');

        $this->assertTrue($captcha->pass($mac1, $hash1));
        $this->assertEquals(1, count($session->getVariable('captchaHashes')));

        $this->assertTrue($captcha->pass($mac2, $hash2));
        $this->assertNull($session->getVariable('captchaHashes'));
    }

    /**
     * oeCaptcha::pass() test case
     */
    public function testSessionPassFail()
    {
        $this->getSession()->setVariable('captchaHashes', array('testHash' => array('testTextHash' => 132)));

        $captcha = $this->getMock('oeCaptcha', array('passFromDb'));
        $captcha->expects($this->never())->method('passFromDb');

        $this->assertFalse($captcha->pass('3at8v', 'd9a470912b222133fb913da36c0f50d0'));
    }

    /**
     * Test passing captcha.
     */
    public function testPassCaptchaYes()
    {
        $captcha = $this->getMock('oeCaptcha', array('pass'));
        $captcha->expects($this->once())->method('pass')->will($this->returnValue(true));

        $result = $captcha->passCaptcha();
        $this->assertTrue($result);
    }

    /**
     * Test not passing captcha.
     */
    public function testPassCaptchaNo()
    {
        $utilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $utilsView->expects($this->once())->method('addErrorToDisplay')->with($this->equalTo('MESSAGE_WRONG_VERIFICATION_CODE'));
        oxRegistry::set('oxUtilsView', $utilsView);

        $captcha = $this->getMock('oeCaptcha', array('pass'));
        $captcha->expects($this->once())->method('pass')->will($this->returnValue(false));

        $result = $captcha->passCaptcha();
        $this->assertFalse($result);
    }

    /**
     * Test not passing captcha without displaying error message.
     */
    public function testPassCaptchaNoWithoutDisplayMessage()
    {
        $utilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $utilsView->expects($this->never())->method('addErrorToDisplay');
        oxRegistry::set('oxUtilsView', $utilsView);

        $captcha = $this->getMock('oeCaptcha', array('pass'));
        $captcha->expects($this->once())->method('pass')->will($this->returnValue(false));

        $result = $captcha->passCaptcha(false);
        $this->assertFalse($result);
    }
}
