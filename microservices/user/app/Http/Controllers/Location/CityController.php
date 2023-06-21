<?php
namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\CityStoreRequest;
use App\Http\Requests\Location\CityUpdateRequest;
use App\Models\City;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:city.list')->only('index');
        $this->middleware('can:city.create')->only('store');
        $this->middleware('can:city.update')->only('update');
        $this->middleware('can:city.delete')->only('destroy');
        $this->middleware('can:city.delete.all')->only('destroyAll');
    }

    public function index()
    {
        $city = City::all();

        return response(
            $city,
            Response::HTTP_OK
        );
    }

    public function store(CityStoreRequest $request)
    {

        $state = City::create(
            $request->only([ 'state_id', 'name' ])
        );
        return response(
            $state,
            Response::HTTP_CREATED
        );
    }

    public function update(City $city, CityUpdateRequest $request)
    {
        $city->update($request->only([ 'state_id', 'name' ]));

        return response(
            [ 'successfully updated' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function destroy(City $city)
    {
        $city->delete();

        return response(
            [ 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function destroyAll()
    {
        City::query()->delete();

        return response(
            [ 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }
}
