<td class="px-3 py-2 md:px-6 md:py-4 whitespace-no-wrap text-sm leading-5 flex flex-col items-start justify-items-center space-y-1">
    @if($row->id == 1)
        @dd($row)
    @endif
    @if (count($row->roles))
        @foreach($row->roles as $role)
            <span>{{ $role->name }}</span>
        @endforeach
    @else
        <span>consulta</span>
    @endif
</td>
