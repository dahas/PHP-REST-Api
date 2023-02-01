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

    public function testPost()
    {
        $url = 'http://localhost:2400/v1/Users';
        $ch = curl_init($url);
        $data = [
            "name" => "Greta Garbo",
            "age" => "93",
            "city" => "Hollywood",
            "country" => "California"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->assertStringContainsString("HTTP/2.0 201", $output);
    }

    public function testPut()
    {
        $url = 'http://localhost:2400/v1/Users/1';
        $ch = curl_init($url);
        $data = [
            "name" => "Jenny Piloso",
            "age" => "29",
            "city" => "Flagstaff",
            "country" => "Arizona"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->assertStringContainsString("HTTP/2.0 202", $output);
    }

    public function testDelete()
    {
        $url = 'http://localhost:2400/v1/Users/6';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->assertStringContainsString("HTTP/2.0 205", $output);
    }

    public function testNotAuthorized()
    {
        $url = 'http://localhost:2400/v1/Users';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{abcde}");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $this->assertStringContainsString("HTTP/2.0 401", $output);
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