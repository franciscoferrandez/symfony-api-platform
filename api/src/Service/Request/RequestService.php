<?php
declare(strict_types=1);

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    /**
     * @param Request $request
     * @param string $fieldName
     * @param bool $isRequired
     * @param bool $isArray
     * @return mixed
     */
    public static function getField(Request $request, string $fieldName, bool $isRequired, bool $isArray)
    {
        $requestData = \json_decode($request->getContent(), true);

        if ($isArray) {
            $arrayData = self::arrayFlatten($requestData);
            foreach ($arrayData as $index => $arrayDatum) {
                if ($fieldName === $index) {
                    return $arrayDatum;
                }
            }
            if ($isRequired) {
                throw new BadRequestHttpException(\sprintf('Missing field %s', $fieldName));
            }
            return null;
        }

        if (\array_key_exists($fieldName, $requestData)) {
            return $requestData[$fieldName];
        }
        if ($isRequired) {
            throw new BadRequestHttpException(\sprintf('Missing field %s', $fieldName));
        }
        return null;
    }

    public static function arrayFlatten(array $array): array
    {
        $return = [];
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $return = \array_merge($return, self::arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }
        return $return;
    }
}