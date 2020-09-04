<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{

    public function welcome()
    {
        return redirect('index.html');
    }

    public function reviews()
    {
        $this->lang();

        $user = Auth::user();
        $reviews = Review::with('user','user_likes')->where('approved', '=', 1)->get();
        return view('review.reviews', compact('reviews', 'user'));
    }

    private function lang() {
        if(!isset($_GET['lang']))
            $_GET['lang'] = 'ru';

        if (! in_array($_GET['lang'], ['en', 'ru', 'kz'])) {
            abort(400);
        }

        App::setLocale($_GET['lang']);
    }

    public function review($id)
    {
        $this->lang();

        $user = Auth::user();
        $review = Review::with('user','user_likes')->where('approved', '=', 1)->where('id', $id)->first();

        if(!$review)
            return response()->redirectTo('reviews');

        return view('review.review', compact('review', 'user'));
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
