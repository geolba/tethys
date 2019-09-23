<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    public function download($id)
    {
        //$report = $this->report->find($id);
        $file = File::findOrFail($id);
        $file_path = public_path('storage/' . $file->path_name);
        $ext = \Illuminate\Support\Facades\File::extension($file_path);
        return response()->download($file_path, $file->label . "." . $ext, ['Content-Type:' . $file->mime_type]);
        // return response()->download($file_path, $file->label, ['Content-Type:' . $file->mime_type]);
    }
}
