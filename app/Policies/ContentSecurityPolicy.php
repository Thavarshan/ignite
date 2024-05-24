<?php

namespace App\Policies;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Env;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic as BasicCsp;

class ContentSecurityPolicy extends BasicCsp
{
    /**
     * Create a new policy instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(
        protected Application $app
    ) {
    }

    /**
     * Configure the default CSP policy for the application.
     *
     * @return void
     */
    public function configure(): void
    {
        parent::configure();

        $this->addDirective(Directive::SCRIPT, 'www.google.com')
            ->addDirective(Directive::STYLE, 'fonts.googleapis.com')
            ->addDirective(Directive::STYLE_ELEM, 'fonts.googleapis.com')
            ->addDirective(Directive::FONT, 'fonts.gstatic.com')
            ->addDirective(Directive::IMG, 'ui-avatars.com')
            ->addDirective(Directive::IMG, 'picsum.photos')
            ->addDirective(Directive::IMG, 'fastly.picsum.photos')
            ->addDirective(Directive::FONT, Keyword::SELF)
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_EVAL)
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::STYLE_ELEM, 'data:')
            ->addDirective(Directive::FONT, 'data:')
            ->addDirective(Directive::IMG, 'data:')
            ->addDirective(Directive::IMG, '*.tile.osm.org')
            ->addDirective(Directive::IMG, sprintf(
                'https://%s.s3.%s.amazonaws.com',
                Env::get('AWS_BUCKET'),
                Env::get('AWS_DEFAULT_REGION', 'eu-west-2')
            ));

        if ($this->app->isLocal()) {
            $this->addDirective(Directive::SCRIPT_ELEM, Keyword::UNSAFE_INLINE)
                ->addDirective(Directive::SCRIPT_ELEM, Keyword::SELF)
                ->addDirective(Directive::STYLE_ELEM, Keyword::UNSAFE_INLINE)
                ->addDirective(Directive::STYLE_ELEM, Keyword::SELF)
                ->addDirective(Directive::SCRIPT_ELEM, 'http://127.0.0.1:5173')
                ->addDirective(Directive::CONNECT, Keyword::SELF)
                ->addDirective(Directive::STYLE, 'http://127.0.0.1:5173')
                ->addDirective(Directive::STYLE, 'http://localhost:8080')
                ->addDirective(Directive::CONNECT, 'http://127.0.0.1:5173')
                ->addDirective(Directive::CONNECT, 'ws://127.0.0.1:5173')
                ->addDirective(Directive::CONNECT, 'wss://127.0.0.1:5173')
                ->addDirective(Directive::SCRIPT_ELEM, 'http://localhost:8080')
                ->addDirective(Directive::CONNECT, 'ws://localhost:8080')
                ->addDirective(Directive::CONNECT, 'wss://localhost:8080')
                ->addDirective(Directive::SCRIPT_ELEM, 'minerva.test')
                ->addDirective(Directive::STYLE, 'minerva.test')
                ->addDirective(Directive::CONNECT, 'minerva.test');
        }
    }
}
