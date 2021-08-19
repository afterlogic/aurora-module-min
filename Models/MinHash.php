<?php
namespace Aurora\Modules\Min\Models;

use Aurora\System\Classes\Model;

class MinHash extends Model
{
        protected $table = 'core_min_hashes';
        
        protected $primaryKey = 'HashId';
	protected $foreignModel = 'Aurora\Modules\Core\Models\User';
	protected $foreignModelIdColumn = 'UserId'; // Column that refers to an external table

        protected $fillable = [
                'HashId',
                'UserId',
                'Hash',
                'Data'
        ];
}