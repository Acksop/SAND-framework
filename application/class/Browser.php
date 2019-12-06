<?php


namespace MVC\Classe;


class Browser
{

    public $user;
    public $userAgent;

    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->user = $this->get_browser_name();
    }

    protected function get_browser_name()
    {

        // Make case insensitive.
        $t = strtolower($this->userAgent);

        // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
        // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
        //     http://php.net/manual/en/function.strpos.php
        $t = " " . $t;

        // Humans / Regular Users
        if (strpos($t, 'opera') || strpos($t, 'opr/')) return 'Opera';
        elseif (strpos($t, 'edge')) return 'Edge';
        elseif (strpos($t, 'chrome')) return 'Chrome';
        elseif (strpos($t, 'safari')) return 'Safari';
        elseif (strpos($t, 'firefox')) return 'Firefox';
        elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) return 'Internet Explorer';

        // Application Users
        elseif (strpos($t, 'curl')) return '[App] Curl';

        // Search Engines
        elseif (strpos($t, 'google')) return '[Bot] Googlebot';
        elseif (strpos($t, 'bing')) return '[Bot] Bingbot';
        elseif (strpos($t, 'slurp')) return '[Bot] Yahoo! Slurp';
        elseif (strpos($t, 'duckduckgo')) return '[Bot] DuckDuckBot';
        elseif (strpos($t, 'baidu')) return '[Bot] Baidu';
        elseif (strpos($t, 'yandex')) return '[Bot] Yandex';
        elseif (strpos($t, 'sogou')) return '[Bot] Sogou';
        elseif (strpos($t, 'exabot')) return '[Bot] Exabot';
        elseif (strpos($t, 'msn')) return '[Bot] MSN';

        // Common Tools and Bots
        elseif (strpos($t, 'mj12bot')) return '[Bot] Majestic';
        elseif (strpos($t, 'ahrefs')) return '[Bot] Ahrefs';
        elseif (strpos($t, 'semrush')) return '[Bot] SEMRush';
        elseif (strpos($t, 'rogerbot') || strpos($t, 'dotbot')) return '[Bot] Moz or OpenSiteExplorer';
        elseif (strpos($t, 'frog') || strpos($t, 'screaming')) return '[Bot] Screaming Frog';

        // Miscellaneous
        elseif (strpos($t, 'facebook')) return '[Bot] Facebook';
        elseif (strpos($t, 'pinterest')) return '[Bot] Pinterest';

        // Check for strings commonly used in bot user agents
        elseif (strpos($t, 'crawler') || strpos($t, 'api') ||
            strpos($t, 'spider') || strpos($t, 'http') ||
            strpos($t, 'bot') || strpos($t, 'archive') ||
            strpos($t, 'info') || strpos($t, 'data')) return '[Bot] Other';

        return 'Other (Unknown)';
    }

    public function isBot()
    {
        if (preg_match('#Bot#', $this->user)) {
            return true;
        } else {
            return false;
        }
    }

    // Alternative TO https://www.php.net/manual/fr/function.get-browser.php
    // Function written and tested December, 2018

    public function isAppRequest()
    {
        if (preg_match('#App#', $this->user)) {
            return true;
        } else {
            return false;
        }
    }
}