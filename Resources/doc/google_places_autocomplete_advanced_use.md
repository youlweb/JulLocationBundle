Google places autocomplete advanced use
=======================================

JulLocationBundle Google places autocomplete configuration is handled by 
the bundle's `GooglemapsController`, which in turn feeds the `placesAutocomplete.js`
script with a comprehensive set of options and features.

Before reading further, you might want to catch up on Google's Places library lingo
by glancing at their [documentation](http://developers.google.com/maps/documentation/javascript/places)

It is also important that you read the [Google places autocomplete basic setup](google_places_autocomplete_basic_setup.md) page,
and try it for yourself, to see what it does.

**Caution:** The Google places API has proven to be quite inconsistent. For instance, when
querying places in the same city, one will return a `State` address component, and another
will not. The `Country`'s long name may be 'United States' for one place, and 'US' for the
other. This will unfortunately affect the integrity of your JulLocationBundle data.

## Full configuration

JulLocationBundle configuration takes place in the Twig template where your form resides.

Here's the full default configuration:

``` twig
{# src/Acme/TravelBundle/Resources/views/Hotel/new.html.twig #}

{% block javascripts %}

{{ parent() }}

<script src="//maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

{% render 'JulLocationBundle:Googlemaps:placesAutocomplete' with {
    locationForm: form,
    zoomDefault: 3,
    zoomResolved: 17,
    latitude: 40.4230,
    longitude: -98.7372,
    mapDiv: 'map_canvas',
    mapOptions: '',
    acFields: { 0: { 
        acInput: 'fieldId', captureEnter: true, acOptions: {}
    } },
    addressFallback: false,
    maxImageWidth: 200,
    maxImageHeight: 200
} %}

<script type="text/javascript">
    window.onload = JulAutoComplete;
</script>

{% endblock %}

```

**The breakdown:**

- `locationForm` _(required)_ The form containing the `Location` field.
- `zoomDefault` _(default `3`)_ The zoom level used when showing the map initially.
- `zoomResolved` _(default `17`)_ The zoom level used when pointing at a selected location.
- `latitude` _(default `40.4230`)_ The latitude used when showing the map initially (US).
- `longitude` _(default `-98.7372`)_ The longitude used when showing the map initially (US).
- `mapDiv` _(default `map_canvas`)_ The name of the `div` to attach the map to.
- `mapOptions` _(array)_ This array will be passed directly to the Google map constructor. See 
[Google map options documentation](http://developers.google.com/maps/documentation/javascript/tutorial#MapOptions). JulLocationBundle will default `mapTypeId` to `google.maps.MapTypeId.ROADMAP`.
- `acFields` _( array( array( acInput, captureEnter, acOptions ) ) )_ You can add as many autocomplete fields as you need, using these options:
    - `acInput` _(default is the id of the `long_name` or `name` field)_ The ID of the field you want to attach the autocomplete to.
    - `captureEnter` _(default `true`)_ Google places autocomplete submits a form automatically when you hit [ENTER] in their suggestion list. This option prevents that. Note that if you let Google handle the submitting, JulLocationBundle won't have time to fill out the location fields automatically.
    - `acOptions` _(array)_ This array is passed directly to the Google autocomplete constructor. See the [Google Places autocomplete documentation](http://developers.google.com/maps/documentation/javascript/places#places_autocomplete) for details. JulLocationBundle will default the `types` option depending on your top level `Location` entity using the following pattern:
        - Top level `Location` defaults to `establishment`.
        - Top level `City` defaults to `(cities)`.
        - Top levels `State` and `Country` default to `(regions)`.

    - To learn more about top level entities, read [Choosing a top level entity](top_level_entity.md).
- `addressFallback` _(default `false`)_ This feature is described in the next section.
- `maxImageWidth` _(default `200`)_ Sets the maximum width in pixels of the images requested to the Google places autocomplete API.
- `maxImageHeight` _(default `200`)_ Sets the maximum height in pixels of the images requested to the Google places autocomplete API.

## Address fallback

By default, the autocomplete feature is attached to your `Location`'s `long_name` field.
The type of Google places returned is set to `establishment`, which basically means businesses.

It may happen that a business exists that is not indexed at Google. In that case, you may
still want to give your visitors an opportunity to point to a valid street address, in order
to obtain a latitude/longitude to use with a map.

JulLocationBundle can handle that for you, almost automatically. Both the `long_name` and
`long_address` fields will have an autocomplete feature, the former showing businesses,
the latter resolving street addresses.

Let's see it in action, first edit your app's config file to enable the `long_address` field:

``` yaml
# app/config/config.yml

jul_location:
    Location:
        fields:
            long_address:
                enabled: true

```

Next, configure your Twig template to use the address fallback feature:

``` twig
{# src/Acme/TravelBundle/Resources/views/Hotel/new.html.twig #}

{% block javascripts %}

{{ parent() }}

<script src="//maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

{% render 'JulLocationBundle:Googlemaps:placesAutocomplete' with {
    locationForm: form,
    addressFallback: true
} %}

<script type="text/javascript">
    window.onload = JulAutoComplete;
</script>

{% endblock %}

```

That's it! JulLocationBundle takes care of setting up the autocomplete fields with the
right parameters.

The `addressFallback` option is the equivalent of the following `acFields` configuration:

``` twig

...

{% render 'JulLocationBundle:Googlemaps:placesAutocomplete' with {
    locationForm: form,
    acFields: {
        0: {
            acInput: form.location.long_name.vars.id,
            acOptions: { types: { 0: 'establishment' } }
        },
        1: {
            acInput: form.location.long_address.vars.id,
            acOptions: { types: { 0: 'geocode' } }
        }
    }

} %}

...

{% endblock %}

```

That's about it for the Google places autocomplete implementation of the JulLocationBundle.
You can dive in the `placesAutocomplete.js` file to understand further what happens in the
background. Suggestions to improve these features are welcome.

## See also

- [Choosing a top level entity](top_level_entity.md)
- [Documentation summary](index.md)

