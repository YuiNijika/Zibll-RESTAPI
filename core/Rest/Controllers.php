<?php
if (!defined('ABSPATH')) exit;

enum HttpCode: int
{
    case OK = 200;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case INTERNAL_ERROR = 500;
}

class RestController
{
    protected $namespace;

    public function __construct()
    {
        $this->router = Zibll_RESTAPI::get_option('router', 'yuinijika');
        $this->namespace = $this->router . '/';
    }

    /**
     * 注册REST API路由
     */
    public function register_routes()
    {
        // 这个方法将由子类实现
    }

    /**
     * 权限回调
     */
    public function permission_callback($request)
    {
        return TokenValidator::validate();
    }

    /**
     * 成功响应
     */
    protected function success_response($data = [], $message = 'Success')
    {
        return new WP_REST_Response([
            'code' => HttpCode::OK->value,
            'message' => $message,
            'timestamp' => time(),
            'data' => $data
        ], HttpCode::OK->value);
    }

    /**
     * 错误响应
     */
    protected function error_response($message = 'Error', $code = HttpCode::BAD_REQUEST)
    {
        return new WP_REST_Response([
            'code' => $code->value,
            'message' => $message,
            'timestamp' => time()
        ], $code->value);
    }
}

$require_once = array(
    'Post.php',
);

foreach ($require_once as $require) {
    require_once plugin_dir_path(__FILE__) . 'Controller/' . $require;
}
