<?php

namespace App\Livewire\Academico\Nota;
use App\Exports\GraduacionExport;
use App\Models\Academico\Ciclo;
use App\Models\Academico\Control;
use App\Models\Academico\Nota;
use App\Models\Academico\Curso;
use App\Models\Academico\Grupo;
use App\Models\Academico\Modulo;
use App\Models\Configuracion\Sede;
use App\Models\Configuracion\Estado;
use App\Models\User;
use App\Traits\FiltroTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Notas extends Component
{
    use WithPagination;
    use FiltroTrait;

    public $ordena='id';
    public $ordenado='DESC';
    public $pages = 10;

    public $is_modify = true;
    public $is_creating = false;
    public $is_editing = false;
    public $is_asistencia = false;
    public $act;

    public $filtroinicia=[];
    public $filtroprofesor;
    public $filtrojornada;
    public $filtrocurso;
    public $filtroSede;
    public $filtrocursogrupo=[];
    public $estado_estudiante=[];
    public $filtrociclo;
    public $filtrogrupo;
    public $estudy=[];

    public $elegido;

    public $buscar='';
    public $buscamin='';
    public $sedes=[];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(){
        $this->claseFiltro(20);
         foreach (Auth::user()->sedes as $value) {
        if(!in_array($value->id, $this->sedes)){
            $this->sedes[] = $value->id;
        }
    }
    }

    //Cargar variable
    public function buscaText(){
        $this->resetPage();
        $this->buscamin=strtolower($this->buscar);
    }

    //Limpiar variables
    public function limpiar(){
        $this->reset('buscamin', 'buscar');
        $this->resetPage();
    }

    // Ordenar Registros
    public function organizar($campo)
    {
        if($this->ordenado === 'ASC')
        {
            $this->ordenado = 'DESC';
        }else{
            $this->ordenado = 'ASC';
        }
        return $this->ordena = $campo;
    }

    //Numero de registros
    public function paginas($valor)
    {
        $this->resetPage();
        $this->pages=$valor;
    }

    //Activar evento
    #[On('created')]
    //Mostrar formulario de creación
    public function updatedIsCreating()
    {
        $this->is_modify = !$this->is_modify;
        $this->is_creating = !$this->is_creating;
    }

    //Activar evento
    #[On('Editando')]
    //Mostrar formulario de creación
    public function updatedIsEditing()
    {
        $this->is_modify = !$this->is_modify;
        $this->is_editing = !$this->is_editing;
    }

    //Activar evento
    #[On('cancelando')]
    //Mostrar formulario de creación
    public function cancela()
    {
        $this->reset(
                        'is_modify',
                        'is_creating',
                        'is_editing',
                        //'is_deleting',
                        'is_asistencia'
                    );
    }


    //Activar evento
    #[On('Asistiendo')]
    //Mostrar formulario de creación
    public function updatedIsAsistencia()
    {
        $this->is_modify = !$this->is_modify;
        $this->is_asistencia = !$this->is_asistencia;
    }

    // Mostrar
    public function show($esta){

        $this->elegido=$esta;
        $this->is_modify = !$this->is_modify;
        $this->is_editing=!$this->is_editing;
    }

    // Mostrar
    public function asistencia($esta){

        $this->elegido=$esta;
        $this->is_modify = !$this->is_modify;
        $this->is_asistencia=!$this->is_asistencia;
    }

    private function notas()
    {
        
        return Nota::buscar($this->buscamin)
                        ->profesor($this->filtroprofesor)
                        ->jornada($this->filtrojornada)
                        ->curso($this->filtrocurso)
                        ->orderBy($this->ordena, $this->ordenado)
                        ->paginate($this->pages);
    }

    private function profesores(){
        return User::where('rol_id',5)
                    ->orderBy('name','ASC')
                    ->get();
    }

    private function cursos(){
        return Curso::orderby('name','ASC')
                        ->get();
    }

      private function sedeasignadas(){

        return Sede::whereIn('id',$this->sedes)
                    ->orderBy('name', 'asc')
                    ->get();
    }

    private function grupos(){
    return Grupo::where('inscritos','>',0)
                    ->where('status',true)
                    ->sede($this->filtroSede)
                    ->curso($this->filtrocursogrupo)

                    ->selectRaw('
                        MIN(id) as id,
                        MIN(name) as name,
                        ciclo_id,
                        MIN(jornada) as jornada
                    ')

                    ->groupBy('ciclo_id')

                    ->orderBy('jornada','ASC')
                    ->orderBy('name','ASC')

                    ->get();
}
      private function controles()
    {
        $controles = Control::where('status', true)
                        ->whereIn('sede_id', $this->sedes)
                        ->buscar($this->buscamin)
                        ->sede($this->filtroSede)
                        ->curso($this->filtrocurso)
                        ->inicia($this->filtroinicia)
                        ->status($this->estado_estudiante)
                        ->ciclo($this->filtrociclo)
                        ->profesor($this->filtroprofesor)
                        ->estudiantes($this->estudy)
                        ->orderBy($this->ordena, $this->ordenado)
                        ->paginate($this->pages);

        $this->ciclos();

        return $controles;
    }
     private function status_estu(){
        return DB::table('estados')
                    ->orderBy('name')
                    ->get();

    }

    private function ciclos(){
        $crt=Control::where('status', true)
                    ->whereIn('sede_id', $this->sedes)
                    ->buscar($this->buscamin)
                    ->sede($this->filtroSede)
                    ->curso($this->filtrocurso)
                    ->inicia($this->filtroinicia)
                    ->status($this->estado_estudiante)
                    ->profesor($this->filtroprofesor)
                    ->select('ciclo_id')
                    ->groupBy('ciclo_id')
                    ->get();

        $ids=array();

        foreach ($crt as $value) {
            array_push($ids,$value->ciclo_id);
        }

        return Ciclo::whereIn('id',$ids)
                        ->select('id','name','inicia')
                        ->orderBy('inicia','DESC')
                        ->get();
    }

    private function estados(){
        return Estado::where('status', true)
                        ->orderBy('name', 'ASC')
                        ->get();

    }

        public function updated($property)
    {
        if (str_contains($property, 'filtro')) {
            $this->resetPage();
        }
    }

    public function render()
    {
        return view('livewire.academico.nota.notas',[
            'controles'     =>$this->controles(),
            'estados'       =>$this->estados(),
            'status_estu'   =>$this->status_estu(),
            'ciclos'        =>$this->ciclos(),
            'notas'=>$this->notas(),
            'profesores'=>$this->profesores(),
            'cursos'=>$this->cursos(),
            'asignadas'     =>$this->sedeasignadas(),
            'grupos'        =>$this->grupos()
        ]);
    }
}
