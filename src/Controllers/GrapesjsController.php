<?php

namespace Bzzix\PageBuilder\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Lamoud\LaravelNelcXapiIntegration\XapiIntegration;

class GrapesjsController extends Controller
{

    public function __construct()
    {
    }

    public function getIndex(Request $request)
    {

        return view('bzzix-pagebuilder::index');
    }

    public function postIndex(Request $request)
    {
    }

    public function getUpdate()
    {
        return view('bzzix-pagebuilder::update');
    }

    public function postUpdate(Request $request, $slug)
    {

    }

}