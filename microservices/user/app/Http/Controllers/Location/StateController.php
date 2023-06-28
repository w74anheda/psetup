<?php
namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StateStoreRequest;
use App\Http\Requests\Location\StateUpdateRequest;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:state.list')->only('index');
        $this->middleware('can:state.create')->only('store');
        $this->middleware('can:state.update')->only('update');
        $this->middleware('can:state.delete')->only('destroy');
        $this->middleware('can:state.delete.all')->only('destroyAll');
    }

    public function index()
    {
        $states = State::all();

        return response(
            $states,
            Response::HTTP_OK
        );
    }

    public function store(StateStoreRequest $request)
    {

        $state = State::create(
            $request->only([ 'name' ])
        );
        return response(
            $state,
            Response::HTTP_CREATED
        );
    }

    public function update(State $state, StateUpdateRequest $request)
    {
        $state->update($request->only([ 'name' ]));

        return response(
            [ 'successfully updated' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function destroy(State $state)
    {
        $state->delete();

        return response(
            [ 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function destroyAll()
    {
        State::query()->delete();

        return response(
            [ 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }
}
