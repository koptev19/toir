<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\DepartmentFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class DepartmentController
 * @package App\Http\Controllers
 */
class DepartmentController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('departments.index');
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $users = User::whereConnected(true)
            ->get();
        
        return view('departments.create', compact('users'));
    }

    /**
     * @param DepartmentFormRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DepartmentFormRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('departments.index');
    }

    /**
     * @param Request $request
     * @param Department $department
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Department $department)
    {
        $users = User::whereConnected(true)
            ->get();

        return view('departments.edit', compact('department', 'users'));
    }

    /**
     * @param DepartmentFormRequest $request
     * @param Department $department
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DepartmentFormRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('departments.index');
    }


}
