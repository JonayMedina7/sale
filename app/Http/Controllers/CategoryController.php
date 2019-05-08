<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar=='') {
            $categories = Category::orderBy('id', 'desc')->paginate(4);
        } else {
            $categories = Category::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(4);
        }

        
        return [
            'pagination' => [
                'total'         => $categories->total(),
                'current_page'  => $categories->currentPage(),
                'per_page'      => $categories->perPage(),
                'last_page'     => $categories->lastPage(),
                'from'          => $categories->firstItem(),
                'to'            => $categories->lastItem(),
            ],
            'categorias' => $categories
        ];
    }

    public function categorySelect(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categories = Category::where('condition', '=', '1' )
        ->select('id','name')->orderBy('name', 'asc')->get();

        return ['categories' => $categories];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->condition = '1';
        $category->save();

    }

  
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->condition = '1';
        $category->save();
    }

    public function desactive(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
        
        $category->condition = '0';
        $category->save();
    }

    public function active(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
        
        $category->condition = '1';
        $category->save();
    }


}
