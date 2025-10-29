<?php

declare(strict_types=1);

if (!defined('ABSPATH')) exit;

final class TokenValidator
{
    public static function validate(): bool
    {
        $auth_mode = Zibll_RESTAPI::get_option('auth_mode', 'public');
        $key = Zibll_RESTAPI::get_option('key', '5YCS5Y2W54uX5q275YWo5a62');
        
        // 公开模式不需要验证
        if ($auth_mode === 'public') {
            return true;
        }
        
        // 登录验证
        if (in_array($auth_mode, ['login', 'token_login', 'bearer_login']) && !is_user_logged_in()) {
            self::sendErrorResponse('Authentication required', HttpCode::UNAUTHORIZED);
            return false;
        }
        
        // Token/Bearer验证
        if (in_array($auth_mode, ['token', 'bearer', 'token_login', 'bearer_login'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            
            switch ($auth_mode) {
                case 'bearer':
                case 'bearer_login':
                    if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                        self::sendErrorResponse('Missing or invalid Bearer token', HttpCode::UNAUTHORIZED);
                        return false;
                    }
                    if (trim($matches[1]) !== $key) {
                        self::sendErrorResponse('Invalid token', HttpCode::FORBIDDEN);
                        return false;
                    }
                    break;
                    
                case 'token':
                case 'token_login':
                    if (trim($authHeader) !== $key) {
                        self::sendErrorResponse('Invalid token', HttpCode::FORBIDDEN);
                        return false;
                    }
                    break;
            }
        }
        
        return true;
    }

    private static function sendErrorResponse(string $message, HttpCode $code): void
    {
        if (!headers_sent()) {
            status_header($code->value);
            header('Content-Type: application/json; charset=UTF-8');
        }

        wp_send_json([
            'code' => $code->value,
            'message' => $message,
            'timestamp' => time()
        ], $code->value);
    }
}
