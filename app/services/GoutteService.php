<?php

namespace App\Services;

use Goutte\Client;

class GoutteService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function test(string $url)
    {
        $time_start = microtime(true);
        $crawler = $this->client->request('GET', $url);
        //title
        dump($crawler->filterXPath("//meta[@property='og:title']")->first()->attr('content'));

        $b = $crawler->filterXPath("//meta[@property='twitter:title']");
        if ($b->count() != 0) {
            dump($b->first()->attr('content'));
        }

        $c = $crawler->filterXPath('//h1');
        if ($c->count() != 0) {
            dump($c->first()->text());
        }

        $d = $crawler->filterXPath("//meta[@name='description']");
        if ($d->count() != 0) {
            dump($d->first()->attr('content'));
        }
        $d = $crawler->filterXPath("//meta[@property='twitter:description']");

        if ($d->count() != 0) {
            dump($d->first()->attr('content'));
        }

        $d = $crawler->filterXPath("//a[contains(@class,'author')]");
        if ($d->count() != 0) {
            dump($d->first()->text());
        }

        $e = $crawler->filterXPath("//meta[@name='author']");
        if ($e->count() != 0) {
            dump($e->first()->attr('content'));
        }

        $f = $crawler->filterXPath("//meta[@property='article:author']");
        if ($f->count() != 0) {
            dump($f->first()->attr('content'));
        }
        $g = $crawler->filterXPath("//meta[@property='og:title']");
        if ($g->count() != 0) {
            dump($g->first()->attr('content'));
        }
        $h = $crawler->filterXPath("//meta[@property='article:published_time']");
        if ($h->count() != 0) {
            dump($h->first()->attr('content'));
        }

        $h = $crawler->filterXPath("//meta[@property='og:image']");
        if ($h->count() != 0) {
            echo '<img width="400px" src="' . ($h->first()->attr('content')) . '">';
        }
        $h = $crawler->filterXPath("//meta[@property='og:article:section']");
        if ($h->count() != 0) {
            dump($h->first()->attr('content'));
        }
        $h = $crawler->filterXPath("//meta[@property='og:article:author']");
        if ($h->count() != 0) {
            dump($h->first()->attr('content'));
        }
        $h = $crawler->filterXPath("//meta[@property='og:article:published_time']");
        if ($h->count() != 0) {
            dump($h->first()->attr('content'));
        }

        dd('Time:' . (microtime(true) - $time_start));

    }
}
