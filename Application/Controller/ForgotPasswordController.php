<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Controller;

use OxidProfessionalServices\Captcha\Application\Shared\Captcha;

class ForgotPasswordController extends ForgotPasswordController_parent
{
    use Captcha;

    public function forgotpassword()
    {
        if (!$this->getCaptcha()->passCaptcha()) {
            return false;
        }

        return parent::forgotpassword();
    }
}
