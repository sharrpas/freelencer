<?php

namespace App\Http\Controllers\Hirer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectResourceWithDescription;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{

    public function index()
    {
        return $this->success(new ProjectCollection(Project::query()->where('user_id', auth()->id())->paginate(10)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'tags' => 'min:3',
        ]);

        Project::query()->create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'tags' => $request->tags,
            'status' => 'open',
        ]);

        return $this->success();
    }

    public function show($project)
    {
        $Project = ProjectResourceWithDescription::make(Project::query()->findOrFail($project));
        return $this->success($Project);
    }

    public function update(Request $request, Project $project)
    {
        if ($project->status == 'done') {
            return $this->error(Status::UPDATE_COMPLETED_PROJECT);
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'tags' => 'min:3',
        ]);

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'tags' => $request->tags,
        ]);

        return $this->success('project updated successfully');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id != auth()->id()) {
            return $this->error(Status::DELETE_OTHERS_PROJECT);
        }
        $project->delete();
        return $this->success('project deleted successfully');
    }


}
