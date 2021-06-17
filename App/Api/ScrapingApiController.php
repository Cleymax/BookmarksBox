<?php


namespace App\Api;


use App\Controllers\Controller;
use App\Services\ScraperService;

class ScrapingApiController extends Controller
{
    /**
     * @throws \Exception
     */
    public function scrape()
    {
        $this->checkGet('q', 'Merci de préciser une url !');
        $scrape = new ScraperService();

        $start = microtime(true);
        $result = $scrape->getInformation(htmlspecialchars($_GET['q']));
        $time = microtime(true) - $start;

        $this->respond_json([
            'take' => $time,
            'data' => $result
        ]);
    }
}
