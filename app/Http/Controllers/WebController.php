<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class WebController extends Controller
{

    public function welcome()
    {
        return redirect('index.html');
    }

    public function about()
    {
        return view('page.about');
    }

    public function products()
    {
        return view('page.products');
    }

    public function cert()
    {
        return view('page.cert');
    }

    public function faq()
    {
        $faq=Faq::where('is_admin','0')->get();
        return view('page.faq',compact('faq'));
    }
}
