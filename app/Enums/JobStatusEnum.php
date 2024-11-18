<?php

namespace App\Enums;

enum JobStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSIG = 'processing';
    case FINISHED = 'finished';
}
