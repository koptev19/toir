<?php

class ToirSettingsController extends ToirController
{

    /**
     * @return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /404.php");
        }
    }

    /**
     * @return void
     */
    public function index()
    {
        $planMonthDay = Settings::getByName('plan_month_day');
        
		$this->showHeader();
        $this->view('settings/index', compact('planMonthDay'));
        $this->showFooter();
    }

	public function update()
    {
        $planMonthDay = Settings::getByName('plan_month_day');
		$planMonthDay->VALUE = $_REQUEST['plan_month_day'];
		$planMonthDay->save();
        
		header("Location: settings.php");
    }

    /**
     * @return void
     */
    private function showHeader()
    {
        $this->view('_header', ['title' => 'Настройки']);
        $this->view('settings/header');
    }

    /**
     * @return void
     */
    public function showFooter()
    {
        $this->view('settings/footer');
        $this->view('_footer');
    }

}