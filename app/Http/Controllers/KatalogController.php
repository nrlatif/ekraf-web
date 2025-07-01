<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use App\Models\SubSektor;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $subsektors = SubSektor::all();

        $katalogs = Katalog::query();

        // Filter berdasarkan subsektor
        if ($request->has('subsektor') && $request->subsektor != '') {
            $katalogs->where('sub_sektor_id', $request->subsektor);
        }

        // Filter sort
        if ($request->sort == 'termurah') {
            $katalogs->orderBy('harga', 'asc');
        } elseif ($request->sort == 'termahal') {
            $katalogs->orderBy('harga', 'desc');
        } elseif ($request->sort == 'terbaru') {
            $katalogs->orderBy('created_at', 'desc');
        } else {
            $katalogs->orderBy('created_at', 'desc');
        }

        $katalogs = $katalogs->paginate(6)->withQueryString();

        return view('pages.katalog', compact('subsektors', 'katalogs'));
    }
}
