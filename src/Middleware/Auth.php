<?php

namespace App\Middleware;
// TODO: implement middleware if needed

class Auth
{
  public function handle($request, \Closure $next)
  {
    return $next($request);
  }
}