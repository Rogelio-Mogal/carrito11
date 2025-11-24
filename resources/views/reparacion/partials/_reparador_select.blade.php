<select id="reparador-{{ $reparacion->id }}"
        data-id="{{ $reparacion->id }}"
        class="asignar-reparador bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
    <option value="">-- Seleccionar --</option>
    @foreach($reparadores as $rep)
        <option value="{{ $rep->id }}" @selected($reparacion->reparador_id == $rep->id)>
            {{ $rep->name }}
        </option>
    @endforeach
</select>