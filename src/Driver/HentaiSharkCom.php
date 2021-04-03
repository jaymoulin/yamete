<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiSharkCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentaishark.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $index = 1;
        $oRes = $this->getClient()
            ->request('GET', 'https://www.' . self::DOMAIN . '/manga/' . $this->aMatches['album']);
        $aReturn = [];
        $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody(), (new \PHPHtmlParser\Options)->setCleanupInput(false));
        foreach ($oParser->find('ul.chapters a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody(), (new \PHPHtmlParser\Options)->setCleanupInput(false));
            foreach ($oParser->find('#all img') as $oImg) {
                /**
                 * @var AbstractNode $oImg
                 */
                $sFilename = trim($oImg->getAttribute('data-src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
