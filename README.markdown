JulLocationBundle
=================

JulLocationBundle has been created to handle `Location` storage within Symfony. It ships
with 4 preconfigured entities:

- `Location`
- `City`
- `State`
- `Country`

The main purpose of JulLocationBundle is to enforce that your dataset is free
of duplicate entries, according to criterias of your choosing.

Basically, if you want only one `Country` named 'United States' in your database, and 
all your `States` referring to it automatically, you're in the right place.

In addition, a Googlemap places autocomplete implementation is available to feed 
the entities with properly formatted data, show the location on a map,
and even offer photos of the location when available.

Documentation
-------------

Learn how to install and use JulLocationBundle in the [documentation](https://github.com/youlweb/JulLocationBundle/tree/master/Resources/doc/index.md)

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

