<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 23:36
 */

namespace Wiltechsteam\FoundationServiceSingle\Models;


use Illuminate\Database\Eloquent\Model;

class AttendanceDayPrint extends Model
{
    protected $table = "attendance_day_print";

    protected $keyType = "string";

    public $incrementing = false;

    protected $guarded = [];
}