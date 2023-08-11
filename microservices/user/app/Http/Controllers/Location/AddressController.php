<?php
namespace App\Http\Controllers\Location;


use App\Http\Controllers\Controller;
use App\Http\Requests\Location\AddressStoreRequest;
use App\Http\Requests\Location\AddressUpdateRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:its-own-address,address')->only([ 'destroy', 'update' ]);
    }

    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->with([ 'city.state' ])->get();

        return new AddressCollection($addresses);

    }

    public function store(AddressStoreRequest $request)
    {
        $address = $request->user()->addresses()->create(
            $request->only([
                'city_id',
                'full_address',
                'house_number',
                'unit_number',
                'postalcode',
                'latitude',
                'longitude',
            ])
        );

        $address->load('city.state');
        return new AddressResource($address);
    }

    public function update(Address $address, AddressUpdateRequest $request)
    {
        $address->update($request->only([
            'city_id',
            'full_address',
            'house_number',
            'unit_number',
            'postalcode',
            'latitude',
            'longitude',
        ]));

        $address->load('city.state');
        $address->unsetRelation('user');
        return new AddressResource($address);
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }
}
