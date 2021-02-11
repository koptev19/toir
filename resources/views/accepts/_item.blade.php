<tr class='text-center'>
    <td>{{ $accept->id }}</td>
    <td>{{ $accept->equipment->line_path }}</td>
    <td>
        <input class="form-control" type="text" value="{{ config('app.url') }}/toir/accept_item.php?id=1" readonly onfocus="this.select(); document.execCommand('copy');">
    </td>         
    <td><a href="/toir/accept_item.php?id=1" class="btn btn-primary" target=_blank>Принять</a></td>         
    <th>
        <a href="{{ route('accepts.edit', $accept) }}"><img src="./images/pencil.svg"></a>
        <form action="{{ route('accepts.destroy', $accept) }}" method="POST" onsubmit="return confirm('Удалить?')" class="d-inline">
            @method('delete')
            @csrf
            <button type="submit" class="btn btn-link"><img src="./images/x.svg"></button>
        </form>
    </th>
</tr>
