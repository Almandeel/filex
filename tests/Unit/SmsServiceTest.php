<?php

namespace Tests\Unit;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Tests\Server;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\GuzzleException;
use Mockery;
use App\Services\SmsService;

class SmsServiceTest extends TestCase
{
	public function tearDown() :void
	{
		 Mockery::close();
	}

	public function testNewWay()
    {
        $service = new SmsService();

        $this->assertInstanceOf(SmsService::class, $service);
        // This will sent real message
        // $this->assertTrue($service->sendMessage(4123123123, 'message'));
    }

    public function test_validate_invalid_to_param()
    {
        $this->expectException(\Exception::class);

        (new SmsService())->sendMessage();
    }

    public function test_throw_an_exception_when_provide_invalid_data()
    {
        $this->expectException(\Exception::class);

        (new SmsService())->sendMessage('number', self::anything());
    }

    public function test_throw_an_exception_when_provide_number_less_than_ten()
    {
        $this->expectException(\Exception::class);

        (new SmsService())->sendMessage(12345678, 'Message');
    }

    public function test_throw_an_exception_when_provide_null_values()
    {
        $this->expectException(\Exception::class);

        (new SmsService())->sendMessage();
    }

    public function test_valid_data_can_passed_the_validation()
    {
        $this->assertTrue((new SmsService())->sendMessage(1234567890, 'Message'));
    }
}
