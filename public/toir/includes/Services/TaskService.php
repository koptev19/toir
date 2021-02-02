<?php

class TaskService
{

    /**
     * @param int $taskId
     * 
     * @return array|null|false
     */
    public static function getTask(int $taskId)
    {
        $rTask = CTasks::GetByID($taskId);
        return $rTask->GetNext();
    }

    /**
     * @param string $TITLE
     * @param string $DESCRIPTION
     * @param string $DEADLINE
     * @param int $RESPONSIBLE_ID
     * @param ?int $PARENT_ID = null
     * @param ?int $lineId = null
     * @param int $CREATED_BY = 64
     * 
     * @return int|null
     */
    public static function create(string $TITLE, string $DESCRIPTION, string $DEADLINE, int $RESPONSIBLE_ID, ?int $PARENT_ID = null, ?int $lineId = null, int $CREATED_BY = TASK_USER_ID): ?int 
    {
        $obTask = new CTasks;
        $arFields = [
            "TITLE" => $TITLE,
            "DESCRIPTION" => $DESCRIPTION,
            'CREATED_BY' => $CREATED_BY,
            "RESPONSIBLE_ID" => $RESPONSIBLE_ID,
            "GROUP_ID" => 34,
            "UF_AUTO_605565826857" => $lineId,
            "DEADLINE" => $DEADLINE,
            'PARENT_ID' => $PARENT_ID
        ];
        return $obTask->Add($arFields);
    }

    /**
     * @param int $lineId
     * 
     * @return void
     */
    public static function delete(?int $taskId) 
    {
        if ($taskId) {
            CTasks::Delete($taskId); 
        }
    }

    /**
     * @param int $taskId
     * @param int $RESPONSIBLE_ID
     * 
     * @return void
     */
    public static function complite(int $taskId, int $RESPONSIBLE_ID)
    {
        try {
            $taskItem = new CTaskItem($taskId, $RESPONSIBLE_ID);
            $taskItem->update([
                'IS_COMPLETE' => 'Y',
                'STATUS' => 5
            ]);
        } catch (Exception $e) {
            
        }
    }

    /**
     * @param int $taskId
     * @param array $operations
     * 
     * @return void
     */
    public static function compliteChecklistItems(int $taskId, array $operations)
    {
        global $USER;
        $task = self::getTask($taskId);

        foreach($operations as $operation) {
            if(!isset($task['CHECKLIST'][$operation->CHECKLIST_ITEM])) {
                continue;
            }

            $taskItem = \CTaskItem::getInstance($taskId, $USER->GetID());
            $item = new \CTaskCheckListItem($taskItem, $operation->CHECKLIST_ITEM);
            $item->update(['IS_COMPLETE' => 'Y']);
        }
    }

    /**
     * @param int $taskId
     * @param int $RESPONSIBLE_ID
     * @param string $text
     * 
     * @return void
     */
    public static function addToDescription(int $taskId, int $RESPONSIBLE_ID, string $text)
    {
        $oTaskItem = new CTaskItem($taskId, $RESPONSIBLE_ID);
        try {
            $oTaskItem->update([
                'DESCRIPTION' => $oTaskItem->getDescription() . $text
            ]);
        } catch(Exception $e) {
            
        }
    }

    /**
     * @param int $taskId
     * @param int $RESPONSIBLE_ID
     * 
     * @return void
     */
    public static function changeResponsibleId(int $taskId, int $RESPONSIBLE_ID)
    {
        global $USER;
        $oTaskItem = new CTaskItem($taskId, $USER->GetID());
        $oTaskItem->update([
            'RESPONSIBLE_ID' => $RESPONSIBLE_ID
        ]);
    }

    /**
     * @param string $date
     * @param int $lineId
     * 
     * @return void
     */
    public static function updateChecklistItems(string $date, int $lineId)
    {
        // В связи с тем, что реестры были удалены, то если функцию включить, то она работать не будет
        return;

        $stop = Stop::getByLineDate($lineId, $date);
        if(!$stop) {
            return;
        }

        $task = self::getTask($stop->TASK);
        if(!$task) {
            return;
        }

        $filter = [
            "PLANNED_DATE" => date("Y-m-d", strtotime($date)),
            "LINE_ID" => $lineId,
        ];

        $operations = Operation::filter($filter)->get();

        $oldChecklists = [];
        $newChecklists = [];
        if($task['CHECKLIST']){
            foreach($task['CHECKLIST'] as $checkListItem) {
                $oldChecklists[] = $checkListItem['ID'];
            }
        }

        $checkListNames = [];
        foreach($operations as $operation) {
            $parentChecklistId = self::getChecklistIdByName($task, "");
            if (!$parentChecklistId) {
                $parentChecklistId = self::insertTaskItem((int)$task['ID'], '', 0);
                $task = self::getTask((int)$task['ID']);
            }

            $newChecklists[] = $parentChecklistId;

            $childCheckListName = $operation->equipment->path();
            $checklistId = self::getChecklistIdByName($task, $childCheckListName, false, $checkListNames);
            if(!$checklistId) {
                $checklistId = self::insertTaskItem((int)$task['ID'], $childCheckListName, $parentChecklistId);
                $task = self::getTask((int)$task['ID']);
            }

            if($checklistId) {
                $operation->CHECKLIST_ITEM = $checklistId;
                $operation->save();

                if(!isset($checkListNames[$childCheckListName])) {
                    $checkListNames[$childCheckListName] = [];
                }
                $checkListNames[$childCheckListName][] = $checklistId;
                $newChecklists[] = $checklistId;
            }
        }

        $deletedChecklists = array_diff($oldChecklists, $newChecklists);
        foreach($deletedChecklists as $id){
            self::deleteChecklistItem((int)$task['ID'], (int)$id);
        }
    }

    /**
     * @param array $task
     * @param string $checklistName
     * @param bool $isParent = true
     * @param array $checkListNames = []
     * 
     * @return int|null
     */
    public static function getChecklistIdByName(array $task, string $checklistName, bool $isParent = true, array $checkListNames = []): ?int
    {
        $checklistId = null;

        $noIds = isset($checkListNames[$checklistName]) ? $checkListNames[$checklistName] : [];

        foreach ($task['CHECKLIST'] as $val){ 
            if(!in_array($val['ID'], $noIds) && ($isParent && $val['PARENT_ID'] == 0 || !$isParent && $val['PARENT_ID'] > 0)) {
                if ($val['TITLE'] == $checklistName) {
                    $checklistId = (int)$val['ID'];
                    break;
                }
            }
        }

        return $checklistId;
    }

    /**
     * @param int $taskId
     * @param string $title
     * @param int $parentId
     * 
     * @return int|null
     */
    public static function insertTaskItem(int $taskId, string $title, int $parentId): ?int
    {
        global $USER;
        $CheckListItem = CTaskItem::getInstance($taskId, $USER->GetID());
        $item = \CTaskCheckListItem::add($CheckListItem, ['TITLE'=> $title, 'PARENT_ID' => $parentId, 'IS_COMPLETE'=>'N']);
        return intval($item->getId());
    }

    /**
     * @param int $taskId
     * @param int $checklistItemId
     * 
     * @return void
     */
    public static function deleteChecklistItem(int $taskId, int $checklistItemId)
    {
        global $USER;
        $taskInstance = \CTaskItem::getInstance($taskId, $USER->GetID());
        $item = new \CTaskCheckListItem($taskInstance, $checklistItemId);
        $item->delete();
    }

    /**
     * @param array|integer $task
     * @param array $sort
     * @return array
     */
    public static function getTaskComments($task, array $sort = ['ID' => 'ASC']): array
    {
        if(is_integer($task)) {
            $task = self::getTask($task);
        }

        $comments = [];

        $db_res = CForumMessage::GetList($sort, ["TOPIC_ID" => $task['FORUM_TOPIC_ID']]);
        while ($message = $db_res->Fetch()) {
            $comments[] = $message;
        }
        return $comments;
    }

}

