<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return ["data"=>'Index data fetched!'];
    }

    public function store()
    {
        return ["data" => "Data has been stored!"];
    }

    public function show(string $id)
    {
        return ["data" => "User Of Id: " . $id . " has been fetched!"];
    }
}
