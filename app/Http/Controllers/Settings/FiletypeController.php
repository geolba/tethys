<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;

class FiletypeController extends Controller
{
    // public function download($id)
    // {
    //     //$report = $this->report->find($id);
    //     $file = File::findOrFail($id);
    //     $file_path = public_path('storage/' . $file->path_name);
    //     return response()->download($file_path, $file->label, ['Content-Type:' . $file->mime_type]);
    // }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $direction = 'asc'; // or desc
        $fileextensions = Config::get('enums.filetypes_allowed');
        return view('settings.filetype.index', compact('fileextensions'));
    }
}
