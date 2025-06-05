<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
    //

    public function index()
    {

        return view("admin.master");
    }

    public function tables()
    {
        return view('admin.table');
    }

     public function static()
    {
        return view('admin.static');
    }

     public function light()
    {
        return view('admin.light');
    }


     public function master()
    {
        return view('master');
    }
}
