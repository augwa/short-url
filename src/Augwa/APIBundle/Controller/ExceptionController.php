<?php


namespace Augwa\APIBundle\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Augwa\APIBundle\Exception\StatusCode;

/**
 * Class ExceptionController
 * @package Augwa\APIBundle\Controller
 */
class ExceptionController extends Base\BaseController
{

    public function showExceptionAction(FlattenException $exception, $logger = null)
    {
        if ($logger !== null && false === $logger instanceof DebugLoggerInterface) {
            exit;
        }

        $exceptionClass = $exception->getClass();
        $statusCode = $this->determineStatusCode(new $exceptionClass);
        $expiryDate = new \DateTime('2000-01-01');

        $content = json_encode(
            [
                'message' => $exception->getMessage(),
                'code' => $statusCode
            ]
        );

        $response = new Response;

        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->setExpires($expiryDate);
        $response->setContent($content);
        $response->setStatusCode($statusCode);

        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param \Exception $exception
     *
     * @return int
     */
    private function determineStatusCode(\Exception $exception)
    {
        /**
         * default to error 500, override based on exception
         */
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        /**
         * Client Errors
         */
        if ($exception instanceof StatusCode\ClientError\BadRequest) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        elseif ($exception instanceof StatusCode\ClientError\Unauthorized) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
        }
        elseif ($exception instanceof StatusCode\ClientError\PaymentRequired) {
            $statusCode = Response::HTTP_PAYMENT_REQUIRED;
        }
        elseif ($exception instanceof StatusCode\ClientError\Forbidden) {
            $statusCode = Response::HTTP_FORBIDDEN;
        }
        elseif ($exception instanceof StatusCode\ClientError\NotFound) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }
        elseif ($exception instanceof StatusCode\ClientError\MethodNotAllowed) {
            $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
        }
        elseif ($exception instanceof StatusCode\ClientError\NotAcceptable) {
            $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        }
        elseif ($exception instanceof StatusCode\ClientError\ProxyAuthenticationRequired) {
            $statusCode = Response::HTTP_PROXY_AUTHENTICATION_REQUIRED;
        }
        elseif ($exception instanceof StatusCode\ClientError\RequestTimeout) {
            $statusCode = Response::HTTP_REQUEST_TIMEOUT;
        }
        elseif ($exception instanceof StatusCode\ClientError\Conflict) {
            $statusCode = Response::HTTP_CONFLICT;
        }
        elseif ($exception instanceof StatusCode\ClientError\Gone) {
            $statusCode = Response::HTTP_GONE;
        }
        elseif ($exception instanceof StatusCode\ClientError\LengthRequired) {
            $statusCode = Response::HTTP_LENGTH_REQUIRED;
        }
        elseif ($exception instanceof StatusCode\ClientError\PreconditionFailed) {
            $statusCode = Response::HTTP_PRECONDITION_FAILED;
        }
        elseif ($exception instanceof StatusCode\ClientError\RequestEntityTooLarge) {
            $statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
        }
        elseif ($exception instanceof StatusCode\ClientError\RequestUriTooLong) {
            $statusCode = Response::HTTP_REQUEST_URI_TOO_LONG;
        }
        elseif ($exception instanceof StatusCode\ClientError\UnsupportedMediaType) {
            $statusCode = Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
        }
        elseif ($exception instanceof StatusCode\ClientError\RequestedRangeNotSatisfiable) {
            $statusCode = Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE;
        }
        elseif ($exception instanceof StatusCode\ClientError\ExpectationFailed) {
            $statusCode = Response::HTTP_EXPECTATION_FAILED;
        }
        elseif ($exception instanceof StatusCode\ClientError\IAmATeapot) {
            $statusCode = Response::HTTP_I_AM_A_TEAPOT;
        }
        elseif ($exception instanceof StatusCode\ClientError\UnprocessableEntity) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        }
        elseif ($exception instanceof StatusCode\ClientError\Locked) {
            $statusCode = Response::HTTP_LOCKED;
        }
        elseif ($exception instanceof StatusCode\ClientError\FailedDependency) {
            $statusCode = Response::HTTP_FAILED_DEPENDENCY;
        }
        elseif ($exception instanceof StatusCode\ClientError\ReservedForWebdavAdvancedCollectionsExpiredProposal) {
            $statusCode = Response::HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL;
        }
        elseif ($exception instanceof StatusCode\ClientError\UpgradeRequired) {
            $statusCode = Response::HTTP_UPGRADE_REQUIRED;
        }
        elseif ($exception instanceof StatusCode\ClientError\PreconditionRequired) {
            $statusCode = Response::HTTP_PRECONDITION_REQUIRED;
        }
        elseif ($exception instanceof StatusCode\ClientError\TooManyRequests) {
            $statusCode = Response::HTTP_TOO_MANY_REQUESTS;
        }
        elseif ($exception instanceof StatusCode\ClientError\RequestHeaderFieldsTooLarge) {
            $statusCode = Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE;
        }

        /**
         * Server Errors
         */
        elseif ($exception instanceof StatusCode\ServerError\InternalServerError) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        elseif ($exception instanceof StatusCode\ServerError\NotImplemented) {
            $statusCode = Response::HTTP_NOT_IMPLEMENTED;
        }
        elseif ($exception instanceof StatusCode\ServerError\BadGateway) {
            $statusCode = Response::HTTP_BAD_GATEWAY;
        }
        elseif ($exception instanceof StatusCode\ServerError\ServiceUnavailable) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }
        elseif ($exception instanceof StatusCode\ServerError\GatewayTimeout) {
            $statusCode = Response::HTTP_GATEWAY_TIMEOUT;
        }
        elseif ($exception instanceof StatusCode\ServerError\HttpVersionNotSupported) {
            $statusCode = Response::HTTP_VERSION_NOT_SUPPORTED;
        }
        elseif ($exception instanceof StatusCode\ServerError\VariantAlsoNegotiatesExperimental) {
            $statusCode = Response::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL;
        }
        elseif ($exception instanceof StatusCode\ServerError\InsufficientStorage) {
            $statusCode = Response::HTTP_INSUFFICIENT_STORAGE;
        }
        elseif ($exception instanceof StatusCode\ServerError\LoopDetected) {
            $statusCode = Response::HTTP_LOOP_DETECTED;
        }
        elseif ($exception instanceof StatusCode\ServerError\NotExtended) {
            $statusCode = Response::HTTP_NOT_EXTENDED;
        }
        elseif ($exception instanceof StatusCode\ServerError\NetworkAuthenticationRequired) {
            $statusCode = Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED;
        }

        return $statusCode;
    }

}