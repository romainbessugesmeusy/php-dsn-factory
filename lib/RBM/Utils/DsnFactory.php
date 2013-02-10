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

class DsnFactory
{

    const MYSQL = 'mysql';
    const MYSQL_SOCKET = 'mysql_socket';

    protected static $_schemes = [
        self::MYSQL => "mysql:host=<host>;port=<port>;dbname=<dbname>",
        self::MYSQL_SOCKET => "mysql:unix_socket=<socket>;dbname=<dbname>",
    ];

    protected $_scheme;

    protected $_attributes;

    public function __construct($driver, $attributes = array())
    {
        if (!isset(self::$_schemes[$driver])) {
            throw new \Exception("Unknown driver '{$driver}'");
        }

        $this->_scheme = self::$_schemes[$driver];
        $this->_attributes = $attributes;
    }


    public function getDsn()
    {
        $dsn = preg_replace_callback('#<(\w+)>#', function ($matches) {
            $keyword = $matches[1];
            if(!isset($this->_attributes[$keyword])){
                throw new \Exception("Required attribute '{$keyword}' was not specified");
            }
            return $this->_attributes[$keyword];
        }, $this->_scheme);

        return $dsn;
    }
}