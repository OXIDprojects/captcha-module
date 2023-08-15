<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Controller;

use OxidProfessionalServices\Captcha\Application\Shared\Captcha;

class InviteController extends InviteController_parent
{
    use Captcha;

    public function send()
    {
        if (!$this->getCaptcha()->passCaptcha()) {
            return false;
        }

        return parent::send();
    }
}
