<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\MimeType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class MimetypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        // $mimetypes = Config::get('enums.mime_types');
        // $checkeds = Config::get('enums.mimetypes_allowed');
        $direction = 'asc'; // or desc
        $mimetypes = MimeType::orderBy('name', $direction)->get();

        return view('settings.filetype.index', [
            'mimetypes' => $mimetypes,
            // 'checkeds' => $checkeds
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

    /**
     * deactivate mimetype
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function down($id): RedirectResponse
    {
        $mimetype = MimeType::findOrFail($id);
        $mimetype->update(['enabled' => 0]);
        session()->flash('flash_message', 'mimetype has been deactivated!');
        return redirect()->route('settings.mimetype.index');
    }

    /**
     * activate mimetype.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function up($id): RedirectResponse
    {
        $mimetype = MimeType::findOrFail($id);
        $mimetype->update(['enabled' => 1]);
        session()->flash('flash_message', 'mimetype has been activated!');
        return redirect()->route('settings.mimetype.index');
    }
}
