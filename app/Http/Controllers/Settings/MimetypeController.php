<?php
namespace App\Http\Controllers\Settings;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class MimetypeController extends Controller
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
        //$direction = 'asc'; // or desc
        $mimetypes = Config::get('enums.mime_types');
        //$checkeds = $document->licenses->pluck('id')->toArray();
        $checkeds = Config::get('enums.mimetypes_allowed');

        return view('settings.filetype.index', [
            'options' => $mimetypes,
            'checkeds' => $checkeds
            ]);
    }

    public function update(Request $request)
    {
        $mimetypes = $request->input('mimetypes');
        Config::set('enums.mimetypes_allowed', $mimetypes);
        return redirect()->back()->with('success', 'Mimetypes are updated');

      
            
        //     session()->flash('flash_message', 'allowed mimtypes have been updated!');
        //     return redirect()->route('settings.document');
        
        // throw new GeneralException(trans('exceptions.backend.dataset.update_error'));
    }
}
