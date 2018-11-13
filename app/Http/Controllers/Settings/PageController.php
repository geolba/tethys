<?php

namespace App\Http\Controllers\Settings;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pages\IndexPageRequest;
use App\Http\Requests\Pages\UpdatePageRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\GeneralException;
use App\Events\Pages\PageUpdated;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Page::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexPageRequest $request): View
    {
        return view('settings.page.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $languages = DB::table('languages')
        ->where('active', true)
        ->pluck('part2_t', 'part2_t');

        return view('settings.page.edit', compact('page', 'languages'));
        // ->withPage($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Pages\UpdatePageRequest $request
      * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePageRequest $request, $id)
    {
        $page = Page::findOrFail($id);
        // $this->pages->update($page, $request->except(['_method', '_token']));
        $input = $request
        ->except(['_method', '_token', 'nav-tab', 'en_title', 'en_description', 'de_title', 'de_description']);
         // Making extra fields
        //$input['page_slug'] = str_slug($input['title']);
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['updated_by'] = \Auth::user()->id;

        $article_data = [
            'en' => [
                'title'       => $request->input('en_title'),
                'description' => $request->input('en_description')
            ],
            'de' => [
                'title'       => $request->input('de_title'),
                'description' => $request->input('de_description')
            ],
         ];
         $ergebnis = array_merge($input, $article_data);

        if ($page->update($ergebnis)) {
            event(new PageUpdated($page));

            return redirect()
                ->route('settings.page.index')
                ->with('flash_message', trans('alerts.backend.pages.updated'));
        }
        throw new GeneralException(trans('exceptions.backend.pages.update_error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        //
    }

    public function query()
    {
        return call_user_func(static::MODEL.'::query');
    }
}
