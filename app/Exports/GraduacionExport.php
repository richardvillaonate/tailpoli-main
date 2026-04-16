<?php

namespace App\Exports;

use App\Models\Academico\Control;
use App\Models\Configuracion\Estado;
use App\Models\Academico\Matricula;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GraduacionExport implements FromCollection, WithCustomStartCell, Responsable, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize, WithDrawings, WithStyles
{
    use Exportable;

    private $buscamin;
    private $filtroSede;
    private $filtrocurso;
    private $filtroinicia;
    private $filtrogrado;
    private $estado_estudiante;
    private $estados;
    private $status;
    private $estadoestudiante=[];
    private $filtroprofesor;
    private $filtrociclo;
    private $filtrodeser;
    private $fileName = "Graduaciones.xlsx";
    private $writerType = \Maatwebsite\Excel\Excel::XLSX;
    private $filtrogrupo;

    public function __construct(
                                    $buscamin,
                                    $filtroSede,
                                    $filtrocurso,
                                    $filtroinicia,
                                    $filtrogrado,
                                    $estado_estudiante,
                                    $filtrociclo,
                                    $filtroprofesor,
                                    $filtrodeser,
                                    $filtrogrupo,
                                )
    {
        $this->buscamin=$buscamin;
        $this->filtroSede=$filtroSede;
        $this->filtrocurso=$filtrocurso;
        $this->filtroinicia=$filtroinicia;
        $this->filtrogrado=$filtrogrado;
        $this->estado_estudiante=$estado_estudiante;
        $this->filtrociclo=$filtrociclo;
        $this->filtroprofesor=$filtroprofesor;
        $this->filtrodeser=$filtrodeser;
        $this->filtrogrupo=$filtrogrupo;

        $estados=Estado::orderBy('id','ASC')->get();

        foreach ($estados as $value) {
            array_push($this->estadoestudiante,$value->name);
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function collection()
{
    return Control::whereNotIn('status_est',[11])
        ->selectRaw('controls.*, DATEDIFF(CURDATE(), ultima_asistencia) as dias_pasados')
       ->with([
            'estudiante.perfil',
            'matricula.carteras', 
            'matricula.configPago',
            'ciclo'
        ])
        ->buscar($this->buscamin)
        ->desert($this->filtrodeser)
        ->sede($this->filtroSede)
        ->curso($this->filtrocurso)
        ->inicia($this->filtroinicia)
        ->grado($this->filtrogrado)
        ->status($this->estado_estudiante)
        ->ciclo($this->filtrociclo)
        ->profesor($this->filtroprofesor)
        ->grupo($this->filtrogrupo) // 🔥 ESTE ES EL QUE TE FALTABA
        ->orderBy('fecha_grado', 'DESC')
        ->get();
}


    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
        {
            return [
                'Estudiante',
                'Documento',
                'correo electrónico',
                'celular',
                'Matricula',
                'Metodo de Pago', 
                'Fecha Matricula',
                'Fecha Inicio',
                'Fecha Finaliza',
                'Fecha Grado',
                'Programación',
                'Último Pago',
                'Concepto Último Pago',
                'Próximo Pago',
                'Última Asistencia',
                'Días desde última asistencia',
                'Cuotas pendientes',
                'Cantidad cuotas pendientes', 
                'Mora',
                'Kit',
                'Diploma',
                'Ceremonia',
                'Estatus Estudiante',
            ];
        }

    private function metodoPago($matriculaId)
{
    if (!$matriculaId) return null;

    return Matricula::with('configPago')
        ->where('id', $matriculaId)
        ->first()?->configPago?->descripcion;
}

    public function map($graduacion): array
{
    $id = $graduacion->status_est - 1;

    $celular = 0;
    if ($graduacion->estudiante->perfil) {
        $celular = $graduacion->estudiante->perfil->celular;
    }

    // 🔥 MÉTODO DE PAGO (usando tu función)
    $metodoPago = $this->metodoPago($graduacion->matricula->id ?? null) ?? 'N/A';

    $hoy = now();

    $carteras = collect();

    // ✅ Validar existencia de matrícula y carteras
    if ($graduacion->matricula && $graduacion->matricula->carteras) {
        $carteras = $graduacion->matricula->carteras
            ->where('estado_cartera_id', '<', 5) // pendientes
            ->sortBy('fecha_pago')
            ->values();
    }

    // ✅ 1. PRÓXIMA CUOTA PENDIENTE (la primera real)
    $proxima = $carteras->first();
    $proximaFecha = optional($proxima)->fecha_pago;

    // ✅ 2. TODAS LAS CUOTAS PENDIENTES CONCATENADAS
    $cuotasPendientes = $carteras
        ->pluck('fecha_pago')
        ->map(function ($fecha) {
            return \Carbon\Carbon::parse($fecha)->format('Y-m-d');
        })
        ->implode(' | ');

    // ✅ 3. CANTIDAD DE CUOTAS PENDIENTES
    $cantidadCuotasPendientes = $carteras->count();

    // ✅ 4. ÚLTIMO PAGO + CONCEPTO
    $ultimoPago = null;
    $conceptoUltimoPago = null;

    if ($graduacion->matricula && $graduacion->matricula->carteras) {
        $ultimo = $graduacion->matricula->carteras
            ->where('estado_cartera_id', '>=', 5) // pagadas
            ->sortByDesc('updated_at')
            ->first();

        if ($ultimo) {
            $ultimoPago = $ultimo->fecha_pago;
            $conceptoUltimoPago = $ultimo->concepto ?? 'N/A';
        }
    }

    return [
        $graduacion->estudiante->name,
        $graduacion->estudiante->documento,
        $graduacion->estudiante->email,
        $celular,
        $graduacion->matricula->id,
        $metodoPago, // 🔥 NUEVA COLUMNA
        $graduacion->matricula->created_at,
        $graduacion->inicia,
        $graduacion->ciclo->finaliza,
        $graduacion->fecha_grado,
        $graduacion->ciclo->name,
        $ultimoPago,
        $conceptoUltimoPago,
        $proximaFecha,
        $graduacion->ultima_asistencia,
        $graduacion->dias_pasados,
        $cuotasPendientes,
        $cantidadCuotasPendientes,
        $graduacion->mora,
        $graduacion->overol,
        $graduacion->diploma,
        $graduacion->ceremonia,
        $this->estadoestudiante[$id],
    ];
}


        public function old_map($graduacion): array
{
    $id = $graduacion->status_est - 1;

    $celular = 0;
    if ($graduacion->estudiante->perfil) {
        $celular = $graduacion->estudiante->perfil->celular;
    }

    $hoy = now();

    $carteras = collect();

    // ✅ Validar existencia de matrícula y carteras
    if ($graduacion->matricula && $graduacion->matricula->carteras) {
        $carteras = $graduacion->matricula->carteras
            ->where('estado_cartera_id', '<', 5) // pendientes
            ->sortBy('fecha_pago')
            ->values();
    }

    // ✅ 1. PRÓXIMA CUOTA PENDIENTE (la primera real)
    $proxima = $carteras->first();
    $proximaFecha = optional($proxima)->fecha_pago;

    // ✅ 2. TODAS LAS CUOTAS PENDIENTES CONCATENADAS
    $cuotasPendientes = $carteras
        ->pluck('fecha_pago')
        ->map(function ($fecha) {
            return \Carbon\Carbon::parse($fecha)->format('Y-m-d');
        })
        ->implode(' | ');

    // ✅ 3. CANTIDAD DE CUOTAS PENDIENTES
    $cantidadCuotasPendientes = $carteras->count();

    // ✅ 4. ÚLTIMO PAGO + CONCEPTO
    $ultimoPago = null;
    $conceptoUltimoPago = null;

    if ($graduacion->matricula && $graduacion->matricula->carteras) {
        $ultimo = $graduacion->matricula->carteras
            ->where('estado_cartera_id', '>=', 5) // pagadas
            ->sortByDesc('updated_at')
            ->first();

        if ($ultimo) {
            $ultimoPago = $ultimo->fecha_pago;
            $conceptoUltimoPago = $ultimo->concepto ?? 'N/A'; // ⚠️ ajusta si el campo es otro
        }
    }

    return [
        $graduacion->estudiante->name,
        $graduacion->estudiante->documento,
        $graduacion->estudiante->email,
        $celular,
        $graduacion->matricula->id,
        $graduacion->matricula->metodoPago,
        $graduacion->matricula->created_at,
        $graduacion->inicia,
        $graduacion->ciclo->finaliza,
        $graduacion->fecha_grado,
        $graduacion->ciclo->name,
        $ultimoPago,
        $conceptoUltimoPago,
        $proximaFecha,
        $graduacion->ultima_asistencia,
        $graduacion->dias_pasados,
        $cuotasPendientes,
        $cantidadCuotasPendientes,
        $graduacion->mora,
        $graduacion->overol,
        $graduacion->diploma,
        $graduacion->ceremonia,
        $this->estadoestudiante[$id],
    ];
}

    public function map11($graduacion): array
    {
        $id=$graduacion->status_est-1;

        $celular=0;

        if($graduacion->estudiante->perfil){
            $celular=$graduacion->estudiante->perfil->celular;
        }


                $hoy = now();

        $carteras = collect();

        // validar que exista matricula
        if ($graduacion->matricula && $graduacion->matricula->carteras) {
            $carteras = $graduacion->matricula->carteras
                ->where('estado_cartera_id', '<', 5)
                ->sortBy('fecha_pago');
        }

        // próxima cuota (no vencida)
        $proxima = $carteras
            ->filter(function ($item) use ($hoy) {
                return $item->fecha_pago >= $hoy;
            })
            ->first();

        $proximaFecha = optional($proxima)->fecha_pago;

        return [
            $graduacion->estudiante->name,
            $graduacion->estudiante->documento,
            $graduacion->estudiante->email,
            $celular,
            $graduacion->matricula->id,
            $graduacion->matricula->created_at,
            $graduacion->inicia,
            $graduacion->ciclo->finaliza,
            $graduacion->fecha_grado,
            $graduacion->ciclo->name,
            $graduacion->ultimo_pago,
            $proximaFecha,
            $graduacion->ultima_asistencia,
            $graduacion->dias_pasados,
            $graduacion->mora,
            $graduacion->overol,
            $graduacion->diploma,
            $graduacion->ceremonia,
            $this->estadoestudiante[$id],
        ];

    }

        public function columnFormats(): array
    {
        return [
            'G' => 'dd/mm/yyyy', // Fecha Matrícula
            'H' => 'dd/mm/yyyy', // Fecha Inicio
            'I' => 'dd/mm/yyyy', // Fecha Finaliza
            'J' => 'dd/mm/yyyy', // Fecha Grado
            'L' => 'dd/mm/yyyy', // Último Pago
            'N' => 'dd/mm/yyyy', // Próximo Pago
            'O' => 'dd/mm/yyyy', // Última Asistencia
        ];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('PoliAndino');
        $drawing->setDescription('PoliAndino');
        $drawing->setPath(public_path('img/logo.jpeg'));
        $drawing->setHeight(70);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Graduaciones');
        $sheet->setCellValue('C2', 'LISTADO DE ALUMNOS CONTROL A: '.now());
        $sheet->mergeCells('C2:G2');
    }
}
