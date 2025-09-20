<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Enums;

enum Role: string
{
    case ADMIN = "admin";
    case USER = "user";
}