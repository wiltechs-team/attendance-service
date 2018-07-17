<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 22:39
 */
namespace Wiltechsteam\FoundationServiceSingle\Listeners;

use Wiltechsteam\FoundationServiceSingle\Cores\AttendanceCore;
use Wiltechsteam\FoundationServiceSingle\Events\WorkTimeCheckedInOutEvent;
use Wiltechsteam\FoundationServiceSingle\Models\AttendanceDayPrint;
use Wiltechsteam\FoundationServiceSingle\Models\Staff;

class WorkTimeCheckedInOutListener
{
    public function handle(WorkTimeCheckedInOutEvent $event)
    {
        $data = $event->data;

        $oStaff = Staff::find($data['staffID']);

        if(empty($oStaff))
        {
            $oStaff = Staff::find(strtoupper($data['staffID']));
        }

        $attendanceData = AttendanceCore::CnToPrintModel($oStaff,$data);

        AttendanceDayPrint::create($attendanceData);
    }
}