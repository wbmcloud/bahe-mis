<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'invite_code';
    }

    public function city()
    {
        return $this->hasOne('App\Models\City', 'city_id', 'city_id');
    }
}