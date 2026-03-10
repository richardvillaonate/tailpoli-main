<?php

namespace App\Livewire\Academico\Nota;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotasAlumno extends Component
{
    public $notas;
    public $actual;

    // guardaremos notas por alumno y número de nota
    public $calificaciones = [];

    public function mount($actual = null)
    {
        $this->actual = $actual;

        $this->registroNotas();
    }

    public function registroNotas()
    {
        $this->notas = DB::table('notas_detalle')
            ->where('nota_id', $this->actual->id)
            ->orderBy('alumno')
            ->get();
    }

  

    public function subir($id, $numero)
{
    $notaCampo = "nota" . $numero;
    $porcenCampo = "porcen" . $numero;

    $nota = $this->calificaciones[$id][$numero] ?? null;

    if ($nota === null) {
        $this->dispatch('alerta', name: 'Debe registrar nota.');
        return;
    }

    if ($nota < 0 || $nota > 5) {
        $this->dispatch('alerta', name: 'La calificación debe estar entre 0 y 5');
        return;
    }

    $item = DB::table('notas_detalle')
        ->where('id', $id)
        ->first();

    // porcentaje configurado para la actividad
    $porcentajeActividad = $this->actual->$porcenCampo ?? 0;

    $porcentaje = round(($nota * $porcentajeActividad) / 100, 2);

    $observaciones = now() . " " . Auth::user()->name .
        " cargó nota {$numero}: {$nota} --- " . ($item->observaciones ?? '');

    DB::table('notas_detalle')
        ->where('id', $id)
        ->update([
            $notaCampo => $nota,
            $porcenCampo => $porcentaje,
            'observaciones' => $observaciones,
        ]);

    $this->promedio($id);

    $this->registroNotas();

    $this->dispatch('alerta', name: "Nota {$numero} guardada correctamente ✔");
}


public function promedio($id)
{
    $item = DB::table('notas_detalle')
        ->where('id', $id)
        ->first();

    $total = 0;
    $contador = 0;

    for ($i = 1; $i <= 10; $i++) {

        $campo = "nota".$i;

        if (!is_null($item->$campo)) {
            $total += $item->$campo;
            $contador++;
        }
    }

    $promedio = $contador > 0 ? round($total / $contador, 2) : 0;

    DB::table('notas_detalle')
        ->where('id', $id)
        ->update([
            'acumulado' => $promedio
        ]);
}

    public function render()
    {
        return view('livewire.academico.nota.notas-alumno');
    }
}