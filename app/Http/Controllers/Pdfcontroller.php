<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class Pdfcontroller extends Controller
{
    public function index()
    {
        $html = '<h1>سلام</h1>';
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        PDF::SetFont('dejavusans', '', 12);

// set some language-dependent strings (optional)
        PDF::setLanguageArray($lg);
        PDF::SetTitle('Hello World');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output('hello_world.pdf');
    }
}
