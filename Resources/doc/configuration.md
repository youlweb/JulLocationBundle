Full configuration options
==========================

JulLocationBundle takes charge of rendering the `Location` fields, and detect
duplicate entities. The configuration options allow you to control both these
aspects precisely to tailor JulLocationBundle to your app's needs.

## Default configuration

The default configuration options provide a good start to use JulLocationBundle with the Google maps
places autocomplete implementation.


``` yaml
# app/config/config.yml

jul_location:
    location:
        data_class: null
        fields:
            name:
                enabled: true
                required: false
                identifier: true
                type: null
                options: []
            long_name:
                enabled: true
                required: false
                identifier: false
                type: null
                options: []
            address:
                enabled: true
                required: false
                identifier: false
                type: null
                options: []
            long_address:
                enabled: false
                required: false
                identifier: false
                type: null
                options: []
            postcode:
                enabled: true
                required: false
                identifier: false
                type: null
                options: []
            latitude:
                enabled: true
                required: false
                identifier: false
                type: hidden
                options: []
            longitude:
                enabled: true
                required: false
                identifier: true
                type: hidden
                options: []
            image_url:
                enabled: true
                required: false
                identifier: false
                type: hidden
                options: []
            website_url:
                enabled: true
                required: false
                identifier: false
                type: null
                options: []
            phone:
                enabled: true
                required: false
                identifier: false
                type: null
                options: []
    city:
        data_class: null
        fields:
            name:
                enabled: true
                required: false
                identifier: true
                type: null
                options: []
            long_name:
                enabled: false
                required: false
                identifier: false
                type: null
                options: []
            latitude:
                enabled: false
                required: false
                identifier: false
                type: hidden
                options: []
            longitude:
                enabled: false
                required: false
                identifier: false
                type: hidden
                options: []
    state:
        data_class: null
        fields:
            name:
                enabled: true
                required: false
                identifier: true
                type: null
                options: []
            long_name:
                enabled: false
                required: false
                identifier: false
                type: null
                options: []
            short_name:
                enabled: false
                required: false
                identifier: false
                type: null
                options: []
            latitude:
                enabled: false
                required: false
                identifier: false
                type: hidden
                options: []
            longitude:
                enabled: false
                required: false
                identifier: false
                type: hidden
                options: []
    country:
        data_class: null
        fields:
            name:
                enabled: true
                required: false
                identifier: true
                type: null
                options: []
            short_name:
                enabled: false
                required: false
                identifier: false
                type: null
                options: []
            latitude:
                enabled: false
                required: false
                identifier: false
                type: hidden
                options: []
            longitude:
                enabled: false
                required: false
                identifier: false
                type: hidden
                options: []

```

**The breakdown:**

Each location entity has a mandatory `data_class` parameter. You must provide the fully qualified
class name (FQCN) of the classes you created, as per the [Installation & basic setup](installation_basic_setup.md) section of this doc.

I the `data_class` parameter is set to `null`, the corresponding entity will simply be disabled.

The `fields` sections allow you to customize how each field will interact with your app:

- `enabled` determines whether the field should be used and rendered, or disabled.
- `required` if set to true, the field will be marked as mandatory in the form validation process.
- `identifier` if set to true, the field will be used to detect a duplicate entity.
- `type` the type of field to render. Passed directly to Symfony's form builder. If set to null, the form builder will try to guess the type as usual.
- `options` array of field options. Passed directly to Symfony's form builder. See [Symfony's forms documentation](http://symfony.com/doc/current/book/forms.html)
for more information on field options.

## See also

- [Documentation summary](index.md)

