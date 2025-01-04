<?php
namespace App\Enums;

enum TaskStatusEnum: string {
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case BLOCKED = 'blocked';
    case IN_REVIEW = 'in_review';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function toSelectArray(): array
    {
        return [
            self::PENDING->value => 'Pending',
            self::IN_PROGRESS->value => 'In Progress',
            self::BLOCKED->value => 'Blocked',
            self::IN_REVIEW->value => 'In Review',
            self::COMPLETED->value => 'Completed',
            self::CANCELLED->value => 'Cancelled',
        ];
    }

}

