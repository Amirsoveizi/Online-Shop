<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Http\Resources\AddressResource;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AddressResource::collection(Address::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = $request->user()->addresses()->create(
            $request->validated()
        );

        return (new AddressResource($address))->
            response()->
            setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return new AddressResource($address->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $address->update(
            $request->validated()
        );

        return new AddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return response()->noContent();
    }
}
