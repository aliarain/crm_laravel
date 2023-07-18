<?php

namespace App\Repositories\Hrm\Leave;

use App\Models\Hrm\Leave\LeaveSetting;

class LeaveSettingRepository
{
    protected LeaveSetting $leaveSetting;

    public function __construct(LeaveSetting $leaveSetting)
    {
        $this->leaveSetting = $leaveSetting;
    }

    public function getLeaveSetting()
    {
        return $this->leaveSetting->query()->first();
    }

    public function settingUpdate($request): bool
    {
        $leaveSetting = $this->leaveSetting->query()->first();
        $leaveSetting->sandwich_leave = $request->sandwich ?? 0;
        $leaveSetting->month = $request->month;
        $leaveSetting->prorate_leave = $request->prorate ?? 0;
        $leaveSetting->save();
        return true;
    }
}