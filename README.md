# 个人PHP通用库

此为本人在业余时间学习PHP的实践总结，旨在打造一个个人的通类库。

## Configuration 配置库

尽可能尝试解析各种配置文件，如`JSON`、`PHP`、`YAML`、`XML`和`INI`等格式的配置文件。配置类能够针对这些文件解析并对其内容操作。

其中用于操纵的主类是`oopsguy\cofnig\Configuration`类，其依赖了`oopsguy\config\parse\IParser`内容解析器接口和`oopsguy\config\provider\AbstractProvider`内容访问抽象类。通过定制IParser和AbstractProvider的实现，可以针对不同格式的配置内容解析和不同存储方式的配置操作。

```php
$parser = new YamlParser();
$provider = new FileProvider(['file' => 'config.yaml']);
$config = new Configuration($parser, $provider);

//获取配置项
$config->get('database.rdbms.mysql');
//设置配置值
$configg->set('database.nosql', 'redis');
//删除配置
$config->delete('language');
//...

//持久化配置
$config->save();
```

## License

MIT License



