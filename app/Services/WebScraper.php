<?php
/**
 * Created by PhpStorm.
 * User: gkalligeros
 * Date: 1/14/2018
 * Time: 2:24 PM
 */

namespace App\Services;


use App\Restaurant;
use Goutte\Client;

class WebScraper
{

    /**
     * Scrape url for restaurant name and description and update local DB
     * @param Restaurant $restaurant
     */
    public function updateRestaurantInfo(Restaurant $restaurant) {
        $client = new Client();
        $crawler = $client->request('GET', $restaurant->url);

        $crawler->filter('#restaurant_show > div > div.container > div > div.col-md-8 > div:nth-child(3) > div > div')->each(function ($node) use ($restaurant) {
            $restaurant->description = $node->text();
        });
        $crawler->filter('#restaurant-name')->each(function ($node) use ($restaurant) {
            $restaurant->name = $node->text();
        });
        $restaurant->save();
    }

}