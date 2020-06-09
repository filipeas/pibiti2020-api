<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Analyse;
use Validator;
use App\Http\Resources\Analyse as AnalyseResource;
use App\User;
use Illuminate\Support\Facades\DB;

class AnalyseController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAllAnalyses($user)
    {
        $analyses = Analyse::where('user', $user)->get();

        if (is_null($analyses)) {
            return $this->sendError('Analyses not found.');
        }

        return $this->sendResponse($analyses, 'Analyses retrieved successfully.');
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
            'user' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if (User::where('is_patient', false)->where('id', $request->user)->first() !== null) {
            return $this->sendResponse(['success' => false], 'It is not possible to register an analysis for a specialist.');
        }

        $analyse = Analyse::create($input);

        return $this->sendResponse(new AnalyseResource($analyse), 'Analyse created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $analyse = Analyse::find($id);

        if (is_null($analyse)) {
            return $this->sendError('Analyse not found.');
        }

        return $this->sendResponse(new AnalyseResource($analyse), 'Analyse retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Analyse $analyse)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $analyse->title = $input['title'];
        $analyse->description = $input['description'];
        $analyse->save();

        return $this->sendResponse(new AnalyseResource($analyse), 'Analyse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Analyse $analyse)
    {
        $analyse->delete();

        return $this->sendResponse([], 'Analyse deleted successfully.');
    }
}
