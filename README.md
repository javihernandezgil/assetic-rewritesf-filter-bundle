Assetic RewriteSF Filter Bundle
===============================

Rewrites css/js bundle referenced resource urls.

## Use

Configure assetic in *app/config/config.yml*:

	assetic:
	    ...
	    filters:
	        rewritesf: 
	             resource: %kernel.root_dir%/../src/Jhg/AsseticRewritesfFilterBundle/Resources/config/rewritesf.xml
	             apply_to: "\.(less|css|scss|js)$"
	        cssrewrite: ~
	        ...