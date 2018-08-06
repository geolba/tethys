<?php
namespace App\Http\Controllers\Settings;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Project;
use App\Http\Requests\ProjectRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() : View
    {
        $projects = Project::get();
        return view('settings.project.category', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add() : View
    {
        return view('settings.project.add');
    }

    public function store(ProjectRequest $request) : RedirectResponse
    {
        $input = $request->all();
        Project::create($input);
        session()->flash('flash_message', 'You have added 1 project!');
        return redirect()->route('settings.project');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) : View
    {
        $project = Project::findOrFail($id);
        return view('settings.project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, ProjectRequest $request) : RedirectResponse
    {
        $project = Project::findOrFail($id);
        $input = $request->all();
        $project->update($input);
        session()->flash('flash_message', 'You have updated 1 project!');
        return redirect()->route('settings.project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id) : RedirectResponse
    {
        $project = Project::with('documents')->findOrFail($id);
        if ($project->documents->count() > 0) {
            session()->flash(
                'flash_message',
                'You cannot delete this project!'
                    . ' There are '
                    . $project->documents->count()
                    . ' documents in this project!'
            );
            return redirect()->route('settings.project');
        } else {
            $project->delete();
            session()->flash('flash_message', 'You have deleted 1 project!');
            return redirect()->route('settings.project');
        }
    }
}
