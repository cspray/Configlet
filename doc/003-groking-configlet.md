# groking Configlet

This document gives you some fine-grained details on how Configlet works and some caveats you should be aware of when using Configlet. If you really wanna get the most out of any library you must grok it; at the end of this article you should grok Configlet.

## Primary functionality provided by `Configlet\MasterConfig`

Most of the functionality that we talk about with Configlet is provided in the `Configlet\MasterConfig` implementation. It alone understands how to parse out our `<module>.<parameter>` syntax and how to associate the correct parameter values to the appropriate modules. Meaning, if you instantiate any of the other `Configlet\Config` implementations you will not get similar functionality. Most of the other `Config` implementations are "dumb" key/value storage objects.

## Understanding module parameters with `Configlet\MasterConfig`

Configlet comes out of the box providing some pretty cool, as cool as configurations can get, features. However, it is important to understand how one of those features is provided; the "magic" module parameter. Let's take a look at what this module parameter is:

```php
<?php

use \Configlet\MasterConfig;

$Config = new MasterConfig();
$Config['module.foo'] = 'foo';
$Config['module.bar'] = 'foobar';
$Config['module.baz'] = 'configlet';

$ModuleConfig = $Config['module'];

var_dump($ModuleConfig['foo']); // string 'foo' (length=3)

?>
```

Now the important thing to note here is the line `$ModuleConfig = $Config['module']` and any accessing of a module name as a parameter to a `MasterConfig` instance. Typically when you do not specify a module for a parameter we implicitly change that parameter to `app.parameter`; in this case you might think that we're changing `$Config['module']` to `$Config['app.module']` but we are not actually doing that here. When you pass a parameter that is recognized as a module name we short-circuit normal processing and we go ahead and give you that module config.

This behavior in and of itself isn't that hard to figure out or use. However, it does introduce some interesting behavior when you start setting app configurations using parameters that are module names. See this example:

```php
<?php

use \Configlet\MasterConfig;

$Config = new MasterConfig();
$Config['module'] = true;   // sets app.module to true
$Config['module.foo'] = 'something';
$Config['module'] = false;  // throws an exception because we can't tell if you want to change app.module or the actual module configuration

?>
```

The first call to setting `$Config['module']` completes successfully because at the time there is no module configuration called 'module'. However, when you set it the second time there's ambiguity, the rest of the `ArrayAccess` API on `MasterConfig` operates on the assumption that parameters matching module names are working on those configurations. `MasterConfig::offsetSet` works on this same assumption to maintain consistency with the API. We don't let you set an already created module configuration to a scalar value because this seems more likely to be the result of a misunderstanding and attempting to set a value to an app configuratino that shares a name with a module.

Ideally, we recommend that you do not use module names as parameters in your app configuration. However, if you need to do this then the following code replicates the above code without throwing the exception and properly setting the 'module' parameter in the app configuration.


```php
<?php

use \Configlet\MasterConfig;

$Config = new MasterConfig();
$Config['module'] = true;    // sets app.module to true
$Config['module.foo'] = 'something';
$Config['app.module'] = false; // sets app.module to false

?>
```

This is, ultimately, the real secret to understanding the app configuration with modules; if you want to set a parameter to your app configuration that shares a name with a module be explicit and use the `app.<mode_name>` syntax.
