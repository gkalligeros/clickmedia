<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeRestaurantInfo;
use App\Restaurant;
use App\Services\WebScraper;
use App\Services\XmlParser;

class RestaurantController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {


        return view("index");
    }

    /**
     * update db from sitemap and return all updated models
     * for each model start a async job to get data by scraping
     * @return static
     */
    public function all() {
        $parser = new XmlParser();
        $restaurants = $parser->getRestaurantList(50);
        foreach ($restaurants as $restaurant) {
            $restaurant_eloquent = Restaurant::firstOrNew(['url' => $restaurant["value"]]);
            if (!$restaurant_eloquent->exists) {
                $restaurant_eloquent->description = "";
                $restaurant_eloquent->name = $this->getTempName($restaurant_eloquent);
                $restaurant_eloquent->save();
            }
             $this->dispatch(new ScrapeRestaurantInfo($restaurant_eloquent));
        }

        return Restaurant::all()->keyBy('id');
    }

    /**
     * Update a paricular restaurant by scraping and show it
     * @param $id
     * @return $this
     */
    public function show($id) {
        $restaurant = Restaurant::findorFail($id);
        $web_scraper = new WebScraper();
        $web_scraper->updateRestaurantInfo($restaurant);

        return view("restaurant")->with([ "restaurant"=>$restaurant]);
    }

    /**
     * generate a temp (before scraping ) name for restaurant
     * @param $restaurant
     * @return mixed
     */
    private function getTempName($restaurant) {
        $array_url = explode("/", $restaurant->url);

        $last_part = $array_url[count($array_url) - 1];
        $last_part_array = collect(explode("-", $last_part));

        return $last_part_array->forget(0)->map(function ($item) {
            return ucfirst($item);
        })->implode(" ");


    }
}
