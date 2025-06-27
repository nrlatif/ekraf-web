<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function show($slug){
        $artikels = Artikel::where('slug', $slug)->first();
        $newests =Artikel::orderBy('created_at','desc')->get()->take(4);
        return view('pages.artikel.show', compact('artikels', 'newests'));
    }
}
