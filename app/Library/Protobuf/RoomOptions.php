<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: PATH/game.proto

namespace App\Library\Protobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * <pre>
 *房间设置-番型选择
 * </pre>
 *
 * Protobuf type <code>app.library.protobuf.RoomOptions</code>
 */
class RoomOptions extends \Google\Protobuf\Internal\Message
{
    /**
     * <pre>
     *模式选择
     * </pre>
     *
     * <code>.app.library.protobuf.ROOM_MODEL_TYPE model = 1;</code>
     */
    private $model = 0;
    /**
     * <pre>
     *番型数量
     * </pre>
     *
     * <code>int32 extend_type_count = 2;</code>
     */
    private $extend_type_count = 0;
    /**
     * <pre>
     *额外番型
     * </pre>
     *
     * <code>repeated .app.library.protobuf.ROOM_EXTEND_TYPE extend_type = 3;</code>
     */
    private $extend_type;
    /**
     * <pre>
     *封顶番数
     * </pre>
     *
     * <code>int32 top_mutiple = 4;</code>
     */
    private $top_mutiple = 0;
    /**
     * <pre>
     *局数
     * </pre>
     *
     * <code>int32 open_rands = 5;</code>
     */
    private $open_rands = 0;
    /**
     * <pre>
     *实时语音
     * </pre>
     *
     * <code>int32 voice_open = 6;</code>
     */
    private $voice_open = 0;

    public function __construct() {
        \GPBMetadata\PATH\Game::initOnce();
        parent::__construct();
    }

    /**
     * <pre>
     *模式选择
     * </pre>
     *
     * <code>.app.library.protobuf.ROOM_MODEL_TYPE model = 1;</code>
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * <pre>
     *模式选择
     * </pre>
     *
     * <code>.app.library.protobuf.ROOM_MODEL_TYPE model = 1;</code>
     */
    public function setModel($var)
    {
        GPBUtil::checkEnum($var, \App\Library\Protobuf\ROOM_MODEL_TYPE::class);
        $this->model = $var;
    }

    /**
     * <pre>
     *番型数量
     * </pre>
     *
     * <code>int32 extend_type_count = 2;</code>
     */
    public function getExtendTypeCount()
    {
        return $this->extend_type_count;
    }

    /**
     * <pre>
     *番型数量
     * </pre>
     *
     * <code>int32 extend_type_count = 2;</code>
     */
    public function setExtendTypeCount($var)
    {
        GPBUtil::checkInt32($var);
        $this->extend_type_count = $var;
    }

    /**
     * <pre>
     *额外番型
     * </pre>
     *
     * <code>repeated .app.library.protobuf.ROOM_EXTEND_TYPE extend_type = 3;</code>
     */
    public function getExtendType()
    {
        return $this->extend_type;
    }

    /**
     * <pre>
     *额外番型
     * </pre>
     *
     * <code>repeated .app.library.protobuf.ROOM_EXTEND_TYPE extend_type = 3;</code>
     */
    public function setExtendType(&$var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::ENUM, \App\Library\Protobuf\ROOM_EXTEND_TYPE::class);
        $this->extend_type = $arr;
    }

    /**
     * <pre>
     *封顶番数
     * </pre>
     *
     * <code>int32 top_mutiple = 4;</code>
     */
    public function getTopMutiple()
    {
        return $this->top_mutiple;
    }

    /**
     * <pre>
     *封顶番数
     * </pre>
     *
     * <code>int32 top_mutiple = 4;</code>
     */
    public function setTopMutiple($var)
    {
        GPBUtil::checkInt32($var);
        $this->top_mutiple = $var;
    }

    /**
     * <pre>
     *局数
     * </pre>
     *
     * <code>int32 open_rands = 5;</code>
     */
    public function getOpenRands()
    {
        return $this->open_rands;
    }

    /**
     * <pre>
     *局数
     * </pre>
     *
     * <code>int32 open_rands = 5;</code>
     */
    public function setOpenRands($var)
    {
        GPBUtil::checkInt32($var);
        $this->open_rands = $var;
    }

    /**
     * <pre>
     *实时语音
     * </pre>
     *
     * <code>int32 voice_open = 6;</code>
     */
    public function getVoiceOpen()
    {
        return $this->voice_open;
    }

    /**
     * <pre>
     *实时语音
     * </pre>
     *
     * <code>int32 voice_open = 6;</code>
     */
    public function setVoiceOpen($var)
    {
        GPBUtil::checkInt32($var);
        $this->voice_open = $var;
    }

}

