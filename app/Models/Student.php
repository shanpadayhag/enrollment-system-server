<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'first_name',
        'middle_name',
        'last_name',
        'address_line_1',
        'address_line_2',
        'city',
        'province',
        'sex',
        'nationality',
        'guardian',
        'guardian_number',
    ];

    /**
     * @return HasOne
     */
    public function enrolledStudent(): HasOne
    {
        return $this->hasOne(EnrolledStudent::class);
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::deleted(function ($student) {
            $student->enrolledStudent->delete();
        });
    }
}
