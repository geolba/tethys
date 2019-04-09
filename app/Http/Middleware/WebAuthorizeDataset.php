<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Dataset;
use App\Models\User;

class WebAuthorizeDataset
{
    const DELIMITER = '|';

    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next, bool $requiresDatasetAdministrator)
    {
        // if ($this->auth->guest() || !$request->user()->can("Administrator")) {
        //     abort(403);
        // }
        $userId = $this->auth->user()->id;
        $datasetId = $request->route('id');
               
        if ($this->auth->guest() || !$this->isUserDatasetAdmin($userId, $datasetId)) {
            abort(403, "You are not allowed to do this action!");
        }
        return $next($request);
    }

    private function isUserDatasetAdmin($userId, $datasetId)
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($datasetId);
        $user = User::findOrFail($userId);
        if ($dataset->user->id == $user->id) { //} || $user->can("administrator")) {
            return true;
        } else {
            return false;
        }
    }
}
