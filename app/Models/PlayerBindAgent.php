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
}