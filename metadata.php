<?php

use OxidProfessionalServices\Captcha\Application\Core\Module;

/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#.
 */
/**
 * This file is part of OXID eSales Captcha module.
 *
 * TODO: license
 *
 * @category      module
 *
 * @author        OXID eSales AG
 *
 * @see          http://www.oxid-esales.com/
 *
 * @copyright (C) OXID eSales AG 2003-20162016
 */

/**
 * Metadata version.
 */
$sMetadataVersion = '2.1';

/**
 * Module information.
 */
$aModule = [
    'id'    => Module::ID,
    'title' => [
        'de' => 'Simple Captcha',
        'en' => 'Simple Captcha',
    ],
    'description' => [
        'de' => 'OXID eSales Simple Captcha Module',
        'en' => 'OXID eSales Simple Captcha Module',
    ],
    'thumbnail'               => 'logo.png',
    'version'                 => Module::VERSION,
    'author'                  => 'OXID eSales AG',
    'url'                     => 'https://www.oxid-esales.com/',
    'email'                   => '',
    'controllers'             => [
        'ith_basic_captcha_generator' => OxidProfessionalServices\Captcha\Application\Controller\ImageGeneratorController::class,
    ],
    'extend'    => [
        OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class => OxidProfessionalServices\Captcha\Application\Controller\DetailsController::class,
        OxidEsales\Eshop\Application\Controller\ContactController::class        => OxidProfessionalServices\Captcha\Application\Controller\ContactController::class,
        OxidEsales\Eshop\Application\Controller\ForgotPasswordController::class => OxidProfessionalServices\Captcha\Application\Controller\ForgotPasswordController::class,
        OxidEsales\Eshop\Application\Controller\InviteController::class         => OxidProfessionalServices\Captcha\Application\Controller\InviteController::class,
        OxidEsales\Eshop\Application\Controller\NewsletterController::class     => OxidProfessionalServices\Captcha\Application\Controller\NewsletterController::class,
        OxidEsales\Eshop\Application\Controller\PriceAlarmController::class     => OxidProfessionalServices\Captcha\Application\Controller\PricealarmController::class,
        OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class     => OxidProfessionalServices\Captcha\Application\Component\Widget\ArticleDetails::class,
    ],
    'templates' => [
        'oe_captcha.tpl' => 'views/smarty/tpl/oe_captcha.tpl',
    ],
    'blocks' => [
        [
            'template' => 'form/contact.tpl',
            'block'    => 'captcha_form',
            'file'     => 'views/smarty/blocks/oe_captcha_form.tpl',
        ],
        [
            'template' => 'form/newsletter.tpl',
            'block'    => 'captcha_form',
            'file'     => 'views/smarty/blocks/oe_captcha_form.tpl',
        ],
        [
            'template' => 'form/privatesales/invite.tpl',
            'block'    => 'captcha_form',
            'file'     => 'views/smarty/blocks/oe_captcha_form.tpl',
        ],
        [
            'template' => 'form/pricealarm.tpl',
            'block'    => 'captcha_form',
            'file'     => 'views/smarty/blocks/oe_captcha_form.tpl',
        ],
        [
            'template' => 'form/suggest.tpl',
            'block'    => 'captcha_form',
            'file'     => 'views/smarty/blocks/oe_captcha_form.tpl',
        ],
        [
            'template' => 'form/forgotpwd_email.tpl',
            'block'    => 'captcha_form',
            'file'     => 'views/smarty/blocks/oe_captcha_form.tpl',
        ],
    ],
    'settings' => [
        [
            'group' => 'main',
            'name'  => 'oecaptchakey',
            'type'  => 'str',
            'value' => '',
        ],
    ],
    'events' => [
        'onActivate'   => Module::class . '::onActivate',
        'onDeactivate' => Module::class . '::onDeactivate',
    ],
];
