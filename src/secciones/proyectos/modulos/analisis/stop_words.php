<?php

include ($_SERVER ['DOCUMENT_ROOT'].'/control/seguridad.php');
function elimina_stopWords($palabras) {
    $stopWords = array('(', ')', '/', '.', ',', 'a', 'aca', 'ahi', 'ajena', 'ajenas', 'ajeno', 'ajenos'
        , 'al', 'algo', 'alguna', 'algunas', 'alguno', 'algunos', 'algun', 'alli'
        , 'alla', 'alli', 'ambos', 'ampleamos', 'ante', 'antes', 'aquel', 'aquella'
        , 'aquellas', 'aquello', 'aquellos', 'aqui', 'aqui', 'arriba', 'asi', 'atras'
        , 'aun', 'aunque', 'bajo', 'bastante', 'bien', 'cabe', 'cada', 'casi', 'cierta'
        , 'ciertas', 'cierto', 'ciertos', 'como', 'con', 'conmigo', 'conseguimos', 'conseguir'
        , 'consigo', 'consigue', 'consiguen', 'consigues', 'contigo', 'contra', 'cual', 'cuales'
        , 'cualquier', 'cualquiera', 'cualquieras', 'cuan', 'cuando', 'cuanta', 'cuantas', 'cuanto'
        , 'cuantos', 'cuan', 'cuanta', 'cuantas', 'cuanto', 'cuantos', 'como', 'de', 'dejar', 'del', 'demas'
        , 'demasiada', 'demasiadas', 'demasiado', 'demasiados', 'demas', 'dentro', 'desde', 'donde', 'dos'
        , 'el', 'ella', 'ellas', 'ello', 'ellos', 'empleais', 'emplean', 'emplear', 'empleas', 'empleo', 'en'
        , 'encima', 'entonces', 'entre', 'era', 'eramos', 'eran', 'eras', 'eres', 'es', 'esa', 'esas', 'ese', 'eso'
        , 'esos', 'esta', 'estaba', 'estado', 'estais', 'estamos', 'estan', 'estar', 'estas', 'este', 'esto'
        , 'estos', 'estoy', 'etc', 'fin', 'fue', 'fueron', 'fui', 'fuimos', 'ha', 'hace', 'haceis', 'hacemos'
        , 'hacen', 'hacer', 'haces', 'hacia', 'hago', 'hasta', 'incluso', 'intenta', 'intentais', 'intentamos'
        , 'intentan', 'intentar', 'intentas', 'intento', 'ir', 'jamas', 'junto', 'juntos', 'la', 'largo', 'las'
        , 'lo', 'los', 'mas', 'me', 'menos', 'mi', 'mia', 'mias', 'mientras', 'mio', 'mios', 'mis', 'misma', 'mismas'
        , 'mismo', 'mismos', 'modo', 'mucha', 'muchas', 'mucho', 'muchos', 'muchisima', 'muchisimas', 'muchisimo'
        , 'muchisimos', 'muy', 'mas', 'mia', 'mio', 'nada', 'ni', 'ningun', 'ninguna', 'ningunas', 'ninguno', 'ningunos'
        , 'no', 'nos', 'nosotras', 'nosotros', 'nuestra', 'nuestras', 'nuestro', 'nuestros', 'nunca', 'os', 'otra', 'otras'
        , 'otro', 'otros', 'para', 'parecer', 'pero', 'poca', 'pocas', 'poco', 'pocos', 'podeis', 'podemos', 'poder', 'podria'
        , 'podriais', 'podriamos', 'podrian', 'podrias', 'por', 'porque', 'primero', 'puede', 'pueden', 'puedo', 'pues', 'que'
        , 'querer', 'quien', 'quienes', 'quienesquiera', 'quienquiera', 'quiza', 'quizas', 'quien', 'que', 'sabe', 'sabeis'
        , 'sabemos', 'saben', 'saber', 'sabes', 'se',  'ser', 'si', 'siempre', 'siendo', 'sin', 'sino', 'so', 'sobre'
        , 'sois', 'solamente', 'solo', 'somos', 'soy', 'sr', 'sra', 'sres', 'sta', 'su', 'sus', 'suya', 'suyas', 'suyo', 'suyos'
        , 'si', 'sin', 'tal', 'tales', 'tambien', 'tambien', 'tampoco', 'tan', 'tanta', 'tantas', 'tanto', 'tantos', 'te', 'teneis'
        , 'tenemos', 'tener', 'tengo', 'ti', 'tiempo', 'tiene', 'tienen', 'toda', 'todas', 'todo', 'todos', 'tomar', 'trabaja'
        , 'trabajais', 'trabajamos', 'trabajan', 'trabajar', 'trabajas', 'tras', 'tu', 'tus', 'tuya', 'tuyo', 'tuyos'
        , 'tu', 'un', 'una', 'unas', 'uno', 'unos', 'usted', 'ustedes', 'va', 'vais', 'valor', 'vamos', 'van', 'varias', 'varios'
        , 'vaya', 'verdad', 'verdadera', 'vosotras', 'vosotros', 'voy', 'vuestra', 'vuestras', 'vuestro', 'vuestros', 'y', 'ya', 'yo', 'el','segun');


    foreach ($palabras as $k=>$p) {
        if (array_search(trim($p), $stopWords) !== false || strlen(trim($p)) < 3) {
            $palabras[$k] = '';
        }
    }
    
    return $palabras;
}
