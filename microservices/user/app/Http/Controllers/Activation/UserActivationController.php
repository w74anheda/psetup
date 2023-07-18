<?php
namespace App\Http\Controllers\Activation;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserActivationRequest;
use App\Models\User;
use Illuminate\Http\Response;

class UserActivationController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:user.activation')->only('activation');
    }

    public function __invoke(User $user, UserActivationRequest $request)
    {
        $user->update([ 'is_active' => $request->status, 'activated_at' => now() ]);
        return response(
            [ 'successfully applied' ],
            Response::HTTP_ACCEPTED
        );
    }
}
