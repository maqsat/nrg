<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\City;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CityController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('admin_city_view')) {
            abort('401');
        }

        $cities = City::all();
        return view('city.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Gate::allows('admin_city_create')) {
            abort('401');
        }

        $countries = Country::all();
        return view('city.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_city_create')) {
            abort('401');
        }

        $request->validate([
            'title'            => 'required',
            'country_id'          => 'required',
        ]);


        City::create([
            'title'                 => $request->title,
            'country_id'            => $request->country_id
        ]);


        return redirect('/city');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        if(!Gate::allows('admin_city_view')) {
            abort('401');
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        if(!Gate::allows('admin_city_edit')) {
            abort('401');
        }

        $countries = Country::all();
        return view('city.edit',compact('countries','city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        if(!Gate::allows('admin_city_update')) {
            abort('401');
        }

        $request->validate([
            'title'            => 'required',
            'country_id'          => 'required',
        ]);


        $city->update([
            'title'                 => $request->title,
            'country_id'            => $request->country_id
        ]);


        return redirect('/city');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if(!Gate::allows('admin_city_destroy')) {
            abort('401');
        }

        //
    }
}
