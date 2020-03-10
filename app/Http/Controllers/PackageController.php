<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $package = Package::all();
        return view('package.index', compact('package'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('package.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title"     => 'required',
            "cost"      => 'required',
            "pv"        => 'required',
            "goods"     => 'required',
            "income"    => 'required',
            "rank"      => 'required',
            "status"    => 'required',
        ]);

        Package::create([
            "title"     => $request->title,
            "cost"      => $request->cost,
            "pv"        => $request->pv,
            "goods"     => $request->goods,
            "income"    => $request->income,
            "rank"      => $request->rank,
            "status"    => $request->status,
        ]);

        return redirect('/office')->with('status', 'Успешно изменено');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = Package::find($id);
        return view('package.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "title"     => 'required',
            "cost"      => 'required',
            "pv"        => 'required',
            "goods"     => 'required',
            "income"    => 'required',
            "rank"      => 'required',
            "status"    => 'required',
        ]);

        Package::whereId($id)->update([
            "title"     => $request->title,
            "cost"      => $request->cost,
            "pv"        => $request->pv,
            "goods"     => $request->goods,
            "income"    => $request->income,
            "rank"      => $request->rank,
            "status"    => $request->status,
        ]);

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
