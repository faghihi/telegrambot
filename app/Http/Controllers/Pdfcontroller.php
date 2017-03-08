<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class Pdfcontroller extends Controller
{
    public function index()
    {
        $html = '<h1>Hello World</h1>';

        PDF::SetTitle('Hello World');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output('hello_world.pdf');
    }
}
