<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

/**
 * Class ToirController
 * @package App\Http\Controllers
 */
class ToirController extends Controller
{
    /**
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, Workshop $workshop)
    {
        return redirect('/toir/index.php?workshop=' . $workshop->id);
    }


}
