<?php

namespace App\Exports;

use App\Models\Academico\Control;
use App\Models\Configuracion\Estado;
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
            'Fecha Matricula',
            'Fecha Inicio',
            'Fecha Finaliza',
            'Fecha Grado',
            'Programación',
            'Último Pago',
            'Próximo Pago',
            'Última Asistencia',
            'Días desde última asistencia',
            'Mora',
            'Kit',
            'Diploma',
            'Ceremonia',
            'Estatus Estudiante',
        ];
    }

    public function map($graduacion): array
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
            'F' => 'dd/mm/yyyy',
            'G' => 'dd/mm/yyyy',
            'H' => 'dd/mm/yyyy',
            'j' => 'dd/mm/yyyy',
            'K' => 'dd/mm/yyyy',
            'L' => 'dd/mm/yyyy',
            'M' => 'dd/mm/yyyy',
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
