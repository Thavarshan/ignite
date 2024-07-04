<?php

namespace Ignite\Discussions\Support;

use HTMLPurifier;
use HTMLPurifier_Config;
use Ignite\Interfaces\Discussions\Body as BodyInterface;

class Body implements BodyInterface
{
    /**
     * The body of the reply.
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Create a new instance of the body.
     *
     * @param string $content
     *
     * @return void
     */
    public function __construct(string $content)
    {
        $this->content = $this->sanitise($content);
    }

    /**
     * Gets a string representation of the object
     *
     * @return string Returns the `string` representation of the object.
     */
    public function __toString(): string
    {
        return $this->getContent();
    }

    /**
     * Get the content of the reply body.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sanitise the body of the reply.
     *
     * @param string $content
     *
     * @return string
     */
    protected function sanitise(string $content): string
    {
        $content = trim($content);

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Allowed', 'b,strong,i,em,u,a[href],ul,ol,li,p,br,span');

        $purifier = new HTMLPurifier($config);

        $content = $purifier->purify($content);

        return htmlspecialchars($content, \ENT_QUOTES, 'UTF-8');
    }
}
