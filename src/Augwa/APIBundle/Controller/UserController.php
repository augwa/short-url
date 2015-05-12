<?php

namespace Augwa\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Augwa\ShortUrlBundle\Controller as ShortUrl;
use Augwa\APIBundle\Exception as APIException;
use Augwa\ShortUrlBundle\Exception as ShortUrlException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package Augwa\APIBundle\Controller
 */
class UserController extends Base\BaseController
{

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Augwa\APIBundle\Exception\User\MissingParameterException
     * @throws \Augwa\APIBundle\Exception\User\DuplicateException
     * @throws \Augwa\APIBundle\Exception\User\InvalidDataException
     */
    public function createAction(Request $request)
    {
        /** @var $user ShortUrl\UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        $arguments = json_decode($request->getContent(), false);

        if (false === isset($arguments->email_address)) {
            throw new APIException\User\MissingParameterException('email_address parameter is missing');
        }

        if (false === isset($arguments->password)) {
            throw new APIException\User\MissingParameterException('password parameter is missing');
        }

        try {
            $user = $user->createAccount($arguments->email_address, $arguments->password, $request->getClientIp());
        }
        catch (ShortUrlException\User\InvalidDataException $e) {
            throw new APIException\User\InvalidDataException($e->getMessage(), $e->getCode(), $e);
        }
        catch (ShortUrlException\User\DuplicateException $e) {
            throw new APIException\User\DuplicateException($e->getMessage(), $e->getCode(), $e);
        }

        $content = json_encode(
            [
                'id' => $user->getUserId(),
                'email_address' => $user->getEmailAddress(),
                'date_created' => $user->getDateCreated(),
                'ip_address' => long2ip($user->getIpAddress())
            ]
        );

        return $this->responseBuilder($content);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Augwa\APIBundle\Exception\User\NotFoundException
     */
    public function readAction(Request $request)
    {
        /** @var $user ShortUrl\UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        try {
            $user->loadById($request->get('user_id'));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $content = json_encode(
            [
                'id' => $user->getUserId(),
                'email_address' => $user->getEmailAddress(),
                'date_created' => $user->getDateCreated(),
                'ip_address' => long2ip($user->getIpAddress())
            ]
        );

        return $this->responseBuilder($content);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Augwa\APIBundle\Exception\User\MissingParameterException
     * @throws \Augwa\APIBundle\Exception\User\NotFoundException
     * @throws \Augwa\APIBundle\Exception\User\InvalidCredentialsException
     */
    public function updateAction(Request $request)
    {
        /** @var $user ShortUrl\UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        try {
            $user->loadById($request->get('user_id'));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $arguments = json_decode($request->getContent(), false);

        if (false === isset($arguments->current_password)) {
            throw new APIException\User\MissingParameterException('current_password is required');
        }

        if (false === $user->isPasswordCorrect($arguments->current_password)) {
            throw new APIException\User\InvalidCredentialsException('current password does not match');
        }

        if (isset($arguments->email_address)) {
            $user->setEmailAddress($arguments->email_address);
        }

        if (isset($arguments->new_password)) {
            $user->setPassword($arguments->new_password);
        }

        $user->save();

        $content = json_encode(
            [
                'id' => $user->getUserId(),
                'email_address' => $user->getEmailAddress(),
                'date_created' => $user->getDateCreated(),
                'ip_address' => long2ip($user->getIpAddress())
            ]
        );

        return $this->responseBuilder($content);
    }


    public function deleteAction(Request $request)
    {
        /** @var $user ShortUrl\UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        try {
            $user->loadById($request->get('user_id'));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $user->delete();

        return $this->responseBuilder(null);
    }

}