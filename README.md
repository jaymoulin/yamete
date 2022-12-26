![logo](logo.png)

Yamete - Hentai downloader in PHP CLI - Easy PHP Site downloader
================================================================

[![latest release](https://img.shields.io/github/release/jaymoulin/yamete.svg "latest release")](http://github.com/jaymoulin/yamete/releases)
[![Docker Pulls](https://img.shields.io/docker/pulls/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![Docker stars](https://img.shields.io/docker/stars/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![PayPal donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/ppl.png "PayPal donation")](https://www.paypal.me/jaymoulin)
[![Buy me a coffee](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png "Buy me a coffee")](https://www.buymeacoffee.com/jaymoulin)
[![Buy me a coffee](https://ko-fi.com/img/githubbutton_sm.svg "Buy me a coffee")](https://www.ko-fi.com/jaymoulin)

DISCLAIMER: As-of 2021, this product does not have a free support team anymore. If you want this product to be maintained, please support on Patreon.

(This product is available under a free and permissive license, but needs financial support to sustain its continued improvements. In addition to maintenance and stability there are many desirable features yet to be added.)

This image allows you easily download specific assets of a site

But why?
--------

You should not ask why I made it but why is it so popular?

If we trust 2019 or 2018 Pornhub stats (https://www.pornhub.com/insights/women-of-the-world, https://www.pornhub.com/insights/2018-year-in-review),
Hentai is the top 3 search for both women and men and growing.

At the beginning, this repo purpose was to download resources from web sites with PHP... It just took a strange turn... (trust me dude, it's named `EZ site downloader` for a reason)
BUT you can still use it for its former purpose by coding your own drivers! (`-d` parameter exists for this purpose)

Installation
------------
Easy install with a single line of code
Please note that this package is also hosted on Github Container Registry, just add `ghcr.io/` before the image name (`docker pull ghcr.io/jaymoulin/yamete` instead of `jaymoulin/yamete`)

### Docker
`docker run --rm -ti -v </path/to/downloads>:/root/downloads jaymoulin/yamete download [...]`

### Composer
`composer require jaymoulin/yamete`


Usage
-----

```
Usage:
  download [options]

Options:
  -u, --url[=URL]             Url to download from
  -l, --list[=LIST]           List file with multiple urls
  -i, --interactive           Interactive (send url to STDIN, never ends)
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

NOTE : Yamete just downloads what it's asked to. If you want to download the same URL multiple times, assets will be downloaded multiple times. The output name could be the same depending on how the driver is coded (All drivers provided with Yametee are designed to give the same output name each time run IF the source file has the same name)

### Mandatory parameter

`-u` or `--url` : the URL to download assets from

OR

`-l` or `--list` : the path to a file containing all URLs to download from on each line

OR

`-i` or `--interactive` : each url entry must be sent separately in the STDIN input. EOF or ^C (<kbd>CTRL</kbd> + <kbd>C</kbd>)

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
docker run --rm -ti -v </path/to/downloads>:/app/downloads -u $(id -u) jaymoulin/yamete download [...]
```

see usage to complete *\[...\]*

with *\</path/to/downloads>* the path where downloaded assets will be downloaded to.

Note: Use the `-u $(id -u)` part for yamete to run as a specific user. It's recommanded to use static values (see: https://docs.docker.com/engine/reference/commandline/exec/#options)

Note: Add `--init` parameter before `jaymoulin/yamete` if you intend to use interactive mode (`-i` parameter of Yamete), this is mandatory with `-ti` parameter.
Otherwise, you will not be able to quit interactive mode. If you forgot the `--init` parameter or this parameter is not before the image, use (<kbd>CTRL</kbd>+<kbd>P</kbd> then <kbd>CTRL</kbd>+<kbd>Q</kbd> to detach your running container)

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
 * nxt-comics.net
 * porncomixonline.net
 * simply-hentai.com
 * xyzcomics.com
 * hentaifantasy.it
 * vercomicsporno.com
 * hitomi.la
 * comicsmanics.com
 * hentaifr.net
 * hentai4manga.com
 * hentaiporn.pics
 * warcraftporn.pro
 * 3dsexpictures.net
 * hentaimanga.pro
 * sexcartoonpics.com
 * xsexcomics.com
 * sexy3dcomics.club
 * xxxtooncomics.com
 * toonsex.pics
 * toonpornpics.com
 * furrypornpics.net
 * xxxhentaipics.pro
 * lolporn.pro
 * 3dsexcomics.pro
 * xxxmangacomix.com
 * sexytoonporn.com
 * cartoonsexcomix.com
 * xxx3dcomix.com
 * xxxcomicporn.com
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
 * sexualhentai.net
 * hcomicbook.com
 * hentairules.net
 * thehentaicomics.com
 * palcomix.com
 * hentaifox.com
 * perveden.com
 * chochox.com
 * doujin-th.com
 * hentaithai.com
 * doujins.com
 * erofus.com
 * mangakakalot.com
 * hentaihere.com
 * 9hentai.ru
 * mintmanga.live
 * hentai-archive.net
 * hentai-ita.net
 * sankakucomplex.com
 * multporn.net
 * theyiffgallery.com
 * hdporncomics.com
 * xcartx.com
 * hentaihand.com
 * poringa.com
 * ver-comics-porno.com
 * hentaicloud.com
 * hentaigratis.biz
 * doujinhentai.net
 * hentaiworld.fr
 * vercomicsporno.xxx
 * pornoanimexxx.com
 * verpornocomic.com
 * myhentaigallery.com
 * pornocomics.net
 * hdhentaicomics.com
 * cartoonporncomics.info
 * hentaischool.com
 * megapornpics.com
 * freesexcomics.pro
 * hentai-id.tv
 * superhentais.com
 * rajahentai.com
 * upcomics.org
 * tnaflix.com
 * hentaicomics.pro
 * porncomics.me
 * sexcomix.me
 * hentaipornpics.net
 * comicsporn.net
 * cartoonporn.pics
 * mangaporno.pro
 * superhq.net
 * animephile.com
 * yuri-ism.net
 * readhent.ai
 * tmohentai.com
 * comicsporno.xxx
 * hentairead.com
 * doujinreader.com
 * myhentaigallery.com
 * manytoon.com
 * mysexgamer.com
 * lolhentai.net
 * hmangasearcher.com
 * mangaowl.com
 * hentaishark.com
 * 18comic.org
 * g6hentai.com
 * naughtyhentai.com
 * hentai-paradise.fr
 * truyenhentai18.net
 * xxxmanga.pro
 * hentaixxxcomics.com
 * allporncomic.com
 * hentaikai.com
 * hqhentai.online
 * hentaizone.me
 * mymangacomics.com
 * zizki.com
 * milftoon.xxx
 * yiffer.xyz
 * avangard-iv.ru
 * sexporncomics.com
 * eggporncomics.com
 * world-hentai.com
 * mangatown.com
 * yaoihavenreborn.com
 * porngameshd.com
 * onlineporngames.xyz
 * onlinesexgames.cc
 * gamesofdesired.com
 * 66games.net
 * sexgamesx.com
 * ipadsexgames.com
 * porngamesapp.com
 * porngames.cc
 * porngames.zone
 * sexgamescc.com
 * comicsporn.me
 * xlecx.com
 * kingcomix.com
 * savitahd.net
 * bestporncomix.com
 * tenmanga.com
 * ninemanga.com
 * mangabat.com
 * isekaiscan.com
 * wiemanga.com
 * fanfox.net
 * mangaeden.com
 * mangahere.cc
 * manganelo.com
 * komikstation.com
 * hennojin.com
 * imgbox.com
 * mangahentai.me
 * manhwahentai.me
 * boyslove.me
 * freecomiconline.me
 * freewebtooncoins.com
 * mangabob.com
 * manganeloteam.com
 * mangatx.com
 * toomics.com
 * 365manga.com
 * mangainn.net
 * lovehug.net
 * manga3s.com
 * manhuas.net
 * twhentai.com
 * lectortmo.com
 * mangairo.com
 * mangatoon.mobi
 * readm.org
 * rawdevart.com
 * the-simpsonsporn.com
 * bobsvagene.club
 * readmng.com
 * kisslove.net
 * taadd.com
 * hentaikun.com
 * nhentai.xxx
 * nhentai.io
 * hentai.desi
 * xxxmilftoon.com
 * xxxcartoonpic.com
 * overwatchporn.pro
 * porncomix.pro
 * heaventoon.com
 * readfreecomics.com
 * mangaread.co
 * porncomixinfo.net
 * funmanga.com
 * mangakakalots.com
 * manga4life.com
 * comixzilla.com
 * mult34.com
 * joyhentai.com
 * 8muses.com/forum
 * hentaixxxpic.com
 * comicsporn.pro
 * fadadosexo.com
 * terceiroz.com
 * xxxtoonpic.com
 * hentaihome.net
 * seuhentai.com
 * superhentai.blog
 * hentaixxx.me
 * hentai-img.com
 * hentaipornpic.com
 * 3dhentaicomics.com
 * xxxcomicsex.com
 * comicsarmy.com
 * xxxcomixporn.com
 * hentaicomics.me
 * imagearn.com

You must pass the URL to the album for the program to download it!
