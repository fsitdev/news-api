<?php

namespace App\Services;

class NewsApiService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function endpoint($params)
    {
        $query = $this->buildQuery($params);
        return "https://newsapi.org/v2/everything?apiKey=$this->apiKey&$query";
    }

    public function mapper($item)
    {
        return [
            'title' => $item['title'],
            'url' => $item['url'],
            'date' => $item['publishedAt'],
            'image' => $item['urlToImage'],
            'source' => 'newsapi',
        ];
    }

    private function buildQuery($params)
    {
        $map = [
            'search' => 'q',
            'dateFrom' => 'from',
            'dateTo' => 'to',
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
