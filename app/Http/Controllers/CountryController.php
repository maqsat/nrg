<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CountryController extends Controller
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
        if(!Gate::allows('admin_country_view')) {
            abort('401');
        }

        $countries = Country::all();
        return view('country.index',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Gate::allows('admin_country_create')) {
            abort('401');
        }

        return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_country_create')) {
            abort('401');
        }

        $request->validate([
            'title'            => 'required',
        ]);

        Country::create([
            'title'            => $request->title
        ]);

        return redirect('/country');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        if(!Gate::allows('admin_country_view')) {
            abort('401');
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        if(!Gate::allows('admin_country_edit')) {
            abort('401');
        }

        return view('country.edit',compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        if(!Gate::allows('admin_country_edit')) {
            abort('401');
        }

        $request->validate([
            'title'            => 'required',
        ]);

        $country->title = $request->title;
        $country->save();

        return redirect('/country');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if(!Gate::allows('admin_country_destroy')) {
            abort('401');
        }

        //
    }
}
