Choosing a top level entity
===========================

Depending on your needs, the default `Location` street address precision might be
overdone. Fortunately, you can use only bits of JulLocationBundle.

The top level entity is the most geographically precise entity of your setup.
For instance, if you wish to use only the `City` and `Country` entities, the `City`
entity is more precise than a `Country` entity, and will thus be your top level entity.

If you only wish to use the `Country` entity, it will inevitably be your top level entity.

The forms, the detection of duplicate entities, and the Google places autocomplete 
implementation will adjust automatically to your setup.

## City and Country example

Back to our travel app example, let's imagine that you are only interested in localizing
your `Hotel` entity with a `City` and a `Country` field.

### Create the classes

Most of the setup expected from you has to do with the relationship associations within
your entities' classes.

**Your `Hotel` class should point at the `City` entity:**

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

**Your `City` class should point at the `Country` entity:**

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

### Configuration

Update the JulLocationBundle configuration as follows:

``` yaml
# app/config/config.yml

jul_location:
    
    City:
        data_class: Acme\TravelBundle\Entity\City
    
    Country:
        data_class: Acme\TravelBundle\Entity\Country

```

### Update your database

If you tried the [basic setup](installation_basic_setup.md) first, you might have to drop your database,
since Doctrine doesn't appreciate you messing with associations too much.

``` bash
$ app/console doctrine:schema:update --force

```

### Add the City field to your Hotel form class

JulLocationBundle comes with 4 custom fields for you to use in your forms:

1. JulLocationField
2. JulCityField
3. JulStateField
4. JulCountryField

You only need to add the top level entity field that you chose in your Hotel class. 
The subsequent fields will be added automatically.

In our example, that's the `City` field:

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

            ->add( 'city', 'JulCityField' )
        ;
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\TravelBundle\Entity\City',
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'acme_travelbundle_hoteltype';
    }
}

```

> You must also update the `data_class` parameter to point at your top level entity.

Choosing your top level entity gives you the freedom to use only what you need from
JulLocationBundle, without having to worry about handling duplicate fields.

## Google places autocomplete implementation

Fortunately, the Google places autocomplete implementation provided in JulLocationBundle
is configured to detect your top level entity automatically, and will adjust its default
settings accordingly.

See the [Google places autocomplete basic setup](google_places_autocomplete_basic_setup.md) doc for more information.

## See also

- [Full configuration options](configuration.md)
- [Documentation summary](index.md)

