<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Resource\ResourceInterface;

class RESTSerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return array($resourceKey ?: 'data' => $data);
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return array($resourceKey ?: 'data' => $data);
    }

    /**
     * Serialize the included data.
     *
     * @param ResourceInterface $resource
     * @param array             $data
     *
     * @return array
     */
    public function includedData(ResourceInterface $resource, array $data)
    {
        $serializedData = array();
        $linkedIds = array();

        foreach ($data as $value) {
            foreach ($value as $innerValue) {
                foreach($innerValue as $includeKey => $includeValue) {
                    if (!isset($includeValue[0])) {
                        $items = [$includeValue];
                    } else {
                        $items = $includeValue;
                    }

                    foreach ($items as $itemValue) {
                        if (!array_key_exists('id', $itemValue)) {
                            continue;
                        }
                        $itemId = $itemValue['id'];
                        if (!empty($linkedIds[$includeKey]) && in_array($itemId, $linkedIds[$includeKey], true)) {
                            continue;
                        }
                        $serializedData[$includeKey][] = $itemValue;
                        $linkedIds[$includeKey][] = $itemId;
                    }
                }
            }
        }

        return empty($serializedData) ? array() : $serializedData;
    }

    /**
     * Indicates if includes should be side-loaded.
     *
     * @return bool
     */
    public function sideloadIncludes()
    {
        return true;
    }

}
