<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 23:23
 */

namespace Wiltechsteam\FoundationServiceSingle\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $keyType = "string";

    public $incrementing = false;

    protected $guarded = [];
}