<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Article;

class ArticleController extends Controller
{
    protected $user;
    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    public function index()
    {
        //return Article::all();
        $pos = $this->user->article()->get(['title','subject','created_by']);
        return response()->json($pos->toArray());
    }

    public function show(Article $article)
    {
        return $article;
    }

    public function store(Request $request)
    {
       
        // $val = Validator::make(
        //     $request->all(),[
        //         'title' => 'require|string',
        //         'subject' => 'required|string'
        //     ]
        // );
        // if($val->fails()){
        //     return response()->json([
        //         'status' => false,
        //         'errors'=> $val->errors(),
        //     ], 400);
        // }
        $p = new Article();
        $p->title = $request->title;
        $p->subject = $request->subject;
        if($this->user->article()->save($p)){
            return response()->json([
                'status'=>true,
                'article' =>$p
            ]);
        }
        else{
            return response()->json([
                'status' =>false,
                'message' =>'Oops, something went wrong'
            ]);
        }

        //$article = Article::create($request->all());

        //return response()->json($article, 201);
    }

    public function update(Request $request, Article $article)
    {
        $article = new Article();
        $article->title = $request->title;
        $article->subject = $request->subject;
        if($this->user->article()->save($article)){
            return response()->json([
                'status'=>true,
                'article' =>$article
            ]);
        }
        else{
            return response()->json([
                'status' =>false,
                'message' =>'Oops, something went wrong'
            ]);
        }

        // $article->update($request->all());
        // return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        if($article->delete()){
            return response()->json([
                'status'=>true,
                'article' =>$article
            ]);
        }
        else{
            return response()->json([
                'status' =>false,
                'message' =>'Oops, something went wrong'
            ]);
        }
        // $article->delete();
        // return response()->json(null, 204);
    }

    protected function guard(){
        return Auth::guard();
    }
}
