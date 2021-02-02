<?php

class ToirTaskReceivingEquipmentController extends ToirController
{

    /**
     * @param array $data
     * @return void
     */
    public function taskDone(array $data)
    {
        $meta = $data['META:PREV_FIELDS'];

        $lastComment = $this->getLastComment($meta);

        $equipmentId = (int)$meta["UF_AUTO_898481918998"];
        $equipment = Equipment::find($equipmentId);

        $create = [
            'NAME' => $meta['TITLE'],
            'TASK' => $meta['ID'],
            'NOTE' => $this->getNote($meta),
			'EQUIPMENT_ID' => $equipment->ID,
			'LINE_ID' => $equipment->LINE_ID,
			'WORKSHOP_ID' => $equipment->WORKSHOP_ID,
        ];

        if ($lastComment) {
            $create['FORUM_COMMENT_TIME'] = date("Y-m-d H:i:s", strtotime($lastComment['TIME']));
            $create['FORUM_COMMENT_ID'] = $lastComment['ID'];
            $create['DETAIL_TEXT'] = $lastComment['MESSAGE'];
//            $create['DETAIL_PICTURE'] = ''; // Здесь нужно добавить картинку из последнего комментария
        }

        Receiving::create($create);
    }

    /**
     * @param array $meta
     * @return string
     */
    private function getNote(array $meta): string
    {
        $note = '-';

        foreach($meta['CHECKLIST'] as $checklist) {
            if($checklist['PARENT_ID'] > 0 && $checklist['TITLE'] == 'ЗАМЕЧАНИЙ НЕТ.') {
                $note = $checklist['IS_COMPLETE'];
            }
        }

        return $note;
    }

    /**
     * @param array $meta
     * @return array|null
     */
    private function getLastComment(array $meta): ?array
    {
        $comment = null;

        $comments = TaskService::getTaskComments($meta, ['ID' => 'DESC']);
        foreach($comments as $message) {
            if ($message['POST_MESSAGE'] == 'commentAuxTaskInfo') {
                continue;
            }

            $comment = [
                'ID' => $message['ID'],
                'MESSAGE' => $message['POST_MESSAGE'],
                'TIME' => $message['POST_DATE'],
            ];
            break;
        }

        return $comment;
    }
 
}