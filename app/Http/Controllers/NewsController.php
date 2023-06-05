<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Foundation\Application;

class NewsController extends Controller
{
    protected $newsapi;
    protected $nytimes;
    protected $theguardian;

    public function __construct(Application $app)
    {
        $this->newsapi = $app->newsapi;
        $this->nytimes = $app->nytimes;
        $this->theguardian = $app->theguardian;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $setting = Setting::where('user_id', $user->id)->first();
        $responses = [];
        if (empty($setting)) {
            $responses = Http::pool(fn (Pool $pool) => [
                $pool->as('newsapi')->get($this->newsapi->endpoint()),
                $pool->as('nytimes')->get($this->nytimes->endpoint()),
                $pool->as('theguardian')->get($this->theguardian->endpoint()),
            ]);
        } else {
            $categories = $setting->categories;
            $authors = $setting->authors;
            $params = [
                'category' => $categories,
                'author' => $authors,
            ];
            if (empty($setting->sources)) {
                $responses = Http::pool(fn (Pool $pool) => [
                    $pool->as('newsapi')->get($this->newsapi->endpoint($params)),
                    $pool->as('nytimes')->get($this->nytimes->endpoint($params)),
                    $pool->as('theguardian')->get($this->theguardian->endpoint($params)),
                ]);
            } else {
                $sources = explode(',', $setting->sources);
                $responses = Http::pool(
                    fn (Pool $pool) =>
                    array_map(fn ($source) => $pool->as($source)->get($this->$source->endpoint($params)), $sources)
                );
            }
        }

        $data = [];
        foreach ($responses as $key => $response) {
            if ($response->ok()) {
                if ($key == 'newsapi') {
                    $data = array_merge($data, array_map([$this->newsapi, 'mapper'], $response->json()['articles']));
                } else if ($key == 'nytimes') {
                    $data = array_merge($data, array_map([$this->nytimes, 'mapper'], $response->json()['response']['docs']));
                } else if ($key == 'theguardian') {
                    $data = array_merge($data, array_map([$this->theguardian, 'mapper'], $response->json()['response']['results']));
                }
            }
        }

        return response()->json($data);
    }

    public function search(Request $request)
    {
        $queryParams = $request->all();
        $requests = [];
        $responses = [];
        if (empty($queryParams['source'])) {
            $responses = Http::pool(fn (Pool $pool) => [
                $pool->as('newsapi')->get($this->newsapi->endpoint($queryParams)),
                $pool->as('nytimes')->get($this->nytimes->endpoint($queryParams)),
                $pool->as('theguardian')->get($this->theguardian->endpoint($queryParams)),
            ]);
        } else {
            if ($queryParams['source'] === 'newsapi') {
                $responses = Http::pool(fn (Pool $pool) => [
                    $pool->as('newsapi')->get($this->newsapi->endpoint($queryParams)),
                ]);
            } else if ($queryParams['source'] === 'nytimes') {
                $responses = Http::pool(fn (Pool $pool) => [
                    $pool->as('nytimes')->get($this->nytimes->endpoint($queryParams)),
                ]);
            } else if ($queryParams['source'] === 'theguardian') {
                $responses = Http::pool(fn (Pool $pool) => [
                    $pool->as('theguardian')->get($this->theguardian->endpoint($queryParams)),
                ]);
            }
        }

        $data = [];
        foreach ($responses as $key => $response) {
            if ($response->ok()) {
                if ($key == 'newsapi') {
                    $data = array_merge($data, array_map([$this->newsapi, 'mapper'], $response->json()['articles']));
                } else if ($key == 'nytimes') {
                    $data = array_merge($data, array_map([$this->nytimes, 'mapper'], $response->json()['response']['docs']));
                } else if ($key == 'theguardian') {
                    $data = array_merge($data, array_map([$this->theguardian, 'mapper'], $response->json()['response']['results']));
                }
            }
        }

        return response()->json($data);
    }
}
