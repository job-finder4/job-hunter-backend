<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const IN_PROGRESS = 'In Progress';
    const DONE = 'Done';
    const TODO = 'To Do';

    protected $dates = [
        'started_at',
        'finished_at'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'started_at',
        'finished_at',
        'status_code'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
