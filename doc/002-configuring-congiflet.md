# Configuring Configlet

We're getting meta here but this where we discuss how you can configure your, uh, configuration. This allows you to alter the behavior of Configlet all while using the same API that you use for setting the rest of your configuration values. All of these configuration values are assumed to be set on `Configlet\MasterConfig`; setting these configuration values on other Configlet implementations may not have the intended effect.

## Configlet Keys

### `configlet.module_return_type`

- Valid Values: `MasterConfig::IMMUTABLE`, `MasterConfig::IMMUTABLE_PROXY`, `MasterConfig::MUTABLE`
- Description: Controls the type of object returned from `Configlet\AppConfiglet` when retrieving a module parameter
