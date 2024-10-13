Place a YAML file under `src/Drush/YamlCommands`, where `src` is PSR-4 mapped directory. E.g., is defined under the `autoload.psr-4` in `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "My\\Custom\\Library\\": "src"
        }
    },
}
```

The file content:

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
