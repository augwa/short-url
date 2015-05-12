<?php


namespace Augwa\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    /** @var array */
    protected $data = [];

    public function setUp()
    {
        $this->data = [
            'email_address' => 'user@example.org',
            'password' => '03f0b9a52e1fd01e981580acc7fc744dc0f1f18ef6b492e35ad716ace84687f1',
            'salt' => 'f1ca81316edcdde7c964f90c0ca41b1e9e53e99e223e1b5305689969b9210791',
            'secret' => 'bdde08545db00af36880c013eacd12fd8075d503df6e2585461e8f59e0e9b28a',
            'ip_address' => '127.0.0.1'
        ];
    }

    public function testUserCreateSuccess()
    {
        $client = static::createClient();

        $user = $this->getMock('\Augwa\ShortUrlBundle\Controller\UserController');
        $client->getContainer()->set('Augwa.ShortURL.User', $user);

        $client->request(
            'POST',
            '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'REMOTE_ADDR' => $this->data['ip_address']
            ],
            json_encode(
                [
                    'emailAddress' => $this->data['email_address'],
                    'password' => $this->data['password']
                ]
            )
        );

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testUserCreateFailurePasswordTooShort()
    {
        $client = static::createClient();

        $stub = $this->getUserControllerStub($this->data);

        $manager = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(
                [
                    'persist',
                    'flush'
                ]
            )
            ->getMock();

        $stub
            ->method('manager')
            ->willReturn($manager);

        $stub
            ->method('manager')
            ->willReturn($manager);

        $client->getContainer()->set('Augwa.ShortURL.User', $stub);

        $client->request(
            'POST',
            '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'REMOTE_ADDR' => $this->data['ip_address']
            ],
            json_encode(
                [
                    'emailAddress' => $this->data['email_address'],
                    'password' => 'a'
                ]
            )
        );

        $json = json_decode($client->getResponse()->getContent());

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame(400, $json->code);
        $this->assertSame("invalid_data_exception", $json->exception);
    }

    public function testUserCreateFailurePasswordMissingEmailAddress()
    {
        $client = static::createClient();

        $stub = $this->getUserControllerStub($this->data);
        $client->getContainer()->set('Augwa.ShortURL.User', $stub);

        $client->request(
            'POST',
            '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'REMOTE_ADDR' => $this->data['ip_address']
            ],
            json_encode(
                [
                    'password' => $this->data['password']
                ]
            )
        );

        $json = json_decode($client->getResponse()->getContent());

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame(400, $json->code);
        $this->assertSame("missing_parameter_exception", $json->exception);
    }

    public function testUserCreateFailurePasswordMissingPassword()
    {
        $client = static::createClient();

        $stub = $this->getUserControllerStub($this->data);
        $client->getContainer()->set('Augwa.ShortURL.User', $stub);

        $client->request(
            'POST',
            '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'REMOTE_ADDR' => $this->data['ip_address']
            ],
            json_encode(
                [
                    'emailAddress' => $this->data['email_address'],
                ]
            )
        );

        $json = json_decode($client->getResponse()->getContent());

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame(400, $json->code);
        $this->assertSame("missing_parameter_exception", $json->exception);
    }

    public function testUserCreateFailureDuplicateEmail()
    {
        $client = static::createClient();

        $stub = $this->getUserControllerStub($this->data);

        $manager = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(
                [
                    'persist',
                    'flush'
                ]
            )
            ->getMock();

        $stub
            ->method('manager')
            ->willReturn($manager);

        $manager
            ->method('flush')
            ->will($this->throwException(new \Augwa\ShortUrlBundle\Exception\User\DuplicateException));

        $stub
            ->method('manager')
            ->willReturn($manager);

        $client->getContainer()->set('Augwa.ShortURL.User', $stub);

        $client->request(
            'POST',
            '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'REMOTE_ADDR' => $this->data['ip_address']
            ],
            json_encode(
                [
                    'emailAddress' => $this->data['email_address'],
                    'password' => $this->data['password']
                ]
            )
        );

        $json = json_decode($client->getResponse()->getContent());

        $this->assertSame(409, $client->getResponse()->getStatusCode());
        $this->assertSame(409, $json->code);
        $this->assertSame("duplicate_exception", $json->exception);
    }


    /**
     * @param array $data
     *
     * @return \Augwa\ShortUrlBundle\Controller\UserController
     */
    private function getUserControllerStub(array $data)
    {

        /** @var \Augwa\ShortUrlBundle\Controller\UserController $stub */
        $stub = $this
            ->getMockBuilder('\Augwa\ShortUrlBundle\Controller\UserController')
            ->setMethods(
                [
                    'repository',
                    'model',
                    'manager',
                    'has',
                    'get',
                    'setPassword',
                    'hashPassword',
                    'generateSalt',
                    'parameter',
                    'emailRegistered'
                ]
            )
            ->getMock();

        $stub
            ->method('generateSalt')
            ->willReturn($data['salt']);

        $stub
            ->method('hashPassword')
            ->willReturn($data['password']);

        $stub
            ->method('parameter')
            ->with('secret')
            ->willReturn($data['secret']);

        return $stub;
    }

}
