<?php

namespace Augwa\ShortUrlBundle\Tests\Controller;

use Augwa\ShortUrlBundle;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @coversDefaultClass \Augwa\ShortUrlBundle\Controller\UserController
 */
class UserControllerTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @covers ::createAccount
     */
    public function testCreateAccountSuccessful()
    {
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

        $manager
            ->method('persist')
            ->will($this->returnCallback(function(ShortUrlBundle\Document\User $user) { $user->setUserId('abc'); }));

        $stub
            ->method('manager')
            ->willReturn($manager);

        $user = $stub->createAccount($this->data['email_address'], $this->data['password'], $this->data['ip_address']);

        $this->assertEquals($this->data['password'], $user->getPassword());
        $this->assertEquals($this->data['salt'], $user->getSalt());
        $this->assertEquals(ip2long($this->data['ip_address']), $user->getIpAddress());
        $this->assertEquals($this->data['email_address'], $user->getEmailAddress());
        $this->assertNotNull($user->getUserId());
        $this->assertEquals(time(), $user->getDateCreated(), '', 1);
        $this->assertGreaterThanOrEqual(time()-1, $user->getDateCreated());
    }

    /**
     * @covers ::createAccount
     * @expectedException \Augwa\ShortUrlBundle\Exception\User\DuplicateException
     */
    public function testCreateAccountFailureDuplicateEmail()
    {
        $stub = $this->getUserControllerStub($this->data);
        $stub
            ->method('emailRegistered')
            ->will($this->throwException(new ShortUrlBundle\Exception\User\DuplicateException));
        $stub->createAccount($this->data['email_address'], $this->data['password'], $this->data['ip_address']);
    }

    /**
     * @covers ::createAccount
     * @expectedException \Augwa\ShortUrlBundle\Exception\User\DuplicateException
     */
    public function testCreateAccountFailureDuplicateEmailBeforeInsert()
    {
        $stub = $this->getUserControllerStub($this->data);

        $manager = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(
                [
                    'flush',
                    'persist'
                ]
            )
            ->getMock();
        $manager
            ->method('flush')
            ->will($this->throwException(new ShortUrlBundle\Exception\User\DuplicateException));

        $stub
            ->method('manager')
            ->willReturn($manager);

        $stub->createAccount($this->data['email_address'], $this->data['password'], $this->data['ip_address']);
    }

    /**
     * @param array $data
     *
     * @return ShortUrlBundle\Controller\UserController
     */
    private function getUserControllerStub(array $data)
    {

        /** @var ShortUrlBundle\Controller\UserController $stub */
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
