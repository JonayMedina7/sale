<?php

namespace App\Http\Controllers;

use App\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
    	$tax = Tax::all();
    	return $tax;
    }

    public function store(Request $request)
    {
    	$tax = new Tax();
    	$tax->tax = $request->tax;
    	$tax->save();
    }

    public function update(Request $request)
    {
    	$tax = Tax::findOrFail($request->id);
    	$tax->tax = $request->tax;
    	$tax->save();
    }

    public function searchTax(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tax = Tax::select('id','tax')
        ->orderBy('id', 'desc')->take(1)->get();

        return $tax;
        
    }

}
