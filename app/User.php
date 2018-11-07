<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'rol' ,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    /**
     * Relationship.
     *
     * @var array
    */
    public function items() {
        return $this->belongsToMany(
            'App\Items',
            'user_item_list',
            'user_id', 'item_id')
        ->withPivot('precio');
    }

    public function getItemArrayAttribute(){
        $output = [];

        foreach ($this->items as $item) {

            $output[] = [
                "item_id"           => $item->id,
                "descripcion"       => $item->descripcion,
                "precio"            => $item->pivot->precio,
            ];
        
        }

        return $output;
    }
}
