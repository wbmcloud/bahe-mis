<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/13
 * Time: 下午10:49
 */

namespace App\Library\Protobuf;

class Protobuf
{
    public static function pack($data)
    {
        $command = new Command();
        $command->setCommandType($data['command_type']);
        $command->setAccount($data['account']);
        $command->setPlayerId($data['player_id']);
        $command->setCount($data['count']);
        return $command->serializeToString();
    }

    public static function unpack($data)
    {
        $command = new Command();
        $command->mergeFromString($data);
        return [
            'error_code' => $command->getErrorCode()
        ];
    }
}