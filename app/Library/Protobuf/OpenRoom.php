<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: PATH/game.proto

namespace App\Library\Protobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * <pre>
 *代开房间
 * </pre>
 *
 * Protobuf type <code>app.library.protobuf.OpenRoom</code>
 */
class OpenRoom extends \Google\Protobuf\Internal\Message
{
    /**
     * <code>.app.library.protobuf.INNER_TYPE type_t = 1;</code>
     */
    private $type_t = 0;
    /**
     * <pre>
     *返回码
     * </pre>
     *
     * <code>.app.library.protobuf.COMMAND_ERROR_CODE error_code = 2;</code>
     */
    private $error_code = 0;
    /**
     * <pre>
     *游戏服务器ID
     * </pre>
     *
     * <code>int64 server_id = 3;</code>
     */
    private $server_id = 0;
    /**
     * <pre>
     *房间ID，如果非0则证明开放成功
     * </pre>
     *
     * <code>int64 room_id = 4;</code>
     */
    private $room_id = 0;

    public function __construct() {
        \GPBMetadata\PATH\Game::initOnce();
        parent::__construct();
    }

    /**
     * <code>.app.library.protobuf.INNER_TYPE type_t = 1;</code>
     */
    public function getTypeT()
    {
        return $this->type_t;
    }

    /**
     * <code>.app.library.protobuf.INNER_TYPE type_t = 1;</code>
     */
    public function setTypeT($var)
    {
        GPBUtil::checkEnum($var, \App\Library\Protobuf\INNER_TYPE::class);
        $this->type_t = $var;
    }

    /**
     * <pre>
     *返回码
     * </pre>
     *
     * <code>.app.library.protobuf.COMMAND_ERROR_CODE error_code = 2;</code>
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * <pre>
     *返回码
     * </pre>
     *
     * <code>.app.library.protobuf.COMMAND_ERROR_CODE error_code = 2;</code>
     */
    public function setErrorCode($var)
    {
        GPBUtil::checkEnum($var, \App\Library\Protobuf\COMMAND_ERROR_CODE::class);
        $this->error_code = $var;
    }

    /**
     * <pre>
     *游戏服务器ID
     * </pre>
     *
     * <code>int64 server_id = 3;</code>
     */
    public function getServerId()
    {
        return $this->server_id;
    }

    /**
     * <pre>
     *游戏服务器ID
     * </pre>
     *
     * <code>int64 server_id = 3;</code>
     */
    public function setServerId($var)
    {
        GPBUtil::checkInt64($var);
        $this->server_id = $var;
    }

    /**
     * <pre>
     *房间ID，如果非0则证明开放成功
     * </pre>
     *
     * <code>int64 room_id = 4;</code>
     */
    public function getRoomId()
    {
        return $this->room_id;
    }

    /**
     * <pre>
     *房间ID，如果非0则证明开放成功
     * </pre>
     *
     * <code>int64 room_id = 4;</code>
     */
    public function setRoomId($var)
    {
        GPBUtil::checkInt64($var);
        $this->room_id = $var;
    }

}

