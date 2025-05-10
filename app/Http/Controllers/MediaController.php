<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MediaUploadRequest;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function upload(MediaUploadRequest $request)
    {
        // Assuming Cloudinary is configured as a disk in config/filesystems.php
        $file = $request->file('file');
        $path = Storage::disk('cloudinary')->putFile('media', $file);
        $url = Storage::disk('cloudinary')->url($path);
        return response()->json(['url' => $url], 201);
    }
}