<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class JapanreadCc extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'japanread.cc';

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
        /**
         * @var \Traversable $oChapters
         * @var AbstractNode[] $aChapters
         * @var AbstractNode $oChapId
         */
        $oRes = $this->getClient()
            ->request('GET', 'https://www.' . self::DOMAIN . '/manga/' . $this->aMatches['album']);
        $aReturn = [];
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('.chapter-row .col a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $iPage = 0;
        foreach ($aChapters as $oChapter) {
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oChapter->getAttribute('href'));
            $aMatches = [];
            if (!preg_match('~data-chapter-id="(?<iChapId>[0-9]+)"~U', (string)$oRes->getBody(), $aMatches)) {
                continue;
            }
            $iChapId = (int)$aMatches['iChapId'];
            $sApiUrl = "https://www.japanread.cc/api/?id=${iChapId}&type=chapter";
            $oRes = $this->getClient()->request('GET', $sApiUrl);
            $aJson = \GuzzleHttp\json_decode((string)$oRes->getBody(), true);
            foreach ($aJson['page_array'] as $sPageUrl) {
                $sFilename = 'https://www.' . self::DOMAIN . $aJson['baseImagesUrl'] . '/' . $sPageUrl;
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($iPage++, 5, '0', STR_PAD_LEFT)
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

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }
}
