## 0.2.0

- Add `Configlet\Config::import(Config)` that will populate a configuration's store with another Configlet object
- Add `Configlet\Config::toArray()` that will return a native PHP array representation of the configuration
- Add `Configlet\Config::hydrate([])` that will return an associative array filled with values from the config matching the values of the passed array
- Add Configlet boolean configuration `configlet.autoimport_app_config` which will cause `MasterConfig::offsetGet` to `import()` the scalar app configuration values into module configs when returned.

## 0.3.0

- Add `Configlet\Config::lock()` method that prevents even mutable configs from being changed after it is called.

