<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 23:21
 */
namespace Wiltechsteam\FoundationServiceSingle\Cores;

use Ramsey\Uuid\Uuid;
use Wiltechsteam\FoundationServiceSingle\Models\Staff;

class AttendanceCore
{

    public static function CnToPrintModel($oStaff,$aDataIn)
    {
        if($aDataIn['workTimeType'] == 4){
            $aDataIn['description'] = $aDataIn['inOutType'] == 2 ? "早餐签入" : "早餐签出";
        }else if($aDataIn['workTimeType'] == 5){
            $aDataIn['description'] = $aDataIn['inOutType'] == 2 ? "午餐签入" : "午餐签出";
        }else if ($aDataIn['workTimeType'] == 6){
            $aDataIn['description'] = $aDataIn['inOutType'] == 2 ? "夜宵签入" : "夜宵签出";
        }

        $type =$aDataIn['inOutType'] == 2 ? 0 : 1;

        $status = 0;

        if($aDataIn['workTimeType'] == 4 and $type == 0){
            $status = 3;//如果是早餐，并且是签入，
        } else if($aDataIn['workTimeType'] == 4 and $type == 1){
            $status = 4;//如果是早餐，并且是签出，
        } else if($aDataIn['workTimeType'] == 5 and $type == 0){
            $status = 5;//如果是午餐，并且是签入，
        } else if($aDataIn['workTimeType'] == 5 and $type == 1){
            $status = 6;//如果是午餐，并且是签出，
        } else if($aDataIn['workTimeType'] == 6 and $type == 0){
            $status = 7;//如果是夜宵，并且是签入，
        } else if($aDataIn['workTimeType'] == 6 and $type == 1){
            $status = 8;//如果是夜宵，并且是签出，
        }

        return [
            "id"    =>  Uuid::uuid1()->toString(),
            'date'  =>  date("Y-m-d H:i:s",strtotime($aDataIn['inOutDate'])),
            'type'  =>   $type,
            "status"    =>  $status,
            'staff_id'  =>  $aDataIn['staffID'],
            "comment"   =>  empty($aDataIn['description'])?null:$aDataIn['description'],
            'work_time_type'  =>  $aDataIn['workTimeType'],
            "print_number"  => $oStaff->finger_print_number,
            "source"    =>  2,
            "created_by"    =>  null,
            "created_at"    =>  date("Y-m-d H:i:s")
        ];
    }

}