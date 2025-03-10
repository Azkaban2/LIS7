<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_request_id',
        'user_id',
        'results',
        'pdf_file',
        'pdf_file_path', 
        'time_released',
        'date_released',
    ];

    protected $casts = [
        'results' => 'array', 
    ];

    // Default attributes for the model
    protected $attributes = [
        'pdf_file_path' => '', 
    ];

    // Relationship with the OrderRequest model
    public function orderRequest()
    {
        return $this->belongsTo(OrderRequest::class, 'order_request_id');
    }

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor for the PDF file URL
    public function getPdfFileUrlAttribute()
    {
        return $this->pdf_file ? asset('storage/' . $this->pdf_file) : null;
    }

    // Mutator for the machine field
    public function setMachineAttribute($value)
    {
        $this->attributes['machine'] = strtoupper($value);
    }

    // Scope for filtering results by machine
    public function scopeByMachine($query, $machine)
    {
        return $query->where('machine', $machine);
    }
}

