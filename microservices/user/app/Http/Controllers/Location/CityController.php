<?php
namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\CityStoreRequest;
use App\Http\Requests\Location\CityUpdateRequest;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:city.create')->only('store');
        $this->middleware('can:city.update')->only('update');
        $this->middleware('can:city.delete')->only('destroy');
        $this->middleware('can:city.delete.all')->only('destroyAll');
    }

    public function index()
    {
        return new CityCollection(
            City::paginate(20)
        );
    }

    public function store(CityStoreRequest $request)
    {
        return new CityResource(
            City::create(
                $request->only([ 'state_id', 'name' ])
            )
        );
    }

    public function update(City $city, CityUpdateRequest $request)
    {
        $city->update($request->only([ 'state_id', 'name' ]));
        $city->load('state');
        return new CityResource($city);
    }

    public function destroy(City $city)
    {
        $city->delete();

        return response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function destroyAll()
    {
        City::query()->delete();

        return response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }
}
