<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralAgents extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'general_agents';
    }

    public function agents()
    {
        return $this->hasMany('App\Models\User', 'invite_code', 'invite_code');
    }
}