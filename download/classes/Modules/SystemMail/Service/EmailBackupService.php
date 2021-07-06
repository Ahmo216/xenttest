<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Service;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Xentral\Components\Database\Database;
use Xentral\Modules\SystemMail\Data\EmailBackupMessage;
use Xentral\Modules\SystemMail\Exception\EmailBackupNotFoundException;

class EmailBackupService
{
    /** @var Database */
    private $db;

    /**
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $hash
     * @param int    $emailAccountId
     *
     * @return EmailBackupMessage|null
     */
    public function findByHash(string $hash, int $emailAccountId): ?EmailBackupMessage
    {
        $sql =
            'SELECT * 
            FROM `emailbackup_mails` AS `em`
            WHERE em.checksum = :hash
            AND em.webmail = :email_account_id';

        $row = $this->db->fetchRow(
            $sql,
            [
                'hash' => $hash,
                'email_account_id' => $emailAccountId,
            ]
        );
        if (empty($row)) {
            return null;
        }

        return EmailBackupMessage::fromDbState($row);
    }

    /**
     * @param int $backupId
     *
     * @throws EmailBackupNotFoundException
     *
     * @return EmailBackupMessage
     */
    public function getById(int $backupId): EmailBackupMessage
    {
        $sql =
            'SELECT * 
            FROM `emailbackup_mails` AS `em`
            WHERE em.id = :id';

        $row = $this->db->fetchRow(
            $sql,
            [
                'id' => $backupId,
            ]
        );
        if (empty($row)) {
            throw new EmailBackupNotFoundException(
                "The email backup with the id {$backupId} does not exist."
            );
        }

        return EmailBackupMessage::fromDbState($row);
    }

    /**
     * @param int                    $emailAccountId
     * @param DateTimeInterface      $begin
     * @param DateTimeInterface|null $end
     *
     * @return EmailBackupMessage[]
     */
    public function findMessagesInTimePeriod(
        int $emailAccountId,
        DateTimeInterface $begin,
        ?DateTimeInterface $end = null
    ): array {
        $sql =
            'SELECT * 
            FROM `emailbackup_mails` AS `em`
            WHERE em.webmail = :email_account_id
            AND em.empfang >= :begin
            ';

        $params = [
            'email_account_id' => $emailAccountId,
            'begin' => $begin->format('Y-m-d H:i:s'),
        ];

        if (!empty($end)) {
            $sql .= 'AND em.empfang <= :end';
            $params['end'] = $end->format('Y-m-d H:i:s');
        }

        $rows = $this->db->fetchAll(
            $sql,
            $params
        );

        if (empty($rows)) {
            return [];
        }

        $messages = [];
        foreach ($rows as $row) {
            $messages[] = EmailBackupMessage::fromDbState($row);
        }

        return $messages;
    }

    /**
     * @param EmailBackupMessage $message
     *
     * @throws EmailBackupNotFoundException
     */
    public function update(EmailBackupMessage $message): void
    {
        $id = $message->getId();
        if (empty($id)) {
            throw new EmailBackupNotFoundException(
                "The email with the id: {$id} does not exist."
            );
        }

        $sql =
            'UPDATE `emailbackup_mails` SET 
            `webmail` = :webmail,
            `subject` = :subject,
            `sender` = :sender,
            `action` = :action,
            `action_html` = :action_html,
            `empfang` = :empfang,
            `anhang` = :anhang,
            `gelesen` = :gelesen,
            `checksum` = :checksum,
            `adresse` = :adresse,
            `spam` = :spam,
            `antworten` = :antworten,
            `geloescht` = :geloescht,
            `warteschlange` = :warteschlange,
            `projekt` = :projekt,
            `ticketnachricht` = :ticketnachricht,
            `mail_replyto` = :mail_replyto,
            `verfasser_replyto` = :verfasser_replyto
            WHERE `id` = :id
            ';

        $params = $this->createQueryParamsFromEmailBackupMessage($message);
        $params['id'] = $message->getId();
        $this->db->perform($sql, $params);
    }

    /**
     * @param int $emailAccountId
     *
     * @return DateTimeInterface|null
     */
    public function findLatestImportDateByAccountId(int $emailAccountId): ?DateTimeInterface
    {
        $sql =
            'SELECT em.empfang
            FROM `emailbackup_mails` AS `em`
            WHERE em.webmail = :email_account_id
            ORDER BY em.empfang DESC
            LIMIT 1';

        $row = $this->db->fetchRow($sql, ['email_account_id' => $emailAccountId]);
        if (empty($row)) {
            return null;
        }

        try {
            $latestDate = new DateTimeImmutable($row['empfang']);
        } catch (Exception $e) {
            return null;
        }

        return $latestDate;
    }

    /**
     * @param array $checksums
     * @param int   $emailAccountId
     *
     * @return int[] ['<checksum>' => <email_backup_id>, ...]
     */
    public function getChecksumIdMap(array $checksums, int $emailAccountId): array
    {
        $hashesImploded = "'" . implode("','", $checksums) . "'";

        $sql =
            "SELECT em.id, em.checksum
            FROM `emailbackup_mails` AS `em`
            WHERE em.webmail = :email_account_id
            AND em.checksum IN ({$hashesImploded})";

        $rows = $this->db->fetchAll(
            $sql,
            [
                'email_account_id' => $emailAccountId,
            ]
        );

        if (empty($rows)) {
            return [];
        }

        $return = [];
        foreach ($rows as $row) {
            $return[$row['checksum']] = $row['id'];
        }

        return $return;
    }

    /**
     * @param int $id
     */
    public function setDeleted(int $id): void
    {
        $sql =
            'UPDATE `emailbackup_mails` SET
            geloescht = 1
            WHERE id = :id';

        $this->db->perform($sql, ['id' => $id]);
    }

    /**
     * @param EmailBackupMessage $message
     *
     * @throws UnableToSaveEmailBackupException
     *
     * @return int
     */
    public function insert(EmailBackupMessage $message): int
    {
        $sql =
            'INSERT INTO `emailbackup_mails` (
                `webmail`,
                `subject`,
                `sender`,
                `action`,
                `action_html`,
                `empfang`,
                `anhang`,
                `gelesen`,
                `checksum`,
                `adresse`,
                `spam`,
                `antworten`,
                `geloescht`,
                `warteschlange`,
                `projekt`,
                `ticketnachricht`,
                `mail_replyto`,
                `verfasser_replyto`
            ) VALUES (
                :webmail,
                :subject,
                :sender,
                :action,
                :action_html,
                :empfang,
                :anhang,
                :gelesen,
                :checksum,
                :adresse,
                :spam,
                :antworten,
                :geloescht,
                :warteschlange,
                :projekt,
                :ticketnachricht,
                :mail_replyto,
                :verfasser_replyto
            )';

        $this->db->perform(
            $sql,
            $this->createQueryParamsFromEmailBackupMessage($message)
        );

        $insertId = $this->db->lastInsertId();
        if ($insertId === 0) {
            throw new UnableToSaveEmailBackupException(
                "Email {$message->getSubject()} could not be saved."
            );
        }

        return $insertId;
    }

    /**
     * @param int $emailBackupId
     */
    public function delete(int $emailBackupId): void
    {
        $sql =
            'DELETE FROM `emailbackup_mails` WHERE id = :email_backup_id';

        $this->db->perform($sql, ['email_backup_id' => $emailBackupId]);
    }

    /**
     * @param EmailBackupMessage $message
     *
     * @return array
     */
    private function createQueryParamsFromEmailBackupMessage(EmailBackupMessage $message): array
    {
        return [
            'webmail' => $message->getEmailAccountId(),
            'subject' => $message->getSubject(),
            'sender' => $message->getSender()->getEmail(),
            'action' => $message->getPlainTextBody(),
            'action_html' => $message->getHtmlBody(),
            'empfang' => $message->getDateReceived()->format('Y-m-d H:i:s'),
            'gelesen' => $message->isMarkedRead(),
            'checksum' => $message->getChecksum(),
            'adresse' => $message->getAddressId(),
            'spam' => $message->isSpam(),
            'warteschlange' => $message->isQueued(),
            'projekt' => $message->getProjectId(),
            'ticketnachricht' => $message->getTicketMessageId(),
            'antworten' => $message->isMarkedReply(),
            'geloescht' => $message->isDeleted(),
            'mail_replyto' => $message->getReplyTo()->getEmail(),
            'verfasser_replyto' => $message->getReplyTo()->getName(),
            'anhang' => $message->hasAttachment(),
        ];
    }
}
