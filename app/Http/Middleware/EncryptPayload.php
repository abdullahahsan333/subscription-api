<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EncryptPayload
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isJson() && $request->getContent()) {
            try {
                $decrypted = Crypt::decryptString($request->getContent());
                $data = json_decode($decrypted, true);
                $request->replace($data ?? []);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Invalid encrypted payload'], 400);
            }
        }

        $response = $next($request);
        if ($response->getContent()) {
            $response->setContent(Crypt::encryptString($response->getContent()));
        }
        return $response;
    }
}
