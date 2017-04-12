<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Http\Request;
use PDF;
use TCPDF_FONTS;

class Pdfcontroller extends Controller
{
    public function index($id)
    {
//        $data=Data::where('chat_id',$id)->first();
//        $data=$data->data;
        $html = '<h1>سلام</h1>';
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        $fontname = TCPDF_FONTS::addTTFfont(public_path().'/fonts/titrbold.ttf', 'TrueTypeUnicode', '', 120);
        PDF::SetFont($fontname, '', 12, '', false);

// set some language-dependent strings (optional)
        PDF::setLanguageArray($lg);
        PDF::SetTitle('Hello World');
        PDF::AddPage();
        PDF::writeHTML(view('pdf', ['salam'=>'تیتر'])->render());
        $fontname = TCPDF_FONTS::addTTFfont(public_path().'/fonts/nazanin.ttf', 'TrueTypeUnicode', '', 120);
        PDF::SetFont($fontname, '', 12, '', false);
        PDF::writeHTML(view('pdf', ['salam'=>'نازنین'])->render());
        $fontname = TCPDF_FONTS::addTTFfont(public_path().'/fonts/mitra.ttf', 'TrueTypeUnicode', '', 120);
        PDF::SetFont($fontname, '', 12, '', false);
        PDF::writeHTML(view('pdf', ['salam'=>'میترا'])->render());
        $pages=PDF::getNumPages	();
        $fontname = TCPDF_FONTS::addTTFfont(public_path().'/fonts/roya.ttf', 'TrueTypeUnicode', '', 120);
        PDF::SetFont($fontname, '', 12, '', false);
        PDF::writeHTML(view('pdf', ['salam'=>'رویا'])->render());

//        PDF::writeHTML(view('pdf', ['salam'=>$pages])->render());

//        PDF::output('salam.pdf');
//        $filename = public_path().'/uploads/salam.pdf';
        PDF::output('resume.pdf', 'I');
    }
}
