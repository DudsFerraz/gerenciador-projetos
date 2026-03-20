<?php

namespace App\Enums;

enum MeetingStatus: string {
    case SCHEDULED = 'SCHEDULED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case FINISHED = 'FINISHED';
}