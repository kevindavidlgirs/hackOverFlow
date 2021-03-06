<?php

class Utils{

    /*
    * Permet d'encoder une string au format base64url, c'est-à-dire un format base64 dans
    * lequel les caractères '+' et '/' sont remplacés respectivement par '-' et '_', ce qui
    * permet d'utiliser le résultat dans un URL.
    *
    * @param string $data La string à encoder.
    * @return string La string encodée.
    */
    private static function base64url_encode($data)
    {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /*
    * Permet de décoder une string encodée au format base64url.
    *
    * @param string $data La string à décoder.
    * @return string La string décodée.
    */
    private static function base64url_decode($data)
    {
    return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    /*
    * Permet d'encoder une structure de donnée (par exemple un tableau
    *associatif ou un
    * objet) au format base64url.
    *
    * @param mixed $data La structure de données à encoder.
    * @return string La string résultant de l'encodage.
    */
    public static function url_safe_encode($data)
    {
    return self::base64url_encode(gzcompress(json_encode($data), 9));
    }

    /*
    * Permet d'encoder une structure de donnée (par exemple un tableau
    *associatif ou un
    * objet) au format base64url.
    *
    * @param mixed $data La structure de données à encoder.
    * @return string La string résultant de l'encodage.
    */
    public static function url_safe_decode($data)
    {
    return json_decode(@gzuncompress(self::base64url_decode($data)), true, 512, JSON_OBJECT_AS_ARRAY);
    }

    public static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

