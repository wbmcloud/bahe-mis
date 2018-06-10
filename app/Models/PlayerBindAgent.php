<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerBindAgent extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'player_bind_agent';
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'agent_id', 'uk');
    }

    public function player()
    {
        return $this->hasOne('App\Models\GamePlayer', 'player_id', 'player_id');
    }
}