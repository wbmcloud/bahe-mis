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
    public static function packCommand($data)
    {
        $command = new Command();
        $command->setTypeT(INNER_TYPE::INNER_TYPE_COMMAND);
        $command->setCommandType($data['command_type']);
        // $command->setAccount($data['account']);
        $command->setPlayerId($data['player_id']);
        $command->setCount($data['count']);
        return $command->serializeToString();
    }

    public static function packRegisterSrv()
    {
        $register = new Register();
        $register->setTypeT(INNER_TYPE::INNER_TYPE_REGISTER);
        $register->setServerType(SERVER_TYPE::SERVER_TYPE_GMT);
        return $register->serializeToString();
    }

    public static function unpackCommand($data)
    {
        $command = new Command();
        $command->mergeFromString($data);
        return $command;
    }

    public static function unpackRegister($data)
    {
        $register = new Register();
        $register->mergeFromString($data);
        return $register;
    }

    public static function unpackForResponse($data)
    {
        $command = self::unpackCommand($data);
        return [
            'error_code' => $command->getErrorCode()
        ];
    }

    public static function packCommandInnerMeta($data)
    {
        $inner_meta = new InnerMeta();
        $inner_meta->setTypeT(INNER_TYPE::INNER_TYPE_COMMAND);
        $inner_meta->setStuff(self::packCommand($data));
        return $inner_meta->serializeToString();
    }

    public static function packRegisterInnerMeta()
    {
        $inner_meta = new InnerMeta();
        $inner_meta->setTypeT(INNER_TYPE::INNER_TYPE_REGISTER);
        $inner_meta->setStuff(self::packRegisterSrv());
        return $inner_meta->serializeToString();
    }
}