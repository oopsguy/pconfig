个人业余时间学习PHP编写的练手工具类库。

## Configuration 配置库

不同的类库或者框架使用的配置文件格式可能是不一样的，这造成了配置文件不能通用的难题。本配置类库结合了自己在开发中经常遇到和使用到的一些配置文件操作方式，并汲取了其他框架操作配置文件方式的优点，它尽可能尝试解析各种配置文件，如`JSON`、`PHP`、`YAML`、`XML`和`INI`等格式的配置文件。配置类能够针对这些文件解析并对其内容操作。

其中用于操纵的主类是`oopsguy\config\Configuration`类，其依赖了`oopsguy\config\parse\IParser`内容解析器接口和`oopsguy\config\provider\AbstractProvider`内容访问抽象类。通过定制IParser和AbstractProvider的实现，可以针对不同格式的配置内容解析和不同存储方式的配置操作。

### 示例

php配置文件格式：

```php
<?php
return [
    'api' => [
        'qiniu' => null,
        'aliyun' => [
            'db' => [
                'oos' => [
                    'HELLOWORLD',
                ],
            ],
        ],
    ],
]; 
```

PHP格式配置文件操作示例：

```php
<?php
use oopsguy\config\parser\impl\JsonParser;
use oopsguy\config\provider\impl\FileProvider;
use oopsguy\config\Configuration;

//指定解析器为Josn解析器
$parser = new JsonParser();

//指定内容获取器为文件类型，参数目前只有file,标识文件位置
$params = ['file' => 'config.php'];
$provider = new FileProvider($params);

//实例化配置类，注入依赖
$config = new Configuration($parser, $provider);

echo '<pre>';

//获取并打印全部配置
print_r($config->get());
//获取指定配置项
$config->delete('api.qiniu');
//删除指定配置项
$config->delete('api.aliyun.db.2');
//设置指定配置项的值
$config->set('api.aliyun.db.4', 'Hello oopsguy');
//配置文件中没有这个配置项，则追加设置的配置项信息
$config->set('hello', 'world');
//可为配置项配置一个数组类型
$config->set('api.aliyun.site', [1,2,3]);

//检测设置后的新配置项是否有值
print_r($config->get('api.aliyun.site.2'));

//重新设置配置文件的路径
$config->setConfigFile('config/temp_config.json');
//持久化配置信息，
$config->save();
```

### Configuration API

#### 构造方法

$extraConfig为配置类处理配置文件内容的额外配置，默认`self::CONFIG_SEPARATOR => '.'`，即表示多层次获取配置时用的分割符为`.`，即可这样获取`get('config1.config2')`。配置`CONFIG_KEY_CASE`则表示处理配置时将配置项名称部分进行统一的大小写转换，默认不处理。
```php
/**
 * 构造方法
 * @param IParser $parser 内容解析器
 * @param AbstractProvider $provider 内容提供者
 * @param array $extraConfig 解析类配置
 * @throws \Exception
 */
function __construct(IParser $parser, AbstractProvider $provider, array $extraConfig = [])
```

#### set
```php
/**
 * 设置配置
 * @param $key $key 配置项名称，可以获取多层次配置，层次之间用配置分割符分割
 * @param $value 配置的值
 * @return bool 是否设置成功
 */
public function set($key, $value)
```

#### get
```php
/**
 * 获取配置项
 * @param null $key 配置项名称，可以获取多层次配置，层次之间用配置分割符分割
 * @return array|null 配置项值
 */
public function get($key = null)
```

#### delete
```php
/**
 * 删除配置项
 * @param null $key $key 配置项名称，可以获取多层次配置，层次之间用配置分割符分割
 * @return bool 是否删除成功
 */
public function delete($key = null)
```

#### setConfigFile
```php
/**
 * 设置配置文件
 * @param string $file 文件路径
 */
public function setConfigFile($file)
```

#### save
```php
/**
 * 默认持久化到文件中
 * 持久化配置文件，默认保存到原来打开的配置文件，如果已经设置了新的配置文件则使用新设置的配置文件路径。
 * @return bool
 */
public function save()
```

#### getConfig
```php
/**
 * 获取解析类配置信息
 * @param null $key 配置项，null则获取全部
 * @return array|mixed|null 配置内容
 */
public function getConfig($key = null)
```

#### setConfig
```php
/**
 * 设置解析配置
 * @param string|array $key 配置项名称
 * @param mixed $value 配置值
 */
public function setConfig($key, $value = null)
```

由于Configuration类实现了ArrayAccess接口，可以用数组的方式访问配置项：

```php
<?php
$value = $config['api.aliyun.key'];
//是等价于
$value = $config->get('api.aliyun,key');
//其他操作isset、unset也是支持的
```

### 其他配置文件格式样例


JSON格式

```json
{
  "api" : {
    "qiniu": "qiniu -api",
    "aliyun": {
      "db": [
        "redis",
        "memcached",
        "memcache",
        "nosql",
        "rdbms"
      ]
    }
  }
}
```

INI格式
```ini
[oss]
key1=qiniu
key2=aliyun
key3=tencent
[db.rdbms]
subkey=mysql
subkey1=sqlserver
subkey3=oracle
subkey4=db2
[db.other.nosql]
a=mongodb
b=redis
c=memcache
d=memcached
[hello.world.1.2]
a=0
b=6
```
XML格式
```xml
<root>
    <title>introduce</title>
    <language>PHP</language>
    <desc>
        <support>
            <databases>
                <db>mysql</db>
                <db>oracle</db>
                <db>redis</db>
                <db>sqlserver</db>
            </databases>
            <files>
                <file>json</file>
                <file>yaml</file>
                <file>xml</file>
                <file>txt</file>
            </files>
        </support>
    </desc>
</root>
```
YAML格式
```yaml
new_post_name: :title.md
default_layout: post
highlight:
  enable: true
  line_number: true

baidusitemap:
  path: baidusitemap.xml

database:
  rdbms:
    oracle
    mysql
    sqlserver
  nosql:
    mongodb
    redis
```

## License

MIT License



