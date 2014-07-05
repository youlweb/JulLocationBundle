JulLocationBundle for Symfony
=============================

**This version is for Symfony 2.5.\*. For Symfony 2.2.\*, 2.3.\*, select the 1.1 branch. For Symfony 2.1.\*, select the `1.0` branch**

JulLocationBundle provides 4 preconfigured location entities:

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

## Documentation summary

1. [Installation & basic setup](installation_basic_setup.md)
2. [Google places autocomplete basic setup](google_places_autocomplete_basic_setup.md)
3. [Google places autocomplete advanced use](google_places_autocomplete_advanced_use.md)
4. [Choosing a top level entity](top_level_entity.md)
5. [Full configuration options](configuration.md)

