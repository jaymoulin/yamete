<?php
namespace SiteDl\Driver;

class Comicspornoxxx extends \SiteDl\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'comicspornoxxx.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oLink = $this->getDomParser()->load((string)$oRes->getBody())->find('.su-box-style-default .su-button-center a')[0];
        $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.thumbnail a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sLink = 'https://cloud-1.sharealo.org/' . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#center .text-center img') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = 'https://cloud-1.sharealo.org/' . $oImg->getAttribute('src');
                $aReturn[$this->getFolder(). DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
