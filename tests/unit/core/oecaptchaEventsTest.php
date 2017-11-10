<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

use OxidEsales\EshopCommunity\Core\DatabaseProvider;

require_once __DIR__ . '/../CaptchaTestCase.php';

class Unit_Core_oecaptchaEventsTest extends CaptchaTestCase
{
    /**
     * Set up the fixture.
     */
    protected function setUp()
    {
        parent::setUp();

        //Drop captcha table
        DatabaseProvider::getDB()->execute("DROP TABLE IF EXISTS `oecaptcha`");
    }

    /**
     * Tear down the fixture.
     */
    public function tearDown()
    {
        oeCaptchaEvents::addCaptchaTable();

        parent::tearDown();
    }

    /**
     * Test onActivate event.
     */
    public function testOnActivate()
    {
        oeCaptchaEvents::onActivate();

        $oDbMetaDataHandler = oxNew('oxDbMetaDataHandler');

        //If session is not available, table oecaptcha is where captcha information is stored
        $this->assertTrue($oDbMetaDataHandler->tableExists('oecaptcha'));

    }

    /**
     * Test onActivate event.
     */
    public function testOnDeactivate()
    {
        oeCaptchaEvents::onDeactivate();

        $oDbMetaDataHandler = oxNew('oxDbMetaDataHandler');

        //If session is not available, table oecaptcha is where captcha information is stored
        $this->assertFalse($oDbMetaDataHandler->tableExists('oecaptcha'));

    }

}
