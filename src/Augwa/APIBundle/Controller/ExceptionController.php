<?php


namespace Augwa\APIBundle\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Augwa\APIBundle\Exception\StatusCode;

/**
 * Class ExceptionController
 * @package Augwa\APIBundle\Controller
 */
class ExceptionController extends Base\BaseController
{

    public function showExceptionAction(FlattenException $exception)
    {

        $exceptionClass = $exception->getClass();

        $unknownException = false === strpos($exceptionClass, "/Augwa\\\\APIBundle\\\\Exception/");

        $statusCode = $this->determineStatusCode($exceptionClass);

        /**
         * will convert "\Augwa\SomeBundle\Exception\SomeProblemException" to "some_problem_exception"
         */
        $pos = strrpos($exceptionClass, '\\');
        $exceptionName = strtolower(
            preg_replace(
                '/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/',
                '$1_$2',
                substr($exceptionClass, $pos !== false ? $pos+1 : 0)
            )
        );

        $content = [
            'code' => $statusCode,
            'exception' => $exceptionName,
            'message' => $exception->getMessage(),
        ];

        if ($unknownException || ($statusCode >= 500 && $statusCode <= 599 && false === in_array($statusCode, [ 501 ]))) {

            $referenceId = md5(time() . rand());

            $root = $this->container->getParameter('augwa.api.exception_location');
            $errorFolder = sprintf('%s/%s/%s/%s/%s', $root, $referenceId[0], $referenceId[1], $referenceId[2], $referenceId[3]);

            if (false === file_exists($errorFolder)) {
                mkdir($errorFolder, 0770, true);
            }

            $error = [
                'code' => $statusCode,
                'reference_id' => $referenceId,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'error' => [
                    'reason' => 'fatal_exception',
                    'message' => $exception->getMessage(),
                    'trace' => debug_backtrace(0, 100)
                ]
            ];

            file_put_contents(
                sprintf('%s/%s.%s.json', $errorFolder, $referenceId, time()),
                json_encode($error, JSON_PRETTY_PRINT)
            );

            $content['reference_id'] = $referenceId;

        }

        return $this->responseBuilder(json_encode($content), $statusCode);
    }

    /**
     * @param string $exceptionClass
     *
     * @return int
     */
    private function determineStatusCode($exceptionClass)
    {
        /**
         * default to error 500, override based on exception
         */
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $prefix = '\Augwa\APIBundle\Exception\StatusCode';
        $clientPrefix = $prefix . '\ClientError';
        $serverPrefix = $prefix . '\ServerError';

        /**
         * Client Errors
         */
        if (is_subclass_of($exceptionClass, $clientPrefix . '\BadRequest')) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\Unauthorized')) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\PaymentRequired')) {
            $statusCode = Response::HTTP_PAYMENT_REQUIRED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\Forbidden')) {
            $statusCode = Response::HTTP_FORBIDDEN;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\NotFound')) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\MethodNotAllowed')) {
            $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\NotAcceptable')) {
            $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\ProxyAuthenticationRequired')) {
            $statusCode = Response::HTTP_PROXY_AUTHENTICATION_REQUIRED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\RequestTimeout')) {
            $statusCode = Response::HTTP_REQUEST_TIMEOUT;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\Conflict')) {
            $statusCode = Response::HTTP_CONFLICT;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\Gone')) {
            $statusCode = Response::HTTP_GONE;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\LengthRequired')) {
            $statusCode = Response::HTTP_LENGTH_REQUIRED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\PreconditionFailed')) {
            $statusCode = Response::HTTP_PRECONDITION_FAILED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\RequestEntityTooLarge')) {
            $statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\RequestUriTooLong')) {
            $statusCode = Response::HTTP_REQUEST_URI_TOO_LONG;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\UnsupportedMediaType')) {
            $statusCode = Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\RequestedRangeNotSatisfiable')) {
            $statusCode = Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\ExpectationFailed')) {
            $statusCode = Response::HTTP_EXPECTATION_FAILED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\IAmATeapot')) {
            $statusCode = Response::HTTP_I_AM_A_TEAPOT;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\UnprocessableEntity')) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\Locked')) {
            $statusCode = Response::HTTP_LOCKED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\FailedDependency')) {
            $statusCode = Response::HTTP_FAILED_DEPENDENCY;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\ReservedForWebdavAdvancedCollectionsExpiredProposal')) {
            $statusCode = Response::HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\UpgradeRequired')) {
            $statusCode = Response::HTTP_UPGRADE_REQUIRED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\PreconditionRequired')) {
            $statusCode = Response::HTTP_PRECONDITION_REQUIRED;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\TooManyRequests')) {
            $statusCode = Response::HTTP_TOO_MANY_REQUESTS;
        }
        elseif (is_subclass_of($exceptionClass, $clientPrefix . '\RequestHeaderFieldsTooLarge')) {
            $statusCode = Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE;
        }

        /**
         * Server Errors
         */
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\InternalServerError')) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\NotImplemented')) {
            $statusCode = Response::HTTP_NOT_IMPLEMENTED;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\BadGateway')) {
            $statusCode = Response::HTTP_BAD_GATEWAY;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\ServiceUnavailable')) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\GatewayTimeout')) {
            $statusCode = Response::HTTP_GATEWAY_TIMEOUT;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\HttpVersionNotSupported')) {
            $statusCode = Response::HTTP_VERSION_NOT_SUPPORTED;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\VariantAlsoNegotiatesExperimental')) {
            $statusCode = Response::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\InsufficientStorage')) {
            $statusCode = Response::HTTP_INSUFFICIENT_STORAGE;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\LoopDetected')) {
            $statusCode = Response::HTTP_LOOP_DETECTED;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\NotExtended')) {
            $statusCode = Response::HTTP_NOT_EXTENDED;
        }
        elseif (is_subclass_of($exceptionClass, $serverPrefix . '\NetworkAuthenticationRequired')) {
            $statusCode = Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED;
        }

        return $statusCode;
    }

}