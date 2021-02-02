<?php

class ToirMainController extends ToirController
{

    /**
     * @return void
     */
    public function index()
    {
        $userToir = UserToir::current();
        $workshops = $userToir->availableWorkshops;
        if(count($workshops) == 1) {
            $workshop = reset($workshops);
            header("Location: index.php?workshop=" . $workshop->ID);
        } else {
            $this->view("_header");
            $this->view('main/index', compact('workshops'));
            $this->view("_footer");
        }
    }

}