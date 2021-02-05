<?php

namespace App\Http\Controllers;

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
        $users = User::with(['workshops', 'departments'])->get();
        $users = new UserResource($users);

        $workshops = ModelsWorkshop::all();
        $workshops = new EquipmentResource($workshops);

        $departments = Department::all();
        $departments = new DepartmentResource($departments);

        return view('users.index', compact('users', 'workshops', 'departments'));
    }


}
