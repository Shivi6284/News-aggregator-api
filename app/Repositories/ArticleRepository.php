<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArticleRepository implements ArticleRepositoryInterface
{
    protected $query;

    public function __construct()
    {
        $this->query = Article::query();
    }

    public function getAllArticles(Request $request)
    {
        return Cache::remember('articles', 3600, function () use ($request) {
            return $this->query
                ->when($request->keyword, fn($query, $keyword) =>
                    $query->where('title', 'like', "%{$keyword}%"))
                ->when($request->category, fn($query, $category) =>
                    $query->where('category', $category))
                ->when($request->source, fn($query, $source) =>
                    $query->where('source', $source))
                ->when($request->date, fn($query, $date) =>
                    $query->whereDate('published_at', $date))
                ->paginate(10);
        });
    }

    public function getArticleById($id)
    {
        return $this->query->findOrFail($id);
    }

    public function getPersonalizedNews($user)
    {
        $preference = $user->preference;
        if (!$preference) {
            return ['error' => 'No preferences set', 'status' => 404];
        }

        return $this->query
            ->when($preference->categories, fn($query, $categories) =>
                $query->whereIn('category', $categories))
            ->when($preference->sources, fn($query, $sources) =>
                $query->whereIn('source', $sources))
            ->when($preference->authors, fn($query, $authors) =>
                $query->whereIn('author', $authors))
            ->paginate(10);
    }
}
