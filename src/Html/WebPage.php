<?php

declare(strict_types=1);

namespace Html;

class WebPage
{
    use StringEscaper;

    /**
     * Texte qui sera compris entre \<head\> et \</head\>.
     */
    private string $head = '';

    /**
     * Texte qui sera compris entre \<title\> et \</title\>.
     */
    private string $title = '';

    /**
     * Texte qui sera compris entre \<body\> et \</body\>.
     */
    private string $body = '';

    /**
     * Constructeur.
     *
     * @param string $title Titre de la page
     */
    public function __construct(string $title = '')
    {
        $this->setTitle($title);
    }

    /**
     * Ajouter un contenu CSS dans $this->head.
     *
     * @param string $css Le contenu CSS à ajouter
     *
     * @see WebPage::appendToHead(string $content) : void
     */
    public function appendCss(string $css): void
    {
        $this->appendToHead(
            <<<HTML
    <style>
    {$css}
    </style>

HTML
        );
    }

    /**
     * Ajouter un contenu dans $this->head.
     *
     * @param string $content Le contenu à ajouter
     */
    public function appendToHead(string $content): void
    {
        $this->head .= $content;
    }

    /**
     * Ajouter l'URL d'un script CSS dans $this->head.
     *
     * @param string $url L'URL du script CSS
     *
     * @see WebPage::appendToHead(string $content) : void
     */
    public function appendCssUrl(string $url): void
    {
        $this->appendToHead(
            <<<HTML
    <link rel="stylesheet" href="{$url}">

HTML
        );
    }

    /**
     * Ajouter un contenu JavaScript dans $this->head.
     *
     * @param string $js Le contenu JavaScript à ajouter
     *
     * @see WebPage::appendToHead(string $content) : void
     */
    public function appendJs(string $js): void
    {
        $this->appendToHead(
            <<<HTML
    <script>
    {$js}
    </script>

HTML
        );
    }

    /**
     * Ajouter l'URL d'un script JavaScript dans $this->head.
     *
     * @param string $url L'URL du script JavaScript
     *
     * @see WebPage::appendToHead(string $content) : void
     */
    public function appendJsUrl(string $url): void
    {
        $this->appendToHead(
            <<<HTML
    <script src="{$url}"></script>

HTML
        );
    }

    /**
     * Ajouter un contenu dans $this->body.
     *
     * @param string $content Le contenu à ajouter
     */
    public function appendContent(string $content): void
    {
        $this->body .= $content;
    }

    /**
     * Produire la page Web complète.
     */
    public function toHTML(): string
    {
        return <<<HTML
    <!DOCTYPE html>
    <html lang="fr">
        <head>
        
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            
            <title>{$this->getTitle()}</title>

            
            <!-- BOX ICONS -->
            <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
            <!-- AOS LOAD ANIMATION -->
            <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
            <!-- ICONS -->
            <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
        
            <!-- PERSONAL -->
            {$this->getHead()}
    </head>
    <body>
        {$this->getBody()}
        <!-- SCRIPT -->
        <script>
            AOS.init();
        </script>
        <!-- END SCRIPT -->
    </body>
</html>
HTML;
    }

    /**
     * Retourner le contenu de $this->title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Affecter le titre de la page.
     *
     * @param string $title Le titre
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Retourner le contenu de $this->head.
     */
    public function getHead(): string
    {
        return $this->head;
    }

    /**
     * Retourner le contenu de $this->body.
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
