<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Controller;

use OxidProfessionalServices\Captcha\Application\Shared\Captcha;

class DetailsController extends DetailsController_parent
{
    use Captcha;

    public function addme()
    {
        if (!$this->getCaptcha()->passCaptcha(false)) {
            $this->_iPriceAlarmStatus = 2;

            return;
        }

        return parent::addme();
    }
}
