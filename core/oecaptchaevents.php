<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class defines what module does on Shop events.
 */
class oeCaptchaEvents
{
    /**
     * Add table oecaptcha.
     */
    public static function addCaptchaTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS `oecaptcha` (" .
                 "`OXID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Captcha id'," .
                 "`OXHASH` char(32) NOT NULL default '' COMMENT 'Hash'," .
                 "`OXTIME` int(11) NOT NULL COMMENT 'Validation time'," .
                 "`OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp'," .
                 "PRIMARY KEY (`OXID`), " .
                 "KEY `OXID` (`OXID`,`OXHASH`), " .
                 "KEY `OXTIME` (`OXTIME`) " .
                 ") ENGINE=MEMORY AUTO_INCREMENT=1 COMMENT 'If session is not available, this is where captcha information is stored';";

        DatabaseProvider::getDb()->execute($query);
    }

    /**
     * Remove table oecaptcha.
     * NOTE: table oecaptcha contains temporary data if any and can be
     *       removed without side effects on module deactivation
     */
    public static function removeCaptchaTable()
    {
        $query = "DROP TABLE IF EXISTS `oecaptcha`";

        DatabaseProvider::getDb()->execute($query);
    }

    /**
     * Execute action on activate event
     *
     * @return null
     */
    public static function onActivate()
    {
        self::addCaptchaTable();
    }

    /**
     * Execute action on deactivate event
     *
     * @return null
     */
    public static function onDeactivate()
    {
        self::removeCaptchaTable();
    }
}
