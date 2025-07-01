<?php

namespace App\Http\Controllers;
use App\Models\Artikel;
use App\Models\Author;
use App\Models\Banner;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        
        $banners = Banner::all();
        $featureds = Artikel::where('is_featured', true)->get();
        $artikels = Artikel::orderBy('created_at', 'desc')->get()->take(4);
        $authors = Author::all()->take(5);
        return view('pages.berita',compact('banners', 'featureds', 'artikels', 'authors'));
    }
}
