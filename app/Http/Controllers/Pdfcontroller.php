<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Http\Request;
use PDF;

class Pdfcontroller extends Controller
{
    public function index()
    {
//        $data=Data::where('chat_id',$id)->first();
//        $data=$data->data;
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
        PDF::writeHTML(view('pdf', ['salam'=>'اینم'])->render());
//        PDF::output('salam.pdf');
//        $filename = public_path().'/uploads/salam.pdf';
        $filename = 'uploads/salam.pdf';
        PDF::output($_SERVER['DOCUMENT_ROOT'].'/'.$filename, 'F');
    }
}
