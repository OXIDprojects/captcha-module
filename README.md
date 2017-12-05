# Simple captcha module

## Description

This module provides a simple captcha ("Completely Automated Public Turing test to tell Computers and Humans Apart")
challenge (distorted characters in an image).

It is used to ensure that only a user who can read the distorted characters and enter them in the related input field
can submit the following forms:
 - contact
 - invite
 - pricealarm
 - suggest

The captcha module then validates the submitted value against the expected one and then decides whether to process the
request (e.g. send contact mail to shop administrator) or refuse and show an error message instead.

## Installation

Please proceed with one of the following ways to install the module:

### Module installation via composer

In order to install the module via composer, run the following commands in commandline of your shop base directory 
(where the shop's composer.json file resides).

```
composer require oxid-projects/captcha-module
```

### Module installation via repository cloning

Clone the module to your OXID eShop **modules/oe/** directory:
```
git clone https://github.com/OXIDprojects/captcha-module.git captcha
```

### Module installation from zip package

* Make a new folder "captcha" in the **modules/oe/ directory** of your shop installation. 
* Download the https://github.com/OXIDprojects/captcha-module/archive/master.zip file and unpack it into the created folder.

## Activate Module

- Activate the module in the administration panel.

## Uninstall

Disable the module in administration area and delete the module folder.

## License

Licensing of the software product depends on the shop edition used. The software for OXID eShop Community Edition
is published under the GNU General Public License v3. You may distribute and/or modify this software according to
the licensing terms published by the Free Software Foundation. Legal licensing terms regarding the distribution of
software being subject to GNU GPL can be found under http://www.gnu.org/licenses/gpl.html. The software for OXID eShop
Professional Edition and Enterprise Edition is released under commercial license. OXID eSales AG has the sole rights to
the software. Decompiling the source code, unauthorized copying as well as distribution to third parties is not
permitted. Infringement will be reported to the authorities and prosecuted without exception.
