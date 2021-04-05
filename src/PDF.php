<?php

namespace Yamete;

use Exception;
use FPDF;
use Iterator;
use function imagecreatefromwebp;
use function imagejpeg;

class PDF extends FPDF
{

    const DPI = 96;
    const MM_IN_INCH = 25.4;
    const A4_HEIGHT = 297;
    const A4_WIDTH = 210;
    const MAX_WIDTH = self::A4_WIDTH;
    const MAX_HEIGHT = self::A4_HEIGHT;

    /**
     * @param Iterator $oList
     * @throws Exception
     */
    public function createFromList(Iterator $oList): void
    {
        $iDisplay = ini_get('display_errors');
        ini_set('display_errors', 0);
        foreach ($oList as $sFilename => $sResource) {
            list($width, $height) = getimagesize($sFilename);
            $this->AddPage($width > $height ? 'L' : 'P');
            $this->fullSizeImage($sFilename);
        }
        ini_set('display_errors', $iDisplay);
    }

    /**
     * @param string $sFileName
     * @throws Exception
     */
    private function fullSizeImage(string $sFileName): void
    {
        list($width, $height) = $this->resizeToFit($sFileName);
        try {
            $this->Image($sFileName, 0, 0, $width, $height);
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Not a PNG file')) {
                $sNewFilename = str_replace('.png', '.jpg', $sFileName);
                rename($sFileName, $sNewFilename);
                $this->fullSizeImage($sNewFilename);
            } elseif (str_contains($e->getMessage(), 'Not a JPEG file')) {
                $sNewFilename = str_replace('.jpg', '.gif', $sFileName);
                rename($sFileName, $sNewFilename);
                $this->fullSizeImage($sNewFilename);
            } elseif (str_contains($e->getMessage(), 'Not a GIF file') or
                (
                    str_contains($e->getMessage(), 'Missing or incorrect image file') and
                    str_contains($e->getMessage(), '.gif')
                )
            ) {
                $sNewFilename = str_replace('.gif', '.png', $sFileName);
                rename($sFileName, $sNewFilename);
                $this->Image($sNewFilename, 0, 0, $width, $height);
            } elseif (str_contains($e->getMessage(), 'Unsupported image type: webp')) {
                $sNewFilename = str_replace('.webp', '.jpg', $sFileName);
                $oImage = imagecreatefromwebp($sFileName);
                imagejpeg($oImage, $sNewFilename, 100);
                unlink($sFileName);
                $this->fullSizeImage($sNewFilename);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param string $imgFilename
     * @return array
     * @throws Exception
     */
    private function resizeToFit(string $imgFilename): array
    {
        list($width, $height) = getimagesize($imgFilename);
        if (!$width || !$height) {
            throw new Exception('Not an image file');
        }
        $width = $this->pixelsToMM($width);
        $height = $this->pixelsToMM($height);
        $iScale = $width < $height ? self::MAX_WIDTH : self::MAX_HEIGHT;
        return [$iScale, $height * $iScale / $width];
    }

    private function pixelsToMM(int $val): int
    {
        return $val * self::MM_IN_INCH / self::DPI;
    }
}
