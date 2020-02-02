<?php 

namespace kamruljpi\admintemplate\controllers;

use App\Http\Controllers\Controller;

class AdminTemplateController extends Controller
{
    public function index()
    {
        return view('admintemplate::admin.index');
    }
}
