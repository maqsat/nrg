<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|Response|View
     */
    public function index()
    {
        if(!Gate::allows('admin_role_view')) {
            abort('401');
        }

        $role = Role::all();
        return view('role.index', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|Response|View
     */
    public function create()
    {
        if(!Gate::allows('admin_role_create')) {
            abort('401');
        }

        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_role_create')) {
            abort('401');
        }

        return redirect('/role')->with('status', 'Успешно изменено');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        if(!Gate::allows('admin_role_view')) {
            abort('401');
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Factory|Application|Response|View
     */
    public function edit($id)
    {
        if(!Gate::allows('admin_role_edit')) {
            abort('401');
        }

        $role = Role::find($id);
        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(!Gate::allows('admin_role_edit')) {
            abort('401');
        }

        $request->validate([
            "title"             => 'required',
            "description"       => 'required',
        ]);

        Role::whereId($id)->update([
            "title"         => $request->title,
            "description"   => $request->description,
        ]);

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        if(!Gate::allows('admin_role_destroy')) {
            abort('401');
        }

        //
    }
}
