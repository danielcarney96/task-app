<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'started_at',
        'ended_at',
    ];

    public function epics(): HasMany
    {
        return $this->hasMany(Epic::class);
    }

    public function projectColumns(): HasMany
    {
        return $this->hasMany(ProjectColumn::class);
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function storyTypes(): HasMany
    {
        return $this->hasMany(StoryType::class);
    }

    public function projectUser(): HasMany
    {
        return $this->hasMany(ProjectUser::class);
    }

    public function projectSettings(): HasMany
    {
        return $this->hasMany(ProjectSetting::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(ProjectUser::class);
    }
}
