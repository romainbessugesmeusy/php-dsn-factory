<?php
/**
 * Kloook API
 *
 * @author      Romain Bessuges <romain@kloook.com>
 * @copyright   2013 Kloook
 * @link        http://api.kloook.com
 * @license     http://api.kloook.com/license
 * @version     0.1.0
 * @package     Kloook
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace RBM\Utils;

class Dsn
{

    const MYSQL        = 'mysql';
    const MYSQL_SOCKET = 'mysql_socket';
    const DBLIB        = 'dblib';
    const SQL_SERVER   = 'sql_server';
    const PGSQL        = 'pgsql';

    protected static $_schemes = array(
        self::MYSQL        => "mysql:host=<host>;port=<port>;dbname=<dbname>",
        self::MYSQL_SOCKET => "mysql:unix_socket=<socket>;dbname=<dbname>",
        self::SQL_SERVER   => "sqlsrv:Server=<host>;Database=<dbname>",
        self::DBLIB        => "dblib:host=<host>:<port>;dbname=<dbname>;charset=<encoding>",
        self::PGSQL        => "pgsql:host=<host>;port=<port>;dbname=<dbname>;user=<user>;password=<password>",
    );

    /** @var string */
    protected $_driver;
    /** @var string */
    protected $_scheme;
    /** @var array */
    protected $_attributes;
    /** @var string */
    protected $_dsnString;

    /**
     * @param $driver
     * @param array $attributes
     */
    public function __construct($driver, $attributes = array())
    {
        $this->setDriver($driver);
        $this->setAttributes($attributes);
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->_driver;
    }

    /**
     * @param $driver
     * @throws \Exception
     */
    public function setDriver($driver)
    {
        if (!isset(self::$_schemes[$driver])) {
            throw new \Exception("Unknown driver '{$driver}'");
        }

        $this->_driver = $driver;
        $this->_scheme = self::$_schemes[$driver];
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * @param $attributes
     */
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            $this->generateDsnString();
        } catch (\Exception $e) {
            return "DSN GENERATION ERROR : {$e->getMessage()}";
        }
        return $this->_dsnString;
    }

    /**
     * @throws \Exception
     */
    protected function generateDsnString()
    {
        $missingParameters = array();

        $this->_dsnString = preg_replace_callback('#<(\w+)>#', function ($matches) use ($missingParameters) {
            $keyword = $matches[1];
            if (!isset($this->_attributes[$keyword])) {
                $missingParameters[] = $keyword;
                return "";
            }
            return $this->_attributes[$keyword];
        }, $this->_scheme);

        if (!empty($missingParameters)) {
            throw new \Exception("Missing mandatory parameters : " . PHP_EOL . implode(PHP_EOL, $missingParameters));
        }
    }
}