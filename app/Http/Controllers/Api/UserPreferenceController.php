<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $preference = $request->user()->preference;

        if (!$preference) {
            return response()->json(['message' => 'No preferences found.'], 404);
        }

        return response()->json($preference, 200);
    }

    public function store(UserPreferenceRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $user->preference()->updateOrCreate(['user_id' => $user->id], $data);

        return response()->json(['message' => 'Preferences updated successfully!']);
    }

    public function personalizedNews(Request $request)
    {
        $result = $this->articleRepository->getPersonalizedNews($request->user());

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['status']);
        }

        return response()->json($result);
    }
}
