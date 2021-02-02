<?php

class ToirController
{
    /**
     * @param string $viewName
     * @param array|null $vars = []
     * @return void
     */
    public function view(string $viewName, array $vars = [])
    {
        $filepath = "includes/Views/" . $viewName . '.php';
        if(file_exists($filepath)) {
            foreach ($vars as $key => $val){
                $$key = $val;
            }
            require($filepath);
        } else {
            die('View '.$viewName.' doesn`t exists');
        }
    }

    /**
     * @param string $viewName
     * @param array|null $vars = []
     * @return void
     */
    public function component(string $viewName, array $vars = [])
    {
        return $this->view('components/' . $viewName, $vars);
    }

    /**
     * @return void
     */
    public function showFooter()
    {
        $this->view("_footer");
    }

    public function openerReloadAndSelfClose()
    {
        $this->view('components/opener_reload_self_close');
    }

    public function componentTemplateStart()
    {
        ob_start();
    }

    public function componentTemplateContent()
    {
        $template= ob_get_contents();
        ob_flush();
        $template = str_replace("\n", "", $template);
        $template = str_replace("\r", "", $template);
        return $template;
    }
}