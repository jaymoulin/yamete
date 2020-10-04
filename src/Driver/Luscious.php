<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;
use Yamete\DriverInterface;

class Luscious extends DriverAbstract
{
    private $aMatches = [];
    private $iCounter = 0;
    private $iAlbumId = 0;
    private $iPage = 0;

    const DOMAIN = 'luscious.net';
    const API = 'https://www.' . self::DOMAIN . '/graphql/nobatch/?id=3&operationName=AlbumListOwnPictures&'
    . 'query=+query+AlbumListOwnPictures%28%24input%3A+PictureListInput%21%29+%7B+picture+%7B+list%28'
    . 'input%3A+%24input%29+%7B+info+%7B+...FacetCollectionInfo+%7D+items+%7B+...PictureStandardWithoutAlbum'
    . '+%7D+%7D+%7D+%7D+fragment+FacetCollectionInfo+on+FacetCollectionInfo+%7B+page+has_next_page+'
    . 'has_previous_page+total_items+total_pages+items_per_page+url_complete+url_filters_only+%7D+'
    . 'fragment+PictureStandardWithoutAlbum+on+Picture+%7B+__typename+id+title+created+like_status+'
    . 'number_of_comments+number_of_favorites+status+width+height+resolution+aspect_ratio+url_to_original+'
    . 'url_to_video+is_animated+position+tags+%7B+id+category+text+url+%7D+permissions+url+thumbnails+'
    . '%7B+width+height+size+url+%7D+%7D+&variables=%7B"input"%3A%7B"filters"%3A%5B%7B"name"%3A"'
    . 'album_id"%2C"value"%3A"#ALBUM#"%7D%5D%2C"display"%3A"position"%2C"page"%3A#PAGE#%7D%7D';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(m\.|www\.)?' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/albums/(?<album>[^/]+)/~',
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
        $aAlbumId = [];
        preg_match('~([0-9]+)$~', $this->aMatches['album'], $aAlbumId);
        $this->iAlbumId = (int)$aAlbumId[1];
        $this->iPage = (int)0;
        return $this->download(
            (string)$this->getClient()->request(
                'GET',
                strtr(self::API, ['#ALBUM#' => $this->iAlbumId, '#PAGE#' => ++$this->iPage])
            )->getBody()
        );
    }

    /**
     * @param $sBody
     * @param array $aReturn
     * @return array
     * @throws GuzzleException
     */
    private function download(string $sBody, array $aReturn = []): array
    {
        $aInfo = \GuzzleHttp\json_decode($sBody, true);
        foreach ($aInfo['data']['picture']['list']['items'] as $aItemData) {
            $sFilename = $aItemData['url_to_original'];
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad($this->iCounter++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        if ($aInfo['data']['picture']['list']['info']['has_next_page']) {
            $oRes = $this->getClient()
                ->request('GET', strtr(self::API, ['#ALBUM#' => $this->iAlbumId, '#PAGE#' => ++$this->iPage]));
            $aReturn = $this->download((string)$oRes->getBody(), $aReturn);
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function clean(): DriverInterface
    {
        $this->iAlbumId = 0;
        $this->iPage = 0;
        return parent::clean();
    }
}
