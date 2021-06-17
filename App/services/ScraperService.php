<?php

namespace App\Services;

use Goutte\Client;

class ScraperService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getInformation(string $url): array
    {
        $result = [];
        $crawler = $this->client->request('GET', $url);

        $b = $crawler->filterXPath("//meta[@property='og:title']");
        if ($b->count() != 0) {
            $result['title'] = $b->first()->attr('content');
        } else {
            $b = $crawler->filterXPath("//meta[@property='twitter:title']");
            if ($b->count() != 0) {
                $result['title'] = $b->first()->attr('content');
            } else {
                $b = $crawler->filterXPath('//title');
                if ($b->count() != 0) {
                    $result['title'] = $b->first()->text();
                } else {
                    $b = $crawler->filterXPath('//h1');
                    if ($b->count() != 0) {
                        $result['title'] = $b->first()->text();
                    }
                }
            }
        }

        $d = $crawler->filterXPath("//meta[@name='description']");
        if ($d->count() != 0) {
            $result['description'] = $d->first()->attr('content');
        } else {
            $d = $crawler->filterXPath("//meta[@property='twitter:description']");
            if ($d->count() != 0) {
                $result['description'] = $d->first()->attr('content');
            }else {
                $d = $crawler->filterXPath("//meta[@property='og:description']");
                if ($d->count() != 0) {
                    $result['description'] = $d->first()->attr('content');
                }
            }
        }

        $e = $crawler->filterXPath("//meta[@name='author']");
        if ($e->count() != 0) {
            $result['author'] = $e->first()->attr('content');
        } else {
            $e = $crawler->filterXPath("//meta[@property='article:author']");
            if ($e->count() != 0) {
                $result['author'] = $e->first()->attr('content');
            } else {
                $e = $crawler->filterXPath("//a[contains(@class,'author')]");
                if ($e->count() != 0) {
                    $result['author'] = $e->first()->text();
                }
            }
        }

        $h = $crawler->filterXPath("//meta[@property='article:published_time']");
        if ($h->count() != 0) {
            $result['published_time'] = $h->first()->attr('content');
        }

        $h = $crawler->filterXPath("//meta[@property='og:image']");
        if ($h->count() != 0) {
            $result['image'] = $h->first()->attr('content');

        } else {
            $h = $crawler->filterXPath("//meta[@name='twitter:image']");
            if ($h->count() != 0) {
                $result['image'] = $h->first()->attr('content');
            }
        }
        return $result;
    }
}
