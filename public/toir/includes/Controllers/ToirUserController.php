<?php

class ToirUserController extends ToirController
{

    /**
     * @return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /");
        }
    }

    /**
     * @return void
     */
    public function index()
    {
        $services = Service::all();
		$workshops = Workshop::all();
        $users =  UserToir::all();
        
        $connectedUsers = [];
        $notConnectedUsers = [];

        foreach($users as $user) {
            if($user->connected) {
                $connectedUsers[] = $user;
            } else {
                $notConnectedUsers[] = $user;
            }
        }

		$this->showHeader();
        $this->view('users/index',compact('services','workshops','connectedUsers', 'notConnectedUsers'));
		$this->showFooter();
    }

    /**
     * @return void
     */
    public function store()
    {
        $users= $_REQUEST['users'] ?? [];
        $workshops= $_REQUEST['workshops'];
        $services= $_REQUEST['services'];
        $admin = $_REQUEST['admin'];
        $wsAll = $_REQUEST['wsAll'];

		$usersNew= $_REQUEST['usersNew'] ?? [];
		$adminNew= $_REQUEST['adminNew'];
		$servicesNew= $_REQUEST['servicesNew'];
		$workshopsNew= $_REQUEST['workshopsNew'];
		$wsAllNew= $_REQUEST['wsAllNew'];

        $delUsers = $_REQUEST['delUsers'] ?? [];

        foreach($delUsers as $userId){
        	$user = UserToir::find((int)$userId);
            $user->connected = false;
            $user->save();
        }

		foreach($usersNew as $userId=>$name){
            $user = UserToir::find((int)$userId);
            $user->connected = true;
            $user->IS_ADMIN = $adminNew[$userId] ? true : false;
            $user->ALL_WORKSHOPS = $wsAllNew[$userId] ? true : false;
            $user->SERVICE_ID = $adminNew[$userId] ? [] : ($servicesNew[$userId] ?? []);
            $user->WORKSHOP_ID = ($adminNew[$userId] || $wsAllNew[$userId] ) ? [] : ($workshopsNew[$userId] ?? []);
            $user->save();
		}
		
		
        foreach($users as $userId=>$v)
        {
            $user=UserToir::find((int)$userId);
            $user->IS_ADMIN = $admin[$userId] ? true : false;
            $user->SERVICE_ID = $admin[$userId] ? [] : ($services[$userId] ?? []);
            $user->WORKSHOP_ID  = ($admin[$userId] || $wsAll[$userId] ) ? [] : ($workshops[$userId] ?? []);
            $user->ALL_WORKSHOPS =  $wsAll[$userId] ? true : false;
            $user->save();
        }
		

	    header("Location: users.php");
    }

       /**
     * @return void
     */
    private function showHeader()
    {
        $this->view('_header', ['title' => 'Пользователи']);
        $users = UserToir::all();
        $this->view('users/header', compact('users'));
    }

    /**
     * @return void
     */
    public function showFooter()
    {
        $this->view('users/footer');
        $this->view('_footer');
    }

}