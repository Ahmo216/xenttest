<?php

namespace Xentral\Modules\SystemNotification\Data;

use Xentral\Modules\SystemNotification\Exception\InvalidArgumentException;
use Xentral\Modules\SystemNotification\Service\NotificationServiceInterface;

final class NotificationMessage
{
    /** @var array $validMessageTypes */
    private static $validMessageTypes = [
        NotificationServiceInterface::TYPE_DEFAULT,
        NotificationServiceInterface::TYPE_NOTICE,
        NotificationServiceInterface::TYPE_SUCESS,
        NotificationServiceInterface::TYPE_WARNING,
        NotificationServiceInterface::TYPE_ERROR,
        NotificationServiceInterface::TYPE_PUSH,
    ];

    /** @var string $type */
    private $type;

    /** @var string $title */
    private $title;

    /** @var string|null $message */
    private $message;

    /** @var bool $priority */
    private $priority;

    /** @var array $options */
    private $options = [];

    /** @var array $tags */
    private $tags = [];

    /** @var int $id */
    private $id;

    /**
     * @param string      $type
     * @param string      $title
     * @param string|null $message
     * @param bool        $priority
     * @param int         $id
     */
    public function __construct($type, $title, $message = null, $priority = false, $id = 0)
    {
        if (!in_array($type, self::$validMessageTypes, true)) {
            throw new InvalidArgumentException('Message type "%s" is invalid.');
        }
        if (empty($title)) {
            throw new InvalidArgumentException('Title is empty.');
        }
        if (mb_strlen($title) > 64) {
            throw new InvalidArgumentException("Message title \"{$title}\" is longer than 64 characters.");
        }

        $this->type = (string)$type;
        $this->title = (string)$title;
        $this->id = (int)$id;

        $this->setMessage($message);
        $this->setPriority($priority);
    }

    /**
     * @param array $data
     *
     * @throws InvalidArgumentException
     *
     * @return static
     */
    public static function fromDbState(array $data): self
    {
        if (!isset($data['type'], $data['title'], $data['priority'], $data['id'])) {
            throw new InvalidArgumentException('invalid database state');
        }
        $object = new self(
            $data['type'],
            $data['title'],
            $data['message'] ?? null,
            $data['priority'] === 1
        );
        if (isset($data['options_json'])) {
            $options = json_decode($data['options_json'], true);
            $object->setOptions( is_array($options) ? $options : []);
        }
        if (isset($data['tags'])) {
            $tags = self::transformTagsStringToArray($data['tags']);
            $object->addTags($tags);
        }
        $object->id = (int)$data['id'];

        return $object;
    }

    /**
     * @param string      $text
     * @param string      $link
     * @param string|null $htmlId Html id attribute (<button id="{$htmlId}">)
     *
     * @return void
     */
    public function addButton($text, $link, $htmlId = null)
    {
        if (!isset($this->options['buttons'])) {
            $this->options['buttons'] = [];
        }

        $this->options['buttons'][] = [
            'text' => $text,
            'link' => $link,
            'id'   => !empty($htmlId) ? $htmlId : null,
        ];
    }

    /**
     * @param string $tag
     *
     * @return void
     */
    public function addTag($tag)
    {
        $this->tags[] = (string)$tag;
    }

    /**
     * @param array $tags
     *
     * @return void
     */
    public function addTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isPriority()
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string|null $message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = !empty($message) ? (string)$message : null;
    }

    /**
     * @param bool $priority
     *
     * @return void
     */
    public function setPriority($priority)
    {
        $this->priority = (bool)$priority;
    }

    /**
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     */
    public function setOption($property, $value)
    {
        $this->options[(string)$property] = $value;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    private static function transformTagsStringToArray(string $string): array
    {
        $tags = [];
        $parts = explode('|', $string);
        foreach ($parts as $tag) {
            $tag = self::normalizeTag($tag);
            if (!empty($tag)) {
                $tags[] = $tag;
            }
        }

        sort($tags);
        $tags = array_unique($tags);

        return $tags;
    }

    /**
     * @param string $tag
     *
     * @return string
     */
    private static function normalizeTag(string $tag): string
    {
        $tag = strtolower(trim($tag));
        $tag = preg_replace('/[^a-z0-9\-]/', '', $tag); // Remove invalid chars
        $tag = preg_replace('/[-]+/', '-', $tag); // Replace multiple dashes

        return $tag;
    }
}
