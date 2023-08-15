<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Core;

use OxidProfessionalServices\Captcha\Application\Shared\Connection;

class Module
{
    use Connection;

    public const ID      = 'oecaptcha';
    public const VERSION = '7.0.0';

    protected static $__instance;

    public static function getInstance()
    {
        return static::$__instance ?? (static::$__instance = oxNew(static::class));
    }

    public static function onActivate(): void
    {
        static::getInstance()->activate();
    }

    public static function onDeactivate(): void
    {
        static::getInstance()->deactivate();
    }

    public function createTable(): void
    {
        $this->getDb()->executeStatement('
            CREATE TABLE IF NOT EXISTS `oecaptcha` (' .
            "`OXID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Captcha id'," .
            "`OXHASH` char(32) NOT NULL default '' COMMENT 'Hash'," .
            "`OXTIME` int(11) NOT NULL COMMENT 'Validation time'," .
            "`OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp'," .
            'PRIMARY KEY (`OXID`), ' .
            'KEY `OXID` (`OXID`,`OXHASH`), ' .
            'KEY `OXTIME` (`OXTIME`) ' .
            ") ENGINE=MEMORY AUTO_INCREMENT=1 COMMENT 'If session is not available, this is where captcha information is stored';
        ");
    }

    public function dropTable(): void
    {
        $this->getDb()->executeStatement('DROP TABLE IF EXISTS `oecaptcha`;');
    }

    public function activate(): void
    {
        $this->createTable();
    }

    public function deactivate(): void
    {
        $this->dropTable();
    }
}
