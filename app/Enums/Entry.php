<?php

namespace App\Enums;

enum Entry: string
{
    case BATCH = 'batch';
    case CACHE = 'cache';
    case COMMAND = 'command';
    case DUMP = 'dump';
    case EVENT = 'event';
    case EXCEPTION = 'exception';
    case JOB = 'job';
    case LOG = 'log';
    case MAIL = 'mail';
    case MODEL = 'model';
    case NOTIFICATION = 'notification';
    case QUERY = 'query';
    case REQUEST = 'request';
    case SCHEDULED_TASK = 'schedule';
    case GATE = 'gate';
}
