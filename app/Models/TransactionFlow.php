<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFlow extends Model
{
    use SoftDeletes;

    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'transaction_flow';
    }

    public function gameServer()
    {
        return $this->hasOne('App\Models\GameServer', 'id', 'game_server_id');
    }

    public function userBind()
    {
        return $this->hasOne('App\Models\PlayerBindAgent', 'player_id', 'recipient_id');
    }
}