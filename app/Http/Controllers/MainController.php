<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        session_start();
        $_SESSION['auth_id'] = \Auth::user()->id;

        $workshops = \Auth::user()->availableWorkshops;
        if(count($workshops) == 1) {
            $workshop = reset($workshops);
            return redirect("toir/index.php?workshop=" . $workshop->ID);
        } else {
            return view('main/index', compact('workshops'));
        }
    }

}
