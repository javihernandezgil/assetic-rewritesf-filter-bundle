Assetic RewriteSF Filter Bundle
===============================

Rewrites css/js bundle referenced resource urls.

## Install

    "repositories": [
        {
            "url": "git@github.com:javihgil/assetic-rewritesf-filter-bundle.git",
            "type": "vcs"
        }
    ],
    "require": {
	...
        "javihgil/assetic-rewritesf-filter-bundle": "dev-master",
	...
    },

## Use

Configure assetic in *app/config/config.yml*:

	assetic:
	    ...
	    filters:
	        rewritesf: 
	             resource: %kernel.root_dir%/../src/Jhg/AsseticRewritesfFilterBundle/Resources/config/rewritesf.xml
	             apply_to: "\.(less|css|scss)$"
	        ...

## Example: css/less/sass @imports override

**config.yml**
    
    assetic:
        debug:          "%kernel.debug%"
        use_controller: true
        bundles:        ['ExampleBundle']
        ruby: "%assetic_ruby_bin%"
        filters:
            rewritesf: 
                 resource: %kernel.root_dir%/../src/Jhg/AsseticRewritesfFilterBundle/Resources/config/rewritesf.xml
                 apply_to: "\.(scss)$"
            sass:
                bin: "%assetic_compass_bin%"
            compass:
                bin: "%assetic_compass_bin%"
                apply_to: "\.(scss)$"

**View file**

    {% block styles %}
        {% stylesheets 
            "@ExampleBundle/Resources/assets/styles/example.scss"
            output='sass.css'
        %}
            <link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}
    {% endblock %}

**src/ExampleBundle/Resources/assets/styles/example.scss**

    @import '@ExampleBundle/Resources/assets/styles/variables.scss';

    body {
        background-color: $color !important;
    }

**src/ExampleBundle/Resources/assets/styles/variables.scss**

    $color: red;

**app/Resources/ExampleBundle/assets/styles/variables.scss**

    $color: green;

**Result**

If *app/Resources/ExampleBundle/assets/styles/variables.scss* file exists the result is 

    body {
        background-color: green !important;
    }

Else the result is 

    body {
        background-color: red !important;
    }
