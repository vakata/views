<?php

namespace vakata\views;

/**
 * A simple template class.
 */
class View
{
    protected static $dirs = [];
    protected static $data = [];

    protected $template = null;
    protected $sectionData = null;

    protected $layout = null;
    protected $layoutData = [];
    protected $layoutSection = null;
    protected $layoutSections = [];

    /**
     * Add a directory to the template list. The name is used when searching for the template.
     * @method addDir
     * @param string $dir  the directory to add
     * @param string $name the alias of the directory
     * @return self
     */
    public static function addDir($dir, $name = '')
    {
        static::$dirs[$name] = $dir;
    }
    /**
     * Share a single variable or an array of variables in all templates.
     * @method shareData
     * @param string|array $var   the variable name, or an array of variables
     * @param mixed        $value if $var is an array omit this parameter, otherwise this is the value of the variable
     */
    public static function shareData($var, $value = null)
    {
        if (is_array($var) && $value === null) {
            static::$data = array_merge(static::$data, $var);
        } else {
            static::$data[$var] = $value;
        }
    }
    /**
     * Render a template.
     * @method get
     * @param  string $template the template to render
     * @param  array  $data     optional data to use in rendering
     * @return string           the result
     */
    public static function get($template, array $data = [])
    {
        return (new self($template))->render($data);
    }

    /**
     * Create an instance.
     * @method __construct
     * @param  string      $template    the template to be rendered
     * @param  array       $sectionData optional available sections
     */
    public function __construct($template, $sectionData = [])
    {
        $template = explode('::', $template, 2);
        if (!isset($template[1])) {
            array_unshift($template, '');
        }
        if (!isset(static::$dirs[$template[0]])) {
            throw new \Exception('Unknown directory: '.$template[0]);
        }
        $template = static::$dirs[$template[0]].DIRECTORY_SEPARATOR.preg_replace('(\.php$)', '', $template[1]).'.php';
        if (!is_file($template)) {
            throw new \Exception('Template not found: '.$template);
        }
        $this->template = $template;
        $this->sectionData = $sectionData;
    }

    /**
     * Specifies a master template to load the current template in. Available only inside templates.
     * @method layout
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
     * @method section
     * @param  string  $name the section name
     * @return string        the section content
     */
    protected function section($name = '')
    {
        return isset($this->sectionData[$name]) ? $this->sectionData[$name] : '';
    }
    /**
     * Start a new section, so that it will be available in the master template. Should be used only inside templates.
     * @method sectionStart
     * @param  string       $name the section name
     */
    protected function sectionStart($name)
    {
        ob_start();
        $this->layoutSection = $name;
    }
    /**
     * Stop and gather the content for the currently started section. Should be used only inside templates.
     * @method sectionStop
     */
    protected function sectionStop()
    {
        $this->layoutSections[$this->layoutSection] = ob_get_clean();
    }
    /**
     * Escape a variable (htmlspecialchars is used). Optionally functions can be applied to the resulting value.
     * @method e
     * @param  string $var   the var to escape
     * @param  string $funcs a pipe delimited list of functions to execute on the value
     * @return string        the escaped string
     */
    protected function e($var, $funcs = '')
    {
        $var = htmlspecialchars($var, ENT_HTML5 | ENT_QUOTES);
        $funcs = array_filter(explode('|', $funcs));
        foreach ($funcs as $f) {
            $var = call_user_func($f, $var);
        }

        return $var;
    }
    /**
     * Include a template inside the current one. Can only be used from inside a template.
     * @method insert
     * @param  string  $template the template to insert
     * @param  array   $data     optional data to pass in
     * @return string            the result
     */
    protected function insert($template, array $data = [])
    {
        return (new self($template))->render($data);
    }
    /**
     * Render the template.
     * @method render
     * @param  array  $data optional data to use when rendering
     * @return string       the result
     */
    public function render(array $data = [])
    {
        extract(static::$data);
        extract($data);
        try {
            ob_start();
            include $this->template;
            $data = ob_get_clean();
            if ($this->layout) {
                $this->layoutSections[''] = $data;
                return (new self($this->layout, $this->layoutSections))->render($this->layoutData);
            }
            return $data;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }
}
