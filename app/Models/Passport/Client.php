<?php
 
namespace App\Models\Passport;
use Illuminate\Support\Facades\Auth;
 
use Laravel\Passport\Client as BaseClient;
use App\Models\UserApplication;
use App\Models\Application;
 
class Client extends BaseClient
{
    /**
     * Determine if the client should skip the authorization prompt.
     */
    public function skipsAuthorization(): bool
    {
        return false;
        $application = Application::where('auth_client_id',request()->client_id)->first();
        if (!is_null($application)) {
            $user_application = UserApplication::where('application_id',$application->id)
                ->where('user_id',Auth::user()->id)->first();
            if (is_null($user_application)) {
                $user_application = new UserApplication([
                    'user_id' => Auth::user()->id,
                    'application_id' => $application->id,
                ]);
                $user_application->save();
            }
            if ($application->public === true || $user_application->approved === true) {
                $user_application->last_login = now();
                $user_application->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

