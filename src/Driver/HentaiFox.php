<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiFox extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifox.com';

    public function canHandle(): bool
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/gallery/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $aMatches = [];
        $aFields = [
            '<input type="hidden" name="load_dir" id="load_dir" value="(?<load_dir>[^"]+)" />',
            '<input type="hidden" name="load_id" id="load_id" value="(?<load_id>[^"]+)" />',
            '<input type="hidden" name="load_pages" id="load_pages" value="(?<load_pages>[^"]+)" />',
        ];
        $sBody = (string)$oRes->getBody();
        if (!preg_match('~' . implode('', $aFields) . '~', $sBody, $aMatches)) {
            return [];
        }

        $oXhr = clone $this->getClient();
        $oQueries = [
            $sBody,
            (string)$oXhr
                ->request(
                    'POST',
                    'https://' . self::DOMAIN . '/includes/thumbs_loader.php',
                    [
                        'headers' => [
                            'X-Requested-With' => 'XMLHttpRequest',
                        ],
                        'form_params' => [
                            'u_id' => $this->aMatches['album'],
                            'g_id' => $aMatches['load_id'],
                            'img_dir' => $aMatches['load_dir'],
                            'visible_pages' => 10,
                            'total_pages' => $aMatches['load_pages'],
                            'type' => 2,
                        ],
                    ]
                )->getBody(),
        ];
        $index = 0;
        foreach ($oQueries as $sBody) {
            foreach ($this->getDomParser()->load($sBody)->find('.g_thumb img') as $oImg) {
                /**
                 * @var AbstractNode $oImg
                 */
                $sFilename = str_replace('t.jpg', '.jpg', $oImg->getAttribute('src'));
                $sIndex = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT) .
                    '-' . basename($sFilename);
                $aReturn[$sIndex] = $sFilename;
            }
        }

        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
