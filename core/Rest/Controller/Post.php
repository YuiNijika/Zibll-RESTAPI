<?php

if (!defined('ABSPATH')) exit;

class PostController extends RestController
{
    public function __construct() {
        parent::__construct();
    }
    
    public function register_routes() {
        register_rest_route($this->namespace, '/posts', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_posts'],
                'permission_callback' => [$this, 'permission_callback'],
            ],
        ]);
        
        register_rest_route($this->namespace, '/posts/(?P<id>\d+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_post'],
                'permission_callback' => [$this, 'permission_callback'],
            ],
        ]);
    }
    
    /**
     * 获取文章列表
     */
    public function get_posts($request) {
        // 检查是否启用获取文章功能
        if (!Zibll_RESTAPI::get_option('enable_get_post', true)) {
            return $this->error_response('Post API is disabled', HttpCode::FORBIDDEN);
        }
        
        $params = $request->get_params();
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => isset($params['per_page']) ? intval($params['per_page']) : 10,
            'paged' => isset($params['page']) ? intval($params['page']) : 1,
        ];
        
        $posts = get_posts($args);
        $formatted_posts = [];
        
        foreach ($posts as $post) {
            $formatted_posts[] = $this->format_post($post);
        }
        
        return $this->success_response($formatted_posts, '文章获取成功');
    }
    
    /**
     * 获取单个文章
     */
    public function get_post($request) {
        if (!Zibll_RESTAPI::get_option('enable_get_post', true)) {
            return $this->error_response('Post API is disabled', HttpCode::FORBIDDEN);
        }
        
        $post_id = $request->get_param('id');
        $post = get_post($post_id);
        
        if (!$post || $post->post_status !== 'publish') {
            return $this->error_response('Post not found', HttpCode::NOT_FOUND);
        }
        
        return $this->success_response($this->format_post($post), 'Post retrieved successfully');
    }
    
    /**
     * 格式化文章数据
     */
    private function format_post($post) {
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => apply_filters('the_content', $post->post_content),
            'excerpt' => $post->post_excerpt,
            'date' => $post->post_date,
            'modified' => $post->post_modified,
            'slug' => $post->post_name,
            'author' => get_the_author_meta('display_name', $post->post_author),
            'categories' => wp_get_post_categories($post->ID, ['fields' => 'names']),
            'tags' => wp_get_post_tags($post->ID, ['fields' => 'names']),
        ];
    }
}

// 注册控制器
Zibll_RESTAPI_Router::register_controller('PostController');
