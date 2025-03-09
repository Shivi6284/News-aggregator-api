<?php

namespace App\Services;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ArticleService
{
    public function fetchNewsFromAPIs()
    {
        $news = [];
        $news = array_merge($news, $this->fetchFromNewsAPI());
        $news = array_merge($news, $this->fetchFromGuardianAPI());
        $this->storeArticles($news);
    }

    private function fetchFromNewsAPI()
    {
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => env('NEWS_API_KEY'),
            'country' => 'us',
            'category' => 'technology',
        ]);

        if ($response->successful()) {
            return array_map(fn ($article) => [
                'title' => $article['title'],
                'content' => $article['description'],
                'author' => $article['author'] ?? 'Unknown',
                'source' => $article['source']['name'],
                'category' => 'Technology',
                'url' => $article['url'],
                'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
            ], $response->json()['articles']);
        }

        return [];
    }

    private function fetchFromGuardianAPI()
    {
        $response = Http::get('https://content.guardianapis.com/search', [
            'api-key' => env('GUARDIAN_API_KEY'),
            'section' => 'technology',
            'show-fields' => 'headline,trailText,byline,webPublicationDate',
        ]);

        if ($response->successful()) {
            return array_map(fn ($article) => [
                'title' => $article['fields']['headline'],
                'content' => $article['fields']['trailText'],
                'author' => $article['fields']['byline'] ?? 'Unknown',
                'source' => 'The Guardian',
                'category' => 'Technology',
                'url' => $article['webUrl'],
                'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
            ], $response->json()['response']['results']);
        }

        return [];
    }

    private function storeArticles($articles)
    {
        foreach ($articles as $article) {
            Article::updateOrCreate(['url' => $article['url']], $article);
        }
    }
}
