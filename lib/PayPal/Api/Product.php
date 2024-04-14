<?php

namespace PayPal\Api;

use PayPal\Common\PayPalModel;
use PayPal\Common\PayPalResourceModel;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;

/**
 * Class Plan
 *
 * Billing plan resource that will be used to create a billing agreement.
 *
 * @package PayPal\Api
 *
 * @property string id
 * @property string name
 * @property string description
 * @property string type
 * @property string state
 * @property string create_time
 * @property string update_time
 * @property \PayPal\Api\PaymentDefinition[] payment_definitions
 * @property \PayPal\Api\Terms[] terms
 * @property \PayPal\Api\MerchantPreferences merchant_preferences
 */
class Product extends PayPalResourceModel
{
    /**
     * Identifier of the billing plan. 128 characters max.
     *
     * @param string $id
     * 
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Identifier of the billing plan. 128 characters max.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Name of the billing plan. 128 characters max.
     *
     * @param string $name
     * 
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Name of the billing plan. 128 characters max.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Description of the billing plan. 128 characters max.
     *
     * @param string $description
     * 
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Description of the billing plan. 128 characters max.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Type of the billing plan. Allowed values: `PHYSICAL`, `DIGITAL`, `SERVICE`.
     *
     * @param string $type
     * 
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Type of the billing plan. Allowed values: `PHYSICAL`, `DIGITAL`, `SERVICE`.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Category
     *
     * @param string $category
     * 
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * 
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Retrieve the details for a particular billing plan by passing the billing plan ID to the request URI.
     *
     * @param string $productId
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Plan
     */
    public static function get($productId, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($productId, 'productId');
        $payLoad = "";
        $json = self::executeCall(
            "/v1/catalogs/products/$productId",
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new Plan();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Create a new billing plan by passing the details for the plan, including the plan name, description, and type, to the request URI.
     *
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Plan
     */
    public function create($apiContext = null, $restCall = null)
    {
        $payLoad = $this->toJSON();
        $json = self::executeCall(
            "/v1/catalogs/products/",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $this->fromJson($json);
        return $this;
    }

    /**
     * Replace specific fields within a billing plan by passing the ID of the billing plan to the request URI. In addition, pass a patch object in the request JSON that specifies the operation to perform, field to update, and new value for each update.
     *
     * @param PatchRequest $patchRequest
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return bool
     */
    public function update($patchRequest, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($this->getId(), "Id");
        ArgumentValidator::validate($patchRequest, 'patchRequest');
        $payLoad = $patchRequest->toJSON();
        self::executeCall(
            "/v1/catalogs/products/{$this->getId()}",
            "PATCH",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        return true;
    }

    /**
     * Delete a billing plan by passing the ID of the billing plan to the request URI.
     *
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return bool
     */
    public function delete($apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($this->getId(), "Id");
        $patchRequest = new PatchRequest();
        $patch = new Patch();
        $value = new PayPalModel('{
            "state":"DELETED"
        }');
        $patch->setOp('replace')
            ->setPath('/')
            ->setValue($value);
        $patchRequest->addPatch($patch);
        return $this->update($patchRequest, $apiContext, $restCall);
    }

    /**
     * List billing plans according to optional query string parameters specified.
     *
     * @param array $params
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return PlanList
     */
    public static function all($params, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($params, 'params');
        $payLoad = "";
        $allowedParams = array(
            'page_size' => 1,
            'page' => 1,
            'total_required' => 1
        );
        $json = self::executeCall(
            "/v1/catalogs/products/" . "?" . http_build_query(array_intersect_key($params, $allowedParams)),
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new PlanList();
        $ret->fromJson($json);
        return $ret;
    }
}
?>