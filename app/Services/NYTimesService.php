<?php

namespace App\Services;

class NYTimesService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function endpoint($params)
    {
        $query = $this->buildQuery($params);
        return "https://api.nytimes.com/svc/search/v2/articlesearch.json?api-key=$this->apiKey&$query";
    }

    public function mapper($item)
    {
        return [
            'title' => $item['headline']['main'],
            'url' => $item['web_url'],
            'date' => $item['pub_date'],
            'image' => empty($item['multimedia']) ? null : 'https://static01.nyt.com/' . $item['multimedia'][0]['url'],
            'source' => 'nytimes',
        ];
    }

    private function buildQuery($params)
    {
        $map = [
            'search' => 'search',
            'dateFrom' => 'begin_date',
            'dateTo' => 'end_date',
            'category' => 'category',
        ];
        $data = [];
        foreach ($map as $key => $value) {
            if (!empty($params[$key])) {
                $data[$value] = $params[$key];
                array_push($data, "$value=" . $params[$key]);
            }
        }
        return implode('&', $data);
    }
}
