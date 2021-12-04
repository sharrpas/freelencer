<?php

namespace App\Http\Controllers\Hirer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function show(Project $project)
    {
        return $this->success($project->load('bidders'));//TODO resource
    }


    public function accept(Project $project, User $user)
    {
        if ($project->bidders()->where('user_id', $user->id)->exists()) {
            if ($project->status == 'open') {
                $project->update([
                    'freelancer_id' => $user->id,
                    'status' => 'done',
                ]);
                return $this->success('bid accepted, inform freelancer pleas');
            }
            return $this->error(Status::ALREADY_ASSIGNED);
        }
        return $this->error(Status::NOT_BID);
    }


}
