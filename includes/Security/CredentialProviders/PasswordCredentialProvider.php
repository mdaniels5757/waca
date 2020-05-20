<?php
/******************************************************************************
 * Wikipedia Account Creation Assistance tool                                 *
 *                                                                            *
 * All code in this file is released into the public domain by the ACC        *
 * Development Team. Please see team.json for a list of contributors.         *
 ******************************************************************************/

namespace Waca\Security\CredentialProviders;

use Waca\DataObjects\User;
use Waca\Exceptions\ApplicationLogicException;
use Waca\PdoDatabase;
use Waca\SiteConfiguration;

class PasswordCredentialProvider extends CredentialProviderBase
{
    const PASSWORD_COST = 10;
    const PASSWORD_ALGO = PASSWORD_BCRYPT;

    public function __construct(PdoDatabase $database, SiteConfiguration $configuration)
    {
        parent::__construct($database, $configuration, 'password');
    }

    public function authenticate(User $user, $data)
    {
        $storedData = $this->getCredentialData($user->getId());
        if($storedData === null)
        {
            // No available credential matching these parameters
            return false;
        }

        if($storedData->getVersion() !== 2) {
            // Non-2 versions are not supported.
            return false;
        }

        if(password_verify($data, $storedData->getData())) {
            if(password_needs_rehash($storedData->getData(), self::PASSWORD_ALGO, array('cost' => self::PASSWORD_COST))){
                $this->setCredential($user, $storedData->getFactor(), $data);
            }

            return true;
        }

        return false;
    }

    public function setCredential(User $user, $factor, $password)
    {
        $storedData = $this->getCredentialData($user->getId());

        if($storedData === null){
            $storedData = $this->createNewCredential($user);
        }

        $storedData->setData(password_hash($password, self::PASSWORD_ALGO, array('cost' => self::PASSWORD_COST)));
        $storedData->setFactor($factor);
        $storedData->setVersion(2);

        $storedData->save();
    }

    public function deleteCredential(User $user)
    {
        throw new ApplicationLogicException('Deletion of password credential is not allowed.');
    }
}