<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */
/**
 * This file is part of OXID eSales Captcha module.
 *
 * TODO: license
 *
 * @category      module
 * @package       captcha
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com/
 * @copyright (C) OXID eSales AG 2003-20162016
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'oecaptcha',
    'title'       => array(
        'de' => 'Simple Captcha',
        'en' => 'Simple Captcha',
    ),
    'description' => array(
        'de' => 'OXID eSales Simple Captcha Module',
        'en' => 'OXID eSales Simple Captcha Module',
    ),
    'thumbnail'   => 'out/pictures/picture.png',
    'version'     => '2.0.4',
    'author'      => 'OXID eSales AG',
    'url'         => 'http://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => array('details'           => 'oe/captcha/controllers/oecaptchadetails',
                           'contact'           => 'oe/captcha/controllers/oecaptchacontact',
                           'forgotpwd'         => 'oe/captcha/controllers/oecaptchaforgotpwd',
                           'invite'            => 'oe/captcha/controllers/oecaptchainvite',
                           'newsletter'        => 'oe/captcha/controllers/oecaptchanewsletter',
                           'pricealarm'        => 'oe/captcha/controllers/oecaptchapricealarm',
                           'suggest'           => 'oe/captcha/controllers/oecaptchasuggest',
                           'oxwarticledetails' => 'oe/captcha/application/component/widget/oecaptchawarticledetails'),
    'files'       => array(
        'oecaptcha'       => 'oe/captcha/core/oecaptcha.php',
        'oecaptchaEvents' => 'oe/captcha/core/oecaptchaevents.php',
    ),
    'templates'   => array(
        'oecaptcha.tpl' => 'oe/captcha/application/views/tpl/oecaptcha.tpl',
    ),
    'blocks'      => array(
        array('template' => 'form/contact.tpl', 'block'=>'captcha_form', 'file'=>'/application/views/blocks/captcha_form.tpl'),
        array('template' => 'form/newsletter.tpl', 'block'=>'captcha_form', 'file'=>'/application/views/blocks/captcha_form.tpl'),
        array('template' => 'form/privatesales/invite.tpl', 'block'=>'captcha_form', 'file'=>'/application/views/blocks/captcha_form.tpl'),
        array('template' => 'form/pricealarm.tpl', 'block'=>'captcha_form', 'file'=>'/application/views/blocks/captcha_form.tpl'),
        array('template' => 'form/suggest.tpl', 'block'=>'captcha_form', 'file'=>'/application/views/blocks/captcha_form.tpl'),
        array('template' => 'form/forgotpwd_email.tpl', 'block'=>'captcha_form', 'file'=>'/application/views/blocks/captcha_form_forgotpwd.tpl'),
    ),
    'settings'    => array(
        array('group' => 'main', 'name' => 'oecaptchakey', 'type' => 'str', 'value' => ''),
    ),
    'events'       => array(
        'onActivate'   => 'oecaptchaevents::onActivate',
        'onDeactivate' => 'oecaptchaevents::onDeactivate'
    ),
);
