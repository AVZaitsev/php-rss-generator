<?php

namespace AVZaitsev\RSSGenerator;

use AVZaitsev\RSSGenerator\ChannelInterface;

interface FeedInterface
{

    /**
     * Add channel
     * @param ChannelInterface $channel
     * @return $this
     */
    public function addChannel(ChannelInterface $channel);

    /**
     * Render XML
     * @return string
     */
    public function render();

    /**
     * Render XML
     * @return string
     */
    public function __toString();
}
