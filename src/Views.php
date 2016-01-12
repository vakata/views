<?php

namespace vakata\views;

class Views
{
    protected $dirs = [];
    protected $data = [];

    /**
     * Creates an instance.
     *
     * @method __construct
     *
     * @param string $defaultDir optional default directory for templates
     * @param array  $sharedData optional array of variables available in all templates
     */
    public function __construct($defaultDir = '', array $sharedData = [])
    {
        if ($defaultDir) {
            $this->dirs[''] = $defaultDir;
        }
        $this->data = $sharedData;
    }

    /**
     * Add a directory to the template list. The name is used when searching for the template.
     *
     * @method addDir
     *
     * @param string $dir  the directory to add
     * @param string $name the alias of the directory
     *
     * @return self
     */
    public function addDir($dir, $name)
    {
        if ($dir) {
            $this->dirs[$name] = $dir;
        }

        return $this;
    }

    /**
     * Share a single variable or an array of variables in all templates.
     *
     * @method shareData
     *
     * @param string|array $var   the variable name, or an array of variables
     * @param mixed        $value if $var is an array omit this parameter, otherwise this is the value of the variable
     *
     * @return self
     */
    public function shareData($var, $value = null)
    {
        if (is_array($var) && $value === null) {
            $this->data = array_merge($this->data, $var);
        } else {
            $this->data[$var] = $value;
        }

        return $this;
    }

    public function render($template, array $data = [])
    {
        return (new View($template, $this->data))->render($data);
    }
}
