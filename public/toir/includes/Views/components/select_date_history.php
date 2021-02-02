<?php
/**
 * @param string $fieldName
 * @param int $months
 * @param int $lineId
 */

$fieldName = empty($fieldName) ? 'date' : $fieldName;
$months = empty($months) ? 2 : $months;
$lineId = empty($lineId) ? 0 : (int)$lineId;

$date = currentMonth();
for($i = 1; $i < $months; $i++) {
    $date = prevMonth($date);
}
?>

<script>
var months = [];
var years = [];
</script>

<?php
for($i = 1; $i <= $months; $i++) {    
    $m = $date['m'];
    $y = $date['Y'];
    $t = (int)date('t', mktime(0, 0, 0, $m, 1, $y));
    ?>
    <h4 class='text-center'><?php echo monthName($m) ?></h4>
    <table class='table table-bordered table-sm table-date' data-date="<?php echo $y . '-' . $m; ?>">
        <thead>
            <tr class="text-center">
                <?php for($d = 1; $d <= $t; $d++) { 
                    $class = classForHistoryDate($d, $m, $y);
                    $cellDate = $class == 'table-secondary' ? '' : 'data-date="' . $y . '-' . ($m < 10 ? '0' : '') . $m . ($d < 10 ? '0' : '') . $d . '"';
                    $weekend = isWeekend($d, $m, $y) ? 'data-weekend="1"' : '';
                    ?>
                    <th class="<?php echo $class; ?>" <?php echo $cellDate; ?> <?php echo $weekend; ?>><?php echo $d; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <?php for($d = 1; $d <= $t; $d++) { 
                    $class = classForHistoryDate($d, $m, $y);
                    $style = $class == 'table-secondary' ? '' : 'cursor:pointer;';
                    $cellDate = $class == 'table-secondary' ? '' : 'data-date="' . $y . '-' . ($m < 10 ? '0' : '') . $m . ($d < 10 ? '0' : '') . $d . '"';
                    $onclick = $class == 'table-secondary' ? '' : 'onclick="selectDate(this)"';
                    $weekend = isWeekend($d, $m, $y) ? 'data-weekend="1"' : '';
                    ?>
                    <td class="<?php echo $class; ?>" style="<?php echo $style; ?>" <?php echo $cellDate; ?> <?php echo $onclick; ?> <?php echo $weekend; ?>>&nbsp;</td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
    <script>
    months.push(<?php echo $m; ?>);
    years.push(<?php echo $y; ?>);
    </script>

    <?
    $date = nextMonth($date);
}


?>

<input type="hidden" name="<?php echo $fieldName; ?>" value="" id="<?php echo $fieldName; ?>">

<script>
var lineId = <?php echo $lineId?>;

function selectDate(cell)
{
    $('#<?php echo $fieldName; ?>').val($(cell).data('date'));
    $('.table-date td, .table-date th').not('.table-secondary').each(function(key, item) {
        $(item).removeClass('table-success');
        if($(item).data('weekend') == '1') {
            $(item).addClass('table-danger');
        }
        if($(item).data('date') == $(cell).data('date')) {
            $(item).removeClass('table-danger');
            $(item).addClass('table-success');
        }
    });
}

function showStopDateLine()
{
    if (Number(lineId) > 0) {
        for (i = 0; i < months.length; i++) {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    action: 'getStopDates',
                    line: lineId,
                    month: months[i],
                    year: years[i],
                },
                dataType :'json',
                success: function (data) {
                    $('.table-date').each(function(key, item) {
                        if($(item).data('date') == data.year + '-' + data.month) {
                            $(item).find('td').each(function(key2, cell){
                                if(data.dates.indexOf($(cell).data('date')) != -1) {
                                    $(cell).html('X');
                                }
                            })
                        }
                    });
                }
            });
        }
    }	
}

</script>