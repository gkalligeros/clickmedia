<?php

namespace App\Jobs;

use App\Restaurant;
use App\Services\WebScraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ScrapeRestaurantInfo implements ShouldQueue
{
    const REDIS_PUBLISH_ON_CHANNEL = "push-notification";
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $restaurant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Restaurant $restaurant) {
        $this->restaurant = $restaurant;
    }

    /**
     * Scrape web page and update db
     * Send push notification to client for real time  update
     *
     * @return void
     */
    public function handle() {
        $web_scraper = new WebScraper();
        $web_scraper->updateRestaurantInfo($this->restaurant);
        Redis::publish(static::REDIS_PUBLISH_ON_CHANNEL, json_encode($this->restaurant));

    }
}
