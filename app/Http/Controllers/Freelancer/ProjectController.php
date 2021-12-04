<?php

namespace App\Http\Controllers\Freelancer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResourceWithDescription;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return $this->success(new ProjectCollection(Project::query()->where('freelancer_id', auth()->id())->paginate(10)));
    }

    public function show($project)
    {
        $Project = ProjectResourceWithDescription::make(Project::query()->with('freelancer')->findOrFail($project));
        return $this->success($Project);
    }

}
