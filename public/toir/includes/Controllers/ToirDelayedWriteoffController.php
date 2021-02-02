<?php

class ToirDelayedWriteoffController extends ToirController
{

    /**
     * @return void
     */
    public function index()
    {
        global $USER;

        $writeoffs = DelayedWriteoff::filter([
                'author_id' => UserToir::current()->id,
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->view('delayed_writeoffs/index', compact('writeoffs'));
    }

    /**
     * @return void
     */
    public function done()
    {
        global $USER;

        $writeoff = DelayedWriteoff::filter([
                'author_id' => UserToir::current()->id,
                'ID' =>(int)$_REQUEST['done']
            ])
            ->first();
        
        $writeoff->IS_DONE = 1;
        $writeoff->save();

        header("Location: delayed_writeoffs.php");
    }
}