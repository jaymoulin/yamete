<?php

namespace Yamete\Driver;

class Hitomi extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hitomi.la';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/galleries/(?<album>[^.]+).html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', str_replace('/galleries/', '/reader/', $this->sUrl));
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.img-url') as $oImg) {
            /**
             * @var \PHPHtmlParser\Dom\HtmlNode $oImg
             */
            $sFilename = 'https:' . str_replace('//g.', '//aa.', $oImg->innerhtml);
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
