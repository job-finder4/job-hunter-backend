<?php

namespace App\Http\Controllers;

use App\Exceptions\FileSizeMismatchException;
use App\Models\Cv;
use App\Http\Resources\JobadCollection;
use App\Http\Resources\CvCollection;
use App\Models\Jobad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Cv as CvResource;

class CvController extends Controller
{
    public function __construct()
    {
//        $this->middleware('can:download,cv')->only('downloadCv');
//        $this->middleware('can:create,App\Models\Cv')->only('store');
//        $this->middleware('can:update,cv')->only('update');
//        $this->middleware('can:delete,App\Models\Cv')->only('delete');
//        $this->middleware('can:view,cv')->only('downloadCv');
//        $this->middleware('can:viewAny,App\Models\User')->only('index');
    }


    //-------------------danie lnew essssssssssssssss
    public function myCvs()
    {
        return response(new CvCollection(auth()->user()->cvs), 200);
    }
    //----------------------------------------------------

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'title' => 'required',
            'cv_file' => 'required'
        ]);

        $data2 = [];
        $data2['title'] = $data['title'];
        $data2['file'] = $data['cv_file'];

        $cv = $user->createCv($data2);

        return new CvResource($cv);
    }

//-------------daniel edit------------------------------
    public function downloadCv(Cv $cv)
    {
        $path = $cv->path;
        $mime = 'application/pdf';

        $myFile = storage_path('app' . $path);
        $headers = ['Content-Type: application/pdf'];
        $newName = $cv->title . '.pdf';

        return Storage::disk('local')->download($path);

        return response()->download($myFile, $newName, $headers);
    }


}
