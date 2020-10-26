<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class SavitaHDNet extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'savitahd.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/?~',
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
        $aReturn = [];
        $this->sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $iNbPages = count($this->getDomParser()->load((string)$oRes->getBody())->find('.page-links a')) + 1;
        for ($iPage = 1; $iPage <= $iNbPages; $iPage++) {
            $oRes = $this->getClient()->request('GET', $this->sUrl . $iPage . '/');
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a') as $oLink) {
                /**
                 * @var AbstractNode $oLink
                 */
                if (strpos($oLink->getAttribute('href'), 'imgfy.net') === false) {
                    continue;
                }
                $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
                $oImage = $this->getDomParser()->load((string)$oRes->getBody())->find('#image-viewer-container img')[0];
                if (!$oImage) {
                    continue;
                }
                $sFilename = str_replace('.md.', '.', $oImage->getAttribute('src'));
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gallery-item a') as $oLink) {
                /**
                 * @var AbstractNode $oLink
                 */
                $sFilename = $oLink->getAttribute('href');
                if (!$sFilename) {
                    continue;
                }
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
