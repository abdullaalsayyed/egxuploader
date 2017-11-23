<?php

namespace App\Http\Controllers;

use App\FileUploader;
use Illuminate\Http\Request;

class SampleUploadController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $uploader = new FileUploader($request);
        $uploader->setFeatureProperties('0* Feature Name here', ['1*/2*', 'like => image/png'], '3* POST or HEAD or ...etc', '4* Optional Sub-folder');
        $link = $uploader->upload();

        if(is_array($link)) return $link[0];
        return response()->json(['link' => $link], 200, ['status' => 'uploaded-successfully']);
    }
}