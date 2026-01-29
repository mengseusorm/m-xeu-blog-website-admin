<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class ActivityLoggerService
{
 public function logAuthentication(string $event, User $user, array $properties = []): void
    {
        try {
            $defaultProperties = [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => session()->getId(),
            ];
            Log::info("Logging authentication activity: ", [
                'event' => $event,
                'user_id' => $user->id,
                'properties' => $defaultProperties,
            ]);

            Log::info("User: ", [$user]); 

            Log::info("Finish logging authentication activity");
        } catch (\Exception $e) {
            // Log error but don't break the authentication flow
            Log::error('Failed to log authentication activity', [
                'event' => $event,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

}
