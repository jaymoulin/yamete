<?php
namespace SiteDl\Driver;

class SimplyHentai extends \SiteDl\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'simply-hentai.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://(?<domain>[^.]+\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/([^?]+))~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', 'http://' . $this->aMatches['domain'] . '/all-pages');
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.image-preview') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('.next-link picture img');
            $sFilename = $oImg->getAttribute('src');
            $aReturn[$this->getFolder(). DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
