<?php

namespace App\Http\Controllers;

use App\Exceptions\FileSizeMismatchException;
use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Cv as CvResource;

class CvController extends Controller
{
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

    public function downloadCv(Request $request)
    {
        $cv = Cv::findOrFail($request->cv_id);

        $path = $cv->path;
        $mime = 'application/pdf';

        return Storage::disk('local')->download($path, 'daniel.pdf', [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; ' . $path
        ]);
    }

}
