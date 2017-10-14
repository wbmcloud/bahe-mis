<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayFlowStat extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'day_flow_stat';
    }
}