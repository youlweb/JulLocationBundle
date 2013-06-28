Google places autocomplete setup
================================

JulLocationBundle for Symfony includes a Google places autocomplete implementation
with the following features:

- Autocomplete places suggestions on a form field.

Upon clicking a suggestion:

- Fill out the form automatically with relevant data
- Display the location on a map.
- Offer to select a photograph of the location when available.

**Caution:** The Google places API has proven to be inconsistent sometimes. For instance, when
querying places in the same city, one will return a `State` address component, and another
will not. The `Country`'s long name may be 'United States' for one place, and 'US' for the
other. This will unfortunately affect the integrity of your JulLocationBundle data.

## Autocomplete Setup

The Google places autocomplete implementation is called in the template where you render
your 'new entity' form.

> In this section, we will use our travel app example from the [Installation & basic setup](installation_basic_setup.md).
> We also assume that your main Twig layout has a `javascripts` block.

**Copy and paste the content of the `javascripts` block:**

``` twig
{# src/Acme/TravelBundle/Resources/views/Hotel/new.html.twig #}

{% block javascripts %}

{{ parent() }}

<script src="//maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

{% render controller( 'JulLocationBundle:Googlemaps:placesAutocomplete', {
    locationForm: form
} ) %}

<script type="text/javascript">
    window.onload = JulAutoComplete;
</script>

{% endblock %}

```

> You must replace the variable `form` with the name you gave the form containing
> the `JulLocationField`.

If you followed the [Installation & basic setup](installation_basic_setup.md),
JulLocationBundle should detect your configuration, and attach the autocomplete
feature to the `Long name` field automatically.

## Map and photos

To visualize the location on a map, and select a photograph if available, you must
add some CSS and a couple of DIVs to your page.

JulLocationBundle provides a simple CSS file for the photo selector, to give you
something to start with. Let's import it.

> We assume that your main Twig layout has a `stylesheets` block,
> as well as a `content` block where the content of your page resides.

**Add the following to the same template as above:**

``` twig
{# src/Acme/TravelBundle/Resources/views/Hotel/new.html.twig #}

{% block stylesheets %}
    {{ parent() }}
    <link rel="stylesheet" href="{{ asset('bundles/jullocation/css/placesAutocomplete.css') }}" type="text/css" media="all" />
{% endblock %}

{% block content %}

    <div id="map_canvas" style="width: 400px; height: 400px"></div>
    <div id="JulLocationPhotoSelector"></div>

{% endblock %}

```

Voilà! You should now see a map, and it should point to the location you selected
with the autocomplete field. If the location you selected has photographs, they should
show under the map.

> If you submit the form and it is not valid, JulLocationBundle will still point the
> map to your selected location, and remember the photograph you selected.

## See also

- [Google places autocomplete advanced use](google_places_autocomplete_advanced_use.md)
- [Documentation summary](index.md)

