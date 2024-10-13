Place a YAML file under `src/Drush/YamlCommands`, where `src` is PSR-4 mapped director. E.g., is defined under the `autoload.psr-4` in `composer.json`:

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
    description: 'This command displays "Hello World!", creates an empty file "file.txt" and displays the current directory'
    tasks:
        - task: exec
          exec: echo "Hello World!"
        # Shortcut for
        # - task: exec
        #   exec: touch file.txt
        - touch file.txt
        - task: exec
          exec: pwd
        # This command wil show nothing as its output is suppressed
        - task: exec
          exec: echo "Bye!"
          output: false
  other-cmd:
    description: ...
    tasks: ...
```
