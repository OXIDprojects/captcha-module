<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

use OxidEsales\EshopCommunity\Core\DatabaseProvider;

abstract class CaptchaTestCase extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Fixture set up.
     */
    protected function setUp()
    {
        parent::setUp();

        $query = "CREATE TABLE IF NOT EXISTS `oecaptcha` (
                  `OXID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Captcha id',
                  `OXHASH` char(32) NOT NULL default '' COMMENT 'Hash',
                  `OXTIME` int(11) NOT NULL COMMENT 'Validation time',
                  `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
                  PRIMARY KEY (`OXID`),
                  KEY `OXID` (`OXID`,`OXHASH`),
                  KEY `OXTIME` (`OXTIME`)
                ) ENGINE=MEMORY AUTO_INCREMENT=1 COMMENT 'If session is not available, this is where captcha information is stored';
                ";

        DatabaseProvider::getDb()->execute($query);
    }

    /**
     * Fixture set up.
     */
    protected function tearDown()
    {
        $query = "DROP TABLE `oecaptcha`";
        DatabaseProvider::getDb()->execute($query);

        parent::tearDown();
    }

}
