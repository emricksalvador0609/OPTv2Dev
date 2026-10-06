<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\OPTv2User;
use App\Models\OPTv2ProjectionPeriod;
use Illuminate\Support\Facades\Session;

class OPTTokenLogin
{
    public function handle(Request $request, Closure $next)
    {
        // kung logged in na, tuloy lang
        // if (session()->has('user_staff')) {
        //     return $next($request);
        // }


        // kung may token, attempt auto-login
        if ($request->filled('tk')) {
            $request->session()->forget('password_policy_check');


            $token = $request->query('tk');
            $extractid = extractIdFromToken($token);

            $user = OPTv2User::where('id', $extractid)->first();

            Session::forget('staff');        
            Session::forget('user_staff');
            Session::forget('pernr');
            Session::forget('rsm');
            Session::forget('ssm');
            Session::forget('rank');
            Session::forget('aplevel');
            
            if ($user) {
                $request->session()->put('staff', $user->id);
                $request->session()->put('user_staff', $user->USERNAME);
                $request->session()->put('pernr', $user->PERNR);
                $request->session()->put('rsm', $user->RSM);
                $request->session()->put('ssm', $user->SSM);
                $request->session()->put('rank', $user->RANK);
                $request->session()->put('aplevel', $user->APLEVEL);
                $request->session()->put('division', $user->DIVISION);

                $request->session()->save(); // para sure bago mag-next
            }
        }

        // Token login sets the identity after the web middleware ran; enforce the policy again.
        return app(RequirePasswordChange::class)->handle($request, function ($request) use ($next) {
            $segment = $request->segments();
            if (count($segment) > 1 && $segment[1] === 'createprojection') {
                OPTv2ProjectionPeriod::whereDate('ENDDATE', '<', date_now('dateonly'))
                    ->where('STATUS', '!=', 0)->update(['STATUS' => 0]);
            }
            return $next($request);
        });
    }
}
