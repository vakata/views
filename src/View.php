<?php

namespace vakata\views;

/**
 * A simple template class.
 */
class View
{
    protected $repository;
    protected $template;
    protected $sectionData;
    protected $data = [];

    protected $layout;
    protected $layoutSection;
    protected $layoutData = [];
    protected $layoutSections = [];

    /**
     * Create an instance.
     * @param  \vakata\views\Views      $repository    a views repository instance
     * @param  string      $template    the template file to be rendered
     * @param  array       $sectionData optional available sections
     * @param  array       $data        optional data to use when rendering
     */
    public function __construct(Views $repository, string $template, array $sectionData = [], array $data = [])
    {
        $this->repository = $repository;
        $this->template = $template;
        $this->sectionData = $sectionData;
        $this->data = $data;
    }

    /**
     * Specifies a master template to load the current template in. Available only inside templates.
     * @param  string $template the master template
     * @param  array  $data     optional data to pass to the template
     */
    protected function layout($template, array $data = [])
    {
        $this->layout = $template;
        $this->layoutData = $data;
    }
    /**
     * Get a secion's string content if available. Should be used only inside templates.
     * @param  string  $name the section name
     * @return string        the section content
     */
    protected function section($name = '')
    {
        return isset($this->sectionData[$name]) ? $this->sectionData[$name] : '';
    }
    /**
     * Start a new section, so that it will be available in the master template. Should be used only inside templates.
     * @param  string       $name the section name
     */
    protected function sectionStart($name)
    {
        ob_start();
        $this->layoutSection = $name;
    }
    /**
     * Stop and gather the content for the currently started section. Should be used only inside templates.
     */
    protected function sectionStop()
    {
        $this->layoutSections[$this->layoutSection] = ob_get_clean();
    }
    /**
     * Escape a variable (htmlspecialchars is used). Optionally functions can be applied to the resulting value.
     * @param  string $var   the var to escape
     * @param  callable|string $funcs a pipe delimited list of functions to execute on the value (or a single callable)
     * @return string        the escaped string
     */
    protected function e($var, $funcs = '')
    {
        if (is_callable($funcs)) {
            $var = call_user_func($funcs, $var);
        } else {
            $funcs = array_filter(explode('|', $funcs));
            foreach ($funcs as $f) {
                $var = call_user_func($f, $var);
            }
        }
        return htmlspecialchars($var, ENT_HTML5 | ENT_QUOTES);
    }
    /**
     * Include a template inside the current one. Can only be used from inside a template.
     * @param  string  $template the template to insert
     * @param  array   $data     optional data to pass in
     * @return string            the result
     */
    protected function insert($template, array $data = [])
    {
        return $this->repository->render($template, $data);
    }
    /**
     * Render the template.
     * @param  array  $data optional data to use when rendering
     * @return string       the result
     */
    public function render(array $data = [])
    {
        extract($this->data);
        extract($data);
        try {
            ob_start();
            include $this->template;
            $data = ob_get_clean();
            if ($this->layout) {
                $this->layoutSections[''] = $data;
                return $this->repository->render($this->layout, $this->layoutData, $this->layoutSections);
            }
            return $data;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
    public function __invoke(array $data = [])
    {
        return $this->render($data);
    }
}
