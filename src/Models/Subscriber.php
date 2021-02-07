<?php

namespace Binomedev\Contact\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Subscriber
 * @package Binomedev\Contact\Models
 * @property int $id
 * @property string $email
 * @property string $ip
 * @property  string $agent
 * @property bool $active
 * @property  string $name
 * @property  string $phone
 * @property  string $token
 * @property array $data
 *
 *
 */
class Subscriber extends Model
{
    use Notifiable, HasFactory;

    protected $table = 'contact_subscribers';

    protected $fillable = [
        'email',
        'ip',
        'agent',
        'active',
        'name',
        'phone',
        'token',
        'data',
    ];

    protected $casts = [
        'active' => 'bool',
        'data' => 'array',
    ];

    /**
     * @return User
     */
    public function asUser(): User
    {
        return new User([
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
        ]);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
