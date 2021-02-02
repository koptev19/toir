<?php

class UserService
{

    /**
     * @param string $userName
     * 
     * @return int|null
     */
    public static function getUserIdByName(string $userName): ?int
    {
        if(!$userName) {
            return null;
        }

		if (ctype_space($userName)) {
			$arUserFilter =  [
				'LAST_NAME' => explode(" ", $userName)[0],
				'NAME' => explode(" ", $userName)[1],
                'ACTIVE' => 'Y'
            ];
        } else  {
			$arUserFilter = [
				'NAME' => $userName,
                'ACTIVE' => 'Y'
            ];
        }

        $data = CUser::GetList(($by="ID"), ($order="ASC"), $arUserFilter);        
        return $data->Fetch()["ID"];
    }

    /**
     * @param array $filter = []
     * 
     * @return array
     */
    public static function getList(array $filter = []): array
    {
        return UserToir::filter($filter)->get();
    }

    /**
     * @param int|string $id
     */
    public static function getById($id)
    {
        return $id ? UserToir::find($id) : [];
    }


}

