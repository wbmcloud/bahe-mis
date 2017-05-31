<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: PATH/game.proto

namespace App\Library\Protobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Protobuf type <code>app.library.protobuf.Register</code>
 */
class Register extends \Google\Protobuf\Internal\Message
{
    /**
     * <code>.app.library.protobuf.INNER_TYPE type_t = 1;</code>
     */
    private $type_t = 0;
    /**
     * <pre>
     *服务器类型
     * </pre>
     *
     * <code>.app.library.protobuf.SERVER_TYPE server_type = 2;</code>
     */
    private $server_type = 0;
    /**
     * <pre>
     *服务器ID
     * </pre>
     *
     * <code>int64 server_id = 3;</code>
     */
    private $server_id = 0;

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
     *服务器类型
     * </pre>
     *
     * <code>.app.library.protobuf.SERVER_TYPE server_type = 2;</code>
     */
    public function getServerType()
    {
        return $this->server_type;
    }

    /**
     * <pre>
     *服务器类型
     * </pre>
     *
     * <code>.app.library.protobuf.SERVER_TYPE server_type = 2;</code>
     */
    public function setServerType($var)
    {
        GPBUtil::checkEnum($var, \App\Library\Protobuf\SERVER_TYPE::class);
        $this->server_type = $var;
    }

    /**
     * <pre>
     *服务器ID
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
     *服务器ID
     * </pre>
     *
     * <code>int64 server_id = 3;</code>
     */
    public function setServerId($var)
    {
        GPBUtil::checkInt64($var);
        $this->server_id = $var;
    }

}

