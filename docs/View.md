# vakata\views\View
A simple template class.

## Methods

| Name | Description |
|------|-------------|
|[addDir](#vakata\views\viewadddir)|Add a directory to the template list. The name is used when searching for the template.|
|[shareData](#vakata\views\viewsharedata)|Share a single variable or an array of variables in all templates.|
|[get](#vakata\views\viewget)|Render a template.|
|[__construct](#vakata\views\view__construct)|Create an instance.|
|[render](#vakata\views\viewrender)|Render the template.|

---



### vakata\views\View::addDir
Add a directory to the template list. The name is used when searching for the template.  


```php
public static function addDir (  
    string $dir,  
    string $name  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$dir` | `string` | the directory to add |
| `$name` | `string` | the alias of the directory |
|  |  |  |
| `return` | `self` |  |

---


### vakata\views\View::shareData
Share a single variable or an array of variables in all templates.  


```php
public static function shareData (  
    string|array $var,  
    mixed $value  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$var` | `string`, `array` | the variable name, or an array of variables |
| `$value` | `mixed` | if $var is an array omit this parameter, otherwise this is the value of the variable |

---


### vakata\views\View::get
Render a template.  


```php
public static function get (  
    string $template,  
    array $data  
) : string    
```

|  | Type | Description |
|-----|-----|-----|
| `$template` | `string` | the template to render |
| `$data` | `array` | optional data to use in rendering |
|  |  |  |
| `return` | `string` | the result |

---


### vakata\views\View::__construct
Create an instance.  


```php
public function __construct (  
    string $template,  
    array $sectionData  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$template` | `string` | the template to be rendered |
| `$sectionData` | `array` | optional available sections |

---


### vakata\views\View::render
Render the template.  


```php
public function render (  
    array $data  
) : string    
```

|  | Type | Description |
|-----|-----|-----|
| `$data` | `array` | optional data to use when rendering |
|  |  |  |
| `return` | `string` | the result |

---

