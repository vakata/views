# vakata\views\Views
A simple template repository class.

## Methods

| Name | Description |
|------|-------------|
|[__construct](#vakata\views\views__construct)|Create an instance|
|[dir](#vakata\views\viewsdir)|Add a directory to the template list. The name is used when searching for the template.|
|[share](#vakata\views\viewsshare)|Share a single variable or an array of variables in all templates.|
|[get](#vakata\views\viewsget)|Get a View instance.|
|[render](#vakata\views\viewsrender)|Render a template.|

---



### vakata\views\Views::__construct
Create an instance  


```php
public function __construct (  
    string|null $dir,  
    array $data  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$dir` | `string`, `null` | optional default dir for views |
| `$data` | `array` | optional preshared data |

---


### vakata\views\Views::dir
Add a directory to the template list. The name is used when searching for the template.  


```php
public function dir (  
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


### vakata\views\Views::share
Share a single variable or an array of variables in all templates.  


```php
public function share (  
    string|array $var,  
    mixed $value  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$var` | `string`, `array` | the variable name, or an array of variables |
| `$value` | `mixed` | if $var is an array omit this parameter, otherwise this is the value of the variable |
|  |  |  |
| `return` | `self` |  |

---


### vakata\views\Views::get
Get a View instance.  


```php
public function get (  
    string $template,  
    array $sections  
) : \vakata\views\View    
```

|  | Type | Description |
|-----|-----|-----|
| `$template` | `string` | the template to render |
| `$sections` | `array` | optional sections to use in rendering |
|  |  |  |
| `return` | [`\vakata\views\View`](View.md) | the view instance |

---


### vakata\views\Views::render
Render a template.  


```php
public function render (  
    string $template,  
    array $data,  
    array $sections  
) : string    
```

|  | Type | Description |
|-----|-----|-----|
| `$template` | `string` | the template to render |
| `$data` | `array` | optional data to use in rendering |
| `$sections` | `array` | optional sections to use in rendering |
|  |  |  |
| `return` | `string` | the result |

---

