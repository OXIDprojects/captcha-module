<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Captcha\Application\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\Captcha\Application\Shared\Options;
use Throwable;

class ImageGeneratorController extends FrontendController
{
    use Options;

    protected $emac;
    protected int $imageHeight = 18;
    protected int $imageWidth  = 80;
    protected $fontSize    = 14;

    public function init()
    {
        parent::init();
        $this->emac = Registry::getRequest()->getRequestEscapedParameter('e_mac', null);
        if ($this->emac) {
            $this->emac = $this->decodeEmac($this->emac);
        }
    }

    public function render()
    {
        parent::render();

        try {
            if (!$this->emac) {
                throw new StandardException('No e_mac parameter given');
            }
            $image = $this->generateVerificationImage();
            if (!$image) {
                throw new StandardException('Image generation failed by returning NULL');
            }
            header('Content-type: image/png');
            imagepng($image);
            imagedestroy($image);

            exit;
        } catch (Throwable $e) {
            Registry::getLogger()->error(sprintf('%s() | %s', __METHOD__, $e->getMessage()), [$e]);
            http_response_code(400);

            exit(1);
        }
    }

    protected function decodeEmac(string $emac): string
    {
        $decryptor = new \OxidEsales\Eshop\Core\Decryptor();

        $key = $this->getOeCaptchaKey();

        return $decryptor->decrypt($emac, $key);
    }

    protected function generateVerificationImage()
    {
        $image = null;

        switch (true) {
            case function_exists('imagecreatetruecolor'):
                $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);

                break;

            case function_exists('imagecreate'):
                $image = imagecreate($this->imageWidth, $this->imageHeight);

                break;

            default:
                return null;
        }
        $textX = (int)ceil(($this->imageWidth - strlen($this->emac) * imagefontwidth($this->fontSize)) / 2);
        $textY = (int)ceil(($this->imageHeight - imagefontheight($this->fontSize)) / 2) - 1;

        $colors = [
            'text'       => imagecolorallocate($image, 0, 0, 0),
            'shadow1'    => imagecolorallocate($image, 200, 200, 200),
            'shadow2'    => imagecolorallocate($image, 100, 100, 100),
            'background' => imagecolorallocate($image, 255, 255, 255),
            'border'     => imagecolorallocate($image, 0, 0, 0),
        ];

        imagefill($image, 0, 0, $colors['background']);
        imagerectangle($image, 0, 0, $this->imageWidth - 2, $this->imageHeight - 2, $colors['border']);
        imagestring($image, $this->fontSize, $textX + 1, $textY + 0, $this->emac, $colors['shadow2']);
        imagestring($image, $this->fontSize, $textX + 0, $textY + 1, $this->emac, $colors['shadow1']);
        imagestring($image, $this->fontSize, $textX, $textY, $this->emac, $colors['text']);

        return $image;
    }
}
