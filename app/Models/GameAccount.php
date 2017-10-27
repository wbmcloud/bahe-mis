<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameAccount extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'game_account';
    }
}