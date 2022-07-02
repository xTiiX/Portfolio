<?php

namespace App\Http\Controllers\API;

use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Validator;

class ProjectController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $projects = Project::all();
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération des Projets.', $th->getMessage(), 500);
        }

        try {
            foreach ($projects as $project)
                $project->image = File::where('id', $project->image_id)->first()->url;
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération des Images.', $th->getMessage(), 400);
        }

        return $this->sendResponse($projects, 'Projets envoyés.');

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
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'description' => 'required',
                'image' => 'required',
            ]);

            if ($validator->fails())
                return $this->sendError('Les informations données sont incompletes.', $validator->errors(), 400);

            $image = File::create([
                'type' => 'image',
                'url' => $request->image,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError('Erreur d\'enregistrement de l\'Image.', $th->getMessage(), 400);
        }
        try {
            $project = Project::create([
                'name' => $request->name,
                'start' => $request->start,
                'end' => $request->end,
                'description' => $request->description,
                'image_id' => $image->id
            ]);
            return $this->sendResponse($project->id, 'Projet enregistré.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur d\'enregistrement du Projet.', $th->getMessage(), 500);
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
            $project = Project::where('id', $id)->first();
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération du Projet.', $th->getMessage(), 500);
        }

        try {
            $project->image = File::where('id', $project->image_id)->first()->url;
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération de l\'Image.', $th->getMessage(), 400);
        }

        return $this->sendResponse($project, 'Projet envoyé.');
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
            $project = Project::where('id', $id)->first();
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération du Projet.', $th->getMessage(), 500);
        }

        try {
            $project->image = File::where('id', $project->image_id)->first()->url;
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de récupération de l\'Image.', $th->getMessage(), 400);
        }

        return $this->sendResponse($project, 'Projet à éditer envoyé.');
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
                'name' => 'required',
                'start' => 'required',
                'end' => 'required',
                'description' => 'required',
                'image' => 'required',
            ]);

            if ($validator->fails())
                return $this->sendError('Les informations données sont incompletes.', $validator->errors(), 400);

            $to_mod = Project::where('id', $id)->first();
            $img_to_mod = File::where('id', $to_mod->image_id)->first();

            $to_mod->name = $request->name;
            $to_mod->start = $request->start;
            $to_mod->end = $request->end;
            $to_mod->description = $request->description;
            $img_to_mod->url = $request->image;

            $to_mod->save();
            $img_to_mod->save();

            return $this->sendResponse($to_mod, 'Projet modifié.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur d\'enregistrement de l\'édition du Projet.', $th->getMessage(), 500);
        }
    }

    /**
     * Delete the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $project = Project::where('id', $id)->first();
            $project->delete();
            return $this->sendResponse($project->id, 'Projet supprimé.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de suppression du Projet.', $th->getMessage(), 500);
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
            $project = Project::withTrashed()->where('id', $id)->first();
            $project->restore();
            return $this->sendResponse($project->id, 'Projet restoré.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur de restoration du Projet.', $th->getMessage(), 500);
        }
    }
}
