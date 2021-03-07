<?php
/**
 * Created by PhpStorm.
 * User: gkalligeros
 * Date: 1/13/2018
 * Time: 7:59 PM
 */

class XmlHelper
{
    public static function xml2json($file) {
        $output = "";
        if (file_exists($file)) {
            $xml = simplexml_load_file($file);
            $date = $xml->EventDate;
            foreach ($xml->Event as $data) {
                $filtered_xml = [];

                $startime = (string)$data->EventStartTime;
                $endtime = (string)$data->EventEndTime;
                $filtered_xml['id'] = (string)$data->event_id;
                $filtered_xml['name'] = (string)$data->EventDetailInfo->eventName;
                $filtered_xml['description'] = (string)$data->Extended_DetailInfo->message;
                $filtered_xml['startime'] = getEpoch($date . $startime);
                $filtered_xml['endtime'] = getEpoch($date . $endtime);
                $filtered_xml['duration'] = $filtered_xml['endtime'] - $filtered_xml['startime'];
                $json = json_encode(($filtered_xml), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . ",";
                $output .= $json;
            }

            return $output;
        } else {
            exit('Failed to open ' . $file);
        }
    }
}