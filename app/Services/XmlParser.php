<?php
/**
 * Created by PhpStorm.
 * User: gkalligeros
 * Date: 1/14/2018
 * Time: 12:04 PM
 */

namespace App\Services;


/**
 * Class XmlParser
 * @package App\Services
 */
class XmlParser
{

    /**
     * Parse sitemap xml for Restaurant items (optional limit)
     * @param null $limit
     * @return static
     */
    public function getRestaurantList($limit=null)
    {
        $file_name = '2013-07-16.xml.gz';

        $buffer_size = 4 * 4096; // read 4kb at a time
        $out_file_name = str_replace('.gz', '', $file_name);

        $file = gzopen("http://www.ask4food.gr/sitemap1.xml.gz", 'rb');
        $out_file = fopen($out_file_name, 'wb');

        while (!gzeof($file)) {
            fwrite($out_file, gzread($file, $buffer_size));
        }

        fclose($out_file);
        gzclose($file);
        $a = file_get_contents($out_file_name);
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $a, $values, $tags);
        unlink($out_file_name);
        xml_parser_free($parser);
        $values = collect($values)->filter(function ($item) {
            return key_exists("value", $item) && substr($item["value"], 0, strlen("https://www.ask4food.gr/estiatoria/")) === "https://www.ask4food.gr/estiatoria/";
        });

        if($limit!=null)
        {
            $values=   $values->take($limit);
        }
        return $values;
    }
}