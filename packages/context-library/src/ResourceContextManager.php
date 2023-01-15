<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * A context manager that wraps a PHP resource and releases it at exit.
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class ResourceContextManager implements ContextManagerInterface
{
    /**
     * @psalm-var array<string,callable|string|null>
     */
    private const DEFAULT_RESOURCE_DESTRUCTORS = [
        'bzip2'                        => '\\bzclose',
        'cubrid connection'            => '\\cubrid_close',
        'persistent cubrid connection' => null,
        'cubrid request'               => '\\cubrid_close_request',
        'cubrid lob'                   => '\\cubrid_lob_close',
        'cubrid lob2'                  => '\\cubrid_lob2_close',
        'curl'                         => '\\curl_close',
        'dba'                          => '\\dba_close',
        'dba persistent'               => null,
        'dbase'                        => '\\dbase_close',
        'dbx_link_object'              => '\\dbx_close',
        'dbx_result_object'            => null,
        'xpath context'                => null,
        'xpath object'                 => null,
        'fbsql link'                   => '\\fbsql_close',
        'fbsql plink'                  => null,
        'fbsql result'                 => '\\fbsql_free_result',
        'fdf'                          => '\\fdf_close',
        'ftp'                          => '\\ftp_close',
        'gd'                           => '\\imagedestroy',
        'gd font'                      => null,
        'gd PS encoding'               => null,
        'gd PS font'                   => '\\imagepsfreefont',
        'GMP integer'                  => null,
        'imap'                         => '\\imap_close',
        'ingres'                       => '\\ingres_close',
        'ingres persistent'            => null,
        'interbase blob'               => null,
        'interbase link'               => '\\ibase_close',
        'interbase link persistent'    => null,
        'interbase query'              => '\\ibase_free_query',
        'interbase result'             => '\\ibase_free_result',
        'interbase transaction'        => '\\ibase_free_transaction',
        'ldap link'                    => '\\ldap_close',
        'ldap result'                  => '\\ldap_free_result',
        'ldap result entry'            => null,
        'SWFAction'                    => null,
        'SWFBitmap'                    => null,
        'SWFButton'                    => null,
        'SWFDisplayItem'               => null,
        'SWFFill'                      => null,
        'SWFFont'                      => null,
        'SWFGradient'                  => null,
        'SWFMorph'                     => null,
        'SWFMovie'                     => null,
        'SWFShape'                     => null,
        'SWFSprite'                    => null,
        'SWFText'                      => null,
        'SWFTextField'                 => null,
        'mnogosearch agent'            => null,
        'mnogosearch result'           => null,
        'msql link'                    => '\\msql_close',
        'msql link persistent'         => null,
        'msql query'                   => '\\msql_free_result',
        'mssql link'                   => '\\mssql_close',
        'mssql link persistent'        => null,
        'mssql result'                 => '\\mssql_free_result',
        'mysql link'                   => '\\mysql_close',
        'mysql link persistent'        => null,
        'mysql result'                 => '\\mysql_free_result',
        'oci8 collection'              => '->free',
        'oci8 connection'              => '\\oci_close',
        'oci8 lob'                     => '->free',
        'oci8 statement'               => '\\oci_free_statement',
        'odbc link'                    => '\\odbc_close',
        'odbc link persistent'         => null,
        'odbc result'                  => '\\odbc_free_result',
        'birdstep link'                => null,
        'birdstep result'              => null,
        'OpenSSL key'                  => '\\openssl_free_key',
        'OpenSSL X.509'                => '\\openssl_x509_free',
        'pdf document'                 => '\\pdf_delete',
        'pdf image'                    => '\\pdf_close_image',
        'pdf object'                   => null,
        'pdf outline'                  => null,
        'pgsql large object'           => '\\pg_lo_close',
        'pgsql link'                   => '\\pg_close',
        'pgsql link persistent'        => null,
        'pgsql result'                 => '\\pg_free_result',
        'pgsql string'                 => null,
        'printer'                      => null,
        'printer brush'                => null,
        'printer font'                 => null,
        'printer pen'                  => null,
        'pspell'                       => null,
        'pspell config'                => null,
        'shmop'                        => '\\shmop_close',
        'sockets file descriptor set'  => '\\close',
        'sockets i/o vector'           => null,
        // 'stream' => ['dir' => '\\closedir', 'STDIO' => '\fclose'],
        // 'stream' => '\\pclose',
        'socket'                       => '\\fclose',
        'sybase-db link'               => '\\sybase_close',
        'sybase-db link persistent'    => null,
        'sybase-db result'             => '\\sybase_free_result',
        'sybase-ct link'               => '\\sybase_close',
        'sybase-ct link persistent'    => null,
        'sybase-ct result'             => '\\sybase_free_result',
        'sysvsem'                      => '\\sem_release',
        'sysvshm'                      => '\\shm_detach',
        'wddx'                         => '\\wddx_packet_end',
        'xml'                          => '\\xml_parser_free',
        'zlib'                         => '\\gzclose',
        'zlib.deflate'                 => null,
        'zlib.inflate'                 => null,
    ];

    /**
     * @var resource
     *
     * @psalm-readonly
     */
    private mixed $resource;

    /**
     * @psalm-readonly
     *
     * @psalm-var string|callable|null
     */
    private mixed $destructor;

    /**
     * Initializes the context manager.
     *
     * @param resource      $resource   The resource to be wrapped;
     * @param null|callable $destructor
     *                                  Destructor function called from exitContext() to release the
     *                                  $resource. If not given or null, a default destructor will be used.
     */
    public function __construct(mixed $resource, ?callable $destructor = null)
    {
        $this->resource = $resource;
        $this->destructor = $destructor ?? self::getDefaultDestructor($resource);
    }

    /**
     * Returns the resource wrapped by this context manager.
     *
     * @psalm-mutation-free
     *
     * @psalm-return resource
     */
    public function getResource(): mixed
    {
        return $this->resource;
    }

    /**
     * Returns the destructor that is used to release the resource.
     *
     * @psalm-mutation-free
     */
    public function getDestructor(): null|callable|string
    {
        return $this->destructor;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return resource
     */
    public function enterContext(): mixed
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function exitContext(\Throwable $exception = null): bool
    {
        if (null !== $this->destructor) {
            call_user_func($this->destructor, $this->resource);
        }

        return false;
    }

    /**
     * Returns a callable that shall be used to release given $resource.
     *
     * We handle quite a lot of resource types, much of which may be
     * unavailable at runtime (not installed extensions). For these
     * resources, we return something, that is not callable.
     *
     * @psalm-param resource $resource
     */
    private static function getDefaultDestructor(mixed $resource): callable|string|null
    {
        $type = get_resource_type($resource);
        $func = self::DEFAULT_RESOURCE_DESTRUCTORS[$type] ?? null;
        if (is_string($func) && '->' === substr($func, 0, 2)) {
            $method = substr($func, 2);

            return self::mkObjectResourceDestructor($method);
        }
        if ('stream' === $type && is_null($func)) {
            return self::getStreamResourceDestructor($resource);
        }

        return $func;
    }

    private static function mkObjectResourceDestructor(string $method): callable
    {
        return function (object $resource) use ($method): void {
            $destructor = [$resource, $method];
            if (is_callable($destructor)) {
                call_user_func($destructor);
            }
        };
    }

    /**
     * @psalm-param resource $resource
     */
    private static function getStreamResourceDestructor(mixed $resource): callable
    {
        $meta = stream_get_meta_data($resource);
        if ('dir' === $meta['stream_type']) {
            return '\\closedir';
        }

        return '\\fclose';
    }
}

// vim: syntax=php sw=4 ts=4 et:
