<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    private $settings;

    public function __construct() {
        parent::__construct();
        $this->settings = CompanySetting::first();
    }

    protected $appends = [
        'user_image_url', 'applied_between_time'
    ];

    public function getUserImageUrlAttribute()
    {
        if (is_null($this->image)) {
            return asset('img/no-image.jpg');
        }
        return asset_url('deal/' . $this->image);
    }


    public function getAppliedBetweenTimeAttribute()
    {
        return $this->open_time.' - '.$this->close_time;
    }

    public function getStartDateAttribute($value)
    {
        $date = new Carbon($value);
        return $date->format('Y-m-d h:i A');
    }

    public function getEndDateAttribute($value)
    {
        $date = new Carbon($value);
        return $date->format('Y-m-d h:i A');
    }

    public function getOpenTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->setTimezone($this->settings->timezone)->format($this->settings->time_format);
    }

    public function getCloseTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->setTimezone($this->settings->timezone)->format($this->settings->time_format);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
}
