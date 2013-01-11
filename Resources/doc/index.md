JulLocationBundle quick setup
=============================

JulLocationBundle provides 4 location entities configured to avoid duplicates:

1. Location
2. City
3. State
4. Country

You can use as little as the `Country` entity, or as much as the full precision
`Location` entity, and everything in between.

In addition, a Googlemap places autocomplete implementation can be activated 
to feed the entities with properly formatted data, show the location on a map,
and even offer photos of the locations when available.

## Requirements

JulLocationBundle has been designed and tested with Symfony 2.1.*.
The Googlemap implementation uses the translator on a little chunk of text.

## Setup

The basic setup is straightforward. It will get you started with the top
level `Location` entity to resolve places with street address precision. For other
uses such as `City`, or `Country` entities, see [Choosing a top level entity](advanced_use.md)

### Add JulLocationBundle to the composer

```js
{
    "require": {
        "jul/location-bundle": "dev-master"
    }
}
```

Update the vendors with the command:

``` bash
$ php composer.phar update jul/location-bundle
```

### Update the kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Jul\LocationBundle\JulLocationBundle(),
    );
}
```

### Add a location column to your existing entity

Let's say you have a `Hotel` entity for which you wish to add location
information, within a travel app for instance.

Add the location reference:

``` php
<?php
// src/Acme/TravelBundle/Entity/Hotel.php

namespace Acme/TravelBundle/Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Hotel
{
    // your own properties (id...)
    
    /**
     * @ORM\ManyToOne(targetEntity="Jul\LocationBundle\Entity\Location")
     * @ORM\JoinColumn(onDelete="set null")
     */
    private $location;

    /**
     * Set location
     *
     * @param \Jul\LocationBundle\Entity\Location $location
     */
    public function setLocation(\Jul\LocationBundle\Entity\Location $location = null)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return \Jul\LocationBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }
}
```

### Update your database:

``` bash
$ app/console doctrine:schema:update --force
```

### Add the Location field

Add the `Location` fields with a single line of code in your `Hotel`
entity form class:

``` php
<?php
// src/Acme/TravelBundle/Form/HotelType.php

namespace Acme\TravelBundle\Form

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // your own fields (name...)

            ->add('location', 'JulLocationField')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\TravelBundle\Entity\Hotel',
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'acme_travelbundle_hoteltype';
    }
}
```

**Two things to note about this code snippet:**

1. Adding all the `Location` fields takes a single line of code using the JulLocationField service.
2. Setting 'cascade_validation' to TRUE is necessary for the `Location` fields to be validated.

> It is possible to add the `Location` fields using the form builder inside a controller,
> but the [Symfony documentation](http://symfony.com/doc/current/book/forms.html#creating-form-classes) suggests to use form classes.







