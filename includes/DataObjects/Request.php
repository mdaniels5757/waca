<?php
namespace Waca\DataObjects;

use DateTime;
use Exception;
use Waca\DataObject;
use Waca\Exceptions\OptimisticLockFailedException;

/**
 * Request data object
 *
 * This data object is the main request object.
 */
class Request extends DataObject
{
	private $email;
	private $ip;
	private $name;
	/** @var string|null */
	private $comment;
	private $status = "Open";
	private $date;
	private $emailsent = 0;
	private $emailconfirm;
	private $reserved = 0;
	private $useragent;
	private $forwardedip;
	private $hasComments = false;
	private $hasCommentsResolved = false;

	/**
	 * @throws Exception
	 */
	public function save()
	{
		if ($this->isNew) {
			// insert
			$statement = $this->dbObject->prepare(<<<SQL
INSERT INTO `request` (
	email, ip, name, comment, status, date, emailsent,
	emailconfirm, reserved, useragent, forwardedip
) VALUES (
	:email, :ip, :name, :comment, :status, CURRENT_TIMESTAMP(), :emailsent,
	:emailconfirm, :reserved, :useragent, :forwardedip
);
SQL
			);
			$statement->bindValue(":email", $this->email);
			$statement->bindValue(":ip", $this->ip);
			$statement->bindValue(":name", $this->name);
			$statement->bindValue(":comment", $this->comment);
			$statement->bindValue(":status", $this->status);
			$statement->bindValue(":emailsent", $this->emailsent);
			$statement->bindValue(":emailconfirm", $this->emailconfirm);
			$statement->bindValue(":reserved", $this->reserved);
			$statement->bindValue(":useragent", $this->useragent);
			$statement->bindValue(":forwardedip", $this->forwardedip);
			if ($statement->execute()) {
				$this->isNew = false;
				$this->id = (int)$this->dbObject->lastInsertId();
			}
			else {
				throw new Exception($statement->errorInfo());
			}
		}
		else {
			// update
			$statement = $this->dbObject->prepare(<<<SQL
UPDATE `request` SET
	status = :status,
	emailsent = :emailsent,
	emailconfirm = :emailconfirm,
	reserved = :reserved,
	updateversion = updateversion + 1
WHERE id = :id AND updateversion = :updateversion
LIMIT 1;
SQL
			);

			$statement->bindValue(':id', $this->id);
			$statement->bindValue(':updateversion', $this->updateversion);

			$statement->bindValue(':status', $this->status);
			$statement->bindValue(':emailsent', $this->emailsent);
			$statement->bindValue(':emailconfirm', $this->emailconfirm);
			$statement->bindValue(':reserved', $this->reserved);

			if (!$statement->execute()) {
				throw new Exception($statement->errorInfo());
			}

			if ($statement->rowCount() !== 1) {
				throw new OptimisticLockFailedException();
			}

			$this->updateversion++;
		}
	}

	/**
	 * @return string
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * @param string $ip
	 */
	public function setIp($ip)
	{
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string|null
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 * @param string $comment
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param string $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @todo make this support DateTime object
	 * @return string
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @todo make this support DateTime object
	 *
	 * @param string $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}

	/**
	 * @todo change this to boolean
	 * @return int
	 */
	public function getEmailSent()
	{
		return $this->emailsent;
	}

	/**
	 * @todo change this to boolean
	 *
	 * @param int $emailsent
	 */
	public function setEmailSent($emailsent)
	{
		$this->emailsent = $emailsent;
	}

	/**
	 * @todo allow this to return null instead
	 * @return int
	 */
	public function getReserved()
	{
		return $this->reserved;
	}

	/**
	 * @param int|null $reserved
	 */
	public function setReserved($reserved)
	{
		if ($reserved === null) {
			// @todo this shouldn't be needed!
			$reserved = 0;
		}

		$this->reserved = $reserved;
	}

	/**
	 * @return string
	 */
	public function getUserAgent()
	{
		return $this->useragent;
	}

	/**
	 * @param string $useragent
	 */
	public function setUserAgent($useragent)
	{
		$this->useragent = $useragent;
	}

	/**
	 * @return string|null
	 */
	public function getForwardedIp()
	{
		return $this->forwardedip;
	}

	/**
	 * @param string|null $forwardedip
	 */
	public function setForwardedIp($forwardedip)
	{
		$this->forwardedip = $forwardedip;
	}

	/**
	 * @return bool
	 */
	public function hasComments()
	{
		if ($this->hasCommentsResolved) {
			return $this->hasComments;
		}

		if ($this->comment != "") {
			$this->hasComments = true;
			$this->hasCommentsResolved = true;

			return true;
		}

		$commentsQuery = $this->dbObject->prepare("SELECT COUNT(*) AS num FROM comment WHERE request = :id;");
		$commentsQuery->bindValue(":id", $this->id);

		$commentsQuery->execute();

		$this->hasComments = ($commentsQuery->fetchColumn() != 0);
		$this->hasCommentsResolved = true;

		return $this->hasComments;
	}

	/**
	 * @return string
	 */
	public function getEmailConfirm()
	{
		return $this->emailconfirm;
	}

	/**
	 * @param string $emailconfirm
	 */
	public function setEmailConfirm($emailconfirm)
	{
		$this->emailconfirm = $emailconfirm;
	}

	public function generateEmailConfirmationHash()
	{
		$this->emailconfirm = bin2hex(openssl_random_pseudo_bytes(16));
	}

	/**
	 * @return string|null
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string|null $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getClosureReason()
	{
		if ($this->status != 'Closed') {
			throw new Exception("Can't get closure reason for open request.");
		}

		$statement = $this->dbObject->prepare(<<<SQL
SELECT closes.mail_desc
FROM log
INNER JOIN closes ON log.action = closes.closes
WHERE log.objecttype = 'Request'
AND log.objectid = :requestId
AND log.action LIKE 'Closed%'
ORDER BY log.timestamp DESC
LIMIT 1;
SQL
		);

		$statement->bindValue(":requestId", $this->id);
		$statement->execute();
		$reason = $statement->fetchColumn();

		return $reason;
	}

	/**
	 * Gets a value indicating whether the request was closed as created or not.
	 */
	public function getWasCreated()
	{
		if ($this->status != 'Closed') {
			throw new Exception("Can't get closure reason for open request.");
		}

		$statement = $this->dbObject->prepare(<<<SQL
SELECT emailtemplate.oncreated, log.action
FROM log
LEFT JOIN emailtemplate ON CONCAT('Closed ', emailtemplate.id) = log.action
WHERE log.objecttype = 'Request'
AND log.objectid = :requestId
AND log.action LIKE 'Closed%'
ORDER BY log.timestamp DESC
LIMIT 1;
SQL
		);

		$statement->bindValue(":requestId", $this->id);
		$statement->execute();
		$onCreated = $statement->fetchColumn(0);
		$logAction = $statement->fetchColumn(1);
		$statement->closeCursor();

		if ($onCreated === null) {
			return $logAction === "Closed custom-y";
		}

		return (bool)$onCreated;
	}

	/**
	 * @return DateTime
	 */
	public function getClosureDate()
	{
		$logQuery = $this->dbObject->prepare(<<<SQL
SELECT timestamp FROM log
WHERE objectid = :request AND objecttype = 'Request' AND action LIKE 'Closed%'
ORDER BY timestamp DESC LIMIT 1;
SQL
		);
		$logQuery->bindValue(":request", $this->getId());
		$logQuery->execute();
		$logTime = $logQuery->fetchColumn();
		$logQuery->closeCursor();

		return new DateTime($logTime);
	}
}
