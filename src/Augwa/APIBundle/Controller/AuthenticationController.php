<?php

namespace Augwa\APIBundle\Controller;

use Augwa\APIBundle\Exception\User\AuthenticationException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use Augwa\ShortUrlBundle;

/**
 * Class AuthenticationController
 * @package Augwa\APIBundle\Controller
 */
class AuthenticationController extends Base\BaseController
{

    public function authenticateAction(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        /** @var string $route */
        $route = $request->get('_route');

        /** @var array $authenticationRoutes */
        $authenticationRoutes = $this->parameter('augwa.api.authentication_routes');

        if (true === in_array($route, $authenticationRoutes)) {

            $user = null;

            /**
             * this will accept Basic authorization
             */
            if ($request->headers->get('Authorization')) {
                $payload = explode(' ', $request->headers->get('Authorization'));
                if (sizeOf($payload) === 2 && $payload[0] === 'Basic') {
                    $credentials = explode(':', base64_decode($payload[1]));
                    if (sizeOf($credentials) === 2) {
                        $emailAddress = $credentials[0];
                        $password = $credentials[1];

                        /** @var $user ShortUrlBundle\Controller\UserController */
                        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

                        try {
                            $user = $user->authenticateByEmailAddressAndPassword($emailAddress, $password);
                        } catch (\Exception $e) {
                            if (
                                $e instanceof ShortUrlBundle\Exception\Authentication\EmailAddressNotFoundException ||
                                $e instanceof ShortUrlBundle\Exception\Authentication\PasswordMismatchException
                            ) {
                                $this->addHeader('WWW-Authenticate', sprintf('Basic realm="%s"', $request->getHttpHost()));
                            }
                            throw new AuthenticationException($e->getMessage(), $e->getCode(), $e);
                        }
                    }
                }
            }

            if (false === ($user instanceof ShortUrlBundle\Controller\UserController)) {
                $this->addHeader('WWW-Authenticate', sprintf('Basic realm="%s"', $request->getHttpHost()));
                throw new AuthenticationException('no suitable authentication methods were given');
            }

        }

    }
}