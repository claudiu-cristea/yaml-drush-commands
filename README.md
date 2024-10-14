# YAML Drush Commands

[![ci](https://github.com/claudiu-cristea/yaml-drush-commands/actions/workflows/ci.yml/badge.svg)](https://github.com/claudiu-cristea/yaml-drush-commands/actions/workflows/ci.yml)

Allows to define Drush command in YAML files (*.yml, *.yaml).

## How it works?

You can define commands in YAML files, placed under `Drush/YamlCommands/` directory, relative to a PSR-4 base namespace. For instance, if a package maps this PSR-4 autoload entry:

```json
"autoload": {
    "psr-4": {
        "My\\Custom\\Library\\": "src"
    }
}
```

then the YAML files should be placed under the `src/Drush/YamlCommands` directory.

Here's an example of YAML file defining Drush commands:

```yaml
commands:
  say-hello:
    aliases:
      - sh
      - sayh
    description: 'Greetings, creates a file and displays the current directory'
    help: |
        This command performs the following:
          - Greets you
          - Creates a file.txt
          - Displays the current directory
    tasks:
        - task: exec
          params:
              exec: echo "Hello World!"
        # Shortcut for
        # - task: exec
        #   params:
        #       exec: touch file.txt
        - touch file.txt
        - task: exec
          params:
              exec: pwd
        # This command will show nothing as its output is suppressed
        - task: exec
          params:
              exec: echo "Bye!"
          output: false
  other-cmd:
    description: ...
    tasks: ...
```

## Contributing

Using [DDEV](https://ddev.com) is the recommended way to contribute. Check [DDEV's documentation](https://ddev.readthedocs.io) to find out how to install and use DDEV.

This package provides the following DDEV commands:

- `ddev phpcs`: Runs coding standards checks
- `ddev phpcbf`: Fixes most of the coding standards violations
- `ddev phpstan`: Runs PHP static analysis
- `ddev phpunit`: Runs PHPUnit tests
