## Introduction[](#introduction)

A powerful repeating data structure field type.

<div class="alert alert-danger">**Heads Up:** The Grid field type is a paid addon! Please checkout our store or ask about our developer partnership for repository access.</div>


### Configuration[](#introduction/configuration)

Below is a list of available configuration with default values:

    "example" => [
        "type"   => "anomaly.field_type.grid",
        "config" => [
            "related"        => [],
            "max"            => null,
            "min"            => null,
            "add_row"        => "anomaly.field_type.grid::button.add_row",
        ]
    ]

###### Configuration

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>Key</th>

<th>Example</th>

<th>Description</th>

</tr>

</thead>

<tbody>

<tr>

<td>

related

</td>

<td>

[`\Anomaly\GridsModule\Example\ExampleModel`]

</td>

<td>

The related grid models. You can also define any models via API.

</td>

</tr>

<tr>

<td>

max

</td>

<td>

5

</td>

<td>

The maximum items allowed.

</td>

</tr>

<tr>

<td>

min

</td>

<td>

2

</td>

<td>

The minimum items allowed.

</td>

</tr>

<tr>

<td>

add_row

</td>

<td>

module::button.add_example

</td>

<td>

The translatable text for the "add row" button.

</td>

</tr>

</tbody>

</table>
