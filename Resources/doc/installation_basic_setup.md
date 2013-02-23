Installation & basic setup
==========================

Installing JulLocationBundle for Symfony is straightforward, thanks to comprehensive
default settings.

## Requirements

JulLocationBundle has been designed and tested with Symfony 2.1.*.
The Googlemap implementation uses the translator on a little chunk of text, and Twig templates.

## Setup

The basic setup will get you started with the top level `Location` entity to resolve
places with street address precision. If you only need `City`, `State` or `Country` precision,
see [Choosing a top level entity](top_level_entity.md).

### Add JulLocationBundle to the composer

``` bash
$ php composer.phar require jul/location-bundle:dev-master

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

### Create the classes

The `Location` entities are meant to be pointed at by other entities through
relationship associations.

To get started, you must create classes that extend each JulLocationBundle entity.

**The travel app example:**

Imagine you are building a travel app with a hotel directory.

**Your `Hotel` class should point at the `Location` entity:**

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
    // your own properties
    
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(onDelete="set null")
     */
    protected $location;
    
    public function setLocation( $location )
    {
        $this->location = $location;
    }
    
    public function getLocation()
    {
        return $this->location;
    }
}

```

**Your `Location` class should point at the `City` entity:**

``` php
<?php
// src/Acme/TravelBundle/Entity/Location.php

namespace Acme/TravelBundle/Entity;

use Jul\LocationBundle\Entity\Location as BaseLocation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Location extends BaseLocation
{
    // your own properties
    
    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(onDelete="set null")
     */
    protected $city;

    public function setCity( $city )
    {
        $this->city = $city;
    }
    
    public function getCity()
    {
        return $this->city;
    }
}

```

**Your `City` class should point at the `State` entity:**

``` php
<?php
// src/Acme/TravelBundle/Entity/City.php

namespace Acme/TravelBundle/Entity;

use Jul\LocationBundle\Entity\City as BaseCity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class City extends BaseCity
{
    // your own properties
    
    /**
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(onDelete="set null")
     */
    protected $state;

    public function setState( $state )
    {
        $this->state = $state;
    }
    
    public function getState()
    {
        return $this->state;
    }
}

```

**Your `State` class should point at the `Country` entity:**

``` php
<?php
// src/Acme/TravelBundle/Entity/State.php

namespace Acme/TravelBundle/Entity;

use Jul\LocationBundle\Entity\State as BaseState;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class State extends BaseState
{
    // your own properties
    
    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(onDelete="set null")
     */
    protected $country;

    public function setCountry( $country )
    {
        $this->country = $country;
    }
    
    public function getCountry()
    {
        return $this->country;
    }
}

```

**Your `Country` class has no association, but must exist in your bundle:**

``` php
<?php
// src/Acme/TravelBundle/Entity/Country.php

namespace Acme/TravelBundle/Entity;

use Jul\LocationBundle\Entity\Country as BaseCountry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Country extends BaseCountry
{
    // your own properties
}

```

### Configure the bundle

Next, you must configure your classes in your app's configuration file:

``` yaml
# app/config/config.yml

jul_location:
    location:
        data_class: Acme\TravelBundle\Entity\Location
    city:
        data_class: Acme\TravelBundle\Entity\City
    state:
        data_class: Acme\TravelBundle\Entity\State
    country:
        data_class: Acme\TravelBundle\Entity\Country

```

There are many more things you can configure to fit JulLocationBundle to your
application requirements, as described in the [Full configuration options](configuration.md)
page.

### Update your database

``` bash
$ app/console doctrine:schema:update --force

```

### Add the Location field to your Hotel form class 

Render all the Location fields at once, with a single line of code:

``` php
<?php
// src/Acme/TravelBundle/Form/HotelType.php

namespace Acme\TravelBundle\Form

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HotelType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder

            // your own fields (name...)

            ->add( 'location', 'JulLocationField' ) // renders all necessary fields
        ;
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
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

**Note: setting 'cascade_validation' to TRUE is necessary for the `Location` fields to be validated**

Although including the location form takes a single line of code, you can configure the fields in details
as described in the [Full configuration options](configuration.md) page.

> It is possible to add the `Location` fields using the form builder inside a controller,
> but the [Symfony documentation](http://symfony.com/doc/current/book/forms.html#creating-form-classes) suggests to use form classes.

## All set

At this point, a new Hotel form will include a complete set of location fields that will
handle duplicates for you automatically, thanks to a data transformer instantiated through
the `JulLocationField` service.

``` php
<?php
// src/Acme/TravelBundle/Controller/HotelController.php

namespace Acme\TravelBundle\Controller;

use Acme\TravelBundle\Entity\Hotel;
use Acme\TravelBundle\Form\HotelType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HotelController extends Controller
{
    public function newAction()
    {
        $hotel = new Hotel();
        $form = $this->createForm( new HotelType(), $hotel );
        
        ...
}

```

But wouldn't it be great to fill the form with properly formatted place names from a
service such as Google maps? Read on to find out how.

## See also

- [Google places autocomplete basic setup](google_places_autocomplete_basic_setup.md)
- [Documentation summary](index.md)

