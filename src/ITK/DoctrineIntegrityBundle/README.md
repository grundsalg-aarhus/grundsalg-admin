# Doctrine Integrity Bundle

This Symfony bundle provides two Twig extensions for testing if an entity can be deleted without causing an integrity violation in your entity model. 

Doctrine will throw a ForeignKeyConstraintViolationException if you attempt to delete an entity with references to it. However, catching the exception and displaying an error to the user isn’t always enough to provide a good user experience. 

The Twig extensions this bundle provides enable applications to adapt the UI depending on whether an entity has references or not.

## Installation 

**Step 1:** Download the Bundle

`composer require itk/doctrine-integrity-bundle` 

**Step 2:** Enable the Bundle

Then, enable the bundle by adding the following line in the app/AppKernel.php file of your project: 

```
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new ITK\DoctrineIntegrityBundle\ITKDoctrineIntegrityBundle(),
        );

        // ...
    }

    // ...
}
```

## Usage

When called the extension analyzes the Doctrine Metadata and checks for any relations that do not have 'cascade delete' configured. 
It then queries the database for the given entity to see how many of each relation the entity has. 

### Test: _deleteable_

To test if an entity can be deleted use the 'deletable' test in Twig:

```
{% if entity is deleteable %}
    {{ include('delete_form.html.twig') }}
{% else %}
    {{ include('cannot_delete_message.html.twig') }}
{% endif %} 
``` 

### Function: _get_not_deleteable_info(entity)_

Given Category and Product entities with a 1:* relationship, so that each product can be part of one Category. If you 
call `get_not_deleteable_info(entity)` on a Category with 46 Products:
      
```
{% set info = get_not_deleteable_info(entity) %}

{{ dump(info) }} 
```

You will get:

```
array:2 [▼
  "total" => 46
  "references" => array:1 [▼
    "AppBundle\Entity\Product" => array:1 [▼
      "Category" => 46
    ]
  ]
]
```
If there are more relations from different entities they will be listed individually under 'references' 

## Requires

```
doctrine/orm
twig/twig
```


