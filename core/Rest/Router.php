<?php 

if (!defined('ABSPATH')) exit;

class Zibll_RESTAPI_Router
{
    private static $controllers = [];
    
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
    
    /**
     * 自动注册控制器
     */
    public static function register_controller($controller_class) {
        self::$controllers[] = $controller_class;
    }
    
    /**
     * 获取所有控制器
     */
    public static function get_controllers() {
        return self::$controllers;
    }
    
    /**
     * 注册所有路由
     */
    public function register_routes() {
        // 检查是否启用RESTAPI
        if (!Zibll_RESTAPI::get_option('enable_restapi', true)) {
            return;
        }
        
        foreach (self::$controllers as $controller_class) {
            if (class_exists($controller_class)) {
                $controller = new $controller_class();
                if (method_exists($controller, 'register_routes')) {
                    $controller->register_routes();
                }
            }
        }
    }
}
