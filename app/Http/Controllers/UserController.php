<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

     /**
        * Build the object to display
        *
        * @return Array
    */
    public function build_user($item)
    {

        $rol = '';
        if($item->rol == '1'){
            $rol = 'TÉCNICO';
        }else{
            $rol = 'ADMIN';
        }

        return [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'rol' => $rol,
        ];
    }

    public function build_user_edit($item)
    {
        $items = [];

        if($item->rol == 1){
            $items = $item->itemArray;
        }

        return [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'password' => $item->password,
            'rol' => $item->rol,
            'items' => $items
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];

        $users = User::all();
            
        $output = $users->transform(function($item){
            return $this->build_user($item);
        });

        return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->input();

        $data['password']=bcrypt($data['password']);

        $user = User::create($data);

        if($data['rol'] == 1)
            $user->items()->sync($data['items']);

        return response()->json(true);
    }

    public function getUserByid($id)
    {
        $output = [];

        $user = User::find($id);

        return response()->json($this->build_user_edit($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [];
        $req = $request->input(); 

        $i = User::find($id);

        $i->name = $req['name'];
        $i->email = $req['email'];

        if($i->password != $req['password'])
        {
            $i->password = bcrypt($req['password']);
        }

        $i->save();

        if($req['items'] !== null){

            $items = $req['items'];

            foreach($items as $key => $value ){
                $i->items()->updateExistingPivot( $value['item_id'] , [ 'precio' => $value['precio'] ] );
            }
        }

        return response()->json($this->build_user($i));
    }

}
