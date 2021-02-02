<div class="mb-5">
    <h3 data-bs-toggle="collapse" href="#table1" role="button" aria-expanded="false" aria-controls="table1-link">
        Коэффициенты ТОиР
        <img src='images/chevron-up.svg' class="ml-2" id="table1-up">
        <img src='images/chevron-down.svg' class="ml-2" id="table1-down" style="display:none;">
    </h3>
    <div class="collapse show" id="table1">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2"></th>
                        <th colspan="2">По компании</th>
                        <th colspan="2">Цех 1</th>
                        <th colspan="2">Цех 2</th>
                        <th colspan="2">Цех 3</th>
                    </tr>
                    <tr class="text-center">
                        <th>План</th>
                        <th>Факт</th>
                        <th>План</th>
                        <th>Факт</th>
                        <th>План</th>
                        <th>Факт</th>
                        <th>План</th>
                        <th>Факт</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="text-left">Коэффициент ремонтных работ</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Коэффициент аварий по оборудованию</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Коэффициент загрузки оборудования</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$('#table1').on('show.bs.collapse', function () {
    $('#table1-up').show();
    $('#table1-down').hide();
})

$('#table1').on('hide.bs.collapse', function () {
    $('#table1-up').hide();
    $('#table1-down').show();
})
</script>