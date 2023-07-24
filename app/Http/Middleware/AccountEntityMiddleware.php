<?php

namespace App\Http\Middleware;

use App\Helpers\api;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountEntityMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {
        $this->auth = Auth::user();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action
        $idEntity = $request->input("data.idEntity", false);
        $idEntityAccesses = $request->input("data.idEntityAccesses", false);
        $idEntityMap = $request->input("data.idEntityMap", false);
        
        if ($idEntity) {
            $entities = collect($this->auth['account']['entities']);
            if (!is_array($idEntity))
                $idEntity = [$idEntity];
            $idEntity = collect($idEntity);

            if($idEntityAccesses) {
                if(!is_array($idEntityAccesses))
                    $idEntityAccesses = [$idEntityAccesses];
                $idEntity = $idEntity->merge(collect($idEntityAccesses));
            }

            if($idEntityMap) {
                if(!is_array($idEntityMap))
                    $idEntityMap = [$idEntityMap];
                $idEntity = $idEntity->merge(collect($idEntityMap));
            }
            
            $entSelect = $entities->pluck("id");
            if ($idEntity->diff($entSelect)->count()) {
                foreach ($entities as $ent) {
                    $entSelect = $entSelect->merge(collect($ent["childs"])->pluck("id"));
                }
                if ($idEntity->diff($entSelect)->count())
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses untuk entitas ini.");
            }
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
