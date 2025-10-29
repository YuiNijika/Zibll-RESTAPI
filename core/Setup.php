<?php

if (!defined('ABSPATH')) exit;

// 检查CSF框架是否可用
if (class_exists('CSF')) {
    $prefix = 'zibll_plugin_restapi';

    CSF::createOptions($prefix, array(
        'menu_title' => 'Zibll RESTAPI',
        'menu_slug' => 'zibll_plugin_restapi',
        'framework_title' => 'Zibll RESTAPI <small>v' . Zibll_RESTAPI::VERSION . '</small>',
        'show_in_customizer' => true,
        'footer_text' => '由鼠子开发 · 遵循 MIT License 协议开源',
        'footer_credit' => '<i class="fa fa-heart" style="color:#ff4757"></i> 感谢使用',
        'theme' => 'light',
    ));

    CSF::createSection($prefix, array(
        'id' => 'base_settings',
        'title' => '基础设置',
        'icon' => 'fa fa-cube',
        'fields' => array(
            array(
                'id' => 'enable_restapi',
                'type' => 'switcher',
                'title' => '是否启用RESTAPI',
                'label' => '是否启用RESTAPI接口',
                'default' => true,
            ),
            array(
                'dependency' => array('enable_restapi', '==', '1'),
                'id' => 'router',
                'type' => 'text',
                'class'      => 'compact',
                'title' => ' ',
                'subtitle' => 'RESTAPI路由前缀',
                'default' => 'yuinijika',
                'placeholder' => '输入RESTAPI路由前缀',
            ),
            array(
                'dependency' => array('enable_restapi', '==', '1'),
                'id'         => 'auth_mode',
                'title'      => ' ',
                'subtitle'   => '验证模式',
                'default'    => 'public',
                'class'      => 'compact',
                'inline'     => true,
                'type'       => 'radio',
                'options'    => array(
                    'public'   => '公开',
                    'login'  => '需登录',
                    'token'  => 'Token',
                    'bearer'  => 'Bearer',
                    'token_login'  => 'Token&登录',
                    'bearer_login'  => 'Bearer&登录',
                ),
            ),
            array(
                'dependency' => array('auth_mode', '!=', 'public'),
                'id'         => 'key',
                'type'       => 'text',
                'class'      => 'compact',
                'title'      => ' ',
                'subtitle'   => '访问密钥',
                'default'    => '5YCS5Y2W54uX5q275YWo5a62',
                'placeholder' => '请求Key',
            ),
        )
    ));

    CSF::createSection($prefix, array(
        'id' => 'core_settings',
        'title' => '核心功能',
        'icon' => 'fa fa-cube',
        'fields' => array(
            array(
                'id' => 'enable_get_post',
                'type' => 'switcher',
                'title' => '获取文章',
                'label' => '是否开启获取文章接口',
                'default' => true,
            ),
            array(
                'dependency' => array('enable_get_post', '!=', '', '', 'visible'),
                'title'      => ' ',
                'subtitle'   => '勾选允许获取文章详情的场景',
                'id'         => 'enable_get_post_detail',
                'desc'       => '选择在哪些场景下允许获取文章详情',
                'help'       => '虽然可以使用WordPress自带的RESTAPI接口获取文章内容',
                'class'      => 'compact',
                'inline'     => true,
                'type'       => 'checkbox',
                'options'    => array(
                    '1' => '文章列表',
                    '2' => '文章详情',
                ),
                'default'    => array('1'),
            ),
        )
    ));
}
