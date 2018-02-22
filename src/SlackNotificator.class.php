<?php

class SlackNotificator
{

    private static $instance = null;
    private $client = null;
    private $options = [];
    private static $LONGOPTS_REQUIRED = [
    ];
    private static $LONGOPTS_OPTIONAL = [
        'message',
        'username',
        'channel',
        'messagepath'
    ];
    private static $OPTION_REQUIRED_SEPARATOR = ':';
    private static $OPTION_OPTIONAL_SEPARATOR = '::';

    private function __construct()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/../config/');
        $dotenv->load();
        $this->client = $this->getSlackClientInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setCLIOptions()
    {
        $options = getopt('', self::getLongopts());
        foreach (array_merge(self::$LONGOPTS_REQUIRED, self::$LONGOPTS_OPTIONAL) as $option) {
            if ($this->hasOption($option, $options)) {
                $this->setOption($option, $options[$option]);
            }
        }
        return self::$instance;
    }

    public function username($value)
    {
        $this->setOption(__FUNCTION__, $value);
        return self::$instance();
    }

    public function channel($value)
    {
        $this->setOption(__FUNCTION__, $value);
        return self::$instance();
    }

    public function message($value)
    {
        $this->setOption(__FUNCTION__, $value);
        return self::$instance();
    }

    public function messagepath($value)
    {
        $this->setOption(__FUNCTION__, $value);
        return self::$instance();
    }

    public function send($message = '')
    {
        $this->setOption('message', $message);
        if (isset($this->options['messagepath']) && is_file($this->options['messagepath'])) {
            $this->setOption('message', file_get_contents($this->options['messagepath']));
        }
        if (!isset($this->options['message']) || $this->options['message'] === '') {
            echo "--message option is required.\n";
            exit(1);
        }
        $this->client
            ->enableMarkdown()
            ->from($this->options['username'])
            ->to($this->options['channel'])
            ->send($this->options['message']);
    }

    private function getSlackClientInstance()
    {
        $this->options = [
            'username' => getenv('DEFAULT_USERNAME'),
            'channel' => getenv('DEFAULT_CHANNEL'),
        ];
        $settings = [
            'link_names' => true
        ];
        return new Maknz\Slack\Client(getenv('DEFAULT_WEBHOOK_URL'), array_merge($this->options, $settings));
    }

    private static function getLongopts()
    {
        $longopts = [];
        foreach (self::$LONGOPTS_REQUIRED as $longopt) {
            $longopts[] = $longopt . self::$OPTION_REQUIRED_SEPARATOR;
        }
        foreach (self::$LONGOPTS_OPTIONAL as $longopt) {
            $longopts[] = $longopt . self::$OPTION_OPTIONAL_SEPARATOR;
        }
        return $longopts;
    }

    private function hasOption($key, array $options)
    {
        return (isset($options[$key]) && $this->isValidOption($options[$key]));
    }

    private function isValidOption($option)
    {
        return (is_string($option) && $option !== '');
    }

    private function setOption($key, $value)
    {
        if ($this->isValidOption($value)) {
            $this->options[$key] = $value;
        }
    }
}
