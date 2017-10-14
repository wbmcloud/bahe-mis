<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayAgentStat extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'day_agent_stat';
    }
}