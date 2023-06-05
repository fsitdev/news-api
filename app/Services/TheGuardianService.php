<?php

namespace App\Services;

class TheGuardianService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function endpoint($params)
    {
        $query = $this->buildQuery($params);
        return "https://content.guardianapis.com/search?api-key=$this->apiKey&$query";
    }

    public function mapper($item)
    {
        return [
            'title' => $item['webTitle'],
            'url' => $item['webUrl'],
            'date' => $item['webPublicationDate'],
            'image' => null,
            'source' => 'theguardian',
        ];
    }

    private function buildQuery($params)
    {
        $map = [
            'search' => 'q',
            'dateFrom' => 'from-date',
            'dateTo' => 'to-date',
            'category' => 'section',
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
