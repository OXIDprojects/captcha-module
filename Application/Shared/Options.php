<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Shared;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidProfessionalServices\Captcha\Application\Core\Captcha;

trait Options
{
    public function getOeCaptchaKey(): string
    {
        $bridge = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingServiceInterface::class);
        $key    = $bridge->getString('oecaptchakey', 'oecaptcha')->toString();
        if (!trim($key)) {
            return Captcha::ENCRYPT_KEY;
        }

        return $key;
    }
}
