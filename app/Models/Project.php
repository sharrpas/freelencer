<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function bidders()
    {
        return $this->belongsToMany(User::class, 'bids')->using(Bid::class)->withTimestamps();
    }

    public function addFreelancer(User $freelancer)
    {
        $this->freelancer_id = $freelancer->id;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function execute()
    {
        $this->save();
    }
}
