<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use RecordActivity;
    use HasFactory;

    protected $guarded = [ ];

    protected $observables = [
        'invited'
    ];

    public function path() {
        return "/season/{$this->id}";
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function bakers() {
        return $this->hasMany(Baker::class);
    }

    public function episodes() {
        return $this->hasMany(Episode::class);
    }

    public function addBaker($values) {
        return $this->bakers()->create($values);
    }

    public function addEpisode($values) {
        return $this->episodes()->create($values);
    }

    public function activities() {
        return $this->hasMany(Activity::class)->latest();
    }

    public function invite($user) {
        $member = $this->members()->attach($user);

        $user->fireModelEvent('invited');

        return $member;
    }

    public function members() {
        return $this->belongsToMany(User::class, 'season_members')->withTimestamps();
    }

    public function getMembers() {
        return ($return = clone $this->members)->push($this->owner);
    }

    public function getUserId() {
        return $this->owner->id;
    }

    public function getSeasonId() {
        return $this->id;
    }

    public function __get($key)
    {
        if ($key == 'allMembers') {
            return $this->getMembers();
        }

        return parent::__get($key); // TODO: Change the autogenerated stub
    }

    public function finalPredictionsCount($user = null) {
        return $this->finalPredictions($user)->count();
    }

    public function finalPredictions($user = null) {
        $user = $user ?: auth()->user();

        return FinalResult::where([
            [ 'owner_id',  '=', $user->id ],
            [ 'season_id', '=', $this->id ]
        ])->get();
    }

    public function finalResultsFinalized($user = null) {
        $user = $user ?: auth()->user();

        return FinalizedFinalResults::where([
            'owner_id' => $user->id,
            'season_id' => $this->id
        ])->first();
    }

    public function finalizeFinalResults($user = null) {
        $user = $user ?: auth()->user();

        return FinalizedFinalResults::firstOrCreate([
            'owner_id' => $user->id,
            'season_id' => $this->id
        ]);
    }

    public function predictedWinner($user = null) {
        $user = $user ?: auth()->user();

        return FinalResult::where([
            [ 'winner', '=', true ],
            [ 'owner_id', '=', $user->id ],
            [ 'season_id', '=', $this->id ]
        ])->first();
    }
}
