<?php
namespace SiteDl\Driver;

class EightMuses extends \SiteDl\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '8muses.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/comix/album/(?<album>[^?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.image-box') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load(
                    (string)$this->getClient()
                        ->request('GET', 'https://www.' . self::DOMAIN . $oLink->getAttribute('href'))->getBody()
                )->find('#imageName');
            $sFilename = 'https://cdn.ampproject.org/i/s/www.8muses.com/data/fu/small/' . $oImg->getAttribute('value');
            $aReturn[$this->getFolder(). DIRECTORY_SEPARATOR . str_pad($i++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
