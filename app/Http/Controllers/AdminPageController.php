<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPageController extends Controller
{
  public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function wallet($userId)
    {
        return view('admin.wallet');
    }

    public function transactions()
    {
        return view('admin.transactions');
    }
}
