<?php

namespace ruslan\Emutator;

use Illuminate\Support\HtmlString;

class MailTo
{
    /**
     * Generate a HTML link to an email address.
     *
     * @param string $email
     * @param string $title
     * @param array  $attributes
     * @param bool   $escape
     *
     * @return \Illuminate\Support\HtmlString
     */
    public static function mutate($email, $title = null, $attributes = [], $escape = true)
    {
        $email = str_replace('@', '&#64;', self::obfuscate($email));

        $title = $title ?: $email;

        if ($escape) {
            $title = htmlentities($title, ENT_QUOTES, 'UTF-8', false);
        }

        $email = self::obfuscate('mailto:') . $email;

        return self::toHtmlString('<a href="' . $email . '"' . self::attributes($attributes) . '>' . $title . '</a>');
    }

    /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected static function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = self::attributeElement($key, $value);

            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected static function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }

        if (is_array($value) && $key === 'class') {
            return 'class="' . implode(' ', $value) . '"';
        }

        if (! is_null($value)) {
            return $key . '="' . e($value, false) . '"';
        }
    }

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param string $value
     *
     * @return string
     */
    public static function obfuscate($value)
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128) {
                return $letter;
            }

            // To properly obfuscate the value, we will randomly convert each letter to
            // its entity or hexadecimal representation, keeping a bot from sniffing
            // the randomly obfuscated letters out of the string on the responses.
            switch (rand(1, 3)) {
                case 1:
                    $safe .= '&#' . ord($letter) . ';';
                    break;

                case 2:
                    $safe .= '&#x' . dechex(ord($letter)) . ';';
                    break;

                case 3:
                    $safe .= $letter;
            }
        }

        return $safe;
    }
}