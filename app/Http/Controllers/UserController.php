<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\UserResource;
use App\Models\Department;
use App\Models\User;
use App\Models\Workshop as ModelsWorkshop;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $users = User::with(['workshops', 'departments'])
            ->where('id', '!=', \Auth::user()->id)
            ->get();
        $users = new UserResource($users);

        $workshops = ModelsWorkshop::all();
        $workshops = new EquipmentResource($workshops);

        $departments = Department::all();
        $departments = new DepartmentResource($departments);

        return view('users.index', compact('users', 'workshops', 'departments'));
    }

    public function store(UserFormRequest $request)
    {
        $users = User::with(['workshops', 'departments'])
            ->where('id', '!=', \Auth::user()->id)
            ->get();

        foreach($users as $user) {
            $user->connected =  in_array($user->id, $request->connected ?? []);
            $user->is_admin =  in_array($user->id, $request->is_admin ?? []);
            $user->all_workshops =  in_array($user->id, $request->all_workshops ?? []);

            $user->save();

            $user->departments()->sync($request->departments[$user->id] ?? []);
            $user->workshops()->sync($request->workshops[$user->id] ?? []);
        }

        return redirect()
            ->route('users.index')
            ->with('users_message', 'Пользователи обновлены');
    }


}
