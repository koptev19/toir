<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
	<div class="btn-group me-4" role="group" aria-label="First group">
		<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#equipmentOperations" aria-expanded="false" aria-controls="equipmentOperations">
    		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
        		<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
    		</svg> 
    		Плановые операции
		</button>
		<a href="/toir/add_plan.php?workshop=<?php echo $equipment->workshop_id; ?>&equipment=<?php echo $equipment->id; ?>" class="btn btn-primary" target="_blank"><span class="h5 font-weight-bold">+</span></a>
	</div>
	<div class="btn-group me-4" role="group" aria-label="Second group">
		<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#equipmentWorks" aria-expanded="false" aria-controls="equipmentWorks">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
			</svg> 
			Операции без даты
		</button>
		<a href="/toir/add_work.php?equipment={{ $equipment->id }}" class="btn btn-primary" target="_blank"><span class="h5 font-weight-bold">+</span></a>
	</div>
	<div class="btn-group" role="group" aria-label="Third group">
		<a href="/toir/history.php?workshop={{ $equipment->workshop_id }}&line={{ $equipment->line_id }}&filter[EQUIPMENT_ID]={{ $equipment->id }}" class="btn btn-primary" target="_blank"><i class="fas fa-history me-2"></i> История работ</a>
	</div>
</div>

<div class="my-4 collapse" id="equipmentOperations">
<h5 class='text-center'>Плановые операции</h5>
	<div id='table2'>
	    <table class="table table-bordered table-sm">
            <thead>
                <tr class='text-center'>
                    <th>Причина возникновения операции</th>
                    <th>Название регламентной операции<br>(Рекомендации)</th>
                    <th>Результат</th>
                    <th>Последняя дата выполнения</th>
                    <th>ВИД ТО<br>периодичность</th>
                    <th>Следующая Дата выполнения
                    <br>Просрочка
                    <th colspan=2></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($equipment->plans ?? [] as $operation)
				<tr id='operation-{{ $operation->id }}' class='text-center'>
                    <td>
                        {{ \App\Models\Operation::verbalReason($operation->reason) }}
                        @if($operation->reason == \App\Models\Operation::REASON_VIEW)
                            {{ $operation->service->short_name }}
                        @endif
                        <br>
                        ({{ \App\Models\Operation::getVerbalType($operation->type_operation) }})
                    </td>
                    <td class='text-start'>
                        <div class="font-weight-bold">{{ $operation->name }}</div>
                        @if($operation->recommendation)
                            <div class="font-italic fst-italic text-info">{{ $operation->recommendation }}</div>
                        @endif
                    </td>
                    <td>
                        @if($operation->status == 'N')
                            <div class='text-danger'>{{ $operation->comment_no_result }}</div>
                        @elseif($operation->status == 'Y')
                            <div class='text-success'>Выполнено</div>
                        @endif
                    </td>
                    <td>{{ $operation->last_date_from_checklist }}</td>
                     <td>
                            {{ \App\Models\Plan::getVerbalTypeTo($operation->type_to) }}<br>
                            {{ $operation->periodicity }} дн.
                     </td>
                      <td>                        
                        {{ $operation->next_execution_date }}
                        @if($operation->late > 0)
                            <div class='text-danger'>{{ $operation->late }} дн.</div>
                            <div>от {{ $operation->getLateDate() }}</div>
                            
                        @else
                            <div class='text-success'>Нет</div>
                        @endif
                    </td>
                    <td class='text-nowrap'>
                        <div class='links'>
                            <a href="/toir/edit_operation.php?operation_id={{ $operation->id }}" target=_blank><img src="./images/pencil.svg" /></a>
                            <a href="/toir/del_operation.php?id={{ $operation->id }}" onclick="return confirm('Удалить?')" class='ml-3'><img src="./images/x.svg" /></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>    
        </table>
    </div>
</div>

<div class="my-4 collapse" id="equipmentWorks">
<h5 class='text-center'>Операции без даты</h5>
	<div id='table2'>
	    <table class="table table-bordered">
            <thead>
                <tr class='text-center'>
                    <th>Название регламентной операции<br>(Рекомендации)</th>
                    <th>Служба</th>
                    <th>Последняя дата выполнения</th>
                    <th>Тип операции</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($equipment->works ?? [] as $work)
				<tr id='work-{{ $work->id }}' class='text-center'>
                    <td class='text-start'>
                        <div class="font-weight-bold">{{ $work->name }}</div>
                        @if($work->recommendation)
                            <div class="font-italic fst-italic text-info">{{ $work->recommendation }}</div>
                        @endif
                    </td>
                    <td>{{ $work->department->short_name }}</td>
                    <td>{{ $work->last_complited }}</td>
                    <td>{{ \App\Models\Operation::getVerbalType($work->type) }}</td>
                    <td class='text-nowrap'>
                        <div class='links'>
                            <a href="/toir/add_work.php?action=edit&work_id={{ $work->id }}" target=_blank><img src="./images/pencil.svg" /></a>
                            <a href="/toir/add_work.php?action=delete&work_id={{ $work->id }}" onclick="return confirm('Удалить?')" class='ml-3'><img src="./images/x.svg" /></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>    
        </table>
    </div>
</div>
