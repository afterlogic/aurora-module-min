<?php
namespace Aurora\Modules\Min\Models;

use Aurora\System\Classes\Model;

class MinHash extends Model
{
	protected $fillable = [
        'HashId',
        'UserId',
        'Hash',
        'Data'
	];
}