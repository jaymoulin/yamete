
<div class="parent">
    <div class="centered">
        <img align="right" src=logo.png>
        <br>
            <h1 align="left" >Yamete - Hentai downloader in PHP CLI - Easy PHP Site downloader</h1>
        </br>
    <div>
</div>

[![latest release](https://img.shields.io/github/release/jaymoulin/yamete.svg "latest release")](http://github.com/jaymoulin/yamete/releases)
[![Docker Pulls](https://img.shields.io/docker/pulls/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![Docker stars](https://img.shields.io/docker/stars/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![PayPal donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/ppl.png "PayPal donation")](https://www.paypal.me/jaymoulin)
[![Buy me a coffee](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png "Buy me a coffee")](https://www.buymeacoffee.com/3Yu8ajd7W)
[![Become a Patron](https://badgen.net/badge/become/a%20patron/F96854 "Become a Patron")](https://patreon.com/jaymoulin)

(This product is available under a free and permissive license, but needs financial support to sustain its continued improvements. In addition to maintenance and stability there are many desirable features yet to be added.)

Early Releases are available on Patreon : https://www.patreon.com/jaymoulin/posts?tag=yamete

This image allows you easily download specific assets of a site

Interested in hentai? You may like this: https://yametee.store

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

Merch - https://yametee.store
---------------------------------

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
 * porncomix.info
 * porncomixonline.net
 * porncomix.site
 * simply-hentai.com
 * xyzcomics.com
 * hentaifantasy.it
 * yaoimangaonline.com
 * vercomicsporno.com
 * hitomi.la
 * comicsmanics.com
 * hentaifr.net
 * hentai4manga.com
 * pururin.io
 * hentaiporn.pics
 * warcraftporn.pro
 * 3dsexpictures.net
 * hentaimanga.pro
 * sexcartoonpics.com
 * xsexcomics.com
 * sexy3dcomix.com
 * xxxtooncomics.com
 * toonsex.pics
 * toonpornpics.com
 * furrypornpics.net
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
 * 9hentai.com
 * mintmanga.live
 * hentai.cafe
 * hentai-archive.net
 * hentai-ita.net
 * sankakucomplex.com
 * azporncomics.com
 * theyiffgallery.com
 * hdporncomics.com
 * xcartx.com
 * hentaihand.com
 * poringa.com
 * ver-comics-porno.com
 * sexcomic.org
 * hentaicloud.com
 * nude-moon.net
 * hentaigratis.biz
 * doujinhentai.net
 * hentai24h.org
 * hentaiworld.fr
 * vercomicsporno.xxx
 * pornoanimexxx.com
 * comic-porno.com
 * verpornocomic.com
 * hentai-corp.com
 * myhentaigallery.com
 * pornocomics.net
 * hdhentaicomics.com
 * cartoonporncomics.info
 * hentaischool.com
 * megapornpics.com
 * porkyfap.org
 * goodcomix.tk
 * porncomicszone.net
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
 * mangapark.net
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
 * kisshentaitv.com
 * hmangasearcher.com
 * mangaowl.com
 * hentaishark.com
 * 18comic.org
 * g6hentai.com
 * naughtyhentai.com
 * hentai.tv
 * hentai-paradise.fr
 * truyenhentai18.net
 * hcomic.in
 * acgxmanga.com
 * d-upp.net
 * a-upp.com
 * xxxmanga.pro
 * hentaixxxcomics.com
 * allporncomic.com
 * hentaikai.com
 * hqhentai.online
 * hentaizone.me
 * mymangacomics.com
 * zizki.com
 * milftoon.xxx
 * ilikecomix.com
 * yiffer.xyz
 * porncomix.one
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
 * mangahome.com
 * xlecx.com
 * kingcomix.com
 * savitahd.net
 * bestporncomix.com
 * tenmanga.com
 * 18lhplus.com
 * ninemanga.com
 * mangabat.com
 * isekaiscan.com
 * mangareader.me
 * wiemanga.com
 * fanfox.net
 * mangaeden.com
 * mangahere.cc
 * manganelo.com
 * mangahub.io
 * komikstation.com
 * hennojin.com
 * imgbox.com
 * mangahentai.me
 * manhwahentai.me
 * boyslove.me
 * freecomiconline.me
 * freewebtooncoins.com
 * mangarockteam.com
 * mangabob.com
 * manganeloteam.com
 * mangatx.com
 * hentainexus.com
 * toomics.com
 * 365manga.com
 * mangainn.net
 * loveheaven.net
 * wakamics.com
 * manhuas.net
 * twhentai.com
 * lectortmo.com
 * mangacrush.com
 * mangairo.com
 * mangatoon.mobi
 * readm.org
 * zinmanga.com
 
You must pass the URL to the album for the program to download it!
