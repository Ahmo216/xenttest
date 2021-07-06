# Custom code importer

Adds support for importing custom code from a git repository. The custom
files can either extend the existing classes or add completely new features.

## Instructions for the custom files

The files in the git repository need to have the same directory structure
as the files in the xentral repository.

Example structure of a git repository:

```
├── cronjobs
│   └── cronjob_custom.php
└── www
    ├── lib
    │   └── class.test_custom.php
    └── pages
        ├── content
        │   └── template_custom.tpl
        └── page_custom.php
```

- The name of each custom file needs to end with `_custom`
- Class names must end with `Custom`, for example `PrintTemplateCustom`
- Files need to be in correct directories (i.e. `.tpl` files under `/www/pages/content/`)

## The importing process

The process of importing custom files works like this:

1. Any existing custom files get removed from the project tree
2. The given git repository gets cloned into the `userdata/custom_code/` directory
3. The cloned files get validated for correct naming and valid target directories
4. The files get copied from the userdata directory into the project tree

## See also

- The library used for interaction with git: https://github.com/cpliakas/git-wrapper
- Confluence page: https://xentral.atlassian.net/wiki/spaces/DEV/pages/1059455113/CustomCodeImporter
