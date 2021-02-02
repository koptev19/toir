<div class="mt-3 text-center mb-2">
    <a href="#" class="link-dark me-3"><i class="fas fa-print"></i></a>
    <span class="h3 me-3">График ТОиР</span>
</div>
<div class="my-2 text-center">
    <a>Декабрь 2020</a>
</div>

<table class="table table-sm table-bordered">
    <thead>
        <tr class="text-center">
            <th rowspan="3">Линия</th>
            @for($d = 1; $d <= 31; $d++)
                <td class="" width="3%">{{ $d }}</td>
            @endfor
        </tr>
        <tr class="text-center">
            @for($d = 1; $d <= 31; $d++)
                <td class="">&nbsp;</td>
            @endfor
        </tr>
    </thead>

    <tbody>
	    <tr class="text-center">
            <td class="text-start"><a href="#" target="_blank">Главная линия</a></td>
            @for($d = 1; $d <= 31; $d++)
                <td class="">&nbsp;</td>
            @endfor
        </tr>
    </tbody>
</table>