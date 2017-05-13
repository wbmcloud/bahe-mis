<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionFlow extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'transaction_flow';
    }
}