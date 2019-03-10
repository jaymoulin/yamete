![logo](logo.png)

Yamete - Hentai downloader in PHP CLI - Easy PHP Site downloader
================================================================

[![latest release](https://img.shields.io/github/release/jaymoulin/yamete.svg "latest release")](http://github.com/jaymoulin/yamete/releases)
[![Docker Pulls](https://img.shields.io/docker/pulls/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![Docker stars](https://img.shields.io/docker/stars/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![Bitcoin donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/btc.png "Bitcoin donation")](https://m.freewallet.org/id/374ad82e/btc)
[![Litecoin donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/ltc.png "Litecoin donation")](https://m.freewallet.org/id/374ad82e/ltc)
[![PayPal donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/ppl.png "PayPal donation")](https://www.paypal.me/jaymoulin)
[![Buy me a coffee](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png "Buy me a coffee")](https://www.buymeacoffee.com/3Yu8ajd7W)

This image allows you easily download specific assets of a site

But why?
--------

You should not ask why I made it but why is it so popular?

If we trust 2019 or 2018 Pornhub stats (https://www.pornhub.com/insights/women-of-the-world, https://www.pornhub.com/insights/2018-year-in-review),
Hentai is the top 3 search for both women and men and growing.

At the beginning, this repo purpose was to download resources from web sites with PHP... It just took a strange turn... (trust me dude, it's named `EZ site downloader` for a reason)
BUT you can still use it for its former purpose by coding your own drivers! (`-d` parameter exists for this purpose)

Usage
-----

```
Usage:
  download [options]

Options:
  -u, --url[=URL]             Url to download from
  -l, --list[=LIST]           List file with multiple urls
  -p, --pdf                   Optional to create a PDF
  -z, --zip                   Optional to create a zip file
  -e, --errors[=ERRORS]       Optional file path to create artifacts error urls
  -d, --drivers[=DRIVERS]     Optional array of drivers to add (multiple values allowed)
  -h, --help                  Display this help message
  -q, --quiet                 Do not output any message
  -V, --version               Display this application version
      --ansi                  Force ANSI output
      --no-ansi               Disable ANSI output
  -n, --no-interaction        Do not ask any interactive question
  -v|vv|vvv, --verbose        Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Download an URL resources
```

### Mandatory parameter
`-u` or `--url` : the URL to download assets from
OR
`-l` or `--list` : the path to a file containing all URLs to download from on each line

### Optional parameter
 - `-d` or `--drivers`: the path to custom drivers to handle some URL
 - `-p` or `--pdf`: Add this parameter to download a single PDF file instead of multiple images
 - `-e` or `--errors`: Add this parameter to put all urls that failed in another file 

### Downloads

All assets will be downloaded to the *downloads* folder at the root folder of this project.

Docker
------

You can use Docker image to use this program easily without knowing code or installing PHP etc...

```
docker run --rm -ti -v </path/to/downloads>:/app/downloads -u $(id -u):$(id -g) jaymoulin/yamete download [...]
```

see usage to complete *\[...\]*

with *\</path/to/downloads>* the path where downloaded assets will be downloaded to.

Note: Use the `-u $(id -u):$(id -g)` part for yamete to run as a specific user. It's recommanded to use static values (see: https://docs.docker.com/engine/reference/commandline/exec/#options)

### Build Docker Image

To build this image locally 

```
docker build -t jaymoulin/yamete -f docker/Dockerfile .
```

Supported sites
---------------

Here's is the list of supported sites for now:

 * asmhentai.com
 * comicspornoxxx.com
 * e-hentai.org
 * 8muses.com
 * 8muses.download
 * freeadultcomix.com
 * hbrowse.com
 * hentai2read.com
 * hentaifromhell.org
 * nhentai.net
 * hentaiporns.net
 * luscious.net
 * myhentaicomics.com
 * nxt-comics.com
 * porncomix.info
 * porncomixonline.net
 * porncomix.site
 * shentai.xyz
 * simply-hentai.com
 * xyzcomics.com
 * hentaifantasy.it
 * hentaivn.net
 * yaoimangaonline.com
 * vercomicsporno.com
 * hitomi.la
 * comicsmanics.com
 * hentaifr.net
 * myreadingmanga.info
 * gassummit.ru
 * hentai4manga.com
 * erolord.com
 * pururin.io
 * readhentaimanga.com
 * hentaiporn.pics
 * warcraftporn.pro
 * 3dsexpictures.net
 * hentaimanga.pro
 * sexcartoonpics.com
 * xsexcomics.com
 * sexy3dcomix.com
 * xxxtooncomics.com
 * toonsex.pics
 * freesexcomix.pro
 * toonpornpics.com
 * furrypornpics.net
 * 3dcomix.pro
 * xxxhentaipics.pro
 * lolhentai.pro
 * lolporn.pro
 * 3dsexcomics.pro
 * xxxhentaicomix.com
 * xxxmangacomix.com
 * sexytoonporn.com
 * cartoonsexcomix.com
 * xxx3dcomix.com
 * xxxcomicporn.com
 * 3dcomix.pro
 * 3dsextoons.net
 * 3dcartoons.net
 * 3dpics.pro
 * freefamouscartoonporn.com
 * cartoonpornimages.com
 * cartoontumblrporn.com
 * cartoonsexpic.com
 * comicporn.info
 * cartoonsex.name
 * toonporn.me
 * cartoonpornimages.com
 * cartooncomicporn.com
 * cartoonporncomics.name
 * cartoonpornfr.com
 * cartoonpicsporn.com
 * cartoonpornpicture.com
 * cartoonxxxcomix.com
 * animesexypics.com
 * hotsexhentai.com
 * x-hentaiporn.com
 * hentai-high-school.com
 * hotanimeporno.com
 * sexualhentai.net
 * fullmetal-hentai.com
 * bebop-hentai.com
 * mangerotica.com
 * xcomics4you.com
 * hcomicbook.com
 * hentairules.net
 * thehentaicomics.com
 * palcomix.com
 * hentaifox.com
 * perveden.com
 * chochox.com
 * lovehentaimanga.com
 * hentaibeast.com
 * doujin-th.com
 * hentaithai.com
 * doujins.com
 * erofus.com
 * mangakakalot.com
 * hentaihere.com
 * korohentai.com
 * hentaimangaly.com
 * 9hentai.com
 * mintmanga.com
 * mangafap.com
 * hentai.cafe
 * hentai-archive.net
 * hentai-ita.net
 * sankakucomplex.com
 * multporn.net
 * azporncomics.com
 * theyiffgallery.com
 * hdporncomics.com
 * xcartx.com
 * hentaihand.com
 * multicomics.net
 * comicspornohentai.com
 * poringa.com
 * ver-comics-porno.com
 * cartoonporn.eu
 * sexcomic.org
 * hentaicloud.com
 * nude-moon.me
 * gntai.xyz
 * hentaigratis.biz
 * doujinhentai.net
 * hqdesexo.com
 * hentai24h.org
 * hentaiworld.fr
 * vercomicsporno.xxx
 * pornoanimexxx.com
 * comic-porno.com
 * verpornocomic.com
 * hentai-corp.com
 * myhentaigallery.com
 * auto-wedding.ru
 * pornocomics.net
 * hdhentaicomics.com
 * cartoonporncomics.info
 * hentaischool.com
 * megapornpics.com
 * porkyfap.org
 * girlscv.com
 * pornhugo.com
 * goodcomix.tk
 * porncomix.re
 * freesexcomics.pro
 * manhwahentai.com

You must pass the URL to the album for the program to download it!
