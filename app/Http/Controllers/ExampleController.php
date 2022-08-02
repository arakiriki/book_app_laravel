<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Example;

class ExampleController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function example(){
        $examples = Example::all();
        // $examples = Example::find(1);
        // $examples = Example::where('id',1)->get();
        // $examples = Example::wherein('id',[1,3])->get();
        return view('example',[ 'examples' => $examples ]);
    }
}
