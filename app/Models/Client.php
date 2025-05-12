<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Client extends Authenticatable
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'access_code',
    ];

    /**
     * Set the access code attribute.
     *
     * @param string $value
     * @return void
     */
    public function setAccessCodeAttribute($value)
    {
        $this->attributes['access_code'] = Crypt::encryptString($value);
    }

    /**
     * Get the access code attribute.
     *
     * @param string $value
     * @return string
     */
    public function getAccessCodeAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            Log::error('Error decrypting access code', [
                'error' => $e->getMessage(),
                'client_id' => $this->id
            ]);
            return null;
        }
    }

    public function verifyAccessCode($code)
    {
        try {
            $decrypted = Crypt::decryptString($this->attributes['access_code']);
            Log::info('Access code verification', [
                'client_id' => $this->id,
                'input_length' => strlen($code),
                'decrypted_length' => strlen($decrypted),
                'match' => $decrypted === $code
            ]);
            return $decrypted === $code;
        } catch (\Exception $e) {
            Log::error('Error verifying access code', [
                'error' => $e->getMessage(),
                'client_id' => $this->id
            ]);
            return false;
        }
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->access_code;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Not implemented
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
