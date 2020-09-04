<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Counter;
use App\Models\Notification;
use App\Models\Office;
use App\Models\Processing;
use App\Models\Product;
use App\Models\Review;
use DB;
use App\User;
use App\Models\Package;
use App\Models\Status;
use App\Models\UserProgram;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }


    public function notCashBonuses(Request $request)
    {
        if(!Gate::allows('not_cash_bonuses_access')) {
            abort('401');
        }

        $not_cash_bonuses = DB::table('not_cash_bonuses')->where('type',$request->type)->get();
        return  view('admin.travel', compact('not_cash_bonuses'));
    }

    public function notCashBonusesAnswer($not_cash_bonuses_id, $status)
    {
        if(!Gate::allows('not_cash_bonuses_access')) {
            abort('401');
        }

        $bonus_status = DB::table('not_cash_bonuses')
            ->where('id', $not_cash_bonuses_id)
            ->update(['status' => $status]);

        return redirect()->back();
    }

    public function offices_bonus()
    {
        if(!Gate::allows('offices_bonus_access')) {
            abort('401');
        }

        $offices = Office::all();
        $data = [];
        $sum = 0;

        foreach ($offices as $key => $item){
            $data[$key][] = $item;
            $users = User::where('office_id',$item->id)->where('status',1)->where('package_id','!=',1)->get();
            $ids = [];
            foreach ($users as $item){
                $ids[] = $item->id;
            }
            $sum = Counter::whereIn('user_id',[$ids])->sum('sum');
            $data[$key][] = $sum;
        }

        return view('admin.offices',compact('data'));
    }

    public function reviews()
    {
        if(!Gate::allows('admin_reviews_access')) {
            abort('401');
        }

        $reviews = Review::with('user')->orderBy('approved', 'ASC')->paginate(30);

        return view('review.index',compact('reviews'));
    }

    public function reviewEdit($id)
    {
        if(!Gate::allows('admin_reviews_edit')) {
            abort('401');
        }

        $review = Review::find($id);
        $user = $review->user()->first();
        $products = Product::all();

        return view('review.edit', compact('review', 'user', 'products'));
    }

    public function reviewAdd($id)
    {
        if(!Gate::allows('admin_reviews_add')) {
            abort('401');
        }

        $review = null;
        $user = User::find($id);
        $products = Product::all();
        return view('review.edit', compact('review', 'user', 'products'));
    }

    public function reviewStatus($id, $status)
    {
        if(!Gate::allows('admin_reviews_status')) {
            abort('401');
        }

        $review = Review::find($id);

        if($status === 'delete') {
            $review->user_likes()->detach();
            $review->delete();
        } else {
            if(Auth::user()->admin !== 1) {
                return redirect()->back();
            }
            $review->approved = $status === 'approved' ? 1 : 0;
            $review->save();
        }

        return redirect()->back()->with('status', 'Действие выполнено успешно!');;
    }

    public function comments()
    {
        if(!Gate::allows('admin_comments_access')) {
            abort('401');
        }

        $comments = Comment::with('user', 'review', 'comment')->orderBy('created_at', 'DESC')->paginate(30);
        return view('review.comments',compact('comments'));
    }

    public function commentStatus($id, $status)
    {
        if(!Gate::allows('admin_comments_status')) {
            abort('401');
        }
        $comment = Comment::find($id);

        $comment->user_likes()->detach();
        $comment->delete();

        return redirect()->back()->with('status', 'Действие выполнено успешно!');;
    }

    public function notifications()
    {
        if(!Gate::allows('admin_notifications_access')) {
            abort('401');
        }

        $all = Notification::where('type', 'LIKE', 'admin_%')->orderBy('created_at', 'DESC')->paginate(30);

        return view('admin.notifications', compact('all'));
    }

    public function progress(Request$request)
    {
        if(!Gate::allows('admin_progress_access')) {
            abort('401');
        }

        $from = '';
        $to = '';
        if(isset($request->from)){
            $list = User::where('inviter_id','!=',0)
                ->whereDate('created_at', '>=',$request->from)
                ->groupBy('inviter_id')
                ->select(['inviter_id', DB::raw('count(*) as count')])
                ->orderBy('count','desc')
                ->get();
            $from = $request->from;
            return view('profile.progress',compact('list','to','from'));
        }

        if(isset($request->to)){
            $list = User::where('inviter_id','!=',0)
                ->whereDate('created_at', '<=',$request->to)
                ->groupBy('inviter_id')
                ->select(['inviter_id', DB::raw('count(*) as count')])
                ->orderBy('count','desc')
                ->get();
            $to = $request->to;
            return view('profile.progress',compact('list','to','from'));
        }

        if(isset($request->from) && isset($request->to)){
            $list = User::where('inviter_id','!=',0)
                ->whereDate('created_at', '>=',$request->from)
                ->whereDate('created_at', '<=',$request->to)
                ->groupBy('inviter_id')
                ->select(['inviter_id', DB::raw('count(*) as count')])
                ->orderBy('count','desc')
                ->get();
            $from = $request->from;
            $to = $request->to;
            return view('profile.progress',compact('list','to','from'));
        }

        $list = User::where('inviter_id','!=',0)
            ->groupBy('inviter_id')
            ->select(['inviter_id', DB::raw('count(*) as count')])
            ->orderBy('count','desc')
            ->get();

        return view('admin.progress',compact('list','to','from'));

    }

    public function programs()
    {
        if(!Gate::allows('admin_programs_access')) {
            abort('401');
        }

        $package = Package::where('status',1)->get();
        $user_package = Package::find(Auth::user()->package_id);
        return  view('admin.packages', compact('package','user_package'));
    }
}
