<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Shared;

use OxidProfessionalServices\Captcha\Application\Core\Captcha as CaptchaCore;

trait Captcha
{
    protected ?CaptchaCore $captcha;

    public function getCaptcha(): CaptchaCore
    {
        if (!$this->captcha) {
            $this->captcha = CaptchaCore::getInstance();
        }

        return $this->captcha;
    }
}
