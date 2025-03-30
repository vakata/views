<?php

declare(strict_types=1);

namespace vakata\views;

/**
 * A simple template repository class.
 */
class Views
{
    /** @var array<string,string> $dirs */
    protected array $dirs = [];
    /** @var array<string,mixed> $data */
    protected array $data = [];

    /**
     * Create an instance
     * @param  string|null         $dir  optional default dir for views
     * @param  array<string,mixed> $data optional preshared data
     */
    public function __construct(?string $dir = null, array $data = [])
    {
        if ($dir) {
            $this->dir($dir);
        }
        if (count($data)) {
            $this->share($data);
        }
    }

    /**
     * Add a directory to the template list. The name is used when searching for the template.
     * @param string $dir  the directory to add
     * @param string $name the alias of the directory
     * @return self
     */
    public function dir(string $dir, string $name = ''): self
    {
        $this->dirs[$name] = $dir;
        return $this;
    }
    /**
     * Share a single variable or an array of variables in all templates.
     * @param string|array $var   the variable name, or an array of variables
     * @param mixed        $value if $var is an array omit this parameter, otherwise this is the value of the variable
     * @return self
     */
    public function share(array|string $var, mixed $value = null): self
    {
        if (is_array($var) && $value === null) {
            $this->data = array_merge($this->data, $var);
        } else {
            $this->data[$var] = $value;
        }
        return $this;
    }
    public function exists(string $template): bool
    {
        $template = explode('::', $template, 2);
        if (!isset($template[1])) {
            array_unshift($template, '');
        }
        list($dir, $name) = $template;
        if (!isset($this->dirs[$dir])) {
            return false;
        }
        $template = $this->dirs[$dir] . DIRECTORY_SEPARATOR . preg_replace('(\.php$)', '', $name) . '.php';
        if (!is_file($template)) {
            return false;
        }
        return true;
    }
    /**
     * Get a View instance.
     * @param  string $template the template to render
     * @return View the view instance
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
     * @param  string $template the template to render
     * @param  array  $data     optional data to use in rendering
     * @param  array  $sections optional sections to use in rendering
     * @return string the result
     */
    public function render($template, array $data = [], array $sections = [])
    {
        return $this->get($template, $sections)->render($data);
    }

    public function getFolders(): object
    {
        return new class ($this->dirs) {
            public function __construct(protected array $dirs)
            {
            }
            public function exists(string $dir): bool
            {
                return in_array($dir, $this->dirs);
            }
        };
    }
    public function addFolder(string $name, string $location): self
    {
        $this->dir($location, $name);
        return $this;
    }
    public function addData(array $data): self
    {
        $this->share($data);
        return $this;
    }
}
