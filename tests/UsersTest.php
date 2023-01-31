<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase {

    private string $username;
    private string $password;

    protected function setUp(): void
    {
        $this->username = "rest";
        $this->password = "test";
    }

    protected function tearDown(): void
    {
        $this->foo = NULL;
    }

    public function testGet()
    {
        $url = 'http://localhost:2400/v1/Users';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->assertStringContainsString("HTTP/2.0 200", $output);
    }

    public function testNotFound()
    {
        $url = 'http://localhost:2400/v1/Abcde';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->assertStringContainsString("HTTP/2.0 404", $output);
    }
}