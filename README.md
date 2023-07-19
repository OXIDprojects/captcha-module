# Simple captcha module

## Description

This module provides a simple captcha ("Completely Automated Public Turing test to tell Computers and Humans Apart")
challenge (distorted characters in an image).

It is used to ensure that only a user who can read the distorted characters and enter them in the related input field
can submit the following forms:
 - contact
 - invite
 - pricealarm (not bound in twig)
 - newsletter (not bound in twig)
 - forgotpwd (not bound in twig)

The captcha module then validates the submitted value against the expected one and then decides whether to process the
request (e.g. send contact mail to shop administrator) or refuse and show an error message instead.

## Installation

Please proceed with one of the following ways to install the module:

### Module installation via composer

In order to install the module via composer, run the following commands in commandline of your shop base directory 
(where the shop's composer.json file resides).

```bash
composer require oxid-projects/captcha-module
```

### Module installation via repository cloning

Clone the module to your OXID eShop **modules/oe/** directory:
```bash
git clone https://github.com/OXIDprojects/captcha-module.git captcha
```
And add repository to root composer:
```bash
composer config repositories.oxid-projects/captcha-module path "source/modules/oe/captcha"
```
And install module:
```bash
composer require oxid-projects/captcha-module
vendor/bin/oe-console oe:module:install source/modules/oe/captcha
```

## Activate Module

- Activate the module in the administration panel.
- Or use console
```bash
vendor/bin/oe-console oe:module:activate oecaptcha
vendor/bin/oe-console oe:cache:clear
```

## Uninstall

Disable the module in administration area or by executing following shell command.
```bash
vendor/bin/oe-console oe:module:deactivate oecaptcha
```
If installed over composer (packagist):
```bash
composer remove oxid-projects/captcha-module
vendor/bin/oe-console oe:cache:clear
```
else if cloned:
```bash
vendor/bin/oe-console oe:module:uninstall oecaptcha
vendor/bin/oe-console oe:cache:clear
composer remove oxid-projects/captcha-module
composer config --unset repositories.oxid-projects/captcha-module
# and remove the source itself
rm -rf source/modules/oe/captcha
```

## License

Licensing of the software product depends on the shop edition used. The software for OXID eShop Community Edition
is published under the GNU General Public License v3. You may distribute and/or modify this software according to
the licensing terms published by the Free Software Foundation. Legal licensing terms regarding the distribution of
software being subject to GNU GPL can be found under http://www.gnu.org/licenses/gpl.html. The software for OXID eShop
Professional Edition and Enterprise Edition is released under commercial license. OXID eSales AG has the sole rights to
the software. Decompiling the source code, unauthorized copying as well as distribution to third parties is not
permitted. Infringement will be reported to the authorities and prosecuted without exception.
