<?php

namespace App\Models;

use App\Models\Shop\Brand;
use App\Models\Shop\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = ['hash'];

    public function setHashAttribute($value)
    {
        if(null === $value){
            $this->attributes['hash'] = md5(
                $this->country.
                $this->other_country.
                $this->locality.
                $this->other_locality.
                $this->address.
                $this->post_code
            );
        } else {
            $this->attributes['hash'] = $value;
        } 
    }

    public function customers()
    {
        return $this->morphedByMany(Customer::class, 'addressable');
    }

    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'addressable');
    }

    public function getCountryName()
    {
        if($this->country !== 'OTH'){
            $country = Country::where('iso3', $this->country)->first();
            if($country){
                return $country->name;
            }
            
        }

        return $this->other_country;
    }

    public function getLocalityName()
    {
        if($this->locality !== 'OTH'){
            $locality = City::where('code', $this->locality)->first();
            if($locality){
                return $locality->name;
            }
            
        }
        
        return $this->other_locality;
    }

    public function getFullAddressAttribute()
    {
        $address = $this->getCountryName() . ', ' . $this->getLocalityName();
        if(null !== $this->address && !empty($this->address)){
            $address .= ', ' . $this->address;
        }
        if(null !== $this->post_code && !empty($this->post_code)){
            $address .= ', ' . $this->post_code;
        }
        
        return $address;
    }
}