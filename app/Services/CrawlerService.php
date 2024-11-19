<?php

namespace App\Services;

use Crwlr\Crawler\HttpCrawler;
use Crwlr\Crawler\UserAgents\BotUserAgent;
use Crwlr\Crawler\UserAgents\UserAgentInterface;

class CrawlerService extends HttpCrawler
{
    protected function userAgent(): UserAgentInterface
    {
        return BotUserAgent::make('MyBot');
    }
}
