<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Customer\Domain\Validator;

use Broadway\ReadModel\RepositoryInterface;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\Exception\EmailAlreadyExistsException;
use OpenLoyalty\Component\Customer\Domain\Exception\LoyaltyCardNumberAlreadyExistsException;
use OpenLoyalty\Component\Customer\Domain\Exception\PhoneAlreadyExistsException;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;

/**
 * Class CustomerUniqueValidator.
 */
class CustomerUniqueValidator
{
    /**
     * @var RepositoryInterface
     */
    protected $customerDetailsRepository;

    /**
     * CustomerUniqueValidator constructor.
     *
     * @param RepositoryInterface $customerDetailsRepository
     */
    public function __construct(RepositoryInterface $customerDetailsRepository)
    {
        $this->customerDetailsRepository = $customerDetailsRepository;
    }

    public function validateEmailUnique($email, CustomerId $customerId = null)
    {
        $customers = $this->customerDetailsRepository->findBy(['email' => $email]);
        if ($customerId) {
            /** @var CustomerDetails $customer */
            foreach ($customers as $key => $customer) {
                if ($customer->getId() == $customerId->__toString()) {
                    unset($customers[$key]);
                }
            }
        }

        if (count($customers) > 0) {
            throw new EmailAlreadyExistsException();
        }
    }

    public function validateLoyaltyCardNumberUnique($number, CustomerId $customerId)
    {
        $customers = $this->customerDetailsRepository->findBy(['loyaltyCardNumber' => $number]);
        /** @var CustomerDetails $customer */
        foreach ($customers as $key => $customer) {
            if ($customer->getId() == $customerId->__toString()) {
                unset($customers[$key]);
            }
        }
        if (count($customers) > 0) {
            throw new LoyaltyCardNumberAlreadyExistsException();
        }
    }

    public function validatePhoneUnique($number, CustomerId $customerId = null)
    {
        $customers = $this->customerDetailsRepository->findBy(['phone' => $number]);
        if ($customerId) {
            /** @var CustomerDetails $customer */
            foreach ($customers as $key => $customer) {
                if ($customer->getId() == $customerId->__toString()) {
                    unset($customers[$key]);
                }
            }
        }
        if (count($customers) > 0) {
            throw new PhoneAlreadyExistsException();
        }
    }
}
