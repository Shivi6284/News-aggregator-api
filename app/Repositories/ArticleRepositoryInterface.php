<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface ArticleRepositoryInterface
{
    public function getAllArticles(Request $request);
    public function getArticleById($id);
    public function getPersonalizedNews($user);
}
