<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Shared;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

trait Options
{
    public const ENCRYPT_KEY = 'fq45QS09_fqyx09239QQ';

    public function getOeCaptchaKey(): string
    {
        $bridge = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingServiceInterface::class);
        $key    = $bridge->getString('oeCaptchaKey', 'oecaptcha')->toString();
        if (!trim($key)) {
            return static::ENCRYPT_KEY;
        }

        return $key;
    }
}
