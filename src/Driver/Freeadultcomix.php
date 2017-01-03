<?php
namespace SiteDl\Driver;

class Freeadultcomix extends \SiteDl\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'freeadultcomix.com';

    public function canHandle()
    {
        return preg_match(
            '~^http://' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);

        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.single-post p img') as $oImg) {
            /** @var \DOMElement $oImg */
            $sFilename = 'http://' . self::DOMAIN . $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
