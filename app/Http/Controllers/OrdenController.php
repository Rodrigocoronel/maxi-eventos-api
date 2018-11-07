<?php

namespace App\Http\Controllers;

use App\Orden;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrdenController extends Controller
{
    /**
        * Build the object to display
        *
        * @return Array
    */
    public function build_orden($item)
    {

        $total = 0;
        foreach ($item->itemArray as $key => $value) {
            $total += $value['cantidad'] * $value['precio'];
        }


        return [
            'id' => $item->id,
            'orden_servicio' => $item->orden_servicio,
            'telefono' => $item->telefono,
            'fecha' => $item->fecha,
            'total' => '$'.number_format($total, 2),
            'user' => $item->users->name,
        ];
    }

    public function build_orden_toEdit($item){
        return [
            'id' => $item->id,
            'orden_servicio' => $item->orden_servicio,
            'telefono' => $item->telefono,
            'fecha' => $item->fecha,
            'items' => $item->itemArray, 
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request ,$rol)
    {
        $output = [];
        $ordenes =[];

        $user = $request->user();

        if($rol == 2){
            $ordenes = Orden::orderby('fecha','DESC')->get();
        }
        else{

            $ordenes = Orden::where('user','=',$user->id)->orderby('fecha','DESC' )->get();
        }
        
        $count = 0;
        foreach ($ordenes as $key => $value) {
            $count++;
            break;
        }

        if($count >0 ){

            $output = $ordenes->transform(function($item){

                return $this->build_orden($item);

            }); 
        }
        

        return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $user = $request->user();

        $data = $request->input();

        $data['user'] = $user->id;

        $obj = Orden::create($data);

        $obj->items()->sync($data['items']);

        return response()->json([$data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orden  $orden
     * @return \Illuminate\Http\Response
     */
    public function getOrderByid($id)
    {
        $data = Orden::find($id);

        return response()->json($this->build_orden_toEdit($data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Orden  $orden
     * @return \Illuminate\Http\Response
     */
    public function edit(Orden $orden)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orden  $orden
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [];
        $req = $request->input(); 

        $i = Orden::find($id);

        $i->orden_servicio = $req['orden_servicio'];
        $i->fecha = $req['fecha'];
        $i->telefono = $req['telefono'];
        $i->save();

        if($req['items'] !== null){

            $items = $req['items'];

            foreach($items as $key => $value ){
                $i->items()->updateExistingPivot( $value['item_id'] , [ 'cantidad' => $value['cantidad'] , 'precio' => $value['precio'] ] );
            }
        }

        return response()->json($this->build_orden($i));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orden  $orden
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orden $orden)
    {
        //
    }
}
