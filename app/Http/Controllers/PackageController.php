<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('admin_package_view')) {
            abort('401');
        }

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
        if(!Gate::allows('admin_package_create')) {
            abort('401');
        }

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
        if(!Gate::allows('admin_package_create')) {
            abort('401');
        }

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
        if(!Gate::allows('admin_package_view')) {
            abort('401');
        }

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
        if(!Gate::allows('admin_package_edit')) {
            abort('401');
        }

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
        if(!Gate::allows('admin_package_edit')) {
            abort('401');
        }

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
        if(!Gate::allows('admin_package_destroy')) {
            abort('401');
        }

        //
    }
}
