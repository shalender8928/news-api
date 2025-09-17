<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Models\Source;
use App\Models\Category;

class ArticleController extends Controller
{
    protected ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/articles
     * Query params: q, source, category, author, from, to, per_page
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q','source','category','author','from','to','per_page']);
        $result = $this->service->list($filters);
        return response()->json($result);
    }

    public function show($id)
    {
        $filters = ['per_page' => 1];
        $res = $this->service->list(['q' => null]);
        // for simplicity show via repository or model
        $article = \App\Models\Article::with(['source','author','category'])->findOrFail($id);
        return response()->json((new \App\DTOs\ArticleDTO($article))->toArray());
    }

    public function meta()
    {
        $sources = Source::all(['key','title']);
        $categories = Category::all(['name']);
        return response()->json(['sources' => $sources, 'categories' => $categories]);
    }
}
