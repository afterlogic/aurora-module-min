<?php
namespace Aurora\Modules\Min\Models;

use Aurora\System\Classes\Model;
use Aurora\Modules\Core\Models\User;

class MinHash extends Model
{
        protected $table = 'core_min_hashes';
        
        protected $primaryKey = 'HashId';
        public $incrementing = false;
	protected $foreignModel = User::class;
	protected $foreignModelIdColumn = 'UserId'; // Column that refers to an external table

        protected $fillable = [
                'HashId',
                'UserId',
                'Hash',
                'Data'
        ];
}