<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Education;

class EducationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->sendResponse(Education::all(), 'Etudes envoyées.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération des Etudes', $th->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails())
                return $this->sendError('Les informations données sont incompletes.', $validator->errors(), 400);

            $education = Education::create([
                'type' => $request->type,
                'name' => $request->name,
                'start' => $request->start,
                'end' => $request->end,
                'description' => $request->description,
            ]);
            return $this->sendResponse($education->id, 'Etude enregistrée.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur d\'enregistrement de l\'Etude.', $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->sendResponse(Education::where('id', $id)->first(), 'Etude envoyée.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération des Etudes', $th->getMessage(), 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return $this->sendResponse(Education::where('id', $id)->first(), 'Etude à éditer envoyée.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération des Etudes', $th->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails())
                return $this->sendError('Les informations données sont incompletes.', $validator->errors(), 400);

            $to_mod = Education::where('id', $id)->first();

            $to_mod->type = $request->type;
            $to_mod->name = $request->name;
            $to_mod->start = $request->start;
            $to_mod->end = $request->end;
            $to_mod->description = $request->description;

            $to_mod->save();

            return $this->sendResponse($to_mod, 'Etude modifiée.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur d\'enregistrement de l\'édition de l\'Etude.', $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $education = Education::where('id', $id)->first();
            $education->delete();
            return $this->sendResponse($education->id, 'Etude supprimée.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de suppression de l\'Etude.', $th->getMessage(), 500);
        }
    }

    /**
     * Restore a deleted specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return Redirect
     */
    public function restore(Request $request, int $id)
    {
        try {
            $education = Education::withTrashed()->where('id', $id)->first();
            $education->restore();
            return $this->sendResponse($education->id, 'Etude restorée.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de restoration de l\'Etude.', $th->getMessage(), 500);
        }
    }
}
