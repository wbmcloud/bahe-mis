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
        isset($data['item_id']) && ($command->setItemId($data['item_id']));
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

    public static function packOpenRoom($data)
    {
        $open_room = new OpenRoom();
        $open_room->setTypeT(INNER_TYPE::INNER_TYPE_OPEN_ROOM);
        $open_room->setServerId($data['server_id']);
        $open_room->setOptions(self::packRoomOptions($data));

        return $open_room->serializeToString();
    }

    public static function packOpenRoomInnerMeta($data)
    {
        $inner_meta = new InnerMeta();
        $inner_meta->setTypeT(INNER_TYPE::INNER_TYPE_OPEN_ROOM);
        $inner_meta->setStuff(self::packOpenRoom($data));
        return $inner_meta->serializeToString();
    }

    public static function unpackOpenRoom($data)
    {
        $open_room = new OpenRoom();
        $open_room->mergeFromString($data);
        return [
            'error_code' => $open_room->getErrorCode(),
            'room_id' => $open_room->getRoomId()
        ];
    }

    public static function packRoomOptions($data)
    {
        $room_option = new RoomOptions();
        //$room_option->setModel($data['model']);
        if (isset($data['extend_type']) && !empty($data['extend_type'])) {
            $room_option->setExtendType($data['extend_type']);
            $room_option->setExtendTypeCount(count($data['extend_type']));
        }
        $room_option->setOpenRands($data['open_rands']);
        $room_option->setTopMutiple($data['top_mutiple']);
        if (isset($data['voice_open']) && !empty($data['voice_open'])) {
            $room_option->setVoiceOpen(intval($data['voice_open']));
        } else {
            $room_option->setVoiceOpen(0);
        }
        $room_option->setCityType($data['city_type']);

        return $room_option->serializeToString();
    }

    public static function packQueryPlayer($player_id)
    {
        $query_player = new QueryPlayer();
        $query_player->setTypeT(INNER_TYPE::INNER_TYPE_QUERY_PLAYER);
        $query_player->setPlayerId($player_id);

        $common_prop = new CommonProp();
        $byte = $common_prop->serializeToString();
        $query_player->setCommonProp($byte);

        return $query_player->serializeToString();
    }

    public static function packQueryPlayerInnerMeta($player_id)
    {
        $inner_meta = new InnerMeta();
        $inner_meta->setTypeT(INNER_TYPE::INNER_TYPE_QUERY_PLAYER);
        $inner_meta->setStuff(self::packQueryPlayer($player_id));
        return $inner_meta->serializeToString();
    }

    public static function unpackQueryPlayer($data)
    {
        $query_player = new QueryPlayer();
        $query_player->mergeFromString($data);
        if ($query_player->getErrorCode() != 0) {
            return false;
        }
        $common_prop = new CommonProp();
        $common_prop->mergeFromString($query_player->getCommonProp());
        return $common_prop;
    }
}