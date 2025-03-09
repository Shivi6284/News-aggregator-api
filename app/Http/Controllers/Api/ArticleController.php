<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Fetch all articles",
     *     tags={"Articles"},
     *     @OA\Response(response=200, description="List of articles"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $articles = $this->articleRepository->getAllArticles($request);
        return response()->json($articles);
    }

    public function show($id)
    {
        $article = $this->articleRepository->getArticleById($id);
        return response()->json($article);
    }
}
