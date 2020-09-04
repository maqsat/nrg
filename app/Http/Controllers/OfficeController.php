<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Office;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('admin_office_view')) {
            abort('401');
        }

        $office = Office::all();
        return view('office.index',compact('office'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Gate::allows('admin_office_create')) {
            abort('401');
        }

        $users = User::whereStatus(1)->get();
        return view('office.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_office_create')) {
            abort('401');
        }

        $request->validate([
            'title'            => 'required',
            'city_id'          => 'required',
            'address'          => 'required',
            'user_id'          => 'required',
        ]);

        $user = User::find($request->user_id);

        if(is_null($user->is_office_lider)){

            $office = Office::create([
                'title'            => $request->title,
                'city_id'          => $request->city_id,
                'address'          => $request->address,
            ]);

            $user->is_office_lider = $office->id;
            $user->save();

            return redirect('/office');
        }
        else return redirect()->back()->with('status', 'У Топ лидера уже есть офис');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        if(!Gate::allows('admin_office_view')) {
            abort('401');
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        if(!Gate::allows('admin_office_edit')) {
            abort('401');
        }

        $lider = User::where('is_office_lider',$office->id)->first();
        if(!is_null($lider)){
            $user_id = $lider->id;
        }
        else    $user_id = 0;

        $users = User::whereStatus(1)->get();
        return view('office.edit',compact('office','user_id','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Office $office)
    {
        if(!Gate::allows('admin_office_edit')) {
            abort('401');
        }

        $request->validate([
            'title'            => 'required',
            'city_id'          => 'required',
            'address'          => 'required',
            'user_id'          => 'required',
            'old_user_id'      => 'required',
        ]);


        if($request->user_id != $request->old_user_id){
            $user = User::find($request->user_id);

            if(is_null($user->is_office_lider)){

                $office->update([
                    'title'            => $request->title,
                    'city_id'          => $request->city_id,
                    'address'          => $request->address,
                ]);

                $user->is_office_lider = $office->id;
                $user->save();

                $old_user_id = User::find($request->old_user_id);
                $old_user_id->is_office_lider = null;
                $old_user_id->save();

                return redirect('/office');
            }
            else return redirect()->back()->with('status', 'У Топ лидера уже есть офис');
        }
        else{
            $office = $office->update([
                'title'            => $request->title,
                'city_id'          => $request->city_id,
                'address'          => $request->address,
            ]);
            return redirect('/office');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function destroy(Office $office)
    {
        if(!Gate::allows('admin_office_destroy')) {
            abort('401');
        }

        //
    }
}
