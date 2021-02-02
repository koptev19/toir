<h3 class='mb-5' class='text-center'>Обработка заявки на ремонт</h3>

<form  method="post" action="">
<input type="hidden" name="save" value="1">
<input type="hidden" name="service_request_id" value="<?php echo $serviceRequest['fields']['ID']; ?>">
<div class="mb-3 row">
    <div class='col-2'>Нужна ли остановка линии</div>
    <div class="col-10">
        <select name="stop_line"  class="form-control form-select">
            <option value="0">Нет</option>
            <option value="1">Да</option>
        </select>
    </div>
</div>

</form>



