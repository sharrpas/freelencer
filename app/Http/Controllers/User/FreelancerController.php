<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    public function index()
    {
        //All my project
    }

    public function show()
    {
        //    return  $m->load('bidders');
    }

    public function store(Project $project)     // add bid
    {
        if ($project->bidders()->where('user_id', auth()->id())->exists()) {
            return $this->error(Status::ALREADY_BID);
        }

        $project->bidders()->attach(auth()->id());
        return $this->success('your bid saved');
    }

    public function bids()
    {
        $user = User::query()->find(auth()->id());
        return $this->success($user->load('bids'));//TODO resource
    }

}
