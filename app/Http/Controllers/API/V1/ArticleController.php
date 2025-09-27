<?php

namespace App\Http\Controllers\API\V1;

use App\DTOs\ArticleDTO;
use App\Enums\SourceKey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArticleService;
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
        $article = $this->service->getById($id);
        return response()->json((new ArticleDTO($article))->toArray());
    }

    public function meta()
    {
        $sources = collect(SourceKey::cases())->map(fn($case) => [
            'key' => $case->value,
            'title' => $case->title(), // from enum method
        ]);
        $categories = Category::all(['name']);
        return response()->json(['sources' => $sources, 'categories' => $categories]);
    }
}
