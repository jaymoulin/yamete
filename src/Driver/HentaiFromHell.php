<?php

namespace SiteDl\Driver;

class HentaiFromHell extends \SiteDl\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifromhell.org';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/gallery2/(?<album>.+)\.html~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient([
            'cookies' => new \GuzzleHttp\Cookie\FileCookieJar(tempnam('/tmp', __CLASS__)),
            'handler' => \Tuna\CloudflareMiddleware::create(),
        ])->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('center a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sLink = $oLink->getAttribute('href');
            preg_match('~^https?://(?<domain>' . strtr(self::DOMAIN, ['.' => '\.']) . ')~', $sLink, $aDomains);
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#overflow-wrapper img') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = 'http://' . $aDomains[1] . $oImg->getAttribute('src');
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
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
