<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        userHasRole(['superAdmin']);
        $model = Role::all();
        return view('roles.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        userHasRole(['superAdmin']);
        $model = Permission::all();
        return view('roles.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if ($request->expectsJson()) {

            $role        = Role::create($request->except('permission'));

            $permissions = $request->input('permission') ? $request->input('permission') : [];

             return response()->json($role->givePermissionTo($permissions));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Role $role )
    {
        userHasRole(['superAdmin']);
        $model=$role;
        $permission = Permission::all();
        return view('roles.edit',compact('model','permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Role $role)
    {
        $role->update($request->except('permission'));
        $role->syncPermissions($request->input('permission'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Role $role )
    {
        $role->permissions()->detach();
        $role->users()->delete();
        $role->delete();
    }

    public function massDestroy(Request $request){
        if ($ids=$request->input('ids')) {
            $roles = Role::query()->whereIn('id',$ids)->get();
            foreach ($roles as $role) {
                $role->permissions()->detach();
                $role->delete();
                $role->users()->delete();
            }
        }

    }
}
