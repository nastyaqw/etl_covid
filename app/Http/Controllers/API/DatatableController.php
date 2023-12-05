<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Datatable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class DatatableController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Datatable::all();
 
        return $this->sendResponse($products->toArray(), 'Datatable retrieved successfully.');
    }

  
    public function create()
    {
        //
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_date'=> 'required',
            'end_date'=> 'required',
            'region'=> 'required',
            'hospitalized'=> 'required',
            'infected'=> 'required',
            'recovered'=> 'required',
            'deaths' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $product = Datatable::create($input);
        return $this->sendResponse($product->toArray(), 'Datatable created successfully.');
    }

   /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $product = Datatable::find($id);
        if (is_null($product)) {
            return $this->sendError('Datatable not found.');
        }
        return $this->sendResponse($product->toArray(), 'Datatable retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $product = Datatable::find($id);
        if (is_null($product)) {
            return $this->sendError('Datatable not found.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_date',
            'end_date',
            'region',
            'hospitalized',
            'infected',
            'recovered',
            'deaths'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        //dd($product);
        if ($request->has('start_date')) {
        $product->start_date = $input['start_date'];}
        if ($request->has('end_date')) {
        $product->end_date = $input['end_date'];}
        if ($request->has('region')) {
        $product->region = $input['region'];}
        if ($request->has('hospitalized')) {
        $product->hospitalized = $input['hospitalized'];}
        if ($request->has('infected')) {
        $product->infected = $input['infected'];}
        if ($request->has('recovered')) {
        $product->recovered = $input['recovered'];}
        if ($request->has('deaths')) {
        $product->deaths = $input['deaths'];}
        $product->save();
        return $this->sendResponse($product->toArray(), 'Datatable updated successfully.');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $product = Datatable::find($id);
        if (is_null($product)) {
            return $this->sendError('Datatable not found.');
        }
        $product->delete();
        return $this->sendResponse($product->toArray(), 'Datatable deleted successfully.');
    }
}
