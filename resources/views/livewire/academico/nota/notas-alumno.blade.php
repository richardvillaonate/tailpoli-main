<div>
<div class="relative overflow-x-auto mt-5">

@if ($notas->count()>0)

<table class="text-sm text-left text-gray-500 dark:text-gray-400 w-full">

<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">

<tr>

<th class="px-6 py-3">Alumno</th>

<th>Nota 1</th>
<th>Nota 2</th>
<th>Nota 3</th>
<th>Nota 4</th>

<th>Acciones</th>

</tr>

</thead>

<tbody>

@foreach ($notas as $nota)

<tr class="bg-white border-b hover:bg-green-100">

<td class="px-6 py-4 font-medium text-gray-900">
{{$nota->alumno}}
</td>

@for ($i = 1; $i <= 4; $i++)

<td class="p-2">

<div class="flex gap-2">

<input
type="number"
step="any"
min="0"
max="5"
class="w-20 border rounded p-1"
wire:model="calificaciones.{{$nota->id}}.{{$i}}"
placeholder="{{$nota->{'nota'.$i} }}"
>

<button
wire:click="subir({{$nota->id}},{{$i}})"
class="bg-green-500 text-white px-2 py-1 rounded"
>
✔
</button>

</div>

</td>

@endfor

<td class="p-2 text-center">

<span class="font-bold text-blue-600">
{{$nota->acumulado}}
</span>

</td>

</tr>

@endforeach

</tbody>

</table>

@else

<h3 class="text-center text-blue-800 font-semibold text-lg">
No se han registrado calificaciones
</h3>

@endif

</div>
</div>