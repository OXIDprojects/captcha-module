<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Shared;

use OxidProfessionalServices\Captcha\Application\Core\Captcha as CaptchaCore;

trait Captcha
{
    protected ?CaptchaCore $oeCaptcha;

    public function getCaptcha(): CaptchaCore
    {
        return $this->oeCaptcha ??= CaptchaCore::getInstance();
    }
}
