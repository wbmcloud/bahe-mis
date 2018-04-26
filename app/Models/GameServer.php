<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameServer extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'game_server';
    }

    public function city()
    {
        return $this->hasOne('App\Models\City', 'city_id', 'city_id');
    }
}