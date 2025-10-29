<?php

/**
 * Plugin Name: Zibll RESTAPI
 * Plugin URI: https://github.com/YuiNijika/Zibll-RESTAPI
 * Description: 适用于WordPress子比主题的RESTAPI扩展插件
 * Version: 1.0.0
 * Author: 鼠子
 * Author URI: https://space.bilibili.com/435502585
 */

if (!defined('ABSPATH')) exit;

class Zibll_RESTAPI
{
    const AUTHOR = '鼠子';
    const VERSION = '1.0.0';
    const OPTION_NAME = 'zibll_plugin_restapi';

    public function __construct()
    {
        $this->define_constants();
        add_action('zib_require_end', array($this, 'init'));
    }

    /**
     * 定义插件常量
     */
    private function define_constants()
    {
        if (!defined('zibll_plugin_restapi_url')) {
            define('zibll_plugin_restapi_url', plugins_url('', __FILE__));
        }
        if (!defined('zibll_plugin_restapi_path')) {
            define('zibll_plugin_restapi_path', plugin_dir_path(__FILE__));
        }
    }

    /**
     * 获取插件选项的辅助方法
     * 
     * @param string $option  需要获取的选项名称
     * @param mixed $default  默认值(当选项不存在时返回)
     * @return mixed          返回选项值或默认值
     */
    public static function get_option($option = '', $default = null)
    {
        $options = get_option(self::OPTION_NAME);
        return (isset($options[$option])) ? $options[$option] : $default;
    }

    /**
     * 插件初始化
     */
    public function init()
    {
        $require_once = array(
            'Setup.php',
            'Rest/Router.php',
            'Rest/Middleware.php',
            'Rest/Controllers.php',
        );

        foreach ($require_once as $require) {
            require_once plugin_dir_path(__FILE__) . 'core/' . $require;
        }

        // 初始化路由
        new Zibll_RESTAPI_Router();
    }
}

new Zibll_RESTAPI();

if (!function_exists('zibll_plugin_restapi')) {
    function zibll_plugin_restapi($option = '', $default = null)
    {
        return Zibll_RESTAPI::get_option($option, $default);
    }
}
