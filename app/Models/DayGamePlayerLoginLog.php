<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayGamePlayerLoginLog extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'day_game_player_login_log';
    }
}