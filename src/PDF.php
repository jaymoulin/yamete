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

    private function pixelsToMM($val)
    {
        return $val * self::MM_IN_INCH / self::DPI;
    }

    private function resizeToFit($imgFilename)
    {
        list($width, $height) = getimagesize($imgFilename);
        $width = $this->pixelsToMM($width);
        $height = $this->pixelsToMM($height);
        $widthScale = self::MAX_WIDTH / $width;
        $heightScale = self::MAX_HEIGHT / $height;
        $scale = min($widthScale, $heightScale);
        return $width > $height
            ? [round($scale * $width), round($scale * $height)]
            : [round($scale * $height), round($scale * $width)];
    }

    public function createFromList(ResultIterator $oList)
    {
        foreach ($oList as $sFilename => $sResource) {
            list($width, $height) = getimagesize($sFilename);
            $this->AddPage($width > $height ? 'L' : 'P');
            $this->fullSizeImage($sFilename);
        }
    }

    function fullSizeImage($sFileName)
    {
        list($width, $height) = $this->resizeToFit($sFileName);
        $this->Image($sFileName, 0, 0, $height, $width);
    }
}
