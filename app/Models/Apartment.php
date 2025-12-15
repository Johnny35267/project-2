<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'city',
        'street',
        'building_number',
        'rooms',
        'floor',
        'area',
        'has_furnish',
        'price',
        'description',
    ];
      public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
 public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'apartment_id');
    }

public function ratings()
{
    return $this->hasMany(Rating::class, 'apartment_id');
}
  public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

public function images()
{
    return $this->hasMany(ApartmentImage::class);
}
    protected $casts = [
        'rooms' => 'integer',
        'floor' => 'integer',
        'rent'  => 'decimal:2',
    ];

    
}
