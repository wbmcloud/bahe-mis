<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 下午10:57
 */

namespace App\Common;

use App\Library\Protobuf\COMMAND_TYPE;

class Constants
{
    /**
     * 角色名称
     */
    const ROLE_SUPER         = 'super';
    const ROLE_ADMIN         = 'admin';
    const ROLE_AGENT         = 'agent';
    const ROLE_FIRST_AGENT   = 'first_agent';
    const ROLE_GENERAL_AGENT = 'general_agent';

    /**
     * 角色类型id
     */
    const ROLE_TYPE_USER          = 0;
    const ROLE_TYPE_SUPER         = 1;
    const ROLE_TYPE_ADMIN         = 2;
    const ROLE_TYPE_AGENT         = 3;
    const ROLE_TYPE_FIRST_AGENT   = 4;
    const ROLE_TYPE_GENERAL_AGENT = 5;

    /**
     * 充值类型
     */
    public static $recharge_type = [
        COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
        COMMAND_TYPE::COMMAND_TYPE_RECHARGE,
        COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU,
    ];

    /**
     * GMT操作类型
     * @var array
     */
    public static $transaction_type = [
        COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD => '房卡',
        COMMAND_TYPE::COMMAND_TYPE_RECHARGE  => '钻石',
        COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU => '欢乐豆',
        self::COMMAND_TYPE_OPEN_ROOM         => '代开房',
    ];

    /**
     * 角色名和角色类型对应关系
     * @var array
     */
    public static $recharge_role_type = [
        self::ROLE_SUPER         => self::ROLE_TYPE_SUPER,
        self::ROLE_ADMIN         => self::ROLE_TYPE_ADMIN,
        self::ROLE_AGENT         => self::ROLE_TYPE_AGENT,
        self::ROLE_FIRST_AGENT   => self::ROLE_TYPE_FIRST_AGENT,
        self::ROLE_GENERAL_AGENT => self::ROLE_TYPE_GENERAL_AGENT,
    ];

    /**
     * 角色描述
     * @var array
     */
    public static $role_type = [
        self::ROLE_TYPE_USER          => '用户',
        self::ROLE_TYPE_SUPER         => '超级管理员',
        self::ROLE_TYPE_ADMIN         => '管理员',
        self::ROLE_TYPE_AGENT         => '代理',
        self::ROLE_TYPE_FIRST_AGENT   => '总代理',
        self::ROLE_TYPE_GENERAL_AGENT => '总监',
    ];

    public static $recharge_status = [
        self::COMMON_DISABLE => '失败',
        self::COMMON_ENABLE  => '成功',
    ];

    const COMMON_ENABLE  = 1;
    const COMMON_DISABLE = 0;

    /**
     * 分页相关配置
     */
    const DEFAULT_PAGE      = 1;
    const DEFAULT_PAGE_SIZE = 10;

    /**
     * 邀请码配置
     */
    const FIRST_AGENT_INVITE_CODE_LENGTH = 7;
    const INVITE_CODE_LENGTH             = 4;
    const INVITE_CODE_LEVEL_LENGTH       = 3;

    const BATCH_SIZE = 100;

    const ROOM_CARD_ITEM_ID = 13303809;

    const OPEN_ROOM_CARD_REDUCE = 1;

    public static $agreement_uri = [
        ParamsRules::IF_USER_AGREEMENT,
        ParamsRules::IF_USER_AGREE,
    ];

    const ADD_USER_TYPE_ADMIN         = 1;
    const ADD_USER_TYPE_AGENT         = 2;
    const ADD_USER_TYPE_FIRST_AGENT   = 3;
    const ADD_USER_TYPE_GENERAL_AGENT = 4;

    public static $recharge_role = [
        self::ROLE_AGENT,
        self::ROLE_FIRST_AGENT,
        self::ROLE_GENERAL_AGENT,
    ];

    public static $agent_role_type = [
        self::ROLE_TYPE_AGENT,
        self::ROLE_TYPE_FIRST_AGENT,
        self::ROLE_TYPE_GENERAL_AGENT
    ];

    public static $admin_role = [
        self::ROLE_SUPER,
        self::ROLE_ADMIN
    ];

    public static $level_agent = [
        self::ROLE_FIRST_AGENT,
        self::ROLE_GENERAL_AGENT,
    ];

    /**
     * 代开房类型
     */
    const COMMAND_TYPE_OPEN_ROOM = 11;

    /**
     * 房卡价钱
     */
    const ROOM_CARD_PRICE = 0.3;

    /**
     * 代理级别类型
     */
    const AGENT_LEVEL_GENERAL = 1;
    const AGENT_LEVEL_FIRST   = 2;

    /**
     * 操作结果
     */
    const OPERATOR_PROMPT_NOT_PERMISSION_TEXT = '请勿非法访问！';
    const JUMP_TIME_INTERNAL                  = 5;

    /**
     * 邀请码生成key前缀
     */
    const INVITE_CODE_INCR       = 'INVITE_CODE_INCR_';
    const INVITE_CODE_LEVEL_INCR = 'INVITE_CODE_LEVEL_INCR_';

    /**
     * 邀请码类型
     */
    const INVITE_CODE_TYPE_GENERAL_AGENT = 1;
    const INVITE_CODE_TYPE_FIRST_AGENT   = 2;

    /**
     * 成功提示标题
     */
    const SUCCESS_PROMPT_OPEN_ROOM               = '房间号';
    const SUCCESS_PROMPT_FIRST_AGENT_INVITE_CODE = '邀请码';

    /**
     * 佣金类型
     */
    const COMMISSION_TYPE_FIRST_TO_AGENT_RATE   = 0.25;
    const COMMISSION_TYPE_GENERAL_TO_FIRST_RATE = 0.25;
    const COMMISSION_TYPE_GENERAL_TO_AGENT_RATE = 0.5;

    /**
     * 打款单类型
     */
    const CASH_ORDER_TYPE_GENERAL = 1;
    const CASH_ORDER_TYPE_FIRST   = 2;

    const ROOM_CARD_RANDOMS = 8;

    const STAT_MAX_DAY = 30;

    const FEE_START_DATE = '2017-10-06';

    const OPEN_ROOM_MODE_CLASSIC = 1;
    const OPEN_ROOM_MODE_GAOFAN  = 2;

    const OPEN_ROOM_FANXING_ZHANLIHU     = 1;
    const OPEN_ROOM_FANXING_DAIJIAHU     = 2;
    const OPEN_ROOM_FANXING_XUANFENGGANG = 3;
    const OPEN_ROOM_FANXING_BAOPAI       = 4;
    const OPEN_ROOM_FANXING_KEDUANMEN    = 5;
    const OPEN_ROOM_FANXING_QINGYISE     = 6;
    const OPEN_ROOM_FANXING_BAOSANJIA    = 7;
    const OPEN_ROOM_FANXING_ANBAO        = 8;

    const OPEN_ROOM_FANXING_28ZUOZHUANG          = 9;
    const OPEN_ROOM_FANXING_MINGPIAO             = 10;
    const OPEN_ROOM_FANXING_HUANGZHUANGHUANGGANG = 11;
    const OPEN_ROOM_FANXING_YIBIANGAO            = 12;
    const OPEN_ROOM_FANXING_SIGUIYI              = 13;
    const OPEN_ROOM_FANXING_YIJIAFU              = 14;

    const OPEN_ROOM_FANXING_HUIPAI        = 15;
    const OPEN_ROOM_FANXING_JUETOUHUI     = 16;
    const OPEN_ROOM_FANXING_QIONGHU       = 17;
    const OPEN_ROOM_FANXING_QIDUI         = 18;
    const OPEN_ROOM_FANXING_ZHUIFENGGANGA = 19;

    const OPEN_ROOM_FANXING_ZHANLIHU_DESC             = '站立胡';
    const OPEN_ROOM_FANXING_DAIJIAHU_DESC             = '带夹胡';
    const OPEN_ROOM_FANXING_XUANFENGGANG_DESC         = '旋风杠';
    const OPEN_ROOM_FANXING_BAOPAI_DESC               = '宝牌';
    const OPEN_ROOM_FANXING_KEDUANMEN_DESC            = '可断门';
    const OPEN_ROOM_FANXING_QINGYISE_DESC             = '清一色';
    const OPEN_ROOM_FANXING_BAOSANJIA_DESC            = '包三家';
    const OPEN_ROOM_FANXING_ANBAO_DESC                = '暗宝';
    const OPEN_ROOM_FANXING_28ZUOZHUANG_DESC          = '二八做掌';
    const OPEN_ROOM_FANXING_MINGPIAO_DESC             = '明飘';
    const OPEN_ROOM_FANXING_HUANGZHUANGHUANGGANG_DESC = '荒庄荒杠';
    const OPEN_ROOM_FANXING_YIBIANGAO_DESC            = '一边高';
    const OPEN_ROOM_FANXING_SIGUIYI_DESC              = '四归一';
    const OPEN_ROOM_FANXING_YIJIAFU_DESC              = '点炮一家付';

    const OPEN_ROOM_FANXING_HUIPAI_DESC        = '带会';
    const OPEN_ROOM_FANXING_JUETOUHUI_DESC     = '绝头会N*2';
    const OPEN_ROOM_FANXING_QIONGHU_DESC       = '穷胡';
    const OPEN_ROOM_FANXING_QIDUI_DESC         = '七小对';
    const OPEN_ROOM_FANXING_ZHUIFENGGANGA_DESC = '追风杠';


    const OPEN_ROOM_ROUNDS_EIGHT   = 8;
    const OPEN_ROOM_ROUNDS_SIXTEEN = 16;
    const OPEN_ROOM_ROUNDS_TWENTY_FOUR = 24;

    const OPEN_ROOM_TOP_MULTIPLE_UNLIMITED  = 0;
    const OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO = 32;
    const OPEN_ROOM_TOP_MULTIPLE_SIXTY_FOUR = 64;
    const OPEN_ROOM_TOP_MULTIPLE_ONE_HUNDRED_FIFTY_SIX = 256;

    const OPEN_ROOM_VOICE_UP   = 1;
    const OPEN_ROOM_VOICE_DOWN = 0;

    const ZHUANG_TYPE_QIANGDIZHU = 1;
    const ZHUANG_TYPE_JIAOFEN = 2;

    public static $open_room_mode = [
        self::OPEN_ROOM_MODE_CLASSIC => '经典模式',
        self::OPEN_ROOM_MODE_GAOFAN  => '高番模式'
    ];

    public static $open_room_fanxing = [
        self::OPEN_ROOM_FANXING_ZHANLIHU             => self::OPEN_ROOM_FANXING_ZHANLIHU_DESC,
        self::OPEN_ROOM_FANXING_DAIJIAHU             => self::OPEN_ROOM_FANXING_DAIJIAHU_DESC,
        self::OPEN_ROOM_FANXING_XUANFENGGANG         => self::OPEN_ROOM_FANXING_XUANFENGGANG_DESC,
        self::OPEN_ROOM_FANXING_BAOPAI               => self::OPEN_ROOM_FANXING_BAOPAI_DESC,
        self::OPEN_ROOM_FANXING_KEDUANMEN            => self::OPEN_ROOM_FANXING_KEDUANMEN_DESC,
        self::OPEN_ROOM_FANXING_QINGYISE             => self::OPEN_ROOM_FANXING_QINGYISE_DESC,
        self::OPEN_ROOM_FANXING_BAOSANJIA            => self::OPEN_ROOM_FANXING_BAOSANJIA_DESC,
        self::OPEN_ROOM_FANXING_ANBAO                => self::OPEN_ROOM_FANXING_ANBAO_DESC,
        self::OPEN_ROOM_FANXING_28ZUOZHUANG          => self::OPEN_ROOM_FANXING_28ZUOZHUANG_DESC,
        self::OPEN_ROOM_FANXING_MINGPIAO             => self::OPEN_ROOM_FANXING_MINGPIAO_DESC,
        self::OPEN_ROOM_FANXING_HUANGZHUANGHUANGGANG => self::OPEN_ROOM_FANXING_HUANGZHUANGHUANGGANG_DESC,
        self::OPEN_ROOM_FANXING_YIBIANGAO            => self::OPEN_ROOM_FANXING_YIBIANGAO_DESC,
        self::OPEN_ROOM_FANXING_SIGUIYI              => self::OPEN_ROOM_FANXING_SIGUIYI_DESC,
        self::OPEN_ROOM_FANXING_YIJIAFU              => self::OPEN_ROOM_FANXING_YIJIAFU_DESC,
        self::OPEN_ROOM_FANXING_HUIPAI               => self::OPEN_ROOM_FANXING_HUIPAI_DESC,
        self::OPEN_ROOM_FANXING_JUETOUHUI            => self::OPEN_ROOM_FANXING_JUETOUHUI_DESC,
        self::OPEN_ROOM_FANXING_QIONGHU              => self::OPEN_ROOM_FANXING_QIONGHU_DESC,
        self::OPEN_ROOM_FANXING_QIDUI                => self::OPEN_ROOM_FANXING_QIDUI_DESC,
        self::OPEN_ROOM_FANXING_ZHUIFENGGANGA        => self::OPEN_ROOM_FANXING_ZHUIFENGGANGA_DESC
    ];

    public static $open_room_rounds = [
        self::OPEN_ROOM_ROUNDS_EIGHT       => '8局（消耗房卡*4）',
        self::OPEN_ROOM_ROUNDS_SIXTEEN     => '16局（消耗房卡*8）',
        self::OPEN_ROOM_ROUNDS_TWENTY_FOUR => '24局（消耗房卡*12））',
    ];

    public static $open_room_top_multiple = [
        self::OPEN_ROOM_TOP_MULTIPLE_UNLIMITED  => '不封顶',
        self::OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO => '32倍',
    ];

    public static $open_room_ddz_top_multiple = [
        self::OPEN_ROOM_TOP_MULTIPLE_UNLIMITED             => '不封顶',
        self::OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO            => '32分',
        self::OPEN_ROOM_TOP_MULTIPLE_SIXTY_FOUR            => '64分',
        self::OPEN_ROOM_TOP_MULTIPLE_ONE_HUNDRED_FIFTY_SIX => '256分'
    ];

    public static $open_room_voice = [
        self::OPEN_ROOM_VOICE_DOWN => '不开启语音',
        self::OPEN_ROOM_VOICE_UP   => '开启语音',
    ];

    public static $open_room_zhuang_type = [
        self::ZHUANG_TYPE_QIANGDIZHU => '抢地主',
        self::ZHUANG_TYPE_JIAOFEN => '叫分'
    ];

    const OPEN_ROOM_PARAM_TYPE_EXTEND_TYPE = 'extend_type';
    const OPEN_ROOM_PARAM_TYPE_OPEN_RANDS = 'open_rands';
    const OPEN_ROOM_PARAM_TYPE_TOP_MULTIPLE = 'top_multiple';
    const OPEN_ROOM_PARAM_TYPE_DDZ_TOP_MULTIPLE = 'ddz_top_multiple';
    const OPEN_ROOM_PARAM_TYPE_ZHUANG_TYPE = 'zhuang_type';

    public static $open_room_param_map = [
        self::OPEN_ROOM_PARAM_TYPE_EXTEND_TYPE => 'open_room_fanxing',
        self::OPEN_ROOM_PARAM_TYPE_OPEN_RANDS => 'open_room_rounds',
        self::OPEN_ROOM_PARAM_TYPE_TOP_MULTIPLE => 'open_room_top_multiple',
        self::OPEN_ROOM_PARAM_TYPE_DDZ_TOP_MULTIPLE => 'open_room_ddz_top_multiple',
        self::OPEN_ROOM_PARAM_TYPE_ZHUANG_TYPE => 'open_room_zhuang_type',
    ];

    /**
     * 房卡数裂变因子
     */
    const ROOM_CARD_FISSION_FACTOR = 4;

    /**
     * 代开房默认配置
     * @var array
     */
    public static $open_room_default_params = [
        '1' => [
            'game_server_id' => 1,
            'game_type'      => self::GAME_TYPE_MJ,
            'extend_type'    => [
                self::OPEN_ROOM_FANXING_BAOPAI,
                self::OPEN_ROOM_FANXING_ZHANLIHU,
                self::OPEN_ROOM_FANXING_DAIJIAHU,
                self::OPEN_ROOM_FANXING_QINGYISE,
                self::OPEN_ROOM_FANXING_XUANFENGGANG,
                self::OPEN_ROOM_FANXING_BAOSANJIA,
            ],
            'open_rands'     => self::OPEN_ROOM_ROUNDS_EIGHT,
            'top_mutiple'   => self::OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO,
        ],
        '2' => [
            'game_server_id' => 2,
            'game_type'      => self::GAME_TYPE_MJ,
            'extend_type'    => [
                self::OPEN_ROOM_FANXING_28ZUOZHUANG,
                self::OPEN_ROOM_FANXING_MINGPIAO,
                self::OPEN_ROOM_FANXING_YIBIANGAO,
                self::OPEN_ROOM_FANXING_SIGUIYI,
                self::OPEN_ROOM_FANXING_YIJIAFU,
            ],
            'open_rands'     => self::OPEN_ROOM_ROUNDS_EIGHT,
            'top_mutiple'   => self::OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO,
        ],
        '3' => [
            'game_server_id' => 3,
            'game_type'      => self::GAME_TYPE_MJ,
            'extend_type'    => [
                self::OPEN_ROOM_FANXING_JUETOUHUI,
                self::OPEN_ROOM_FANXING_ZHANLIHU,
                self::OPEN_ROOM_FANXING_QIONGHU,
                self::OPEN_ROOM_FANXING_KEDUANMEN,
                self::OPEN_ROOM_FANXING_QINGYISE,
                self::OPEN_ROOM_FANXING_XUANFENGGANG,
                self::OPEN_ROOM_FANXING_BAOSANJIA,
                self::OPEN_ROOM_FANXING_HUIPAI,
                self::OPEN_ROOM_FANXING_ZHUIFENGGANGA,
                self::OPEN_ROOM_FANXING_QIDUI,
            ],
            'open_rands'     => self::OPEN_ROOM_ROUNDS_EIGHT,
            'top_mutiple'   => self::OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO,
        ],
        '4' => [
            'game_server_id' => 4,
            'game_type'      => self::GAME_TYPE_DDZ,
            'open_rands'     => self::OPEN_ROOM_ROUNDS_EIGHT,
            'top_mutiple'    => self::OPEN_ROOM_TOP_MULTIPLE_THIRTY_TWO,
            'zhuang_type'    => self::ZHUANG_TYPE_QIANGDIZHU
        ],
    ];

    public static $city_map = [
        '2340' => '1',
        '2342' => '2',
        '2280' => '3',
    ];

    public static $bind_player_city_ban = [
        '2340',
        '2342'
    ];


    const GAME_TYPE_MJ  = 1;
    const GAME_TYPE_DDZ = 2;

    public static $division_city_game_type = [
        self::GAME_TYPE_MJ
    ];
}