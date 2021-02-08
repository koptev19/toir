<tr class='text-center'>
    <td>{{ $accept->id }}</td>
    <td>{{ $accept->equipment->line_path }}</td>
    <td>
        <input class="form-control" type="text" value=""  readonly><br>
        <a href="#">скопировать ссылку</a>
    </td>         
    <td><a href="#" class="btn btn-primary" target=_blank>Принять</a></td>         
    <th>
        <a href="{{ route('accepts.edit', $accept) }}"><img src="./images/pencil.svg"></a>
        <form action="{{ route('accepts.destroy', $accept) }}" method="POST" onsubmit="return confirm('Удалить?')" class="d-inline">
            @method('delete')
            @csrf
            <button type="submit" class="btn btn-link"><img src="./images/x.svg"></button>
        </form>
    </th>
</tr>
