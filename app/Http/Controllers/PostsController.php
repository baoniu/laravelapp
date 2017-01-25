<?php

namespace App\Http\Controllers;

use App\Discussion;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $discussions = Discussion::all();
        return view('form.index', compact('discussions'));
    }
}
