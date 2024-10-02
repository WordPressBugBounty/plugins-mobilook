<?php
namespace Pagup\Mobilook\Traits;

trait NotificationHelperTrait
{
    /**
     * Displays a development mode notification.
     *
     * @return string The HTML for the notification.
     */
    public function devNotification(): string
    {
        return '<div class="ep-alert ep-alert--error is-light el-alert el-alert--error" role="alert" style="width: 99%; margin-top: 1rem; font-weight: 700"><i class="ep-icon ep-alert__icon"><svg style="height: 1em; width: 1em;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024"><path fill="currentColor" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896m0 192a58.432 58.432 0 0 0-58.24 63.744l23.36 256.384a35.072 35.072 0 0 0 69.76 0l23.296-256.384A58.432 58.432 0 0 0 512 256m0 512a51.2 51.2 0 1 0 0-102.4 51.2 51.2 0 0 0 0 102.4"></path></svg></i><div class="ep-alert__content"><span class="ep-alert__title">&nbsp;PLUGIN IS RUNNING IN DEVELOPMENT MODE</span></div></div>';
    }
}
