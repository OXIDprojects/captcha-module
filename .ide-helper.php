<?php

namespace OxidProfessionalServices\Captcha\Application\Controller{
    use OxidEsales\Eshop\Application\Controller\ArticleDetailsController;
    use OxidEsales\Eshop\Application\Controller\ContactController;
    use OxidEsales\Eshop\Application\Controller\ForgotPasswordController;
    use OxidEsales\Eshop\Application\Controller\InviteController;
    use OxidEsales\Eshop\Application\Controller\NewsletterController;
    use OxidEsales\Eshop\Application\Controller\PriceAlarmController;

    class ContactController_parent extends ContactController
    {}
    class DetailsController_parent extends ArticleDetailsController {}
    class ForgotPasswordController_parent extends ForgotPasswordController {}
    class InviteController_parent extends InviteController {}
    class NewsletterController_parent extends NewsletterController {}
    class PricealarmController_parent extends PriceAlarmController {}
}

namespace OxidProfessionalServices\Captcha\Application\Component\Widget {
    use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
    class ArticleDetails_parent extends ArticleDetails {}
}