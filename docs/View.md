# vakata\views\View
A simple template class.

## Methods

| Name | Description |
|------|-------------|
|[__construct](#vakata\views\view__construct)|Create an instance.|
|[render](#vakata\views\viewrender)|Render the template.|

---



### vakata\views\View::__construct
Create an instance.  


```php
public function __construct (  
    \vakata\views\Views $repository,  
    string $template,  
    array $sectionData,  
    array $data  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$repository` | `\vakata\views\Views` | a views repository instance |
| `$template` | `string` | the template file to be rendered |
| `$sectionData` | `array` | optional available sections |
| `$data` | `array` | optional data to use when rendering |

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

