<?php

namespace App\Http\Middleware;

use App\Models\Registration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestoreMemberSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If already in session, skip
        if ($request->session()->has('registered_user_name')) {
            return $next($request);
        }

        // Try to restore from cookies
        $registeredEmail = trim((string) $request->cookie('mubs_registered_email', ''));
        $registeredName = trim((string) $request->cookie('mubs_registered_name', ''));

        if ($registeredEmail !== '') {
            // Look up member by email
            $member = Registration::where('email', $registeredEmail)->orderByDesc('id')->first();
            
            if ($member) {
                // Restore session
                $request->session()->put('registered_user_name', $member->full_name);
                return $next($request);
            }
        }

        if ($registeredName !== '') {
            // Look up member by name
            $member = Registration::where('full_name', $registeredName)->orderByDesc('id')->first();
            
            if ($member) {
                // Restore session
                $request->session()->put('registered_user_name', $member->full_name);
                // Also update email in session if available
                if ($member->email) {
                    $request->session()->put('mubs_registered_email', $member->email);
                }
                return $next($request);
            }
        }

        return $next($request);
    }
}
