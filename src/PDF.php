<?php

namespace Yamete;

class PDF extends \FPDF
{

    const DPI = 96;
    const MM_IN_INCH = 25.4;
    const A4_HEIGHT = 297;
    const A4_WIDTH = 210;
    const MAX_WIDTH = self::A4_WIDTH;
    const MAX_HEIGHT = self::A4_HEIGHT;

    private function pixelsToMM(int $val): int
    {
        return $val * self::MM_IN_INCH / self::DPI;
    }

    /**
     * @param string $imgFilename
     * @return array
     * @throws \Exception
     */
    private function resizeToFit(string $imgFilename): array
    {
        list($width, $height) = getimagesize($imgFilename);
        if (!$width || !$height) {
            throw new \Exception('Not an image file');
        }
        $width = $this->pixelsToMM($width);
        $height = $this->pixelsToMM($height);
        $widthScale = self::MAX_WIDTH / ($width ?: 1);
        $heightScale = self::MAX_HEIGHT / ($height ?: 1);
        $scale = min($widthScale, $heightScale);
        return [round($scale * $height), round($scale * $width)];
    }

    /**
     * @param \Iterator $oList
     * @throws \Exception
     */
    public function createFromList(\Iterator $oList): void
    {
        foreach ($oList as $sFilename => $sResource) {
            list($width, $height) = getimagesize($sFilename);
            $this->AddPage($width > $height ? 'L' : 'P');
            $this->fullSizeImage($sFilename);
        }
    }

    /**
     * @param string $sFileName
     * @throws \Exception
     */
    function fullSizeImage(string $sFileName): void
    {
        list($width, $height) = $this->resizeToFit($sFileName);
        try {
            $this->Image($sFileName, 0, 0, $height, $width);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Not a PNG file') !== false) {
                $sNewFilename = str_replace('.png', '.jpg', $sFileName);
                rename($sFileName, $sNewFilename);
                $this->Image($sNewFilename, 0, 0, $height, $width);
            } else {
                throw $e;
            }
        }
    }
}
