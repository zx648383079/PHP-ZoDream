<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Adapters;

/**
 *
 * AdapterEvent::Min->name => 'Min'
 * constant("AdapterEvent::Min") => AdapterEvent::Min
 */
enum AdapterEvent {
    /**
     * 接收验证
     */
    case AccessVerification;
    case Subscribe;
    case UnSubscribe;
    case Message;
    case MenuClick;

    public function getEventName(): string {
        return strtolower($this->name);
    }
}