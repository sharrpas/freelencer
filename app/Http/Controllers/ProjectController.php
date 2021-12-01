<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResourceWithDescription;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return $this->success(new ProjectCollection(Project::query()->paginate(10)));
    }

    public function show($project)
    {
        $Project = ProjectResourceWithDescription::make(Project::query()->findOrFail($project));
        return $this->success($Project);
    }

    public function search()
    {
        //todo
    }

    //    return  $m->load('bidders');
}
