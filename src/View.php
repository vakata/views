<?php

namespace vakata\views;

class View
{
    protected $template = null;
    protected $sharedData = null;
    protected $sectionData = null;

    protected $layout = null;
    protected $layoutData = [];
    protected $layoutSection = null;
    protected $layoutSections = [];

    public function __construct($template, array $data = [], $sectionData = [])
    {
        $template = explode('::', $template, 2);
        if (!isset($template[1])) {
            array_unshift($template, '');
        }
        if (!isset($this->dirs[$template[0]])) {
            throw new \Exception('Unknown directory: '.$template[0]);
        }
        $template = $template[0].DIRECTORY_SEPARATOR.preg_replace('(\.php$)', '', $template[1]).'.php';
        if (!is_file($template)) {
            throw new \Exception('Template not found: '.$template);
        }
        $this->template = $template;
        $this->sharedData = $sharedData;
        $this->sectionData = $sectionData;
    }

    protected function layout($template, array $data = [])
    {
        $this->layout = $template;
        $this->layoutData = $data;
    }
    protected function section($name = '')
    {
        return isset($this->sectionData[$name]) ? $this->sectionData[$name] : '';
    }
    protected function sectionStart($name)
    {
        ob_start();
        $this->layoutSection = $name;
    }
    protected function sectionStop()
    {
        $this->layoutSections[$this->layoutSection] = ob_get_clean();
    }
    protected function e($var, $funcs = '')
    {
        $var = htmlspecialchars($var, ENT_HTML5 | ENT_QUOTES);
        $funcs = array_filter(explode('|', $funcs));
        foreach ($funcs as $f) {
            $var = call_user_func($f, $var);
        }

        return $var;
    }

    public function render(array $data = [])
    {
        extract($this->sharedData);
        extract($data);
        try {
            ob_start();
            include $this->template;
            $data = ob_get_clean();
            if ($this->layout) {
                $this->layoutSections[''] = $data;
                return (new self($this->layout, $this->sharedData, $this->layoutSections))->render($this->layoutData);
            }
            return $data;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }
    public function include($template, array $data = [])
    {
        return (new self($template, $this->data))->render($data);
    }
}
