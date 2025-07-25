<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    // GET /api/articles
    public function index()
    {
        return Article::latest('published_at')->paginate(10);
    }

    // GET /api/articles/{id}
    public function show($id)
    {
        return Article::findOrFail($id);
    }

    // GET /api/articles/search?q=bitcoin
    public function search(Request $request)
    {
        $query = $request->input('q');
        $articles = Article::where('title', 'like', "%$query%")
            ->orWhere('summary', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
            ->latest('published_at')
            ->paginate(10);

        return $articles;
    }

    // GET /api/articles/filter?source=BBC&category=Politics&from=2024-01-01&to=2024-12-31
    public function filter(Request $request)
    {
        $query = Article::query();

        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('from')) {
            $query->whereDate('published_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('published_at', '<=', $request->input('to'));
        }

        return $query->latest('published_at')->paginate(10);
    }
}
