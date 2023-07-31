<?php

namespace AVZaitsev\RSSGenerator;

use AVZaitsev\RSSGenerator\ChannelInterface;
use SimpleXMLElement;

interface ItemInterface
{

    /**
     * Set item title
     * @param string $title
     * @return $this
     */
    public function title($title);

    /**
     * Set item URL
     * @param string $link
     * @return $this
     */
    public function link($link);

    /**
     * Set item description
     * @param string $description
     * @return $this
     */
    public function description($description);

    /**
     * Set item category
     * @param string $name Category name
     * @param string $domain Category URL
     * @return $this
     */
    public function category($name, $domain = null);

    /**
     * Set GUID
     * @param string $guid
     * @param bool $isPermalink
     * @return $this
     */
    public function guid($guid);

    /**
     * Set published date
     * @param int $pubDate Unix timestamp
     * @return $this
     */
    public function pubDate($pubDate);

    /**
     * Set enclosure
     * @param string $url Url to media file
     * @param int $length Length in bytes of the media file
     * @param string $type Media type, default is audio/mpeg
     * @return $this
     */
    public function enclosure($url, $length = 0, $type = 'audio/mpeg');

    /**
     * Append item to the channel
     * @param ChannelInterface $channel
     * @return $this
     */
    public function appendTo(ChannelInterface $channel);

    /**
     * Return XML object
     * @return SimpleXMLElement
     */
    public function asXML();
}
