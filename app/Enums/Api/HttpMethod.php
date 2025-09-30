<?php
declare(strict_types=1);

namespace App\Enums\Api;

/**
 * HttpMethod Enum
 */
enum HttpMethod: string
{
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case DELETE = 'delete';
}
