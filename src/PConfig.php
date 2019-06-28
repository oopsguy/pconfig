<?php

namespace pconfig;

use ArrayAccess;
use Closure;
use Exception;
use pconfig\provider\impl\FileProvider;
use pconfig\provider\IProvider;
use pconfig\serializer\ISerializer;
use pconfig\utils\ArrayUtil;
use pconfig\utils\ClassUtil;

/**
 * Class PConfig
 * @package pconfig
 */
class PConfig implements ArrayAccess
{

    const CONFIG_KEY_FILE = "file";

    const CONFIG_KEY_DATA = "data";

    const CONFIG_KEY_PROVIDER = "provider";

    const CONFIG_KEY_SERIALIZER = "serializer";

    const CONFIG_KEY_EXTRACT_CONFIG = "config";

    /**
     * Key for config key separator
     */
    const CONFIG_KEY_EXTRACT_SEPARATOR = 'separator';

    const EXISTS_MODE_BREAK = 0;

    const EXISTS_MODE_APPEND = 1;

    /**
     * @var ISerializer config serializer implementation
     */
    private $serializer;

    /**
     * @var FileProvider config data provider implementation
     */
    private $provider;

    /**
     * @var array data
     */
    private $data = [];

    /**
     * @var array parsing config
     */
    private $config = [
        self::CONFIG_KEY_EXTRACT_SEPARATOR => '.',
    ];

    /**
     * $config = [
     *  'data' => [],
     *  'file' => string,
     *  'serializer' => ISerializer,
     *  'provider' => IProvider,
     *  'config' => []
     * ]
     * PConfig constructor.
     * @param $config
     * @throws Exception
     */
    public function __construct($config)
    {
        if (is_string($config)) {
            $this->provider = new FileProvider($config);
            $this->serializer = $this->detectSerializer($config);
            $this->loadData();
        } elseif (is_array($config)) {
            $file = null;
            $this->provider = $config[self::CONFIG_KEY_PROVIDER];
            $this->serializer = $config[self::CONFIG_KEY_SERIALIZER];

            if (isset($config[self::CONFIG_KEY_DATA])) {
                $this->data = $config[self::CONFIG_KEY_DATA];
            } elseif (isset($config[self::CONFIG_KEY_FILE])) {
                $file = $config[self::CONFIG_KEY_FILE];
                if (is_null($this->provider)) {
                    $this->provider = new FileProvider($file);
                }

                if (is_null($this->serializer)) {
                    $this->serializer = $this->detectSerializer($file);
                }
                $this->loadData();
            } else {
                if (is_null($this->provider)) {
                    throw new Exception("must provide 'data', 'file' or 'provider'");
                }
                $this->loadData();
            }

            if (isset($config[self::CONFIG_KEY_EXTRACT_CONFIG])) {
                $extraConfig = $config[self::CONFIG_KEY_EXTRACT_CONFIG];
                if (is_array($extraConfig)) {
                    $this->config = array_merge($this->config, $extraConfig);
                }
            }
        } else {
            throw new Exception('invalid parameter, require a string or an array');
        }
    }

    /**
     * @param $file
     * @return ISerializer
     * @throws Exception
     */
    private function detectSerializer($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $fileName = strtoupper($extension) . 'Serializer';
        $serializer = ClassUtil::loadClass("pconfig\\serializer\\impl\\{$fileName}");
        if (!$serializer instanceof ISerializer) {
            throw new Exception("Class {$fileName} is not a ISerializer!");
        }
        return $serializer;
    }

    /**
     * @throws Exception
     */
    private function loadData()
    {
        if (!$this->serializer instanceof ISerializer) {
            throw new Exception('must specify a serializer');
        }
        $content = $this->provider->read();
        $this->data = $this->serializer->deserialize($content);
    }

    /**
     * @throws Exception
     */
    public function reload()
    {
        $this->loadData();
    }

    /**
     * Save config to storage
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        if (!$this->serializer instanceof ISerializer) {
            throw new Exception('must specify a serializer');
        }
        $content = $this->serializer->serialize($this->data);
        return $this->provider->save($content);
    }

    /**
     * Set config file path
     * @param string $file file path
     */
    public function setFile($file)
    {
        $this->provider->setFile($file);
    }

    /**
     * @param IProvider $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param ISerializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * SPL interface
     * See `get` method
     * @param mixed $key config key
     * @return array|null config value
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * SPL interface
     * See `set` method
     * @param mixed $key config key
     * @param mixed $value config value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * SPL interface
     * See `delete` method
     * @param mixed $key config key
     */
    public function offsetUnset($key)
    {
        $this->delete($key);
    }


    /**
     * Get config data
     * @param null $key config key with a key-separator
     * @param mixed $default default value, will be return if key does not present
     * @return array|null value
     */
    public function get($key = null, $default = null)
    {
        if ($key == null) return $this->data;

        if (empty($key)) return [];

        $keys = $this->parseKey($key);
        $value = null;

        $this->find($this->data, $keys, function ($item) use (&$value) {
            $value = $item;
        });

        if ($default !== null && $value !== false) return $default;

        return $value;
    }

    /**
     * Remove config data
     * @param null $key config key with a key-separator
     * @return bool Weather remove successfully
     */
    public function delete($key = null)
    {
        if ($key == null) {
            $this->data = [];
            return true;
        }

        if (empty($key)) return false;

        $keys = $this->parseKey($key);

        return $this->find($this->data, $keys,
            function (/** @noinspection PhpUnusedParameterInspection */ $item, &$config, $index) {
                $isAssoc = ArrayUtil::isAssocArray($config);
                unset($config[$index]);
                //不是关联数组的数组需要从新排序下标
                if (!$isAssoc) {
                    $config = array_values($config);
                }
            });
    }

    /**
     * Set config data
     * @param string $key config key with a key-separator
     * @param mixed $value config data value
     * @return bool Weather set successfully
     */
    public function set($key, $value)
    {
        if (empty($key)) return false;

        $keys = $this->parseKey($key);

        return $this->find($this->data, $keys, function (&$item) use ($value) {
            $item = $value;
        }, self::EXISTS_MODE_APPEND);
    }

    /**
     * Get extracting config by a key
     * @param null $key config key. if null, returns whole config
     * @return array|mixed|null value
     */
    public function getConfig($key = null)
    {
        if ($key == null) return $this->config;

        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * Set extracting config
     * @param string|array $key config key
     * @param mixed $value config value
     */
    public function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                isset($this->config[$k]) && $this->config[$k] = $v;
            }
        } else {
            isset($this->config[$key]) && $this->config[$key] = $value;
        }
    }

    /**
     * SPL interface
     * Whether a key exists
     * @param mixed $key key
     * @return bool
     */
    public function exists($key)
    {
        $keys = $this->parseKey($key);

        return $this->find($this->data, $keys);
    }

    public function clear()
    {
        $this->data = [];
    }

    /**
     * Explode config key by key-separator
     * @param $key string key
     * @return array exploded key in array
     */
    private function parseKey($key)
    {
        return explode($this->config[self::CONFIG_KEY_EXTRACT_SEPARATOR], $key);
    }

    /**
     * 寻找目标配置项
     * @param array $config 配置数组
     * @param array $keys $key 配置项下标数组
     * @param Closure|null $callback 处理回调
     * @param int $existsMode 元素不能存在的时候的处理方式
     * @param int $index 当前配置下标的位置
     * @return bool 是否找到配置项
     */
    private function find(array &$config, array $keys, Closure $callback = null, $existsMode = self::EXISTS_MODE_BREAK, $index = 0)
    {
        if (!isset($config[$keys[$index]])) {
            if ($existsMode == self::EXISTS_MODE_BREAK) return false;

            $config[$keys[$index]] = [];
        }

        if (count($keys) - 1 == $index) {
            is_callable($callback) && $callback($config[$keys[$index]], $config, $keys[$index]);

            return true;
        }

        if (is_array($config[$keys[$index]])) {
            return $this->find(
                $config[$keys[$index]],
                $keys,
                $callback,
                $existsMode,
                $index + 1
            );
        }

        return false;
    }

}