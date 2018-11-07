<?php

namespace App\Http\Controllers;

use App\Items;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];

        $result = Items::all();

        $output = $result->transform(function($item){
            return $this->build_items($item);
        });

        return response()->json($output);
    }

    public function itemsByUser($id)
    {
        $output =[];

        $user = User::find($id);

        foreach ($user->itemArray as $key => $value) {
            $value['cantidad'] = 0;
            $output [] = $value;
        }

        return response()->json($output);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function build_items($item){
        return [
            'item_id' => $item->id,
            'descripcion' => $item->descripcion,
            'cantidad' => 0,
            'precio' => $item->precio,
        ];
    }

}
