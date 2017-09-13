<?php

namespace SiteDl\Driver;

class Hentaiporns extends \SiteDl\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'nhentai.net';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/g/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.gallerythumb') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oLink->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#image-container img') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = $oImg->getAttribute('src');
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT) . '-'
                    . basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
