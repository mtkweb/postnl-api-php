<?php
/**
 * The MIT License (MIT).
 *
 * Copyright (c) 2017-2023 Michael Dekker (https://github.com/firstred)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <git@michaeldekker.nl>
 * @copyright 2017-2023 Michael Dekker
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Firstred\PostNL\Entity\Response;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Firstred\PostNL\Entity\AbstractEntity;
use Firstred\PostNL\Entity\Amount;
use Firstred\PostNL\Entity\Barcode;
use Firstred\PostNL\Entity\Customer;
use Firstred\PostNL\Entity\Dimension;
use Firstred\PostNL\Entity\Expectation;
use Firstred\PostNL\Entity\Group;
use Firstred\PostNL\Entity\ProductOption;
use Firstred\PostNL\Entity\Status;
use Firstred\PostNL\Entity\StatusAddress;
use Firstred\PostNL\Entity\Warning;
use Firstred\PostNL\Exception\InvalidArgumentException;
use Firstred\PostNL\Service\BarcodeService;
use Firstred\PostNL\Service\ConfirmingService;
use Firstred\PostNL\Service\DeliveryDateService;
use Firstred\PostNL\Service\LabellingService;
use Firstred\PostNL\Service\LocationService;
use Firstred\PostNL\Service\TimeframeService;
use Sabre\Xml\Writer;
use stdClass;

/**
 * Class CompleteStatusResponseShipment.
 *
 * @method StatusAddress[]|null                   getAddresses()
 * @method Amount[]|null                          getAmounts()
 * @method Barcode|null                           getBarcode()
 * @method Customer|null                          getCustomer()
 * @method DateTimeInterface|null                 getDeliveryDate()
 * @method Dimension|null                         getDimension()
 * @method CompleteStatusResponseEvent[]|null     getEvents()
 * @method Expectation|null                       getExpectation()
 * @method Group[]|null                           getGroups()
 * @method string|null                            getMainBarcode()
 * @method CompleteStatusResponseOldStatus[]|null getOldStatuses()
 * @method string|null                            getProductCode()
 * @method string|null                            getProductDescription()
 * @method ProductOption[]|null                   getProductOptions()
 * @method string|null                            getReference()
 * @method string|null                            getShipmentAmount()
 * @method string|null                            getShipmentCounter()
 * @method Status|null                            getStatus()
 * @method Warning[]|null                         getWarnings()
 * @method CompleteStatusResponseShipment         setAddresses(StatusAddress[]|null $Addresses = null)
 * @method CompleteStatusResponseShipment         setAmounts(Amount[]|null $Amounts = null)
 * @method CompleteStatusResponseShipment         setBarcode(string|null $Barcode = null)
 * @method CompleteStatusResponseShipment         setCustomer(Customer|null $Customer = null)
 * @method CompleteStatusResponseShipment         setDimension(Dimension|null $Dimension = null)
 * @method CompleteStatusResponseShipment         setEvents(CompleteStatusResponseEvent[]|null $Events = null)
 * @method CompleteStatusResponseShipment         setExpectation(Expectation|null $Expectation = null)
 * @method CompleteStatusResponseShipment         setGroups(Group[]|null $Groups = null)
 * @method CompleteStatusResponseShipment         setMainBarcode(string|null $MainBarcode = null)
 * @method CompleteStatusResponseShipment         setOldStatuses(CompleteStatusResponseOldStatus[]|null $OldStatuses = null)
 * @method CompleteStatusResponseShipment         setProductCode(string|null $ProductCode = null)
 * @method CompleteStatusResponseShipment         setProductDescription(string|null $ProductDescription = null)
 * @method CompleteStatusResponseShipment         setProductOptions(ProductOption[]|null $ProductOptions = null)
 * @method CompleteStatusResponseShipment         setReference(string|null $Reference = null)
 * @method CompleteStatusResponseShipment         setShipmentAmount(string|null $ShipmentAmount = null)
 * @method CompleteStatusResponseShipment         setShipmentCounter(string|null $ShipmentCounter = null)
 * @method CompleteStatusResponseShipment         setStatus(Status|null $Status = null)
 * @method CompleteStatusResponseShipment         setWarnings(Warning[]|null $Warnings = null)
 *
 * @since 1.0.0
 */
class CompleteStatusResponseShipment extends AbstractEntity
{
    /** @var string[][] */
    public static $defaultProperties = [
        'Barcode'      => [
            'Addresses'          => BarcodeService::DOMAIN_NAMESPACE,
            'Amounts'            => BarcodeService::DOMAIN_NAMESPACE,
            'Barcode'            => BarcodeService::DOMAIN_NAMESPACE,
            'Customer'           => BarcodeService::DOMAIN_NAMESPACE,
            'DeliveryDate'       => BarcodeService::DOMAIN_NAMESPACE,
            'Dimension'          => BarcodeService::DOMAIN_NAMESPACE,
            'Events'             => BarcodeService::DOMAIN_NAMESPACE,
            'Expectation'        => BarcodeService::DOMAIN_NAMESPACE,
            'Groups'             => BarcodeService::DOMAIN_NAMESPACE,
            'MainBarcode'        => BarcodeService::DOMAIN_NAMESPACE,
            'OldStatuses'        => BarcodeService::DOMAIN_NAMESPACE,
            'ProductCode'        => BarcodeService::DOMAIN_NAMESPACE,
            'ProductDescription' => BarcodeService::DOMAIN_NAMESPACE,
            'ProductOptions'     => BarcodeService::DOMAIN_NAMESPACE,
            'Reference'          => BarcodeService::DOMAIN_NAMESPACE,
            'ShipmentAmount'     => BarcodeService::DOMAIN_NAMESPACE,
            'ShipmentCounter'    => BarcodeService::DOMAIN_NAMESPACE,
            'Status'             => BarcodeService::DOMAIN_NAMESPACE,
            'Warnings'           => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'   => [
            'Addresses'          => ConfirmingService::DOMAIN_NAMESPACE,
            'Amounts'            => ConfirmingService::DOMAIN_NAMESPACE,
            'Barcode'            => ConfirmingService::DOMAIN_NAMESPACE,
            'Customer'           => ConfirmingService::DOMAIN_NAMESPACE,
            'DeliveryDate'       => ConfirmingService::DOMAIN_NAMESPACE,
            'Dimension'          => ConfirmingService::DOMAIN_NAMESPACE,
            'Events'             => ConfirmingService::DOMAIN_NAMESPACE,
            'Expectation'        => ConfirmingService::DOMAIN_NAMESPACE,
            'Groups'             => ConfirmingService::DOMAIN_NAMESPACE,
            'MainBarcode'        => ConfirmingService::DOMAIN_NAMESPACE,
            'OldStatuses'        => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductCode'        => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductDescription' => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductOptions'     => ConfirmingService::DOMAIN_NAMESPACE,
            'Reference'          => ConfirmingService::DOMAIN_NAMESPACE,
            'ShipmentAmount'     => ConfirmingService::DOMAIN_NAMESPACE,
            'ShipmentCounter'    => ConfirmingService::DOMAIN_NAMESPACE,
            'Status'             => ConfirmingService::DOMAIN_NAMESPACE,
            'Warnings'           => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'    => [
            'Addresses'          => LabellingService::DOMAIN_NAMESPACE,
            'Amounts'            => LabellingService::DOMAIN_NAMESPACE,
            'Barcode'            => LabellingService::DOMAIN_NAMESPACE,
            'Customer'           => LabellingService::DOMAIN_NAMESPACE,
            'DeliveryDate'       => LabellingService::DOMAIN_NAMESPACE,
            'Dimension'          => LabellingService::DOMAIN_NAMESPACE,
            'Events'             => LabellingService::DOMAIN_NAMESPACE,
            'Expectation'        => LabellingService::DOMAIN_NAMESPACE,
            'Groups'             => LabellingService::DOMAIN_NAMESPACE,
            'MainBarcode'        => LabellingService::DOMAIN_NAMESPACE,
            'OldStatuses'        => LabellingService::DOMAIN_NAMESPACE,
            'ProductCode'        => LabellingService::DOMAIN_NAMESPACE,
            'ProductDescription' => LabellingService::DOMAIN_NAMESPACE,
            'ProductOptions'     => LabellingService::DOMAIN_NAMESPACE,
            'Reference'          => LabellingService::DOMAIN_NAMESPACE,
            'ShipmentAmount'     => LabellingService::DOMAIN_NAMESPACE,
            'ShipmentCounter'    => LabellingService::DOMAIN_NAMESPACE,
            'Status'             => LabellingService::DOMAIN_NAMESPACE,
            'Warnings'           => LabellingService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate' => [
            'Addresses'          => DeliveryDateService::DOMAIN_NAMESPACE,
            'Amounts'            => DeliveryDateService::DOMAIN_NAMESPACE,
            'Barcode'            => DeliveryDateService::DOMAIN_NAMESPACE,
            'Customer'           => DeliveryDateService::DOMAIN_NAMESPACE,
            'DeliveryDate'       => DeliveryDateService::DOMAIN_NAMESPACE,
            'Dimension'          => DeliveryDateService::DOMAIN_NAMESPACE,
            'Events'             => DeliveryDateService::DOMAIN_NAMESPACE,
            'Expectation'        => DeliveryDateService::DOMAIN_NAMESPACE,
            'Groups'             => DeliveryDateService::DOMAIN_NAMESPACE,
            'MainBarcode'        => DeliveryDateService::DOMAIN_NAMESPACE,
            'OldStatuses'        => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductCode'        => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductDescription' => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductOptions'     => DeliveryDateService::DOMAIN_NAMESPACE,
            'Reference'          => DeliveryDateService::DOMAIN_NAMESPACE,
            'ShipmentAmount'     => DeliveryDateService::DOMAIN_NAMESPACE,
            'ShipmentCounter'    => DeliveryDateService::DOMAIN_NAMESPACE,
            'Status'             => DeliveryDateService::DOMAIN_NAMESPACE,
            'Warnings'           => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'     => [
            'Addresses'          => LocationService::DOMAIN_NAMESPACE,
            'Amounts'            => LocationService::DOMAIN_NAMESPACE,
            'Barcode'            => LocationService::DOMAIN_NAMESPACE,
            'Customer'           => LocationService::DOMAIN_NAMESPACE,
            'DeliveryDate'       => LocationService::DOMAIN_NAMESPACE,
            'Dimension'          => LocationService::DOMAIN_NAMESPACE,
            'Events'             => LocationService::DOMAIN_NAMESPACE,
            'Expectation'        => LocationService::DOMAIN_NAMESPACE,
            'Groups'             => LocationService::DOMAIN_NAMESPACE,
            'MainBarcode'        => LocationService::DOMAIN_NAMESPACE,
            'OldStatuses'        => LocationService::DOMAIN_NAMESPACE,
            'ProductCode'        => LocationService::DOMAIN_NAMESPACE,
            'ProductDescription' => LocationService::DOMAIN_NAMESPACE,
            'ProductOptions'     => LocationService::DOMAIN_NAMESPACE,
            'Reference'          => LocationService::DOMAIN_NAMESPACE,
            'ShipmentAmount'     => LocationService::DOMAIN_NAMESPACE,
            'ShipmentCounter'    => LocationService::DOMAIN_NAMESPACE,
            'Status'             => LocationService::DOMAIN_NAMESPACE,
            'Warnings'           => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'    => [
            'Addresses'          => TimeframeService::DOMAIN_NAMESPACE,
            'Amounts'            => TimeframeService::DOMAIN_NAMESPACE,
            'Barcode'            => TimeframeService::DOMAIN_NAMESPACE,
            'Customer'           => TimeframeService::DOMAIN_NAMESPACE,
            'DeliveryDate'       => TimeframeService::DOMAIN_NAMESPACE,
            'Dimension'          => TimeframeService::DOMAIN_NAMESPACE,
            'Events'             => TimeframeService::DOMAIN_NAMESPACE,
            'Expectation'        => TimeframeService::DOMAIN_NAMESPACE,
            'Groups'             => TimeframeService::DOMAIN_NAMESPACE,
            'MainBarcode'        => TimeframeService::DOMAIN_NAMESPACE,
            'OldStatuses'        => TimeframeService::DOMAIN_NAMESPACE,
            'ProductCode'        => TimeframeService::DOMAIN_NAMESPACE,
            'ProductDescription' => TimeframeService::DOMAIN_NAMESPACE,
            'ProductOptions'     => TimeframeService::DOMAIN_NAMESPACE,
            'Reference'          => TimeframeService::DOMAIN_NAMESPACE,
            'ShipmentAmount'     => TimeframeService::DOMAIN_NAMESPACE,
            'ShipmentCounter'    => TimeframeService::DOMAIN_NAMESPACE,
            'Status'             => TimeframeService::DOMAIN_NAMESPACE,
            'Warnings'           => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var StatusAddress[]|null */
    protected $Addresses;
    /** @var Amount[]|null */
    protected $Amounts;
    /** @var Barcode|null */
    protected $Barcode;
    /** @var Customer|null */
    protected $Customer;
    /** @var DateTimeInterface|null */
    protected $DeliveryDate;
    /** @var Dimension|null Dimension */
    protected $Dimension;
    /** @var CompleteStatusResponseEvent[]|null */
    protected $Events;
    /** @var Expectation|null */
    protected $Expectation;
    /** @var Group[]|null */
    protected $Groups;
    /** @var string|null */
    protected $MainBarcode;
    /** @var CompleteStatusResponseOldStatus[]|null */
    protected $OldStatuses;
    /** @var string|null */
    protected $ProductCode;
    /** @var string|null */
    protected $ProductDescription;
    /** @var ProductOption[]|null */
    protected $ProductOptions;
    /** @var string|null */
    protected $Reference;
    /** @var string|null */
    protected $ShipmentAmount;
    /** @var string|null */
    protected $ShipmentCounter;
    /** @var Status|null */
    protected $Status;
    /** @var Warning[]|null */
    protected $Warnings;
    // @codingStandardsIgnoreEnd

    /**
     * CompleteStatusResponseShipment constructor.
     *
     * @param StatusAddress[]|null                   $Addresses
     * @param Amount[]|null                          $Amounts
     * @param string|null                            $Barcode
     * @param Customer|null                          $Customer
     * @param DateTimeInterface|string|null          $DeliveryDate
     * @param Dimension|null                         $Dimension
     * @param CompleteStatusResponseEvent[]|null     $Events
     * @param Expectation|null                       $Expectation
     * @param Group[]|null                           $Groups
     * @param CompleteStatusResponseOldStatus[]|null $OldStatuses
     * @param string|null                            $ProductCode
     * @param ProductOption[]|null                   $ProductOptions
     * @param string|null                            $Reference
     * @param Status|null                            $Status
     * @param Warning[]|null                         $Warnings
     * @param string|null                            $MainBarcode
     * @param string|null                            $ShipmentAmount
     * @param string|null                            $ShipmentCounter
     * @param string|null                            $ProductDescription
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        array $Addresses = null,
        array $Amounts = null,
        $Barcode = null,
        $Customer = null,
        $DeliveryDate = null,
        $Dimension = null,
        array $Events = null,
        $Expectation = null,
        array $Groups = null,
        array $OldStatuses = null,
        $ProductCode = null,
        array $ProductOptions = null,
        $Reference = null,
        $Status = null,
        array $Warnings = null,
        $MainBarcode = null,
        $ShipmentAmount = null,
        $ShipmentCounter = null,
        $ProductDescription = null
    ) {
        parent::__construct();

        $this->setAddresses($Addresses);
        $this->setAmounts($Amounts);
        $this->setBarcode($Barcode);
        $this->setCustomer($Customer);
        $this->setDeliveryDate($DeliveryDate);
        $this->setDimension($Dimension);
        $this->setEvents($Events);
        $this->setExpectation($Expectation);
        $this->setGroups($Groups);
        $this->setOldStatuses($OldStatuses);
        $this->setProductCode($ProductCode);
        $this->setProductOptions($ProductOptions);
        $this->setReference($Reference);
        $this->setStatus($Status);
        $this->setWarnings($Warnings);
        $this->setMainBarcode($MainBarcode);
        $this->setShipmentAmount($ShipmentAmount);
        $this->setShipmentCounter($ShipmentCounter);
        $this->setProductDescription($ProductDescription);
    }

    /**
     * @param string|DateTimeInterface|null $deliveryDate
     *
     * @return static
     *
     * @throws InvalidArgumentException
     *
     * @since 1.2.0
     */
    public function setDeliveryDate($deliveryDate = null)
    {
        if (is_string($deliveryDate)) {
            try {
                $deliveryDate = new DateTimeImmutable($deliveryDate, new DateTimeZone('Europe/Amsterdam'));
            } catch (Exception $e) {
                throw new InvalidArgumentException($e->getMessage(), 0, $e);
            }
        }

        $this->DeliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * @return Amount[]|null
     */
    public function getAmount()
    {
        return $this->getAmounts();
    }

    /**
     * @param Amount[]|null $Amount
     *
     * @return static
     */
    public function setAmount($Amount = null)
    {
        return $this->setAmounts($Amount);
    }

    /**
     * @param stdClass $json
     *
     * @return mixed|stdClass|null
     *
     * @throws InvalidArgumentException
     * @throws \Firstred\PostNL\Exception\NotSupportedException
     *
     * @since 1.2.0
     */
    public static function jsonDeserialize(stdClass $json)
    {
        if (isset($json->CompleteStatusResponseShipment->Address)) {
            $json->CompleteStatusResponseShipment->Addresses = $json->CompleteStatusResponseShipment->Address;
            unset($json->CompleteStatusResponseShipment->Address);

            if (!is_array($json->CompleteStatusResponseShipment->Addresses)) {
                $json->CompleteStatusResponseShipment->Addresses = [$json->CompleteStatusResponseShipment->Addresses];
            }
        }

        if (isset($json->CompleteStatusResponseShipment->Amount)) {
            $json->CompleteStatusResponseShipment->Amounts = $json->CompleteStatusResponseShipment->Amount;
            unset($json->CompleteStatusResponseShipment->Amount);

            if (!is_array($json->CompleteStatusResponseShipment->Amounts)) {
                $json->CompleteStatusResponseShipment->Amounts = [$json->CompleteStatusResponseShipment->Amounts];
            }
        }

        return parent::jsonDeserialize($json);
    }

    /**
     * Return a serializable array for the XMLWriter.
     *
     * @param Writer $writer
     *
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $xml = [];
        if (!$this->currentService || !in_array($this->currentService, array_keys(static::$defaultProperties))) {
            $writer->write($xml);

            return;
        }

        foreach (static::$defaultProperties[$this->currentService] as $propertyName => $namespace) {
            if ('Addresses' === $propertyName) {
                $addresses = [];
                foreach ($this->Addresses as $address) {
                    $addresses[] = ["{{$namespace}}StatusAddress" => $address];
                }
                $xml["{{$namespace}}Addresses"] = $addresses;
            } elseif ('Amounts' === $propertyName) {
                $amounts = [];
                foreach ($this->Amounts as $amount) {
                    $amounts[] = ["{{$namespace}}Amount" => $amount];
                }
                $xml["{{$namespace}}Amounts"] = $amounts;
            } elseif ('Groups' === $propertyName) {
                $groups = [];
                foreach ($this->Groups as $group) {
                    $groups[] = ["{{$namespace}}Group" => $group];
                }
                $xml["{{$namespace}}Groups"] = $groups;
            } elseif ('Events' === $propertyName) {
                $events = [];
                foreach ($this->Events as $event) {
                    $events[] = ["{{$namespace}}CompleteStatusResponseEvent" => $event];
                }
                $xml["{{$namespace}}Events"] = $events;
            } elseif ('OldStatuses' === $propertyName) {
                $oldStatuses = [];
                foreach ($this->OldStatuses as $oldStatus) {
                    $oldStatuses[] = ["{{$namespace}}CompleteStatusResponseOldStatus" => $oldStatus];
                }
                $xml["{{$namespace}}OldStatuses"] = $oldStatuses;
            } elseif ('ProductOption' === $propertyName) {
                $productOptions = [];
                foreach ($this->ProductOptions as $productOption) {
                    $productOptions[] = ["{{$namespace}}ProductOptions" => $productOption];
                }
                $xml["{{$namespace}}ProductOptions"] = $productOptions;
            } elseif ('Warnings' === $propertyName) {
                $warnings = [];
                foreach ($this->Warnings as $warning) {
                    $warnings[] = ["{{$namespace}}Warning" => $warning];
                }
                $xml["{{$namespace}}Warnings"] = $warnings;
            } elseif (isset($this->$propertyName)) {
                $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = $this->$propertyName;
            }
        }
        // Auto extending this object with other properties is not supported with SOAP
        $writer->write($xml);
    }
}
