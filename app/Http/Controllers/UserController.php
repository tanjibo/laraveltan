<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {

        userHasAccess(['users_show']);
        return view('user.index');
    }


    public function indexApi( Request $request )
    {
        if (request()->expectsJson()) {
            $model = User::query();

            $request->order == 'ascending' ? $model->orderBy($request->columns) : $model->orderByDesc($request->columns);

            if ($search = $request->search) {
                $model->orWhere('id', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%")->orWhere('nickname', 'like', "%{$search}%");
            }

            $model = $model->paginate($request->prePage ?: 10);
            return response()->json($model);


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {


        return view('user.userInfo');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( User $user )
    {

//        $user=User::query()->findOrFail($user);

        if (\request()->expectsJson()) {

            return response()->json($user);
        }
        $model = $user;
        return view('user.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, User $user )
    {
        if ($request->expectsJson()) {

            return response()->json($user->updateUser($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( User $user )
    {
        return response()->json($user->delete());
    }

    /**
     * @param Request $request
     * 分配用户权限
     */
    public function givePermissions( Request $request, User $user )
    {
        userHasRole(['superAdmin']);
        if (request()->expectsJson()) {

            return response()->json($user->syncRoles($request->roles));
        }

        $roles = Role::all();


        $hasRole = $user->roles()->select('name')->pluck('name');


        return view('user.give_permissions', compact('user', 'roles', 'hasRole'));
    }

    /**
     * 用户的全部订单
     */
    public function userAllOrder( Request $request, User $user )
    {
        if ($request->expectsJson()) {

            return response()->json($user->experience_bookings()->with('experience_booking_rooms')->get()->merge($user->tearoom_booking()->with('tearoom')->get()));
        }
        return view('user.user_all_order',compact('user'));
    }
}
