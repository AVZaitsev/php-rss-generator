<?php

namespace AVZaitsev\RSSGenerator;

use AVZaitsev\RSSGenerator\FeedInterface;
use AVZaitsev\RSSGenerator\ItemInterface;
use SimpleXMLElement;
use AVZaitsev\RSSGenerator\ChannelInterface;
use InvalidArgumentException;

class Channel implements ChannelInterface
{
    const GENERATOR = 'https://github.com/AVZaitsev/php-rss-generator';
    const DOCS = 'https://www.rssboard.org/rss-specification';

    /** @var string */
    protected $title;

    /** @var string */
    protected $link;

    /** @var string */
    protected $description;

    /** @var string */
    protected $language;

    /** @var string */
    protected $copyright;

    /** @var string */
    protected $managingEditor;

    /** @var string */
    protected $webMaster;

    /** @var int */
    protected $pubDate;

    /** @var int */
    protected $lastBuildDate;

    /** @var string */
    protected $category;

    /** @var object */
    protected $cloud;

    /** @var int */
    protected $ttl;

    /** @var object */
    protected $image;

    /** @var  string */
    protected $updatePeriod;

    /** @var  string */
    protected $rating;

    /** @var object */
    protected $textInput;

    /** @var int[] */
    protected $skipHours = [];

    /** @var array */
    protected $skipDays = [];

    /** @var ItemInterface[] */
    protected $items = [];

    /**
     * Set channel title
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set channel URL
     * @param string $link
     * @return $this
     */
    public function link($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Set channel description
     * @param string $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set ISO639 language code
     *
     * The language the channel is written in. This allows aggregators to group all
     * Italian language sites, for example, on a single page. A list of allowable
     * values for this element, as provided by Netscape, is here. You may also use
     * values defined by the W3C.
     *
     * @param string $language
     * @return $this
     */
    public function language($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Set channel copyright. Copyright notice for content in the channel.
     * @param string $copyright
     * @return $this
     */
    public function copyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Set channel managingEditor. Email address for person responsible for editorial content.
     * @param string $managingEditor
     * @return $this
     */
    public function managingEditor($managingEditor)
    {
        $this->managingEditor = $managingEditor;

        return $this;
    }

    /**
     * Set channel webMaster. Email address for person responsible for technical issues relating to channel.
     * @param string $webMaster
     * @return $this
     */
    public function webMaster($webMaster)
    {
        $this->webMaster = $webMaster;

        return $this;
    }

    /**
     * Set channel published date
     * @param int $pubDate Unix timestamp
     * @return $this
     */
    public function pubDate($pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * Set channel last build date
     * @param int $lastBuildDate Unix timestamp
     * @return $this
     */
    public function lastBuildDate($lastBuildDate)
    {
        $this->lastBuildDate = $lastBuildDate;

        return $this;
    }

    /**
     * Set channel category. Specify one or more categories that the channel belongs to. 
     * Follows the same rules as the <item>-level category element.
     * @param string $category Category of channel
     * @return $this
     */
    public function category($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set channel cloud
     * 
     * @param string $domain
     * @param int $port
     * @param string $path
     * @param string $registerProcedure
     * @param string $protocol
     * @return $this
     */
    public function cloud($domain, $port, $path, $registerProcedure, $protocol)
    {
        $this->cloud = (object) [];
        $this->cloud->domain = $domain;
        $this->cloud->port = $port;
        $this->cloud->path = $path;
        $this->cloud->registerProcedure = $registerProcedure;
        $this->cloud->protocol = $protocol;
        return $this;
    }

    /**
     * Set channel ttl (minutes)
     * @param int $ttl
     * @return $this
     */
    public function ttl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Set channel image
     * 
     * The url is the image URL.
     * The title is used as the alt attribute if the image is used in HTML.
     * The link should be the URL of the site.
     * 
     * @param string $url
     * @param string $title
     * @param string $link
     * @param integer $width optional, indicating the width of the image in pixels. Max 144.
     * @param integer $height optional, indicating the height of the image in pixels. Max 400.
     * @param string $description optional
     * @return $this
     * @throws InvalidArgumentException
     */
    public function image($url, $title, $link, int $width = 88, int $height = 31, $description = null)
    {
        if ($width < 1 || $width > 144) {
            throw new InvalidArgumentException('Width is out of range. Width should be from 1 to 144');
        }
        if ($height < 1 || $height > 400) {
            throw new InvalidArgumentException('Height is out of range. Height should be from 1 to 400');
        }
        $this->image = (object) [];
        $this->image->url = $url;
        $this->image->title = $title;
        $this->image->link = $link;
        $this->image->width = $width;
        $this->image->height = $height;
        $this->image->description = $description;
        return $this;
    }

    /**
     * Set channel textInput
     * The textInput element defines a form to submit a text query 
     * to the feed's publisher over the Common Gateway Interface (CGI).
     * 
     * @param string $title The label of the Submit button in the text input area.
     * @param string $link The URL of the CGI script that processes text input requests.
     * @param string $name The name of the text object in the text input area.
     * @param string $description Explains the text input area.
     * @return $this
     */
    public function textInput($title, $link, $name, $description)
    {
        $this->textInput = (object) [];
        $this->textInput->title = $title;
        $this->textInput->link = $link;
        $this->textInput->name = $name;
        $this->textInput->description = $description;
        return $this;
    }

    /**
     * Set channel skipHours. Array of numbers between 0 and 23
     * @param int[] $skipHours
     * @return $this
     * @throws InvalidArgumentException
     */
    public function skipHours($skipHours)
    {
        if (array_diff($skipHours, range(0, 23))) {
            throw new InvalidArgumentException('The array must contain integer numbers between 0 and 23');
        }
        $this->skipHours = array_unique($skipHours, SORT_NUMERIC);

        return $this;
    }


    /**
     * Set channel skipDays. Array of numbers between 0 and 23
     * @param string[] $skipDays Monday, Tuesday, Wednesday, Thursday, Friday, Saturday or Sunday
     * @return $this
     * @throws InvalidArgumentException
     */
    public function skipDays($skipDays)
    {
        $days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];
        if (array_diff($skipDays, $days)) {
            throw new InvalidArgumentException('The array must contain days of week');
        }
        $this->skipDays = array_unique($skipDays);

        return $this;
    }

    /**
     * Add item object
     * @param ItemInterface $item
     * @return $this
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Append to feed
     * @param FeedInterface $feed
     * @return $this
     */
    public function appendTo(FeedInterface $feed)
    {
        $feed->addChannel($this);

        return $this;
    }

    /**
     * Return XML object
     * @return SimpleXMLElement
     */
    public function asXML()
    {
        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8" ?><channel></channel>',
            LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL
        );
        $xml->addChild('title', $this->title);
        $xml->addChild('link', $this->link);
        $xml->addChild('description', $this->description);

        if ($this->language !== null) {
            $xml->addChild('language', $this->language);
        }

        if ($this->copyright !== null) {
            $xml->addChild('copyright', $this->copyright);
        }

        if ($this->managingEditor !== null) {
            $xml->addChild('managingEditor', $this->managingEditor);
        }

        if ($this->webMaster !== null) {
            $xml->addChild('webMaster', $this->webMaster);
        }

        if ($this->pubDate !== null) {
            $xml->addChild('pubDate', date(DATE_RSS, $this->pubDate));
        }

        if ($this->lastBuildDate !== null) {
            $xml->addChild('lastBuildDate', date(DATE_RSS, $this->lastBuildDate));
        }

        if ($this->category !== null) {
            $xml->addChild('category', date(DATE_RSS, $this->category));
        }

        $xml->addChild('generator', self::GENERATOR);
        $xml->addChild('docs', self::DOCS);

        if ($this->cloud !== null) {
            $cloud = $xml->addChild('cloud');
            $cloud->addAttribute('domain', $this->cloud->domain);
            $cloud->addAttribute('port', $this->cloud->port);
            $cloud->addAttribute('path', $this->cloud->path);
            $cloud->addAttribute('registerProcedure', $this->cloud->registerProcedure);
            $cloud->addAttribute('protocol', $this->cloud->protocol);
        }

        if ($this->ttl !== null) {
            $xml->addChild('ttl', $this->ttl);
        }

        if ($this->image !== null) {
            $image = $xml->addChild('image');
            $image->addChild('url', $this->image->url);
            $image->addChild('title', $this->image->title);
            $image->addChild('link', $this->image->link);
            if (!empty($this->image->width)) $image->addChild('width', $this->image->width);
            if (!empty($this->image->height)) $image->addChild('height', $this->image->height);
            if (!empty($this->image->description)) $image->addChild('description', $this->image->description);
        }

        if ($this->rating !== null) {
            $xml->addChild('rating', $this->rating);
        }

        if ($this->textInput !== null) {
            $textInput = $xml->addChild('textInput');
            $textInput->addChild('title', $this->textInput->title);
            $textInput->addChild('description', $this->textInput->description);
            $textInput->addChild('name', $this->textInput->name);
            $textInput->addChild('link', $this->textInput->link);
        }

        if ($this->skipHours !== null) {
            $skipHours = $xml->addChild('skipHours');
            foreach ($this->skipHours as $hour) {
                $skipHours->addChild('hour', $hour);
            }
        }

        if ($this->skipDays !== null) {
            $skipDays = $xml->addChild('skipDays');
            foreach ($this->skipDays as $day) {
                $skipDays->addChild('day', $day);
            }
        }

        foreach ($this->items as $item) {
            $toDom = dom_import_simplexml($xml);
            $fromDom = dom_import_simplexml($item->asXML());
            $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
        }

        return $xml;
    }
}
