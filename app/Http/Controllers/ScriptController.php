<?php

namespace App\Http\Controllers;

use App\VehUsaDev;
use App\HoraSincronizacion;
use Illuminate\Http\Request;
use Mail;

class ScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($token)
    {
        if($token == "9A18657AB16BDF182548DB1F1FACF"){
            //echo "Hola";
            /* Crear nueva tabla "historial_sincronizaciones", esta debe almacenar la hora de comprobación y el tiempo que ha pasado entre la última sincronización y la hora de comprobación*/
            // Script de hora de registro con diferencia máxima de actualización de 2 horas
            $fecha_hora = date("Y-m-d H:i:s");
            
            $veh_usados_dev = VehUsaDev::orderBy("hora_registro", "DESC")->first();
            $ultima_fecha_hora_vehiculo = $veh_usados_dev['hora_registro'];
            $s_ultima_fecha_hora_vehiculo = strtotime($ultima_fecha_hora_vehiculo);

            $s_fecha_hora = strtotime($fecha_hora); // Convertir a segundos

            $diferencia = $s_fecha_hora - $s_ultima_fecha_hora_vehiculo; // Diferencia de fechas en segundos
            $diferencia_formato = (date("Y", $diferencia)-1970)."-".(date("m", $diferencia)-1)."-".(date("d", $diferencia)-1)." ". date("H:i:s", $diferencia); // Formato para distinguir años, meses, días, horas, minutos y segundos de diferencia de la hora actual con la última sincronización

            $nueva_verificacion = new HoraSincronizacion;
            $table = [];

            $nueva_verificacion->hora_verificacion = $fecha_hora;
            $nueva_verificacion->hora_ultima_actualizacion = $ultima_fecha_hora_vehiculo;
            $nueva_verificacion->hora_diferencia_dev = date("Y-m-d H:i:s", $diferencia);
            $nueva_verificacion->hora_diferencia = $diferencia_formato;

            $table['hora_verificacion'] = $fecha_hora;
            $table['hora_ultima_actualizacion'] = $ultima_fecha_hora_vehiculo;
            $table['hora_diferencia_dev'] = date("Y-m-d H:i:s", $diferencia);
            $table['hora_diferencia'] = $diferencia_formato;

            if($s_fecha_hora >= $s_ultima_fecha_hora_vehiculo + 7200){
                $nueva_verificacion->sincronizada = false;
                $nueva_verificacion->save();

                //Enviar notificación al correo
                $email = 'pmanriquez.kti@gmail.com';
                
                Mail::send('emails.hora_sincronizacion', $table, function($msj) use ($email){
                    $msj->subject('Soporte KTI');
                    $msj->bcc('nsilva@kti.cl');
                    $msj->to(
                        [
                            'yoeltorres@sergioescobar.cl',
                            'egnenzerene@sergioescobar.cl',
                            'tpacheco@kti.cl',
                            'gdomke@kti.cl'
                        ]
                    );			
                    $msj->cc($email);
                });
                
                echo "No sincronizada";
                return "No sincronizada";
            }
            $nueva_verificacion->sincronizada = true;
            $nueva_verificacion->save();
            echo "Sincronizada";
            return "Sincronizada";
        }
        echo "Permiso denegado";
        return "Permiso denegado";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\VehUsaDev  $vehUsaDev
     * @return \Illuminate\Http\Response
     */
    public function show(VehUsaDev $vehUsaDev)
    {
        //
        echo "Bienvenido";
        return true;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VehUsaDev  $vehUsaDev
     * @return \Illuminate\Http\Response
     */
    public function edit(VehUsaDev $vehUsaDev)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VehUsaDev  $vehUsaDev
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehUsaDev $vehUsaDev)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VehUsaDev  $vehUsaDev
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehUsaDev $vehUsaDev)
    {
        //
    }
}
