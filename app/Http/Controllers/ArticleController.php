<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $articles= Article::join('categories','articles.category_id','=','categories.id')
            ->select('articles.id','articles.category_id','articles.code','articles.name','categories.name as category_name','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id','desc')->paginate(3);
        }
        else
        {
            $articles= Article::join('categories','articles.category_id','=','categories.id')
            ->select('articles.id','articles.category_id','articles.code','articles.name','categories.name as category_name','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$judgment, 'like', '%'.$search.'%')
            ->orderBy('articles.id','desc')->paginate(3);      
        }
        
        return [
            'pagination' => [
                'total' => $articles->total(),
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'last_page' => $articles->lastPage(),
                'from' => $articles->firstItem(),
                'to' => $articles->lastItem()
            ],
            'articles' => $articles
        ];
    }

    public function listArticleModal(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $articles= Article::join('categories','articles.category_id','=','categories.id')
            ->select('articles.id','articles.category_id','articles.code','articles.name','categories.name as category_name','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id','desc')->paginate(10);
        }
        else
        {
            $articles= Article::join('categories','articles.category_id','=','categories.id')
            ->select('articles.id','articles.category_id','articles.code','articles.name','categories.name as category_name','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$judgment, 'like', '%'.$search.'%')
            ->orderBy('articles.id','desc')->paginate(10);      
        }
        
        return ['articles' => $articles];
    }

    public function listArticleSale(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $articles= Article::join('categories','articles.category_id','=','categories.id')
            ->select('articles.id','articles.category_id','articles.code','articles.name','categories.name as category_name','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->where('articles.stock','>','0')
            ->orderBy('articles.id','desc')->paginate(10);
        }
        else
        {
            $articles= Article::join('categories','articles.category_id','=','categories.id')
            ->select('articles.id','articles.category_id','articles.code','articles.name','categories.name as category_name','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->where('articles.stock','>','0')
            ->where('articles.'.$judgment, 'like', '%'.$search.'%')
            ->orderBy('articles.id','desc')->paginate(10);      
        }
        
        return ['articles' => $articles];
    }

    public function searchArticle(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filter= $request->filter;
        $articles= Article::where('code','=',$filter)
        ->select('id','name')->orderBy('name','asc')->take(1)->get();

        return ['articles'=>$articles];
    }

    public function searchArticleSale(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filter= $request->filter;
        $articles= Article::where('code','=',$filter)
        ->select('id','name','stock','sale_price')
        ->where('stock','>','0')
        ->orderBy('name','asc')->take(1)->get();

        return ['articles'=>$articles];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $article= new Article();
        $article->fill($request->all());
        $article->condition= 1;
        $article->save();
        return $article;
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $article= Article::findOrFail($request->id);
        $article->fill($request->all());
        $article->condition= 1;
        $article->save();
        return $article;
    }

    public function update_condition(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $article= Article::findOrFail($request->id);
        $article->condition = ($article->condition) ? false : true;
        $article->save();

        return $article;
    }

    public function listPDF(Request $request)
    {
        $articles= Article::join('categories','articles.category_id','=','categories.id')
        ->select('articles.id','articles.category_id','articles.code','articles.name',
        'categories.name as category_name','articles.sale_price','articles.stock',
        'articles.description','articles.condition')
        ->orderBy('articles.name','desc')->get();

        $count= Article::count();
        $pdf= \PDF::loadView('PDF.articlesPDF',['articles' => $articles,'count' => $count]);
        return $pdf->download('articles.pdf');
    }
}
