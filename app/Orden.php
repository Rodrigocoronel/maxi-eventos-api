<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'ordenes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['orden_servicio', 'telefono', 'fecha', 'user'];

	/**
	 * Relationship.
	 *
	 * @var array
	*/
    public function items() {
        return $this->belongsToMany(
            'App\Items',
            'item_list',
            'orden_id', 'item_id')
        ->withPivot('cantidad','precio');
    }

    public function users(){
        return $this->belongsTo('App\User', 'user' , 'id');
    }

    public function getItemArrayAttribute(){
        $output = [];

        foreach ($this->items as $item) {

            $output[] = [
                "item_id"           => $item->id,
                "descripcion"       => $item->descripcion,
                "cantidad"          => $item->pivot->cantidad,
                "precio"            => $item->pivot->precio,
            ];
        
        }

        return $output;
    }
}
