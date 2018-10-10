<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Language;
use App\Http\Requests\LicenseRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;

class LicenseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() : View
    {
        $direction = 'asc'; // or desc
        $licenses = License::orderBy('sort_order', $direction)->get();
        return view('settings.license.license', compact('licenses'));
    }

    public function edit($id): View
    {
        $license = License::findOrFail($id);
        //$languages = Language::where('active', true)->pluck('part2_t');
        $languages = DB::table('languages')
        ->where('active', true)
        ->pluck('part2_t', 'part2_t');

        return view('settings.license.edit', compact('license', 'languages'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validatedData = $this->validate($request, [
            'desc_text' =>  'max:4000',
            'language' =>  'max:3',
            'link_licence' => 'required|url:max:255',
            'link_logo' => 'url|max:255',
            'mime_type' =>  'max:30',
            'name_long' =>  'required|min:5|max:255',
            'sort_order' => 'required|integer',
            'active' => 'required|boolean',
            'pod_allowed' => 'required|boolean',
        ]);

        $license = License::findOrFail($id);
        $input = $request->all();
        if ($license->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('settings.license')
                ->with('flash_message', 'You have updated the license!');
        }
        throw new GeneralException(trans('exceptions.backend.licenses.update_error'));
        // $license->update($input);
        // session()->flash('flash_message', 'You have updated the license!');
        // return redirect()->route('settings.license');
    }
}
