<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

// #1428C - spam spider prevention
if (isset($_GET['e_mac'])) {
    $emac = $_GET['e_mac'];
} else {
    return;
}

require_once '../../../../../bootstrap.php';

if (!function_exists('generateVerificationImg')) {

    /**
     * Generates image
     *
     * @param string $mac verification code
     *
     * @return null
     */
    function generateVerificationImg($mac)
    {
        $width = 80;
        $height = 18;
        $fontSize = 14;

        if (function_exists('imagecreatetruecolor')) {
            // GD2
            $image = imagecreatetruecolor($width, $height);
        } elseif (function_exists('imagecreate')) {
            // GD1
            $image = imagecreate($width, $height);
        } else {
            // GD not found
            return;
        }

        $textX = ($width - strlen($mac) * imagefontwidth($fontSize)) / 2;
        $textY = ($height - imagefontheight($fontSize)) / 2;

        $colors = array();
        $colors["text"] = imagecolorallocate($image, 0, 0, 0);
        $colors["shadow1"] = imagecolorallocate($image, 200, 200, 200);
        $colors["shadow2"] = imagecolorallocate($image, 100, 100, 100);
        $colors["background"] = imagecolorallocate($image, 255, 255, 255);
        $colors["border"] = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $colors["background"]);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $colors["border"]);
        imagestring($image, $fontSize, $textX + 1, $textY + 0, $mac, $colors["shadow2"]);
        imagestring($image, $fontSize, $textX + 0, $textY + 1, $mac, $colors["shadow1"]);
        imagestring($image, $fontSize, $textX, $textY, $mac, $colors["text"]);

        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }
}

if (!function_exists('strRem')) {

    /**
     * OXID specific string manipulation method
     *
     * @param string $value string
     *
     * @return string
     */
    function strRem($value)
    {
        $decryptor = new \OxidEsales\Eshop\Core\Decryptor();
        $config = oxRegistry::getConfig();

        $key = $config->getConfigParam('oecaptchakey');
        if (empty($key)) {
            $key = getOxConfKey();
        }

        return $decryptor->decrypt($value, $key);
    }
}

if (!function_exists('getOxConfKey')) {

    /**
     * Get default config key.
     *
     * @return string
     */
    function getOxConfKey()
    {
        $config = oxRegistry::getConfig();
        $configKey = $config->getConfigParam('sConfigKey') ?: \OxidEsales\Eshop\Core\Config::DEFAULT_CONFIG_KEY;
        return $configKey;
    }

}

$mac = strRem($emac);
generateVerificationImg($mac);
