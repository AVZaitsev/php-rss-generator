<?php

namespace AVZaitsev\RSSGenerator;

use AVZaitsev\RSSGenerator\ItemInterface;
use AVZaitsev\RSSGenerator\ChannelInterface;
use SimpleXMLElement;

class Item implements ItemInterface
{

    /** @var string */
    protected $title;

    /** @var string */
    protected $link;

    /** @var string */
    protected $description;

    /** @var string */
    protected $author;

    /** @var array */
    protected $categories = [];

    /** @var string */
    protected $comments;

    /** @var array */
    protected $enclosure;

    /** @var string */
    protected $guid;

    /** @var int */
    protected $pubDate;

    /** @var array */
    protected $source;

    /**
     * Set item title
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set item URL
     * @param string $link
     * @return $this
     */
    public function link($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Set item description
     * @param string $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set item author
     * @param string $author
     * @return $this
     */
    public function author($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Set item category
     * @param string $name Category name
     * @param string $domain Category URL
     * @return $this
     */
    public function category($name, $domain = null)
    {
        $this->categories[] = [$name, $domain];

        return $this;
    }

    /**
     * Set item comments URL
     * @param string $url
     * @return $this
     */
    public function comments($url)
    {
        $this->comments = $url;

        return $this;
    }

    /**
     * Set enclosure
     * @param string $url Url to media file
     * @param int $length Length in bytes of the media file
     * @param string $type Media type, default is audio/mpeg
     * @return $this
     */
    public function enclosure($url, $length = 0, $type = 'audio/mpeg')
    {
        $this->enclosure = ['url' => $url, 'length' => $length, 'type' => $type];

        return $this;
    }

    /**
     * Set GUID
     * @param string $guid
     * @return $this
     */
    public function guid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Set published date
     * @param int $pubDate Unix timestamp
     * @return $this
     */
    public function pubDate($pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * Set item source
     * @param string $name parent channel name
     * @param string $url URL of feed
     * @return $this
     */
    public function source($name, $url)
    {
        $this->source = [$name, $url];

        return $this;
    }

    /**
     * Append item to the channel
     * @param ChannelInterface $channel
     * @return $this
     */
    public function appendTo(ChannelInterface $channel)
    {
        $channel->addItem($this);

        return $this;
    }

    /**
     * Return XML object
     * @return SimpleXMLElement
     */
    public function asXML()
    {
        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8" ?><item></item>',
            LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL
        );
        $xml->addChild('title', $this->title);
        $xml->addChild('link', $this->link);
        $xml->addChild('description', $this->description);

        if ($this->author !== null) {
            $xml->addChild('author', $this->author);
        }

        foreach ($this->categories as $category) {
            $element = $xml->addChild('category', $category[0]);

            if (isset($category[1])) {
                $element->addAttribute('domain', $category[1]);
            }
        }

        if ($this->comments) {
            $xml->addChild("comments", $this->comments);
        }

        if (is_array($this->enclosure) && (count($this->enclosure) == 3)) {
            $element = $xml->addChild('enclosure');
            $element->addAttribute('url', $this->enclosure['url']);
            $element->addAttribute('type', $this->enclosure['type']);

            if ($this->enclosure['length']) {
                $element->addAttribute('length', $this->enclosure['length']);
            }
        }

        if ($this->guid) {
            $xml->addChild('guid', $this->guid);
        }

        if ($this->pubDate !== null) {
            $xml->addChild('pubDate', date(DATE_RSS, $this->pubDate));
        }

        if (is_array($this->source) && (count($this->source) == 2)) {
            $element = $xml->addChild('source', $this->source[0]);
            $element->addAttribute('url', $this->source[1]);
        }

        return $xml;
    }
}
