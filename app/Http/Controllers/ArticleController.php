<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::published();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('kategori')) {
            $query->where('category', $category);
        }

        return view('articles.index', [
            'articles' => $query->paginate(6)->withQueryString(),
            'popular' => Article::published()->orderByDesc('views')->take(4)->get(),
            'categories' => Article::published()->distinct()->pluck('category'),
            'currentCategory' => $category,
            'search' => $search,
            'totalArticles' => Article::count(),
        ]);
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $article->increment('views');

        return view('articles.show', [
            'article' => $article,
            'related' => Article::published()
                ->where('id', '!=', $article->id)
                ->where('category', $article->category)
                ->take(3)->get(),
            'latest' => Article::published()->where('id', '!=', $article->id)->take(4)->get(),
        ]);
    }
}
