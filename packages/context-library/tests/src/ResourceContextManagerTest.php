<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Context\ResourceContextManager;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\Testing\Lib\Context\ExpectFunctionOnceWillReturnTrait;
use Tailors\Testing\Lib\Context\GetContextFunctionMockTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\ResourceContextManager
 *
 * @internal
 */
final class ResourceContextManagerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetContextFunctionMockTrait;
    use ExpectFunctionOnceWillReturnTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, ResourceContextManager::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(): void
    {
        $destructor = function (): void {
        };

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('foo', $destructor);

        /** @psalm-suppress DocblockTypeContradiction */
        $this->assertSame('foo', $manager->getResource());
        $this->assertSame($destructor, $manager->getDestructor());
    }

    /**
     * @psalm-return array<array{0:string,1:?string}>
     */
    public static function provConstructSetsDefaultDestructor(): array
    {
        $values = [
            ['bzip2', '\\bzclose'],
            ['cubrid connection', '\\cubrid_close'],
            ['persistent cubrid connection', null],
            ['cubrid request', '\\cubrid_close_request'],
            ['cubrid lob', '\\cubrid_lob_close'],
            ['cubrid lob2', '\\cubrid_lob2_close'],
            ['curl', '\\curl_close'],
            ['dba', '\\dba_close'],
            ['dba persistent', null],
            ['dbase', '\\dbase_close'],
            ['dbx_link_object', '\\dbx_close'],
            ['dbx_result_object', null],
            ['xpath context', null],
            ['xpath object', null],
            ['fbsql link', '\\fbsql_close'],
            ['fbsql plink', null],
            ['fbsql result', '\\fbsql_free_result'],
            ['fdf', '\\fdf_close'],
            ['ftp', '\\ftp_close'],
            ['gd', '\\imagedestroy'],
            ['gd font', null],
            ['gd PS encoding', null],
            ['gd PS font', '\\imagepsfreefont'],
            ['GMP integer', null],
            ['imap', '\\imap_close'],
            ['ingres', '\\ingres_close'],
            ['ingres persistent', null],
            ['interbase blob', null],
            ['interbase link', '\\ibase_close'],
            ['interbase link persistent', null],
            ['interbase query', '\\ibase_free_query'],
            ['interbase result', '\\ibase_free_result'],
            ['interbase transaction', '\\ibase_free_transaction'],
            ['ldap link', '\\ldap_close'],
            ['ldap result', '\\ldap_free_result'],
            ['ldap result entry', null],
            ['SWFAction', null],
            ['SWFBitmap', null],
            ['SWFButton', null],
            ['SWFDisplayItem', null],
            ['SWFFill', null],
            ['SWFFont', null],
            ['SWFGradient', null],
            ['SWFMorph', null],
            ['SWFMovie', null],
            ['SWFShape', null],
            ['SWFSprite', null],
            ['SWFText', null],
            ['SWFTextField', null],
            ['mnogosearch agent', null],
            ['mnogosearch result', null],
            ['msql link', '\\msql_close'],
            ['msql link persistent', null],
            ['msql query', '\\msql_free_result'],
            ['mssql link', '\\mssql_close'],
            ['mssql link persistent', null],
            ['mssql result', '\\mssql_free_result'],
            ['mysql link', '\\mysql_close'],
            ['mysql link persistent', null],
            ['mysql result', '\\mysql_free_result'],
            ['oci8 connection', '\\oci_close'],
            ['oci8 statement', '\\oci_free_statement'],
            ['odbc link', '\\odbc_close'],
            ['odbc link persistent', null],
            ['odbc result', '\\odbc_free_result'],
            ['birdstep link', null],
            ['birdstep result', null],
            ['OpenSSL key', '\\openssl_free_key'],
            ['OpenSSL X.509', '\\openssl_x509_free'],
            ['pdf document', '\\pdf_delete'],
            ['pdf image', '\\pdf_close_image'],
            ['pdf object', null],
            ['pdf outline', null],
            ['pgsql large object', '\\pg_lo_close'],
            ['pgsql link', '\\pg_close'],
            ['pgsql link persistent', null],
            ['pgsql result', '\\pg_free_result'],
            ['pgsql string', null],
            ['printer', null],
            ['printer brush', null],
            ['printer font', null],
            ['printer pen', null],
            ['pspell', null],
            ['pspell config', null],
            ['shmop', '\\shmop_close'],
            ['sockets file descriptor set', '\\close'],
            ['sockets i/o vector', null],
            ['socket', '\\fclose'],
            ['sybase-db link', '\\sybase_close'],
            ['sybase-db link persistent', null],
            ['sybase-db result', '\\sybase_free_result'],
            ['sybase-ct link', '\\sybase_close'],
            ['sybase-ct link persistent', null],
            ['sybase-ct result', '\\sybase_free_result'],
            ['sysvsem', '\\sem_release'],
            ['sysvshm', '\\shm_detach'],
            ['wddx', '\\wddx_packet_end'],
            ['xml', '\\xml_parser_free'],
            ['zlib', '\\gzclose'],
            ['zlib.deflate', null],
            ['zlib.inflate', null],
        ];
        $keys = array_map(function (array $value): string {
            return $value[0];
        }, $values);

        return array_combine($keys, $values);
    }

    /**
     * @dataProvider provConstructSetsDefaultDestructor
     *
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructSetsDefaultDestructor(string $type, ?string $destructor): void
    {
        $this->expectFunctionOnceWillReturn('get_resource_type', ['foo'], $type);

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('foo');
        $this->assertSame($destructor, $manager->getDestructor());
    }

    /**
     * @runInSeprarateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructSetsDefaultDestructorOci8Collection(): void
    {
        $resource = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['free'])
            ->getMock()
        ;
        $resource->expects($this->once())
            ->method('free')
        ;

        $this->expectFunctionOnceWillReturn('get_resource_type', [$resource], 'oci8 collection');

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager($resource);

        $destructor = $manager->getDestructor();

        $this->assertIsCallable($destructor);

        call_user_func($destructor, $resource);
    }

    /**
     * @runInSeprarateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructSetsDefaultDestructorOci8Lob(): void
    {
        $resource = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['free'])
            ->getMock()
        ;
        $resource->expects($this->once())
            ->method('free')
        ;

        $this->expectFunctionOnceWillReturn('get_resource_type', [$resource], 'oci8 lob');

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager($resource);

        $destructor = $manager->getDestructor();

        $this->assertIsCallable($destructor);

        call_user_func($destructor, $resource);
    }

    /**
     * @runInSeprarateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructSetsDefaultDestructorDirStream(): void
    {
        $this->expectFunctionOnceWillReturn('get_resource_type', ['foo'], 'stream');
        $this->expectFunctionOnceWillReturn('stream_get_meta_data', ['foo'], ['stream_type' => 'dir']);

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('foo');
        $this->assertEquals('\\closedir', $manager->getDestructor());
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructSetsDefaultDestructorNonDirStream(): void
    {
        $this->expectFunctionOnceWillReturn('get_resource_type', ['foo'], 'stream');
        $this->expectFunctionOnceWillReturn('stream_get_meta_data', ['foo'], ['stream_type' => 'baz']);

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('foo');
        $this->assertEquals('\\fclose', $manager->getDestructor());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testEnterContext(): void
    {
        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('foo', function () {
        });

        /** @psalm-suppress DocblockTypeContradiction */
        $this->assertSame('foo', $manager->enterContext());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExitContextWithNullDestructor(): void
    {
        $this->expectFunctionOnceWillReturn('get_resource_type', ['dba persistent'], 'dba persistent');

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('dba persistent');
        $this->assertNull($manager->getDestructor());
        $manager->exitContext();
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExitContextWithCallableDestructor(): void
    {
        $destructor = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['__invoke'])
            ->getMock()
        ;
        $destructor->expects($this->once())
            ->method('__invoke')
            ->with('foo')
        ;

        /** @psalm-suppress InvalidArgument */
        $manager = new ResourceContextManager('foo', $destructor);

        $this->assertFalse($manager->exitContext());
    }
}

// vim: syntax=php sw=4 ts=4 et:
