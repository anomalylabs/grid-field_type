## Usage[](#usage)

This section will show you how to use the field type via API and in the view layer.


### Setting Values[](#usage/setting-values)

Setting Grid values is best left to the control panel.

However, you can set the repeater field type value programmatically with a grid model instance:

    $entry->example = $grid;

You can set the value with a collection of grid instances too:

    $entry->example = $collection;

Lastly you can set the repeater field type value with an array of grid IDs:

    $entry->example = [7, 8];


### Basic Output[](#usage/basic-output)

The `grid` field type always returns an `\Anomaly\GridFieldType\Grid\GridCollection` instance of related `GridModel` instances.

###### Example

    $entry->example; // Collection of models.

###### Twig

    {% for entry in entry.example %}

        {% if entry.type == 'books' %}
            <h2>{{ entry.title }}</h2>
            <p>{{ entry.description }}</p>
        {% endif %}

        {% if entry.type == 'movies' %}
            {{ entry.cover.thumbnail|raw }}
            <h2>{{ entry.title }}</h2>
            <p>{{ entry.description }}</p>
        {% endif %}

    {% endfor %}


#### Including Partials[](#usage/basic-output/including-partials)

A more elegant way of rendering different grid types would use Twig's `include` feature. Assuming your page has a Grid field with the slug `content` assigned to it your page type's layout might look like this:

    {% for section in page.content %}
        <div class="section {{ section.type }}-section-type">
            {% include "theme::sections/" ~ section.type %}
        </div>
    {% endfor %}


##### GridCollection::views()[](#usage/basic-output/including-partials/gridcollection-views)

The `views` method helps automate rendering partials as mentioned above:

`{{ entry.example.views|raw }}`

The above will look for partials in `theme::grids/{type}` and the grid object within those views will be named `grid`.


##### Customizing Output[](#usage/basic-output/including-partials/customizing-output)

Below is an example of some options you can pass via the `PluginCriteria` returned:

`{{ entry.example.views.path('theme::blocks').name('block').cache(2)|raw }}`

The above will look for partials in `theme::blocks/{type}` and the grid object within those views will be named `block`. Everything will be cached in the model cache repository for 2 minutes.


### Presenter Output[](#usage/presenter-output)

When accessing the field value from a decorated entry model the collection will contain instances of `\Anomaly\GridFieldType\Grid\GridPresenter`.

###### Example

    $decorated->grid->first()->email->mailto('Email me!');

###### Twig

    {{ decorated.grid.first().email.mailto('Email me!')|raw }}
