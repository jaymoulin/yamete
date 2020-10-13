<?php

namespace Yamete\Driver;

class XXXCartoonPicCom extends XXXComicPornCom
{
    protected function getDomain(): string
    {
        return 'xxxcartoonpic.com';
    }

    protected function getSelector(): string
    {
        return '.thumbs figure a';
    }
}
