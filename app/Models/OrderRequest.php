<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'patient_id',
        'birthday',
        'age',
        'gender',
        'date_performed',
        'date_released',
        'programs',
        'order',
        'sample_type',
        'sample_container',
        'collection_date',
        'test_code',
        'medtech_full_name',
        'medtech_lic_no',
        'pathologist_full_name',
        'pathologist_lic_no',
        'physician_full_name',
    ];

    protected $casts = [
        'programs' => 'array',
        'order' => 'array',
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
