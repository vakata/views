<?php

namespace vakata\views;

/**
 * A simple template repository class.
 */
class Views
{
    protected $dirs = [];
    protected $data = [];

    /**
     * Create an instance
     * @method __construct
     * @param  string|null $dir  optional default dir for views
     * @param  array       $data optional preshared data
     */
    public function __construct(string $dir = null, array $data = [])
    {
        $this->dir($dir);
        $this->share($data);
    }

    /**
     * Add a directory to the template list. The name is used when searching for the template.
     * @method dir
     * @param string $dir  the directory to add
     * @param string $name the alias of the directory
     * @return self
     */
    public function dir($dir, $name = '')
    {
        $this->dirs[$name] = $dir;
        return $this;
    }
    /**
     * Share a single variable or an array of variables in all templates.
     * @method share
     * @param string|array $var   the variable name, or an array of variables
     * @param mixed        $value if $var is an array omit this parameter, otherwise this is the value of the variable
     * @return self
     */
    public function share($var, $value = null)
    {
        if (is_array($var) && $value === null) {
            $this->data = array_merge($this->data, $var);
        } else {
            $this->data[$var] = $value;
        }
        return $this;
    }
    /**
     * Get a View instance.
     * @method get
     * @param  string $template the template to render
     * @param  array  $sections optional sections to use in rendering
     * @return \vakata\views\View the view instance
     */
    public function get($template, array $sections = [])
    {
        $template = explode('::', $template, 2);
        if (!isset($template[1])) {
            array_unshift($template, '');
        }
        list($dir, $name) = $template;
        if (!isset($this->dirs[$dir])) {
            throw new \Exception('Unknown directory: ' . $dir);
        }
        $template = $this->dirs[$dir] . DIRECTORY_SEPARATOR . preg_replace('(\.php$)', '', $name) . '.php';
        if (!is_file($template)) {
            throw new \Exception('Template not found: ' . $template);
        }
        return new View($this, $template, $sections, $this->data);
    }
    /**
     * Render a template.
     * @method render
     * @param  string $template the template to render
     * @param  array  $data     optional data to use in rendering
     * @param  array  $sections optional sections to use in rendering
     * @return string the result
     */
    public function render($template, array $data = [], array $sections = [])
    {
        return $this->get($template, $sections, $this->data)->render($data);
    }
}
