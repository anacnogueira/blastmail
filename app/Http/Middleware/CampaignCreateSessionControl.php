<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CampaignCreateSessionControl
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!str($request->header('referer'))->contains($request->route()->compiled->getStaticPrefix())) {
            session()->forget('campaigns::create');
        } else {
            $session = session()->get('campaigns::create');
            $tab = $request->route('tab');

            if (filled($tab) && (blank(data_get($session, 'name')))) {
                return redirect()->route('campaigns.create');

            }

            if ($tab == 'schedule' && (blank(data_get($session, 'body')))) {
                return redirect()->route('campaigns.create', 'template');

            }

        }
        return $next($request);
    }
}
